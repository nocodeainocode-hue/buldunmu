<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\Post;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** Posta Pulu · Mektup Kutusu (posta-pulu) teması. */
class PostaPuluThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    private City $city;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        Carbon::setTestNow(Carbon::parse('2026-03-10 10:00:00'));

        $this->directory = Directory::create([
            'name' => 'Kayseri Esnaf Rehberi', 'slug' => 'kayseri-esnaf', 'domain' => 'pul.test', 'status' => 'active',
            'template' => 'posta-pulu', 'geography_mode' => 'national',
        ]);
        $this->city = City::create(['name' => 'Kayseri', 'slug' => 'kayseri']);
        $this->category = Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        $this->addCompany('Özgün Kuaför', '0532 123 45 67', 4, [
            'is_premium' => true, 'whatsapp' => '0532 123 45 67', 'short_description' => 'Saç ve bakım hizmetleri.',
        ]);
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();
        parent::tearDown();
    }

    private function open(string $path = '/')
    {
        return $this->withServerVariables(['HTTP_HOST' => 'pul.test'])->get('http://pul.test'.$path);
    }

    private function addCompany(string $name, string $phone, int $daysAgo, array $extra = []): Company
    {
        $company = Company::create(array_merge([
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'phone' => $phone,
        ], $extra));
        Company::query()->withoutGlobalScopes()->whereKey($company->id)->update(['created_at' => now()->subDays($daysAgo)]);

        return $company;
    }

    public function test_theme_is_registered_with_its_own_layout_and_files(): void
    {
        $this->assertSame('posta-pulu', ThemeHelper::TEMPLATES['posta-pulu']['layout']);
        $this->assertSame('Posta Pulu · Mektup Kutusu', ThemeHelper::templateSelectOptions()['posta-pulu']);
        $this->assertNotContains('posta-pulu', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/posta-pulu', 'partials/posta-pulu/header', 'partials/posta-pulu/styles', 'partials/posta-pulu/card', 'partials/posta-pulu/postmark'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }

        $styles = file_get_contents(resource_path('views/partials/posta-pulu/styles.blade.php'));
        preg_match_all('/grid-template-columns:\s*repeat\(([^;]+)\)/', $styles, $m);
        foreach ($m[1] as $repeat) {
            $this->assertStringContainsString('minmax(0,1fr)', $repeat);
        }
    }

    public function test_home_page_renders_the_envelope_search_stamps_and_call_links(): void
    {
        $this->addCompany('Usta Berber', '+90 533 222 33 44', 3);
        $this->addCompany('Talas Kuaför', '0352 437 28 46', 2, ['is_verified' => true]);

        $html = (string) $this->open()->assertOk()
            ->assertSee('theme-posta-pulu', false)
            ->assertSee('pp-header', false)
            ->assertSee('Pulunuzu yapıştırın')
            ->assertSee('class="pp-env pp-airmail"', false)
            ->assertSee('id="pp-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('Kime:')
            ->assertSee('Nereye:')
            ->assertSee('Gönder · Firma bul')
            ->assertSee('Günün pulu')
            ->assertSee('Masadaki pullar')
            ->assertSee('Yeni gelen mektuplar')
            ->assertSee('Pul tabakası')
            ->assertSee('Şehir damgaları')
            ->assertSee('Burada sizin pulunuz olabilir')
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->getContent();

        $this->assertStringContainsString('pp-perf pp-stamp pp-stamp--lg', $html);
        $this->assertStringContainsString('href="tel:05321234567"', $html);
        $this->assertStringContainsString('href="tel:05332223344"', $html);
        $this->assertStringContainsString('0533 222 33 44', $html);
        $this->assertStringContainsString('https://wa.me/905321234567', $html);
        $this->assertStringNotContainsString('href="tel:"', $html);
        // Rozetler yalnızca gerçekse: öne çıkan firma taahhütlü, onaylı firma onaylı.
        $this->assertStringContainsString('pp-rubber--red">Taahhütlü', $html);
        $this->assertStringContainsString('pp-rubber--green">Onaylı', $html);
        // Damga tarihi İstanbul saatine göre; H1 tek.
        $this->assertStringContainsString('10.03.2026', $html);
        $this->assertSame(1, substr_count($html, '<h1'));
        // Az firma varken toplam sayı gösterilmez.
        $this->assertStringNotContainsString('Mektup kutusunda', $html);
    }

    public function test_postmark_date_follows_istanbul_time_and_posts_become_postcards(): void
    {
        // 22:30 UTC, İstanbul'da 11 Mart 01:30.
        Carbon::setTestNow(Carbon::parse('2026-03-10 22:30:00'));
        Post::create([
            'title' => 'Kuaför seçerken nelere bakılır', 'slug' => 'kuafor-secerken', 'excerpt' => 'Kısa bir rehber yazısı.',
            'content' => '<p>İçerik</p>', 'status' => 'published', 'published_at' => Carbon::parse('2026-03-09 22:15:00'),
        ]);

        $this->open()->assertOk()
            ->assertSee('11.03.2026')
            ->assertSee('Kartpostallar')
            ->assertSee('class="pp-card"', false)
            ->assertSee('10 Mart 2026');
    }

    public function test_counts_only_appear_when_real_and_large_enough(): void
    {
        for ($i = 2; $i <= 25; $i++) {
            $this->addCompany('Salon '.$i, '0532 '.(100 + $i).' 45 67', $i);
        }

        $this->open()->assertOk()->assertSee('Mektup kutusunda 25 firma var.');
    }

    public function test_empty_directory_shows_empty_states(): void
    {
        Company::query()->withoutGlobalScopes()->delete();

        $html = (string) $this->open()->assertOk()
            ->assertSee('Henüz mektup gelmedi')
            ->assertSee('Mektup kutusu henüz boş')
            ->assertSee('id="hizmetler"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('name="q"', false)
            ->assertDontSee('aria-label="Masadaki pullar"', false)
            ->assertDontSee('Günün pulu')
            ->assertSee(route('owner.register'), false)
            ->getContent();

        $this->assertStringNotContainsString('href="tel:', $html);
        $this->assertStringContainsString('pp-hero__grid--solo', $html);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim'] as $path) {
            $this->open($path)->assertOk()->assertSee('pp-header', false);
        }
    }

    public function test_no_network_count_brand_or_payment_wording_on_the_home_page(): void
    {
        foreach (['Usta Berber' => '0533 222 33 44', 'Merkez Salon' => '0532 765 43 21', 'Talas Kuaför' => '0352 437 28 46'] as $name => $phone) {
            $this->addCompany($name, $phone, 2);
        }

        $html = (string) $this->open()->assertOk()->getContent();
        $text = mb_strtolower(strip_tags(preg_replace('#<(style|script)\b.*?</\1>#is', '', $html)));

        foreach (['diğer rehber', 'rehber ağı', 'rehberlerimiz', 'ağımız', 'en büyük', 'online ödeme', 'kartla öde', 'ödeme yap', 'ptt'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $text);
        }
        $this->assertDoesNotMatchRegularExpression('/(?<![\d.,])83(?![\d.,])/u', $text);
        $this->assertDoesNotMatchRegularExpression('/\b100\s+(rehber|site)\b/iu', $text);
    }
}
