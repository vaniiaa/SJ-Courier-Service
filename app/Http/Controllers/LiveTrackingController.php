<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class LiveTrackingController extends Controller
{
    public function index()
    {
        return view('kurir.live_tracking');
    }

    public function updateLocation(Request $request)
    {
        try {
            $request->validate([
                'tracking_number' => 'required|exists:shipments,tracking_number',
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
            ]);

            $updated = DB::table('shipments')
                ->where('tracking_number', $request->tracking_number)
                ->update([
                    'current_lat' => $request->lat,
                    'current_long' => $request->long,
                    'last_tracked_at' => now(),
                ]);

            if ($updated) {
                return response()->json(['message' => 'Lokasi berhasil diperbarui']);
            } else {
                return response()->json(['message' => 'Lokasi sudah terbaru atau tidak ada perubahan.']);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal memperbarui lokasi: Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in updateLocation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal memperbarui lokasi: Terjadi kesalahan server.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint to get shipment location and status for the user/public view.
     * This method is accessed via the route:
     * Route::get('/api/shipment-location', [LiveTrackingController::class, 'getShipmentLocation'])->name('api.shipment_location');
     */
    public function getShipmentLocation(Request $request)
    {
        try {
            $request->validate([
                'tracking_number' => 'required|string|exists:shipments,tracking_number',
            ], [
                'tracking_number.exists' => 'Data pengiriman tidak ditemukan untuk nomor resi ini.'
            ]);

            $shipment = DB::table('shipments')
                ->where('tracking_number', $request->tracking_number)
                ->select('current_lat', 'current_long', 'currentStatus', 'last_tracked_at')
                ->first();

            if (!$shipment) {
                return response()->json(['message' => 'Pengiriman tidak ditemukan'], 404);
            }

            // Format tanggal menggunakan Carbon agar lebih mudah dibaca di frontend
            $lastTracked = $shipment->last_tracked_at ? Carbon::parse($shipment->last_tracked_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB' : 'Belum ada data';

            return response()->json([
                'lat' => $shipment->current_lat,
                'long' => $shipment->current_long,
                'status' => $shipment->currentStatus,
                'last_tracked_at' => $lastTracked,
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Gagal melacak: Data input tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error in getShipmentLocation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal melacak: Terjadi kesalahan pada server.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}
