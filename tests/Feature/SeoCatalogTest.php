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
            'status' => 'active',
        ]);
        app()->instance('currentDirectory', $directory);

        $response = $this->get('/sitemap.xml')->assertOk();
        $response->assertSee('/kategori/'.$category->slug, false);
        $response->assertSee('/sehir/'.$city->slug, false);
        $response->assertDontSee('/kategori/'.$emptyCategory->slug, false);

        $this->get('/kategori/'.$emptyCategory->slug)
            ->assertOk()
            ->assertSee('noindex,follow,max-image-preview:large', false);
    }
}
