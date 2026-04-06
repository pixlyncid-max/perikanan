<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = [
        'nama',
        'alamat',
        'latitude',
        'longitude',
    ];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_locations')
                    ->withPivot('stok')
                    ->withTimestamps();
    }
}
