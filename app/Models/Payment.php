<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;
    protected $primaryKey = 'paymentID';
    protected $fillable = [
        'orderID', 'midtrans_transaction_id', 'amount', 'paymentMethod',
        'paymentDate', 'status', 'raw_midtrans_response'
    ];

    protected $casts = [
        'paymentDate' => 'datetime',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class, 'orderID', 'orderID');
    }
}
