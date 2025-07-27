<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create roles if they don't exist
        $adminRole = Role::firstOrCreate(['name' => 'admin']);
        $sellerRole = Role::firstOrCreate(['name' => 'seller']);
        $buyerRole = Role::firstOrCreate(['name' => 'buyer']);

        // Define permissions (e.g., for admin to manage users)
        Permission::firstOrCreate(['name' => 'manage users']);
        Permission::firstOrCreate(['name' => 'manage products']);
        Permission::firstOrCreate(['name' => 'view orders']);
        Permission::firstOrCreate(['name' => 'place orders']);

        // Assign permissions to roles
        $adminRole->givePermissionTo(['manage users', 'manage products', 'view orders']);
        $sellerRole->givePermissionTo('manage products');
        $buyerRole->givePermissionTo('place orders');

        // You can assign a default role to new users, e.g., 'buyer'
        // Or you can create a test user and assign a role to it
        \App\Models\User::factory()->create([
            'name' => 'Admin User',
            'email' => 'admin@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('admin');

        \App\Models\User::factory()->create([
            'name' => 'Seller User',
            'email' => 'seller@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('seller');

        \App\Models\User::factory()->create([
            'name' => 'Buyer User',
            'email' => 'buyer@example.com',
            'password' => bcrypt('password'),
        ])->assignRole('buyer');
    }
}