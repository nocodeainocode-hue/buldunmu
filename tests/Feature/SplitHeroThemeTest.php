<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SplitHeroThemeTest extends TestCase
{
    use RefreshDatabase;

    public function test_split_hero_has_its_own_home_layout(): void
    {
        $this->assertSame('split-hero', ThemeHelper::TEMPLATES['split-hero']['layout']);
        $this->assertFileExists(resource_path('views/frontend/home/split-hero.blade.php'));

        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Bölünmüş Rehber', 'slug' => 'bolunmus-rehber', 'domain' => $host,
            'status' => 'active', 'template' => 'split-hero', 'geography_mode' => 'national',
        ]);
        $city = City::create(['name' => 'İzmir', 'slug' => 'izmir']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        Company::create([
            'name' => 'Özgün Atölye', 'slug' => 'ozgun-atolye', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active', 'is_premium' => true, 'phone' => '0232 111 22 33',
        ]);

        $this->get('/')->assertOk()
            ->assertSee('theme-split-hero', false)
            ->assertSee('sp-hero__left', false)
            ->assertSee('Özgün Atölye')
            ->assertSee('Mobilya')
            ->assertSee(route('search'), false);
    }
}
