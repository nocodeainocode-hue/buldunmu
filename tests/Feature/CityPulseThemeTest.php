<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityPulseThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_renders_the_city_pulse_layout_with_directory_data(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Şehir Nabzı Test',
            'slug' => 'sehir-nabzi-test',
            'domain' => $host,
            'status' => 'active',
            'template' => 'city-pulse',
            'geography_mode' => 'national',
        ]);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Restoran', 'slug' => 'restoran', 'status' => 'active']);

        Company::create([
            'directory_id' => $directory->id,
            'name' => 'Nabız Lokantası',
            'slug' => 'nabiz-lokantasi',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
            'is_premium' => true,
            'latitude' => 41.0082,
            'longitude' => 28.9784,
            'short_description' => 'Şehrin merkezinde yerel lezzetler.',
        ]);

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Canlı yerel keşif');
        $response->assertSee('Nabız Lokantası');
        $response->assertSee('Şehir haritası');
        $response->assertSee('city-pulse-map');
    }
}
