<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_by',
        'updated_at',
        'updated_by'
    ];

    protected $fillable = [
        'customer_id',
        'total_price',
        'status',
    ];
}
