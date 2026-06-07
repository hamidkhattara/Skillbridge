<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Service extends Model
{
    use HasFactory;

    /**
     * 1. MASS ASSIGNMENT PROTECTION
     */
    protected $fillable = [
        'name',
        'description',
        'default_duration',
        'price',
        'billing_unit',
        'modality',
        'color',
        'is_active',
        'is_recurring_default',
    ];

    /**
     * 2. ATTRIBUTE CASTING (Laravel 11 syntax)
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'is_recurring_default' => 'boolean',
            'price' => 'decimal:2',
        ];
    }

    /**
     * 3. LOCAL SCOPES
     */
    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}