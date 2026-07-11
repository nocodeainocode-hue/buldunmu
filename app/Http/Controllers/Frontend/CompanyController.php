<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Category;
use App\Models\City;
use App\Models\District;
use App\Models\Post;
use Illuminate\Http\Request;


class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $query = Company::active()->with(['category', 'city', 'district']);

        // Premium companies first
        $query->orderByDesc('is_premium')->orderByDesc('created_at');

        // Search
        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($qry) use ($q) {
                $qry->where('name', 'like', "%{$q}%")
                    ->orWhere('short_description', 'like', "%{$q}%")
                    ->orWhereHas('category', fn($c) => $c->where('name', 'like', "%{$q}%"))
                    ->orWhereHas('city', fn($c) => $c->where('name', 'like', "%{$q}%"));
            });
        }

        // Category filter
        if ($request->filled('category')) {
            $query->whereHas('category', fn($c) => $c->where('slug', $request->category));
        }

        // City filter
        if ($request->filled('city')) {
            $query->whereHas('city', fn($c) => $c->where('slug', $request->city));
        }

        // District filter
        if ($request->filled('district')) {
            $query->whereHas('district', fn($d) => $d->where('slug', $request->district));
        }

        $mapCompanies = (clone $query)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->take(80)
            ->get();
        $companies = $query->paginate(12)->withQueryString();
        $categories = Category::active()->orderBy('name')->get();
        $cities = City::orderBy('name')->get();
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $metaTitle = 'Firmalar';
        if ($request->filled('category')) {
            $cat = Category::where('slug', $request->category)->first();
            if ($cat) $metaTitle = $cat->name . ' Firmaları';
        }
        if ($request->filled('city')) {
            $ct = City::where('slug', $request->city)->first();
            if ($ct) $metaTitle .= ' - ' . $ct->name;
        }

        return view('frontend.companies.index', compact('companies', 'mapCompanies', 'categories', 'cities', 'metaTitle', 'directory'));
    }
    public function show(string $slug)
    {
        $company = Company::active()
            ->with(['category', 'city', 'district', 'images', 'approvedReviews'])
            ->withAvg(['approvedReviews as reviews_avg_rating'], 'rating')
            ->where('slug', $slug)
            ->firstOrFail();

        $company->incrementViewCount();

        $directory = $company->directory ?? (app()->bound('currentDirectory') ? app('currentDirectory') : null);
        $template = $directory->template ?? 'default';

        // Determine detail variant: seo_story for templates that benefit from rich content
        $storyTemplates = ['elegant', 'city-focused', 'category-mega', 'corporate', 'bold', 'premium-showcase', 'map-directory', 'local-map-landing', 'map-first'];
        $detailVariant = in_array($template, $storyTemplates) ? 'seo-story' : 'compact-local';

        $similarCompanies = Company::active()
            ->where('id', '!=', $company->id)
            ->where(function ($q) use ($company) {
                $q->where('category_id', $company->category_id)
                  ->orWhere('city_id', $company->city_id);
            })
            ->with(['category', 'city'])
            ->latest()
            ->take(6)
            ->get();

        $sameCategoryCompanies = Company::active()
            ->where('id', '!=', $company->id)
            ->where('category_id', $company->category_id)
            ->with(['category', 'city'])
            ->latest()
            ->take(4)
            ->get();

        // Nearby companies (same district or city)
        $nearbyCompanies = Company::active()
            ->where('id', '!=', $company->id)
            ->when($company->district_id, fn($q) => $q->where('district_id', $company->district_id))
            ->when(!$company->district_id, fn($q) => $q->where('city_id', $company->city_id))
            ->with(['category', 'city'])
            ->latest()
            ->take(4)
            ->get();

        // Related posts for SEO
        $relatedPosts = Post::published()
            ->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id ?? 0))
            ->latest('published_at')
            ->take(3)
            ->get();

        return view('frontend.companies.show', compact(
            'company', 'similarCompanies', 'sameCategoryCompanies',
            'nearbyCompanies', 'relatedPosts', 'directory', 'detailVariant'
        ));
    }
}
