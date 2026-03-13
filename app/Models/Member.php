<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// FISHERIES - Member Model (Laravel Eloquent)

class Member extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'members';

    protected $fillable = [
        'email',
        'password',
        'full_name',
        'phone',
        'address',
        'avatar',
        'membership_number',
        'dpc',
        'occupation',
        'join_date',
        'status',
        'expiry_date',
        'benefits',
    ];

    protected $casts = [
        'join_date' => 'date',
        'expiry_date' => 'date',
        'benefits' => 'array',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the name attribute (alias for full_name).
     */
    public function getNameAttribute()
    {
        return $this->full_name;
    }

    /**
     * Set the name attribute (alias for full_name).
     */
    public function setNameAttribute($value)
    {
        $this->attributes['full_name'] = $value;
    }

    /**
     * Get the member_number attribute (alias for membership_number).
     */
    public function getMemberNumberAttribute()
    {
        return $this->membership_number;
    }

    /**
     * Set the member_number attribute (alias for membership_number).
     */
    public function setMemberNumberAttribute($value)
    {
        $this->attributes['membership_number'] = $value;
    }

    /**
     * Check if membership is active.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && strtotime($this->expiry_date) > time();
    }

    /**
     * Get member card data.
     */
    public function getCardData(): array
    {
        return [
            'number' => $this->member_number,
            'name' => $this->name,
            'dpc' => $this->dpc,
            'occupation' => $this->occupation,
            'join_date' => $this->join_date,
            'expiry_date' => $this->expiry_date,
            'status' => $this->status,
        ];
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return false; // Member tidak bisa jadi admin
    }

    /**
     * Check if user is member.
     */
    public function isMember(): bool
    {
        return true; // Member selalu member
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return false; // Member bukan user biasa
    }

    /**
     * Get user role.
     */
    public function getRole(): string
    {
        return 'member';
    }
}
