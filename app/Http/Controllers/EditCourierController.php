<?php

/**
 * Nama File: EditCourierController.php
 * Deskripsi: Controller ini menangani operasi pengeditan untuk data akun kurir.
 * Termasuk menampilkan form pengeditan dan memproses pembaruan data kurir.
 * Dibuat Oleh: [Nama Anda/Tim Anda]
 * Tanggal: 25 Mei 2025
 */

// Mendefinisikan namespace untuk controller ini.
namespace App\Http\Controllers; 

 // Mengimpor kelas Request untuk menangani permintaan HTTP.
use Illuminate\Http\Request; 

// Mengimpor model Kurir yang merepresentasikan tabel 'kurir' di database.
use App\Models\Kurir; 

// Mengimpor facade Hash untuk mengenkripsi password.
use Illuminate\Support\Facades\Hash; 

// Mendefinisikan kelas controller, yang mewarisi fungsionalitas dasar dari kelas Controller Laravel.
class EditCourierController extends Controller
{
    /**
     * Menampilkan form untuk mengedit data kurir berdasarkan ID yang diberikan.
     * Jika kurir dengan ID tersebut tidak ditemukan, sistem akan otomatis menampilkan halaman 404.
     *
     * @param int $id ID dari kurir yang akan diedit.
     * @return \Illuminate\View\View Mengembalikan view 'admin.edit_kurir' dengan data kurir yang ditemukan.
     */
    public function editKurir($id)
    {
        // Mencari data kurir berdasarkan ID. Jika tidak ditemukan, akan memicu error 404.
        $kurir = Kurir::findOrFail($id);
        
        // Mengembalikan view 'admin.edit_kurir' dan meneruskan objek kurir yang ditemukan.
        return view('admin.edit_kurir', compact('kurir'));
    }

    /**
     * Memperbarui data kurir yang sudah ada di database.
     * Fungsi ini menerima data dari form, melakukan validasi, dan kemudian menyimpan perubahan.
     * Password hanya akan diperbarui jika ada input password baru.
     *
     * @param Request $request Objek Request yang berisi data yang dikirimkan melalui form.
     * @param int $id ID dari kurir yang akan diperbarui.
     * @return \Illuminate\Http\RedirectResponse Mengarahkan kembali ke halaman kelola kurir dengan pesan sukses.
     */
    public function updateKurir(Request $request, $id)
    {
        // Melakukan validasi data yang diterima dari form.
        $request->validate([
            'nama' => 'required|string|max:100', // Nama wajib, string, maks 100 karakter.
            'email' => 'required|email|max:100', // Email wajib, format email, maks 100 karakter.
            'no_hp' => 'required|string|max:20', // Nomor HP wajib, string, maks 20 karakter.
            'alamat' => 'required|string', // Alamat wajib, berupa string.
            'wilayah_pengiriman' => 'required|string', // Wilayah pengiriman wajib, berupa string.
            'username' => 'required|string|max:50', // Username wajib, string, maks 50 karakter.
            // Password tidak divalidasi 'required' di sini karena bersifat opsional (hanya jika diubah).
        ]);

        // Mencari data kurir yang akan diperbarui berdasarkan ID. Jika tidak ditemukan, akan memicu error 404.
        $kurir = Kurir::findOrFail($id);

        // Memperbarui atribut-atribut kurir dengan data dari request.
        $kurir->nama = $request->nama;
        $kurir->email = $request->email;
        $kurir->no_hp = $request->no_hp;
        $kurir->alamat = $request->alamat;
        $kurir->wilayah_pengiriman = $request->wilayah_pengiriman;
        $kurir->username = $request->username;

        // Mengecek apakah input 'password' diisi oleh pengguna.
        // Jika diisi, password baru akan di-hash dan disimpan.
        if ($request->filled('password')) {
            // Mengenkripsi password baru sebelum menyimpannya ke database.
            $kurir->password = Hash::make($request->password);
        }

        // Menyimpan perubahan data kurir ke database.
        $kurir->save();

        // Mengarahkan kembali pengguna ke route 'admin.kelola_kurir' setelah sukses,
        // dan menyertakan pesan 'success' untuk ditampilkan.
        return redirect()->route('admin.kelola_kurir')->with('success', 'Data kurir berhasil diperbarui.');
    }
}