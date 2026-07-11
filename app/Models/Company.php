<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Company extends Model
{
    use BelongsToDirectory;

    protected static function booted(): void
    {
        static::saving(function (self $company) {
            if ($company->google_maps_url && (blank($company->latitude) || blank($company->longitude) || $company->isDirty('google_maps_url'))) {
                $coordinates = self::coordinatesFromGoogleMapsInput($company->google_maps_url);

                if ($coordinates) {
                    $company->latitude = $coordinates['latitude'];
                    $company->longitude = $coordinates['longitude'];
                }
            }
        });

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
            $slug = Str::slug($company->name);

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
        'latitude', 'longitude', 'opening_hours', 'short_description', 'description', 'services', 'why_us_items', 'external_links', 'logo', 'cover_image',
        'is_premium', 'premium_until', 'status', 'view_count',
        'meta_title', 'meta_description', 'directory_id',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'premium_until' => 'datetime',
        'services' => 'array',
        'why_us_items' => 'array',
        'external_links' => 'array',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public static function coordinatesFromGoogleMapsInput(?string $input): ?array
    {
        if (blank($input)) {
            return null;
        }

        $value = html_entity_decode($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $value, $srcMatch)) {
            $value = html_entity_decode($srcMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        $decoded = urldecode($value);

        if (preg_match('/!2d(-?\d+(?:\.\d+)?).*?!3d(-?\d+(?:\.\d+)?)/', $decoded, $matches)) {
            return self::validatedCoordinates((float) $matches[2], (float) $matches[1]);
        }

        if (preg_match('/[?&](?:q|query)=(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/', $decoded, $matches)) {
            return self::validatedCoordinates((float) $matches[1], (float) $matches[2]);
        }

        if (preg_match('/@(-?\d+(?:\.\d+)?),\s*(-?\d+(?:\.\d+)?)/', $decoded, $matches)) {
            return self::validatedCoordinates((float) $matches[1], (float) $matches[2]);
        }

        return null;
    }

    public function googleMapsEmbedSrc(): ?string
    {
        if (blank($this->google_maps_url)) {
            return null;
        }

        $value = html_entity_decode($this->google_maps_url, ENT_QUOTES | ENT_HTML5, 'UTF-8');

        if (preg_match('/\bsrc=["\']([^"\']+)["\']/i', $value, $srcMatch)) {
            return html_entity_decode($srcMatch[1], ENT_QUOTES | ENT_HTML5, 'UTF-8');
        }

        return $value;
    }

    protected static function validatedCoordinates(float $latitude, float $longitude): ?array
    {
        if ($latitude < -90 || $latitude > 90 || $longitude < -180 || $longitude > 180) {
            return null;
        }

        return [
            'latitude' => round($latitude, 7),
            'longitude' => round($longitude, 7),
        ];
    }

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
