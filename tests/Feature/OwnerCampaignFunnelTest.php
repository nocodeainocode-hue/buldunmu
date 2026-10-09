<?php

namespace Tests\Feature;

use App\Filament\Pages\AdAttributionReport;
use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyOwner;
use App\Models\Directory;
use App\Models\OwnerCampaignEvent;
use App\Models\SiteSetting;
use App\Models\User;
use App\Services\CampaignPlanService;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class OwnerCampaignFunnelTest extends TestCase
{
    use RefreshDatabase;

    private function directory(): Directory
    {
        return Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
    }

    private function owner(Directory $directory, array $userOverrides = []): array
    {
        $category = Category::firstOrCreate(['slug' => 'dis-klinigi'], ['name' => 'Diş Kliniği', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'tekirdag'], ['name' => 'Tekirdağ']);
        $user = User::factory()->create($userOverrides);
        $company = Company::create([
            'name' => 'Ayşe Diş Kliniği', 'directory_id' => $directory->id, 'category_id' => $category->id,
            'city_id' => $city->id, 'status' => 'active', 'phone' => '02820000000',
        ]);
        CompanyOwner::create(['company_id' => $company->id, 'user_id' => $user->id, 'directory_id' => $directory->id, 'role' => 'owner', 'status' => 'active']);

        return [$user, $company];
    }

    private function visit(Directory $directory, string $path)
    {
        return $this->withServerVariables(['HTTP_HOST' => $directory->domain])->get('http://'.$directory->domain.$path);
    }

    public function test_ad_click_source_is_captured_and_saved_on_registration(): void
    {
        $directory = $this->directory();
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);

        // Test istemcisi çerezleri kendiliğinden taşımaz; tarayıcının yaptığını elle yapıyoruz.
        $this->disableCookieEncryption();
        $landing = $this->visit($directory, '/firma-kayit?utm_source=google&utm_medium=cpc&utm_campaign=ekim-firma&gclid=ABC123')
            ->assertOk()
            ->assertCookie('fr_attr');
        $attribution = $landing->getCookie('fr_attr', false)->getValue();

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->withCookie('fr_attr', $attribution)
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ayşe Yılmaz', 'email' => 'ayse@example.test',
                'password' => 'guvenli-parola', 'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ayşe Diş Kliniği', 'category_id' => $category->id,
                'city_id' => $city->id, 'phone' => '0282 000 00 00',
            ])->assertRedirect();

        $user = User::where('email', 'ayse@example.test')->firstOrFail();
        $this->assertSame('google', $user->utm_source);
        $this->assertSame('cpc', $user->utm_medium);
        $this->assertSame('ekim-firma', $user->utm_campaign);
        $this->assertSame('gclid:ABC123', $user->click_id);
        $this->assertSame($directory->id, (int) $user->signup_directory_id);
    }

    public function test_registration_without_a_tracked_source_still_works(): void
    {
        $directory = $this->directory();
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ali Veli', 'email' => 'ali@example.test',
                'password' => 'guvenli-parola', 'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ali Klinik', 'category_id' => $category->id,
                'city_id' => $city->id, 'phone' => '0282 111 00 00',
            ])->assertRedirect();

        $user = User::where('email', 'ali@example.test')->firstOrFail();
        $this->assertNull($user->utm_source);
        $this->assertSame($directory->id, (int) $user->signup_directory_id);
    }

    public function test_dashboard_shows_banner_and_popup_once_and_records_the_event(): void
    {
        $directory = $this->directory();
        [$user] = $this->owner($directory);
        $this->actingAs($user);

        $this->visit($directory, '/panel')
            ->assertOk()
            ->assertSee('Rakiplerinizden sıyrılın.')
            ->assertSee('data-campaign-banner', false)
            ->assertSee('Ayşe Diş Kliniği 100 rehberde yayınlansın')
            ->assertSee('Rehber başına yaklaşık 49 TL');

        $this->assertSame(1, OwnerCampaignEvent::where('user_id', $user->id)->where('event', 'popup_shown')->count());
        $this->assertNotNull($user->fresh()->campaign_popup_seen_at);

        // İkinci ziyarette popup yok, banner var.
        $this->visit($directory, '/panel')->assertOk()->assertDontSee('Rakiplerinizden sıyrılın.')->assertSee('data-campaign-banner', false);
    }

    public function test_popup_returns_once_after_two_days_only_if_the_owner_never_engaged(): void
    {
        $directory = $this->directory();
        [$user] = $this->owner($directory, ['campaign_popup_seen_at' => now()->subDays(3)]);
        OwnerCampaignEvent::create(['user_id' => $user->id, 'directory_id' => $directory->id, 'event' => 'popup_shown', 'source' => 'popup', 'created_at' => now()->subDays(3)]);
        $this->actingAs($user);

        // 3 gün önce görmüş, etkileşim yok: bir kez daha gösterilir.
        $this->visit($directory, '/panel')->assertOk()->assertSee('Rakiplerinizden sıyrılın.');
        $this->assertSame(2, OwnerCampaignEvent::where('user_id', $user->id)->where('event', 'popup_shown')->count());

        // Toplam 2 gösterimden sonra bir daha çıkmaz.
        $user->forceFill(['campaign_popup_seen_at' => now()->subDays(5)])->save();
        $this->visit($directory, '/panel')->assertOk()->assertDontSee('Rakiplerinizden sıyrılın.');

        // Kampanya sayfasına giren kişiye ikinci kez gösterilmez.
        [$engaged] = $this->owner($directory, ['campaign_popup_seen_at' => now()->subDays(4), 'email' => 'e@example.test']);
        OwnerCampaignEvent::create(['user_id' => $engaged->id, 'directory_id' => $directory->id, 'event' => 'popup_shown', 'source' => 'popup', 'created_at' => now()->subDays(4)]);
        OwnerCampaignEvent::create(['user_id' => $engaged->id, 'directory_id' => $directory->id, 'event' => 'page_view', 'source' => 'popup', 'created_at' => now()->subDays(4)]);
        $this->actingAs($engaged);
        $this->visit($directory, '/panel')->assertOk()->assertDontSee('Rakiplerinizden sıyrılın.');
    }

    public function test_campaign_page_records_source_dedupes_and_explains_the_process(): void
    {
        $directory = $this->directory();
        [$user] = $this->owner($directory);
        $this->actingAs($user);

        $this->visit($directory, '/panel/kampanyalar?from=popup')
            ->assertOk()
            ->assertSee('100 Firma Rehberinde Yayın Projesi')
            ->assertSee('4.900 TL')
            ->assertSee('Ayşe Diş Kliniği')
            ->assertSee('Nasıl işler?')
            ->assertSee('10-15 gün')
            ->assertSee('Rehber başına yaklaşık 49 TL');

        $this->visit($directory, '/panel/kampanyalar?from=banner')->assertOk();

        $this->assertSame(1, OwnerCampaignEvent::where('event', 'page_view')->count(), '10 dk içinde tekrar ziyaret yeniden sayılmamalı');

        [$other] = $this->owner($directory, ['email' => 'baska@example.test']);
        $this->actingAs($other);
        $this->visit($directory, '/panel/kampanyalar?from=hack<script>')->assertOk();
        $this->assertSame('direct', OwnerCampaignEvent::where('user_id', $other->id)->where('event', 'page_view')->first()->source, 'Geçersiz kaynak "direct" olmalı');
    }

    public function test_whatsapp_click_is_counted_and_redirects_with_the_prefilled_message(): void
    {
        $directory = $this->directory();
        SiteSetting::getSettings();
        SiteSetting::withoutGlobalScope('directory')->where('directory_id', $directory->id)->update(['campaign_whatsapp' => '905551112233']);
        [$user] = $this->owner($directory);
        $this->actingAs($user);

        $response = $this->visit($directory, '/panel/kampanyalar/whatsapp');

        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://wa.me/905551112233?text=', $location);
        $this->assertStringContainsString(urlencode('Ayşe Diş Kliniği'), $location);
        $this->assertSame(1, OwnerCampaignEvent::where('user_id', $user->id)->where('event', 'whatsapp_click')->count());
    }

    public function test_popup_dismiss_beacon_is_recorded_and_only_known_events_are_accepted(): void
    {
        $directory = $this->directory();
        [$user] = $this->owner($directory);
        $this->actingAs($user);

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->postJson('http://buldunmu.test/panel/kampanyalar/olay', ['event' => 'popup_dismiss'])
            ->assertNoContent();
        $this->assertSame(1, OwnerCampaignEvent::where('event', 'popup_dismiss')->count());

        $this->withServerVariables(['HTTP_HOST' => $directory->domain])
            ->postJson('http://buldunmu.test/panel/kampanyalar/olay', ['event' => 'whatsapp_click'])
            ->assertStatus(422);
    }

    public function test_funnel_report_groups_signups_by_source(): void
    {
        $directory = $this->directory();
        [$google] = $this->owner($directory, ['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'ekim', 'signup_directory_id' => $directory->id, 'email' => 'g@example.test']);
        $this->owner($directory, ['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'ekim', 'signup_directory_id' => $directory->id, 'email' => 'g2@example.test']);
        $this->owner($directory, ['signup_directory_id' => $directory->id, 'email' => 'd@example.test']);
        foreach (['popup_shown', 'page_view', 'whatsapp_click'] as $event) {
            OwnerCampaignEvent::create(['user_id' => $google->id, 'directory_id' => $directory->id, 'event' => $event, 'source' => 'popup', 'created_at' => now()]);
        }

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $rows = collect(Livewire::test(AdAttributionReport::class)->instance()->getRows())->keyBy('source');

        $this->assertSame(2, $rows['google']['signups']);
        $this->assertSame(1, $rows['google']['whatsapp']);
        $this->assertSame(50, $rows['google']['whatsapp_rate']);
        $this->assertSame(1, $rows['Doğrudan']['signups']);

        $this->get('/admin/ad-attribution-report')->assertOk()->assertSee('Reklam Kaynakları ve Kampanya Hunisi');
    }

    public function test_campaign_plan_only_uses_directories_that_fit_the_company_city(): void
    {
        $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
        $ankara = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
        $company = Company::create(['name' => 'Ankara Firması', 'directory_id' => $source->id, 'category_id' => $category->id, 'city_id' => $ankara->id, 'status' => 'active']);

        // id sırasına göre önce UYMAYANLAR oluşturulur: eski kod limiti uygulamadan önce bunları alırdı.
        $tekirdagLocal = Directory::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag-r', 'domain' => 'tekirdag.test', 'status' => 'active', 'geography_mode' => 'local', 'primary_city_slug' => 'tekirdag']);
        $trakya = Directory::create(['name' => 'Trakya', 'slug' => 'trakya', 'domain' => 'trakya.test', 'status' => 'active', 'geography_mode' => 'custom', 'featured_city_slugs' => ['tekirdag', 'edirne', 'kirklareli'], 'group_other_cities' => false]);
        $national = Directory::create(['name' => 'Ulusal', 'slug' => 'ulusal', 'domain' => 'ulusal.test', 'status' => 'active', 'geography_mode' => 'national']);
        $ankaraLocal = Directory::create(['name' => 'Ankara', 'slug' => 'ankara-r', 'domain' => 'ankara.test', 'status' => 'active', 'geography_mode' => 'local', 'primary_city_slug' => 'ankara']);
        $customWithOthers = Directory::create(['name' => 'Gruplu', 'slug' => 'gruplu', 'domain' => 'gruplu.test', 'status' => 'active', 'geography_mode' => 'custom', 'featured_city_slugs' => ['izmir'], 'group_other_cities' => true]);
        $customFeaturingAnkara = Directory::create(['name' => 'Ankaralı', 'slug' => 'ankarali', 'domain' => 'ankarali.test', 'status' => 'active', 'geography_mode' => 'custom', 'featured_city_slugs' => ['ankara', 'konya'], 'group_other_cities' => false]);

        $campaign = Campaign::create(['directory_id' => $source->id, 'company_id' => $company->id, 'name' => 'Plan', 'total_directories' => 100, 'daily_limit' => 10, 'status' => 'draft']);

        $result = app(CampaignPlanService::class)->generate($campaign);

        $planned = $campaign->items()->pluck('directory_id')->all();
        $this->assertEqualsCanonicalizing([$national->id, $ankaraLocal->id, $customWithOthers->id, $customFeaturingAnkara->id], $planned);
        $this->assertNotContains($tekirdagLocal->id, $planned, 'Ankara firması Tekirdağ rehberine eklenmemeli');
        $this->assertNotContains($trakya->id, $planned, 'Ankara firması Trakya rehberine eklenmemeli');
        $this->assertSame(4, $result['created']);
    }

    public function test_campaign_plan_takes_the_target_count_after_filtering(): void
    {
        $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
        $city = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
        $company = Company::create(['name' => 'Firma', 'directory_id' => $source->id, 'category_id' => $category->id, 'city_id' => $city->id, 'status' => 'active']);

        foreach (range(1, 3) as $i) {
            Directory::create(['name' => "Yerel {$i}", 'slug' => "yerel-{$i}", 'domain' => "yerel{$i}.test", 'status' => 'active', 'geography_mode' => 'local', 'primary_city_slug' => 'edirne']);
        }
        foreach (range(1, 5) as $i) {
            Directory::create(['name' => "Ulusal {$i}", 'slug' => "ulusal-{$i}", 'domain' => "ulusal{$i}.test", 'status' => 'active', 'geography_mode' => 'national']);
        }

        $campaign = Campaign::create(['directory_id' => $source->id, 'company_id' => $company->id, 'name' => 'Plan', 'total_directories' => 4, 'daily_limit' => 10, 'status' => 'draft']);
        app(CampaignPlanService::class)->generate($campaign);

        $this->assertSame(4, $campaign->items()->count(), 'Uymayan rehberler hedefin sayısını azaltmamalı');
        $this->assertSame(0, $campaign->items()->whereIn('directory_id', Directory::where('geography_mode', 'local')->pluck('id'))->count());
    }
}
