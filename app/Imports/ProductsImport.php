<?php

namespace App\Imports;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCustomCsvSettings;

class ProductsImport implements ToModel, WithHeadingRow, WithCustomCsvSettings
{
    public function getCsvSettings(): array
    {
        return [
            'delimiter' => ';'
        ];
    }
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        // Requires product name, skip if empty
        if (!isset($row['nama_produk']) || empty(trim($row['nama_produk']))) {
            return null;
        }

        // Handle category discovery or creation
        $categoryId = null;
        $categoryName = $row['kategori'] ?? null;
        if (!empty(trim($categoryName))) {
            $category = Category::firstOrCreate(
                ['name' => trim($categoryName)],
                ['slug' => Str::slug(trim($categoryName))]
            );
            $categoryId = $category->id;
        }

        // Handle Auto SKU logic if empty
        $sku = trim($row['sku'] ?? '');
        if (empty($sku)) {
            $prefix = 'PRD';
            if (!empty(trim($categoryName))) {
                $cleanName = preg_replace('/[^A-Za-z0-9]/', '', trim($categoryName));
                $prefix = strtoupper(substr($cleanName, 0, 3));
                if (strlen($prefix) < 3) {
                    $prefix = str_pad($prefix, 3, 'X');
                }
            }
            $latestProduct = Product::where('sku', 'like', $prefix . '-%')->orderBy('id', 'desc')->first();
            $nextNumber = 1;
            if ($latestProduct && $latestProduct->sku) {
                $parts = explode('-', $latestProduct->sku);
                if (count($parts) > 1 && is_numeric(end($parts))) {
                    $nextNumber = intval(end($parts)) + 1;
                }
            }
            $sku = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            while (Product::where('sku', $sku)->exists()) {
                $nextNumber++;
                $sku = $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
            }
        }

        // Ensure slug uniqueness
        $slug = Str::slug(trim($row['nama_produk']));
        $originalSlug = $slug;
        $count = 1;
        while (Product::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $count++;
        }

        // Parse prices natively
        $price = floatval(preg_replace('/[^0-9.]/', '', $row['harga_normal'] ?? 0));
        $salePrice = isset($row['harga_diskon']) && !empty($row['harga_diskon']) ? floatval(preg_replace('/[^0-9.]/', '', $row['harga_diskon'])) : 0;
        $memberPrice = isset($row['harga_member']) && !empty($row['harga_member']) ? floatval(preg_replace('/[^0-9.]/', '', $row['harga_member'])) : 0;

        return new Product([
            'name'              => trim($row['nama_produk']),
            'slug'              => $slug,
            'category_id'       => $categoryId,
            'description'       => trim($row['deskripsi_lengkap'] ?? ''),
            'short_description' => trim($row['deskripsi_singkat'] ?? ''),
            'price'             => $price,
            'sale_price'        => $salePrice,
            'member_price'      => $memberPrice,
            'stock'             => intval($row['stok'] ?? 0),
            'sku'               => $sku,
            'featured'          => strtolower(trim($row['unggulan'] ?? '')) === 'ya' ? 1 : 0,
            'is_active'         => strtolower(trim($row['status_aktif'] ?? '')) === 'tidak' ? 0 : 1, // default active
        ]);
    }
}
