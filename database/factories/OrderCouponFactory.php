<?php

namespace Database\Factories;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderCoupon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderCoupon>
 */
class OrderCouponFactory extends Factory
{
    protected $model = OrderCoupon::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'coupon_id' => Coupon::factory(),
            'amount' => $this->faker->numberBetween(5000, 30000),
        ];
    }
}
