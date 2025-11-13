<?php

namespace Database\Factories;

use App\Models\Order;
use App\Models\Shipment;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Shipment>
 */
class ShipmentFactory extends Factory
{
    protected $model = Shipment::class;

    public function definition()
    {
        return [
            'order_id' => Order::factory(),
            'carrier' => 'Giao Hàng Nhanh',
            'tracking_number' => null,
            'shipped_at' => null,
            'delivered_at' => null,
            'status' => 'preparing',
        ];
    }
}
