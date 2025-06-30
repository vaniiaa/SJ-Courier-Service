<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class TarifController extends Controller
{
    /**
     * Menghitung tarif pengiriman menggunakan OSRM (OpenStreetMap Routing Machine) dan Nominatim (Geocoding).
     * Ini adalah alternatif gratis jika Google Maps API tidak dapat digunakan.
     */
    public function hitungTarif(Request $request)
    {
        // 1. Validasi Input
        $request->validate([
            'asal' => 'required|string|max:255',
            'tujuan' => 'required|string|max:255',
            'berat_kategori' => 'required|in:1-5,5-10',
        ]);

        $beratKategori = $request->input('berat_kategori');
        $asal = $request->input('asal');
        $tujuan = $request->input('tujuan');

        // 2. Tentukan Tarif per Kilometer
        $tarifPerKm = 0;
        if ($beratKategori == '1-5') {
            $tarifPerKm = 4000;
        } elseif ($beratKategori == '5-10') {
            $tarifPerKm = 5000;
        } else {
            return redirect()->back()->with('error', 'Kategori berat tidak valid.')->withInput();
        }

        $jarak = 0; // Inisialisasi jarak dalam KM

        try {
            // --- Langkah A: Geocoding (Mengubah Nama Lokasi menjadi Koordinat) ---
            // Menggunakan Nominatim (OpenStreetMap's Geocoder)
            $nominatimBaseUrl = 'https://nominatim.openstreetmap.org/search';

            // Menambahkan "Batam, Indonesia" agar hasil lebih spesifik di Batam
            $coordsAsal = $this->getCoordinates($asal . ', Batam, Indonesia', $nominatimBaseUrl);
            $coordsTujuan = $this->getCoordinates($tujuan . ', Batam, Indonesia', $nominatimBaseUrl);

            if (!$coordsAsal || !$coordsTujuan) {
                return redirect()->back()->with('error', 'Gagal menemukan koordinat untuk salah satu atau kedua lokasi. Pastikan nama lokasi sudah benar (contoh: Piayu, Batam) dan dapat ditemukan.')->withInput();
            }

            // --- Langkah B: Routing (Mendapatkan Jarak dari Koordinat Menggunakan OSRM) ---
            // OSRM Demo Server (sangat terbatas untuk penggunaan produksi!)
            $osrmUrl = "http://router.project-osrm.org/route/v1/driving/";
            $osrmResponse = Http::timeout(10)->get( // Timeout 10 detik untuk antisipasi server demo lambat
                $osrmUrl . "{$coordsAsal['lon']},{$coordsAsal['lat']};{$coordsTujuan['lon']},{$coordsTujuan['lat']}",
                ['overview' => 'false', 'alternatives' => 'false', 'steps' => 'false']
            );

            $osrmData = $osrmResponse->json();

            // Log respons OSRM untuk debugging (bisa dihapus setelah yakin berfungsi)
            // Log::info('OSRM API Response:', ['response' => $osrmData]);

            // Periksa kegagalan respons HTTP atau jika data jarak tidak ada
            if ($osrmResponse->failed() || !isset($osrmData['routes'][0]['distance'])) {
                Log::error('OSRM routing failed or distance not found:', ['response' => $osrmData, 'status' => $osrmResponse->status()]);
                
                $errorMessage = "Gagal menghitung jarak rute. ";
                if (isset($osrmData['code'])) {
                    $errorMessage .= "Kode OSRM: " . $osrmData['code'] . ". ";
                }
                if (isset($osrmData['message'])) {
                    $errorMessage .= "Pesan: " . $osrmData['message'] . ". ";
                } else if ($osrmResponse->status() === 429) { // Too Many Requests
                    $errorMessage .= "Terlalu banyak permintaan ke server peta. Mohon coba lagi beberapa saat.";
                } else {
                    $errorMessage .= "Server peta tidak merespon atau terjadi kesalahan tidak dikenal.";
                }
                return redirect()->back()->with('error', $errorMessage)->withInput();
            }

            $distanceInMeters = $osrmData['routes'][0]['distance']; // Jarak dalam meter
            $jarak = ceil($distanceInMeters / 1000); // Konversi ke KM dan bulatkan ke atas

        } catch (\Exception $e) {
            // Menangkap exception umum (misal masalah koneksi jaringan)
            Log::error('Exception during OSM API call: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem saat menghubungi layanan peta (OSM). Mohon coba lagi.')->withInput();
        }

        // Pastikan jarak yang didapat valid (lebih dari 0 km)
        if ($jarak <= 0) {
            return redirect()->back()->with('error', 'Jarak tidak dapat ditentukan atau hasilnya nol. Pastikan input lokasi valid dan dapat dijangkau.')->withInput();
        }

        // 4. Hitung Total Tarif Akhir
        $totalTarif = $tarifPerKm * $jarak;

        // 5. Simpan Data Pengiriman ke Session untuk Ditampilkan di View
        $dataPengiriman = [
            'asal' => $asal,
            'tujuan' => $tujuan,
            'berat_kategori' => $beratKategori,
            'jarak' => $jarak,
        ];

        // 6. Redirect Kembali ke Halaman Dashboard dengan Hasil
        return redirect()->back()
                        ->with('tarif', $totalTarif)
                        ->with('data_pengiriman', $dataPengiriman)
                        ->withInput();
    }

    /**
     * Helper function untuk mendapatkan koordinat (lintang & bujur) dari nama lokasi
     * menggunakan Nominatim (OpenStreetMap's Geocoder).
     */
    private function getCoordinates(string $locationName, string $nominatimBaseUrl): ?array
    {
        try {
            $response = Http::timeout(10)->get($nominatimBaseUrl, [
                'q' => $locationName,
                'format' => 'json',
                'limit' => 1,
                'addressdetails' => 0, // Tidak perlu detail alamat, hemat bandwidth
                'accept-language' => 'id', // Preferensi bahasa respon (Indonesia)
                'email' => 'your-email@example.com' // Disarankan oleh Nominatim untuk identifikasi (ganti dengan email Anda)
            ]);

            $data = $response->json();

            // Log respons Nominatim untuk debugging (bisa dihapus setelah yakin berfungsi)
            // Log::info("Nominatim API Response for '{$locationName}':", ['response' => $data]);

            if (!empty($data) && isset($data[0]['lat']) && isset($data[0]['lon'])) {
                return [
                    'lat' => $data[0]['lat'],
                    'lon' => $data[0]['lon'],
                ];
            }
        } catch (\Exception $e) {
            Log::error("Geocoding failed for '{$locationName}': " . $e->getMessage());
        }
        return null; // Gagal mendapatkan koordinat
    }
}