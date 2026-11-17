<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PackageCategory extends Model
{
    protected $fillable = ['name', 'slug', 'icon', 'image', 'description', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(TourPackage::class, 'category_id');
    }
}
