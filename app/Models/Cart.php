<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
    ];

    /** Quan hệ: 1 cart có nhiều cart item */
    public function items()
    {
        return $this->hasMany(CartItem::class);
    }

    /** Quan hệ: cart thuộc về 1 user (có thể null nếu guest -> khách vãng lai) */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Merge cart guest (theo session_id) vào cart của user.
    public static function mergeSessionCartIntoUser(int $userId, ?string $sessionId): void
    {
        if (!$userId || !$sessionId) {
            return;
        }

        // Cart guest theo session_id (chỉ lấy cart chưa có user_id)
        $guestCart = static::whereNull('user_id')
            ->where('session_id', $sessionId)
            ->with(['items.product', 'items.variant'])
            ->first();

        if (!$guestCart || $guestCart->items->isEmpty()) {
            return;
        }

        // Cart của user (tạo nếu chưa có)
        $userCart = static::firstOrCreate(['user_id' => $userId]);
        $userCart->load('items');

        foreach ($guestCart->items as $guestItem) {
            $product = $guestItem->product;
            $variant = $guestItem->variant;

            if (!$product) {
                continue;
            }

            // Tồn kho hiện tại
            $availableStock = $variant
                ? $variant->stock_quantity
                : $product->stock;

            if ($availableStock <= 0) {
                continue;
            }

            // Tìm xem trong cart user đã có dòng cùng product & variant chưa
            $existingItem = $userCart->items()
                ->where('product_id', $guestItem->product_id)
                ->where('variant_id', $guestItem->variant_id)
                ->first();

            $currentQty = $existingItem ? $existingItem->quantity : 0;
            $mergeQty   = $currentQty + $guestItem->quantity;

            // Không cho vượt tồn kho
            if ($mergeQty > $availableStock) {
                $mergeQty = $availableStock;
            }

            if ($mergeQty <= 0) {
                continue;
            }

            // Giá hiện tại (theo variant / product)
            $price = $variant && $variant->price !== null
                ? $variant->price
                : $product->price;

            if ($existingItem) {
                $existingItem->update([
                    'quantity'       => $mergeQty,
                    'price_snapshot' => $price,
                ]);
            } else {
                $userCart->items()->create([
                    'product_id'     => $guestItem->product_id,
                    'variant_id'     => $guestItem->variant_id,
                    'quantity'       => $mergeQty,
                    'price_snapshot' => $price,
                ]);
            }
        }

        // Xoá cart guest sau khi merge xong
        $guestCart->items()->delete();
        $guestCart->delete();
    }
}
