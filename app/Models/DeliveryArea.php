<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryArea extends Model
{
    protected $table = 'delivery_area';
    protected $primaryKey = 'area_id';
    protected $fillable = ['area_name'];
    public $timestamps = true;
}