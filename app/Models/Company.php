<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Company extends Model
{
    use BelongsToDirectory;

    protected static function booted(): void
    {
        static::deleting(function (self $company) {
            foreach ($company->images as $image) {
                if ($image->image_path && Storage::disk('public')->exists($image->image_path)) {
                    Storage::disk('public')->delete($image->image_path);
                }
            }
            if ($company->logo && Storage::disk('public')->exists($company->logo)) {
                Storage::disk('public')->delete($company->logo);
            }
            if ($company->cover_image && Storage::disk('public')->exists($company->cover_image)) {
                Storage::disk('public')->delete($company->cover_image);
            }
        });

        // Rename logo and cover to SEO-friendly names after save (only when changed)
        static::saved(function (self $company) {
            $slug = \Illuminate\Support\Str::slug($company->name);

            // Only rename if logo was actually uploaded/changed
            if ($company->wasChanged('logo') && $company->logo && Storage::disk('public')->exists($company->logo)) {
                $ext = pathinfo($company->logo, PATHINFO_EXTENSION);
                $newPath = 'companies/logos/' . $slug . '-logo.' . $ext;
                if ($company->logo !== $newPath) {
                    Storage::disk('public')->move($company->logo, $newPath);
                    $company->updateQuietly(['logo' => $newPath]);
                }
            }

            // Only rename if cover was actually uploaded/changed
            if ($company->wasChanged('cover_image') && $company->cover_image && Storage::disk('public')->exists($company->cover_image)) {
                $ext = pathinfo($company->cover_image, PATHINFO_EXTENSION);
                $newPath = 'companies/covers/' . $slug . '-cover.' . $ext;
                if ($company->cover_image !== $newPath) {
                    Storage::disk('public')->move($company->cover_image, $newPath);
                    $company->updateQuietly(['cover_image' => $newPath]);
                }
            }
        });
    }
    protected $fillable = [
        'name', 'slug', 'category_id', 'city_id', 'district_id',
        'phone', 'whatsapp', 'email', 'website', 'address', 'google_maps_url',
        'opening_hours', 'short_description', 'description', 'services', 'why_us_items', 'logo', 'cover_image',
        'is_premium', 'premium_until', 'status', 'view_count',
        'meta_title', 'meta_description', 'directory_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'premium_until' => 'datetime',
        'services' => 'array',
        'why_us_items' => 'array',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function images()
    {
        return $this->hasMany(CompanyImage::class)->orderBy('sort_order');
    }

    public function reviews()
    {
        return $this->hasMany(CompanyReview::class)->latest();
    }

    public function approvedReviews()
    {
        return $this->hasMany(CompanyReview::class)->approved()->latest();
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true)
            ->where(function ($q) {
                $q->whereNull('premium_until')
                  ->orWhere('premium_until', '>=', now());
            });
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }
}
