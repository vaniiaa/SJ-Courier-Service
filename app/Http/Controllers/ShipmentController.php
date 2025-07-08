<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Services\PricingService;
use App\Services\MidtransService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Payment;
use App\Models\TrackingHistory;
use App\Models\SavedAddress;
use Exception;
use Illuminate\Support\Facades\Log;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Barryvdh\DomPDF\Facade\Pdf; //untuk membuat file PDF menggunakan dompdf

class ShipmentController extends Controller
{
    protected PricingService $pricingService;
    protected MidtransService $midtransService;

    public function __construct(PricingService $pricingService, MidtransService $midtransService)
    {
        $this->pricingService = $pricingService;
        $this->midtransService = $midtransService;
        $this->middleware('auth');
    }

    /**
     * LANGKAH 1: Menampilkan form utama pengiriman.
     */
    public function createStep1()
    {
        // Mengambil data alamat tersimpan jika ada (fitur masa depan)
        $savedAddresses = SavedAddress::where('user_id', Auth::id())->get();
        return view('User.create', compact('savedAddresses'));
    }

    /**
     * LANGKAH 1: Memproses dan menyimpan data dari form utama ke session.
     */
    public function storeStep1(StoreShipmentRequest $request)
    {
        $validatedData = $request->validated();

        try {
            // Hitung Jarak dan Harga
            $distance = $this->pricingService->calculateDistance(
                (float)$validatedData['pickupLatitude'], (float)$validatedData['pickupLongitude'],
                (float)$validatedData['receiverLatitude'], (float)$validatedData['receiverLongitude']
            );
            $estimatedPrice = $this->pricingService->calculateEstimatedPrice(
                (float)$validatedData['weightKG'], $distance
            );

            // Simpan semua data yang diperlukan ke dalam session
            $shipmentData = array_merge($validatedData, [
                'estimatedDistanceKM' => $distance,
                'estimatedPrice' => $estimatedPrice,
            ]);

            $request->session()->put('shipment_data', $shipmentData);
            
            Log::info('Data Langkah 1 disimpan ke session:', $shipmentData);

            // Redirect ke halaman ringkasan (Langkah 2)
            return redirect()->route('shipments.create.step2');

        } catch (Exception $e) {
            Log::error("Error pada Langkah 1 pengiriman: " . $e->getMessage());
            return back()->withInput()->with('error', "Gagal memproses data: " . $e->getMessage());
        }
    }

    /**
     * LANGKAH 2: Menampilkan halaman ringkasan dan pembayaran.
     */
    public function createStep2(Request $request)
    {
        // Ambil data dari session
        $shipmentData = $request->session()->get('shipment_data');

        // Jika tidak ada data di session (misal user akses URL langsung), kembalikan ke langkah 1
        if (!$shipmentData) {
            return redirect()->route('shipments.create.step1')->with('error', 'Silakan isi detail pengiriman terlebih dahulu.');
        }
        
        Log::info('Menampilkan Langkah 2 dengan data session:', $shipmentData);

        return view('User.create-step-2', ['data' => $shipmentData]);
    }

    /**
     * FINAL: Memproses pesanan dari halaman ringkasan.
     */
    public function storeFinal(Request $request)
    {
        // Validasi metode pembayaran yang dipilih
        $request->validate(['paymentMethodOption' => 'required|string|in:cod,online']);
        
        // Ambil data dari session
        $shipmentData = $request->session()->get('shipment_data');
        if (!$shipmentData) {
            return redirect()->route('shipments.create.step1')->with('error', 'Sesi pengiriman telah berakhir. Silakan mulai lagi.');
        }

        DB::beginTransaction();
        try {
            // Buat Order menggunakan data dari session
            $order = Order::create([
                'senderUserID' => Auth::id(),
                'status' => 'Pending Payment',
                'orderDate' => now(),
                // Isi semua data dari $shipmentData
                'receiverName' => $shipmentData['receiverName'],
                'receiverAddress' => $shipmentData['receiverAddress'],
                'receiverPhoneNumber' => $shipmentData['receiverPhoneNumber'],
                'pickupAddress' => $shipmentData['pickupAddress'],
                'estimatedDistanceKM' => $shipmentData['estimatedDistanceKM'],
                'estimatedPrice' => $shipmentData['estimatedPrice'],
                'pickupLatitude' => $shipmentData['pickupLatitude'],
                'pickupLongitude' => $shipmentData['pickupLongitude'],
                'receiverLatitude' => $shipmentData['receiverLatitude'],
                'receiverLongitude' => $shipmentData['receiverLongitude'],
                'notes' => $shipmentData['notes'] ?? null,
            ]);

            // Hapus data dari session setelah digunakan
            $request->session()->forget('shipment_data');
            
            if ($request->input('paymentMethodOption') === 'online') {
                $snapToken = $this->midtransService->createSnapToken($order);
                DB::commit();
                // Simpan snap token ke session untuk diambil oleh halaman konfirmasi
                return redirect()->route('shipments.confirmation', ['order' => $order])->with('snap_token', $snapToken);
            }
            
            // Logika untuk COD
            $order->status = 'Processing';
            $order->save();
            
            $shipment = Shipment::create([
                'orderID' => $order->orderID,
                'itemType' => $shipmentData['itemType'],
                'weightKG' => (float)$shipmentData['weightKG'],
                'currentStatus' => 'Scheduled for Pickup',
                'finalPrice' => $shipmentData['estimatedPrice'],
            ]);

            Payment::create(['orderID' => $order->orderID, 'amount' => $shipmentData['estimatedPrice'], 'paymentMethod' => 'COD', 'status' => 'Pending']);
            TrackingHistory::create(['shipmentID' => $shipment->shipmentID, 'statusDescription' => 'Pesanan COD dibuat, menunggu penjemputan.']);

            DB::commit();
            return redirect()->route('shipments.confirmation', ['order' => $order]);

        } catch (Exception $e) {
            DB::rollBack();
            $request->session()->forget('shipment_data');
            Log::error("Gagal menyimpan pengiriman final: " . $e->getMessage());
            return redirect()->route('shipments.create.step1')->with('error', "Terjadi kesalahan: " . $e->getMessage());
        }
    }

