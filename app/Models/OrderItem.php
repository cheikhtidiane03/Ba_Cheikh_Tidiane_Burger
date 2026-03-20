<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    protected $casts = [
        'quantity'   => 'integer',
        'unit_price' => 'decimal:2',
        'subtotal'   => 'decimal:2',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::saving(function ($item) {
            $item->subtotal = $item->quantity * $item->unit_price;
        });
    }

    public function getFormattedUnitPriceAttribute(): string
    {
        return number_format($this->unit_price, 0, ',', ' ') . ' FCFA';
    }

    public function getFormattedSubtotalAttribute(): string
    {
        return number_format($this->subtotal, 0, ',', ' ') . ' FCFA';
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}