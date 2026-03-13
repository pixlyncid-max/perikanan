<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MemberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Member::create([
            'full_name' => 'Ahmad Fauzi',
            'email' => 'ahmad.fauzi@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567880',
            'address' => 'Jl. Kenanga No. 15, Samarinda',
            'membership_number' => 'FIS-2024-0001',
            'join_date' => '2024-01-15',
            'expiry_date' => '2025-01-15',
            'status' => 'active',
            'benefits' => 'Diskon 10% untuk pembelian bibit ikan',
        ]);

        Member::create([
            'full_name' => 'Siti Rahayu',
            'email' => 'siti.rahayu@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567881',
            'address' => 'Jl. Cempaka No. 8, Balikpapan',
            'membership_number' => 'FIS-2024-0002',
            'join_date' => '2024-02-20',
            'expiry_date' => '2025-02-20',
            'status' => 'active',
            'benefits' => 'Akses pelatihan budidaya ikan gratis',
        ]);

        Member::create([
            'full_name' => 'Bambang Wijaya',
            'email' => 'bambang.wijaya@example.com',
            'password' => Hash::make('password'),
            'phone' => '081234567882',
            'address' => 'Jl. Anggrek No. 22, Bontang',
            'membership_number' => 'FIS-2024-0003',
            'join_date' => '2024-03-10',
            'expiry_date' => '2025-03-10',
            'status' => 'inactive',
            'benefits' => 'Diskon 5% untuk penyewaan kapal',
        ]);
    }
}
