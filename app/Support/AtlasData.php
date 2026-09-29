<?php

namespace App\Support;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;

class AtlasData
{
    public static function forCurrentDirectory(): array
    {
        $cities = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->withCount(['companies' => fn ($query) => $query->active()])
            ->get()
            ->keyBy('slug');

        $provinces = [];
        foreach (TurkeyCities::options() as $slug => $name) {
            $index = count($provinces);
            $city = $cities->get($slug);
            $provinces['TR-'.str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT)] = [
                'name' => $name,
                'slug' => $slug,
                'count' => $city?->companies_count ?? 0,
                'url' => $city ? route('cities.show', $slug) : null,
            ];
        }

        return [
            'provinces' => $provinces,
            'atlasCategories' => Category::active()->orderBy('name')->get(['id', 'name', 'slug']),
            'totalCompanies' => Company::active()->count(),
            'mappedCompanies' => Company::active()->whereNotNull('latitude')->whereNotNull('longitude')->count(),
        ];
    }
}
