<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;

// FISHERIES - Organization Model (Laravel Eloquent)

class Organization extends Model
{
    use HasFactory;

    protected $table = 'organizations';

    protected $fillable = [
        'name',
        'type',
        'city',
        'address',
        'phone',
        'email',
        'chairman',
        'secretary',
        'treasurer',
        'established_year',
        'member_count',
        'facilities',
        'description',
        'logo',
        'is_active',
    ];

    protected $casts = [
        'facilities' => 'array',
        'is_active' => 'boolean',
    ];

    /**
     * Get full address.
     */
    public function getFullAddress(): string
    {
        return $this->address . ', ' . $this->city;
    }

    /**
     * Get management structure.
     */
    public function getManagement(): array
    {
        return [
            'Ketua' => $this->chairman,
            'Sekretaris' => $this->secretary,
            'Bendahara' => $this->treasurer,
        ];
    }

    /**
     * Check if DPC is active.
     */
    public function isActive(): bool
    {
        return $this->is_active;
    }
}
