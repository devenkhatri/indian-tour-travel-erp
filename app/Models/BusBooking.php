<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'from_city',
        'to_city',
        'journey_date',
        'bus_type',
        'boarding_point',
        'dropping_point',
        'passenger_count',
        'ticket_number',
        'status',
        'ticket_file',
        'contact_name',
        'contact_phone',
        'remarks',
    ];

    protected $casts = [
        'journey_date' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }
}
