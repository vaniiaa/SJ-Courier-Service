<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash; // Penting: untuk mengenkripsi password baru
use Illuminate\Validation\ValidationException; // Penting: untuk menangani error validasi

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil pengguna.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Memperbarui informasi profil pengguna untuk bidang tertentu.
     * Metode ini menangani update field seperti nama, email, telepon, alamat, dan password.
     */
    public function updateField(Request $request, $field): RedirectResponse
    {
        $user = $request->user();

        try {
            switch ($field) {
                case 'name':
                    // Validasi untuk nama
                    $request->validate([
                        'value' => ['required', 'string', 'max:255'],
                    ]);
                    $user->name = $request->input('value');
                    break;
                case 'email':
                    // Validasi untuk email: harus unik kecuali untuk email user itu sendiri
                    $request->validate([
                        'value' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                    ]);
                    $user->email = $request->input('value');
                    // Jika email diubah, set ulang status verifikasi
                    if ($user->isDirty('email')) {
                        $user->email_verified_at = null;
                    }
                    break;
                case 'phone':
                    // Validasi untuk nomor telepon: opsional, string, maks 20 karakter
                    $request->validate([
                        'value' => ['nullable', 'string', 'max:20'],
                    ]);
                    $user->phone = $request->input('value');
                    break;
                case 'address':
                    // Validasi untuk alamat: opsional, string, maks 255 karakter
                    $request->validate([
                        'value' => ['nullable', 'string', 'max:255'],
                    ]);
                    $user->address = $request->input('value');
                    break;
                case 'password':
                    // Validasi untuk password baru: minimal 8 karakter dan harus dikonfirmasi
                    $request->validate([
                        'value' => ['required', 'string', 'min:8', 'confirmed'], // 'confirmed' akan memverifikasi dengan input 'value_confirmation'
                    ], [
                        'value.required' => 'Kata sandi baru wajib diisi.',
                        'value.min' => 'Kata sandi baru harus minimal 8 karakter.',
                        'value.confirmed' => 'Konfirmasi kata sandi tidak cocok.',
                    ]);
                    $user->password = Hash::make($request->input('value')); // Enkripsi password baru
                    break;
                default:
                    // Jika bidang yang dikirim tidak valid atau tidak dikenali
                    return Redirect::back()->with('status', 'error')->withErrors(['field' => 'Bidang yang tidak valid untuk diperbarui.']);
            }

            $user->save(); // Simpan perubahan ke database

            // Tentukan pesan status yang akan dikirim kembali ke tampilan
            $statusMessage = ($field === 'password') ? 'password-updated' : 'profile-updated';

            // Redirect kembali ke halaman profil dengan pesan status sukses
            return Redirect::route('profile.edit')->with('status', $statusMessage);

        } catch (ValidationException $e) {
            // Tangani error validasi (misalnya, password tidak cocok, email sudah terdaftar)
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Tangani error umum lainnya (misal: masalah database, dll.)
            // Anda bisa menggunakan Log::error($e->getMessage()) untuk debugging
            return Redirect::back()->with('status', 'error')->withErrors(['general' => 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus akun pengguna.
     * Membutuhkan konfirmasi password pengguna yang sedang login.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Validasi password pengguna yang dimasukkan di modal
        // 'current_password' adalah aturan validasi Laravel yang membandingkan input dengan password user yang sedang login
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ], [
            'password.required' => 'Kata sandi wajib diisi untuk menghapus akun.',
            'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
        ]);

        $user = $request->user();

        // Logout pengguna dari sesi saat ini
        Auth::logout();

        // Hapus pengguna dari database
        $user->delete();

        // Invalidasi sesi dan regenerasi token CSRF untuk keamanan
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Redirect ke halaman utama dengan pesan status sukses
        return Redirect::to('/')->with('status', 'account-deleted');
    }
}