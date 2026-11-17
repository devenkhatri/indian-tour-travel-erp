<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code',
        'description',
        'discount_type',
        'discount_value',
        'min_spend',
        'max_discount',
        'valid_from',
        'valid_to',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'min_spend' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'valid_from' => 'date',
        'valid_to' => 'date',
        'is_active' => 'boolean',
    ];

    public function isValid(?float $orderAmount = null): bool
    {
        if (! $this->is_active) {
            return false;
        }

        $today = now()->toDateString();
        if ($this->valid_from > $today || $this->valid_to < $today) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        if ($orderAmount !== null && $this->min_spend > $orderAmount) {
            return false;
        }

        return true;
    }

    public function calculateDiscount(float $amount): float
    {
        if ($this->discount_type === 'percentage') {
            $discount = ($amount * (float) $this->discount_value) / 100;
            if ($this->max_discount && $discount > (float) $this->max_discount) {
                return (float) $this->max_discount;
            }

            return $discount;
        }

        return min((float) $this->discount_value, $amount);
    }
}
