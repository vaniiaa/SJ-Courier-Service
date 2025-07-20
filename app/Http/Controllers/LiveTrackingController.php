<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Shipment;

class LiveTrackingController extends Controller
{
    /**
     * Daftar status yang dianggap "selesai" dan tidak perlu dilacak lagi.
     * Dibuat lowercase untuk perbandingan yang konsisten.
     */
    private $finishedStatuses = [
        'pesanan selesai', 'dibatalkan', 'dikembalikan', 'pesanan diterima'
    ];

    /**
     * Menampilkan halaman live tracking berdasarkan role user.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Redirect ke view sesuai role
        switch ($user->role->role_name) {
            case 'admin':
                return view('admin.live_tracking_admin');
            case 'courier':
                return view('kurir.live_tracking');
            case 'customer':
                return view('User.live_tracking');
            default:
                return redirect()->route('login');
        }
    }

    /**
     * Menampilkan halaman live tracking untuk guest (public).
     */
    public function publicTracking()
    {
        return view('public.live_tracking');
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
                'tracking_number' => 'required|exists:shipments,tracking_number',
                'lat' => 'required|numeric',
                'long' => 'required|numeric',
            ]);

            // Cek apakah user adalah kurir
            $user = Auth::user();
            // PERBAIKAN: Cek role dengan benar
            if ($user->role->role_name !== 'courier') {
                return response()->json(['message' => 'Unauthorized. Hanya kurir yang dapat memperbarui lokasi.'], 403);
            }

            // Cek status pengiriman sebelum update lokasi
            $shipment = Shipment::where('tracking_number', $request->tracking_number)->first();
            if (!$shipment || in_array(strtolower(trim($shipment->currentStatus)), $this->finishedStatuses)) {
                return response()->json(['message' => 'Tidak dapat memperbarui lokasi, pengiriman sudah selesai atau tidak ditemukan.'], 400);
            }

            // Cek apakah kurir yang login adalah kurir yang bertugas untuk pengiriman ini
            if (!$shipment || $shipment->courierUserID !== $user->user_id) {
                return response()->json(['message' => 'Unauthorized. Anda tidak bertugas untuk pengiriman ini.'], 403);
            }

            // Cek status setelah otorisasi
            if (in_array(strtolower(trim($shipment->currentStatus)), $this->finishedStatuses)) {
                return response()->json(['message' => 'Tidak dapat memperbarui lokasi, pengiriman sudah selesai.'], 400);
            }

            // Perbarui kolom lokasi dan waktu terakhir dilacak di tabel 'shipments'
            $updated = $shipment->update([
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
     * API endpoint untuk mendapatkan lokasi pengiriman dan status untuk tampilan pengguna/publik.
     * Dapat diakses oleh semua role dan guest.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getShipmentLocation(Request $request)
    {
        try {
            // Validasi input nomor resi
            $request->validate([
                'tracking_number' => 'required|string|exists:shipments,tracking_number',
            ], [
                'tracking_number.required' => 'Nomor resi wajib diisi.',
                'tracking_number.exists' => 'Nomor resi tidak ditemukan.'
            ]);

            // Ambil data pengiriman dengan relasi ke order
            $shipment = Shipment::with('order')->where('tracking_number', $request->tracking_number)->first();

            // Otorisasi sederhana (opsional, bisa disesuaikan)
            // Jika user adalah customer, pastikan dia adalah pemilik order
            if (Auth::check() && Auth::user()->role->role_name === 'customer') {
                if ($shipment->order->senderUserID !== Auth::id()) {
                     return response()->json(['message' => 'Unauthorized. Anda tidak memiliki akses ke pengiriman ini.'], 403);
                }
            }

            $lastTrackedAt = $shipment->last_updated_at
                ? Carbon::parse($shipment->last_updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                : 'N/A';

            // Siapkan data detail untuk ditampilkan di frontend
            $shipmentDetails = [
                'receiver_name' => $shipment->order->receiverName,
                'receiver_address' => $shipment->order->receiverAddress,
                'item_type' => $shipment->itemType,
                'weight_kg' => $shipment->weightKG,
            ];

            // Cek apakah pengiriman sudah selesai
            if (in_array(strtolower(trim($shipment->currentStatus)), $this->finishedStatuses)) {
                return response()->json([
                    'status' => 'finished',
                    'message' => 'Pengiriman untuk nomor resi ini telah selesai.',
                    'shipment_status' => $shipment->currentStatus,
                    'last_tracked_at' => $lastTrackedAt,
                    'details' => $shipmentDetails,
                ], 200);
            }

            // Cek apakah pelacakan live aktif
            $isTrackingActive = $shipment->current_lat && $shipment->current_long &&
                                Carbon::parse($shipment->last_updated_at)->diffInMinutes(now()) < 5;

            if ($isTrackingActive) {
                // Jika pelacakan aktif, kirim lokasi.
                return response()->json([
                    'tracking_status' => 'active',
                    'lat' => $shipment->current_lat,
                    'long' => $shipment->current_long,
                    'shipment_status' => $shipment->currentStatus,
                    'last_tracked_at' => $lastTrackedAt,
                ]);
            } else {
                // Jika tidak aktif, kirim pesan informatif beserta detail pengiriman.
                return response()->json([
                    'tracking_status' => 'inactive',
                    'message' => 'Pelacakan live akan aktif saat kurir sedang dalam perjalanan menuju lokasi Anda.',
                    'shipment_status' => $shipment->currentStatus,
                    'last_tracked_at' => $lastTrackedAt,
                    'details' => $shipmentDetails,
                ]);
            }

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['message' => $e->validator->errors()->first()], 422);
        } catch (\Exception $e) {
            Log::error('Error in getShipmentLocation: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal melacak: Terjadi kesalahan internal server.'], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan semua pengiriman yang sedang aktif (untuk admin).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllActiveShipments()
    {
        try {
            // Hanya admin yang bisa mengakses
            if (!Auth::check() || Auth::user()->role !== 'admin') {
                return response()->json(['message' => 'Unauthorized. Hanya admin yang dapat mengakses data ini.'], 403);
            }

            // Ambil semua pengiriman yang belum selesai dan memiliki lokasi
            $shipments = Shipment::select('tracking_number', 'current_lat', 'current_long', 'currentStatus', 'last_updated_at')
                                ->whereNotIn(DB::raw('LOWER(TRIM(currentStatus))'), $this->finishedStatuses)
                                ->whereNotNull('current_lat')
                                ->whereNotNull('current_long')
                                ->get();

            return response()->json([
                'shipments' => $shipments->map(function ($shipment) {
                    return [
                        'tracking_number' => $shipment->tracking_number,
                        'lat' => $shipment->current_lat,
                        'long' => $shipment->current_long,
                        'status' => $shipment->currentStatus,
                        'last_tracked_at' => $shipment->last_updated_at
                            ? Carbon::parse($shipment->last_updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                            : 'N/A',
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getAllActiveShipments: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal mengambil data pengiriman aktif.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan pengiriman milik customer yang login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCustomerShipments()
    {
        try {
            // Hanya customer yang bisa mengakses
            if (!Auth::check() || Auth::user()->role !== 'customer') {
                return response()->json(['message' => 'Unauthorized. Hanya customer yang dapat mengakses data ini.'], 403);
            }

            // Ambil pengiriman milik customer yang login
            $shipments = Shipment::select('tracking_number', 'current_lat', 'current_long', 'currentStatus', 'last_updated_at')
                                ->where('customer_id', Auth::id())
                                ->whereNotNull('current_lat')
                                ->whereNotNull('current_long')
                                ->get();

            return response()->json([
                'shipments' => $shipments->map(function ($shipment) {
                    return [
                        'tracking_number' => $shipment->tracking_number,
                        'lat' => $shipment->current_lat,
                        'long' => $shipment->current_long,
                        'status' => $shipment->currentStatus,
                        'last_tracked_at' => $shipment->last_updated_at
                            ? Carbon::parse($shipment->last_updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                            : 'N/A',
                        'is_finished' => in_array(strtolower(trim($shipment->currentStatus)), $this->finishedStatuses)
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getCustomerShipments: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal mengambil data pengiriman customer.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan pengiriman yang ditugaskan ke kurir yang login.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getCourierShipments()
    {
        try {
            // Hanya kurir yang bisa mengakses
            if (!Auth::check() || Auth::user()->role !== 'courier') {
                return response()->json(['message' => 'Unauthorized. Hanya kurir yang dapat mengakses data ini.'], 403);
            }

            // Ambil pengiriman yang ditugaskan ke kurir yang login
            $shipments = Shipment::select('tracking_number', 'current_lat', 'current_long', 'currentStatus', 'last_updated_at')
                                ->where('courier_id', Auth::id())
                                ->whereNotNull('current_lat')
                                ->whereNotNull('current_long')
                                ->get();

            return response()->json([
                'shipments' => $shipments->map(function ($shipment) {
                    return [
                        'tracking_number' => $shipment->tracking_number,
                        'lat' => $shipment->current_lat,
                        'long' => $shipment->current_long,
                        'status' => $shipment->currentStatus,
                        'last_tracked_at' => $shipment->last_updated_at
                            ? Carbon::parse($shipment->last_updated_at)->setTimezone('Asia/Jakarta')->format('d M Y, H:i:s') . ' WIB'
                            : 'N/A',
                        'is_finished' => in_array(strtolower(trim($shipment->currentStatus)), $this->finishedStatuses)
                    ];
                })
            ]);

        } catch (\Exception $e) {
            Log::error('Error in getCourierShipments: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return response()->json([
                'message' => 'Gagal mengambil data pengiriman kurir.',
                'error_detail' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * API endpoint untuk mendapatkan daftar pengiriman AKTIF yang ditugaskan ke kurir.
     * Digunakan untuk mengisi dropdown di halaman live tracking kurir.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function getActiveCourierShipments()
    {
        try {
            $kurirId = Auth::id();

            // Ambil pengiriman yang ditugaskan ke kurir dan BELUM selesai
            $shipments = Shipment::with('order:orderID,receiverName,receiverAddress')
                                ->where('courierUserID', $kurirId)
                                ->whereNotIn(DB::raw('LOWER(TRIM("currentStatus"))'), $this->finishedStatuses)
                                ->select('tracking_number', 'orderID')
                                ->get();

            return response()->json(['shipments' => $shipments]);

        } catch (\Exception $e) {
            Log::error('Error in getActiveCourierShipments: ' . $e->getMessage());
            return response()->json(['message' => 'Gagal mengambil daftar pengiriman.'], 500);
        }
    }
}