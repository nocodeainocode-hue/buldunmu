<?php

namespace Tests\Feature;

use App\Models\Directory;
use App\Models\ListingRequest;
use App\Models\User;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class TelegramCompanyApplicationNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // These tests need real commits so DB::afterCommit callbacks run.
        // RefreshDatabase wraps tests in a transaction; DatabaseMigrations
        // rolls back legacy SQLite migrations that cannot be reversed here.
        $this->artisan('migrate:fresh');
    }

    public function test_a_committed_application_sends_one_private_message_with_an_admin_link(): void
    {
        config()->set('services.telegram.bot_token', 'test-bot-token');
        config()->set('services.telegram.chat_id', '123456789');
        config()->set('services.telegram.admin_url', 'https://buldunmu.com.tr/admin');
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => true], 200)]);

        $directory = Directory::create([
            'name' => 'İzmir Rehberi',
            'slug' => 'izmir-rehberi',
            'domain' => 'izmir.test',
            'status' => 'active',
        ]);

        $listing = DB::transaction(fn () => ListingRequest::create([
            'directory_id' => $directory->id,
            'company_name' => 'Özgün Atölye',
            'phone' => '0555 123 45 67',
            'email' => 'ornek@example.test',
            'source' => 'owner_registration',
            'status' => 'new',
        ]));

        Http::assertSentCount(1);
        Http::assertSent(function ($request) use ($listing) {
            $text = $request['text'];

            return $request->url() === 'https://api.telegram.org/bottest-bot-token/sendMessage'
                && $request['chat_id'] === '123456789'
                && str_contains($text, 'Rehber: İzmir Rehberi')
                && str_contains($text, 'Firma: Özgün Atölye')
                && str_contains($text, 'Talep: Firma sahibi kaydı')
                && str_contains($text, "https://buldunmu.com.tr/admin/listing-requests/{$listing->id}/open")
                && ! str_contains($text, '0555 123 45 67')
                && ! str_contains($text, 'ornek@example.test');
        });
    }

    public function test_a_rolled_back_application_does_not_send_a_message(): void
    {
        config()->set('services.telegram.bot_token', 'test-bot-token');
        config()->set('services.telegram.chat_id', '123456789');
        Http::fake();

        DB::beginTransaction();
        ListingRequest::create(['company_name' => 'İptal Edilen Firma', 'status' => 'new']);
        DB::rollBack();

        Http::assertNothingSent();
        $this->assertDatabaseMissing('listing_requests', ['company_name' => 'İptal Edilen Firma']);
    }

    public function test_missing_bot_configuration_keeps_the_application_and_logs_a_warning(): void
    {
        config()->set('services.telegram.bot_token', null);
        config()->set('services.telegram.chat_id', null);
        Http::fake();
        Log::spy();

        ListingRequest::create(['company_name' => 'Ayarsız Firma', 'status' => 'new']);

        $this->assertDatabaseHas('listing_requests', ['company_name' => 'Ayarsız Firma']);
        Http::assertNothingSent();
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_telegram_failure_keeps_the_application_and_logs_a_warning(): void
    {
        config()->set('services.telegram.bot_token', 'test-bot-token');
        config()->set('services.telegram.chat_id', '123456789');
        Http::fake(['api.telegram.org/*' => Http::response(['ok' => false, 'error_code' => 403], 403)]);
        Log::spy();

        ListingRequest::create(['company_name' => 'Yanıt Hatası Firması', 'status' => 'new']);

        $this->assertDatabaseHas('listing_requests', ['company_name' => 'Yanıt Hatası Firması']);
        Http::assertSentCount(1);
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_connection_failure_keeps_the_application(): void
    {
        config()->set('services.telegram.bot_token', 'test-bot-token');
        config()->set('services.telegram.chat_id', '123456789');
        Http::fake(fn () => throw new ConnectionException('Connection failed'));
        Log::spy();

        ListingRequest::create(['company_name' => 'Bağlantı Hatası Firması', 'status' => 'new']);

        $this->assertDatabaseHas('listing_requests', ['company_name' => 'Bağlantı Hatası Firması']);
        Log::shouldHaveReceived('warning')->once();
    }

    public function test_reviewed_records_do_not_send_a_message(): void
    {
        config()->set('services.telegram.bot_token', 'test-bot-token');
        config()->set('services.telegram.chat_id', '123456789');
        Http::fake();

        ListingRequest::create(['company_name' => 'İncelenmiş Firma', 'status' => 'reviewed']);

        Http::assertNothingSent();
    }

    public function test_notification_link_selects_the_application_directory(): void
    {
        $other = Directory::create(['name' => 'Diğer', 'slug' => 'diger', 'domain' => 'diger.test', 'status' => 'active']);
        $target = Directory::create(['name' => 'Hedef', 'slug' => 'hedef', 'domain' => 'hedef.test', 'status' => 'active']);
        $listing = ListingRequest::create(['company_name' => 'Hedef Firma', 'directory_id' => $target->id, 'status' => 'new']);

        $this->actingAs(User::factory()->create(['is_admin' => true]))
            ->withSession(['current_directory_id' => $other->id])
            ->get("/admin/listing-requests/{$listing->id}/open")
            ->assertRedirect("/admin/listing-requests/{$listing->id}/edit")
            ->assertSessionHas('current_directory_id', $target->id);

        $this->get("/admin/listing-requests/{$listing->id}/edit")->assertOk();
    }

    public function test_notification_link_rejects_non_admin_accounts(): void
    {
        $listing = ListingRequest::create(['company_name' => 'Korunan Firma', 'status' => 'new']);

        $this->actingAs(User::factory()->create(['is_admin' => false]))
            ->get("/admin/listing-requests/{$listing->id}/open")
            ->assertForbidden();
    }
}
