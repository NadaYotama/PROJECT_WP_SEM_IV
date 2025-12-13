<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Menu;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'adminpadang@gmail.com',
            'role' => 'admin',
            'phone' => '0812345678901',
            'is_active' => true,
            'password' => bcrypt('admin123'),
        ]);

        // Manager user
        User::create([
            'name' => 'Manager',
            'email' => 'managerpadang@gmail.com',
            'role' => 'manager',
            'phone' => '0812345678902',
            'is_active' => true,
            'password' => bcrypt('manager123'),
        ]);

        // Kasir user
        User::create([
            'name' => 'Kasir',
            'email' => 'kasirpadang@gmail.com',
            'role' => 'kasir',
            'phone' => '081234567892',
            'is_active' => true,
            'password' => bcrypt('kasir123'),
        ]);

        // Customer user
        User::create([
            'name' => 'Customer',
            'email' => 'customerpadang@gmail.com',
            'role' => 'customer',
            'phone' => '0812345678903',
            'is_active' => true,
            'password' => bcrypt('customer123'),
        ]);

        // Seed menus
        $this->call([
            MenuSeeder::class,
        ]);

    }
}