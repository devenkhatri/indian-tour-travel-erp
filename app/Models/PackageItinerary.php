<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PackageItinerary extends Model
{
    protected $fillable = [
        'package_id',
        'day_number',
        'title',
        'description',
        'hotel_info',
        'meals_info',
        'transport_info',
        'activity_info',
        'image',
    ];

    public function package(): BelongsTo
    {
        return $this->belongsTo(TourPackage::class, 'package_id');
    }

    public function getMealsIncludedAttribute(): ?string
    {
        return $this->meals_info;
    }

    public function getHotelStayAttribute(): ?string
    {
        return $this->hotel_info;
    }

    public function getActivitiesAttribute(): ?string
    {
        return $this->activity_info;
    }
}
