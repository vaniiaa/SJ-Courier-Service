<?php

namespace App\Services;

use Midtrans\Config as MidtransConfig;
use Midtrans\Snap;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransService
{
    public function __construct()
    {
        MidtransConfig::$serverKey = config('midtrans.server_key');
        MidtransConfig::$isProduction = config('midtrans.is_production');
        MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
        MidtransConfig::$is3ds = config('midtrans.is_3ds');
    }

    /**
     * Membuat transaksi Snap token untuk Order.
     *
     * @param Order $order
     * @return string Snap Token
     * @throws Exception
     */
    public function createSnapToken(Order $order): string
    {
        $sender = $order->sender; // Asumsikan relasi sender() ada di model Order
        if (!$sender) {
            Log::error("MidtransService: Sender tidak ditemukan untuk Order ID: {$order->orderID}");
            throw new Exception("Detail pengirim tidak ditemukan untuk membuat pembayaran.");
        }

        // Generate unique Midtrans order ID
        $midtransOrderId = 'JASA-KIRIM-BTM-' . $order->orderID . '-' . time();
        $order->midtrans_order_id = $midtransOrderId; // Simpan ke order

        $params = [
            'transaction_details' => [
                'order_id' => $midtransOrderId,
                'gross_amount' => $order->estimatedPrice,
            ],
            'customer_details' => [
                'first_name' => $sender->name,
                'email' => $sender->email,
                'phone' => $sender->phoneNumber ?? '081000000000', // Pastikan ada nomor telepon
                'billing_address' => [ // Alamat penagihan, bisa sama dengan alamat pengirim
                    'first_name' => $sender->name,
                    'phone' => $sender->phoneNumber ?? '081000000000',
                    'address' => $sender->address ?? $order->pickupAddress, // Ambil dari User atau Order
                    'city' => 'Batam', // Hardcode atau ambil dari alamat
                    'postal_code' => '29400', // Contoh, idealnya dari alamat
                    'country_code' => 'IDN'
                ],
                 // Alamat pengiriman bisa diisi dengan detail penerima jika relevan untuk Midtrans
                'shipping_address' => [
                    'first_name' => $order->receiverName,
                    'phone' => $order->receiverPhoneNumber,
                    'address' => $order->receiverAddress,
                    'city' => 'Batam',
                    'postal_code' => '29400',
                    'country_code' => 'IDN'
                ]
            ],
            'item_details' => [
                [
                    'id' => $order->orderID,
                    'price' => $order->estimatedPrice,
                    'quantity' => 1,
                    'name' => 'Biaya Pengiriman Paket - Order #' . $order->orderID,
                    'merchant_name' => config('app.name', 'Jasa Kirim Batam')
                ]
            ],
            'callbacks' => [ // URL Notifikasi dari Midtrans
                'finish' => route('payment.finish') // URL redirect setelah pembayaran
            ],
            // 'expiry' => [ // Opsional: atur waktu kadaluarsa token/pembayaran
            //     'start_time' => date('Y-m-d H:i:s O'), // Waktu sekarang
            //     'unit' => 'hour',
            //     'duration' => 1, // Kadaluarsa dalam 1 jam
            // ],
            // 'enabled_payments' => [], // Jika ingin membatasi metode pembayaran
        ];

        try {
            Log::info("MidtransService: Membuat Snap Token untuk Midtrans Order ID: {$midtransOrderId}", $params);
            $snapToken = Snap::getSnapToken($params);
            $order->midtrans_snap_token = $snapToken;
            $order->save(); // Simpan snap token dan midtrans_order_id ke order
            Log::info("MidtransService: Snap Token berhasil dibuat untuk Midtrans Order ID: {$midtransOrderId}, Snap Token: {$snapToken}");
            return $snapToken;
        } catch (Exception $e) {
            Log::error("MidtransService: Gagal membuat Snap Token untuk Order ID: {$order->orderID}. Error: " . $e->getMessage(), [
                'params' => $params,
                'exception' => $e
            ]);
            throw new Exception("Gagal memproses permintaan pembayaran ke Midtrans: " . $e->getMessage());
        }
    }

    /**
     * Verifikasi notifikasi dari Midtrans.
     *
     * @param object $notificationPayload
     * @return \Midtrans\Transaction
     * @throws Exception
     */
    public static function verifyNotification($notificationPayload)
    {
        // Untuk production, Anda mungkin ingin implementasi validasi signature key
        // $isValidSignature = Veritrans_Vtweb::isValidSignature($notificationPayload, config('midtrans.server_key'));
        // if(!$isValidSignature){
        //     throw new Exception('Invalid Midtrans signature');
        // }

        // Atau, lebih mudah menggunakan status dari API
        // Ini akan melakukan GET request ke API Midtrans untuk mendapatkan status transaksi yang sebenarnya
        // Hal ini lebih aman daripada hanya mempercayai payload notifikasi.
        // try {
        //     $status = \Midtrans\Transaction::status($notificationPayload->order_id);
        //     return $status;
        // } catch (Exception $e) {
        //     Log::error("MidtransService: Gagal verifikasi status notifikasi Midtrans Order ID: {$notificationPayload->order_id}. Error: " . $e->getMessage());
        //     throw new Exception("Gagal verifikasi status transaksi Midtrans.");
        // }

        // Untuk contoh ini, kita akan langsung memproses payload (ASUMSI PAYLOAD VALID)
        // PENTING: Di produksi, SELALU verifikasi signature atau panggil API status Midtrans.
        if (empty($notificationPayload->order_id) || empty($notificationPayload->transaction_status)) {
            Log::error("MidtransService: Payload notifikasi tidak valid.", (array) $notificationPayload);
            throw new Exception("Payload notifikasi Midtrans tidak lengkap.");
        }
        Log::info("MidtransService: Payload notifikasi diterima untuk Midtrans Order ID: {$notificationPayload->order_id}", (array) $notificationPayload);
        return $notificationPayload; // Langsung kembalikan payload (untuk disederhanakan)
    }
}
