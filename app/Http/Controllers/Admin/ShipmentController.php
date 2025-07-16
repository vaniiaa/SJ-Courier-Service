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
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf; //untuk membuat file PDF menggunakan dompdf


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
        'pickupTimestamp' => 'required|date_format:Y-m-d\TH:i',
        'notes' => 'nullable|string|max:255', // <-- Telah diubah
    ]);

    DB::beginTransaction();
    try {
        $shipment = Shipment::findOrFail($request->shipment_id);
        $kurir = User::findOrFail($request->kurir_id);

        // Perbarui data pengiriman
        $shipment->courierUserID = $kurir->user_id;
        $shipment->currentStatus = 'Kurir Ditugaskan';
        $shipment->pickupTimestamp = $request->pickupTimestamp;
        // Mengambil dari request 'notes' dan tetap menyimpan ke kolom 'noteadmin'
        $shipment->noteadmin = $request->notes; // <-- Telah diubah
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

    /**
     * Menampilkan halaman riwayat pengiriman yang sudah selesai.
     * --- TELAH DIPERBAIKI ---
     */
    public function historyPengiriman(Request $request)
    {
        $search = $request->input('search');
        $query = Shipment::query()->with(['order.sender', 'courier', 'order.payments']);

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                    ->orWhereHas('order.sender', function($subq) use ($search) {
                        $subq->where('name', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('order', function($subq) use ($search) {
                        $subq->where('receiverName', 'like', '%' . $search . '%');
                    });
            });
        }

        // PERBAIKAN 1: Sesuaikan kapitalisasi array agar sama persis dengan yang ada di database.
        $finishedStatuses = ['Pesanan Selesai', 'Dibatalkan', 'Dikembalikan'];
        
        // PERBAIKAN 2: Gunakan whereIn langsung ke kolom 'currentStatus' tanpa DB::raw.
        $query->whereIn('currentStatus', $finishedStatuses);

        $pengiriman = $query->latest('updated_at')->paginate(10);

        return view('admin.history_pengiriman', compact('pengiriman'));
    }

     /**
    * Download Resi dalam format PDF
    */
    public function downloadResi($id)
    {
    $shipment = Shipment::findOrFail($id);
 
    // QRCODE
    $qrContent = 'https://sj-courier-service-production-3685.up.railway.app/';

    // Generate QR code dari link URL
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

        // QRCODE
        $qrContent = 'https://sj-courier-service-production-3685.up.railway.app/';

        // Generate QR code dalam format base64 PNG
        // Ukuran QR Code untuk browser print (biasanya lebih kecil karena resolusi layar)
        $qrcode = base64_encode(QrCode::format('png')->size(70)->generate($qrContent)); 

        return view('User.resi_print', compact('shipment', 'qrcode'));
    }

}