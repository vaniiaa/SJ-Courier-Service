<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    protected $table = 'pengiriman'; // Pastikan nama tabel benar
    protected $fillable = [
        'resi',
        'nama_pengirim',
        'alamat_penjemputan',
        'nama_penerima',
        'alamat_tujuan',
        'metode_pembayaran',
        'kurir_id',
        'nama_kurir', // Tambahkan ini jika Anda menyimpan nama kurir juga
        'tanggal_pengiriman',
        'berat',
        'harga',
        'status_pengiriman',
        'catatan',
    ];

    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}