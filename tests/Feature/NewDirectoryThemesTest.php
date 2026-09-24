<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewDirectoryThemesTest extends TestCase
{
    use RefreshDatabase;

    public function test_three_new_homepages_render_search_and_company_links(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Keşif Testi',
            'slug' => 'kesif-testi',
            'domain' => $host,
            'status' => 'active',
            'template' => 'signal-station',
        ]);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Tesisat', 'slug' => 'tesisat', 'status' => 'active']);
        Company::create([
            'name' => 'Örnek Tesisat',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'directory_id' => $directory->id,
            'status' => 'active',
            'is_premium' => true,
        ]);

        foreach (['signal-station', 'paper-trail', 'orbit-directory'] as $template) {
            $directory->update(['template' => $template]);

            $response = $this->get('/');

            $response->assertOk();
            $response->assertSee('Örnek Tesisat');
            $response->assertSee(route('search'), false);
            $response->assertSee('theme-'.$template, false);
        }
    }

    public function test_two_utopian_homepages_render_search_categories_and_company_links(): void
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';
        $directory = Directory::create([
            'name' => 'Ütopya Testi', 'slug' => 'utopya-testi', 'domain' => $host,
            'status' => 'active', 'template' => 'sky-archipelago',
        ]);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Tasarım', 'slug' => 'tasarim', 'status' => 'active']);
        Company::create([
            'name' => 'Yeni Dünya Tasarım', 'category_id' => $category->id, 'city_id' => $city->id,
            'directory_id' => $directory->id, 'status' => 'active',
        ]);

        foreach (['sky-archipelago', 'luminous-garden'] as $template) {
            $directory->update(['template' => $template]);

            $this->get('/')
                ->assertOk()
                ->assertSee('Yeni Dünya Tasarım')
                ->assertSee(route('search'), false)
                ->assertSee(route('categories.show', $category->slug), false)
                ->assertSee('theme-'.$template, false);
        }
    }
}
