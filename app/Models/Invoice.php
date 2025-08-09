<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'customer_id',
        'invoice_date',
        'due_date',
        'total_amount',
        'status',
        'notes',
        'generated_by'
    ];

    protected $casts = [
        'invoice_date' => 'date',
        'due_date' => 'date',
        'total_amount' => 'decimal:2'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }

    public function addItem($data)
    {
        $data['subtotal'] = $data['quantity'] * $data['unit_price'];
        return $this->invoiceItems()->create($data);
    }

    public function updateTotalAmount()
    {
        $this->total_amount = $this->invoiceItems()->sum('subtotal');
        $this->save();
    }

    protected static function booted()
    {
        static::created(function ($invoice) {
            $invoice->invoice_number = 'INV-' . str_pad($invoice->id, 6, '0', STR_PAD_LEFT);
            $invoice->save();
        });
    }
}