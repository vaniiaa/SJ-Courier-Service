<?php

/**
 * Nama File: CourierLoginController.php
 * Deskripsi: Controller ini dibuat agar kurir dapat melakukan login pada aplikasi
 * Dibuat Oleh: [Aulia Sabrina] - NIM [3312301002]
 * Tanggal: 25 Mei 2025
 */


namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth; // Untuk fungsi autentikasi
use Illuminate\Support\Facades\Hash; // Untuk memverifikasi password
use App\Models\Kurir; // Pastikan Anda mengimpor Model Kurir

class CourierLoginController extends Controller
{
      /**
     * Menampilkan form login kurir.
     *
     * @return \Illuminate\View\View
     */
    public function showLoginForm()
    {
        return view('auth.kurir.masuk'); // View untuk form login
    }

    /**
     * Menangani proses login kurir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function login(Request $request)
    {
        // 1. Validasi Input: Pastikan username dan password tidak kosong
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ], [
            'username.required' => 'Username wajib diisi.',
            'password.required' => 'Password wajib diisi.',
        ]);

        // 2. Coba cari kurir berdasarkan username di database
        $kurir = Kurir::where('username', $request->username)->first();

        // 3. Verifikasi: Jika kurir ditemukan DAN password cocok (setelah di-hash)
        if ($kurir && Hash::check($request->password, $kurir->password)) {
            // Login sukses: Masukkan kurir ke sesi autentikasi dengan guard 'kurir'
            Auth::guard('kurir')->login($kurir);
            $request->session()->regenerate(); // Regenerasi ID sesi untuk keamanan

            // Arahkan ke dashboard kurir setelah login berhasil
            return redirect()->intended('/kurir/dashboard'); // Ganti dengan route dashboard kurir Anda
        }

        // 4. Login Gagal: Kembali ke form login dengan pesan error
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username'); // Hanya menyimpan input username sebelumnya
    }

    /**
     * Menangani proses logout kurir.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function logout(Request $request)
    {
        Auth::guard('kurir')->logout(); // Logout dari guard 'kurir'
        $request->session()->invalidate(); // Hapus semua data sesi
        $request->session()->regenerateToken(); // Buat token CSRF baru

        return redirect('/auth/kurir/masuk'); // Arahkan kembali ke halaman login
    }
}
