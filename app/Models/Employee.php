<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Employee extends Model
{
    protected $fillable = [
        'user_id',
        'branch_id',
        'department_id',
        'employee_code',
        'designation',
        'sales_target',
        'commission_rate',
        'joining_date',
        'is_active',
    ];

    protected $casts = [
        'sales_target' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'joining_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function branch(): BelongsTo
    {
        return $this->belongsTo(Branch::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }
}
