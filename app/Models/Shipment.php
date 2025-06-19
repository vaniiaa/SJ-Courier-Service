<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Shipment extends Model
{
    use HasFactory;
    protected $primaryKey = 'shipmentID';
    protected $fillable = [
        'orderID', 'courierUserID', 'itemType', 'weightKG',
        'currentStatus', 'pickupTimestamp', 'deliveredTimestamp', 'finalPrice'
    ];

    protected $casts = [
        'weightKG' => 'decimal:2',
        'pickupTimestamp' => 'datetime',
        'deliveredTimestamp' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }

    public function courier()
    {
        return $this->belongsTo(User::class, 'courierUserID', 'user_id');
    }

    public function trackingHistories()
    {
        return $this->hasMany(TrackingHistory::class, 'shipmentID');
    }
}
