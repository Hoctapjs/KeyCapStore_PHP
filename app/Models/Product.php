<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'title',
        'code',
        'brand_id',
        'price',
        'stock',
        'colors',
        'description',
        'images',
        'specs',
        'slug',
        'status',
    ];

    protected $casts = [
        'colors' => 'array',
        'images' => 'array',
        'specs' => 'array',
        'price' => 'decimal:2',
    ];

    // Relationships
    public function brand()
    {
        return $this->belongsTo(Brand::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->withPivot('primary_flag');
    }

    public function primaryCategory()
    {
        return $this->belongsToMany(Category::class, 'product_categories', 'product_id', 'category_id')
            ->wherePivot('primary_flag', true)
            ->first();
    }

    public function productImages()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class);
    }

    public function tags()
    {
        return $this->belongsToMany(ProductTag::class, 'product_tag_pivot', 'product_id', 'product_tag_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function inventoryMovements()
    {
        return $this->hasMany(InventoryMovement::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    /**
     * Stock hiển thị cho UI:
     * - Nếu có variant: tổng stock_quantity của tất cả variant
     * - Nếu không: dùng cột stock của product
     */
    public function getDisplayStockAttribute()
    {
        if ($this->variants()->count() > 0) {
            return (int) $this->variants()->sum('stock_quantity');
        }

        return (int) $this->stock;
    }
    // Accessors & Helpers
    public function getMinPriceAttribute()
    {
        return $this->variants()->min('price') ?? 0;
    }

    public function getMaxPriceAttribute()
    {
        return $this->variants()->max('price') ?? 0;
    }

    public function getPriceRangeAttribute()
    {
        $min = $this->min_price;
        $max = $this->max_price;
        
        if ($min == $max || $max == 0) {
            return number_format($min, 0, ',', '.') . 'đ';
        }
        
        return number_format($min, 0, ',', '.') . 'đ - ' . number_format($max, 0, ',', '.') . 'đ';
    }
}
