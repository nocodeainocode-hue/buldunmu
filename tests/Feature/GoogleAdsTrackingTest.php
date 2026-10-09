<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GoogleAdsTrackingTest extends TestCase
{
    use RefreshDatabase;

    public function test_google_tag_is_scoped_to_its_directory_and_has_consent_controls(): void
    {
        Directory::create(['name' => '81 İl Firmalar', 'slug' => '81-il', 'domain' => '81ilfirmalar.com.tr', 'status' => 'active']);
        Directory::create(['name' => 'Diğer Rehber', 'slug' => 'diger', 'domain' => 'diger.test', 'status' => 'active']);

        $this->get('https://81ilfirmalar.com.tr/firma-kayit?gclid=test_click_123&email=private@example.com')->assertOk()
            ->assertSee('gtag/js?id=AW-18054234044', false)
            ->assertSee('Reklam ölçümü için Google çerezlerine izin verir misiniz')
            ->assertSee("ad_personalization: 'denied'", false)
            ->assertSee('test_click_123', false)
            ->assertDontSee('private@example.com', false)
            ->assertSee('data-registration="null"', false);
        $this->get('https://diger.test/firma-kayit')->assertOk()
            ->assertDontSee('gtag/js?id=AW-18054234044', false)
            ->assertDontSee('ads-consent-banner', false);
    }

    public function test_dashboard_only_emits_a_registration_event_for_a_matching_successful_signup(): void
    {
        $directory = Directory::create(['name' => '81 İl Firmalar', 'slug' => '81-il', 'domain' => '81ilfirmalar.com.tr', 'status' => 'active']);
        config(['tracking.google_ads' => [$directory->domain => ['tag_id' => 'AW-18054234044', 'registration_label' => 'test_registration_label']]]);
        $user = User::factory()->create();
        $this->actingAs($user)->withSession(['google_ads_registration' => [
            'directory_id' => $directory->id,
            'transaction_id' => 'conversion-unique-123',
        ]])->get('https://81ilfirmalar.com.tr/panel')->assertOk()
            ->assertSee('AW-18054234044', false)
            ->assertSee('test_registration_label', false)
            ->assertSee('conversion-unique-123', false);

        $this->withSession(['google_ads_registration' => null])
            ->get('https://81ilfirmalar.com.tr/panel')->assertOk()
            ->assertDontSee('test_registration_label', false)
            ->assertSee('data-registration="null"', false);

        $this->withSession(['google_ads_registration' => ['directory_id' => $directory->id + 1, 'transaction_id' => 'wrong-directory']])
            ->get('https://81ilfirmalar.com.tr/panel')->assertOk()
            ->assertDontSee('wrong-directory', false);
    }
}
