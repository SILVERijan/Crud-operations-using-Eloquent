<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = Carbon::now();
        
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'description' => 'Administrator with full CRUD access to all posts and resources',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Reader',
                'slug' => 'reader',
                'description' => 'Reader with view-only access to posts',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'name' => 'Customer',
                'slug' => 'customer',
                'description' => 'Customer who can create, view, and delete their own forms',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('roles')->insert($roles);
    }
}
