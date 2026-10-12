<?php

namespace Tests\Feature;

use App\Models\AdCampaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\MembershipPlan;
use App\Support\PremiumPlanFeatures;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class PremiumAdFreeProfileTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;
    private Category $category;
    private City $city;

    protected function setUp(): void
    {
        parent::setUp();

        Cache::flush();
        $this->directory = Directory::create([
            'name' => 'Reklam Rehberi', 'slug' => 'reklam-rehberi', 'domain' => 'reklamrehberi.test', 'status' => 'active',
            'template' => 'default', 'geography_mode' => 'national',
        ]);
        $this->category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $this->city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);

        AdCampaign::create([
            'name' => 'Test Reklam', 'type' => 'house', 'status' => 'active', 'placements' => ['top', 'bottom'],
            'headline' => 'Genel Test Reklam Başlığı', 'link_url' => 'https://example.com/hedef', 'weight' => 1,
        ]);

        foreach (['Rakip Birinci Klinik', 'Rakip İkinci Klinik'] as $name) {
            $this->company($name);
        }
    }

    private function company(string $name, array $extra = []): Company
    {
        return Company::create($extra + [
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => $this->category->id,
            'city_id' => $this->city->id, 'status' => 'active', 'phone' => '0282 123 45 67',
        ]);
    }

    private function profile(Company $company)
    {
        return $this->withServerVariables(['HTTP_HOST' => 'reklamrehberi.test'])->get('http://reklamrehberi.test/firma/'.$company->slug);
    }

    public function test_regular_profile_shows_similar_companies_and_ads(): void
    {
        $company = $this->company('Sıradan Klinik');

        $this->profile($company)->assertOk()
            ->assertSee('Benzer Firmalar')
            ->assertSee('Rakip Birinci Klinik')
            ->assertSee('Genel Test Reklam Başlığı');
    }

    public function test_premium_profile_has_no_other_companies_and_no_ads(): void
    {
        $company = $this->company('Premium Klinik', ['is_premium' => true]);

        $this->profile($company)->assertOk()
            ->assertSee('Premium Klinik')
            ->assertDontSee('Benzer Firmalar')
            ->assertDontSee('Rakip Birinci Klinik')
            ->assertDontSee('Rakip İkinci Klinik')
            ->assertDontSee('Genel Test Reklam Başlığı');
    }

    public function test_premium_that_is_still_valid_is_ad_free_but_an_expired_one_is_not(): void
    {
        $valid = $this->company('Süreli Premium Klinik', ['is_premium' => true, 'premium_until' => now()->addMonth()]);
        $expired = $this->company('Süresi Biten Klinik', ['is_premium' => true, 'premium_until' => now()->subDay()]);

        $this->profile($valid)->assertOk()->assertDontSee('Benzer Firmalar')->assertDontSee('Genel Test Reklam Başlığı');
        $this->profile($expired)->assertOk()->assertSee('Benzer Firmalar')->assertSee('Genel Test Reklam Başlığı');
    }

    public function test_other_pages_keep_their_ads_even_when_premium_firms_exist(): void
    {
        $this->company('Premium Klinik', ['is_premium' => true]);

        $this->withServerVariables(['HTTP_HOST' => 'reklamrehberi.test'])->get('http://reklamrehberi.test/firmalar')
            ->assertOk()->assertSee('Genel Test Reklam Başlığı');
    }

    public function test_ad_free_feature_is_added_to_premium_plans_only_and_only_once(): void
    {
        $premium = MembershipPlan::create(['name' => 'Premium', 'slug' => 'premium', 'price' => 4900, 'features' => [['title' => 'Öne çıkan profil'], ['title' => 'Fotoğraf galerisi']]]);
        $other = MembershipPlan::create(['name' => 'Başlangıç', 'slug' => 'baslangic', 'price' => 0, 'features' => [['title' => 'Temel profil']]]);
        $withoutFeatures = MembershipPlan::create(['name' => 'Premium Plus', 'slug' => 'premium-plus', 'price' => 9900, 'features' => null]);

        $this->assertSame(2, PremiumPlanFeatures::addAdFree());
        $this->assertSame(0, PremiumPlanFeatures::addAdFree(), 'Tekrar çalıştırmak ikinci kez eklememeli.');

        $adFree = ['title' => PremiumPlanFeatures::AD_FREE_TITLE, 'description' => PremiumPlanFeatures::AD_FREE_DESCRIPTION];
        $this->assertSame([['title' => 'Öne çıkan profil'], ['title' => 'Fotoğraf galerisi'], $adFree], $premium->fresh()->features);
        $this->assertSame([$adFree], $withoutFeatures->fresh()->features);
        $this->assertSame([['title' => 'Temel profil']], $other->fresh()->features);
    }

    public function test_packages_page_lists_the_ad_free_feature_for_premium(): void
    {
        MembershipPlan::create([
            'name' => 'Premium', 'slug' => 'premium', 'price' => 4900, 'is_active' => true, 'sort_order' => 1,
            'features' => [['title' => 'Öne çıkan profil']],
        ]);
        PremiumPlanFeatures::addAdFree();

        $this->withServerVariables(['HTTP_HOST' => 'reklamrehberi.test'])->get('http://reklamrehberi.test/paketler')
            ->assertOk()
            ->assertSee('Reklamsız profil');
    }
}
