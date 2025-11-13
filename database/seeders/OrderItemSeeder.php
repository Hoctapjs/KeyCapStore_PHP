<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderItemSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_items')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'product_id' => 1,
                'variant_id' => null,
                'title_snapshot' => '[In-stock] Keycap Lobo Artisan Vô Diện',
                'sku_snapshot' => null,
                'price' => 103000.00,
                'quantity' => 1,
                'total' => 103000.00,
                'created_at' => '2024-11-02 09:21:00',
                'updated_at' => '2024-11-02 09:21:00',
            ],
            [
                'id' => 2,
                'order_id' => 1,
                'product_id' => 2,
                'variant_id' => null,
                'title_snapshot' => 'Keycap Lobo Artisan GENSHIN',
                'sku_snapshot' => null,
                'price' => 194000.00,
                'quantity' => 1,
                'total' => 194000.00,
                'created_at' => '2024-11-02 09:21:00',
                'updated_at' => '2024-11-02 09:21:00',
            ],
            [
                'id' => 3,
                'order_id' => 2,
                'product_id' => 4,
                'variant_id' => null,
                'title_snapshot' => 'Keycap Cherry Walker Black-Supplement PBT Dyesub',
                'sku_snapshot' => null,
                'price' => 80000.00,
                'quantity' => 1,
                'total' => 80000.00,
                'created_at' => '2024-11-03 14:41:00',
                'updated_at' => '2024-11-03 14:41:00',
            ],
        ]);
    }
}
