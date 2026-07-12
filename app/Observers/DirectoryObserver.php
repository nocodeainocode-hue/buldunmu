<?php

namespace App\Observers;

use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\SiteSetting;

class DirectoryObserver
{
    public function created(Directory $directory): void
    {
        // Copy categories from default directory (skip if slug already exists)
        $defaultCategories = Category::where('directory_id', 1)->get();
        foreach ($defaultCategories as $cat) {
            Category::firstOrCreate(
                ['slug' => $cat->slug, 'directory_id' => $directory->id],
                $cat->replicate()->fill(['directory_id' => $directory->id])->toArray()
            );
        }

        // Copy cities from default directory (skip if slug already exists)
        $defaultCities = City::where('directory_id', 1)->get();
        $visibleCitySlugs = $directory->visibleCitySlugs();
        foreach ($defaultCities as $city) {
            if ($directory->geography_mode !== 'national' && !in_array($city->slug, $visibleCitySlugs, true)) {
                continue;
            }
            City::firstOrCreate(
                ['slug' => $city->slug, 'directory_id' => $directory->id],
                $city->replicate()->fill(['directory_id' => $directory->id])->toArray()
            );
        }

        // Copy site settings (skip if already exists)
        $defaultSettings = SiteSetting::where('directory_id', 1)->first();
        if ($defaultSettings && !SiteSetting::where('directory_id', $directory->id)->exists()) {
            SiteSetting::create($defaultSettings->replicate()->fill([
                'directory_id' => $directory->id,
                'site_name' => $directory->name,
                'homepage_title' => $directory->name . ' - Aradiginiz Firmayi Bulun',
            ])->toArray());
        }
    }
}
