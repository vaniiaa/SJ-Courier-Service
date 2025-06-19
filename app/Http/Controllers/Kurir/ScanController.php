<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use App\Models\Shipment;
use App\Models\TrackingHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ScanController extends Controller
{
    /**
     * Pastikan hanya kurir yang bisa mengakses controller ini.
     */
    public function __construct()
    {
        // Anda bisa menambahkan middleware untuk role 'courier' di sini
        // $this->middleware('auth.courier');
        $this->middleware('auth');
    }

    /**
     * Menangani aksi setelah QR code di-scan.
     *
     * @param string $tracking_number
     * @return \Illuminate\Http\RedirectResponse
     */
    public function scanTrack($tracking_number)
    {
        $kurir = Auth::user();

        // Cari shipment berdasarkan nomor resi yang unik
        $shipment = Shipment::where('tracking_number', $tracking_number)->first();

        if (!$shipment) {
            // Jika shipment tidak ditemukan
            return redirect()->route('dashboard')->with('error', 'Nomor Resi tidak valid atau tidak ditemukan.');
        }

        try {
            // Logika untuk mengupdate status
            // Contoh: jika statusnya 'Scheduled for Pickup', ubah menjadi 'Picked Up by Courier'
            $newStatus = 'Picked Up by Courier'; // Anda bisa membuat logika yang lebih kompleks di sini

            if ($shipment->currentStatus !== $newStatus) {
                $shipment->currentStatus = $newStatus;
                // Jika ini adalah momen penjemputan, catat waktunya
                if (is_null($shipment->pickupTimestamp)) {
                    $shipment->pickupTimestamp = now();
                }
                // Tugaskan kurir ini ke pengiriman jika belum ada
                if (is_null($shipment->courierUserID)) {
                    $shipment->courierUserID = $kurir->user_id;
                }
                $shipment->save();

                // Buat entri baru di riwayat pelacakan
                TrackingHistory::create([
                    'shipmentID' => $shipment->shipmentID,
                    'statusDescription' => 'Paket telah dijemput oleh kurir: ' . $kurir->name,
                    'updatedByUserID' => $kurir->user_id,
                ]);

                Log::info("Kurir #{$kurir->user_id} berhasil scan dan update status untuk resi #{$tracking_number}");
                return redirect()->route('dashboard')->with('success', "Status untuk resi #{$tracking_number} berhasil diperbarui.");
            } else {
                return redirect()->route('dashboard')->with('info', "Status untuk resi #{$tracking_number} sudah '{$newStatus}'.");
            }
        } catch (\Exception $e) {
            Log::error("Gagal update status via scan untuk resi #{$tracking_number}: " . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Gagal memperbarui status pengiriman.');
        }
    }
}
