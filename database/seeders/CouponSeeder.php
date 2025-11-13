<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponSeeder extends Seeder
{
    public function run()
    {
        DB::table('coupons')->insert([
            [
                'id' => 1,
                'code' => 'WELCOME10',
                'type' => 'percent',
                'value' => 10.00,
                'max_uses' => 500,
                'per_user_limit' => 1,
                'starts_at' => '2024-11-01 00:00:00',
                'ends_at' => '2024-12-31 23:59:59',
                'min_order_total' => 300000.00,
                'created_at' => '2024-11-01 10:00:00',
                'updated_at' => '2024-11-01 10:00:00',
            ],
            [
                'id' => 2,
                'code' => 'FREESHIPHN',
                'type' => 'fixed',
                'value' => 30000.00,
                'max_uses' => 200,
                'per_user_limit' => 2,
                'starts_at' => '2024-11-01 00:00:00',
                'ends_at' => '2025-01-31 23:59:59',
                'min_order_total' => 200000.00,
                'created_at' => '2024-11-01 10:00:00',
                'updated_at' => '2024-11-01 10:00:00',
            ],
        ]);
    }
}
