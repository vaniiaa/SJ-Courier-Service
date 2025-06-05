<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Kurir; // Pastikan ini diimpor
use App\Models\Pengiriman; // Pastikan ini diimpor
use Illuminate\Support\Facades\Auth; // Jika Anda akan memfilter berdasarkan kurir yang login


class KelolaPengirimanController extends Controller
{
    // Method ini tetap untuk API endpoint jika diperlukan di frontend
    public function getKurirByWilayah($wilayah)
    {
        $wilayah = strtolower(trim($wilayah));
        $kurirs = Kurir::whereRaw('LOWER(TRIM(wilayah_pengiriman)) LIKE ?', ['%' . $wilayah . '%'])->get(['id', 'nama', 'username']);

        if ($kurirs->isEmpty()) {
            return response()->json(['message' => 'Tidak ada kurir untuk wilayah ini.'], 404);
        }

        return response()->json($kurirs);
    }

    /**
     * Fungsi untuk menampilkan halaman daftar pengiriman untuk kurir.
     * Filter berdasarkan kurir yang login.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function daftarPengiriman(Request $request)
    {
        // Mendapatkan identifier kurir yang sedang login
        $loggedInKurir = Auth::guard('kurir')->user(); // Sesuaikan guard jika berbeda (misal 'web')

        $query = Pengiriman::with('kurir'); // Memuat relasi kurir

        // Filter pengiriman berdasarkan nama kurir yang sedang login (asumsi kolom 'kurir' menyimpan nama kurir)
        if ($loggedInKurir) {
            $query->where('kurir', $loggedInKurir->nama);
        } else {
            // Jika tidak ada kurir login, tampilkan koleksi kosong
            $pengirimans = collect();
            return view('kurir.daftar_pengiriman', compact('pengirimans'));
        }

        // Tambahkan fungsionalitas pencarian (jika diperlukan untuk kurir)
        $search = $request->input('search');
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('resi', 'like', '%' . $search . '%')
                  ->orWhere('nama_pengirim', 'like', '%' . $search . '%')
                  ->orWhere('nama_penerima', 'like', '%' . $search . '%');
            });
        }

        // Ambil data dan paginasi
        $pengirimans = $query->paginate(10); // Asumsi 10 item per halaman

        // Mengembalikan view Blade dengan data dari database
        return view('kurir.daftar_pengiriman', compact('pengirimans'));
    }
}
