<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Lead extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'lead_number',
        'customer_id',
        'name',
        'email',
        'phone',
        'departure_city',
        'destination',
        'lead_source_id',
        'assigned_to',
        'branch_id',
        'category',
        'priority',
        'status',
        'lost_reason',
        'budget',
        'adults',
        'children',
        'infants',
        'travel_start_date',
        'travel_end_date',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'budget' => 'decimal:2',
        'travel_start_date' => 'date',
        'travel_end_date' => 'date',
        'adults' => 'integer',
        'children' => 'integer',
        'infants' => 'integer',
    ];

    public function customer(): BelongsTo
    {
        return $this->belongsTo(Customer::class);
    }

    public function source(): BelongsTo
    {
        return $this->belongsTo(LeadSource::class, 'lead_source_id');
    }

    public function assignedEmployee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function followups(): HasMany
    {
        return $this->hasMany(LeadFollowup::class);
    }

    public function activities(): HasMany
    {
        return $this->hasMany(LeadActivity::class);
    }

    public function quotations(): HasMany
    {
        return $this->hasMany(Quotation::class);
    }
}
