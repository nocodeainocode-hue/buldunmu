<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Tabela Çarşısı · Esnaf Sırası (tabela-carsi) teması. */
class TabelaCarsiThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    private City $city;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Çarşı Rehberi', 'slug' => 'carsi-rehberi', 'domain' => 'tabela.test', 'status' => 'active',
            'template' => 'tabela-carsi', 'geography_mode' => 'national',
        ]);
        $this->city = City::create(['name' => 'Kayseri', 'slug' => 'kayseri']);
        $this->category = Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        Company::create([
            'name' => 'Özgün Kuaför', 'directory_id' => $this->directory->id, 'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'is_premium' => true, 'phone' => '0532 123 45 67', 'whatsapp' => '0532 123 45 67',
            'short_description' => 'Saç ve bakım hizmetleri.',
        ]);
    }

    private function open(string $path)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'tabela.test'])->get('http://tabela.test'.$path);
    }

    private function home(): string
    {
        return (string) $this->open('/')->assertOk()->getContent();
    }

    private function addCompany(string $name, string $phone, array $extra = []): void
    {
        Company::create(array_merge([
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'phone' => $phone,
        ], $extra));
    }

    public function test_theme_is_registered_with_its_own_layout(): void
    {
        $this->assertSame('tabela-carsi', ThemeHelper::TEMPLATES['tabela-carsi']['layout']);
        $this->assertSame('Tabela Çarşısı · Esnaf Sırası', ThemeHelper::templateSelectOptions()['tabela-carsi']);
        $this->assertNotContains('tabela-carsi', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/tabela-carsi', 'partials/tabela-carsi/header', 'partials/tabela-carsi/styles', 'partials/tabela-carsi/card', 'partials/tabela-carsi/city-plates'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }
    }

    public function test_home_page_renders_street_search_shop_cards_and_registration(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-tabela-carsi', false)
            ->assertSee('tc-header', false)
            ->assertSee('tc-hero', false)
            ->assertSee('class="tc-street"', false)
            ->assertSee('id="tc-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('tel:05321234567', false)
            ->assertSee('https://wa.me/905321234567', false)
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->assertSee(route('categories.show', 'kuafor'), false)
            ->assertSee('Esnafa sor')
            ->assertSee('Tabelanı as')
            ->assertSee('Tabelanı bekliyor')
            ->assertSee('Çarşının vitrini')
            ->assertSee('Meslek sokakları')
            ->assertSee('Özgün Kuaför')
            ->assertSee('Saç ve bakım hizmetleri.')
            ->assertSee('0532 123 45 67')
            ->assertSee('Vitrinde');
    }

    public function test_h1_comes_from_the_homepage_title_setting(): void
    {
        $html = $this->home();

        $this->assertSame(1, substr_count($html, '<h1'));
        $this->assertMatchesRegularExpression('#<h1 class="tc-h1">[^<]+</h1>#', $html);
    }

    public function test_shutters_do_not_depend_on_the_clock_and_doors_are_not_numbered(): void
    {
        $this->addCompany('Usta Berber', '0533 222 33 44');

        $html = $this->home();

        foreach (['frontend/home/tabela-carsi', 'partials/tabela-carsi/card'] as $view) {
            $this->assertStringNotContainsString('isOpenNow', file_get_contents(resource_path('views/'.$view.'.blade.php')));
        }

        // Yalnızca tabela bekleyen boş dükkânın kepengi yarı inik; diğer dükkânlar her saatte açık görünür.
        $this->assertSame(1, substr_count($html, 'class="tc-kepenk"'));
        $this->assertStringNotContainsString('KAPALI', $html);
        $this->assertStringNotContainsString('Şu an açık', $html);
        $this->assertDoesNotMatchRegularExpression('/No:\s*\d/u', $html);
    }

    public function test_counts_only_appear_when_real_and_large_enough(): void
    {
        $this->open('/')->assertOk()->assertDontSee('esnaf çarşıda');

        for ($i = 2; $i <= 25; $i++) {
            $this->addCompany('Salon '.$i, '0532 '.(100 + $i).' 45 67');
        }

        $this->open('/')->assertOk()
            ->assertSee('25</b>&nbsp;esnaf çarşıda', false)
            ->assertSee('Tüm dükkânlar (25)');
    }

    public function test_phone_links_dial_the_number_shown_and_skip_text_only_phones(): void
    {
        $this->addCompany('On Haneli', '5339876543');
        $this->addCompany('Uluslararası', '+90 216 555 44 33');
        $this->addCompany('Numarasız', 'yok');

        $html = $this->home();

        $this->assertStringContainsString('href="tel:05339876543"', $html);
        $this->assertStringContainsString('0533 987 65 43', $html);
        $this->assertStringContainsString('href="tel:02165554433"', $html);
        $this->assertStringContainsString('0216 555 44 33', $html);
        $this->assertStringNotContainsString('href="tel:"', $html);
        $this->assertStringContainsString('Numarasız', $html);
    }

    public function test_badges_only_appear_when_real(): void
    {
        $this->addCompany('Sade Berber', '0533 222 33 44');

        $html = $this->home();
        // Yalnızca öne çıkan firmada "Vitrinde", onaysız firmada "Onaylı" rozeti yok.
        $this->assertSame(1, substr_count($html, 'class="tc-vitrinde"'));
        $this->assertStringNotContainsString('Onaylı esnaf', $html);
        // WhatsApp yalnızca numarası olan firmada.
        $this->assertSame(1, substr_count($html, 'class="tc-btn tc-btn--wa"'));

        Company::query()->withoutGlobalScopes()->where('name', 'Sade Berber')->update(['is_verified' => true]);
        $this->assertSame(1, substr_count($this->home(), 'Onaylı esnaf'));
    }

    public function test_empty_showcase_slot_only_fills_a_gap_in_the_last_row(): void
    {
        // 1 firma: masaüstünde iki sütunluk, tablette bir sütunluk boşluk.
        $this->assertStringContainsString('tc-slot tc-slot--d2 tc-slot--t1', $this->home());

        $this->addCompany('Usta Berber', '0533 222 33 44');
        $this->addCompany('Kadıköy Güzellik', '0216 345 67 89');
        // 3 firma: masaüstü sırası tam, tablette son sıra yarım.
        $this->assertStringContainsString('tc-slot tc-slot--d0 tc-slot--t1', $this->home());

        foreach (['Merkez Salon' => '0532 765 43 21', 'Talas Kuaför' => '0352 437 28 46', 'Melikgazi Saç' => '0352 222 14 37'] as $name => $phone) {
            $this->addCompany($name, $phone);
        }
        // 6 firma: hiçbir düzende boşluk yok, "Yer boş" kartı basılmaz.
        $html = $this->home();
        $this->assertStringNotContainsString('class="tc-slot', $html);
        $this->assertStringNotContainsString('Bu vitrin senin olsun', $html);
    }

    public function test_few_cities_hang_on_the_wall_instead_of_a_half_empty_section(): void
    {
        $html = $this->home();

        $this->assertSame(1, substr_count($html, 'id="tc-mahalleler"'));
        $this->assertStringContainsString('Şehir levhaları', $html);
        $this->assertStringContainsString(route('cities.show', 'kayseri'), $html);
        $this->assertStringContainsString('1 esnaf', $html);
    }

    public function test_empty_directory_still_renders_with_calls_to_action(): void
    {
        Company::query()->withoutGlobalScopes()->delete();
        Category::query()->withoutGlobalScopes()->delete();

        $html = $this->home();

        $this->assertStringContainsString('Çarşı yeni kuruluyor', $html);
        $this->assertStringContainsString('Henüz dükkân yok: ilk tabelayı sen as', $html);
        $this->assertStringContainsString('Yakında açılıyor', $html);
        $this->assertStringContainsString('Tabela duvarı henüz boş.', $html);
        $this->assertStringContainsString('Levhalar henüz asılmadı.', $html);
        $this->assertStringContainsString('id="tc-sokaklar"', $html);
        $this->assertStringContainsString('id="tc-mahalleler"', $html);
        $this->assertStringContainsString(route('owner.register'), $html);
        $this->assertStringNotContainsString('tc-shop__sign tc-bolts"><h3', $html);
    }

    public function test_fonts_are_loaded_once(): void
    {
        preg_match_all('#fonts\.googleapis\.com/css2\?[^"]+#', $this->home(), $fontUrls);
        $this->assertCount(1, array_unique($fontUrls[0]));
        $this->assertStringContainsString('Alfa+Slab+One', $fontUrls[0][0]);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim'] as $path) {
            $this->open($path)->assertOk()->assertSee('tc-header', false);
        }
    }

    public function test_no_network_count_or_payment_claims_on_the_home_page(): void
    {
        $html = $this->home();
        preg_match('#<main[^>]*>(.*)</main>#s', $html, $main);
        $text = mb_strtolower(trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('#<(script|style)\b.*?</\1>#s', '', $main[1] ?? '')))));
        $this->assertNotSame('', $text);

        foreach (['en büyük', 'rehberlerimiz', 'diğer rehber', 'rehber ağı', 'ağımız', 'ödeme', 'kredi kartı', '4.900'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $text);
        }
        $this->assertDoesNotMatchRegularExpression('/\b83\b/u', $text);
        $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|site)\b/iu', mb_strtolower($html));
    }
}
