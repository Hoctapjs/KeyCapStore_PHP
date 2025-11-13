<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CartSeeder extends Seeder
{
    public function run()
    {
        DB::table('carts')->insert([
            [
                'id' => 1,
                'user_id' => 2,
                'session_id' => null,
                'created_at' => '2024-11-02 09:00:00',
                'updated_at' => '2024-11-02 09:15:00',
            ],
            [
                'id' => 2,
                'user_id' => 3,
                'session_id' => null,
                'created_at' => '2024-11-03 14:20:00',
                'updated_at' => '2024-11-03 14:35:00',
            ],
        ]);
    }
}
