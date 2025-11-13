<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'cart_id',
        'product_id',
        'variant_id',
        'quantity',
        'price_snapshot',
    ];

    /** Thuộc về 1 cart */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /** Sản phẩm */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /** Variant (nếu có) */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }
}
