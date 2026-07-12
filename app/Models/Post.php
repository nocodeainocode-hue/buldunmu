<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'title', 'slug', 'content_type', 'primary_query', 'search_intent',
        'target_city_slug', 'target_category_slug', 'excerpt', 'content', 'image',
        'author_name', 'reviewer_name', 'sources', 'faq_items', 'pros', 'cons',
        'is_indexable', 'canonical_url', 'editorial_notes', 'status', 'published_at', 'directory_id',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'sources' => 'array',
        'faq_items' => 'array',
        'pros' => 'array',
        'cons' => 'array',
        'is_indexable' => 'boolean',
    ];

    public function directories()
    {
        return $this->belongsToMany(Directory::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }

    public function robotsDirective(): string
    {
        return $this->is_indexable
            ? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1'
            : 'noindex,follow,max-image-preview:large';
    }
}
