<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'variant_id',
        'title_snapshot',
        'sku_snapshot',
        'price',
        'quantity',
        'total',
    ];

    /** Thuộc về đơn hàng */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /** Sản phẩm gốc */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /** Biến thể */
    public function variant()
    {
        return $this->belongsTo(ProductVariant::class);
    }

    // Accessors for easier usage in views
    public function getProductNameAttribute()
    {
        return $this->title_snapshot;
    }

    public function getVariantNameAttribute()
    {
        if ($this->variant_id && $this->variant) {
            $options = $this->variant->option_values ?? [];
            return is_array($options) ? implode(' - ', $options) : '';
        }
        return null;
    }

    public function getPriceSnapshotAttribute()
    {
        return $this->price;
    }
}
