<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class AtlasController extends Controller
{
    public function companies(Request $request): JsonResponse
    {
        $filters = $request->validate([
            'city' => ['nullable', 'string', 'max:100'],
            'category' => ['nullable', 'string', 'max:100'],
            'west' => ['nullable', 'numeric', 'between:-180,180'],
            'south' => ['nullable', 'numeric', 'between:-90,90'],
            'east' => ['nullable', 'numeric', 'between:-180,180'],
            'north' => ['nullable', 'numeric', 'between:-90,90'],
        ]);

        $query = Company::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['city:id,name,slug', 'category:id,name,slug']);

        if (! empty($filters['city'])) {
            $query->whereHas('city', fn ($city) => $city->where('slug', $filters['city']));
        }
        if (! empty($filters['category'])) {
            $query->whereHas('category', fn ($category) => $category->where('slug', $filters['category']));
        }
        if (isset($filters['west'], $filters['south'], $filters['east'], $filters['north'])) {
            $query->whereBetween('longitude', [$filters['west'], $filters['east']])
                ->whereBetween('latitude', [$filters['south'], $filters['north']]);
        }

        $total = (clone $query)->count();
        // Keep a pin from each represented province on the nationwide view.
        $firstIdsByCity = (clone $query)
            ->selectRaw('MIN(companies.id) as id')
            ->groupBy('companies.city_id')
            ->pluck('id');
        $columns = [
            'id', 'name', 'slug', 'city_id', 'category_id', 'latitude', 'longitude', 'is_premium',
        ];
        $firstCompanies = (clone $query)->whereIn('companies.id', $firstIdsByCity)->get($columns);
        $moreCompanies = (clone $query)->whereNotIn('companies.id', $firstIdsByCity)
            ->orderByDesc('is_premium')->orderBy('id')
            ->limit(max(0, 500 - $firstCompanies->count()))
            ->get($columns);
        $companies = $firstCompanies->concat($moreCompanies)->map(fn (Company $company) => [
            'name' => $company->name,
            'category' => $company->category?->name,
            'city' => $company->city?->name,
            'lat' => (float) $company->latitude,
            'lng' => (float) $company->longitude,
            'premium' => (bool) $company->is_premium,
            'url' => route('companies.show', $company->slug),
        ]);

        return response()->json(['total' => $total, 'companies' => $companies]);
    }
}
