<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Booking extends Model
{
    protected $fillable = [
        'coach_id',
        'service_id',
        'client_name',
        'starts_at',
        'duration',
        'status',
        'modality',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime', // Automatically converts MySQL timestamp to Carbon Date object
        ];
    }

    /**
     * RELATIONSHIPS
     */
    public function coach(): BelongsTo
    {
        return $this->belongsTo(Coach::class);
    }

    public function service(): BelongsTo
    {
        return $this->belongsTo(Service::class);
    }
}