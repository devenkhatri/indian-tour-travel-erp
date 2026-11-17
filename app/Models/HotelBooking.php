<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HotelBooking extends Model
{
    protected $fillable = [
        'booking_id',
        'customer_id',
        'hotel_name',
        'city',
        'check_in_date',
        'check_out_date',
        'rooms_count',
        'adults',
        'children',
        'room_type',
        'meal_plan',
        'confirmation_code',
        'status',
        'voucher_file',
        'total_price',
        'contact_name',
        'contact_phone',
        'contact_email',
    ];

    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'total_price' => 'decimal:2',
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
