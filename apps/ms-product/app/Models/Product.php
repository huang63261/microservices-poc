<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $hidden = [
        'created_at',
        'created_by',
        'updated_at',
        'updated_by',
    ];

    protected $fillable = [
        'name',
        'category_id',
        'price',
        'status',
        'description',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }
}
