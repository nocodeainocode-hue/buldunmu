<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\JobPosting;
use App\Models\Post;
use App\Support\ModelCache;

class SitemapController extends Controller
{
    public function index()
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        // Firma, yazı ve rehber değişince 'dir.{id}' kapsamı yenilenir; sitemap de onunla birlikte tazelenir.
        $xml = ModelCache::remember('dir.'.($directory?->id ?? 'global'), 'sitemap.'.request()->getHost(), 900, fn () => $this->render($directory));

        return response($xml)->header('Content-Type', 'application/xml; charset=UTF-8');
    }

    private function render($directory): string
    {
        $indexable = fn ($q) => $q->searchIndexable();

        $companies = Company::searchIndexable()
            ->when($directory, fn ($q) => $q->where('directory_id', $directory->id))
            ->get(['id', 'slug', 'updated_at']);

        $categories = Category::active()
            ->whereHas('companies', $indexable)
            ->withMax(['companies as companies_updated_at' => $indexable], 'updated_at')
            ->get();

        // Bölgesel rehberde yalnızca açılabilen şehirler listelenir; diğerleri 404 verirdi.
        $localMode = $directory && in_array($directory->geography_mode, ['local', 'custom'], true);
        $cities = City::query()
            ->whereHas('companies', $indexable)
            ->when($localMode, fn ($q) => $q->whereIn('slug', $directory->visibleCitySlugs()))
            ->withMax(['companies as companies_updated_at' => $indexable], 'updated_at')
            ->get();
        $otherCities = $localMode && $directory->group_other_cities
            && City::query()->whereNotIn('slug', $directory->visibleCitySlugs())->whereHas('companies', $indexable)->exists();

        $posts = Post::publishedForDirectory($directory)
            ->where('is_indexable', true)
            ->whereNull('canonical_url')
            ->latest('published_at')
            ->get();

        $jobs = JobPosting::visible()
            ->when($directory, fn ($query) => $query->where('directory_id', $directory->id))
            ->latest('published_at')
            ->get();

        $lastChange = collect([$companies->max('updated_at'), $posts->max('updated_at')])->filter()->max();

        return view('frontend.sitemap', compact('companies', 'categories', 'cities', 'otherCities', 'posts', 'jobs', 'lastChange'))->render();
    }
}
