<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Çini Pano · İznik Karosu (cini-pano) teması. */
class CiniPanoThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    private City $city;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Salon Rehberi', 'slug' => 'salon-rehberi', 'domain' => 'cini.test', 'status' => 'active',
            'template' => 'cini-pano', 'geography_mode' => 'national',
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
        return $this->withServerVariables(['HTTP_HOST' => 'cini.test'])->get('http://cini.test'.$path);
    }

    private function addCompany(string $name, string $phone, ?Category $category = null): void
    {
        Company::create([
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => ($category ?? $this->category)->id, 'city_id' => $this->city->id,
            'status' => 'active', 'phone' => $phone,
        ]);
    }

    public function test_theme_is_registered_with_its_own_layout(): void
    {
        $this->assertSame('cini-pano', ThemeHelper::TEMPLATES['cini-pano']['layout']);
        $this->assertSame('Çini Pano · İznik Karosu', ThemeHelper::templateSelectOptions()['cini-pano']);
        $this->assertNotContains('cini-pano', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/cini-pano', 'partials/cini-pano/header', 'partials/cini-pano/styles', 'partials/cini-pano/card', 'partials/cini-pano/motif'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }
    }

    public function test_home_page_renders_medallion_search_tiles_cards_and_registration(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-cini-pano', false)
            ->assertSee('cn-header', false)
            ->assertSee('cn-semse', false)
            ->assertSee('id="cn-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('id="karolar"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('id="cn-rozet"', false)
            ->assertSee('href="tel:05321234567"', false)
            ->assertSee('https://wa.me/905321234567', false)
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->assertSee('Özgün Kuaför')
            ->assertSee('Saç ve bakım hizmetleri.')
            ->assertSee('0532 123 45 67')
            ->assertSee('Öne çıkan')
            ->assertSee('Kuaför, 1 firma', false)
            ->assertSee('Panodaki işletmeler')
            ->assertSee('Panoda sizin karonuz eksik');
    }

    public function test_heading_comes_from_settings_and_fonts_load_once(): void
    {
        $html = (string) $this->open('/')->assertOk()->getContent();

        $this->assertSame(1, substr_count($html, '<h1'));
        preg_match_all('#fonts\.googleapis\.com/css2\?[^"]+#', $html, $fontUrls);
        $this->assertCount(1, array_unique($fontUrls[0]));
        $this->assertStringContainsString('family=Alegreya', $fontUrls[0][0]);
        // Firma kartı başlığı H1'den sonra gelir; boş "tel:" bağlantısı basılmaz.
        $this->assertStringNotContainsString('href="tel:"', $html);
    }

    public function test_counts_only_appear_when_real(): void
    {
        $this->open('/')->assertOk()->assertDontSee('kayıtlı firma');

        for ($i = 2; $i <= 25; $i++) {
            $this->addCompany('Salon '.$i, '0532 '.(100 + $i).' 45 67');
        }

        $this->open('/')->assertOk()->assertSee('25 kayıtlı firma');
    }

    public function test_phone_links_dial_the_number_shown_and_skip_text_only_phones(): void
    {
        Company::query()->withoutGlobalScopes()->delete();
        $this->addCompany('On Haneli', '5339876543');
        $this->addCompany('Uluslararası', '+90 216 555 44 33');
        $this->addCompany('Numarasız', 'yok');

        $html = (string) $this->open('/')->assertOk()->getContent();

        $this->assertStringContainsString('href="tel:05339876543"', $html);
        $this->assertStringContainsString('0533 987 65 43', $html);
        $this->assertStringContainsString('href="tel:02165554433"', $html);
        $this->assertStringContainsString('0216 555 44 33', $html);
        $this->assertStringNotContainsString('href="tel:"', $html);
        $this->assertStringContainsString('Firmayı incele', $html);
    }

    public function test_card_shows_no_filler_text_or_fake_badges(): void
    {
        $this->addCompany('Açıklamasız Salon', '0533 222 33 44');

        $html = (string) $this->open('/')->assertOk()->getContent();

        $this->assertStringContainsString('Açıklamasız Salon', $html);
        $this->assertSame(substr_count($html, 'Saç ve bakım hizmetleri.'), substr_count($html, 'class="cn-kart__desc"'));
        // Yalnızca öne çıkan (premium) firmada mühür; onaylı rozeti gerçek değilse yok.
        $this->assertSame(1, substr_count($html, 'class="cn-kart__seal"'));
        $this->assertStringNotContainsString('class="cn-chip cn-chip--ok"', $html);
    }

    public function test_tile_panel_fillers_complete_the_last_row_for_each_column_count(): void
    {
        foreach (['Berber', 'Tesisat', 'Avukat', 'Emlak', 'Nakliyat'] as $name) {
            $category = Category::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name), 'status' => 'active']);
            $this->addCompany($name.' Usta', '0533 222 33 4'.strlen($name), $category);
        }

        // 6 kategori: 2 sütunda 0, 3 sütunda 0, 4 sütunda 2 süs karosu.
        $html = (string) $this->open('/')->assertOk()->getContent();
        $this->assertSame(6, substr_count($html, '<a class="cn-karo"'));
        $this->assertSame(['class="cn-dolgu cn-dolgu--c4"', 'class="cn-dolgu cn-dolgu--c4"'], $this->fillers($html));

        // 7 kategori: 2 sütunda 1, 3 sütunda 2, 4 sütunda 1 süs karosu.
        $category = Category::create(['name' => 'Eczane', 'slug' => 'eczane', 'status' => 'active']);
        $this->addCompany('Merkez Eczane', '0352 222 14 37', $category);
        $html = (string) $this->open('/')->assertOk()->getContent();
        $this->assertSame(['class="cn-dolgu cn-dolgu--c2 cn-dolgu--c3 cn-dolgu--c4"', 'class="cn-dolgu cn-dolgu--c3"'], $this->fillers($html));
    }

    private function fillers(string $html): array
    {
        preg_match_all('#class="cn-dolgu[^"]*"#', $html, $m);

        return $m[0];
    }

    public function test_empty_directory_still_renders_with_a_call_to_action(): void
    {
        Company::query()->withoutGlobalScopes()->delete();
        Category::query()->delete();

        $this->open('/')->assertOk()
            ->assertSee('Bu panoya ilk karoyu siz koyun')
            ->assertSee('Hizmet karoları firmalar eklendikçe dizilecek.')
            ->assertSee('id="karolar"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('Panoda sizin karonuz eksik')
            ->assertSee(route('owner.register'), false)
            ->assertDontSee('class="cn-kartlar__cta"', false);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim', '/paketler'] as $path) {
            $this->open($path)->assertOk()->assertSee('cn-header', false);
        }
    }

    public function test_no_network_payment_or_superlative_claims_on_the_home_page(): void
    {
        $html = mb_strtolower((string) $this->open('/')->getContent());

        foreach (['en büyük', 'rehberlerimiz', 'diğer rehber', 'rehber ağı', 'kredi kartı', 'online ödeme', 'sepete'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $html);
        }
        $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|site)\b/iu', $html);
        // Görünen metinde 83 sayısı geçmez (stil ve betik içerikleri hariç).
        $text = strip_tags(preg_replace('#<(style|script)\b.*?</\1>#s', '', $html));
        $this->assertDoesNotMatchRegularExpression('/\b83\b/u', $text);
    }
}
