<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LeadFollowup extends Model
{
    protected $fillable = [
        'lead_id',
        'user_id',
        'followup_date',
        'status',
        'remarks',
        'reminder_at',
    ];

    protected $casts = [
        'followup_date' => 'datetime',
        'reminder_at' => 'datetime',
    ];

    public function lead(): BelongsTo
    {
        return $this->belongsTo(Lead::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
