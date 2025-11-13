<?php

namespace Database\Factories;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CartItem>
 */
class CartItemFactory extends Factory
{
    protected $model = CartItem::class;

    public function definition(): array
    {
        return [
            'cart_id' => Cart::factory(),
            'product_id' => 1, // sửa lại thay cho một sản phẩm hợp lệ 
            'variant_id' => null,
            'quantity' => $this->faker->numberBetween(1, 3),
            'price_snapshot' => $this->faker->randomFloat(2, 50000, 500000),
        ];
    }
}
