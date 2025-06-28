<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DeliveryArea extends Model
{
    use HasFactory;

    protected $table = 'delivery_area';
    protected $primaryKey = 'area_id';
    protected $fillable = ['area_name'];
    public $timestamps = true;

    public function couriers()
    {
        return $this->hasMany(User::class, 'area_id', 'area_id');
    }   
}