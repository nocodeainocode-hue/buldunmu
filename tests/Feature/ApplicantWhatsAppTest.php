<?php

namespace Tests\Feature;

use App\Jobs\SendApplicantWhatsApp;
use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\ListingRequest;
use App\Support\ApplicantWhatsAppMessage;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class ApplicantWhatsAppTest extends TestCase
{
    use RefreshDatabase;

    private Directory $firmondo;

    protected function setUp(): void
    {
        parent::setUp();

        // Telegram bildirimi bu testlerin HTTP sayımına karışmasın.
        config(['services.telegram.bot_token' => null, 'services.telegram.chat_id' => null]);
        config(['services.waha' => ['url' => 'http://127.0.0.1:3000', 'session' => 'test-oturum', 'api_key' => 'test-anahtar', 'partial_delay_minutes' => 15, 'delay_minutes' => 2]]);
        $this->firmondo = Directory::create(['name' => 'Firmondo', 'slug' => 'firmondo', 'domain' => 'firmondo.com.tr', 'status' => 'active']);
    }

    private function listing(array $attributes = [], ?Directory $directory = null): ListingRequest
    {
        $category = Category::firstOrCreate(['slug' => 'kuafor'], ['name' => 'Kuaför', 'status' => 'active']);
        $city = City::firstOrCreate(['slug' => 'kayseri'], ['name' => 'Kayseri']);

        return ListingRequest::create($attributes + [
            'company_name' => 'Özgün Kuaför', 'contact_name' => 'Ayşe Yılmaz', 'phone' => '0532 412 55 66',
            'category_id' => $category->id, 'city_id' => $city->id, 'directory_id' => ($directory ?? $this->firmondo)->id,
            'status' => 'new', 'source' => 'owner_registration', 'is_partial' => false,
        ]);
    }

    public function test_jobs_are_queued_with_a_longer_wait_for_partial_applications(): void
    {
        Queue::fake();

        $complete = $this->listing();
        $partial = $this->listing(['company_name' => 'Yarım Kuaför', 'phone' => '0533 111 22 33', 'is_partial' => true]);

        Queue::assertPushed(SendApplicantWhatsApp::class, fn ($job) => $job->listingRequestId === $complete->id
            && $job->delay->diffInMinutes(now(), true) <= 2.1 && $job->delay->diffInMinutes(now(), true) >= 1.9);
        Queue::assertPushed(SendApplicantWhatsApp::class, fn ($job) => $job->listingRequestId === $partial->id
            && $job->delay->diffInMinutes(now(), true) >= 14.9);

        // Yarım başvuru tamamlanınca 2 dakikalık yeni bir iş eklenir.
        $partial->update(['is_partial' => false]);
        Queue::assertPushed(SendApplicantWhatsApp::class, 3);
    }

    public function test_nothing_is_queued_when_waha_is_not_configured(): void
    {
        Queue::fake();
        config(['services.waha.api_key' => null]);

        $this->listing();

        Queue::assertNothingPushed();
    }

    public function test_message_carries_the_directory_the_applicant_came_from(): void
    {
        Http::fake(['127.0.0.1:3000/*' => Http::response(['id' => 'ok'])]);
        $buldunmu = Directory::create(['name' => 'Buldun mu?', 'slug' => 'buldunmu', 'domain' => 'buldunmu.com.tr', 'status' => 'active']);
        Queue::fake();
        $fromBuldunmu = $this->listing([], $buldunmu);
        $fromFirmondo = $this->listing(['company_name' => 'Erciyes Oto', 'phone' => '0544 210 33 21']);

        (new SendApplicantWhatsApp($fromBuldunmu->id))->handle(app(\App\Services\WahaClient::class));
        (new SendApplicantWhatsApp($fromFirmondo->id))->handle(app(\App\Services\WahaClient::class));

        Http::assertSent(fn ($r) => $r['chatId'] === '905324125566@c.us' && $r['session'] === 'test-oturum'
            && $r->hasHeader('X-Api-Key', 'test-anahtar')
            && str_contains($r['text'], '*Buldun mu?* (buldunmu.com.tr)') && str_contains($r['text'], 'Merhaba Ayşe Yılmaz,')
            && ! str_contains($r['text'], 'Firmondo'));
        Http::assertSent(fn ($r) => $r['chatId'] === '905442103321@c.us' && str_contains($r['text'], '*Firmondo* (firmondo.com.tr)')
            && str_contains($r['text'], '*Erciyes Oto*'));
    }

    public function test_partial_that_completes_before_the_send_gets_the_completed_message_once(): void
    {
        Http::fake(['127.0.0.1:3000/*' => Http::response(['id' => 'ok'])]);
        Queue::fake();
        $listing = $this->listing(['is_partial' => true]);
        $listing->update(['is_partial' => false]);

        // Önce tamamlanma işi (2 dk), sonra yarım iş (15 dk) çalışır: yalnızca bir mesaj, "başvurunuz alındı".
        (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));
        (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));

        Http::assertSentCount(1);
        Http::assertSent(fn ($r) => str_contains($r['text'], 'başvurusu bize ulaştı') && ! str_contains($r['text'], 'son adım tamamlanmadı'));
    }

    public function test_still_partial_application_gets_the_continue_message_with_the_directory_link(): void
    {
        Http::fake(['127.0.0.1:3000/*' => Http::response(['id' => 'ok'])]);
        Queue::fake();
        $listing = $this->listing(['is_partial' => true, 'contact_name' => null]);

        (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));

        Http::assertSent(fn ($r) => str_starts_with($r['text'], 'Merhaba,') && str_contains($r['text'], 'son adım tamamlanmadı')
            && str_contains($r['text'], 'https://firmondo.com.tr/firma-kayit'));
    }

    public function test_same_phone_gets_one_message_a_day_landlines_and_rejected_are_skipped(): void
    {
        Http::fake(['127.0.0.1:3000/*' => Http::response(['id' => 'ok'])]);
        Queue::fake();
        $first = $this->listing();
        $samePhone = $this->listing(['company_name' => 'İkinci Şube']);
        $landline = $this->listing(['company_name' => 'Sabit Hat', 'phone' => '0352 222 14 37']);
        $rejected = $this->listing(['company_name' => 'Reddedilen', 'phone' => '0535 000 11 22', 'status' => 'rejected']);

        foreach ([$first, $samePhone, $landline, $rejected] as $listing) {
            (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));
        }

        Http::assertSentCount(1);
        $this->assertNull(ApplicantWhatsAppMessage::phone($landline));
        $this->assertSame('905324125566', ApplicantWhatsAppMessage::phone($first));
    }

    public function test_failed_send_releases_the_lock_so_a_retry_can_send(): void
    {
        Queue::fake();
        $listing = $this->listing();
        Http::fakeSequence('127.0.0.1:3000/*')->push(['error' => 'down'], 500)->push(['id' => 'ok'], 200);

        try {
            (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));
            $this->fail('İlk deneme hata vermeliydi.');
        } catch (\RuntimeException) {
        }
        (new SendApplicantWhatsApp($listing->id))->handle(app(\App\Services\WahaClient::class));

        Http::assertSentCount(2);
    }
}
