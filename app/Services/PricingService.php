<?php

namespace App\Services;

use App\Models\PricingTier;
use Exception;
use Illuminate\Support\Facades\Log;

class PricingService
{
    /**
     * Dapatkan harga per KM berdasarkan berat.
     */
    protected function getPricePerKm(float $weight): int
    {
        $tier = PricingTier::where('minWeightKG', '<=', $weight)
                           ->where('maxWeightKG', '>=', $weight)
                           ->first();

        if (!$tier) {
            Log::error("PricingService: Tier harga tidak ditemukan untuk berat: {$weight} Kg.");
            throw new Exception("Tidak ada tingkatan harga yang sesuai untuk berat paket Anda ({$weight} Kg).");
        }
        Log::info("PricingService: Ditemukan tier '{$tier->description}' dengan harga Rp {$tier->pricePerKM}/Km untuk berat {$weight} Kg.");
        return $tier->pricePerKM;
    }

    /**
     * Hitung jarak antara dua titik koordinat menggunakan formula Haversine.
     * @param float $lat1 Latitude titik awal
     * @param float $lon1 Longitude titik awal
     * @param float $lat2 Latitude titik akhir
     * @param float $lon2 Longitude titik akhir
     * @return float Jarak dalam kilometer
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        if (($lat1 == $lat2) && ($lon1 == $lon2)) {
            return 0; // Jarak nol jika titiknya sama
        }

        $earthRadius = 6371; // Radius bumi dalam kilometer

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);

        $a = sin($dLat / 2) * sin($dLat / 2) +
             sin($dLon / 2) * sin($dLon / 2) * cos($lat1Rad) * cos($lat2Rad);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        Log::info("PricingService: Menghitung jarak Haversine: ($lat1, $lon1) ke ($lat2, $lon2) = {$distance} Km.");
        return round($distance, 2); // Bulatkan ke 2 desimal
    }

    /**
     * Hitung estimasi harga pengiriman.
     */
    public function calculateEstimatedPrice(float $weight, float $distance): int
    {
        if ($distance <= 0 && $weight > 0) { // Perbolehkan jarak 0 jika berat ada (mungkin kasus khusus)
             Log::warning("PricingService: Jarak {$distance} Km, mengembalikan harga 0.");
            return 0; // Atau harga minimal jika ada aturan
        }
         if ($weight <= 0) {
            Log::error("PricingService: Berat tidak valid ({$weight} Kg) untuk kalkulasi harga.");
            throw new Exception("Berat barang tidak valid untuk perhitungan harga.");
        }

        $pricePerKm = $this->getPricePerKm($weight);
        $estimatedPrice = $distance * $pricePerKm;
        $finalPrice = (int) ceil($estimatedPrice); // Dibulatkan ke atas

        Log::info("PricingService: Kalkulasi harga: Berat {$weight}Kg, Jarak {$distance}Km, Harga/Km Rp {$pricePerKm} = Estimasi Rp {$estimatedPrice}, Final Rp {$finalPrice}.");
        return $finalPrice;
    }
}