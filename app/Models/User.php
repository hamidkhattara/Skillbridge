<?php

namespace App\Models;

// 1. Add these two Filament imports
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// 2. Add "implements FilamentUser" to the class definition
class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
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

    // 3. Add the Security Rules!
    public function canAccessPanel(Panel $panel): bool
    {
        // If they are trying to access the Boss's panel...
        if ($panel->getId() === 'admin') {
            return $this->role === 'admin';
        }

        // If they are trying to access the Employee's panel...
        if ($panel->getId() === 'coach') {
            return $this->role === 'coach';
        }

        // If it's anything else, lock them out.
        return false;
    }
    public function coach()
    {
        return $this->hasOne(Coach::class);
    }
}