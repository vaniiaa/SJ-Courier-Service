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
    public function formShipment()
    {
        try {
            $savedAddresses = SavedAddress::where('user_id', Auth::id())
                ->get();
                
            $kecamatanList = [ 
                'Batam Kota', 'Nongsa', 'Bengkong', 'Batu Ampar', 'Sekupang',
                'Batu Aji', 'Sagulung', 'Sei Beduk', 'Lubuk Baja',
                'Belakang Padang', 'Bulang', 'Galang'
            ];
            sort($kecamatanList);
            
            return view('User.form_pengiriman', compact('savedAddresses', 'kecamatanList'));
        } catch (Exception $e) {
            Log::error("Error loading form: " . $e->getMessage());
            return back()->with('error', 'Gagal memuat form pengiriman. Silakan coba lagi.');
        }
    }

    /**
     * LANGKAH 1: Memproses dan menyimpan data dari form utama ke session.
     */
    public function storeShipment(StoreShipmentRequest $request)
    {
        // Set timeout lebih pendek untuk operasi ini
        set_time_limit(30);
        
        $validatedData = $request->validated();

        try {
            // Validasi koordinat terlebih dahulu
            $pickupLat = (float)$validatedData['pickupLatitude'];
            $pickupLng = (float)$validatedData['pickupLongitude'];
            $receiverLat = (float)$validatedData['receiverLatitude'];
            $receiverLng = (float)$validatedData['receiverLongitude'];
            
            // Validasi range koordinat Batam
            if (!$this->isValidBatamCoordinates($pickupLat, $pickupLng) || 
                !$this->isValidBatamCoordinates($receiverLat, $receiverLng)) {
                throw new Exception('Koordinat tidak valid untuk area Batam');
            }

            // Hitung Jarak dan Harga dengan timeout
            $distance = $this->pricingService->calculateDistance($pickupLat, $pickupLng, $receiverLat, $receiverLng);
            $estimatedPrice = $this->pricingService->calculateEstimatedPrice((float)$validatedData['weightKG'], $distance);

            // Simpan ke session
            $shipmentData = array_merge($validatedData, [
                'estimatedDistanceKM' => $distance,
                'estimatedPrice' => $estimatedPrice,
            ]);

            $request->session()->put('shipment_data', $shipmentData);
            
            Log::info('Data Langkah 1 disimpan ke session:', ['user_id' => Auth::id(), 'distance' => $distance]);

            return redirect()->route('user.ringkasan_pengiriman')
                ->with('success', 'Data pengiriman berhasil disimpan. Silakan lanjut ke langkah berikutnya.');

        } catch (Exception $e) {
            Log::error("Error pada Langkah 1 pengiriman: " . $e->getMessage(), [
                'user_id' => Auth::id(),
                'data' => $validatedData
            ]);
            return back()->withInput()->with('error', "Gagal memproses data: " . $e->getMessage());
        }
    }

    /**
     * LANGKAH 2: Menampilkan halaman ringkasan dan pembayaran.
     */
    public function summaryShipment(Request $request)
    {
        $shipmentData = $request->session()->get('shipment_data');

        if (!$shipmentData) {
            return redirect()->route('user.form_pengiriman')
                ->with('error', 'Silakan isi detail pengiriman terlebih dahulu.');
        }
        
        Log::info('Menampilkan Langkah 2 dengan data session:', ['user_id' => Auth::id()]);

        return view('User.ringkasan_pengiriman', ['data' => $shipmentData]);
    }

/**
 * FINAL: Memproses pesanan dari halaman ringkasan.
 */
