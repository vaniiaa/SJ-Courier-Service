<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Log;

class ProfileController extends Controller
{
    /**
     * Menampilkan formulir profil pengguna.
     */
    public function edit(Request $request): View
    {
        $user = Auth::user();
        $viewName = '';

        // Tentukan view mana yang akan digunakan berdasarkan peran
        switch ($user->role->role_name) {
            case 'admin':
                $viewName = 'admin.profile';
                break;
            case 'courier':
                $viewName = 'kurir.profile';
                break;
            default: // Untuk customer dan peran lainnya
                $viewName = 'profile.edit';
                break;
        }

        // Kembalikan view yang sudah dipilih dengan data user
        return view($viewName, [
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
                    $request->validate([
                        'value' => ['required', 'string', 'max:255'],
                    ]);
                    $user->name = $request->input('value');
                    break;
                case 'email':
                    // Email memerlukan password saat ini untuk keamanan.
                    // Ini adalah praktik yang baik untuk perubahan sensitif seperti email.
                    $request->validate([
                        'value' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
                        'current_password' => ['required', 'string', 'current_password'],
                    ], [
                        'current_password.required' => 'Kata sandi saat ini wajib diisi untuk mengubah email.',
                        'current_password.current_password' => 'Kata sandi saat ini salah.',
                    ]);
                    $user->email = $request->input('value');
                    if ($user->isDirty('email')) {
                        $user->email_verified_at = null;
                    }
                    break;
                case 'phone':
                    // Phone tidak memerlukan password saat ini.
                    $request->validate([
                        'value' => ['nullable', 'string', 'max:20'],
                    ]);
                    $user->phone = $request->input('value');
                    break;
                case 'address':
                    // Address tidak memerlukan password saat ini.
                    $request->validate([
                        'value' => ['nullable', 'string', 'max:255'],
                    ]);
                    $user->address = $request->input('value');
                    break;
                case 'password':
                    // Validasi untuk password baru: minimal 8 karakter dan harus dikonfirmasi
                    // DAN memerlukan password saat ini
                    $request->validate([
                        'current_password' => ['required', 'string', 'current_password'], // Wajib ada untuk perubahan password
                        'value' => ['required', 'string', 'min:8', 'confirmed'],
                        'value_confirmation' => ['required'], // Pastikan field konfirmasi juga wajib diisi
                    ], [
                        'current_password.required' => 'Kata sandi saat ini wajib diisi.',
                        'current_password.current_password' => 'Kata sandi saat ini salah.',
                        'value.required' => 'Kata sandi baru wajib diisi.',
                        'value.min' => 'Kata sandi baru harus minimal 8 karakter.',
                        'value.confirmed' => 'Konfirmasi kata sandi baru tidak cocok.',
                        'value_confirmation.required' => 'Konfirmasi kata sandi baru wajib diisi.',
                    ]);
                    $user->password = Hash::make($request->input('value'));
                    break;
                default:
                    throw ValidationException::withMessages([
                        'general' => 'Bidang yang tidak valid untuk diperbarui.',
                    ]);
            }

            $user->save();

            $statusMessage = ($field === 'password') ? 'password-updated' : 'profile-updated';

            // Redirect ke halaman profile.edit dengan pesan status dan field yang diupdate
            return Redirect::route('profile.edit')->with('status', $statusMessage)->with('field', $field);

        } catch (ValidationException $e) {
            // Jika ada error validasi, kembali ke halaman sebelumnya dengan error dan input
            return Redirect::back()->withErrors($e->errors())->withInput();
        } catch (\Exception $e) {
            // Tangani error umum
            Log::error("Error updating profile field '{$field}': " . $e->getMessage());
            return Redirect::back()->with('status', 'error')->withErrors(['general' => 'Terjadi kesalahan saat memperbarui profil. Silakan coba lagi.']);
        }
    }

    /**
     * Menghapus akun pengguna.
     * Membutuhkan konfirmasi password pengguna yang sedang login.
     */
    public function destroy(Request $request): RedirectResponse
    {
        try {
            $request->validate([
                'password' => ['required', 'current_password'],
            ], [
                'password.required' => 'Kata sandi wajib diisi untuk menghapus akun.',
                'password.current_password' => 'Kata sandi yang Anda masukkan salah.',
            ]);

            $user = $request->user();

            Auth::logout();

            $user->delete();

            $request->session()->invalidate();
            $request->session()->regenerateToken();

            return Redirect::to('/')->with('status', 'account-deleted');

        } catch (ValidationException $e) {
            // Jika ada error validasi dari modal hapus akun
            return Redirect::back()->withErrors($e->errors())->withInput()->with('from_delete_modal', true);
        } catch (\Exception $e) {
            Log::error("Error deleting user account: " . $e->getMessage());
            return Redirect::back()->with('status', 'error')->withErrors(['general' => 'Terjadi kesalahan saat menghapus akun. Silakan coba lagi.']);
        }
    }
}