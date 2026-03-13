<?php

namespace Database\Seeders;

use App\Models\Product;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            [
                'category_id' => 1,
                'subcategory_id' => 1,
                'name' => 'Bibit Lele 5-7 cm',
                'slug' => 'bibit-lele-5-7-cm',
                'description' => 'Bibit ikan lele berkualitas ukuran 5-7 cm',
                'price' => 500,
                'stock' => 1000,
                'unit' => 'ekor',
                'status' => 'active',
                'featured' => true,
            ],
            [
                'category_id' => 1,
                'subcategory_id' => 2,
                'name' => 'Bibit Nila 3-5 cm',
                'slug' => 'bibit-nila-3-5-cm',
                'description' => 'Bibit ikan nila unggul ukuran 3-5 cm',
                'price' => 800,
                'stock' => 800,
                'unit' => 'ekor',
                'status' => 'active',
                'featured' => true,
            ],
            [
                'category_id' => 2,
                'subcategory_id' => 4,
                'name' => 'Pelet Apung Premium',
                'slug' => 'pelet-apung-premium',
                'description' => 'Pakan pelet apung kualitas premium',
                'price' => 15000,
                'stock' => 100,
                'unit' => 'kg',
                'status' => 'active',
                'featured' => false,
            ],
            [
                'category_id' => 2,
                'subcategory_id' => 5,
                'name' => 'Pelet Tenggelam 781-2',
                'slug' => 'pelet-tenggelam-781-2',
                'description' => 'Pakan pelet tenggelam untuk lele',
                'price' => 12000,
                'stock' => 150,
                'unit' => 'kg',
                'status' => 'active',
                'featured' => false,
            ],
        ];

        foreach ($products as $product) {
            Product::create($product);
        }
    }
}
