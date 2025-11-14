<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    public function run(): void
    {
        $brands = [
            ['name' => 'Logitech', 'slug' => 'logitech', 'website_url' => 'https://www.logitech.com'],
            ['name' => 'Razer', 'slug' => 'razer', 'website_url' => 'https://www.razer.com'],
            ['name' => 'Corsair', 'slug' => 'corsair', 'website_url' => 'https://www.corsair.com'],
            ['name' => 'SteelSeries', 'slug' => 'steelseries', 'website_url' => 'https://www.steelseries.com'],
            ['name' => 'HyperX', 'slug' => 'hyperx', 'website_url' => 'https://www.hyperx.com'],
            ['name' => 'Keychron', 'slug' => 'keychron', 'website_url' => 'https://www.keychron.com'],
            ['name' => 'Ducky', 'slug' => 'ducky', 'website_url' => 'https://www.duckychannel.com.tw'],
            ['name' => 'Akko', 'slug' => 'akko', 'website_url' => 'https://en.akkogear.com'],
        ];

        foreach ($brands as $brand) {
            Brand::create($brand);
        }

        // Tạo thêm brands ngẫu nhiên
        Brand::factory(7)->create();
    }
}
