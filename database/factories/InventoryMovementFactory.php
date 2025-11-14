<?php

namespace Database\Factories;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryMovementFactory extends Factory
{
    protected $model = InventoryMovement::class;

    public function definition(): array
    {
        return [
            'product_id' => Product::factory(),
            'variant_id' => null,
            'change_qty' => $this->faker->numberBetween(-50, 50),
            'reason' => $this->faker->randomElement(['order', 'restock', 'manual', 'refund']),
            'note' => $this->faker->optional()->sentence(),
            'created_at' => $this->faker->dateTimeBetween('-1 year', 'now'),
        ];
    }

    public function withVariant(): static
    {
        return $this->state(fn (array $attributes) => [
            'variant_id' => ProductVariant::factory(),
        ]);
    }
}
