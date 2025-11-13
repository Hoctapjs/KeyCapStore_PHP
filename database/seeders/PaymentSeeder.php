<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        DB::table('payments')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'method' => 'bank_transfer',
                'amount' => 327000.00,
                'status' => 'paid',
                'transaction_id' => 'TRANS001',
                'raw_payload' => null,
                'paid_at' => '2024-11-02 09:30:00',
                'created_at' => '2024-11-02 09:20:00',
                'updated_at' => '2024-11-02 09:30:00',
            ],
            [
                'id' => 2,
                'order_id' => 2,
                'method' => 'cod',
                'amount' => 110000.00,
                'status' => 'pending',
                'transaction_id' => null,
                'raw_payload' => null,
                'paid_at' => null,
                'created_at' => '2024-11-03 14:45:00',
                'updated_at' => '2024-11-03 14:45:00',
            ],
        ]);
    }
}
