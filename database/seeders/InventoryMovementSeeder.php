<?php

namespace Database\Seeders;

use App\Models\InventoryMovement;
use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Database\Seeder;

class InventoryMovementSeeder extends Seeder
{
    public function run(): void
    {
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->warn('Không có products. Hãy chạy ProductSeeder trước.');
            return;
        }

        // Tạo inventory movements cho products
        foreach ($products->random(min(20, $products->count())) as $product) {
            InventoryMovement::factory(rand(2, 5))->create([
                'product_id' => $product->id,
                'variant_id' => null,
            ]);
        }

        // Tạo inventory movements cho variants
        $variants = ProductVariant::all();
        foreach ($variants->random(min(20, $variants->count())) as $variant) {
            InventoryMovement::factory(rand(1, 3))->create([
                'product_id' => $variant->product_id,
                'variant_id' => $variant->id,
            ]);
        }
    }
}
