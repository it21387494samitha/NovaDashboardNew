<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'amount',
        'status',
        'notes',
        'order_date',
    ];

    // Auto-cast these columns to proper types
    protected $casts = [
        'amount' => 'decimal:2',    // Always 2 decimal places
        'order_date' => 'date',     // Carbon date object
    ];

    // An order belongs to one company (inverse of hasMany)
    public function company()
    {
        return $this->belongsTo(Company::class);
    }
}
