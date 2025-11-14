<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Brand;
use App\Models\Category;
use App\Models\ProductTag;
use App\Models\ProductVariant;
use App\Models\ProductImage;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $brands = Brand::all();
        $categories = Category::whereNotNull('parent_id')->get(); // Chỉ lấy sub-categories
        $tags = ProductTag::all();

        // Tạo 50 sản phẩm
        Product::factory(50)->create()->each(function ($product) use ($categories, $tags) {
            // Gán categories (1-3 categories)
            $productCategories = $categories->random(rand(1, 3));
            $primarySet = false;
            
            foreach ($productCategories as $category) {
                $product->categories()->attach($category->id, [
                    'primary_flag' => !$primarySet
                ]);
                $primarySet = true;
            }

            // Gán tags (0-5 tags)
            if ($tags->count() > 0) {
                $product->tags()->attach(
                    $tags->random(rand(0, min(5, $tags->count())))->pluck('id')->toArray()
                );
            }

            // Tạo product images (2-5 images)
            ProductImage::factory(rand(2, 5))->create([
                'product_id' => $product->id,
            ]);

            // Tạo product variants (1-4 variants)
            ProductVariant::factory(rand(1, 4))->create([
                'product_id' => $product->id,
            ]);
        });
    }
}
