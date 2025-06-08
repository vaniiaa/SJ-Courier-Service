<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PricingTier extends Model
{
    use HasFactory;
    protected $primaryKey = 'tierID';
    protected $fillable = ['description', 'minWeightKG', 'maxWeightKG', 'pricePerKM'];
}