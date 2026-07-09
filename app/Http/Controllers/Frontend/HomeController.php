<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Post;
use App\Models\SiteSetting;
use App\View\Helpers\ThemeHelper;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $settings = SiteSetting::getSettings();
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        $categories = Category::active()->withCount('companies')->orderByDesc('companies_count')->take(12)->get();
        $cities = City::withCount('companies')->orderByDesc('companies_count')->take(12)->get();
        $premiumCompanies = Company::active()->premium()->with(['category', 'city'])->latest()->take(6)->get();
        $latestCompanies = Company::active()->with(['category', 'city'])->latest()->take(9)->get();
        $postsQuery = Post::published()->with('directories');
        if ($directory) {
            $postsQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $postsQuery->latest('published_at')->take(3)->get();

        $layout = ThemeHelper::layoutFile($directory);
        $viewName = 'frontend.home.' . (view()->exists('frontend.home.' . $layout) ? $layout : 'default');

        return view($viewName, compact(
            'settings', 'categories', 'cities', 'premiumCompanies', 'latestCompanies', 'posts', 'directory'
        ));
    }
}
