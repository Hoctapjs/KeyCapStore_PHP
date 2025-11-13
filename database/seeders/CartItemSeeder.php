<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('cart_items')->insert([
            [
                'id' => 1,
                'cart_id' => 1,
                'product_id' => 1,
                'variant_id' => null,
                'quantity' => 1,
                'price_snapshot' => 103000.00,
                'created_at' => '2024-11-02 09:05:00',
                'updated_at' => '2024-11-02 09:05:00',
            ],
            [
                'id' => 2,
                'cart_id' => 1,
                'product_id' => 2,
                'variant_id' => null,
                'quantity' => 1,
                'price_snapshot' => 194000.00,
                'created_at' => '2024-11-02 09:10:00',
                'updated_at' => '2024-11-02 09:10:00',
            ],
            [
                'id' => 3,
                'cart_id' => 2,
                'product_id' => 3,
                'variant_id' => null,
                'quantity' => 2,
                'price_snapshot' => 40000.00,
                'created_at' => '2024-11-03 14:22:00',
                'updated_at' => '2024-11-03 14:22:00',
            ]
        ]);
    }
}
