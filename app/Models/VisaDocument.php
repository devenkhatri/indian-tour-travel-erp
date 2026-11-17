<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VisaDocument extends Model
{
    protected $fillable = [
        'visa_application_id',
        'document_name',
        'file_path',
        'is_verified',
        'remarks',
    ];

    protected $casts = [
        'is_verified' => 'boolean',
    ];

    public function application(): BelongsTo
    {
        return $this->belongsTo(VisaApplication::class, 'visa_application_id');
    }
}
