<?php

namespace Database\Seeders;

use App\Models\Subcategory;
use Illuminate\Database\Seeder;

class SubcategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $subcategories = [
            // Bibit Ikan (category_id: 1)
            [
                'category_id' => 1,
                'name' => 'Bibit Lele',
                'slug' => 'bibit-lele',
                'description' => 'Bibit ikan lele berkualitas',
                'status' => 'active',
            ],
            [
                'category_id' => 1,
                'name' => 'Bibit Nila',
                'slug' => 'bibit-nila',
                'description' => 'Bibit ikan nila unggul',
                'status' => 'active',
            ],
            [
                'category_id' => 1,
                'name' => 'Bibit Gurame',
                'slug' => 'bibit-gurame',
                'description' => 'Bibit ikan gurame',
                'status' => 'active',
            ],
            // Pakan Ikan (category_id: 2)
            [
                'category_id' => 2,
                'name' => 'Pelet Apung',
                'slug' => 'pelet-apung',
                'description' => 'Pakan pelet apung',
                'status' => 'active',
            ],
            [
                'category_id' => 2,
                'name' => 'Pelet Tenggelam',
                'slug' => 'pelet-tenggelam',
                'description' => 'Pakan pelet tenggelam',
                'status' => 'active',
            ],
            [
                'category_id' => 2,
                'name' => 'Pakan Hidup',
                'slug' => 'pakan-hidup',
                'description' => 'Cacing, jentik nyamuk, dll',
                'status' => 'active',
            ],
        ];

        foreach ($subcategories as $subcategory) {
            Subcategory::create($subcategory);
        }
    }
}
