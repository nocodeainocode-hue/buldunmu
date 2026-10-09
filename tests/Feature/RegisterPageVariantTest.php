<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\RegisterVariant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegisterPageVariantTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
        Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
    }

    private function page(string $query = '')
    {
        return $this->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])->get('http://buldunmu.test/firma-kayit'.$query);
    }

    public function test_default_page_keeps_the_standard_copy(): void
    {
        $this->page()->assertOk()
            ->assertSee('Müşteriler firmanızı kolayca bulsun')
            ->assertSee('Ücretsiz Profilimi Oluştur')
            ->assertSee('Doğrudan ulaşılabilir olun')
            ->assertSee('Profil önizlemesi');
    }

    public function test_a_variant_in_the_ad_url_changes_headline_button_and_fills_the_directory_name(): void
    {
        RegisterVariant::create([
            'key' => 'hizli', 'name' => 'Hızlı', 'headline' => 'Firmanız 2 dakikada {rehber} üzerinde yayında',
            'button_text' => 'Firmamı Yayına Al',
            'benefits' => [['title' => 'Kredi kartı yok', 'text' => 'Taahhüt de yok.']],
        ]);

        $this->page('?v=hizli')->assertOk()
            ->assertSee('Firmanız 2 dakikada Buldun mu? üzerinde yayında')
            ->assertSee('Firmamı Yayına Al')
            ->assertSee('Kredi kartı yok')
            ->assertDontSee('Müşteriler firmanızı kolayca bulsun');
    }

    public function test_city_and_category_placeholders_are_filled_from_the_ad_url(): void
    {
        RegisterVariant::where('key', 'sehir')->update(['headline' => "{sehir}'de {kategori} arayanlar sizi bulsun", 'subheadline' => null]);

        $this->page('?v=sehir&sehir=tekirdag&kategori=dis-klinigi')->assertOk()
            ->assertSee('Tekirdağ&#039;de Diş Kliniği arayanlar sizi bulsun', false);
    }

    public function test_unresolved_placeholders_paused_unknown_or_invalid_variants_fall_back_to_the_default(): void
    {
        RegisterVariant::where('key', 'sehir')->update(['headline' => '{sehir} firmaları için', 'subheadline' => null]);
        RegisterVariant::create(['key' => 'durdu', 'name' => 'Durdu', 'headline' => 'Durdurulmuş başlık', 'status' => 'paused']);

        foreach (['?v=sehir', '?v=sehir&sehir=yok-boyle-sehir', '?v=durdu', '?v=bilinmeyen', '?v=<script>'] as $query) {
            $this->page($query)->assertOk()
                ->assertSee('Müşteriler firmanızı kolayca bulsun')
                ->assertDontSee('Durdurulmuş başlık');
        }
    }

    public function test_step_one_offers_whatsapp_same_as_phone_and_optional_district(): void
    {
        $this->page()->assertOk()
            ->assertSee("Bu numara WhatsApp'ta da var", false)
            ->assertSee('id="same_whatsapp"', false)
            ->assertSee('(isteğe bağlı)');
    }

    public function test_category_search_only_appears_when_the_list_is_long(): void
    {
        $this->page()->assertOk()->assertDontSee('id="category_search"', false);

        foreach (range(1, 13) as $i) {
            Category::create(['name' => "Kategori $i", 'slug' => "kategori-$i", 'status' => 'active']);
        }

        $this->page()->assertOk()->assertSee('id="category_search"', false);
    }

    public function test_company_counter_is_shown_only_when_it_is_large_enough(): void
    {
        $category = Category::first();
        $city = City::first();

        $this->page()->assertOk()->assertDontSee('firma yayında.');

        foreach (range(1, 25) as $i) {
            Company::create([
                'name' => "Firma $i", 'directory_id' => $this->directory->id, 'category_id' => $category->id,
                'city_id' => $city->id, 'status' => 'active', 'phone' => '02820000001',
            ]);
        }
        \Illuminate\Support\Facades\Cache::flush();

        $this->page()->assertOk()->assertSee('firma yayında.');
    }

    public function test_variants_are_managed_from_the_admin_panel(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)->get('/admin/register-variants')->assertOk()->assertSee('Varyant');
    }
}
