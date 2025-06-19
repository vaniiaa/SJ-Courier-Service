<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str; // Import Str facade
use Illuminate\Support\Facades\Auth; // Import Auth facade

class Shipment extends Model
{
    use HasFactory;
    protected $primaryKey = 'shipmentID';
    protected $fillable = [
        'orderID', 'tracking_number', 'courierUserID', 'itemType', 'weightKG',
        'currentStatus', 'pickupTimestamp', 'deliveredTimestamp', 'finalPrice'
    ];

    protected $casts = [
        'weightKG' => 'decimal:2',
        'pickupTimestamp' => 'datetime',
        'deliveredTimestamp' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (Shipment $shipment) {
            // Hanya generate jika tracking_number belum diisi
            if (empty($shipment->tracking_number)) {
                $shipment->tracking_number = self::generateUniqueTrackingNumber();
            }
        });
    }

    public static function generateUniqueTrackingNumber(): string
    {
        do {
            // Format: Contoh "25" + 8 karakter acak uppercase
            $trackingNumber = date('y') . Str::upper(Str::random(8));
        } while (self::where('tracking_number', $trackingNumber)->exists()); // Pastikan unik

        return $trackingNumber;
    }

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

    public function createStep1()
{
    // Mengambil alamat tersimpan milik pengguna yang login
    $savedAddresses = \App\Models\SavedAddress::where('user_id', Auth::id())->get();
    
    return view('shipments.create-step-1', compact('savedAddresses'));
}
}