public function storeFinal(Request $request)
{
    set_time_limit(120); // Beri waktu 2 menit untuk proses ini
    
    $request->validate(['paymentMethodOption' => 'required|string|in:cod,online']);
    
    $shipmentData = $request->session()->get('shipment_data');
    if (!$shipmentData) {
        return redirect()->route('user.form_pengiriman')
            ->with('error', 'Sesi pengiriman telah berakhir. Silakan mulai lagi.');
    }

    DB::beginTransaction(); // Mulai transaksi database
    
    try {
        // LANGKAH 1: Buat Order (berlaku untuk semua jenis pembayaran)
        $order = Order::create([
            'senderUserID' => Auth::id(),
            // Status awal, akan diubah di bawah berdasarkan metode pembayaran
            'status' => 'Pending Payment', 
            'orderDate' => now(),
            'receiverName' => $shipmentData['receiverName'],
            'receiverAddress' => $shipmentData['receiverAddress'],
            'receiverPhoneNumber' => $shipmentData['receiverPhoneNumber'],
            'pickupAddress' => $shipmentData['pickupAddress'],
            'estimatedDistanceKM' => $shipmentData['estimatedDistanceKM'],
            'estimatedPrice' => $shipmentData['estimatedPrice'],
            'pickupKecamatan' => $shipmentData['pickupKecamatan'],
            'receiverKecamatan' => $shipmentData['receiverKecamatan'],
            'pickupLatitude' => $shipmentData['pickupLatitude'],
            'pickupLongitude' => $shipmentData['pickupLongitude'],
            'receiverLatitude' => $shipmentData['receiverLatitude'],
            'receiverLongitude' => $shipmentData['receiverLongitude'],
            'notes' => $shipmentData['notes'] ?? null,
            'itemType' => $shipmentData['itemType'],
            'weightKG' => $shipmentData['weightKG'],
        ]);

        Log::info('Order created successfully', ['order_id' => $order->orderID, 'user_id' => Auth::id()]);

        // LANGKAH 2: Buat Shipment (berlaku untuk semua jenis pembayaran)
        $shipment = Shipment::create([
            'orderID' => $order->orderID,
            'itemType' => $shipmentData['itemType'],
            'weightKG' => (float)$shipmentData['weightKG'],
            'currentStatus' => 'Menunggu Konfirmasi', // Status awal pengiriman
            'finalPrice' => $shipmentData['estimatedPrice'],
        ]);
        
        // Hapus data dari session setelah semuanya aman untuk dibuat
        $request->session()->forget('shipment_data');

        // LANGKAH 3: Proses berdasarkan Metode Pembayaran
        if ($request->input('paymentMethodOption') === 'online') {
            // Untuk pembayaran Online
            Payment::create([
                'orderID' => $order->orderID,
                'amount' => $shipmentData['estimatedPrice'],
                'paymentMethod' => 'Online',
                'status' => 'Pending' // Status menunggu pembayaran dari Midtrans
            ]);

            TrackingHistory::create([
                'shipmentID' => $shipment->shipmentID,
                'statusDescription' => 'Menunggu pembayaran online.'
            ]);
            
            try {
                // Coba buat Snap Token dari Midtrans
                $snapToken = $this->midtransService->createSnapToken($order);
                DB::commit();
                
                // Kirim token ke halaman konfirmasi via session
                return redirect()->route('user.confirmation', ['order' => $order])
                    ->with('snap_token', $snapToken);
                    
            } catch (Exception $midtransError) {
                // FALLBACK: Jika Midtrans error, batalkan transaksi dan beri tahu user
                Log::error("Midtrans error: " . $midtransError->getMessage());
                DB::rollBack(); 
                // Redirect kembali ke form dengan data yang sudah diisi dan pesan error
                return back()->withInput($request->all())->with('error', 'Layanan pembayaran online sedang tidak tersedia. Silakan coba lagi atau pilih metode COD.');
            }

        } else {
            // Untuk pembayaran COD
            $order->status = 'Processing'; // Ubah status order untuk COD
            $order->save();
            
            Payment::create([
                'orderID' => $order->orderID,
                'amount' => $shipmentData['estimatedPrice'],
                'paymentMethod' => 'COD',
                'status' => 'Pending' // Menunggu dibayar saat barang sampai
            ]);
            
            TrackingHistory::create([
                'shipmentID' => $shipment->shipmentID,
                'statusDescription' => 'Pesanan COD dibuat, menunggu konfirmasi.'
            ]);

            DB::commit();
            Log::info('COD order completed successfully', ['order_id' => $order->orderID]);
            return redirect()->route('user.confirmation', ['order' => $order]);
        }

    } catch (Exception $e) {
        DB::rollBack();
        
        Log::error("Gagal menyimpan pengiriman final: " . $e->getMessage(), [
            'user_id' => Auth::id(),
            'trace' => $e->getTraceAsString()
        ]);
        
        return redirect()->route('user.form_pengiriman')
            ->with('error', "Terjadi kesalahan sistem. Silakan coba lagi dalam beberapa menit.");
    }
}

    /**
     * Helper method untuk membuat pesanan COD sebagai fallback jika pembayaran online gagal.
     */
    private function createCODOrderAsFallback($shipmentData)
    {
        DB::beginTransaction();
        try {
            // Buat ulang order, tapi dengan status untuk COD
            $order = Order::create([
                'senderUserID' => Auth::id(),
                'status' => 'Processing', // Status untuk COD
                'orderDate' => now(),
                'receiverName' => $shipmentData['receiverName'],
                'receiverAddress' => $shipmentData['receiverAddress'],
                'receiverPhoneNumber' => $shipmentData['receiverPhoneNumber'],
                'pickupAddress' => $shipmentData['pickupAddress'],
                'estimatedDistanceKM' => $shipmentData['estimatedDistanceKM'],
                'estimatedPrice' => $shipmentData['estimatedPrice'],
                'pickupKecamatan' => $shipmentData['pickupKecamatan'],
                'receiverKecamatan' => $shipmentData['receiverKecamatan'],
                'pickupLatitude' => $shipmentData['pickupLatitude'],
                'pickupLongitude' => $shipmentData['pickupLongitude'],
                'receiverLatitude' => $shipmentData['receiverLatitude'],
                'receiverLongitude' => $shipmentData['receiverLongitude'],
                'notes' => $shipmentData['notes'] ?? null,
                'itemType' => $shipmentData['itemType'], // <-- TAMBAHKAN
                'weightKG' => $shipmentData['weightKG'], // <-- TAMBAHKAN
            ]);

            // Buat Shipment, Payment, dan Tracking History untuk COD
            $shipment = Shipment::create(['orderID' => $order->orderID, 'itemType' => $shipmentData['itemType'], 'weightKG' => (float)$shipmentData['weightKG'], 'currentStatus' => 'Menunggu Konfirmasi', 'finalPrice' => $shipmentData['estimatedPrice']]);
            Payment::create(['orderID' => $order->orderID, 'amount' => $shipmentData['estimatedPrice'], 'paymentMethod' => 'COD', 'status' => 'Pending']);
            TrackingHistory::create(['shipmentID' => $shipment->shipmentID, 'statusDescription' => 'Pesanan COD dibuat (fallback dari online), menunggu penjemputan.']);

            DB::commit();
            
            // Redirect ke halaman konfirmasi dengan pesan info
            return redirect()->route('user.confirmation', ['order' => $order])
                ->with('info', 'Pembayaran online tidak tersedia saat ini. Pesanan Anda telah dibuat sebagai COD.');
                
        } catch (Exception $e) {
            DB::rollBack();
            Log::critical("Gagal membuat pesanan COD fallback: " . $e->getMessage());
            return redirect()->route('user.form_pengiriman')
                ->with('error', "Terjadi kesalahan kritis. Gagal membuat pesanan. Silakan coba lagi.");
        }
    }

    /**
     * Validasi koordinat untuk area Batam
     */
    private function isValidBatamCoordinates($lat, $lng)
    {
        // Batam coordinate bounds (approximate)
        $minLat = 0.9;
        $maxLat = 1.3;
        $minLng = 103.7;
        $maxLng = 104.4;
        
        return ($lat >= $minLat && $lat <= $maxLat && $lng >= $minLng && $lng <= $maxLng);
    }

    /**
     * Menampilkan halaman konfirmasi setelah pesanan dibuat.
     */
    public function confirmation(Order $order)
    {
        try {
            // Pastikan order ini milik user yang sedang login
            if ($order->senderUserID !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            $order->load('shipment', 'payments');
            $shipment = $order->shipment;

            // Ambil snap_token dari session jika ada
            return view('User.confirmation', compact('order', 'shipment'));
            
        } catch (Exception $e) {
            Log::error("Error loading confirmation: " . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Halaman konfirmasi tidak dapat dimuat.');
        }
    }

    // Status yang dianggap selesai
    private $finishedStatuses = [
        'Pesanan Selesai', 'Pesanan Ditolak',
        'Dibatalkan', 'Dikembalikan'
    ];

    /**
     * Menampilkan halaman "Daftar Pengiriman" (yang masih aktif) untuk customer.
     */
    public function List(Request $request)
{
    try {
        $search = $request->input('search');

        $query = Shipment::whereHas('order', function ($q) {
                $q->where('senderUserID', Auth::id());
            })
            ->whereNotIn('currentStatus', $this->finishedStatuses)
            ->with(['order.sender', 'courier', 'order.payments']);

        // Jika ada pencarian, tambahkan filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('order', function ($subq) use ($search) {
                      $subq->where('receiverName', 'like', '%' . $search . '%');
                  });
            });
        }

        $shipments = $query->latest()->paginate(10);

        return view('User.Daftar_Pengiriman', compact('shipments', 'search'));

    } catch (Exception $e) {
        Log::error("Error loading shipment list: " . $e->getMessage());
        return back()->with('error', 'Gagal memuat daftar pengiriman.');
    }
}

    /**
     * Menampilkan halaman "History Pengiriman" (yang sudah selesai) untuk customer.
     */
    public function history(Request $request)
{
    try {
        $search = $request->input('search');

        // Query untuk shipment dengan status "Pesanan selesai" milik user saat ini
        $query = \App\Models\Shipment::with(['order.sender', 'courier', 'order.payments'])
            ->where('currentStatus', 'Pesanan Selesai')
            ->whereHas('order', function ($query) {
                $query->where('senderUserID', \Illuminate\Support\Facades\Auth::id());
            });

        // Jika ada pencarian, tambahkan filter
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('tracking_number', 'like', '%' . $search . '%')
                  ->orWhereHas('order', function ($subq) use ($search) {
                      $subq->where('receiverName', 'like', '%' . $search . '%');
                  });
            });
        }

        // Ambil data dengan urutan terbaru
        $shipments = $query->latest('updated_at')->paginate(10);

        return view('User.History_Pengiriman', compact('shipments'));

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error("Error loading shipment history: " . $e->getMessage());
        return back()->with('error', 'Gagal memuat riwayat pengiriman.');
    }
}


    /**
     * Menangani redirect dari Midtrans setelah proses pembayaran (finish URL).
     */
    public function paymentFinish(Request $request)
    {
        try {
            $midtransOrderId = $request->query('order_id');
            $statusCode = $request->query('status_code');
            $transactionStatus = $request->query('transaction_status');

            if (!$midtransOrderId) {
                return redirect()->route('dashboard')->with('error', 'Transaksi tidak valid atau tidak lengkap.');
            }

            $order = Order::where('midtrans_order_id', $midtransOrderId)->firstOrFail();

            // Pastikan user yang login adalah pemilik order
            if ($order->senderUserID !== Auth::id()) {
                abort(403, 'Unauthorized action.');
            }

            // Berdasarkan status transaksi dari Midtrans
            if ($transactionStatus == 'settlement' || ($transactionStatus == 'capture' && $statusCode == '200')) {
                // Jika pembayaran sukses, arahkan ke history dengan pesan sukses
                return redirect()->route('user.history')->with('success', 'Pembayaran untuk pesanan ' . $order->midtrans_order_id . ' telah berhasil.');
            } elseif ($transactionStatus == 'pending') {
                // Jika pembayaran pending, arahkan kembali ke halaman konfirmasi dengan info
                return redirect()->route('user.confirmation', ['order' => $order])->with('info', 'Pembayaran Anda sedang diproses. Status akan diperbarui secara otomatis setelah pembayaran selesai.');
            } else {
                // Jika pembayaran gagal (deny, expire, cancel) atau error (status code bukan 200/201)
                return redirect()->route('user.confirmation', ['order' => $order])->with('error', 'Pembayaran untuk pesanan ' . $order->midtrans_order_id . ' gagal atau dibatalkan.');
            }
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::warning('Midtrans finish redirect: Order not found for midtrans_order_id: ' . $request->query('order_id'));
            return redirect()->route('dashboard')->with('error', 'Pesanan yang Anda coba akses tidak ditemukan.');
        } catch (\Exception $e) {
            Log::error("Error on paymentFinish: " . $e->getMessage(), ['request' => $request->all()]);
            return redirect()->route('dashboard')->with('error', 'Terjadi kesalahan saat memproses status pembayaran Anda.');
        }
    }

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
        return view('User.resi_print', compact('shipment', 'qrcode'));
    }

    public function cancel(Shipment $shipment)
    {
        // 1. Otorisasi: Pastikan pengguna yang login adalah pemilik pengiriman
        if ($shipment->order->senderUserID !== Auth::id()) {
            return redirect()->route('user.daftar_pengiriman')->with('error', 'Anda tidak memiliki izin untuk membatalkan pesanan ini.');
        }

        // 2. Validasi Aturan Bisnis: Cek status saat ini
        $cancellableStatuses = ['Menunggu Pembayaran', 'Menunggu Penjemputan', 'Kurir Belum Ditugaskan'];
        if (!in_array($shipment->currentStatus, $cancellableStatuses)) {
            return redirect()->route('user.daftar_pengiriman')->with('error', 'Pesanan tidak dapat dibatalkan karena sudah dalam proses pengiriman.');
        }

        // 3. Proses Pembatalan
        $shipment->currentStatus = 'Dibatalkan';
        // (Opsional) Anda bisa menambahkan kolom 'cancelled_at' untuk mencatat waktu pembatalan
        // $shipment->cancelled_at = now(); 
        $shipment->save();

        // 4. Logika Penanganan Pembayaran
        // Cek jika pesanan sudah dibayar (misalnya, status pembayaran bukan 'pending' atau 'unpaid')
        if ($shipment->order->payment_status === 'paid') {
            // TODO: Logika untuk pengembalian dana (refund)
            // Untuk saat ini, kita bisa menambahkan catatan untuk admin.
            // Di masa depan, ini bisa diintegrasikan dengan API payment gateway.
            Log::info('Pesanan ' . $shipment->tracking_number . ' dibatalkan dan memerlukan refund manual.');
            // Anda juga bisa mengirim notifikasi ke admin di sini.
        }
        
        return redirect()->route('user.daftar_pengiriman')->with('success', 'Pesanan dengan resi ' . $shipment->tracking_number . ' berhasil dibatalkan.');
    }
}