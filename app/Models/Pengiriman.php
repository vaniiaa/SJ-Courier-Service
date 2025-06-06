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
        'nama_kurir', // Penting: Tambahkan ini agar bisa disimpan
        'tanggal_pengiriman',
        'berat',
        'harga',
        'status_pengiriman',
        'catatan',
        'tanggal_pemesanan', // Asumsi ini ada atau diambil dari created_at
    ];

    /**
     * Get the courier associated with the shipment.
     */
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}