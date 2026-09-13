<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SeoCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_sitemap_contains_only_shared_taxonomies_with_active_companies(): void
    {
        $directory = Directory::create([
            'name' => 'SEO Rehberi', 'slug' => 'seo-rehberi', 'domain' => 'seo.test', 'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Aktif Kategori', 'slug' => 'aktif-kategori', 'status' => 'active']);
        $emptyCategory = Category::create(['name' => 'Boş Kategori', 'slug' => 'bos-kategori', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
        Company::create([
            'name' => 'SEO Firması',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'phone' => '02821234567',
            'address' => 'Tekirdağ merkez',
            'description' => 'Tekirdağ merkezde uzun süredir hizmet veren SEO Firması; müşterilerine güncel iletişim, adres ve hizmet detaylarını açık biçimde sunar.',
            'status' => 'active',
        ]);
        app()->instance('currentDirectory', $directory);

        $response = $this->get('/sitemap.xml')->assertOk();
        $response->assertSee('/kategori/'.$category->slug, false);
        $response->assertSee('/sehir/'.$city->slug, false);
        $response->assertDontSee('/kategori/'.$emptyCategory->slug, false);
        $response->assertDontSee('/firma-ekle', false);

        $this->get('/kategori/'.$emptyCategory->slug)
            ->assertOk()
            ->assertSee('noindex,follow,max-image-preview:large', false);
    }

    public function test_thin_company_profiles_are_noindex_and_excluded_from_sitemap(): void
    {
        $directory = Directory::create([
            'name' => 'Kalite Rehberi', 'slug' => 'kalite-rehberi', 'domain' => 'kalite.test', 'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
        $thin = Company::create([
            'name' => 'Eksik Profil', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'phone' => '02821234567', 'address' => 'Merkez', 'status' => 'active',
        ]);
        $ready = Company::create([
            'name' => 'Hazır Profil', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'phone' => '02827654321', 'address' => 'Merkez',
            'description' => 'Tekirdağ merkezde ağız ve diş sağlığı alanında randevulu hizmet veren Hazır Profil, güncel iletişim kanalları ve hizmet bilgileriyle ziyaretçilerini bilgilendirir.',
            'status' => 'active',
        ]);
        app()->instance('currentDirectory', $directory);

        $this->get('/firma/'.$thin->slug)
            ->assertOk()
            ->assertSee('noindex,follow,max-image-preview:large', false);
        $this->get('/firma/'.$ready->slug)
            ->assertOk()
            ->assertSee('index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1', false);

        $this->get('/sitemap.xml')
            ->assertOk()
            ->assertDontSee('/firma/'.$thin->slug, false)
            ->assertSee('/firma/'.$ready->slug, false);
    }
}
