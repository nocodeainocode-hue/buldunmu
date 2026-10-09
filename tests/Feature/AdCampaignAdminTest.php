<?php

namespace Tests\Feature;

use App\Filament\Resources\AdCampaigns\Pages\CreateAdCampaign;
use App\Filament\Resources\AdCampaigns\Pages\EditAdCampaign;
use App\Models\AdCampaign;
use App\Models\City;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class AdCampaignAdminTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): void
    {
        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));
    }

    public function test_seeded_house_ads_can_be_edited_and_retargeted_to_another_city(): void
    {
        $tekirdag = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
        $ankara = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $this->artisan('ads:seed-house')->assertSuccessful();
        $regional = AdCampaign::where('link_url', 'https://suaritma59.com')->firstOrFail();

        $this->admin();

        Livewire::test(EditAdCampaign::class, ['record' => $regional->getKey()])
            ->fillForm([
                'headline' => 'Ankara için yeni başlık',
                'link_url' => 'https://ornek-reklamveren.com',
                'type' => 'paid',
                'city_ids' => [$ankara->id],
            ])
            ->call('save')
            ->assertHasNoFormErrors();

        $regional->refresh();
        $this->assertSame('Ankara için yeni başlık', $regional->headline);
        $this->assertSame('paid', $regional->type);
        $this->assertSame([$ankara->id], array_map('intval', $regional->city_ids));
        $this->assertNotContains($tekirdag->id, $regional->city_ids);
    }

    public function test_admin_can_create_an_ad_without_any_city_targeting(): void
    {
        $this->admin();

        Livewire::test(CreateAdCampaign::class)
            ->fillForm([
                'name' => 'Şehirsiz reklam',
                'type' => 'paid',
                'status' => 'active',
                'placements' => ['top'],
                'headline' => 'Her yerde görünür',
                'link_url' => 'https://example.com',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $ad = AdCampaign::where('name', 'Şehirsiz reklam')->firstOrFail();
        $this->assertEmpty($ad->city_ids);
        $this->assertSame(['top'], $ad->placements);
    }

    public function test_panel_dates_are_entered_in_istanbul_time_and_stored_as_utc(): void
    {
        $this->admin();

        Livewire::test(CreateAdCampaign::class)
            ->fillForm([
                'name' => 'Saat dilimi',
                'type' => 'house',
                'status' => 'active',
                'placements' => ['top'],
                'headline' => 'Saat testi',
                'link_url' => 'https://example.com',
                'starts_at' => '2026-10-09 11:59:00',
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        $ad = AdCampaign::where('name', 'Saat dilimi')->firstOrFail();

        // Panelde girilen 11:59 (Türkiye) = 08:59 UTC; uygulama/DB UTC kalır.
        $this->assertSame('2026-10-09 08:59:00', $ad->starts_at->utc()->format('Y-m-d H:i:s'));
        $this->assertSame('UTC', config('app.timezone'));

        // Türkiye saatiyle 11:59'da reklam yayında sayılmalı; 11:58'de henüz değil.
        $this->travelTo(\Carbon\Carbon::parse('2026-10-09 08:58:30', 'UTC'));
        $this->assertFalse($ad->fresh()->isLive());
        $this->travelTo(\Carbon\Carbon::parse('2026-10-09 09:00:00', 'UTC'));
        $this->assertTrue($ad->fresh()->isLive());
    }
}
