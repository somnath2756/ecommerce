<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
     use HasFactory;

    protected $fillable = [
        'name', 'contact_person', 'email', 'phone', 'address',
        'city', 'state', 'zip_code', 'country'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class); // If you add purchase orders later
    }
}
