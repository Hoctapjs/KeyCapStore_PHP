<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderCouponSeeder extends Seeder
{
    public function run()
    {
        DB::table('order_coupons')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'coupon_id' => 1,
                'amount' => 29700.00,
            ]
        ]);
    }
}
