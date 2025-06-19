<?php

/**
 * Nama File: Kurir.php
 * Deskripsi: Model ini merepresentasikan tabel 'kurir' dalam database.
 * Ini berfungsi sebagai model autentikasi untuk kurir dan mendefinisikan atribut-atribut serta relasi dengan pengiriman.
 * Dibuat Oleh: Aulia Sabrina
 * Tanggal: 25 Mei 2025
 */

 // Mendefinisikan namespace untuk model ini.
namespace App\Models; 

// Mengimpor kelas Authenticatable untuk fungsionalitas autentikasi.
use Illuminate\Foundation\Auth\User as Authenticatable; 

// Mengimpor trait Notifiable untuk fungsionalitas notifikasi.
use Illuminate\Notifications\Notifiable; 

// Mendefinisikan kelas model Kurir, yang mewarisi fungsionalitas autentikasi.
class Kurir extends Authenticatable 
{
    // Menggunakan trait Notifiable untuk mengirimkan notifikasi.
    use Notifiable; 

    // Menentukan nama tabel di database yang terkait dengan model ini.
    protected $table = 'kurir';

    // Mendefinisikan kolom-kolom yang boleh diisi (mass assignable) untuk model Kurir.
    protected $fillable = [
        'nama', 'email', 'no_hp', 'alamat', 'wilayah_pengiriman', 'username', 'password'
    ];

    // Mendefinisikan atribut yang harus disembunyikan saat model dikonversi ke array atau JSON (misalnya untuk keamanan).
    protected $hidden = [
        'password', // Sembunyikan password
        'remember_token', // Sembunyikan token 'remember me'
    ];

    // Menonaktifkan pengelolaan kolom `created_at` dan `updated_at` secara otomatis oleh Eloquent, karena tabel 'kurir' mungkin tidak memilikinya.
    public $timestamps = false; 

    /**
     * Mendefinisikan relasi "hasMany" antara model Kurir dan Pengiriman.
     * Artinya, satu `Kurir` bisa memiliki banyak `Pengiriman`.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function pengiriman()
    {
        // Menentukan bahwa Kurir ini memiliki banyak Pengiriman. Secara default, Eloquent akan mencari kolom `kurir_id` di tabel 'pengiriman'.
        return $this->hasMany(Pengiriman::class);
    }
}