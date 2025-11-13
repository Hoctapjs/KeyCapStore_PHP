<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    protected $model = Order::class;

    public function definition(): array
    {
        $subtotal = $this->faker->numberBetween(80000, 300000);
        $shipping = 30000;
        $total = $subtotal + $shipping;

        return [
            'user_id' => 1,
            'code' => 'WK' . $this->faker->unique()->numerify('######'),
            'status' => 'pending',
            'subtotal' => $subtotal,
            'discount_total' => 0,
            'shipping_fee' => $shipping,
            'tax_total' => 0,
            'total' => $total,
            'shipping_address' => null,
            'billing_address' => null,
            'note' => $this->faker->optional()->sentence(),
        ];
    }
}
