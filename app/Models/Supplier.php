<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'category',
        'contact_person',
        'email',
        'phone',
        'city',
        'state',
        'country',
        'gstin',
        'pan',
        'bank_details',
        'rating',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'bank_details' => 'array',
        'rating' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function assignments(): HasMany
    {
        return $this->hasMany(TripAssignment::class);
    }
}
