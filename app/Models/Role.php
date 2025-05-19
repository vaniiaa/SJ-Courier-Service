<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $primaryKey = 'role_id';

    protected $fillable = [
        'role_name',
    ];

    //get users for the role
    public function users()
    {
        return $this->hasMany(User::class, 'role_id', 'role_id');
    }
}
