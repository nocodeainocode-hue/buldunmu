<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use BelongsToDirectory;
    protected $fillable = [
        'name', 'slug', 'category_id', 'city_id', 'district_id',
        'phone', 'whatsapp', 'email', 'website', 'address',
        'short_description', 'description', 'logo', 'cover_image',
        'is_premium', 'premium_until', 'status',
        'meta_title', 'meta_description', 'directory_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'premium_until' => 'datetime',
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
}
