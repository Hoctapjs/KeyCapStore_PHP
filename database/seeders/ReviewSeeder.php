<?php

namespace Database\Seeders;

use App\Models\Review;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        $users = User::all();

        if ($products->isEmpty() || $users->isEmpty()) {
            $this->command->warn('Không có products hoặc users để tạo reviews. Hãy chạy ProductSeeder và UserSeeder trước.');
            return;
        }

        // Tạo 100 reviews ngẫu nhiên
        foreach ($products->random(min(30, $products->count())) as $product) {
            Review::factory(rand(1, 5))->create([
                'product_id' => $product->id,
                'user_id' => $users->random()->id,
            ]);
        }
    }
}
