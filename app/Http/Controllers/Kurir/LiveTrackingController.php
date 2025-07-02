<?php

namespace App\Http\Controllers\Kurir;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
use App\Models\Shipment; // Import Model Shipment

class LiveTrackingController extends Controller
{
    /**
     * Menampilkan halaman live tracking untuk kurir.
     * Kurir akan menggunakan halaman ini untuk mengaktifkan dan memperbarui lokasi mereka.
     */
    public function index()
    {
        return view('kurir.live_tracking');
    }

    /**
     * Memperbarui lokasi terkini kurir (dan pengiriman) berdasarkan nomor resi.
     * Endpoint ini akan dipanggil secara berkala dari sisi kurir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateLocation(Request $request)
    {
        try {
            // Validasi input dari request
            $request->validate([
                'tracking_number' => 'required|exists:shipments,tracking_number', // Pastikan nomor resi ada di tabel shipments
                'lat' => 'required|numeric', // Latitude harus berupa angka
                'long' => 'required|numeric', // Longitude harus berupa angka
            ]);

            // Perbarui kolom lokasi dan waktu terakhir dilacak di tabel 'shipments'
            // Perhatikan bahwa di sini kita menggunakan 'last_updated_at'
            $updated = DB::table('shipments') // Anda bisa juga menggunakan Shipment::where(...)
                ->where('tracking_number', $request->tracking_number)
                ->update([
                    'current_lat' => $request->lat,
                    'current_long' => $request->long,
                    'last_updated_at' => now(), // Menggunakan helper now() Laravel untuk waktu saat ini
                ]);

            // Berikan respons berdasarkan apakah pembaruan berhasil atau tidak
            if ($updated) {
                return response()->json(['message' => 'Lokasi berhasil diperbarui']);
            } else {
                // Ini bisa terjadi jika data yang dikirim sama dengan yang sudah ada
                return response()->json(['message' => 'Lokasi sudah terbaru atau tidak ada perubahan.']);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi input
            return response()->json([
                'message' => 'Gagal memperbarui lokasi: Data input tidak valid.',
                'errors' => $e->errors() // Mengembalikan detail error validasi
            ], 422); // Kode status HTTP 422 Unprocessable Entity
        } catch (\Exception $e) {
            // Tangani error umum atau error server
            Log::error('Error in updateLocation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal memperbarui lokasi: Terjadi kesalahan server.',
                'error_detail' => $e->getMessage() // Detail error untuk debugging (opsional di produksi)
            ], 500); // Kode status HTTP 500 Internal Server Error
        }
    }

    /**
     * API endpoint untuk mendapatkan lokasi pengiriman dan status untuk tampilan pengguna/publik.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShipmentLocation(Request $request)
    {
        try {
            // Validasi input nomor resi
            $request->validate([
                'tracking_number' => 'required|string', // Nomor resi wajib string
            ]);

            // Ambil data pengiriman dari database
            // Menggunakan 'last_updated_at' sesuai konfirmasi Anda
            $shipment = Shipment::select('current_lat', 'current_long', 'currentStatus as status', 'last_updated_at')
                                ->where('tracking_number', $request->tracking_number)
                                ->first();

            // Jika pengiriman tidak ditemukan
            if (!$shipment) {
                return response()->json(['message' => 'Data pengiriman tidak ditemukan untuk nomor resi ini.'], 404); // Kode status HTTP 404 Not Found
            }

            // Kembalikan data lokasi, status, dan waktu terakhir dilacak
            // Mengakses $shipment->last_updated_at
            return response()->json([
                'lat' => $shipment->current_lat,
                'long' => $shipment->current_long,
                'status' => $shipment->status,
                'last_tracked_at' => $shipment->last_updated_at // Menggunakan 'last_updated_at' di sini
                    ? Carbon::parse($shipment->last_updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                    : 'N/A', // Format waktu agar mudah dibaca
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            // Tangani error validasi input
            return response()->json([
                'message' => 'Gagal melacak: Nomor resi tidak valid.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            // Tangani error umum atau error server
            Log::error('Error in getShipmentLocation: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal melacak: Terjadi kesalahan internal server.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }
}