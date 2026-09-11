<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Directory extends Model
{
    protected $fillable = [
        'name', 'slug', 'domain', 'logo', 'favicon', 'template', 'theme',
        'slug_pattern', 'plan', 'status', 'expires_at',
        'meta_title', 'meta_description', 'page_contents', 'geography_mode',
        'primary_city_slug', 'featured_city_slugs', 'group_other_cities',
        'blog_layout', 'editorial_voice',
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

    public function categorySettings()
    {
        return $this->hasMany(CategoryDirectorySetting::class);
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

    public static function normalizeDomain(?string $domain): ?string
    {
        if (blank($domain)) {
            return null;
        }

        $normalized = Str::lower(trim($domain));
        $normalized = preg_replace('#^https?://#', '', $normalized);
        $normalized = explode('/', $normalized, 2)[0];
        $normalized = preg_replace('/:\d+$/', '', $normalized);

        return preg_replace('/^www\./', '', rtrim($normalized, '.'));
    }

    public function setDomainAttribute(?string $value): void
    {
        $this->attributes['domain'] = static::normalizeDomain($value);
    }
}
