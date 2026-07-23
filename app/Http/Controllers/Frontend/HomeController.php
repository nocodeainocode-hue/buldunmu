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
        $cities = $this->navigationCities($directory);
        $companyIncludes = ['category', 'city', 'district'];
        $premiumCompanies = Company::active()->premium()->with($companyIncludes)
            ->withCount('approvedReviews')->withAvg(['approvedReviews as reviews_avg_rating'], 'rating')
            ->latest()->take(6)->get();
        $latestCompanies = Company::active()->with($companyIncludes)
            ->withCount('approvedReviews')->withAvg(['approvedReviews as reviews_avg_rating'], 'rating')
            ->latest()->take(9)->get();
        $openCompanies = Company::active()->whereNotNull('opening_hours')->with($companyIncludes)
            ->withCount('approvedReviews')->withAvg(['approvedReviews as reviews_avg_rating'], 'rating')
            ->latest()->take(40)->get()->filter(fn(Company $company) => $company->isOpenNow() === true)->take(8);
        $trustedCompanies = Company::active()->where('is_verified', true)->with($companyIncludes)
            ->withCount('approvedReviews')->withAvg(['approvedReviews as reviews_avg_rating'], 'rating')
            ->orderByDesc('reviews_avg_rating')->latest()->take(8)->get();
        $mapCompanies = Company::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->with(['category', 'city', 'district'])
            ->latest()
            ->take(60)
            ->get();
        $postsQuery = Post::published()->with('directories');
        if ($directory) {
            $postsQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $postsQuery->latest('published_at')->take(3)->get();

        $layout = ThemeHelper::layoutFile($directory);
        $viewName = 'frontend.home.' . (view()->exists('frontend.home.' . $layout) ? $layout : 'default');

        return view($viewName, compact(
            'settings', 'categories', 'cities', 'premiumCompanies', 'latestCompanies', 'openCompanies', 'trustedCompanies',
            'mapCompanies', 'posts', 'directory'
        ));
    }

    private function navigationCities($directory)
    {
        if (!$directory || $directory->geography_mode === 'national') {
            return City::withoutGlobalScope('directory')
                ->whereNull('directory_id')
                ->whereHas('companies', fn($query) => $query->active())
                ->withCount(['companies' => fn($query) => $query->active()])
                ->orderByDesc('companies_count')
                ->take(12)
                ->get();
        }

        $visibleSlugs = $directory->visibleCitySlugs();
        $cities = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->whereIn('slug', $visibleSlugs)
            ->withCount('companies')
            ->get()
            ->sortBy(fn(City $city) => array_search($city->slug, $visibleSlugs, true))
            ->values();

        if ($directory->group_other_cities) {
            $other = new City(['name' => 'Diğer İller', 'slug' => 'diger-iller']);
            $other->setAttribute('companies_count', Company::active()
                ->whereHas('city', fn($query) => $query->whereNotIn('slug', $visibleSlugs))
                ->count());
            $cities->push($other);
        }

        return $cities;
    }
}
