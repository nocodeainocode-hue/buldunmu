<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Category;
use App\Models\City;


class SitemapController extends Controller
{
    public function index()
    {
        $companies = Company::active()->get();
        $categories = Category::active()->get();
        $cities = City::all();

        return response()->view('frontend.sitemap', compact('companies', 'categories', 'cities'))
            ->header('Content-Type', 'text/xml');
    }
}
