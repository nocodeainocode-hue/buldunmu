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
                $model = $builder->getModel();
                $table = $model->getTable();

                if ($model->allowsSharedDirectoryRecords()) {
                    $builder->where(function ($q) use ($table, $dir) {
                        $q->whereNull("{$table}.directory_id")
                          ->orWhere("{$table}.directory_id", $dir->id);
                    });
                } else {
                    $builder->where("{$table}.directory_id", $dir->id);
                }
            }
        });
    }

    public function allowsSharedDirectoryRecords(): bool
    {
        return true;
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }
}
