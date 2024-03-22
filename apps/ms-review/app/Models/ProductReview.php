<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_by',
        'updated_by',
    ];

    protected $fillable = [
        'product_id',
        'customer_id',
        'rating',
        'content',
    ];
}
