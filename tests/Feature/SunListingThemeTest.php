<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Canlı Vitrin · Sarı Liste (sun-listing) teması. */
class SunListingThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Salon Rehberi', 'slug' => 'salon-rehberi', 'domain' => 'salonrehberi.test', 'status' => 'active',
            'template' => 'sun-listing', 'geography_mode' => 'national',
        ]);
        $city = City::create(['name' => 'Kayseri', 'slug' => 'kayseri']);
        $category = Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        Company::create([
            'name' => 'Özgün Kuaför', 'directory_id' => $this->directory->id, 'category_id' => $category->id, 'city_id' => $city->id,
            'status' => 'active', 'is_premium' => true, 'phone' => '0532 123 45 67', 'short_description' => 'Saç ve bakım hizmetleri.',
        ]);
    }

    private function open(string $path)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'salonrehberi.test'])->get('http://salonrehberi.test'.$path);
    }

    public function test_theme_is_registered_with_its_own_layout(): void
    {
        $theme = ThemeHelper::TEMPLATES['sun-listing'];

        $this->assertSame('sun-listing', $theme['layout']);
        $this->assertFileExists(resource_path('views/frontend/home/sun-listing.blade.php'));
        $this->assertFileExists(resource_path('views/partials/sun-listing/header.blade.php'));
        $this->assertFileExists(resource_path('views/partials/sun-listing/styles.blade.php'));
    }

    public function test_home_page_shows_hero_search_cards_and_sections(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-sun-listing', false)
            ->assertSee('sl-hero', false)
            ->assertSee('<mark>', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('Firma Bul')
            ->assertSee('Özgün Kuaför')
            ->assertSee('Öne çıkan')
            ->assertSee('Saç ve bakım hizmetleri.')
            ->assertSee('tel:05321234567', false)
            ->assertSee('Dört adımda aradığınıza ulaşın')
            ->assertSee('Hızlı keşfet')
            ->assertSee(route('owner.register'), false)
            ->assertSee('sl-header', false);
    }

    public function test_numbers_are_only_shown_when_they_are_real_and_large_enough(): void
    {
        // Tek firmalı rehberde "Aktif firma" sayacı gösterilmez (uydurma sayı yok).
        $this->open('/')->assertOk()->assertDontSee('Aktif firma');
    }

    public function test_empty_directory_still_renders_with_a_call_to_action(): void
    {
        Company::query()->withoutGlobalScopes()->delete();

        $this->open('/')->assertOk()->assertSee('Bu rehberde ilk firma sizinki olabilir');
    }

    public function test_inner_pages_open_without_errors_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim', '/paketler'] as $path) {
            $this->open($path)->assertOk()->assertSee('sl-header', false);
        }
    }

    public function test_no_network_or_unverified_superlative_claims_on_the_home_page(): void
    {
        $html = mb_strtolower((string) $this->open('/')->getContent());

        $this->assertStringNotContainsString('en büyük', $html);
        $this->assertStringNotContainsString('rehberlerimiz', $html);
    }
}
