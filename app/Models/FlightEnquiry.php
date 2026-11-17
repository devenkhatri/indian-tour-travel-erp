<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FlightEnquiry extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'trip_type',
        'departure_airport',
        'arrival_airport',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'cabin_class',
        'preferred_airline',
        'pnr_number',
        'ticket_file',
        'status',
        'contact_name',
        'contact_email',
        'contact_phone',
        'quoted_price',
        'remarks',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'quoted_price' => 'decimal:2',
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
