<?php

namespace Tests\Feature;

use App\Models\AdCampaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\User;
use App\Services\AdServer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class AdSystemTest extends TestCase
{
    use RefreshDatabase;

    private function directory(array $overrides = []): Directory
    {
        $host = parse_url(config('app.url'), PHP_URL_HOST) ?: 'localhost';

        return Directory::create($overrides + [
            'name' => 'Reklam Rehberi', 'slug' => 'reklam-rehberi', 'domain' => $host,
            'status' => 'active', 'template' => 'default', 'geography_mode' => 'national',
        ]);
    }

    private function ad(array $attributes = []): AdCampaign
    {
        return AdCampaign::create($attributes + [
            'name' => 'Test Reklam', 'type' => 'house', 'status' => 'active', 'placements' => ['top', 'bottom'],
            'headline' => 'Test başlık', 'link_url' => 'https://example.com/hedef', 'weight' => 1,
        ]);
    }

    private function city(string $name): City
    {
        return City::create(['name' => $name, 'slug' => \Illuminate\Support\Str::slug($name)]);
    }

    public function test_selection_prefers_paid_then_narrow_house_then_general_house(): void
    {
        Cache::flush();
        $directory = $this->directory();
        $tekirdag = $this->city('Tekirdağ');
        $izmir = $this->city('İzmir');

        $general = $this->ad(['name' => 'Genel', 'headline' => 'Genel']);
        $regional = $this->ad(['name' => 'Bölgesel', 'headline' => 'Bölgesel', 'city_ids' => [$tekirdag->id]]);
        $server = app(AdServer::class);

        $this->assertSame($regional->id, $server->pick('top', $directory, $tekirdag->slug)->id, 'Tekirdağ bağlamında bölgesel reklam öne geçmeli');
        $this->assertSame($general->id, $server->pick('top', $directory, $izmir->slug)->id, 'Başka şehirde genel reklam gösterilmeli');
        $this->assertSame($general->id, $server->pick('top', $directory)->id, 'Bağlam yoksa genel reklam gösterilmeli');

        $paid = $this->ad(['name' => 'Ücretli', 'headline' => 'Ücretli', 'type' => 'paid']);
        $this->assertSame($paid->id, $server->pick('top', $directory, $tekirdag->slug)->id, 'Ücretli reklamveren her zaman önde olmalı');
    }

    public function test_local_directory_of_the_target_city_matches_without_page_context(): void
    {
        Cache::flush();
        $tekirdag = $this->city('Tekirdağ');
        $local = $this->directory(['geography_mode' => 'local', 'primary_city_slug' => 'tekirdag']);
        $national = Directory::create(['name' => 'Ulusal', 'slug' => 'ulusal', 'domain' => 'ulusal.example', 'status' => 'active', 'geography_mode' => 'national']);

        $regional = $this->ad(['name' => 'Bölgesel', 'city_ids' => [$tekirdag->id]]);
        $server = app(AdServer::class);

        $this->assertSame($regional->id, $server->pick('top', $local)->id);
        $this->assertNull($server->pick('top', $national));
    }

    public function test_paused_expired_future_and_other_placement_ads_are_ignored(): void
    {
        Cache::flush();
        $directory = $this->directory();
        $this->ad(['name' => 'Durduruldu', 'status' => 'paused']);
        $this->ad(['name' => 'Bitti', 'ends_at' => now()->subDay()]);
        $this->ad(['name' => 'Gelecek', 'starts_at' => now()->addDay()]);
        $this->ad(['name' => 'Sadece alt', 'placements' => ['bottom']]);

        $this->assertNull(app(AdServer::class)->pick('top', $directory));
    }

    public function test_category_targeting_requires_a_matching_category_context(): void
    {
        Cache::flush();
        $directory = $this->directory();
        $oto = Category::create(['name' => 'Otomotiv', 'slug' => 'otomotiv', 'status' => 'active']);
        $gida = Category::create(['name' => 'Gıda', 'slug' => 'gida', 'status' => 'active']);
        $this->ad(['name' => 'Oto', 'category_ids' => [$oto->id]]);
        $server = app(AdServer::class);

        $this->assertNotNull($server->pick('top', $directory, null, $oto->slug));
        $this->assertNull($server->pick('top', $directory, null, $gida->slug));
        $this->assertNull($server->pick('top', $directory));
    }

    public function test_pages_render_labelled_sponsored_banner_in_both_slots(): void
    {
        Cache::flush();
        $this->directory();
        $this->ad(['name' => 'Sayfa Reklamı', 'headline' => 'Görünür Başlık']);

        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Görünür Başlık')
            ->assertSee('adx adx--top', false)
            ->assertSee('adx adx--bottom', false)
            ->assertSee('rel="sponsored nofollow noopener"', false)
            ->assertSee('>Reklam<', false);
    }

    public function test_no_banner_when_there_is_no_ad_and_none_in_owner_area(): void
    {
        Cache::flush();
        $this->directory();

        $this->get('/')->assertOk()->assertDontSee('adx adx--', false);

        $this->ad(['headline' => 'Panel Dışı Reklam']);
        Cache::flush();
        $this->get('/panel/giris')->assertOk()->assertDontSee('Panel Dışı Reklam');
    }

    public function test_phone_shell_themes_do_not_show_banners(): void
    {
        Cache::flush();
        $this->directory(['template' => 'pocket-stories']);
        $this->ad(['headline' => 'Cep Reklamı']);

        $this->get('/')->assertOk()->assertDontSee('Cep Reklamı');
    }

    public function test_tekirdag_page_shows_regional_ad_and_other_city_shows_general_ad(): void
    {
        Cache::flush();
        $directory = $this->directory();
        $tekirdag = $this->city('Tekirdağ');
        $izmir = $this->city('İzmir');
        $category = Category::create(['name' => 'Mobilya', 'slug' => 'mobilya', 'status' => 'active']);
        foreach ([$tekirdag, $izmir] as $city) {
            Company::create(['name' => 'Firma '.$city->name, 'category_id' => $category->id, 'city_id' => $city->id,
                'directory_id' => $directory->id, 'status' => 'active', 'phone' => '02122223344']);
        }
        $this->ad(['name' => 'Genel', 'headline' => 'Genel Reklam Başlığı']);
        $this->ad(['name' => 'Tekirdağ', 'headline' => 'Tekirdağ Reklam Başlığı', 'city_ids' => [$tekirdag->id]]);

        $this->get('/sehir/tekirdag')->assertOk()->assertSee('Tekirdağ Reklam Başlığı');
        $this->get('/sehir/izmir')->assertOk()->assertSee('Genel Reklam Başlığı')->assertDontSee('Tekirdağ Reklam Başlığı');

        // Regresyon: ana sayfadaki şehir döngüsü layout'a sızıp bölgesel reklamı tetiklememeli.
        $this->get('/')->assertOk()->assertSee('Genel Reklam Başlığı')->assertDontSee('Tekirdağ Reklam Başlığı');

        $company = Company::where('city_id', $tekirdag->id)->first();
        $this->get('/firma/'.$company->slug)->assertOk()->assertSee('Tekirdağ Reklam Başlığı');
        $this->get('/kategori/mobilya?city=tekirdag')->assertOk()->assertSee('Tekirdağ Reklam Başlığı');
    }

    public function test_click_redirects_with_utm_and_counts_only_real_visitors(): void
    {
        $directory = $this->directory();
        $ad = $this->ad(['name' => 'Su Arıtma 59', 'link_url' => 'https://suaritma59.com/']);

        $this->withHeader('User-Agent', 'Mozilla/5.0 (Windows NT 10.0) Chrome/120 Safari/537.36')
            ->get(route('ad.click', $ad))
            ->assertRedirect()
            ->assertRedirectContains('https://suaritma59.com/?utm_source='.$directory->domain.'&utm_medium=banner&utm_campaign=su-aritma-59');

        $this->assertSame(1, $ad->fresh()->clicks_total);

        $this->withHeader('User-Agent', 'Googlebot/2.1 (+http://www.google.com/bot.html)')
            ->get(route('ad.click', $ad))->assertRedirect();
        $this->assertSame(1, $ad->fresh()->clicks_total, 'Bot tıklaması sayılmamalı');

        $this->assertSame(1, (int) $ad->dailyStats()->sum('clicks'));
    }

    public function test_impression_pixel_counts_real_visitors_only(): void
    {
        $this->directory();
        $ad = $this->ad();
        $human = 'Mozilla/5.0 (iPhone; CPU iPhone OS 17_0) AppleWebKit/605.1.15 Mobile Safari/604.1';

        $this->withHeader('User-Agent', $human)->get(route('ad.impression', $ad))
            ->assertOk()->assertHeader('Content-Type', 'image/gif');
        $this->withHeader('User-Agent', $human)->get(route('ad.impression', $ad))->assertOk();
        $this->withHeader('User-Agent', 'bingbot/2.0')->get(route('ad.impression', $ad))->assertOk();

        $this->assertSame(2, $ad->fresh()->impressions_total);
        $this->assertSame(2, (int) $ad->dailyStats()->sum('impressions'));
    }

    public function test_click_on_a_non_http_target_is_rejected(): void
    {
        $this->directory();
        $ad = $this->ad(['link_url' => 'javascript:alert(1)']);

        $this->withHeader('User-Agent', 'Mozilla/5.0 Chrome/120')->get(route('ad.click', $ad))->assertNotFound();
    }

    public function test_house_ad_command_is_idempotent_and_creates_regional_ad_only_with_the_city(): void
    {
        $this->artisan('ads:seed-house')->assertSuccessful();
        $this->assertSame(1, AdCampaign::count(), 'Tekirdağ yoksa yalnızca genel reklam oluşmalı');

        $tekirdag = $this->city('Tekirdağ');
        $this->artisan('ads:seed-house')->assertSuccessful();
        $this->artisan('ads:seed-house')->assertSuccessful();

        $this->assertSame(2, AdCampaign::count());
        $regional = AdCampaign::where('link_url', 'https://suaritma59.com')->first();
        $this->assertSame([$tekirdag->id], $regional->city_ids);
        $this->assertNull(AdCampaign::where('link_url', 'https://omnipuremarketing.com')->first()->city_ids);
    }

    public function test_admin_can_open_the_ad_resource(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->get('/admin/ad-campaigns')
            ->assertOk()
            ->assertSee('Reklamlar');

        $this->get('/admin/ad-campaigns/create')->assertOk();
    }
}
