<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; 
use Illuminate\Notifications\Notifiable;

class Kurir extends Authenticatable
{
    use Notifiable;

    protected $table = 'kurir';

    protected $fillable = [
        'nama', 'email', 'no_hp', 'alamat', 'wilayah_pengiriman', 'username', 'password'
    ];

    protected $hidden = [
        'password',
        'remember_token', 
    ];

    public $timestamps = false;
}
