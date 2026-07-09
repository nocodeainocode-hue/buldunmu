<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\City;


class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $directory = $category->directory ?? (app()->bound('currentDirectory') ? app('currentDirectory') : null);

        $companies = Company::active()
            ->where('category_id', $category->id)
            ->with(['city', 'district'])
            ->orderByDesc('is_premium')
            ->orderByDesc('created_at')
            ->paginate(12);

        // Cities with companies in this category for SEO sidebar
        $popularCities = City::whereHas('companies', fn($q) => $q->where('category_id', $category->id))
            ->withCount(['companies' => fn($q) => $q->where('category_id', $category->id)])
            ->orderByDesc('companies_count')
            ->take(8)
            ->get();

        // Subcategories if any (for mega layouts)
        $totalInCategory = Company::active()->where('category_id', $category->id)->count();

        return view('frontend.categories.show', compact(
            'category', 'companies', 'directory', 'popularCities', 'totalInCategory'
        ));
    }
}
