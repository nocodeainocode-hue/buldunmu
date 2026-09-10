<?php

namespace App\Models;

use App\Models\Concerns\BelongsToDirectory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MembershipPlan extends Model
{
    use BelongsToDirectory;

    protected $fillable = [
        'directory_id', 'name', 'slug', 'price', 'currency',
        'billing_period', 'features', 'is_active', 'sort_order',
    ];

    protected $casts = [
        'features' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
        'sort_order' => 'integer',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public static function forDirectoryWithFallback(?Directory $directory): Builder
    {
        $query = static::withoutGlobalScope('directory');

        if (! $directory) {
            return $query->whereNull('directory_id');
        }

        $hasDirectoryPlans = (clone $query)
            ->where('directory_id', $directory->id)
            ->active()
            ->exists();

        return $query->where(
            'directory_id',
            $hasDirectoryPlans ? $directory->id : null
        );
    }
}
