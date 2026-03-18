<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Bibit Ikan',
                'slug' => 'bibit-ikan',
                'description' => 'Bibit ikan berkualitas untuk budidaya',
                'icon' => 'fish',
                'is_active' => true,
            ],
            [
                'name' => 'Pelet Pakan Ikan',
                'slug' => 'pelet-pakan-ikan',
                'description' => 'Pelet pakan ikan berkualitas tinggi',
                'icon' => 'food',
                'is_active' => true,
            ],
            [
                'name' => 'Pakan Hidup',
                'slug' => 'pakan-hidup',
                'description' => 'Pakan hidup untuk ikan',
                'icon' => 'worm',
                'is_active' => true,
            ],
            [
                'name' => 'Umpan Ikan Laut',
                'slug' => 'umpan-ikan-laut',
                'description' => 'Umpan untuk memancing ikan laut',
                'icon' => 'hook',
                'is_active' => true,
            ],
            [
                'name' => 'Penyewaan Kapal',
                'slug' => 'penyewaan-kapal',
                'description' => 'Layanan penyewaan kapal perikanan',
                'icon' => 'ship',
                'is_active' => true,
            ],
            [
                'name' => 'Vitamin Air',
                'slug' => 'vitamin-air',
                'description' => 'Vitamin untuk kualitas air kolam',
                'icon' => 'water',
                'is_active' => true,
            ],
            [
                'name' => 'Sewa Pancing',
                'slug' => 'sewa-pancing',
                'description' => 'Layanan penyewaan alat pancing air tawar',
                'icon' => 'hook',
                'is_active' => true,
            ],
            [
                'name' => 'Kolam Pemancingan',
                'slug' => 'kolam-pemancingan',
                'description' => 'Layanan akses kolam pemancingan',
                'icon' => 'water',
                'is_active' => true,
            ],
            [
                'name' => 'Komunitas Air Tawar',
                'slug' => 'komunitas-air-tawar',
                'description' => 'Layanan pendaftaran komunitas air tawar',
                'icon' => 'users',
                'is_active' => true,
            ],
            [
                'name' => 'Sewa Pancing Laut',
                'slug' => 'sewa-pancing-laut',
                'description' => 'Layanan penyewaan alat pancing air laut',
                'icon' => 'hook',
                'is_active' => true,
            ],
            [
                'name' => 'Komunitas Air Laut',
                'slug' => 'komunitas-air-laut',
                'description' => 'Layanan pendaftaran komunitas air laut',
                'icon' => 'users',
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
