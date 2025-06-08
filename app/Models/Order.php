<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    // ...
    protected $primaryKey = 'orderID';
    protected $fillable = [
        'senderUserID',
        'receiverName',
        'receiverAddress',
        'receiverPhoneNumber',
        'receiverUserID',
        'pickupAddress',
        'orderDate',
        'notes',
        'status',
        'estimatedDistanceKM',
        'estimatedPrice',
        'pickupLatitude',
        'pickupLongitude',
        'receiverLatitude',
        'receiverLongitude',
        'midtrans_snap_token',
        'midtrans_order_id',
    ];

    public function sender()
    {
        // Secara eksplisit beritahu foreign key dan owner key (PK di tabel users)
        return $this->belongsTo(User::class, 'senderUserID', 'user_id');
    }

    public function receiverUser()
    {
        return $this->belongsTo(User::class, 'receiverUserID', 'user_id');
    }

    public function shipment()
    {
        return $this->hasOne(Shipment::class, 'orderID');
    }

    public function payments()
    {
        return $this->hasMany(Payment::class, 'orderID');
    }
    // ...
}
