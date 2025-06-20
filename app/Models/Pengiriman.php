<?php

/**
 * Nama File: Pengiriman.php
 * Deskripsi:
 * Model ini merepresentasikan tabel 'shipments' dalam database.
 * Model ini digunakan untuk mengelola data pengiriman (shipment), 
 * termasuk relasinya dengan kurir dan kolom-kolom yang bisa diisi.
 * Tanggal: 25 Mei 2025
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pengiriman extends Model
{
    use HasFactory;

    //Nama tabel di database yang direpresentasikan oleh model ini. Harus ditulis sesuai persis dengan nama tabel di database (termasuk huruf besar/kecil).
    protected $table = 'shipments';
    protected $primaryKey = 'id'; 
    //Daftar atribut yang dapat diisi secara massal (mass assignable). Ini berguna saat melakukan insert atau update data menggunakan metode create() atau update().
    protected $fillable = [
        'resi',
        'nama_pengirim',
        'alamat_penjemputan',
        'nama_penerima',
        'alamat_tujuan',
        'metode_pembayaran',
        'kurir_id',
        'nama_kurir',
        'tanggal_pengiriman',
        'berat',
        'harga',
        'status_pengiriman',
        'catatan',
        'tanggal_pemesanan',
        'bukti_pengiriman',
    ];

    //Relasi: Setiap pengiriman dimiliki oleh satu kurir. foreign key: kurir_id pada tabel shipments return: Objek relasi belongsTo ke model Kurir
     
    public function kurir()
    {
        return $this->belongsTo(Kurir::class, 'kurir_id');
    }
}
