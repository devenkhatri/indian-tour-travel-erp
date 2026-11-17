<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TrainBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'from_station',
        'to_station',
        'journey_date',
        'class_type',
        'train_number',
        'pnr_number',
        'passenger_count',
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
