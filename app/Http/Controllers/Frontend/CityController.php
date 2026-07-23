<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Company;
use App\Models\Category;
use App\Models\District;
use App\Models\Post;
use Illuminate\Http\Request;


class CityController extends Controller
{
    public function other(Request $request)
    {
        $directory = app()->bound('currentDirectory') ? app('currentDirectory') : null;
        abort_unless($directory && $directory->geography_mode !== 'national' && $directory->group_other_cities, 404);

        $visibleSlugs = $directory->visibleCitySlugs();
        $outsideVisibleCities = fn($query) => $query->whereHas(
            'city',
            fn($city) => $city->whereNotIn('slug', $visibleSlugs)
        );

        $query = Company::active()
            ->where($outsideVisibleCities)
            ->with(['category', 'city', 'district']);

        if ($request->filled('category')) {
            $query->whereHas('category', fn($category) => $category->where('slug', $request->category));
        }

        $companies = $query->orderByDesc('is_premium')->orderByDesc('created_at')->paginate(12);
        $popularCategories = Category::active()
            ->whereHas('companies', $outsideVisibleCities)
            ->withCount(['companies' => $outsideVisibleCities])
            ->orderByDesc('companies_count')
            ->take(10)
            ->get();

        $city = new City(['name' => 'Diğer İller', 'slug' => 'diger-iller']);
        $districts = collect();
        $totalInCity = $companies->total();
        $isOtherCities = true;

        // Nearby cities (random 5 — all real cities since this is the synthetic "Diğer İller" page)
        $nearbyCities = City::inRandomOrder()->take(5)->get();

        // SEO content
        $seoContent = 'Türkiye\'nin farklı illerinde faaliyet gösteren firmaları bir arada bulabileceğiniz bu sayfada, ' .
            'büyük şehirler dışındaki işletmelere kolayca erişebilirsiniz. Kategori filtrelerini kullanarak ' .
            'aradığınız hizmeti en hızlı şekilde bulabilir, firma profillerini inceleyerek karar verebilirsiniz.';

        // Blog posts
        $postsQuery = Post::published()->with('directories');
        if ($directory) {
            $postsQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $postsQuery->latest('published_at')->take(3)->get();

        return view('frontend.cities.show', compact(
            'city', 'companies', 'districts', 'directory', 'popularCategories',
            'totalInCity', 'isOtherCities', 'nearbyCities', 'seoContent', 'posts'
        ));
    }

    public function show(string $slug, Request $request)
    {
        $city = City::withoutGlobalScope('directory')
            ->whereNull('directory_id')
            ->where('slug', $slug)
            ->when(app()->bound('currentDirectory') && app('currentDirectory')->geography_mode !== 'national', function ($query) {
                $directory = app('currentDirectory');
                $visibleSlugs = $directory->visibleCitySlugs();
                return $query->whereIn('slug', $visibleSlugs);
            })
            ->firstOrFail();
        $directory = $city->directory ?? (app()->bound('currentDirectory') ? app('currentDirectory') : null);
        $districts = District::where('city_id', $city->id)->orderBy('name')->get();

        $query = Company::active()->where('city_id', $city->id)->with(['category', 'district']);

        if ($request->filled('district')) {
            $query->whereHas('district', fn($d) => $d->where('slug', $request->district));
        }

        if ($request->filled('category')) {
            $query->whereHas('category', fn($c) => $c->where('slug', $request->category));
        }

        $companies = $query->orderByDesc('is_premium')->orderByDesc('created_at')->paginate(12);

        // Popular categories in this city for SEO sidebar
        $popularCategories = Category::active()
            ->whereHas('companies', fn($q) => $q->active()->where('city_id', $city->id))
            ->withCount(['companies' => fn($q) => $q->active()->where('city_id', $city->id)])
            ->orderByDesc('companies_count')
            ->take(10)
            ->get();

        // Nearby cities (5 random other cities)
        $nearbyCities = City::where('id', '!=', $city->id)
            ->withCount(['companies' => fn($q) => $q->active()])
            ->inRandomOrder()
            ->take(5)
            ->get();

        // SEO content: natural text
        $seoContent = $this->buildSeoContent($city, $popularCategories);

        // Blog posts from this directory
        $postsQuery = Post::published()->with('directories');
        if ($directory) {
            $postsQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $postsQuery->latest('published_at')->take(3)->get();

        $totalInCity = Company::active()->where('city_id', $city->id)->count();

        return view('frontend.cities.show', compact(
            'city', 'companies', 'districts', 'directory', 'popularCategories',
            'nearbyCities', 'seoContent', 'posts', 'totalInCity'
        ));
    }

    /**
     * Build natural SEO content text for the city page.
     */
    private function buildSeoContent(City $city, $popularCategories): string
    {
        $categoryNames = $popularCategories->pluck('name')->filter()->take(5);
        $categoryList = '';
        if ($categoryNames->count() === 1) {
            $categoryList = $categoryNames->first();
        } elseif ($categoryNames->count() > 1) {
            $last = $categoryNames->pop();
            $categoryList = $categoryNames->implode(', ') . ' ve ' . $last;
        }

        $lines = [
            "{$city->name} firmaları rehberi, {$city->name} ilinde faaliyet gösteren tüm işletmelere " .
            "tek bir platform üzerinden ulaşmanızı sağlar. {$city->name} il sınırları içerisinde hizmet veren " .
            "firmaların güncel telefon numaraları, WhatsApp iletişim bilgileri, açık adresleri, web siteleri " .
            "ve müşteri yorumlarını detaylı firma profilleri üzerinden inceleyebilirsiniz.",
        ];

        if ($categoryList) {
            $lines[] = "{$city->name} ilinde en çok aranan kategoriler arasında {$categoryList} gibi " .
                "sektörler öne çıkmaktadır. {$city->name} genelinde bu kategorilerde hizmet veren onlarca " .
                "firmanın iletişim bilgilerine sayfamızdan hızlıca erişebilirsiniz.";
        }

        $lines[] = "{$city->name} firma rehberimizde aradığınız işletmeyi kolayca bulabilmeniz için " .
            "kategori ve ilçe bazlı filtreleme seçenekleri sunulmaktadır. {$city->name} merkez başta olmak üzere " .
            "tüm ilçelerdeki firmaları listeleyebilir, kullanıcı puanlamalarına göre en iyi işletmeleri " .
            "karşılaştırabilirsiniz.";

        $lines[] = "Rehberimiz {$city->name} işletmeleri için tamamen ücretsiz bir hizmet sunmakta olup, " .
            "herhangi bir üyelik gerektirmeden tüm firma profillerini görüntüleyebilir, dilediğiniz firmayla " .
            "doğrudan iletişime geçebilirsiniz. {$city->name} ilinde hizmet veren firmanızı rehberimize " .
            "ücretsiz eklemek için firma ekleme sayfamızı ziyaret edebilirsiniz.";

        if ($categoryList) {
            $lines[] = "{$city->name} şehrinde {$categoryList} alanlarında uzmanlaşmış, " .
                "müşteri memnuniyeti yüksek firmaları bir arada görmek için ilgili kategori sayfalarımızı " .
                "ziyaret edebilir, size en yakın ve en uygun işletmeyi seçebilirsiniz.";
        }

        return implode("\n\n", $lines);
    }
}
