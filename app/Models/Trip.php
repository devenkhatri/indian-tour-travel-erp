<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Trip extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_code',
        'booking_id',
        'package_id',
        'title',
        'start_date',
        'end_date',
        'tour_leader_id',
        'status',
        'vehicle_info',
        'driver_info',
        'guide_info',
        'special_instructions',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function tourLeader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tour_leader_id');
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(TripAssignment::class);
    }
}
