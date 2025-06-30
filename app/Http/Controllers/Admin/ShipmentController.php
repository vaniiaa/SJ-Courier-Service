<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use Illuminate\Http\Request;
use App\Models\DeliveryArea;
use App\Models\User;
use App\Models\TrackingHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;


class ShipmentController extends Controller
{
    /**
     * Menampilkan halaman untuk mengelola semua pengiriman.
     */
    public function index(Request $request)
    {
        // Memulai query dengan eager loading untuk mengambil data dari tabel lain secara efisien
        $query = Shipment::with(['order.sender', 'courier', 'order.payments'])
                         ->latest(); // Mengurutkan dari yang paling baru

        // Logika untuk fitur pencarian
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%') // Cari berdasarkan nomor resi
                  ->orWhereHas('order.sender', function($q_user) use ($search) {
                      $q_user->where('name', 'like', '%' . $search . '%'); // Cari berdasarkan nama pengirim
                  })
                  ->orWhereHas('order', function($q_order) use ($search) {
                      $q_order->where('receiverName', 'like', '%' . $search . '%'); // Cari berdasarkan nama penerima
                  })
                  ->orWhereHas('courier', function($q_courier) use ($search) {
                        $q_courier->where('name', 'like', '%' . $search . '%'); // Cari berdasarkan nama kurir
                  });
            });
        }

        // Mengambil data dengan paginasi (10 data per halaman)
        // withQueryString() agar filter pencarian tidak hilang saat pindah halaman
        $shipments = $query->paginate(10)->withQueryString();

        // Ambil semua area pengiriman dari database
        $deliveryAreas = DeliveryArea::orderBy('area_name')->get();

        return view('admin.kelola_pengiriman', compact('shipments', 'deliveryAreas'));
    }

    /**
     * Mengambil daftar kurir berdasarkan area_id.
     * Dipanggil oleh JavaScript (AJAX/Fetch).
     */
    public function getCouriersByArea(Request $request, $area_id)
    {
        // Cari user dengan peran role_id = 2 dan area_id yang cocok
        $couriers = User::where('role_id', 2)
            ->where('area_id', $area_id)
            ->select('user_id as id', 'name') // Hanya ambil ID dan nama
            ->get();

        return response()->json($couriers);
    }

    /**
     * Menugaskan kurir ke sebuah pengiriman.
     */
    public function assignCourier(Request $request)
    {
        $request->validate([
            'shipment_id' => 'required|exists:shipments,shipmentID',
            'kurir_id' => 'required|exists:users,user_id',
            'pickupTimestamp' => 'required|date_format:Y-m-d\TH:i', // Format timestamp pengambilan
            'noteadmin' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $shipment = Shipment::findOrFail($request->shipment_id);
            $kurir = User::findOrFail($request->kurir_id);

            // Perbarui data pengiriman
            $shipment->courierUserID = $kurir->user_id;
            $shipment->currentStatus = 'Menunggu Diambil Kurir';
            $shipment->pickupTimestamp = $request->pickupTimestamp; // Simpan timestamp pengambilan
            $shipment->noteadmin = $request->noteadmin; // Simpan catatan admin
            $shipment->save();

            // Buat entri baru di riwayat pelacakan
            TrackingHistory::create([
                'shipmentID' => $shipment->shipmentID,
                'statusDescription' => 'Kurir (' . $kurir->name . ') telah ditugaskan oleh admin.',
                'updatedByUserID' => auth()->id(),
            ]);

            DB::commit();

            return response()->json(['message' => 'Kurir ' . $kurir->name . ' berhasil ditugaskan untuk resi ' . $shipment->tracking_number]);

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Gagal menugaskan kurir: " . $e->getMessage());
            return response()->json(['message' => 'Terjadi kesalahan internal.'], 500);
        }
    }

    /**
     * Menampilkan halaman status pengiriman yang sedang berlangsung.
     */
    public function statusPengiriman(Request $request)
    {
        $search = $request->input('search');
        $query = Shipment::query();

        if ($search) {
            $query->where('tracking_number', 'like', '%' . $search . '%')
                ->orWhereHas('order.sender', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('order', function($q) use ($search) {
                    $q->where('receiverName', 'like', '%' . $search . '%');
                });
        }


        $pengiriman = $query->whereRaw('TRIM(LOWER("currentStatus")) NOT IN (?)', ['Pesanan selesai'])
                                ->latest()->paginate(10);

        return view('admin.status_pengiriman', compact('pengiriman'));
    }
    /**
     * Menampilkan halaman riwayat pengiriman yang sudah selesai.
     */

    public function historyPengiriman(Request $request)
    {
        $search = $request->input('search');
        $query = Shipment::query();

        if ($search) {
            $query->where('tracking_number', 'like', '%' . $search . '%')
                ->orWhereHas('order.sender', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('order', function($q) use ($search) {
                    $q->where('receiverName', 'like', '%' . $search . '%');
                });
        }

        $pengiriman = $query->whereRaw('TRIM(LOWER("currentStatus")) NOT IN (?)', ['Pesanan selesai'])
                                ->latest()->paginate(10);

        return view('admin.history_pengiriman', compact('pengiriman'));
    }

}