<?php

/**
 * Nama File: KelolaKurirController.php
 * Deskripsi: Controller ini menangani operasi pengelolaan daftar kurir,
 * termasuk menampilkan daftar dengan fitur pencarian dan paginasi.
 * Dibuat Oleh: [Aulia Sabrina] - [3312301002]
 * Tanggal: 25 Mei 2025
 */

 // Mendefinisikan namespace untuk controller.
namespace App\Http\Controllers; 

// Mengimpor kelas Request untuk menangani permintaan HTTP.
use Illuminate\Http\Request; 

// Mengimpor model Kurir untuk berinteraksi dengan tabel kurir di database.
use App\Models\Kurir; 

// Mendefinisikan kelas controller.
class KelolaKurirController extends Controller 
{
    /**
     * Menampilkan daftar kurir dengan fitur pencarian dan paginasi.
     *
     * @param  \Illuminate\Http\Request  $request Objek request berisi parameter pencarian.
     * @return \Illuminate\View\View Mengembalikan view 'admin.kelola_kurir' dengan data kurir.
     */
    public function index(Request $request)
    {
        // Mengambil input pencarian dari request.
        $search = $request->input('search');

        // Mengambil data kurir. Jika ada pencarian, filter berdasarkan nama, email, atau username.
        // Hasilnya dipaginasi 10 item per halaman.
        // `appends($request->query())` memastikan parameter pencarian tetap ada di URL paginasi.
        $kurirs = Kurir::when($search, function ($query, $search) {
            return $query->where('nama', 'like', '%' . $search . '%')
                         ->orWhere('email', 'like', '%' . $search . '%')
                         ->orWhere('username', 'like', '%' . $search . '%');
        })->paginate(10)->appends($request->query());

        // Mengirimkan data kurir yang sudah dipaginasi dan difilter ke view.
        return view('admin.kelola_kurir', compact('kurirs'));
    }

}