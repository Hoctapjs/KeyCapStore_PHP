<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition(): array
    {
        $title = $this->faker->unique()->words(3, true);
        $slug = Str::slug($title);
        
        return [
            'url' => $this->faker->unique()->url(),
            'title' => ucfirst($title),
            'code' => strtoupper($this->faker->unique()->bothify('PRD-####-???')),
            'brand_id' => Brand::factory(),
            'price' => $this->faker->randomFloat(2, 10, 500),
            'stock' => $this->faker->numberBetween(0, 100),
            'colors' => json_encode($this->faker->randomElements(['Red', 'Blue', 'Green', 'Black', 'White', 'Yellow'], $this->faker->numberBetween(1, 3))),
            'description' => $this->faker->paragraph(5),
            'images' => json_encode([
                $this->faker->imageUrl(640, 480, 'product'),
                $this->faker->imageUrl(640, 480, 'product'),
            ]),
            'specs' => json_encode([
                'weight' => $this->faker->randomFloat(2, 0.1, 5) . ' kg',
                'dimensions' => $this->faker->numberBetween(10, 50) . 'x' . $this->faker->numberBetween(10, 50) . 'x' . $this->faker->numberBetween(5, 20) . ' cm',
                'material' => $this->faker->randomElement(['Plastic', 'Metal', 'Wood', 'Glass']),
            ]),
            'slug' => $slug,
            'status' => $this->faker->randomElement(['draft', 'active', 'archived']),
        ];
    }

    public function active(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'active',
        ]);
    }

    public function inStock(): static
    {
        return $this->state(fn (array $attributes) => [
            'stock' => $this->faker->numberBetween(10, 100),
        ]);
    }
}
