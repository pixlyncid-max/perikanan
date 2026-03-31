<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// FISHERIES - Admin Model (Laravel Eloquent)

class Admin extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $table = 'admins';

    protected $fillable = [
        'full_name',
        'email',
        'password',
        'phone',
        'address',
        'avatar',
        'role',
        'permissions',
        'account_status',
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


    protected $casts = [
        'permissions' => 'array',
        'password' => 'hashed',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Check if admin has specific permission.
     */
    public function hasPermission(string $permission): bool
    {
        $permissions = $this->permissions ?? [];
        return in_array($permission, $permissions) || in_array('all', $permissions);
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return true; // Admin selalu admin
    }

    /**
     * Check if user is member.
     */
    public function isMember(): bool
    {
        return true; // Admin juga dianggap member (memiliki hak akses member)
    }

    /**
     * Check if user is regular user.
     */
    public function isUser(): bool
    {
        return false; // Admin bukan user biasa
    }

    /**
     * Get user role.
     */
    public function getRole(): string
    {
        return 'admin';
    }
}
