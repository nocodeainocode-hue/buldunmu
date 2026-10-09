<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class AdCampaign extends Model
{
    public const PLACEMENTS = [
        'top' => 'Sayfa üstü (ince şerit)',
        'bottom' => 'Sayfa altı (büyük kart)',
    ];

    protected $fillable = [
        'name', 'type', 'status', 'placements', 'headline', 'body', 'cta_label', 'link_url', 'image_path',
        'bg_color', 'text_color', 'accent_color', 'directory_ids', 'city_ids', 'category_ids',
        'weight', 'starts_at', 'ends_at',
    ];

    protected $casts = [
        'placements' => 'array',
        'directory_ids' => 'array',
        'city_ids' => 'array',
        'category_ids' => 'array',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'weight' => 'integer',
    ];

    protected static function booted(): void
    {
        $flush = fn () => Cache::forget('ads.active');

        static::saved($flush);
        static::deleted($flush);
    }

    public function dailyStats()
    {
        return $this->hasMany(AdDailyStat::class);
    }

    public function isLive(): bool
    {
        return $this->status === 'active'
            && ($this->starts_at === null || $this->starts_at->lte(now()))
            && ($this->ends_at === null || $this->ends_at->gte(now()));
    }

    public function ctr(): float
    {
        return $this->impressions_total > 0 ? round($this->clicks_total / $this->impressions_total * 100, 2) : 0.0;
    }
}
