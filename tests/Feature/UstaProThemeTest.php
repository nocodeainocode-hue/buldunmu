<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UstaProThemeTest extends TestCase
{
    use RefreshDatabase;

    private function makeDirectory(string $template): Directory
    {
        // Middleware directory'yi request host'una göre çözer; test URL'si .env APP_URL'den gelir
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

        return Directory::create([
            'name' => 'Usta Rehberi Test',
            'slug' => 'usta-rehberi-test',
            'domain' => $host,
            'status' => 'active',
            'template' => $template,
            'geography_mode' => 'national',
        ]);
    }

    private function seedDirectoryContent(): void
    {
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $category = Category::create(['name' => 'Su Tesisatçısı', 'slug' => 'su-tesisatcisi', 'status' => 'active']);

        Company::create([
            'name' => 'Yılmaz Tesisat',
            'slug' => 'yilmaz-tesisat',
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
            'is_premium' => true,
            'phone' => '05320000000',
            'short_description' => 'Su tesisatı ve petek temizliği',
        ]);
    }

    public function test_home_renders_with_usta_pro_layout(): void
    {
        $this->makeDirectory('usta-pro');
        $this->seedDirectoryContent();

        $response = $this->get('/');

        $response->assertOk();
        // hero arama + popüler kategoriler + vitrin + istatistik + CTA bölümleri render olmalı
        $response->assertSee('Firma Bul');
        $response->assertSee('Popüler Kategoriler');
        $response->assertSee('Su Tesisatçısı');
        $response->assertSee('Vitrindeki Firmalar');
        $response->assertSee('Yılmaz Tesisat');
        $response->assertSee('Kayıtlı Firma');
        $response->assertSee('Firmanız mı var?');
    }

    public function test_home_renders_with_usta_premium_dark_variant(): void
    {
        $this->makeDirectory('usta-premium');

        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Firma Bul');
        // Koyu varyantın renk token'ları CSS değişkenlerine basılmalı
        $response->assertSee('--primary: #8FAE93', false);
        $response->assertSee('--bg: #17201A', false);
    }
}
