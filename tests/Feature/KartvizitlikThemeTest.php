<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Kartvizitlik · Kart Dosyası (kartvizitlik) teması. */
class KartvizitlikThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    private City $city;

    private Category $category;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Salon Rehberi', 'slug' => 'salon-rehberi', 'domain' => 'kartvizit.test', 'status' => 'active',
            'template' => 'kartvizitlik', 'geography_mode' => 'national',
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
        return $this->withServerVariables(['HTTP_HOST' => 'kartvizit.test'])->get('http://kartvizit.test'.$path);
    }

    private function addCompany(string $name, string $phone): void
    {
        Company::create([
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => $this->category->id, 'city_id' => $this->city->id,
            'status' => 'active', 'phone' => $phone,
        ]);
    }

    public function test_theme_is_registered_with_its_own_layout(): void
    {
        $this->assertSame('kartvizitlik', ThemeHelper::TEMPLATES['kartvizitlik']['layout']);
        $this->assertSame('Kartvizitlik · Kart Dosyası', ThemeHelper::templateSelectOptions()['kartvizitlik']);
        $this->assertNotContains('kartvizitlik', ThemeHelper::PHONE_SHELL);

        foreach (['frontend/home/kartvizitlik', 'partials/kartvizitlik/header', 'partials/kartvizitlik/styles', 'partials/kartvizitlik/card', 'partials/kartvizitlik/blank-card'] as $view) {
            $this->assertFileExists(resource_path('views/'.$view.'.blade.php'));
        }
    }

    public function test_home_page_renders_card_grammar_search_and_registration(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-kartvizitlik', false)
            ->assertSee('kv-header', false)
            ->assertSee('kv-hero', false)
            ->assertSee('id="kv-ara"', false)
            ->assertSee('name="q"', false)
            ->assertSee('name="city"', false)
            ->assertSee('tel:05321234567', false)
            ->assertSee('https://wa.me/905321234567', false)
            ->assertSee('name="company_name"', false)
            ->assertSee(route('owner.register'), false)
            ->assertSee('Kartları getir')
            ->assertSee('Özgün Kuaför')
            ->assertSee('Saç ve bakım hizmetleri.')
            ->assertSee('0532 123 45 67')
            ->assertSee('Öne çıkan')
            ->assertSee('Masadaki kartlar')
            ->assertSee('Senin yerin')
            ->assertSee('Masada senin kartın eksik');
    }

    public function test_counts_and_quick_call_list_only_appear_when_real(): void
    {
        $this->open('/')->assertOk()
            ->assertDontSee('firma kartı')
            ->assertDontSee('Hızlı arama fişi');

        $this->addCompany('Usta Berber', '0533 222 33 44');
        $this->addCompany('Kadıköy Güzellik', '0216 345 67 89');

        // Üç firma da raftaki kartlarda; yedek numara listesi aynı firmaları ikinci kez göstermez.
        $this->open('/')->assertOk()
            ->assertSee('0216 345 67 89')
            ->assertDontSee('Hızlı arama fişi')
            ->assertDontSee('firma kartı');

        Company::query()->withoutGlobalScopes()->update(['is_verified' => true]);

        $this->open('/')->assertOk()->assertSee('Onaylı firmalar, hemen ara');

        for ($i = 4; $i <= 25; $i++) {
            $this->addCompany('Salon '.$i, '0532 '.(100 + $i).' 45 67');
        }

        // Kategori ve şehir listeleri 12 ile sınırlı olduğundan etikette yalnızca gerçek firma toplamı yazar.
        $this->open('/')->assertOk()
            ->assertSee('Salon Rehberi · kart dosyası · 25 firma kartı')
            ->assertDontSee('şehir kutusu');
    }

    public function test_phone_links_dial_the_number_shown_and_skip_text_only_phones(): void
    {
        $this->addCompany('On Haneli', '5339876543');
        $this->addCompany('Uluslararası', '+90 216 555 44 33');
        $this->addCompany('Numarasız', 'yok');
        Company::query()->withoutGlobalScopes()->update(['is_verified' => true]);

        $html = (string) $this->open('/')->assertOk()->assertSee('Onaylı firmalar, hemen ara')->getContent();

        $this->assertStringContainsString('href="tel:05339876543"', $html);
        $this->assertStringContainsString('0533 987 65 43', $html);
        $this->assertStringContainsString('href="tel:02165554433"', $html);
        $this->assertStringContainsString('0216 555 44 33', $html);
        $this->assertStringNotContainsString('href="tel:"', $html);
    }

    public function test_accessibility_details_on_the_home_page(): void
    {
        foreach (['Usta Berber' => '0533 222 33 44', 'Kadıköy Güzellik' => '0216 345 67 89', 'Merkez Salon' => '0532 765 43 21'] as $name => $phone) {
            $this->addCompany($name, $phone);
        }

        $html = (string) $this->open('/')->assertOk()->getContent();

        // Yelpazedeki kart başlık değil; ilk bölüm başlığından önce H3 gelmez.
        $this->assertStringContainsString('<p class="kv-card__name">', $html);
        $this->assertLessThan(strpos($html, '<h3 class="kv-card__name">'), strpos($html, 'Masadaki kartlar'));
        // Görünen "Kart ekle" metni erişilebilir adın parçası kalır.
        $this->assertStringContainsString('<span class="kv-cta-short">Kart ekle</span>', $html);
        $this->assertStringContainsString('aria-controls="mobile-menu" aria-expanded="false"', $html);
        $this->assertStringContainsString('aria-label="Kartını bırak: firmanı ücretsiz ekle"', $html);
        // Yazı tipleri tek adresten bir kez yüklenir.
        preg_match_all('#fonts\.googleapis\.com/css2\?[^"]+#', $html, $fontUrls);
        $this->assertCount(1, array_unique($fontUrls[0]));
    }

    public function test_empty_directory_still_renders_with_a_call_to_action(): void
    {
        Company::query()->withoutGlobalScopes()->delete();

        $this->open('/')->assertOk()
            ->assertSee('İlk kart senin olsun')
            ->assertSee('Henüz kart yok')
            ->assertSee('id="cekmeceler"', false)
            ->assertSee('id="sehirler"', false)
            ->assertSee('Şehir kutuları henüz boş.')
            ->assertSee(route('owner.register'), false);
    }

    public function test_inner_pages_open_and_keep_the_theme_header(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim', '/paketler'] as $path) {
            $this->open($path)->assertOk()->assertSee('kv-header', false);
        }
    }

    public function test_register_page_prefills_the_name_typed_on_the_blank_card(): void
    {
        $this->open('/firma-kayit?company_name=Deneme%20Usta')->assertOk()->assertSee('value="Deneme Usta"', false);
        $this->open('/firma-kayit?company_name[]=x')->assertOk();
    }

    public function test_no_network_or_superlative_claims_on_the_home_page(): void
    {
        $html = mb_strtolower((string) $this->open('/')->getContent());

        foreach (['en büyük', 'rehberlerimiz', 'diğer rehber', 'rehber ağı'] as $phrase) {
            $this->assertStringNotContainsString($phrase, $html);
        }
        $this->assertDoesNotMatchRegularExpression('/\b(83|100)\s+(rehber|site)\b/iu', $html);
    }
}
