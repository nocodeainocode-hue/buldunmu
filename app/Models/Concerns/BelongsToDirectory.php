<?php

namespace App\Models\Concerns;

use App\Models\Directory;
use Illuminate\Database\Eloquent\Builder;

trait BelongsToDirectory
{
    public static function bootBelongsToDirectory(): void
    {
        static::addGlobalScope('directory', function (Builder $builder) {
            $dir = app()->bound('currentDirectory') ? app('currentDirectory') : null;
            if ($dir) {
                $builder->where($builder->getModel()->getTable() . '.directory_id', $dir->id);
            }
        });
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}
