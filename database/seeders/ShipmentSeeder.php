<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ShipmentSeeder extends Seeder
{
    public function run()
    {
        DB::table('shipments')->insert([
            [
                'id' => 1,
                'order_id' => 1,
                'carrier' => 'Giao Hàng Nhanh',
                'tracking_number' => 'GHN240001',
                'shipped_at' => '2024-11-02 16:00:00',
                'delivered_at' => '2024-11-03 10:00:00',
                'status' => 'delivered',
                'created_at' => '2024-11-02 15:00:00',
                'updated_at' => '2024-11-03 10:00:00',
            ],
            [
                'id' => 2,
                'order_id' => 2,
                'carrier' => 'Giao Hàng Tiết Kiệm',
                'tracking_number' => 'GHTK240002',
                'shipped_at' => '2024-11-04 09:00:00',
                'delivered_at' => null,
                'status' => 'shipped',
                'created_at' => '2024-11-04 08:30:00',
                'updated_at' => '2024-11-04 08:30:00',
            ],
        ]);
    }
}
