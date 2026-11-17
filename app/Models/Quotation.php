<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_number',
        'lead_id',
        'customer_id',
        'package_id',
        'title',
        'destination',
        'start_date',
        'end_date',
        'adults',
        'children',
        'infants',
        'itinerary_details',
        'hotels_details',
        'transports_details',
        'inclusions',
        'exclusions',
        'subtotal',
        'tax_rate',
        'tax_amount',
        'discount_amount',
        'total_amount',
        'supplier_cost',
        'profit_margin',
        'valid_until',
        'status',
        'terms_conditions',
        'created_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'valid_until' => 'date',
        'itinerary_details' => 'array',
        'hotels_details' => 'array',
        'transports_details' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'subtotal' => 'decimal:2',
        'tax_rate' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'supplier_cost' => 'decimal:2',
        'profit_margin' => 'decimal:2',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
