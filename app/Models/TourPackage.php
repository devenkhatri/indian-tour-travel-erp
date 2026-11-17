<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TourPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'packages';

    protected $fillable = [
        'destination_id',
        'category_id',
        'package_code',
        'title',
        'slug',
        'type',
        'duration_days',
        'duration_nights',
        'starting_price',
        'discount_price',
        'adult_price',
        'child_price',
        'infant_price',
        'departure_city',
        'pickup_drop_info',
        'featured_image',
        'gallery',
        'highlights',
        'inclusions',
        'exclusions',
        'terms_conditions',
        'cancellation_policy',
        'min_group_size',
        'max_group_size',
        'difficulty_level',
        'is_featured',
        'is_active',
        'status',
        'tags',
        'seo_title',
        'meta_description',
    ];

    protected $casts = [
        'gallery' => 'array',
        'highlights' => 'array',
        'inclusions' => 'array',
        'exclusions' => 'array',
        'tags' => 'array',
        'starting_price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'adult_price' => 'decimal:2',
        'child_price' => 'decimal:2',
        'infant_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function destination(): BelongsTo
    {
        return $this->belongsTo(Destination::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(PackageCategory::class, 'category_id');
    }

    public function itineraries(): HasMany
    {
        return $this->hasMany(PackageItinerary::class, 'package_id')->orderBy('day_number');
    }

    public function departures(): HasMany
    {
        return $this->hasMany(PackageDeparture::class, 'package_id');
    }

    public function bookings(): HasMany
    {
        return $this->hasMany(Booking::class, 'package_id');
    }

    public function wishlists(): HasMany
    {
        return $this->hasMany(Wishlist::class, 'package_id');
    }
}
