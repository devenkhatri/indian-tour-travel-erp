<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Agent extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'agency_name',
        'agency_code',
        'owner_name',
        'email',
        'phone',
        'city',
        'state',
        'gstin',
        'pan',
        'agency_logo',
        'credit_limit',
        'used_credit',
        'wallet_balance',
        'commission_rate',
        'status',
        'approved_at',
        'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'used_credit' => 'decimal:2',
        'wallet_balance' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class);
    }

    public function commissions(): HasMany
    {
        return $this->hasMany(AgentCommission::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
