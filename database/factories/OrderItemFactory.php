<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    protected $model = OrderItem::class;

    public function definition(): array
    {
        $price = $this->faker->numberBetween(80000, 400000);
        $qty = $this->faker->numberBetween(1, 2);

        return [
            'order_id' => Order::factory(),
            'product_id' => 1,
            'variant_id' => null,
            'title_snapshot' => $this->faker->sentence(3),
            'sku_snapshot' => null,
            'price' => $price,
            'quantity' => $qty,
            'total' => $price * $qty,
        ];
    }
}
