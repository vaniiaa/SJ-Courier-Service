<?php

/**
 * Nama File: KelolaStatusController.php
 * Deskripsi: Controller ini menangani pengelolaan status pengiriman oleh kurir,
 * menampilkan history, dan mengunggah bukti menggunakan model Eloquent.
 * Dibuat Oleh: [Vania] - [3312301024] - Diadaptasi oleh AI
 * Tanggal: 22 Juni 2025
 */
namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB; // <-- Import DB Facade
use Barryvdh\DomPDF\Facade\Pdf;

class KelolaStatusController extends Controller
{

    // Dibuat lowercase agar konsisten dengan query DB yang menggunakan LOWER()
    private $finishedStatuses = ['pesanan selesai', 'dibatalkan', 'dikembalikan'];
    /**
     * Pastikan semua method di controller ini hanya bisa diakses oleh kurir.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Menampilkan daftar pengiriman aktif (belum selesai) untuk kurir yang login.
     */
    public function index(Request $request)
    {
        $kurirId = Auth::id();
        $search = $request->input('search');
        $finishedStatuses = $this->finishedStatuses;

        $query = Shipment::with(['order.sender'])
            ->where('courierUserID', $kurirId)
            ->whereNotIn(DB::raw('LOWER(TRIM("currentStatus"))'), $finishedStatuses);
            
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('order.sender', function($subq) use ($search) {
                      $subq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($subq) use ($search) {
                      $subq->where('receiverName', 'like', '%' . $search . '%');
                  });
            });
        }

        $shipments = $query->latest()->paginate(10);
        
        // Mengirim data ke view dengan nama variabel 'shipments'
        return view('kurir.kelola_status', compact('shipments'));
    }

    /**
     * Mengonfirmasi dan memperbarui status pengiriman.
     */
    public function konfirmasiStatus(Request $request)
    {
        $request->validate([
            'shipmentID' => 'required|exists:shipments,shipmentID',
            'currentStatus' => 'required|string',
            'delivery_proof' => 'nullable|file|mimes:jpg,jpeg,png,heic|max:2048', // max 2MB
        ]);

        $shipment = Shipment::findOrFail($request->shipmentID);
        $kurir = Auth::user();

        // Pastikan kurir hanya bisa mengupdate pengiriman miliknya
        if ($shipment->courierUserID !== $kurir->user_id) {
            return back()->with('error', 'Anda tidak berhak memperbarui pengiriman ini.');
        }

        $status = strtolower(trim($request->currentStatus));
        
        // Simpan path bukti pengiriman jika ada
        if ($request->hasFile('delivery_proof')) {
            $path = $request->file('delivery_proof')->store('delivery_proof', 'public');
            $shipment->delivery_proof = $path;
        }

        // Update status di tabel shipments (pastikan huruf kapital di awal)
        $shipment->currentStatus = ucfirst($status); // Simpan dengan huruf kapital di awal
        $shipment->save();
        
        // Buat entri baru di tabel tracking_histories
        TrackingHistory::create([
            'shipmentID' => $shipment->shipmentID,
            'statusDescription' => 'Status diperbarui menjadi: ' . ucfirst($status),
            'updatedByUserID' => $kurir->user_id,
        ]);

        $successMessage = 'Status untuk resi ' . $shipment->tracking_number . ' berhasil diperbarui.';

        // Jika status pengiriman adalah "pesanan diterima", arahkan ke halaman history
        if (in_array($status, $this->finishedStatuses)) {
            return redirect()->route('kurir.history_pengiriman_kurir')->with('success', $successMessage);
        }

        return redirect()->route('kurir.kelola_status')->with('success', $successMessage);
    }

    /**
     * Menampilkan riwayat pengiriman yang sudah selesai untuk kurir yang login.
     */
    public function history(Request $request)
    {
        $kurirId = Auth::id();
        $search = $request->input('search');

        $query = Shipment::with(['order.sender', 'courier', 'order.payments'])
            ->where('courierUserID', $kurirId)
            ->whereIn(DB::raw('LOWER(TRIM("currentStatus"))'), $this->finishedStatuses);

        if ($search) {
             $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('order.sender', function($subq) use ($search) {
                      $subq->where('name', 'like', '%' . $search . '%');
                  })
                  ->orWhereHas('order', function($subq) use ($search) {
                      $subq->where('receiverName', 'like', '%' . $search . '%');
                  });
            });
        }
        
        $shipments = $query->latest('updated_at')->paginate(10);

        return view('kurir.history_pengiriman_kurir', compact('shipments'));
    }

    // Fungsi download dan print Resi tidak perlu diubah, hanya pastikan nama variabelnya benar
    public function downloadResi($shipmentID)
    {
        $shipment = Shipment::findOrFail($shipmentID);
        if ($shipment->courierUserID !== Auth::id()) {
            return redirect()->route('kurir.kelola_status')->with('error', 'Anda tidak berhak mengunduh resi ini.');
        }
        $pdf = Pdf::loadView('kurir.resi_pdf', compact('shipment'))->setPaper(0, 0, 283.46, 425.2); // Ukuran A4 dalam mm
        return $pdf->download('resi_' . $shipment->tracking_number . '.pdf');
    }

    public function printResi($shipmentID)
    {
        $shipment = Shipment::with('order.sender')->findOrFail($shipmentID);
        if ($shipment->courierUserID !== Auth::id()) {
            return redirect()->route('kurir.kelola_status')->with('error', 'Anda tidak berhak mencetak resi ini.');
        }
        $pdf = Pdf::loadView('kurir.resi_pdf', compact('shipment'))->setPaper(0, 0, 283.46, 425.2); // Ukuran A4 dalam mm
        return $pdf->stream('resi_' . $shipment->tracking_number . '.pdf');
    }
}