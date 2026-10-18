<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Hızlı indeksleme temelleri: rehbere özel robots.txt, bölgesel rehberde 404'süz sitemap, iç sayfa başlığında marka. */
class IndexingBasicsTest extends TestCase
{
    use RefreshDatabase;

    public function test_there_is_no_static_robots_file_shadowing_the_per_directory_route(): void
    {
        $this->assertFileDoesNotExist(public_path('robots.txt'));

        $directory = Directory::create(['name' => 'Robot Rehberi', 'slug' => 'robot', 'domain' => 'robot.test', 'status' => 'active']);
        app()->instance('currentDirectory', $directory);

        $this->get('http://robot.test/robots.txt')->assertOk()
            ->assertSee('Sitemap: http://robot.test/sitemap.xml', false)
            ->assertSee('Disallow: /panel', false)
            ->assertSee('Disallow: /ara', false);
    }

    public function test_local_directory_sitemap_lists_only_cities_that_open(): void
    {
        $directory = Directory::create([
            'name' => 'Trakya Rehberi', 'slug' => 'trakya', 'domain' => 'trakya.test', 'status' => 'active',
            'geography_mode' => 'local', 'primary_city_slug' => 'tekirdag', 'group_other_cities' => true,
        ]);
        $category = Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        foreach (['Tekirdağ' => 'tekirdag', 'Ankara' => 'ankara'] as $name => $slug) {
            $city = City::create(['name' => $name, 'slug' => $slug]);
            Company::create(['name' => $name.' Kuaför', 'directory_id' => $directory->id, 'category_id' => $category->id,
                'city_id' => $city->id, 'status' => 'active', 'phone' => '0282 412 55 66']);
        }
        app()->instance('currentDirectory', $directory);

        $this->get('http://trakya.test/sitemap.xml')->assertOk()
            ->assertHeader('Content-Type', 'application/xml; charset=UTF-8')
            ->assertSee('/sehir/tekirdag', false)
            ->assertDontSee('/sehir/ankara', false)
            ->assertSee('/sehir/diger-iller', false)
            ->assertSee('<lastmod>', false);

        $this->get('http://trakya.test/sehir/ankara')->assertNotFound();
        $this->get('http://trakya.test/sehir/tekirdag')->assertOk()->assertSee('<title>Tekirdağ Firmaları | Trakya Rehberi</title>', false);
    }
}
