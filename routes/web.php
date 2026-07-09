<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\CompanyController;
use App\Http\Controllers\Frontend\CompanyReviewController;
use App\Http\Controllers\Frontend\CategoryController;
use App\Http\Controllers\Frontend\CityController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\ListingRequestController;
use App\Http\Controllers\Frontend\BlogController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

// Ana sayfa
Route::get('/', [HomeController::class, 'index'])->name('home');

// Firma listeleme
Route::get('/firmalar', [CompanyController::class, 'index'])->name('companies.index');

// Firma detay
Route::get('/firma/{slug}', [CompanyController::class, 'show'])->name('companies.show');
Route::post('/firma/{company:slug}/yorum', [CompanyReviewController::class, 'store'])->name('companies.reviews.store');

// Kategori detay
Route::get('/kategori/{slug}', [CategoryController::class, 'show'])->name('categories.show');

// Şehir detay
Route::get('/sehir/{slug}', [CityController::class, 'show'])->name('cities.show');

// Firma ekleme talebi
Route::get('/firma-ekle', [ListingRequestController::class, 'create'])->name('listing.create');
Route::post('/firma-ekle', [ListingRequestController::class, 'store'])->name('listing.store');

// İletişim
Route::post('/iletisim', [ContactController::class, 'store'])->name('contact.store');

// Arama
Route::get('/ara', [SearchController::class, 'index'])->name('search');

// Statik sayfalar
Route::get('/hakkimizda', [PageController::class, 'about'])->name('pages.about');
Route::get('/iletisim', [PageController::class, 'contact'])->name('pages.contact');
Route::get('/gizlilik-politikasi', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/kullanim-sartlari', [PageController::class, 'terms'])->name('pages.terms');

// SEO
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');
Route::get('/robots.txt', function () {
    $domain = request()->getHost();
    $sitemapUrl = url('/sitemap.xml');

    $lines = [
        'User-agent: *',
        'Allow: /',
        '',
        'Disallow: /admin/',
        'Disallow: /livewire/',
        '',
        '# Sitemap',
        'Sitemap: ' . $sitemapUrl,
    ];

    return response(implode("\n", $lines))
        ->header('Content-Type', 'text/plain');
})->name('robots');

// Blog
Route::get('/blog', [BlogController::class, 'index'])->name('blog.index');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Tenant switch
Route::post('/admin/tenant/switch', function () {
    $id = request('directory_id');
    if ($id) {
        session(['current_directory_id' => (int) $id]);
    } else {
        session()->forget('current_directory_id');
    }
    session()->save();
    return redirect()->back();
})->name('filament.admin.tenant.switch')->middleware('web');

// Campaign CSV export
Route::get('/admin/campaigns/{campaign}/export-csv', function (App\Models\Campaign $campaign) {
    return App\Services\CampaignReportService::exportCsv($campaign);
})->name('filament.admin.campaigns.export-csv')->middleware(['web', 'auth']);
