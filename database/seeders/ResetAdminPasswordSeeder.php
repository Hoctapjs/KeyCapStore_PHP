<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class ResetAdminPasswordSeeder extends Seeder
{
    public function run(): void
    {
        // Delete existing admin users
        User::whereIn('email', ['admin@test.com', 'staff@test.com'])->delete();
        
        // Create fresh admin user - Let mutator handle password hashing
        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@test.com',
            'password' => '12345678',  // Plain text - mutator will hash it
            'role' => 'admin',
        ]);

        // Create fresh staff user
        $staff = User::create([
            'name' => 'Staff',
            'email' => 'staff@test.com',
            'password' => '12345678',  // Plain text - mutator will hash it
            'role' => 'staff',
        ]);

        echo "✅ Admin users reset successfully!\n";
        echo "   Admin: admin@test.com / 12345678\n";
        echo "   Staff: staff@test.com / 12345678\n";
        echo "\n";
        echo "   Admin ID: {$admin->id}\n";
        echo "   Staff ID: {$staff->id}\n";
    }
}
