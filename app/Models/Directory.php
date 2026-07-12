<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Directory extends Model
{
    protected $fillable = [
        'name', 'slug', 'domain', 'logo', 'favicon', 'template', 'theme',
        'slug_pattern', 'plan', 'status', 'expires_at',
        'meta_title', 'meta_description', 'page_contents', 'geography_mode',
        'primary_city_slug', 'featured_city_slugs', 'group_other_cities',
    ];

    protected $casts = [
        'theme' => 'array',
        'page_contents' => 'array',
        'featured_city_slugs' => 'array',
        'group_other_cities' => 'boolean',
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

    public function visibleCitySlugs(): array
    {
        return match ($this->geography_mode) {
            'local' => array_values(array_filter([$this->primary_city_slug])),
            'custom' => array_values(array_filter($this->featured_city_slugs ?? [])),
            default => [],
        };
    }
}
