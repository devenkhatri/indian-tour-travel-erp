<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Booking extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'booking_number',
        'customer_id',
        'user_id',
        'agent_id',
        'service_type',
        'package_id',
        'departure_date',
        'return_date',
        'adults',
        'children',
        'infants',
        'selling_amount',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'balance_amount',
        'supplier_cost',
        'expected_profit',
        'assigned_to',
        'booking_status',
        'payment_status',
        'notes',
        'cancellation_reason',
    ];

    protected $casts = [
        'departure_date' => 'date',
        'return_date' => 'date',
        'selling_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'balance_amount' => 'decimal:2',
        'supplier_cost' => 'decimal:2',
        'expected_profit' => 'decimal:2',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function agent(): BelongsTo
    {
        return $this->belongsTo(Agent::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function passengers(): HasMany
    {
        return $this->hasMany(BookingPassenger::class);
    }

    public function vouchers(): HasMany
    {
        return $this->hasMany(BookingVoucher::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class);
    }

    public function trip(): HasOne
    {
        return $this->hasOne(Trip::class);
    }

    public function getBookingReferenceAttribute(): string
    {
        return $this->booking_number;
    }

    public function getAdultsCountAttribute(): int
    {
        return (int) $this->adults;
    }

    public function getChildrenCountAttribute(): int
    {
        return (int) $this->children;
    }

    public function getBasePriceAttribute(): float
    {
        return (float) $this->selling_amount;
    }
}
