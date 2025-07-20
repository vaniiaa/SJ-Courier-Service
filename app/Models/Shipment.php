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

    // Tambahkan 'current_lat', 'current_long', dan 'last_tracked_at' ke dalam $fillable
    protected $fillable = [
        'orderID',
        'tracking_number',
        'courierUserID',
        'itemType',
        'weightKG',
        'currentStatus',
        'pickupTimestamp',
        'deliveredTimestamp',
        'finalPrice',
        'delivery_proof',
        'noteadmin',
        'current_lat',       
        'current_long',      
        'last_updated_at'    
    ];

    protected $casts = [
        'weightKG' => 'decimal:2',
        'pickupTimestamp' => 'datetime',
        'deliveredTimestamp' => 'datetime',
        'last_updated_at' => 'datetime', // <-- Tambahkan ini agar di-cast ke objek Carbon
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
            // Format: Contoh "25" + 8 karakter acak uppercase (tahun 2025 akan jadi '25')
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

        return view('user.form_pengiriman', compact('savedAddresses'));
    }
}