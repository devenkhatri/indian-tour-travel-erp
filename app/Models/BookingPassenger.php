<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookingPassenger extends Model
{
    protected $fillable = [
        'booking_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'type',
        'passport_number',
        'passport_expiry',
        'passport_country',
        'ticket_number',
        'seat_number',
        'meal_preference',
    ];

    protected $casts = [
        'dob' => 'date',
        'passport_expiry' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getTitleAttribute(): string
    {
        return $this->gender === 'Female' ? 'Ms.' : 'Mr.';
    }

    public function getPassengerTypeAttribute(): string
    {
        return $this->type ?? 'adult';
    }
}
