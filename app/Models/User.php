<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'role',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Check if user is admin
     */
    public function isAdmin(): bool
    {
        return $this->role === 'admin' || $this->hasRole('admin');
    }

    /**
     * Check if user is gudang
     */
    public function isGudang(): bool
    {
        return $this->role === 'gudang' || $this->hasRole('gudang');
    }

    /**
     * Check if user is kasir
     */
    public function isKasir(): bool
    {
        return $this->role === 'kasir' || $this->hasRole('kasir');
    }

    /**
     * Check if user is viewer
     */
    public function isViewer(): bool
    {
        return $this->role === 'viewer' || $this->hasRole('viewer');
    }

    /**
     * Get role badge color
     */
    public function getRoleBadgeColor(): string
    {
        return match($this->role) {
            'admin' => 'bg-purple-100 text-purple-800',
            'gudang' => 'bg-blue-100 text-blue-800',
            'kasir' => 'bg-green-100 text-green-800',
            'viewer' => 'bg-gray-100 text-gray-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    /**
     * Get role display name
     */
    public function getRoleDisplayName(): string
    {
        return match($this->role) {
            'admin' => '👑 Administrator',
            'gudang' => '📦 Staff Gudang',
            'kasir' => '💰 Kasir',
            'viewer' => '👁️ Viewer',
            default => ucfirst($this->role),
        };
    }
}
