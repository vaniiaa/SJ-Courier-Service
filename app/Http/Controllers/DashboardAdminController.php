<?php

/**
 * Nama File: DashboardAdminController.php
 * Deskripsi: Controller ini berfungsi untuk menampilkan data realtime dari jumlah kurir, jumlah pengiriman, dan wilayah pengiriman.
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 */


namespace App\Http\Controllers; // Mendefinisikan namespace untuk controller ini, membantu dalam organisasi kode.

use Illuminate\Http\Request; // Mengimpor kelas Request, meskipun tidak digunakan secara langsung di controller ini, seringkali ada secara default.
use App\Models\Kurir; // Mengimpor model Kurir, merepresentasikan tabel 'kurir' di database.
use App\Models\Pengiriman; // Mengimpor model Pengiriman, merepresentasikan tabel 'pengiriman' di database.

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
        $totalKurir = Kurir::count();

        // Menghitung total jumlah pengiriman yang tercatat di database.
        $totalPengiriman = Pengiriman::count();

        // Menghitung total jumlah wilayah pengiriman unik berdasarkan kolom 'alamat_tujuan'.
        $totalWilayah = Pengiriman::distinct('alamat_tujuan')->count('alamat_tujuan');

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
        // Mengambil data pengiriman.
        // Memilih kolom 'alamat_tujuan'.
        // Menghitung jumlah pengiriman untuk setiap wilayah dan memberinya alias 'total'.
        // Mengelompokkan hasil berdasarkan 'alamat_tujuan'.
        // Menjalankan query dan mendapatkan hasilnya sebagai koleksi.
        $data = Pengiriman::select('alamat_tujuan')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('alamat_tujuan')
            ->get();

        // Mengembalikan data yang diambil dalam format JSON.
        return response()->json($data);
    }
}