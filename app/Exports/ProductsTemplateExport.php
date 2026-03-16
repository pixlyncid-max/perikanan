<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ProductsTemplateExport implements FromArray, WithHeadings, ShouldAutoSize
{
    /**
     * Define headers for the template.
     */
    public function headings(): array
    {
        return [
            'SKU', 
            'Nama Produk', 
            'Kategori', 
            'Deskripsi Singkat', 
            'Deskripsi Lengkap', 
            'Harga Normal', 
            'Harga Diskon', 
            'Harga Member', 
            'Stok', 
            'Unggulan', 
            'Status Aktif'
        ];
    }

    /**
     * Provide a sample row of data for the template.
     */
    public function array(): array
    {
        return [
            [
                '', // SKU (optional, can be auto-generated)
                'Bibit Nila Merah Super', 
                'Bibit Ikan', 
                'Bibit nila merah tahan banting', 
                'Sangat cocok untuk peternak...', 
                '5000', 
                '', 
                '', 
                '1000', 
                'Ya', 
                'Ya'
            ]
        ];
    }
}
