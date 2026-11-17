<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Destination extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'name',
        'slug',
        'type',
        'state',
        'banner_image',
        'description',
        'best_time_to_visit',
        'weather_info',
        'popular_attractions',
        'popular_activities',
        'travel_tips',
        'faqs',
        'is_popular',
        'is_trending',
        'is_active',
        'seo_title',
        'meta_description',
    ];

    protected $casts = [
        'popular_attractions' => 'array',
        'popular_activities' => 'array',
        'faqs' => 'array',
        'is_popular' => 'boolean',
        'is_trending' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function packages(): HasMany
    {
        return $this->hasMany(TourPackage::class);
    }
}
