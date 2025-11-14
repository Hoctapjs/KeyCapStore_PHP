<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Root categories
        $keyboards = Category::create([
            'name' => 'Keyboards',
            'slug' => 'keyboards',
            'parent_id' => null,
        ]);

        $keycaps = Category::create([
            'name' => 'Keycaps',
            'slug' => 'keycaps',
            'parent_id' => null,
        ]);

        $switches = Category::create([
            'name' => 'Switches',
            'slug' => 'switches',
            'parent_id' => null,
        ]);

        $accessories = Category::create([
            'name' => 'Accessories',
            'slug' => 'accessories',
            'parent_id' => null,
        ]);

        // Sub-categories cho Keyboards
        Category::create(['name' => 'Mechanical Keyboards', 'slug' => 'mechanical-keyboards', 'parent_id' => $keyboards->id]);
        Category::create(['name' => 'Gaming Keyboards', 'slug' => 'gaming-keyboards', 'parent_id' => $keyboards->id]);
        Category::create(['name' => 'Wireless Keyboards', 'slug' => 'wireless-keyboards', 'parent_id' => $keyboards->id]);
        Category::create(['name' => 'Custom Keyboards', 'slug' => 'custom-keyboards', 'parent_id' => $keyboards->id]);

        // Sub-categories cho Keycaps
        Category::create(['name' => 'ABS Keycaps', 'slug' => 'abs-keycaps', 'parent_id' => $keycaps->id]);
        Category::create(['name' => 'PBT Keycaps', 'slug' => 'pbt-keycaps', 'parent_id' => $keycaps->id]);
        Category::create(['name' => 'Artisan Keycaps', 'slug' => 'artisan-keycaps', 'parent_id' => $keycaps->id]);

        // Sub-categories cho Switches
        Category::create(['name' => 'Linear Switches', 'slug' => 'linear-switches', 'parent_id' => $switches->id]);
        Category::create(['name' => 'Tactile Switches', 'slug' => 'tactile-switches', 'parent_id' => $switches->id]);
        Category::create(['name' => 'Clicky Switches', 'slug' => 'clicky-switches', 'parent_id' => $switches->id]);

        // Sub-categories cho Accessories
        Category::create(['name' => 'Cables', 'slug' => 'cables', 'parent_id' => $accessories->id]);
        Category::create(['name' => 'Wrist Rests', 'slug' => 'wrist-rests', 'parent_id' => $accessories->id]);
        Category::create(['name' => 'Tools', 'slug' => 'tools', 'parent_id' => $accessories->id]);
    }
}
