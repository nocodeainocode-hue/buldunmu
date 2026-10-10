<?php

namespace Tests\Feature;

use App\Models\City;
use App\Models\District;
use App\Support\DistrictNameFixes;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class DistrictNameFixesTest extends TestCase
{
    use RefreshDatabase;

    private function city(string $name, string $slug): City
    {
        return City::create(['name' => $name, 'slug' => $slug]);
    }

    public function test_known_misspellings_and_combining_dots_are_fixed(): void
    {
        $kayseri = $this->city('Kayseri', 'kayseri');
        $adana = $this->city('Adana', 'adana');

        $this->assertSame('Sarıoğlan', DistrictNameFixes::fix($kayseri, 'Sarioğlan'));
        $this->assertSame('Hacılar', DistrictNameFixes::fix($kayseri, 'Hacilar'));
        $this->assertSame('Akkışla', DistrictNameFixes::fix($kayseri, 'Akkişla'));
        $this->assertSame('Karaisalı', DistrictNameFixes::fix($adana, "Karai\u{0307}sali"));
        $this->assertSame('Yüreğir', DistrictNameFixes::fix($adana, "Yüreği\u{0307}r"));
        // Doğru yazılmış ve tabloda olmayan adlar olduğu gibi kalır.
        $this->assertSame('Melikgazi', DistrictNameFixes::fix($kayseri, 'Melikgazi'));
        $this->assertSame('Kadıköy', DistrictNameFixes::fix($this->city('İstanbul', 'istanbul'), 'Kadıköy'));
    }

    public function test_the_central_district_takes_the_correct_city_name(): void
    {
        $agri = $this->city('Ağrı', 'agri');
        $adiyaman = $this->city('Adıyaman', 'adiyaman');

        $this->assertSame('Ağrı', DistrictNameFixes::fix($agri, 'Ağri'));
        $this->assertSame('Adıyaman', DistrictNameFixes::fix($adiyaman, 'Adiyaman'));
    }

    public function test_command_corrects_stored_names_keeps_slugs_and_is_repeatable(): void
    {
        $kayseri = $this->city('Kayseri', 'kayseri');
        $sarioglan = District::create(['city_id' => $kayseri->id, 'name' => 'Sarioğlan', 'slug' => 'sarioglan']);
        $hacilar = District::create(['city_id' => $kayseri->id, 'name' => 'Hacilar', 'slug' => 'hacilar']);
        $ok = District::create(['city_id' => $kayseri->id, 'name' => 'Talas', 'slug' => 'talas']);

        $this->artisan('catalog:fix-district-names --dry-run')->assertSuccessful();
        $this->assertSame('Sarioğlan', $sarioglan->fresh()->name);

        $this->artisan('catalog:fix-district-names')->assertSuccessful();

        $this->assertSame('Sarıoğlan', $sarioglan->fresh()->name);
        $this->assertSame('sarioglan', $sarioglan->fresh()->slug);
        $this->assertSame('Hacılar', $hacilar->fresh()->name);
        $this->assertSame('Talas', $ok->fresh()->name);

        $this->artisan('catalog:fix-district-names')->assertSuccessful()->expectsOutputToContain('0 ilçe adı düzeltildi');
    }

    public function test_sync_stores_the_corrected_names(): void
    {
        $this->city('Kayseri', 'kayseri');
        Http::fake(['*' => Http::response([
            ['name' => ['local' => 'Sarioğlan'], 'parent' => ['name' => ['local' => 'Kayseri']]],
            ['name' => ['local' => "Hacilar"], 'parent' => ['name' => ['local' => 'Kayseri']]],
        ])]);

        $this->artisan('catalog:sync-districts')->assertSuccessful();

        $this->assertEqualsCanonicalizing(['Hacılar', 'Sarıoğlan'], District::withoutGlobalScopes()->pluck('name')->all());
    }
}
