<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'name', 'slug', 'description', 'icon',
        'meta_title', 'meta_description', 'status', 'directory_id',
    ];

    public function companies()
    {
        return $this->hasMany(Company::class);
    }

    public function directorySettings(): HasMany
    {
        return $this->hasMany(CategoryDirectorySetting::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        $query->where('status', 'active');

        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        return $directory ? $query->visibleForDirectory($directory) : $query;
    }

    public function scopeVisibleForDirectory(Builder $query, ?Directory $directory): Builder
    {
        if (!$directory) {
            return $query;
        }

        $directoryId = $directory->getKey();

        return $query
            ->where(function (Builder $visibility) use ($directoryId): void {
                $visibility
                    ->whereDoesntHave('directorySettings', fn (Builder $settings) => $settings->where('directory_id', $directoryId))
                    ->orWhereHas('directorySettings', fn (Builder $settings) => $settings
                        ->where('directory_id', $directoryId)
                        ->where('is_visible', true));
            })
            ->orderByRaw(
                'CASE WHEN (SELECT sort_order FROM category_directory_settings WHERE category_id = categories.id AND directory_id = ? LIMIT 1) IS NULL THEN 1 ELSE 0 END ASC',
                [$directoryId],
            )
            ->orderByRaw(
                '(SELECT sort_order FROM category_directory_settings WHERE category_id = categories.id AND directory_id = ? LIMIT 1) ASC',
                [$directoryId],
            );
    }
}
