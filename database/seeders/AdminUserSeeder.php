<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin user already exists
        $adminEmail = 'admin@example.com';
        
        if (User::where('email', $adminEmail)->exists()) {
            $this->command->info('Admin user already exists!');
            return;
        }

        // Create Admin User
        $admin = User::create([
            'name' => 'Admin User',
            'email' => $adminEmail,
            'password' => Hash::make('password'), // Change this in production!
        ]);

        // Assign admin role
        $adminRole = Role::where('slug', Role::ADMIN)->first();
        
        if ($adminRole) {
            $admin->roles()->attach($adminRole);
            $this->command->info('Admin user created successfully!');
            $this->command->info('Email: admin@example.com');
            $this->command->info('Password: password');
        } else {
            $this->command->error('Admin role not found! Please run RoleSeeder first.');
        }
    }
}
