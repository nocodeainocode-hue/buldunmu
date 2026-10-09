<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\ClaimInvite;
use App\Models\Company;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use App\Services\ClaimInviteGenerator;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ClaimInviteTest extends TestCase
{
    use RefreshDatabase;

    private Directory $directory;
    private Category $category;
    private City $city;

    protected function setUp(): void
    {
        parent::setUp();

        $this->directory = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldun-mu', 'domain' => 'buldunmu.test', 'status' => 'active']);
        $this->category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $this->city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
    }

    private function company(string $name, ?string $phone, array $extra = []): Company
    {
        return Company::create($extra + [
            'name' => $name, 'directory_id' => $this->directory->id, 'category_id' => $this->category->id,
            'city_id' => $this->city->id, 'status' => 'active', 'phone' => $phone,
        ]);
    }

    private function generate(int $limit = 50, bool $mobileOnly = true, ?int $cityId = null): int
    {
        return app(ClaimInviteGenerator::class)->generate($this->directory->id, $cityId, null, $limit, $mobileOnly, ClaimInvite::DEFAULT_TEMPLATE);
    }

    private function host(): self
    {
        return $this->withServerVariables(['HTTP_HOST' => 'buldunmu.test']);
    }

    public function test_generator_creates_invites_with_link_and_message_for_mobile_numbers_only(): void
    {
        $mobile = $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->company('Sabit Klinik', '0282 123 45 67');
        $this->company('Telefonsuz Klinik', null);

        $this->assertSame(1, $this->generate());

        $invite = ClaimInvite::firstOrFail();
        $this->assertSame($mobile->id, $invite->company_id);
        $this->assertSame('ready', $invite->status);
        $this->assertStringContainsString('Ayşe Klinik', $invite->message);
        $this->assertStringContainsString('Buldun mu?', $invite->message);
        $this->assertStringContainsString('https://buldunmu.test/s/'.$invite->token, $invite->message);
        $this->assertStringContainsString('İSTEMİYORUM', $invite->message);
    }

    public function test_generator_skips_invited_owned_duplicate_phone_and_opted_out_numbers_and_respects_limit(): void
    {
        $first = $this->company('Bir', '0532 123 45 67');
        $this->company('İki', '0533 234 56 78');
        $this->company('Üç', '0534 345 67 89');
        $this->company('Aynı Numara', '+90 532 123 45 67');

        $this->assertSame(2, $this->generate(limit: 2));
        // İkinci çalıştırma yalnızca henüz davet edilmeyen firmaları üretir; aynı numara tekrar davet edilmez.
        $this->assertSame(1, $this->generate());
        $this->assertSame(0, $this->generate());
        $this->assertSame(3, ClaimInvite::count());

        $invite = ClaimInvite::where('company_id', $first->id)->first();
        $invite->forceFill(['status' => 'opted_out', 'opted_out_at' => now()])->save();
        $this->company('Yeni Kayıt', '0532 123 45 67');
        $this->assertSame(0, $this->generate());
    }

    public function test_clicking_the_link_records_the_visit_and_redirects_to_the_claim_page_with_utm(): void
    {
        $company = $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();

        $this->host()->withHeader('User-Agent', 'Mozilla/5.0 Chrome/130')->get('http://buldunmu.test/s/'.$invite->token)
            ->assertRedirect(route('companies.claim', ['company' => $company->slug, 'utm_source' => 'davet', 'utm_medium' => 'whatsapp', 'utm_campaign' => 'sahiplen']));

        $invite->refresh();
        $this->assertSame('clicked', $invite->status);
        $this->assertSame(1, $invite->clicks);
        $this->assertNotNull($invite->first_clicked_at);

        // Botlar (önizleme taramaları) tıklama sayılmaz.
        $this->host()->withHeader('User-Agent', 'WhatsApp/2.23 Bot')->get('http://buldunmu.test/s/'.$invite->token)->assertRedirect();
        $this->assertSame(1, $invite->fresh()->clicks);
    }

    public function test_link_does_not_work_on_another_directory_or_with_an_unknown_token(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();
        Directory::create(['name' => 'Diğer', 'slug' => 'diger', 'domain' => 'diger.test', 'status' => 'active']);

        $this->withServerVariables(['HTTP_HOST' => 'diger.test'])->get('http://diger.test/s/'.$invite->token)->assertNotFound();
        $this->host()->get('http://buldunmu.test/s/yokboyle1')->assertNotFound();
    }

    public function test_opted_out_firms_are_not_tracked_when_they_open_the_link(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();
        $invite->forceFill(['status' => 'opted_out'])->save();

        $this->host()->get('http://buldunmu.test/s/'.$invite->token)->assertRedirect();

        $this->assertSame('opted_out', $invite->fresh()->status);
        $this->assertSame(0, $invite->fresh()->clicks);
    }

    public function test_submitting_the_claim_form_after_the_link_marks_the_invite_claimed(): void
    {
        $company = $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();

        $this->host()->get('http://buldunmu.test/s/'.$invite->token);

        $this->host()->post('http://buldunmu.test/firma-ekle', [
            'claim_company_id' => $company->id,
            'company_name' => 'Ayşe Klinik',
            'phone' => '0532 123 45 67',
            'category_id' => $this->category->id,
            'city_id' => $this->city->id,
        ])->assertRedirect();

        $invite->refresh();
        $this->assertSame('claimed', $invite->status);
        $this->assertNotNull($invite->claimed_at);
        $this->assertSame(ListingRequest::withoutGlobalScope('directory')->latest('id')->value('id'), $invite->listing_request_id);
    }

    public function test_send_route_marks_the_invite_sent_and_opens_whatsapp_with_the_message(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();
        $admin = User::factory()->create(['is_admin' => true]);

        $response = $this->actingAs($admin)->get('/admin/claim-invites/'.$invite->id.'/send');

        $response->assertRedirect();
        $location = $response->headers->get('Location');
        $this->assertStringStartsWith('https://wa.me/905321234567?text=', $location);
        $this->assertStringContainsString(rawurlencode('Ayşe Klinik'), $location);
        $this->assertSame('sent', $invite->fresh()->status);
        $this->assertNotNull($invite->fresh()->sent_at);
    }

    public function test_send_route_and_invite_list_are_admin_only(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();
        $owner = User::factory()->create(['is_admin' => false]);

        $this->actingAs($owner)->get('/admin/claim-invites/'.$invite->id.'/send')->assertForbidden();
        $this->actingAs($owner)->get('/admin/claim-invites')->assertForbidden();

        $admin = User::factory()->create(['is_admin' => true]);
        $this->actingAs($admin)->get('/admin/claim-invites')->assertOk()->assertSee('Davet oluştur');
    }

    public function test_admin_can_generate_a_batch_from_the_list_page(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->company('Veli Klinik', '0533 234 56 78');
        $this->actingAs(User::factory()->create(['is_admin' => true]));

        \Livewire\Livewire::test(\App\Filament\Resources\ClaimInvites\Pages\ListClaimInvites::class)
            ->callAction('generate', [
                'directory_id' => $this->directory->id, 'limit' => 1, 'mobile_only' => true,
                'template' => ClaimInvite::DEFAULT_TEMPLATE,
            ])
            ->assertHasNoActionErrors();

        $this->assertSame(1, ClaimInvite::count());
    }

    public function test_link_leads_all_the_way_to_the_claim_page(): void
    {
        $this->company('Ayşe Klinik', '0532 123 45 67');
        $this->generate();
        $invite = ClaimInvite::firstOrFail();

        $this->host()->withHeader('User-Agent', 'Mozilla/5.0 Chrome/130')
            ->followingRedirects()
            ->get('http://buldunmu.test/s/'.$invite->token)
            ->assertOk()
            ->assertSee('Ayşe Klinik profilini sahiplenin');
    }

    public function test_claim_page_picks_the_company_of_the_current_directory_when_slugs_repeat(): void
    {
        $other = Directory::create(['name' => 'Diğer', 'slug' => 'diger', 'domain' => 'diger.test', 'status' => 'active']);
        // Başka rehberdeki aynı adresli kayıt daha düşük kimlikle oluşur.
        $foreign = $this->company('Ayşe Klinik', '0532 111 22 33', ['directory_id' => $other->id, 'slug' => 'ayse-klinik']);
        $own = $this->company('Ayşe Klinik', '0532 123 45 67', ['slug' => 'ayse-klinik']);

        $this->assertSame($foreign->slug, $own->slug);

        $this->host()->get('http://buldunmu.test/firma/'.$own->slug.'/sahiplen')
            ->assertOk()
            ->assertSee('name="claim_company_id" value="'.$own->id.'"', false);
    }
}
