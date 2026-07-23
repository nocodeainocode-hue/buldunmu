<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Company;
use App\Models\City;
use App\Models\Post;


class CategoryController extends Controller
{
    public function show(string $slug)
    {
        $category = Category::active()->where('slug', $slug)->firstOrFail();
        $directory = $category->directory ?? (app()->bound('currentDirectory') ? app('currentDirectory') : null);

        $companies = Company::active()
            ->where('category_id', $category->id)
            ->with(['city', 'district'])
            ->orderByDesc('is_premium')
            ->orderByDesc('created_at')
            ->paginate(12);

        // Cities with active companies in this category for SEO sidebar
        $popularCities = City::whereHas('companies', fn($q) =>
            $q->active()->where('category_id', $category->id)
        )
            ->withCount(['companies' => fn($q) =>
                $q->active()->where('category_id', $category->id)
            ])
            ->orderByDesc('companies_count')
            ->take(10)
            ->get();

        // Related categories in the same directory
        $relatedCategories = Category::active()
            ->where('id', '!=', $category->id)
            ->withCount(['companies' => fn($q) => $q->active()])
            ->inRandomOrder()
            ->take(6)
            ->get();

        // SEO content: natural 200-300 word text
        $seoContent = $this->buildSeoContent($category, $popularCities);

        // Blog posts from this directory
        $postsQuery = Post::published()->with('directories');
        if ($directory) {
            $postsQuery->whereHas('directories', fn($q) => $q->where('directory_id', $directory->id));
        }
        $posts = $postsQuery->latest('published_at')->take(3)->get();

        // Total companies in category
        $totalInCategory = Company::active()->where('category_id', $category->id)->count();

        return view('frontend.categories.show', compact(
            'category', 'companies', 'directory', 'popularCities',
            'relatedCategories', 'seoContent', 'posts', 'totalInCategory'
        ));
    }

    /**
     * Build natural SEO content text for the category page.
     */
    private function buildSeoContent(Category $category, $popularCities): string
    {
        $cityNames = $popularCities->pluck('name')->filter()->take(5);
        $cityList = '';
        if ($cityNames->count() === 1) {
            $cityList = $cityNames->first();
        } elseif ($cityNames->count() > 1) {
            $last = $cityNames->pop();
            $cityList = $cityNames->implode(', ') . ' ve ' . $last;
        }

        $lines = [
            "{$category->name} kategorisi, Türkiye genelinde en çok aranan ve ihtiyaç duyulan hizmet sektörlerinden biridir. " .
            "Firma rehberimizde {$category->name} alanında faaliyet gösteren yüzlerce işletmenin güncel iletişim bilgilerini, " .
            "adreslerini, çalışma saatlerini ve müşteri yorumlarını bir arada bulabilirsiniz.",
        ];

        if ($cityList) {
            $lines[] = "{$category->name} hizmeti veren firmalar ağırlıklı olarak {$cityList} gibi büyük şehirlerde " .
                "yoğunlaşmakla birlikte, Türkiye'nin her ilinde {$category->name} sektöründe kaliteli işletmelere ulaşmanız mümkündür.";
        }

        $lines[] = "{$category->name} firması seçerken dikkat etmeniz gereken en önemli kriterlerden biri, işletmenin " .
            "müşteri memnuniyeti ve referanslarıdır. Rehberimizde yer alan kullanıcı yorumları ve puanlamalar sayesinde, " .
            "size en uygun {$category->name} firmasını kolayca seçebilir, doğrudan telefon veya WhatsApp üzerinden iletişime geçebilirsiniz.";

        $lines[] = "Firma rehberimiz {$category->name} kategorisinde düzenli olarak güncellenmekte olup, yeni eklenen " .
            "firmalar, kampanyalar ve fırsatlardan anında haberdar olabilirsiniz. Aradığınız {$category->name} hizmetini " .
            "en yakın lokasyonda, en uygun koşullarda bulmak için şehir ve ilçe filtrelerini kullanarak aramanızı daraltabilirsiniz.";

        if ($cityList) {
            $lines[] = "Özellikle {$cityList} gibi şehirlerde {$category->name} arayışında olan kullanıcılar için, " .
                "her şehre özel sayfalarımız üzerinden o bölgedeki en iyi {$category->name} firmalarına ulaşabilir, " .
                "detaylı firma profillerini inceleyerek karşılaştırma yapabilirsiniz. Rehberimiz tamamen ücretsiz olup, " .
                "herhangi bir üyelik gerektirmeden tüm firma bilgilerine erişim sağlayabilirsiniz.";
        }

        return implode("\n\n", $lines);
    }
}
