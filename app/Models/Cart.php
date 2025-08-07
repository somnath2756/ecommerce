<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /**
     * Get the total price of all items in the cart.
     * This is a Laravel accessor and can be called like $cart->total.
     *
     * @return float
     */
    public function getTotalAttribute()
    {
        return $this->items->sum(function ($item) {
            return $item->quantity * $item->product->price;
        });
    }

    /**
     * Get the total count of all individual items (sum of quantities) in the cart.
     *
     * @return int
     */
    public function totalItems()
    {
        return $this->items->sum('quantity');
    }
}
