<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Neon Bulvar · Gece Vitrini (neon-bulvar) teması. */
class NeonBulvarThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    private City $city;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Gece Rehberi', 'slug' => 'gece-rehberi', 'domain' => 'neon.test', 'status' => 'active',
            'template' => 'neon-bulvar', 'geography_mode' => 'national',
        ]);
        $this->city = City::create(['name' => 'Eskişehir', 'slug' => 'eskisehir']);
        $this->category = Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        Company::create([
            'name' => 'Işık Kuaför', 'directory_id' => $this->directory->id, 'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'is_premium' => true, 'phone' => '0532 123 45 67', 'whatsapp' => '0532 123 45 67',
            'short_description' => 'Saç ve bakım hizmetleri.',
        ]);
    }

    private function open(string $path)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'neon.test'])->get('http://neon.test'.$path);
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
        $this->assertSame('neon-bulvar', ThemeHelper::TEMPLATES['neon-bulvar']['layout']);
        $this->assertSame('Neon Bulvar · Gece Vitrini', ThemeHelper::templateSelectOptions()['neon-bulvar']);
        $this->assertNotContains('neon-bulvar', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/neon-bulvar', 'partials/neon-bulvar/header', 'partials/neon-bulvar/styles', 'partials/neon-bulvar/card', 'partials/neon-bulvar/skyline'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }
    }

    public function test_home_page_renders_skyline_search_signs_showcases_and_registration(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-neon-bulvar', false)
            ->assertSee('nb-header', false)
            ->assertSee('class="nb-sky"', false)
            ->assertSee('nb-sky__near', false)
            ->assertSee('id="nb-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('id="tabelalar"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('class="nb-sign nb-c', false)
            ->assertSee('href="tel:05321234567"', false)
            ->assertSee('https://wa.me/905321234567', false)
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->assertSee('Işık Kuaför')
            ->assertSee('Saç ve bakım hizmetleri.')
            ->assertSee('0532 123 45 67')
            ->assertSee('Öne çıkan')
            ->assertSee('Kuaför, 1 firma', false)
            ->assertSee('Bulvarın vitrinleri')
            ->assertSee('Bulvarda sizin tabelanız sönük')
            ->assertSee('class="nb-dock"', false);
    }

    public function test_heading_comes_from_settings_and_fonts_load_once(): void
    {
        $html = $this->home();

        $this->assertSame(1, substr_count($html, '<h1'));
        preg_match_all('#fonts\.googleapis\.com/css2\?[^"]+#', $html, $fontUrls);
        $this->assertCount(1, array_unique($fontUrls[0]));
        $this->assertStringContainsString('family=Righteous', $fontUrls[0][0]);
        $this->assertStringContainsString('family=Outfit', $fontUrls[0][0]);
        $this->assertStringNotContainsString('href="tel:"', $html);
    }

    public function test_counts_only_appear_when_real_and_large_enough(): void
    {
        $this->open('/')->assertOk()->assertDontSee('kayıtlı firma');

        for ($i = 2; $i <= 25; $i++) {
            $this->addCompany('Vitrin '.$i, '0532 '.(100 + $i).' 45 67');
        }

        $this->open('/')->assertOk()->assertSee('25</span> kayıtlı firma', false);
    }

    public function test_phone_links_dial_the_number_shown_and_skip_text_only_phones(): void
    {
        Company::query()->withoutGlobalScopes()->delete();
        $this->addCompany('On Haneli', '5339876543');
        $this->addCompany('Uluslararası', '+90 216 555 44 33');
        $this->addCompany('Numarasız', 'yok');

        $html = $this->home();

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

        $html = $this->home();

        $this->assertStringContainsString('Açıklamasız Salon', $html);
        $this->assertSame(substr_count($html, 'Saç ve bakım hizmetleri.'), substr_count($html, 'class="nb-vitrin__desc"'));
        $this->assertSame(1, substr_count($html, 'class="nb-vitrin__seal'));
        $this->assertStringNotContainsString('class="nb-chip nb-chip--ok"', $html);
    }

    public function test_same_category_lights_in_the_same_neon_tone_everywhere(): void
    {
        $tone = abs(crc32('kuafor')) % 6;
        $html = $this->home();

        $this->assertStringContainsString('class="nb-sign nb-c'.$tone.'"', $html);
        $this->assertStringContainsString('class="nb-vitrin nb-c'.$tone.' nb-vitrin--premium"', $html);
        $this->assertStringContainsString('class="nb-often__a nb-c'.$tone.'"', $html);
    }

    public function test_skyline_is_deterministic_and_animations_respect_reduced_motion(): void
    {
        $first = $this->home();
        $second = $this->home();

        preg_match('#<div class="nb-sky".*?</div>\s*</div>#s', $first, $a);
        preg_match('#<div class="nb-sky".*?</div>\s*</div>#s', $second, $b);
        $this->assertNotEmpty($a);
        $this->assertSame($a[0], $b[0]);
        $this->assertSame(3, substr_count($first, 'class="nb-sky__layer'));
        $this->assertStringContainsString('@media (prefers-reduced-motion: reduce)', $first);
    }

    public function test_empty_directory_still_renders_with_calls_to_action(): void
    {
        Company::query()->withoutGlobalScopes()->delete();
        Category::query()->delete();

        $this->open('/')->assertOk()
            ->assertSee('Bu bulvarda ilk neonu siz yakın')
            ->assertSee('Tabelalar firmalar eklendikçe yanacak.')
            ->assertSee('Şehirler firmalar eklendikçe burada yazılacak.')
            ->assertSee('id="tabelalar"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('Bulvarda sizin tabelanız sönük')
            ->assertSee(route('owner.register'), false)
            ->assertDontSee('class="nb-vitrinler__cta"', false);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim', '/paketler'] as $path) {
            $this->open($path)->assertOk()->assertSee('nb-header', false);
        }
    }

    public function test_no_network_count_or_payment_claims_on_the_home_page(): void
    {
        $html = mb_strtolower($this->home());

        foreach (['en büyük', 'rehberlerimiz', 'diğer rehber', 'rehber ağı', 'kredi kartı', 'online ödeme', 'sepete'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $html);
        }
        $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|site)\b/iu', $html);
        $text = strip_tags(preg_replace('#<(style|script)\b.*?</\1>#s', '', $html));
        $this->assertDoesNotMatchRegularExpression('/\b83\b/u', $text);
    }
}
