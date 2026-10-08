<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\JobPosting;
use App\Models\MembershipPlan;
use App\Models\Post;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DesignCatalogThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_theme_is_registered_and_selectable(): void
    {
        $this->assertSame('Tasarım Kataloğu', ThemeHelper::templateSelectOptions()['design-catalog']);
        $this->assertSame('design-catalog', ThemeHelper::TEMPLATES['design-catalog']['layout']);
        $this->assertFileExists(resource_path('views/frontend/home/design-catalog.blade.php'));
        $this->assertFileExists(resource_path('views/frontend/companies/themes/design-catalog.blade.php'));

        foreach (['blog', 'category', 'city', 'companies', 'company-list', 'contact', 'info', 'job', 'jobs', 'packages', 'post'] as $page) {
            $this->assertFileExists(resource_path('views/frontend/catalog/'.$page.'.blade.php'));
        }
    }

    public function test_every_public_page_renders_in_the_catalog_family(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Katalog Rehberi', 'slug' => 'katalog-rehberi', 'domain' => $host,
            'status' => 'active', 'template' => 'design-catalog', 'geography_mode' => 'national',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        $company = Company::create([
            'name' => 'Özgün Atölye', 'slug' => 'ozgun-atolye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true,
            'phone' => '0232 111 22 33', 'short_description' => 'El yapımı ahşap mobilya',
            'description' => str_repeat('Özgün mobilya ve tasarım hizmeti sunuyoruz. ', 3),
        ]);
        foreach (['Aydınlatma' => 'Ankara', 'Boya Badana' => 'Bursa', 'Çilingir' => 'Antalya', 'Nakliyat' => 'İstanbul', 'Halı Yıkama' => 'Adana'] as $name => $cityName) {
            $extraCity = City::firstOrCreate(['slug' => \Illuminate\Support\Str::slug($cityName)], ['name' => $cityName]);
            $extraCategory = Category::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'status' => 'active']);
            Company::create([
                'name' => $name.' Uzmanı', 'slug' => \Illuminate\Support\Str::slug($name).'-uzmani', 'category_id' => $extraCategory->id,
                'city_id' => $extraCity->id, 'directory_id' => $directory->id, 'status' => 'active',
                'phone' => '0212 000 00 00', 'short_description' => $name.' alanında hızlı ve güvenilir hizmet.',
            ]);
        }
        $job = JobPosting::create([
            'company_id' => $company->id, 'directory_id' => $directory->id,
            'title' => 'Mobilya Ustası', 'description' => 'Ekibimize çalışma arkadaşı arıyoruz.',
            'employment_type' => 'full_time', 'status' => 'published',
            'published_at' => now(), 'admin_published' => true,
        ]);
        $post = Post::create([
            'title' => 'Mobilya Seçme Rehberi', 'slug' => 'mobilya-secme-rehberi',
            'content' => '<p>Ahşap mobilya seçerken malzemeye dikkat edin.</p>',
            'status' => 'published', 'published_at' => now(), 'directory_id' => $directory->id,
        ]);
        $post->directories()->attach($directory->id);
        MembershipPlan::create([
            'directory_id' => $directory->id, 'name' => 'Başlangıç', 'slug' => 'baslangic', 'price' => 0,
            'currency' => 'TRY', 'billing_period' => 'monthly', 'is_active' => true, 'sort_order' => 1,
            'features' => [['title' => 'Profil sayfası', 'description' => 'Temel bilgiler']],
        ]);

        $this->get('/')->assertOk()
            ->assertSee('theme-design-catalog', false)
            ->assertSee('kat-hero', false)
            ->assertSee('İçindekiler')
            ->assertSee('Özgün Atölye')
            ->assertSee('Mobilya Ustası')
            ->assertSee('Mobilya Seçme Rehberi');

        $this->get('/firma/'.$company->slug)->assertOk()
            ->assertSee('kat-detail', false)
            ->assertSee('id="yorumlar"', false)
            ->assertSee(route('companies.reviews.store', $company->slug), false);

        foreach ([
            '/firmalar' => 'Yayındaki firmalar',
            '/ara?q=Özgün' => 'Arama sonuçları',
            '/kategori/mobilya' => 'Mobilya firmaları',
            '/sehir/izmir' => 'İzmir firmaları',
            '/blog' => 'Yazılar ve rehberler',
            '/blog/'.$post->slug => 'Mobilya Seçme Rehberi',
            '/is-ilanlari' => 'Açık pozisyonlar',
            '/is-ilanlari/'.$job->slug => 'Görev tanımı',
            '/paketler' => 'Gold',
            '/hakkimizda' => 'Hakkımızda',
            '/iletisim' => 'Mesajı gönder',
            '/gizlilik-politikasi' => 'Gizlilik Politikası',
            '/kullanim-sartlari' => 'Kullanım Şartları',
        ] as $url => $text) {
            $this->get($url)->assertOk()
                ->assertSee('kat-header', false)
                ->assertSee('kat-footer', false)
                ->assertSee($text);
        }
    }
}
