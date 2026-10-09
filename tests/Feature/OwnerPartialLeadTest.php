<?php

namespace Tests\Feature;

use App\Filament\Pages\AdAttributionReport;
use App\Filament\Resources\ListingRequests\Pages\ListListingRequests;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use Filament\Facades\Filament;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Client\Request as HttpRequest;
use Illuminate\Support\Facades\Http;
use Livewire\Livewire;
use Tests\TestCase;

class OwnerPartialLeadTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;
    private Category $category;
    private City $city;

    protected function setUp(): void
    {
        parent::setUp();

        config([
            'services.telegram.bot_token' => 'test-token',
            'services.telegram.chat_id' => '123',
            'services.telegram.admin_url' => 'https://admin.test/admin',
        ]);
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true])]);

        $this->directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
        $this->category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $this->city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
    }

    private function lead(array $overrides = [], array $session = [])
    {
        return $this->withSession($session)->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])
            ->postJson('http://buldunmu.test/firma-kayit/on-basvuru', $overrides + [
                'company_name' => 'Ayşe Diş Kliniği',
                'category_id' => $this->category->id,
                'city_id' => $this->city->id,
                'phone' => '0282 000 00 00',
                'whatsapp' => '0555 111 22 33',
            ]);
    }

    private function telegramTexts(): array
    {
        return Http::recorded()->map(fn (array $pair) => $pair[0] instanceof HttpRequest ? ($pair[0]['text'] ?? '') : '')->filter()->values()->all();
    }

    public function test_step_one_saves_a_partial_application_and_alerts_the_admin_with_the_phone(): void
    {
        $this->lead()->assertNoContent();

        $lead = ListingRequest::withoutGlobalScope('directory')->firstOrFail();
        $this->assertTrue($lead->is_partial);
        $this->assertSame('new', $lead->status);
        $this->assertSame('owner_registration', $lead->source);
        $this->assertSame($this->directory->id, (int) $lead->directory_id);
        $this->assertSame('Ayşe Diş Kliniği', $lead->company_name);
        $this->assertSame('0282 000 00 00', $lead->phone);
        $this->assertSame($this->city->id, (int) $lead->city_id);
        $this->assertNotEmpty($lead->lead_token);

        $texts = $this->telegramTexts();
        $this->assertCount(1, $texts);
        $this->assertStringContainsString('Yarım kalan firma başvurusu', $texts[0]);
        $this->assertStringContainsString('Telefon: 0282 000 00 00', $texts[0]);
        $this->assertStringContainsString('Ayşe Diş Kliniği', $texts[0]);
    }

    public function test_resubmitting_step_one_updates_the_same_lead_without_a_second_alert(): void
    {
        $this->lead();
        $token = ListingRequest::withoutGlobalScope('directory')->value('lead_token');

        $this->lead(['company_name' => 'Ayşe Diş Kliniği Yeni', 'phone' => '0282 000 00 01'], ['registration_lead_token' => $token])->assertNoContent();

        $this->assertSame(1, ListingRequest::withoutGlobalScope('directory')->count());
        $lead = ListingRequest::withoutGlobalScope('directory')->first();
        $this->assertSame('Ayşe Diş Kliniği Yeni', $lead->company_name);
        $this->assertSame('0282 000 00 01', $lead->phone);
        $this->assertCount(1, $this->telegramTexts(), 'Güncelleme yeni bildirim üretmemeli');
    }

    public function test_same_phone_within_a_day_reuses_the_lead_even_without_a_session(): void
    {
        $this->lead();
        $this->lead(['company_name' => 'Ayşe Diş Kliniği (düzeltme)']);

        $this->assertSame(1, ListingRequest::withoutGlobalScope('directory')->count());
        $this->assertSame('Ayşe Diş Kliniği (düzeltme)', ListingRequest::withoutGlobalScope('directory')->first()->company_name);
    }

    public function test_invalid_input_is_rejected_quietly_and_nothing_is_saved(): void
    {
        $this->lead(['phone' => '123'])->assertStatus(422);
        $this->lead(['company_name' => ''])->assertStatus(422);
        $this->lead(['city_id' => 99999])->assertStatus(422);

        $this->assertSame(0, ListingRequest::withoutGlobalScope('directory')->count());
    }

    public function test_ad_source_is_stored_on_the_partial_lead(): void
    {
        // JSON istekleri test istemcisinde çerezi yalnızca withCredentials() ile gönderir (tarayıcı fetch'i gibi).
        $this->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])
            ->withCredentials()
            ->withCookie('fr_attr', json_encode(['utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'ekim']))
            ->postJson('http://buldunmu.test/firma-kayit/on-basvuru', [
                'company_name' => 'Kaynaklı Firma', 'category_id' => $this->category->id,
                'city_id' => $this->city->id, 'phone' => '0282 111 11 11',
            ])->assertNoContent();

        $lead = ListingRequest::withoutGlobalScope('directory')->firstOrFail();
        $this->assertSame('google', $lead->utm_source);
        $this->assertSame('cpc', $lead->utm_medium);
        $this->assertSame('ekim', $lead->utm_campaign);
    }

    public function test_finishing_step_two_converts_the_same_record_and_notifies_completion(): void
    {
        $this->lead();
        $lead = ListingRequest::withoutGlobalScope('directory')->firstOrFail();

        $this->withSession(['registration_lead_token' => $lead->lead_token])
            ->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])
            ->post('http://buldunmu.test/firma-kayit', [
                'name' => 'Ayşe Yılmaz', 'email' => 'ayse@example.test',
                'password' => 'guvenli-parola', 'password_confirmation' => 'guvenli-parola',
                'company_name' => 'Ayşe Diş Kliniği', 'category_id' => $this->category->id,
                'city_id' => $this->city->id, 'phone' => '0282 000 00 00',
            ])->assertRedirect();

        $this->assertSame(1, ListingRequest::withoutGlobalScope('directory')->count(), 'İkinci başvuru kaydı açılmamalı');

        $lead->refresh();
        $this->assertFalse($lead->is_partial);
        $this->assertNull($lead->lead_token);
        $this->assertSame('Ayşe Yılmaz', $lead->contact_name);
        $this->assertSame('ayse@example.test', $lead->email);
        $this->assertNotNull($lead->claim_company_id);
        $this->assertSame('new', $lead->status);

        $texts = $this->telegramTexts();
        $this->assertCount(2, $texts);
        $this->assertStringContainsString('Yarım kalan firma başvurusu', $texts[0]);
        $this->assertStringContainsString('Firma başvurusu tamamlandı', $texts[1]);
    }

    public function test_registration_page_tells_visitors_their_details_are_saved_and_posts_step_one(): void
    {
        $this->withServerVariables(['HTTP_HOST' => 'buldunmu.test'])
            ->get('http://buldunmu.test/firma-kayit')
            ->assertOk()
            ->assertSee('firma bilgileriniz başvuru olarak kaydedilir', false)
            ->assertSee('on-basvuru', false)
            ->assertSee('goToSecondStep', false);
    }

    public function test_admin_cannot_approve_a_partial_lead_and_can_filter_them(): void
    {
        $this->lead();
        $partial = ListingRequest::withoutGlobalScope('directory')->firstOrFail();
        $complete = ListingRequest::withoutGlobalScope('directory')->create([
            'company_name' => 'Tamam Firma', 'phone' => '0282 222 22 22', 'directory_id' => $this->directory->id,
            'category_id' => $this->category->id, 'city_id' => $this->city->id, 'status' => 'new', 'source' => 'owner_registration',
        ]);

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        Livewire::test(ListListingRequests::class)
            ->assertTableActionHidden('approve', $partial)
            ->assertTableActionHidden('reject', $partial)
            ->assertTableActionVisible('approve', $complete)
            ->filterTable('is_partial', true)
            ->assertCanSeeTableRecords([$partial])
            ->assertCanNotSeeTableRecords([$complete]);
    }

    public function test_attribution_report_counts_partial_leads_per_source(): void
    {
        ListingRequest::withoutGlobalScope('directory')->create([
            'company_name' => 'Yarım 1', 'phone' => '0282 333 33 33', 'directory_id' => $this->directory->id, 'status' => 'new',
            'source' => 'owner_registration', 'is_partial' => true, 'utm_source' => 'google', 'utm_medium' => 'cpc', 'utm_campaign' => 'ekim',
        ]);
        ListingRequest::withoutGlobalScope('directory')->create([
            'company_name' => 'Yarım 2', 'phone' => '0282 444 44 44', 'directory_id' => $this->directory->id, 'status' => 'new',
            'source' => 'owner_registration', 'is_partial' => true,
        ]);

        $this->actingAs(User::factory()->create(['is_admin' => true]));
        Filament::setCurrentPanel(Filament::getPanel('admin'));

        $rows = collect(Livewire::test(AdAttributionReport::class)->instance()->getRows())->keyBy('source');

        $this->assertSame(1, $rows['google']['partial']);
        $this->assertSame(0, $rows['google']['signups']);
        $this->assertSame(1, $rows['Doğrudan']['partial']);
    }
}
