<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

// FISHERIES - Product Model (Laravel Eloquent)

class Product extends Model
{
    use HasFactory;

    protected $table = 'products';

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'short_description',
        'price',
        'member_price',
        'sale_price',
        'stock',
        'sku',
        'images',
        'featured',
        'is_active',
        'meta_title',
        'meta_description',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'member_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
        'images' => 'array',
        'featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the category that owns the product.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the subcategory that owns the product.
     */
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }

    /**
     * Get the locations where the product is available.
     */
    public function locations()
    {
        return $this->belongsToMany(Location::class, 'product_locations')
                    ->withPivot('stok')
                    ->withTimestamps();
    }

    /**
     * Get the order items for the product.
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the variations for the product.
     */
    public function variations(): HasMany
    {
        return $this->hasMany(ProductVariation::class);
    }

    /**
     * Check if product is on sale.
     */
    public function isOnSale(): bool
    {
        return $this->sale_price > 0 && $this->sale_price < $this->price;
    }

    /**
     * Get the final price (regular or sale).
     */
    public function getFinalPrice(): float
    {
        return $this->isOnSale() ? $this->sale_price : $this->price;
    }

    /**
     * Get price for specific user role.
     * Admin and Member get member_price, User gets regular price.
     */
    public function getPriceForUser(?string $userRole = null): float
    {
        // Default to regular price
        $basePrice = $this->getFinalPrice();
        
        // If user is admin or member, use member price if available
        if (($userRole === 'admin' || $userRole === 'member') && $this->member_price > 0) {
            // Check if there's an active sale price that's even lower
            if ($this->isOnSale() && $this->sale_price < $this->member_price) {
                return $this->sale_price;
            }
            return $this->member_price;
        }
        
        return $basePrice;
    }

    /**
     * Check if product is in stock.
     */
    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    /**
     * Check if product is Pakan Hidup category.
     */
    public function isPakanHidup(): bool
    {
        return $this->category && $this->category->is_pakan_hidup;
    }

    /**
     * Scope a query to only include featured products.
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Scope a query to only include active products.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to only include Pakan Hidup products.
     */
    public function scopePakanHidup($query)
    {
        return $query->whereHas('category', function ($q) {
            $q->where('is_pakan_hidup', true);
        });
    }

    /**
     * Synchronize total stock from location-based stock.
     */
    public function syncStock(): int
    {
        $totalStock = (int) $this->locations()->sum('product_locations.stok');
        $this->update(['stock' => $totalStock]);

        // Sync variations that are set to follow total stock
        $this->variations()->where('is_stock_synced', true)->update(['stock' => $totalStock]);

        return $totalStock;
    }
}
