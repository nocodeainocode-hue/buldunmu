<?php

namespace App\Observers;

use App\Models\Directory;
use App\Models\SiteSetting;

class DirectoryObserver
{
    public function created(Directory $directory): void
    {
        SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
            ['directory_id' => $directory->id],
            [
                'directory_id' => $directory->id,
                'site_name' => $directory->name,
                'homepage_title' => $directory->name.' - Aradiginiz Firmayi Bulun',
            ],
        );
    }

    public function updated(Directory $directory): void
    {
        if (! $directory->wasChanged('name')) {
            return;
        }

        SiteSetting::withoutGlobalScope('directory')
            ->where('directory_id', $directory->id)
            ->update(['site_name' => $directory->name]);
    }
}
