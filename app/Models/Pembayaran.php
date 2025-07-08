<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran;

class Pembayaran extends Model
{
    protected $table = 'payments'; // Nama tabel
    protected $primaryKey = 'id'; // Primary key
    public $timestamps = true; // Aktifkan timestamps (created_at & updated_at)

    protected $fillable = [
        'shipment_id',
        'method_payment',
    ];

    // Relasi ke shipment (many-to-one)
    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipment_id');
    }
}
