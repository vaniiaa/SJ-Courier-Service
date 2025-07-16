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
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class KelolaStatusController extends Controller
{

    // Dibuat lowercase agar konsisten dengan query DB yang menggunakan LOWER()
    private $finishedStatuses = ['Pesanan selesai', 'dibatalkan', 'dikembalikan'];
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

        $shipment = Shipment::with('order')->findOrFail($request->shipmentID);
        $kurir = Auth::user();

        // Pastikan kurir hanya bisa mengupdate pengiriman miliknya
        if ($shipment->courierUserID !== $kurir->user_id) {
            return back()->with('error', 'Anda tidak berhak memperbarui pengiriman ini.');
        }

        $status = strtolower(trim($request->currentStatus));
        $deskripsiRiwayat = ''; // Variabel untuk menyimpan deskripsi riwayat
        
        // Menggunakan switch untuk logika yang lebih terstruktur
        switch ($status) {
            case 'kurir menuju lokasi penjemputan':
                $shipment->currentStatus = 'Kurir Menuju Lokasi Penjemputan';
                $deskripsiRiwayat = 'Kurir sedang dalam perjalanan untuk mengambil paket.';
                break;

            case 'paket telah di-pickup':
                $shipment->currentStatus = 'Paket Telah Di-pickup';
                $shipment->pickupTimestamp = now(); // Set waktu pickup secara otomatis
                $deskripsiRiwayat = 'Paket telah berhasil dijemput dari pengirim.';
                break;

            case 'dalam perjalanan ke penerima':
                $shipment->currentStatus = 'Dalam Perjalanan ke Penerima';
                $deskripsiRiwayat = 'Paket sedang diantar menuju alamat penerima.';
                break;

            case 'pesanan selesai':
                // Validasi bukti pengiriman jika statusnya 'pesanan selesai'
                $request->validate(['delivery_proof' => 'required|file|mimes:jpg,jpeg,png,heic|max:2048']);
                
                $shipment->currentStatus = 'Pesanan Selesai';
                $shipment->deliveredTimestamp = now(); // Set waktu pengiriman selesai
                $deskripsiRiwayat = 'Paket telah berhasil diterima oleh ' . $shipment->order->receiverName . '.';
                
                if ($request->hasFile('delivery_proof')) {
                    $path = $request->file('delivery_proof')->store('delivery_proof', 'public');
                    $shipment->delivery_proof = $path;
                }
                break;

            default:
                return back()->with('error', 'Aksi status tidak valid.');
        }

        // Simpan semua perubahan pada shipment
        $shipment->save();
        
        // Buat entri baru di tabel tracking_histories dengan deskripsi yang lebih baik
        TrackingHistory::create([
            'shipmentID' => $shipment->shipmentID,
            'statusDescription' => $deskripsiRiwayat,
            'updatedByUserID' => $kurir->user_id,
        ]);

        $successMessage = 'Status untuk resi ' . $shipment->tracking_number . ' berhasil diperbarui.';

        // Jika status pengiriman adalah "pesanan selesai", arahkan ke halaman history
        if ($status === 'pesanan selesai') {
            return redirect()->route('kurir.history_pengiriman_kurir')->with('success', $successMessage);
        }

        return redirect()->route('kurir.kelola_status')->with('success', $successMessage);
    }


    /**
     * Menampilkan riwayat pengiriman yang sudah selesai untuk kurir yang login.
     */
    /**
     * Menampilkan riwayat pengiriman yang sudah selesai untuk kurir yang login.
     * --- TELAH DIPERBAIKI ---
     */
    public function history(Request $request)
    {
        $kurirId = Auth::id();
        $search = $request->input('search');

        // PENYESUAIAN 1: Buat array status dengan kapitalisasi yang benar
        // sesuaikan dengan yang tersimpan di database Anda.
        $finishedStatuses = ['Pesanan Selesai', 'Dibatalkan', 'Dikembalikan'];

        $query = Shipment::with(['order.sender', 'courier', 'order.payments'])
            ->where('courierUserID', $kurirId)
            // PENYESUAIAN 2: Gunakan whereIn langsung tanpa DB::raw
            ->whereIn('currentStatus', $finishedStatuses);

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
    public function downloadResi($id)
{
    $shipment = Shipment::findOrFail($id);

    // Buat isi QR Code berupa URL Google Drive
    $qrContent = 'https://sj-courier-service-production-3685.up.railway.app/';

    // Generate QR code dari link URL, bukan dari tracking_number
    $qrcode = base64_encode(QrCode::format('png')->size(150)->generate($qrContent));

    $pdf = PDF::loadView('kurir.resi_pdf', compact('shipment', 'qrcode'))
             ->setPaper([0, 0, 283.46, 340.157]); // Ukuran resi 10x12 cm
             
    return $pdf->download('resi_' . $shipment->tracking_number . '.pdf');
}

/**
 * Menampilkan preview resi pengiriman dalam bentuk halaman (tanpa download).
 */
public function printResi($id)
    {
        $shipment = Shipment::findOrFail($id);

        // Buat isi QR Code (contoh: link tracking atau data resi)
        $qrContent = 'https://sj-courier-service-production-3685.up.railway.app/';

        // Generate QR code dalam format base64 PNG
        // Ukuran QR Code untuk browser print (biasanya lebih kecil karena resolusi layar)
        $qrcode = base64_encode(QrCode::format('png')->size(70)->generate($qrContent)); // <-- Ukuran ini cocokkan dengan CSS resi_print

        // GANTI INI KE NAMA VIEW BARU: kurir.resi_print
        return view('kurir.resi_print', compact('shipment', 'qrcode'));
    }
}