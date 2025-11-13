<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Payment>
 */
class PaymentFactory extends Factory
{
    protected $model = Payment::class;

    public function definition(): array
    {
        return [
            'order_id' => Order::factory(),
            'method' => 'cod',
            'amount' => $this->faker->numberBetween(100000, 500000),
            'status' => 'pending',
            'transaction_id' => null,
            'raw_payload' => null,
            'paid_at' => null,
        ];
    }
}
