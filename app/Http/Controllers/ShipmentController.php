<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreShipmentRequest;
use App\Services\PricingService;
use App\Services\MidtransService; // Tambahkan MidtransService
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Payment; // Tambahkan model Payment
use App\Models\TrackingHistory; // Tambahkan model TrackingHistory
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request; // Tambahkan untuk payment finish

class ShipmentController extends Controller
{
    protected PricingService $pricingService;
    protected MidtransService $midtransService; // Deklarasikan MidtransService

    public function __construct(PricingService $pricingService, MidtransService $midtransService)
    {
        $this->pricingService = $pricingService;
        $this->midtransService = $midtransService; // Inject MidtransService
        $this->middleware('auth');
    }

    public function create()
    {
        $midtransClientKey = config('midtrans.client_key');
        Log::info('User ' . Auth::id() . ' mengakses form pembuatan kiriman.');
        return view('shipments.create', compact('midtransClientKey'));
    }

    public function store(StoreShipmentRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        Log::info('Memproses permintaan kiriman baru untuk pengguna: ' . $user->id, $validatedData);

        DB::beginTransaction();

        try {
            // 1. Hitung Jarak menggunakan LatLng
            $distance = $this->pricingService->calculateDistance(
                (float)$validatedData['pickupLatitude'],
                (float)$validatedData['pickupLongitude'],
                (float)$validatedData['receiverLatitude'],
                (float)$validatedData['receiverLongitude']
            );
            Log::info("Jarak terhitung: {$distance} Km untuk pesanan oleh pengguna: " . $user->id);

            // 2. Hitung Estimasi Harga
            $estimatedPrice = $this->pricingService->calculateEstimatedPrice(
                (float)$validatedData['weightKG'],
                $distance
            );
            Log::info("Estimasi harga terhitung: Rp {$estimatedPrice} untuk pesanan oleh pengguna: " . $user->id);
             if ($estimatedPrice <= 0 && (float)$validatedData['weightKG'] > 0) {
                 // Jika harga 0 tapi ada berat, mungkin karena jarak 0 (pickup = tujuan)
                 // Atau ada aturan harga minimal yang belum tercover.
                 // Untuk sementara, kita buat error jika harga 0 tapi ada berat.
                Log::error("Harga estimasi Rp 0 dengan berat > 0. Berat: " . $validatedData['weightKG'] . "Kg, Jarak: " . $distance . "Km");
                throw new Exception("Gagal menghitung harga pengiriman. Pastikan alamat penjemputan dan tujuan berbeda atau periksa konfigurasi harga.");
            }


            // 3. Buat Order
            $order = Order::create([
                'senderUserID' => $user->id,
                'receiverName' => $validatedData['receiverName'],
                'receiverAddress' => $validatedData['receiverAddress'],
                'receiverPhoneNumber' => $validatedData['receiverPhoneNumber'],
                'pickupAddress' => $validatedData['pickupAddress'],
                'orderDate' => now()->toDateString(),
                'notes' => $validatedData['notes'] ?? null,
                'status' => 'Pending Payment', // Status awal sebelum pembayaran
                'estimatedDistanceKM' => $distance,
                'estimatedPrice' => $estimatedPrice,
                'pickupLatitude' => (float)$validatedData['pickupLatitude'],
                'pickupLongitude' => (float)$validatedData['pickupLongitude'],
                'receiverLatitude' => (float)$validatedData['receiverLatitude'],
                'receiverLongitude' => (float)$validatedData['receiverLongitude'],
            ]);
            Log::info("Order dibuat dengan ID: {$order->orderID} oleh pengguna: " . $user->id);

            // 4. Handle Opsi Pembayaran
            if ($validatedData['paymentMethodOption'] === 'online') {
                // 4a. Proses Pembayaran Online dengan Midtrans
                Log::info("Memproses pembayaran online untuk Order ID: {$order->orderID}");
                $snapToken = $this->midtransService->createSnapToken($order); // Order akan di-save di dalam service
                // `midtrans_order_id` dan `midtrans_snap_token` sudah tersimpan di $order
                Log::info("Snap Token Midtrans dibuat: {$snapToken} untuk Order ID: {$order->orderID}");

                // Tidak membuat shipment dulu sampai pembayaran dikonfirmasi oleh Midtrans (via webhook)
                DB::commit();
                Log::info("Transaksi database di-commit untuk Order ID: {$order->orderID} menunggu pembayaran online.");

                // Redirect ke halaman form dengan Snap Token untuk diproses oleh Midtrans Snap JS
                return redirect()->route('shipments.create')
                                 ->with('snap_token', $snapToken)
                                 ->with('midtrans_order_id', $order->midtrans_order_id) // kirim juga midtrans_order_id
                                 ->with('order_id', $order->orderID) // kirim orderID aplikasi
                                 ->with('info_payment', 'Silakan selesaikan pembayaran Anda.');

            } elseif ($validatedData['paymentMethodOption'] === 'cod') {
                // 4b. Proses untuk Cash On Delivery (COD)
                $order->status = 'Pending COD Confirmation'; // Status khusus untuk COD
                // Atau bisa langsung 'Processing' jika Admin tidak perlu konfirmasi manual
                $order->save();
                Log::info("Order ID: {$order->orderID} dipilih untuk COD.");

                // Buat Shipment langsung karena COD
                $shipment = Shipment::create([
                    'orderID' => $order->orderID,
                    'itemType' => $validatedData['itemType'],
                    'weightKG' => (float)$validatedData['weightKG'],
                    'currentStatus' => 'Scheduled for Pickup',
                    'finalPrice' => $estimatedPrice,
                ]);
                Log::info("Shipment (COD) dibuat dengan ID (Resi): {$shipment->shipmentID} untuk Order ID: {$order->orderID}");

                // Buat entri Payment untuk COD (opsional, tergantung alur akuntansi)
                Payment::create([
                    'orderID' => $order->orderID,
                    'amount' => $estimatedPrice,
                    'paymentMethod' => 'COD',
                    'status' => 'Pending', // Akan diupdate jadi 'Success' saat kurir konfirmasi terima uang
                    'paymentDate' => now(), // Bisa juga null sampai uang diterima
                ]);

                // Buat Tracking History Awal
                TrackingHistory::create([
                    'shipmentID' => $shipment->shipmentID,
                    'statusDescription' => 'Pesanan COD dibuat dan menunggu penjemputan.',
                    'updatedByUserID' => $user->id,
                ]);

                DB::commit();
                Log::info("Transaksi database di-commit untuk Order COD ID: {$order->orderID}.");

                return redirect()->route('shipments.create')
                                 ->with('success', "Pengiriman COD berhasil dibuat! Nomor Resi Anda: SHP{$shipment->shipmentID}. Estimasi Harga: Rp " . number_format($estimatedPrice, 0, ',', '.'));
            }

        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Pembuatan kiriman gagal untuk pengguna: " . $user->id . ". Error: " . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return back()->withInput()->with('error', "Gagal membuat pengiriman: " . $e->getMessage());
        }
    }

