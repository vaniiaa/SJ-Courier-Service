<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SavedAddress extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'label', 'address', 'latitude', 'longitude'];
    public function user() {
    return $this->belongsTo(User::class, 'user_id', 'user_id');
}
}
