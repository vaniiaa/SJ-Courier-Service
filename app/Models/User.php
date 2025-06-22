<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Log;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */

     // Tentukan primary key
     protected $primaryKey = 'user_id';

    protected $fillable = [
        'role_id',
        'name',
        'phone',
        'email',
        'password',
        'address',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    //get role
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'role_id');
    }

    //cek apakah user adalah admin
    public function isAdmin()
    {
        Log::info('User role_id: ' . $this->role_id);
    return (int)$this->role_id === 1;
    }

    //cek apakah user adalah kurir
    public function isKurir()
    {
        Log::info('User role_id: ' . $this->role_id);
        return (int)$this->role_id === 2;
    }

     public function sentOrders() {
        return $this->hasMany(Order::class, 'senderUserID');
    }

    public function savedAddresses()
    {
    return $this->hasMany(SavedAddress::class, 'user_id');
    }

    public function deliveryArea()
    {
        return $this->belongsTo(DeliveryArea::class, 'area_id', 'area_id');
    }

}
