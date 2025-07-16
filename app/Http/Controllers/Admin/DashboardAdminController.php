<?php

/**
 * Nama File: DashboardAdminController.php
 * Deskripsi: Controller ini berfungsi untuk menampilkan data realtime dari jumlah kurir, jumlah pengiriman, dan wilayah pengiriman.
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 */


namespace App\Http\Controllers\Admin; // Mendefinisikan namespace untuk controller ini, membantu dalam organisasi kode.

use Illuminate\Http\Request; // Mengimpor kelas Request, meskipun tidak digunakan secara langsung di controller ini, seringkali ada secara default.
use App\Models\Shipment; // Mengimpor model Shipment yang baru
use App\Models\DeliveryArea; // Mengimpor model DeliveryArea untuk data wilayah
use App\Models\User; // Mengimpor model User, merepresentasikan tabel 'users' di database yang berisi data pengguna termasuk kurir.
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB; // Mengimpor DB facade untuk query kompleks

class DashboardAdminController extends Controller // Mendefinisikan kelas controller, yang mewarisi fungsionalitas dasar dari kelas Controller Laravel.
{
    /**
     * Fungsi ini bertanggung jawab untuk menyiapkan dan menampilkan halaman dashboard admin.
     * Ia mengambil total jumlah kurir, pengiriman, dan wilayah pengiriman yang unik.
     *
     * @return \Illuminate\View\View Mengembalikan view 'admin.dashboard_admin' dengan data statistik.
     */
    public function index()
    {
        // Menghitung total jumlah kurir yang terdaftar di database.
        $totalKurir =User::whereHas('role', function($q) {
        $q->where('role_name', 'courier');
        })->count();
        
        // Menghitung total jumlah pengiriman menggunakan model Shipment yang baru.
        $totalPengiriman = Shipment::count();

        // Menghitung total jumlah wilayah pengiriman dari tabel delivery_area.
        $totalWilayah = DeliveryArea::count();

        // Mengembalikan view 'admin.dashboard_admin' dan meneruskan variabel-variabel statistik
        // agar dapat ditampilkan di halaman dashboard.
        return view('admin.dashboard_admin', compact('totalKurir', 'totalPengiriman', 'totalWilayah'));
    }

    /**
     * Fungsi ini digunakan untuk mengambil data jumlah pengiriman per wilayah tujuan.
     * Hasilnya dikembalikan dalam format JSON, cocok untuk digunakan oleh chart atau grafik di frontend.
     *
     * @return \Illuminate\Http\JsonResponse Mengembalikan data pengiriman per wilayah dalam format JSON.
     */
    public function getPengirimanPerWilayah()
{
    $data = DeliveryArea::query()
        ->leftJoin('users', function ($join) {
            $join->on('delivery_area.area_id', '=', 'users.area_id')
                 ->where('users.role_id', 2);
        })
        // GANTI 'courierUserID' DENGAN NAMA KOLOM YANG BENAR (misal: 'courier_user_id')
        ->leftJoin('shipments', 'users.user_id', '=', 'shipments.courierUserID') 
        // GANTI 'shipmentID' DENGAN NAMA KOLOM YANG BENAR (misal: 'shipment_id')
        ->select('delivery_area.area_name', DB::raw('COUNT(shipments."shipmentID") as total')) 
        ->groupBy('delivery_area.area_name')
        ->orderBy('delivery_area.area_name', 'asc')
        ->get();

    return response()->json($data);
}
}