<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

/** Yaprak Takvim · Günün Firması (yaprak-takvim) teması. */
class YaprakTakvimThemeTest extends TestCase
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
            'name' => 'Kayseri Esnaf Rehberi', 'slug' => 'kayseri-esnaf', 'domain' => 'takvim.test', 'status' => 'active',
            'template' => 'yaprak-takvim', 'geography_mode' => 'national',
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
        return $this->withServerVariables(['HTTP_HOST' => 'takvim.test'])->get('http://takvim.test'.$path);
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

    private function todayFirm(string $html): ?string
    {
        return preg_match('#class="yt-today__name"><a href="[^"]+">([^<]+)<#', $html, $m) ? html_entity_decode($m[1]) : null;
    }

    private function yesterdayFirm(string $html): ?string
    {
        return preg_match('#Dünün yaprağı</p>\s*<p class="yt-torn__name"><a href="[^"]+">([^<]+)<#u', $html, $m) ? html_entity_decode($m[1]) : null;
    }

    public function test_theme_is_registered_with_its_own_layout_and_files(): void
    {
        $this->assertSame('yaprak-takvim', ThemeHelper::TEMPLATES['yaprak-takvim']['layout']);
        $this->assertSame('Yaprak Takvim · Günün Firması', ThemeHelper::templateSelectOptions()['yaprak-takvim']);
        $this->assertNotContains('yaprak-takvim', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/yaprak-takvim', 'partials/yaprak-takvim/header', 'partials/yaprak-takvim/styles', 'partials/yaprak-takvim/card'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }
    }

    public function test_home_page_renders_the_leaf_featured_firm_search_and_call_links(): void
    {
        $this->addCompany('Usta Berber', '0533 222 33 44', 3);

        $html = (string) $this->open()->assertOk()
            ->assertSee('theme-yaprak-takvim', false)
            ->assertSee('yt-header', false)
            ->assertSee('class="yt-today"', false)
            ->assertSee('Günün firması')
            ->assertSee('Mart')
            ->assertSee('Salı')
            ->assertSee('id="yt-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('Ne arıyorsunuz?')
            ->assertSee('Koparılan yapraklar')
            ->assertSee('Dünün yaprağı')
            ->assertSee('Bu haftanın yaprakları')
            ->assertSee('Takvimde yerinizi alın')
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->getContent();

        $this->assertMatchesRegularExpression('#<time class="yt-today__num" datetime="2026-03-10">10</time>#', $html);
        $this->assertStringContainsString('href="tel:05321234567"', $html);
        $this->assertStringContainsString('href="tel:05332223344"', $html);
        $this->assertStringContainsString('https://wa.me/905321234567', $html);
        $this->assertStringNotContainsString('href="tel:"', $html);
        // H1, rehberin ana sayfa başlığından gelir ve tek kez basılır.
        $this->assertSame(1, substr_count($html, '<h1'));
    }

    public function test_featured_firm_is_deterministic_per_day_and_rotates_daily(): void
    {
        // Havuz sırası: öne çıkan (Özgün Kuaför), sonra en yeniden eskiye D, C, B.
        $this->addCompany('Firma B', '0533 222 33 44', 3);
        $this->addCompany('Firma C', '0533 222 33 45', 2);
        $this->addCompany('Firma D', '0533 222 33 46', 1);
        $pool = ['Özgün Kuaför', 'Firma D', 'Firma C', 'Firma B'];

        $day = now()->dayOfYear;
        $first = (string) $this->open()->assertOk()->getContent();
        $this->assertSame($pool[$day % 4], $this->todayFirm($first));

        // Aynı gün içinde başka saatte de aynı firma (20:30 UTC = 23:30 İstanbul, hâlâ 10 Mart).
        Carbon::setTestNow(Carbon::parse('2026-03-10 20:30:00'));
        $this->assertSame($this->todayFirm($first), $this->todayFirm((string) $this->open()->getContent()));

        // Gün İstanbul saatine göre döner: 22:30 UTC, İstanbul'da 11 Mart 01:30'dur.
        Carbon::setTestNow(Carbon::parse('2026-03-10 22:30:00'));
        $this->assertSame($pool[($day + 1) % 4], $this->todayFirm((string) $this->open()->getContent()));

        // Ertesi gün sıradaki firma; dünün firması "Dünün yaprağı" olur.
        Carbon::setTestNow(Carbon::parse('2026-03-11 09:00:00'));
        $next = (string) $this->open()->assertOk()->getContent();
        $this->assertSame($pool[($day + 1) % 4], $this->todayFirm($next));
        $this->assertNotSame($this->todayFirm($first), $this->todayFirm($next));
        $this->assertSame($this->todayFirm($first), $this->yesterdayFirm($next));
    }

    public function test_counts_only_appear_when_real_and_large_enough(): void
    {
        $this->open()->assertOk()
            ->assertDontSee('Kayıtlı</dt>', false)
            ->assertDontSee('Şu an açık</dt>', false);

        for ($i = 2; $i <= 25; $i++) {
            $this->addCompany('Salon '.$i, '0532 '.(100 + $i).' 45 67', $i);
        }

        $this->open()->assertOk()->assertSee('<dt>Kayıtlı</dt><dd>25 firma</dd>', false);
    }

    public function test_empty_directory_shows_empty_states(): void
    {
        Company::query()->withoutGlobalScopes()->delete();

        $html = (string) $this->open()->assertOk()
            ->assertSee('Bu yaprak henüz boş')
            ->assertSee('Takvimin yaprakları henüz boş')
            ->assertSee('id="hizmetler"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('name="q"', false)
            ->assertDontSee('aria-label="Önceki günlerin yaprakları"', false)
            ->assertSee(route('owner.register'), false)
            ->getContent();

        $this->assertStringNotContainsString('href="tel:', $html);
        $this->assertStringContainsString('yt-hero__grid--solo', $html);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim'] as $path) {
            $this->open($path)->assertOk()->assertSee('yt-header', false);
        }
    }

    public function test_no_network_count_or_payment_wording_on_the_home_page(): void
    {
        foreach (['Usta Berber' => '0533 222 33 44', 'Merkez Salon' => '0532 765 43 21', 'Talas Kuaför' => '0352 437 28 46'] as $name => $phone) {
            $this->addCompany($name, $phone, 2);
        }

        $html = (string) $this->open()->assertOk()->getContent();
        $text = mb_strtolower(strip_tags(preg_replace('#<(style|script)\b.*?</\1>#is', '', $html)));

        foreach (['diğer rehber', 'rehber ağı', 'rehberlerimiz', 'ağımız', 'en büyük', 'online ödeme', 'kartla öde', 'ödeme yap'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $text);
        }
        $this->assertDoesNotMatchRegularExpression('/(?<![\d.,])83(?![\d.,])/u', $text);
        $this->assertDoesNotMatchRegularExpression('/\b100\s+(rehber|site)\b/iu', $text);
    }
}
