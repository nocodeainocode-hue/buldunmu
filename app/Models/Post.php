<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = ['title', 'slug', 'excerpt', 'content', 'image', 'status', 'published_at', 'directory_id'];

    protected $casts = ['published_at' => 'datetime'];

    public function directories()
    {
        return $this->belongsToMany(Directory::class);
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published')->where('published_at', '<=', now());
    }
}