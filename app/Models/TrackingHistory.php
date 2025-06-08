<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrackingHistory extends Model
{
    use HasFactory;
    protected $primaryKey = 'trackingHistoryID';
    protected $fillable = [
        'shipmentID', 'timestamp', 'statusDescription',
        'locationLatitude', 'locationLongitude', 'updatedByUserID'
    ];

    protected $casts = [
        'timestamp' => 'datetime',
        'locationLatitude' => 'decimal:7',
        'locationLongitude' => 'decimal:7',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'shipmentID', 'shipmentID');
    }

    public function updatedByUser()
    {
        return $this->belongsTo(User::class, 'updatedByUserID', 'user_id');
    }
}
