<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class JobPosting extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'company_id', 'directory_id', 'title', 'description', 'employment_type',
        'location', 'apply_email', 'apply_url', 'status', 'published_at',
        'expires_at', 'admin_published',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'expires_at' => 'datetime',
        'admin_published' => 'boolean',
    ];

    protected static function booted(): void
    {
        static::created(function (self $job): void {
            $titleSlug = Str::slug($job->title, '-', 'tr') ?: 'is-ilani';
            $job->forceFill(['slug' => $titleSlug.'-'.$job->id])->saveQuietly();
        });
    }

    public function allowsSharedDirectoryRecords(): bool
    {
        return false;
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function isPubliclyVisible(): bool
    {
        return $this->status === 'published'
            && $this->published_at?->lte(now())
            && ($this->expires_at === null || $this->expires_at->isFuture())
            && $this->company?->status === 'active'
            && ($this->admin_published || $this->company->hasActivePremium());
    }

    public function scopeVisible(Builder $query): Builder
    {
        return $query
            ->where('status', 'published')
            ->where('published_at', '<=', now())
            ->where(fn (Builder $dates) => $dates->whereNull('expires_at')->orWhere('expires_at', '>', now()))
            ->whereHas('company', fn (Builder $companies) => $companies->active())
            ->where(fn (Builder $eligibility) => $eligibility
                ->where('admin_published', true)
                ->orWhereHas('company', fn (Builder $companies) => $companies->premium()));
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
