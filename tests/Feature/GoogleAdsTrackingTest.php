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
            ->assertSee("ad_personalization: 'denied'", false)
            ->assertSee('test_click_123', false)
            ->assertDontSee('private@example.com', false)
            ->assertDontSee('ads-consent-banner', false);
        $this->get('https://diger.test/firma-kayit')->assertOk()
            ->assertDontSee('gtag/js?id=AW-18054234044', false)
            ->assertDontSee('ads-consent-banner', false);
    }
}
