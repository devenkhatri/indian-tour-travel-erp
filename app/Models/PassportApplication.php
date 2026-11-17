<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PassportApplication extends Model
{
    protected $fillable = [
        'application_number',
        'customer_id',
        'service_type',
        'applicant_name',
        'gender',
        'dob',
        'place_of_birth',
        'assigned_to',
        'appointment_date',
        'appointment_location',
        'status',
        'fee',
        'remarks',
        'contact_phone',
        'contact_email',
    ];

    protected $casts = [
        'dob' => 'date',
        'appointment_date' => 'date',
        'fee' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
