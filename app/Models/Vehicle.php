<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;


    protected $fillable = [
        'make',
        'model',
        'year',
        'color',
        'license_plate',
        'status',
    ];



    public function orders()
    {
        return $this->hasMany(Orders::class);
        
    }


    //the vehicle can have many oredrs but the order can have only one vehicle
}
