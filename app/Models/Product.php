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

    protected static function boot()
    {
        parent::boot();

        // Khi xóa sản phẩm
        static::deleting(function ($product) {
            // Xóa tất cả file ảnh vật lý
            if ($product->images && is_array($product->images)) {
                foreach ($product->images as $image) {
                    $imagePath = public_path($image);
                    if (file_exists($imagePath)) {
                        @unlink($imagePath);
                    }
                }
            }

            // ProductImages, ProductVariants, Reviews, Tags, Categories 
            // đã có cascade delete trong migration
            
            // CartItems sẽ được xóa cascade sau khi chạy migration mới
            
            // OrderItems sẽ set product_id = null để giữ lịch sử
        });
    }

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

    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    public function scopeArchived($query)
    {
        return $query->where('status', 'archived');
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    // Accessors & Helpers
    
    /**
     * Tổng số lượng tồn kho từ tất cả variants
     */
    public function getTotalStockAttribute()
    {
        return (int) $this->variants()->sum('stock_quantity');
    }

    /**
     * Stock hiển thị cho UI:
     * - Nếu có variant: tổng stock_quantity của tất cả variant
     * - Nếu không: dùng cột stock của product
     */
    public function getDisplayStockAttribute()
    {
        if ($this->variants()->count() > 0) {
            return $this->total_stock;
        }

        return (int) $this->stock;
    }

    public function getMinPriceAttribute()
    {
        // Kiểm tra variants đã được load chưa, nếu rồi thì dùng collection, nếu chưa thì query
        if ($this->relationLoaded('variants')) {
            $variantMinPrice = $this->variants->where('price', '>', 0)->min('price');
        } else {
            $variantMinPrice = $this->variants()->where('price', '>', 0)->min('price');
        }
        
        // Nếu không có variants hoặc giá variant = 0/null, dùng giá gốc sản phẩm
        if ($variantMinPrice === null || $variantMinPrice == 0) {
            return $this->price ?? 0;
        }
        
        return $variantMinPrice;
    }

    public function getMaxPriceAttribute()
    {
        // Kiểm tra variants đã được load chưa, nếu rồi thì dùng collection, nếu chưa thì query
        if ($this->relationLoaded('variants')) {
            $variantMaxPrice = $this->variants->where('price', '>', 0)->max('price');
        } else {
            $variantMaxPrice = $this->variants()->where('price', '>', 0)->max('price');
        }
        
        // Nếu không có variants hoặc giá variant = 0/null, dùng giá gốc sản phẩm
        if ($variantMaxPrice === null || $variantMaxPrice == 0) {
            return $this->price ?? 0;
        }
        
        return $variantMaxPrice;
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

    /**
     * Lấy tên trạng thái bằng tiếng Việt
     */
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'active' => 'Hoạt động',
            'draft' => 'Bản nháp',
            'archived' => 'Lưu trữ',
            default => $this->status,
        };
    }

    /**
     * Lấy màu badge cho trạng thái
     */
    public function getStatusBadgeClassAttribute()
    {
        return match($this->status) {
            'active' => 'bg-success',
            'draft' => 'bg-warning text-dark',
            'archived' => 'bg-secondary',
            default => 'bg-danger',
        };
    }

    /**
     * Lấy URL hình ảnh đầu tiên
     */
    public function getImageUrlAttribute()
    {
        if ($this->images && is_array($this->images) && count($this->images) > 0) {
            return $this->images[0];
        }
        
        // Trả về hình mặc định nếu không có
        return asset('images/no-image.png');
    }
}
