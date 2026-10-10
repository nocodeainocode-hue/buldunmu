<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\View\Helpers\ThemeHelper;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/** Yerelix · Yerel Rehber (local-ledger) teması ana sayfada ve alt sayfalarda sorunsuz açılır. */
class LocalLedgerThemeTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create([
            'name' => 'Yerelix', 'slug' => 'yerelix', 'domain' => 'yerelix.test', 'status' => 'active',
            'template' => 'local-ledger', 'geography_mode' => 'national',
        ]);
        $city = City::create(['name' => 'Kayseri', 'slug' => 'kayseri']);
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        Company::create([
            'name' => 'Özgün Atölye', 'directory_id' => $this->directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'is_premium' => true, 'phone' => '0532 123 45 67',
        ]);
    }

    private function open(string $path)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'yerelix.test'])->get('http://yerelix.test'.$path);
    }

    public function test_theme_is_registered_with_readable_defaults(): void
    {
        $theme = ThemeHelper::TEMPLATES['local-ledger'];

        $this->assertSame('local-ledger', $theme['layout']);
        $this->assertStringContainsString('Arial', $theme['font_body']);
        $this->assertFileExists(resource_path('views/frontend/home/local-ledger.blade.php'));
    }

    public function test_home_page_uses_the_local_ledger_layout_with_the_short_register_button(): void
    {
        $this->open('/')->assertOk()
            ->assertSee('theme-local-ledger', false)
            ->assertSee('yl-header__cta-long', false)
            ->assertSee('yl-header__cta-short', false)
            ->assertSee('Özgün Atölye')
            ->assertSee(route('owner.register'), false);
    }

    public function test_inner_pages_open_without_errors(): void
    {
        foreach (['/firmalar', '/blog', '/firma-kayit', '/hakkimizda', '/iletisim', '/paketler'] as $path) {
            $this->open($path)->assertOk();
        }
    }
}
