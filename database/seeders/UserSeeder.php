<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create default admin user
        User::create([
            'name' => 'Administrator',
            'email' => 'admin@fisheries.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567890',
            'address' => 'Jl. Perikanan No. 1, Samarinda',
        ]);

        // Create sample users
        User::create([
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567891',
            'address' => 'Jl. Mawar No. 10, Balikpapan',
        ]);

        User::create([
            'name' => 'Ani Wijaya',
            'email' => 'ani@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567892',
            'address' => 'Jl. Melati No. 5, Bontang',
        ]);
    }
}
