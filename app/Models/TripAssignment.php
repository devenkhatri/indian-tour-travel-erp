<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TripAssignment extends Model
{
    protected $fillable = [
        'trip_id',
        'supplier_id',
        'type',
        'details',
        'status',
        'cost',
    ];

    protected $casts = [
        'details' => 'array',
        'cost' => 'decimal:2',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(Trip::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }
}
