<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
     use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'stock',
        'image',
    ];

    /**
     * Get the user (seller) that owns the product.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
