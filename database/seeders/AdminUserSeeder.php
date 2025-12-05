<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        // Create admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@test.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Admin User 2',
            'email' => 'admin2@test.com',
            'password' => '12345678',
            'role' => 'admin',
        ]);

        // Create staff user
        User::create([
            'name' => 'Staff User',
            'email' => 'staff@test.com',
            'password' => Hash::make('password'),
            'role' => 'staff',
        ]);

        User::create([
            'name' => 'Staff User 2',
            'email' => 'staff2@test.com',
            'password' => '12345678',
            'role' => 'staff',
        ]);

        echo "✅ Admin users created:\n";
        echo "   Admin: admin@test.com / password\n";
        echo "   Staff: staff@test.com / password\n";
    }
}
