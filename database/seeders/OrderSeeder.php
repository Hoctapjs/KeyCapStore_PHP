<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    public function run()
    {
        DB::table('orders')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'code' => 'WK240001',
                'status' => 'paid',
                'subtotal' => 297000.00,
                'discount_total' => 0,
                'shipping_fee' => 30000.00,
                'tax_total' => 0,
                'total' => 327000.00,
                'shipping_address' => json_encode([
                    'full_name' => 'Nguyen Van Nam',
                    'address' => '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                    'phone' => '+84901234567'
                ]),
                'billing_address' => json_encode([
                    'full_name' => 'Nguyen Van Nam',
                    'address' => '123 Lê Lợi, Quận 1, TP. Hồ Chí Minh',
                    'phone' => '+84901234567'
                ]),
                'note' => 'Yêu cầu gói hàng cẩn thận',
                'created_at' => '2024-11-02 09:20:00',
                'updated_at' => '2024-11-02 09:25:00'
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'code' => 'WK240002',
                'status' => 'processing',
                'subtotal' => 80000.00,
                'discount_total' => 0,
                'shipping_fee' => 30000.00,
                'tax_total' => 0,
                'total' => 110000.00,
                'shipping_address' => json_encode([
                    'full_name' => 'Tran Thi Lan',
                    'address' => '12 Nguyễn Huệ, TP. Vinh',
                    'phone' => '+84907654321'
                ]),
                'billing_address' => json_encode([
                    'full_name' => 'Tran Thi Lan',
                    'address' => '12 Nguyễn Huệ, TP. Vinh',
                    'phone' => '+84907654321'
                ]),
                'note' => null,
                'created_at' => '2024-11-03 14:40:00',
                'updated_at' => '2024-11-03 14:45:00'
            ],
        ]);
    }
}
