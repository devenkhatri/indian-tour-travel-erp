<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Blog extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'image',
        'summary',
        'content',
        'author',
        'published_at',
        'is_published',
        'seo_title',
        'meta_description',
    ];

    protected $casts = [
        'published_at' => 'date',
        'is_published' => 'boolean',
    ];

    public function getFeaturedImageAttribute(): ?string
    {
        return $this->image;
    }
}
