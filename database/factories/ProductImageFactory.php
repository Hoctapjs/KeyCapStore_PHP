<?php

namespace Database\Factories;

use App\Models\ProductImage;
use App\Models\Product;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductImageFactory extends Factory
{
    protected $model = ProductImage::class;

    public function definition(): array
    {
        // Use local placeholder image for instant loading
        return [
            'product_id' => Product::factory(),
            'image_url' => '/images/products/placeholder.svg',
            'alt' => $this->faker->words(3, true),
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
