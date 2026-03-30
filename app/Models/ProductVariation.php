<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductVariation extends Model
{
    use HasFactory;

    protected $table = 'product_variations';

    protected $fillable = [
        'product_id',
        'type', // e.g., 'Ukuran', 'Warna'
        'name', // e.g., 'XL', 'Merah'
        'price_adjustment', // e.g., 5000.00
        'stock',
        'image',
        'description',
    ];

    protected $casts = [
        'price_adjustment' => 'decimal:2',
        'stock' => 'integer',
    ];

    /**
     * Get the product that owns the variation.
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}
