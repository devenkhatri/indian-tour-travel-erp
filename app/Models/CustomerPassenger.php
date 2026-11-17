<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CustomerPassenger extends Model
{
    protected $fillable = [
        'customer_id',
        'first_name',
        'last_name',
        'gender',
        'dob',
        'relationship',
        'passport_number',
        'passport_expiry',
        'passport_country',
    ];

    protected $casts = [
        'dob' => 'date',
        'passport_expiry' => 'date',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function getFullNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }
}
