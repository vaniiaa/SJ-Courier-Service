<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pembayaran;

class Status extends Model
{
    protected $table = 'shipment'; // sesuaikan dengan nama tabel PostgreSQL
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'tracking_number',
        'sender_name',
        'pickup_address',
        'receiver_name',
        'destination_address',
        'shipment_date',
        'weight',
        'shipping_cost',
        'courier_name',
        'delivery_status',
        'delivery_proof',
        'notes'
    ];
    // Relasi ke payment (one-to-one)
    public function payment()
    {
        return $this->hasOne(Payment::class, 'shipment_id');
    }
}