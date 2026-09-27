<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->input('q', ''));
        $query = Company::active()
            ->with(['category', 'city', 'district']);

        if ($q !== '') {
            $query->where(function ($query) use ($q) {
                $query->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhereHas('category', fn ($category) => $category->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('city', fn ($city) => $city->where('name', 'like', "%{$q}%"));
            });
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn ($category) => $category->where('slug', $request->input('category')));
        }

        if ($request->filled('city')) {
            $query->whereHas('city', fn ($city) => $city->where('slug', $request->input('city')));
        }

        $mapCompanies = (clone $query)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->orderByDesc('is_premium')
            ->orderByDesc('created_at')
            ->take(80)
            ->get();

        $companies = $query
            ->orderByDesc('is_premium')
            ->orderByDesc('created_at')
            ->paginate(12)
            ->withQueryString();

        return view('frontend.companies.index', [
            'directory' => app()->bound('currentDirectory') ? app('currentDirectory') : null,
            'companies' => $companies,
            'mapCompanies' => $mapCompanies,
            'categories' => Category::active()->orderBy('name')->get(),
            'cities' => City::orderBy('name')->get(),
            'metaTitle' => $q !== '' ? "Arama: {$q}" : 'Firmalar',
        ]);
    }
}
