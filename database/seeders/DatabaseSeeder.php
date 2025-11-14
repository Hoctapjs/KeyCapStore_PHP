<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo users trước
        User::factory(20)->create();

        User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        // Module 2: Catalog (Products, Categories, Brands, Tags)
        $this->call([
            BrandSeeder::class,
            CategorySeeder::class,
            ProductTagSeeder::class,
            ProductSeeder::class, // Tạo products với variants, images
            ReviewSeeder::class,
            InventoryMovementSeeder::class,
        ]);

        // Module 3: Orders, Carts, Payments (của Nam)
        $this->call([
            CartSeeder::class,
            CartItemSeeder::class,
            OrderSeeder::class,
            OrderItemSeeder::class,
            PaymentSeeder::class,
            ShipmentSeeder::class,
            CouponSeeder::class,
            OrderCouponSeeder::class,
            CouponRedemptionSeeder::class,
        ]);
    }
}
