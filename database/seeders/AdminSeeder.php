<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'full_name' => 'Super Administrator',
            'email' => 'superadmin@fisheries.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567899',
            'address' => 'Kantor Pusat DPP Perikanan',
            'role' => 'super_admin',
            'permissions' => json_encode(['all']),
            'account_status' => 'active',
        ]);

        Admin::create([
            'full_name' => 'Admin Keuangan',
            'email' => 'keuangan@fisheries.com',
            'password' => Hash::make('admin123'),
            'phone' => '081234567898',
            'address' => 'Kantor DPP Perikanan',
            'role' => 'admin',
            'permissions' => json_encode(['finance', 'reports']),
            'account_status' => 'active',
        ]);
    }
}
