<?php

/**
 * Nama File: CreateCourierController.php
 * Deskripsi: Controller ini dibuat agar admin dapat menambah data-data akun pada aplikasi termasuk emai,
 * no.hp, alamat, wilayah pengiriman, password, dan username
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 */

namespace App\Http\Controllers; // Mendefinisikan namespace untuk controller ini, agar mudah diorganisir dan diakses.

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani permintaan HTTP dari pengguna.
use App\Models\Kurir; // Mengimpor model Kurir, yang merepresentasikan tabel 'kurir' di database.
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk mengamankan password.

class CreateCourierController extends Controller // Mendefinisikan kelas controller, yang mewarisi fungsionalitas dasar dari kelas Controller Laravel.
{
    /**
     * Fungsi ini bertanggung jawab untuk menampilkan daftar kurir yang ada.
     * Ia juga mendukung fungsionalitas pencarian berdasarkan nama, username, atau email kurir.
     *
     * @param Request $request Objek Request yang berisi data permintaan HTTP, termasuk parameter pencarian.
     * @return \Illuminate\View\View Mengembalikan view 'admin.kelola_kurir' dengan data kurir yang sudah difilter atau semua kurir.
     */
    public function index(Request $request)
    {
        // Mengambil nilai input 'search' dari permintaan HTTP. Jika tidak ada, nilainya akan null.
        $search = $request->input('search');

        // Mengambil data kurir dari database.
        $kurirs = Kurir::when($search, function ($query, $search) {
            // Jika ada parameter 'search', tambahkan kondisi pencarian ke query.
            return $query->where('nama', 'like', '%' . $search . '%') // Mencari kurir berdasarkan nama (case-insensitive).
                         ->orWhere('username', 'like', '%' . $search . '%') // Mencari kurir berdasarkan username.
                         ->orWhere('email', 'like', '%' . $search . '%'); // Mencari kurir berdasarkan email.
        })->paginate(10); // Melakukan paginasi hasil, menampilkan 10 data per halaman.

        // Mengembalikan view 'admin.kelola_kurir' dan meneruskan data kurir yang telah diambil.
        return view('admin.kelola_kurir', compact('kurirs'));
    }

    /**
     * Fungsi ini menampilkan form untuk menambah data kurir baru.
     *
     * @return \Illuminate\View\View Mengembalikan view 'admin.tambah_kurir' yang berisi form.
     */
    public function create()
    {
        // Mengembalikan view yang menampilkan form penambahan kurir.
        return view('admin.tambah_kurir');
    }

    /**
     * Fungsi ini menangani penyimpanan data kurir baru yang dikirim dari form.
     * Ia melakukan validasi data dan mengenkripsi password sebelum menyimpannya ke database.
     *
     * @param Request $request Objek Request yang berisi data yang dikirimkan melalui form.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan kembali ke halaman kelola kurir dengan pesan sukses atau error.
     */
    public function store(Request $request)
    {
        // Melakukan validasi data yang diterima dari form.
        $request->validate([
            'nama' => 'required|string|max:255', // Nama wajib diisi, berupa string, maksimal 255 karakter.
            'email' => 'required|email|unique:kurir', // Email wajib, format email valid, harus unik di tabel 'kurir'.
            'no_hp' => 'required|string|max:20', // Nomor HP wajib, berupa string, maksimal 20 karakter.
            'alamat' => 'required|string|max:255', // Alamat wajib, berupa string, maksimal 255 karakter.
            'wilayah_pengiriman' => 'required|string|max:255', // Wilayah pengiriman wajib, string, maksimal 255 karakter.
            'username' => 'required|string|unique:kurir,username', // Username wajib, string, harus unik di kolom 'username' tabel 'kurir'.
            'password' => 'required|string|min:6', // Password wajib, string, minimal 6 karakter.
        ]);

        // Membuat entri baru di tabel 'kurir' dengan data yang sudah divalidasi.
        Kurir::create([
            'nama' => $request->nama, // Mengisi kolom 'nama' dengan nilai dari input 'nama'.
            'email' => $request->email, // Mengisi kolom 'email' dengan nilai dari input 'email'.
            'no_hp' => $request->no_hp, // Mengisi kolom 'no_hp' dengan nilai dari input 'no_hp'.
            'alamat' => $request->alamat, // Mengisi kolom 'alamat' dengan nilai dari input 'alamat'.
            'wilayah_pengiriman' => $request->wilayah_pengiriman, // Mengisi kolom 'wilayah_pengiriman' dengan nilai dari input 'wilayah_pengiriman'.
            'username' => $request->username, // Mengisi kolom 'username' dengan nilai dari input 'username'.
            'password' => Hash::make($request->password), // Mengenkripsi password menggunakan Hash::make() sebelum disimpan.
        ]);

        // Mengarahkan kembali pengguna ke route 'admin.kelola_kurir' setelah sukses menyimpan data,
        // dan menyertakan pesan 'success' yang akan ditampilkan di halaman selanjutnya.
        return redirect()->route('admin.kelola_kurir')->with('success', 'Kurir berhasil ditambahkan.');
    }
}