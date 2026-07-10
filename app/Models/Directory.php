<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $fillable = [
        'name', 'slug', 'domain', 'logo', 'favicon', 'template', 'theme',
        'slug_pattern', 'plan', 'status', 'expires_at',
        'meta_title', 'meta_description', 'page_contents',
    ];

    protected $casts = [
        'theme' => 'array',
        'page_contents' => 'array',
        'expires_at' => 'datetime',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function categories()
    {
        return $this->hasMany(Category::class);
    }

    public function cities()
    {
        return $this->hasMany(City::class);
    }
}
