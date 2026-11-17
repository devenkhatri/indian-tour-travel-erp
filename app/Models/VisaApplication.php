<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class VisaApplication extends Model
{
    protected $fillable = [
        'application_number',
        'booking_id',
        'customer_id',
        'country_id',
        'visa_category',
        'applicant_name',
        'passport_number',
        'passport_expiry',
        'travel_date',
        'fee',
        'supplier_cost',
        'assigned_to',
        'status',
        'appointment_date',
        'submission_date',
        'expected_date',
        'actual_date',
        'visa_document',
        'remarks',
        'contact_phone',
        'contact_email',
    ];

    protected $casts = [
        'passport_expiry' => 'date',
        'travel_date' => 'date',
        'fee' => 'decimal:2',
        'supplier_cost' => 'decimal:2',
        'appointment_date' => 'date',
        'submission_date' => 'date',
        'expected_date' => 'date',
        'actual_date' => 'date',
    ];

    public function booking(): BelongsTo
    {
        return $this->belongsTo(Booking::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function documents(): HasMany
    {
        return $this->hasMany(VisaDocument::class);
    }
}
