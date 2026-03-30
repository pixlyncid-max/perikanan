<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProductVariationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = \App\Models\Product::take(5)->get();
        
        foreach ($products as $index => $product) {
            if ($index % 2 == 0) {
                // Add sizes
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Ukuran',
                    'name' => 'S',
                    'stock' => 10,
                ]);
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Ukuran',
                    'name' => 'M',
                    'stock' => 15,
                ]);
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Ukuran',
                    'name' => 'L',
                    'stock' => 5,
                    'price_adjustment' => 5000,
                ]);
            } else {
                // Add colors
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Warna',
                    'name' => 'Blue',
                    'stock' => 8,
                ]);
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Warna',
                    'name' => 'Khaki',
                    'stock' => 12,
                ]);
                \App\Models\ProductVariation::create([
                    'product_id' => $product->id,
                    'type' => 'Warna',
                    'name' => 'Black',
                    'stock' => 20,
                ]);
            }
        }
    }
}
