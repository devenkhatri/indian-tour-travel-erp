<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageDeparture extends Model
{
    protected $fillable = [
        'package_id',
        'departure_date',
        'return_date',
        'total_seats',
        'available_seats',
        'price_override',
        'status',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'price_override' => 'decimal:2',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }
}
