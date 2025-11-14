<?php

namespace Database\Seeders;

use App\Models\ProductTag;
use Illuminate\Database\Seeder;

class ProductTagSeeder extends Seeder
{
    public function run(): void
    {
        $tags = [
            ['name' => 'New Arrival', 'slug' => 'new-arrival'],
            ['name' => 'Best Seller', 'slug' => 'best-seller'],
            ['name' => 'On Sale', 'slug' => 'on-sale'],
            ['name' => 'Featured', 'slug' => 'featured'],
            ['name' => 'Limited Edition', 'slug' => 'limited-edition'],
            ['name' => 'Hot Deal', 'slug' => 'hot-deal'],
            ['name' => 'RGB', 'slug' => 'rgb'],
            ['name' => 'Wireless', 'slug' => 'wireless'],
            ['name' => 'Compact', 'slug' => 'compact'],
            ['name' => 'Full Size', 'slug' => 'full-size'],
        ];

        foreach ($tags as $tag) {
            ProductTag::create($tag);
        }

        // Tạo thêm tags ngẫu nhiên
        ProductTag::factory(10)->create();
    }
}