    /**
     * Halaman redirect setelah customer diarahkan kembali dari Midtrans.
     */
    public function paymentFinish(Request $request)
    {
        // Anda bisa mendapatkan detail transaksi dari query string yang dikirim Midtrans
        // atau dari session jika Anda menyimpannya sebelumnya.
        // Contoh: $request->query('order_id'), $request->query('status_code'), $request->query('transaction_status')

        // Sebaiknya, verifikasi status terbaru dari server Anda via webhook atau API Midtrans
        // daripada hanya mengandalkan parameter redirect.

        $midtransOrderId = $request->query('order_id');
        $transactionStatus = $request->query('transaction_status');
        $statusCode = $request->query('status_code');

        Log::info("Midtrans Payment Finish Redirect. Midtrans Order ID: {$midtransOrderId}, Status: {$transactionStatus}, Code: {$statusCode}");

        if (!$midtransOrderId) {
            return redirect()->route('dashboard')->with('error', 'Pembayaran tidak ditemukan.');
        }

        $order = Order::where('midtrans_order_id', $midtransOrderId)->first();

        if (!$order) {
            Log::error("Order tidak ditemukan untuk Midtrans Order ID: {$midtransOrderId} pada halaman finish.");
            return redirect()->route('dashboard')->with('error', 'Pesanan tidak ditemukan terkait pembayaran ini.');
        }

        // Periksa status order (yang seharusnya sudah diupdate oleh webhook)
        if ($order->status === 'Paid' || $order->shipment) { // Jika sudah 'Paid' atau Shipment sudah ada
             $shipment = $order->shipment;
             $resi = $shipment ? "SHP".$shipment->shipmentID : "(Menunggu Konfirmasi Pembayaran)";
            return redirect()->route('dashboard')->with('success', "Status Pembayaran untuk Order #{$order->orderID} (Resi: {$resi}) telah diproses. Terima kasih!");
        } elseif ($transactionStatus === 'pending') {
            return redirect()->route('dashboard')->with('info', "Pembayaran untuk Order #{$order->orderID} masih tertunda. Silakan selesaikan pembayaran Anda.");
        } elseif ($transactionStatus === 'expire' || $transactionStatus === 'cancel' || $transactionStatus === 'deny') {
            return redirect()->route('dashboard')->with('error', "Pembayaran untuk Order #{$order->orderID} gagal atau dibatalkan.");
        } else {
             // Jika status belum jelas atau masih 'Pending Payment' di sistem kita.
            return redirect()->route('dashboard')->with('info', "Kami sedang memverifikasi pembayaran Anda untuk Order #{$order->orderID}. Mohon tunggu beberapa saat.");
        }
    }
}
