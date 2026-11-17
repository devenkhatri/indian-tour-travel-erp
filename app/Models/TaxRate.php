<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TaxRate extends Model
{
    protected $fillable = [
        'name',
        'type',
        'rate_percent',
        'cgst_percent',
        'sgst_percent',
        'igst_percent',
        'hsn_sac_code',
        'is_active',
    ];

    protected $casts = [
        'rate_percent' => 'decimal:2',
        'cgst_percent' => 'decimal:2',
        'sgst_percent' => 'decimal:2',
        'igst_percent' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function invoices(): HasMany
    {
        return $this->hasMany(Invoice::class, 'tax_id');
    }
}
