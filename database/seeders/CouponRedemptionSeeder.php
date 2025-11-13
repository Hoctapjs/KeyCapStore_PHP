<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CouponRedemptionSeeder extends Seeder
{
    public function run()
    {
        DB::table('coupon_redemptions')->insert([
            [
                'id' => 1,
                'coupon_id' => 1,
                'user_id' => 2,
                'order_id' => 1,
                'used_at' => '2024-11-02 09:19:00',
                'amount' => 29700.00,
            ]
        ]);
    }
}