    /**
     * Menampilkan halaman konfirmasi setelah pesanan dibuat.
     */
    public function confirmation(Request $request, $orderID)
    {
        $order = Order::with('shipment')->where('senderUserID', Auth::id())->findOrFail($orderID);

        $shipment = $order->shipment;
         if (!$shipment) {
            // Bisa jadi pembayaran online belum dikonfirmasi oleh webhook Midtrans.
            // Kita tetap tampilkan halaman konfirmasi sederhana.
            return $this->confirmation($request, $orderID);
        }

        $snapToken = $request->session()->get('snap_token'); // Ambil snap token jika ada

        return view('User.confirmation', compact('order', 'shipment', 'snapToken'));
    }

    // ... (method-method Anda yang sudah ada biarkan di atas sini) ...

    // Daftar status yang dianggap "selesai" untuk dipindahkan ke riwayat
    // Disesuaikan dengan status yang di-set oleh kurir
    private $finishedStatuses = [
        'Pesanan Selesai', 'Pesanan diterima', // <-- Status yang digunakan kurir
        'Delivered', 'Cancelled', 'Returned to Sender', 'Dibatalkan', 'Dikembalikan'
    ];

    /**
     * Menampilkan halaman "Daftar Pengiriman" (yang masih aktif) untuk customer.
     */
    public function List(Request $request)
{
    $search = $request->input('search');

    $query = \App\Models\Shipment::with(['order.sender', 'courier', 'order.payments'])
        ->whereHas('order', function ($q) {
            $q->where('senderUserID', \Illuminate\Support\Facades\Auth::id());
        })
        ->whereNotIn('currentStatus', $this->finishedStatuses);

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', '%' . $search . '%')
              ->orWhereHas('order.sender', function ($subq) use ($search) {
                  $subq->where('name', 'like', '%' . $search . '%');
              })
              ->orWhereHas('order', function ($subq) use ($search) {
                  $subq->where('receiverName', 'like', '%' . $search . '%');
              });
        });
    }

    $shipments = $query->latest()->paginate(10);

    return view('User.Daftar_Pengiriman', compact('shipments'));
}

    
    /**
     * Menampilkan halaman "History Pengiriman" (yang sudah selesai) untuk customer.
     */
    public function history(Request $request)
{
    $search = $request->input('search');

    $query = \App\Models\Shipment::with(['order.sender', 'courier', 'order.payments'])
        ->where('currentStatus', 'Pesanan selesai') // Status selesai
        ->whereHas('order', function ($query) {
            $query->where('senderUserID', \Illuminate\Support\Facades\Auth::id());
        });

    if ($search) {
        $query->where(function ($q) use ($search) {
            $q->where('tracking_number', 'like', '%' . $search . '%')
              ->orWhereHas('order', function ($subq) use ($search) {
                  $subq->where('receiverName', 'like', '%' . $search . '%');
              });
        });
    }

    $shipments = $query->latest('updated_at')->paginate(10);

    return view('User.History_Pengiriman', compact('shipments'));
}


public function downloadResi($id)
{
    $shipment = Shipment::findOrFail($id);

    // Buat isi QR Code berupa URL Google Drive
    $qrContent = 'https://sj-courier-service-production.up.railway.app/';

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
        $qrContent = 'https://sj-courier-service-production.up.railway.app/';

        // Generate QR code dalam format base64 PNG
        // Ukuran QR Code untuk browser print (biasanya lebih kecil karena resolusi layar)
        $qrcode = base64_encode(QrCode::format('png')->size(70)->generate($qrContent)); // <-- Ukuran ini cocokkan dengan CSS resi_print

        // GANTI INI KE NAMA VIEW BARU: kurir.resi_print
        return view('User.resi_print', compact('shipment', 'qrcode'));
    }


}
