<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model
{
    protected $fillable = ['name', 'code', 'iso3', 'phone_code', 'currency', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function destinations(): HasMany
    {
        return $this->hasMany(Destination::class);
    }

    public function visaApplications(): HasMany
    {
        return $this->hasMany(VisaApplication::class);
    }
}
