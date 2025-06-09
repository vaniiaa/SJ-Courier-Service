<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\MidtransService; // Gunakan service yang sudah dibuat
use App\Models\Order;
use App\Models\Payment;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class MidtransNotificationController extends Controller
{
    /**
     * Handle HTTP Notification from Midtrans.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $notificationPayload = json_decode($request->getContent()); // Ambil payload JSON

        if (!$notificationPayload) {
            Log::error('Midtrans Notification: Invalid JSON payload received.');
            return response()->json(['message' => 'Invalid payload.'], 400);
        }

        Log::info('Midtrans Notification: Payload received.', (array)$notificationPayload);

        try {
            // PENTING: Di lingkungan produksi, SELALU verifikasi signature dari Midtrans
            // atau panggil API status Midtrans untuk mendapatkan status transaksi yang sebenarnya
            // sebelum memproses notifikasi.
            // $verifiedNotification = MidtransService::verifyNotification($notificationPayload);
            // Untuk demo ini, kita asumsikan payload valid (setelah json_decode).
            $verifiedNotification = $notificationPayload; // Menyederhanakan untuk demo


            $midtransOrderId = $verifiedNotification->order_id;
            $transactionStatus = $verifiedNotification->transaction_status;
            $fraudStatus = $verifiedNotification->fraud_status ?? null; // Bisa jadi tidak ada
            $paymentType = $verifiedNotification->payment_type ?? 'unknown';
            $transactionId = $verifiedNotification->transaction_id ?? null;
            $grossAmount = $verifiedNotification->gross_amount ?? null;

            Log::info("Midtrans Notification: Processing for Midtrans Order ID: {$midtransOrderId}, Status: {$transactionStatus}, Fraud: {$fraudStatus}");

            $order = Order::where('midtrans_order_id', $midtransOrderId)->first();

            if (!$order) {
                Log::error("Midtrans Notification: Order tidak ditemukan untuk Midtrans Order ID: {$midtransOrderId}.");
                // Kirim response 404 agar Midtrans tidak retry jika order memang tidak ada.
                return response()->json(['message' => 'Order not found.'], 404);
            }

            // Hindari memproses ulang notifikasi yang sudah selesai
            if ($order->status === 'Paid' || $order->status === 'Cancelled' || $order->shipment) {
                 Log::info("Midtrans Notification: Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}) sudah diproses sebelumnya. Status saat ini: {$order->status}.");
                return response()->json(['message' => 'Notification already processed for this order.'], 200);
            }

            DB::beginTransaction();

            // Buat atau update record Payment
            $payment = Payment::updateOrCreate(
                ['orderID' => $order->orderID, 'midtrans_transaction_id' => $transactionId], // Kunci unik
                [
                    'amount' => $grossAmount ? (int)explode('.', $grossAmount)[0] : $order->estimatedPrice,
                    'paymentMethod' => $paymentType,
                    'paymentDate' => $verifiedNotification->transaction_time ?? now(),
                    'status' => 'Pending', // Akan diupdate di bawah
                    'raw_midtrans_response' => json_encode($verifiedNotification),
                ]
            );

            $orderUpdated = false;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    // TODO: Set order status to "challenge" and wait for your approval
                    $payment->status = 'Challenge';
                    $order->status = 'Payment Challenged'; // Status order yang sesuai
                    Log::debug('Midtrans Notification: Payment challenged.', [
                        'order_id' => $order->orderID,
                        'midtrans_order_id' => $midtransOrderId,
                        'notification' => (array)$verifiedNotification
                    ]);
                    Log::warning("Midtrans Notification: Pembayaran untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}) di-challenge oleh FDS.");
                    $orderUpdated = true;
                } else if ($fraudStatus == 'accept') {
                    // TODO: Set order status to "success"
                    $payment->status = 'Success';
                    $order->status = 'Paid'; // Status order menjadi "Paid"
                    Log::info("Midtrans Notification: Pembayaran berhasil (fraud accept) untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");

                    // Buat Shipment setelah pembayaran berhasil
                    if (!$order->shipment) {
                        $shipment = Shipment::create([
                            'orderID' => $order->orderID,
                            'itemType' => $order->senderUserID, // Ambil dari data order yang sudah ada
                            'weightKG' => $order->senderUserID, // Ambil dari data order yang sudah ada
                            'currentStatus' => 'Payment Confirmed - Awaiting Pickup',
                            'finalPrice' => $order->estimatedPrice,
                        ]);
                        Log::info("Midtrans Notification: Shipment dibuat dengan ID: {$shipment->shipmentID} untuk Order ID: {$order->orderID}");

                        // Buat Tracking History Awal
                        TrackingHistory::create([
                            'shipmentID' => $shipment->shipmentID,
                            'statusDescription' => 'Pembayaran dikonfirmasi. Menunggu penjemputan oleh kurir.',
                            'updatedByUserID' => $order->senderUserID, // Sistem atau user pengirim
                        ]);
                    }
                    $orderUpdated = true;
                }
            } else if ($transactionStatus == 'settlement') {
                // TODO: Set order status to "success"
                // Ini adalah konfirmasi final bahwa dana sudah masuk
                $payment->status = 'Success';
                $order->status = 'Paid';
                Log::info("Midtrans Notification: Pembayaran settlement untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");

                // Buat Shipment setelah pembayaran berhasil (jika belum dibuat oleh 'capture')
                if (!$order->shipment) {
                    // Ambil detail item dari order yang sudah ada, karena tidak ada di payload notifikasi settlement
                    $originalOrderDataForShipment = Order::find($order->orderID); // Re-fetch untuk data yang mungkin belum ada di instance $order
                    if ($originalOrderDataForShipment) {
                        $shipment = Shipment::create([
                            'orderID' => $originalOrderDataForShipment->orderID,
                            'itemType' => $originalOrderDataForShipment->itemType ?? 'Barang Kiriman', // Placeholder jika tidak ada
                            'weightKG' => $originalOrderDataForShipment->weightKG ?? 1, // Placeholder jika tidak ada
                            'currentStatus' => 'Payment Confirmed - Awaiting Pickup',
                            'finalPrice' => $originalOrderDataForShipment->estimatedPrice,
                        ]);
                        Log::info("Midtrans Notification: Shipment dibuat (on settlement) dengan ID: {$shipment->shipmentID} untuk Order ID: {$order->orderID}");

                        TrackingHistory::create([
                            'shipmentID' => $shipment->shipmentID,
                            'statusDescription' => 'Pembayaran dikonfirmasi. Menunggu penjemputan oleh kurir.',
                            'updatedByUserID' => $originalOrderDataForShipment->senderUserID,
                        ]);
                    } else {
                         Log::error("Midtrans Notification: Gagal mengambil detail order asli untuk membuat shipment pada settlement. Order ID: {$order->orderID}");
                    }
                }
                $orderUpdated = true;
            } else if ($transactionStatus == 'pending') {
                // TODO: Set order status to "pending"
                $payment->status = 'Pending';
                $order->status = 'Pending Payment'; // Tetap atau update jika perlu
                Log::info("Midtrans Notification: Pembayaran pending untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");
                $orderUpdated = true;
            } else if ($transactionStatus == 'deny') {
                // TODO: Set order status to "denied"
                $payment->status = 'Denied';
                $order->status = 'Payment Denied';
                Log::error("Midtrans Notification: Pembayaran ditolak untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");
                $orderUpdated = true;
            } else if ($transactionStatus == 'expire') {
                // TODO: Set order status to "expired"
                $payment->status = 'Expired';
                $order->status = 'Payment Expired';
                Log::error("Midtrans Notification: Pembayaran kadaluarsa untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");
                $orderUpdated = true;
            } else if ($transactionStatus == 'cancel') {
                // TODO: Set order status to "cancelled"
                $payment->status = 'Cancelled';
                $order->status = 'Payment Cancelled';
                Log::error("Midtrans Notification: Pembayaran dibatalkan untuk Order ID {$order->orderID} (Midtrans ID: {$midtransOrderId}).");
                $orderUpdated = true;
            }

            $payment->save();
            if ($orderUpdated) {
                $order->save();
            }

            DB::commit();
            Log::info("Midtrans Notification: Proses selesai untuk Midtrans Order ID: {$midtransOrderId}. Status Payment: {$payment->status}, Status Order: {$order->status}");
            return response()->json(['message' => 'Notification processed successfully.'], 200);

        } catch (Exception $e) {
            DB::rollBack();
            Log::critical("Midtrans Notification: Exception saat memproses notifikasi. Error: " . $e->getMessage(), [
                'payload' => (array)$notificationPayload,
                'trace' => $e->getTraceAsString()
            ]);
            // Jangan kirim error 500 ke Midtrans agar mereka tidak retry terus jika error dari sistem kita.
            // Kecuali jika error karena koneksi database sementara, dll.
            return response()->json(['message' => 'Internal server error while processing notification.'], 200); // Kirim 200 agar Midtrans stop
        }
    }
}