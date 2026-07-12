<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Category;
use App\Models\City;
use App\Models\Post;

class SitemapController extends Controller
{
    public function index()
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;

        $companies = Company::active()
            ->when($directory, fn($q) => $q->where('directory_id', $directory->id))
            ->get();

        $categories = Category::active()
            ->when($directory, fn($q) => $q->where('directory_id', $directory->id))
            ->get();

        $cities = City::when($directory, fn($q) => $q->where('directory_id', $directory->id))
            ->whereHas('companies', fn($q) => $q->active())
            ->get();

        $posts = Post::published()
            ->where('is_indexable', true)
            ->whereNull('canonical_url')
            ->when($directory, fn($q) => $q->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id)))
            ->latest('published_at')
            ->get();

        return response()->view('frontend.sitemap', compact('companies', 'categories', 'cities', 'posts'))
            ->header('Content-Type', 'text/xml');
    }
}
