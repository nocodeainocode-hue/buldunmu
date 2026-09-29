<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AtlasTest extends TestCase
{
    use RefreshDatabase;

    public function test_turkey_atlas_theme_puts_the_map_on_its_homepage_and_scopes_company_pins(): void
    {
        $this->assertSame('Türkiye Firma Atlası', \App\View\Helpers\ThemeHelper::templateSelectOptions()['turkey-atlas']);
        $directory = Directory::create(['name' => 'Atlas Rehberi', 'slug' => 'atlas', 'domain' => 'atlas.local', 'status' => 'active', 'template' => 'turkey-atlas']);
        $otherDirectory = Directory::create(['name' => 'Başka Rehber', 'slug' => 'baska', 'domain' => 'baska.local', 'status' => 'active']);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $ankara = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $category = Category::create(['name' => 'Restoran', 'slug' => 'restoran', 'status' => 'active']);

        Company::create(['name' => 'Görünen Firma', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'latitude' => 41.01, 'longitude' => 28.98]);
        Company::create(['name' => 'Diğer Rehber Firması', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $otherDirectory->id, 'status' => 'active', 'latitude' => 41.02, 'longitude' => 28.99]);
        Company::create(['name' => 'Konumsuz Firma', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active']);
        Company::create(['name' => 'Ankara Firması', 'category_id' => $category->id, 'city_id' => $ankara->id,
            'directory_id' => $directory->id, 'status' => 'active', 'latitude' => 39.93, 'longitude' => 32.85]);

        $this->withServerVariables(['HTTP_HOST' => 'atlas.local'])
            ->get('http://atlas.local/')
            ->assertOk()
            ->assertSee('theme-turkey-atlas', false)
            ->assertSee('id="atlas-map"', false)
            ->assertSee('İşletmeleri')
            ->assertSee('İstanbul');

        $this->withServerVariables(['HTTP_HOST' => 'baska.local'])
            ->get('http://baska.local/')
            ->assertOk()
            ->assertDontSee('id="atlas-map"', false);

        $this->withServerVariables(['HTTP_HOST' => 'atlas.local'])
            ->getJson('http://atlas.local/harita/firmalar?city=istanbul&west=28&south=40&east=30&north=42')
            ->assertOk()
            ->assertJsonPath('total', 1)
            ->assertJsonPath('companies.0.name', 'Görünen Firma');

        $this->withServerVariables(['HTTP_HOST' => 'atlas.local'])
            ->getJson('http://atlas.local/harita/firmalar?west=25&south=35&east=45&north=43')
            ->assertOk()
            ->assertJsonPath('total', 2)
            ->assertJsonFragment(['name' => 'Ankara Firması']);
    }
}
