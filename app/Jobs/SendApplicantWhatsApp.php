<?php

namespace App\Jobs;

use App\Models\Directory;
use App\Models\ListingRequest;
use App\Services\WahaClient;
use App\Support\ApplicantWhatsAppMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Başvuru sahibine, başvurunun geldiği rehberin adıyla WhatsApp mesajı gönderir.
 * Gecikmeli çalışır ve mesajı gönderim anındaki duruma göre seçer: 1. adımda kalan biri bu arada kaydı
 * tamamladıysa "kaydı tamamlayalım" yerine "başvurunuz alındı" gider. Aynı başvuruya bir kez, aynı numaraya
 * 24 saatte bir kez mesaj gider (yarım + tamamlandı olaylarından iki iş kuyruğa girse bile).
 */
class SendApplicantWhatsApp implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable;

    public int $tries = 3;

    public int $backoff = 120;

    public function __construct(public int $listingRequestId) {}

    public static function listingKey(int $id): string
    {
        return 'wa-applicant:listing:'.$id;
    }

    public static function phoneKey(string $phone): string
    {
        return 'wa-applicant:phone:'.$phone;
    }

    public function handle(WahaClient $waha): void
    {
        if (! $waha->configured()) {
            return;
        }

        $listing = ListingRequest::withoutGlobalScopes()->find($this->listingRequestId);
        if (! $listing || in_array($listing->status, ['rejected', 'spam'], true)) {
            return;
        }

        $directory = $listing->directory_id ? Directory::find($listing->directory_id) : null;
        $phone = ApplicantWhatsAppMessage::phone($listing);
        if (! $directory || ! $phone) {
            return;
        }

        $listingKey = self::listingKey($listing->id);
        if (! Cache::add($listingKey, 1, now()->addDays(30))) {
            return;
        }
        $phoneKey = self::phoneKey($phone);
        if (! Cache::add($phoneKey, 1, now()->addDay())) {
            return;
        }

        try {
            $waha->sendText($phone, ApplicantWhatsAppMessage::text($listing, $directory));
        } catch (Throwable $e) {
            // Tekrar denemede gönderilebilsin diye kilitler bırakılır.
            Cache::forget($listingKey);
            Cache::forget($phoneKey);
            Log::warning('Başvuru WhatsApp mesajı gönderilemedi.', ['listing_request_id' => $listing->id, 'exception' => $e::class]);

            throw $e;
        }

        Log::info('Başvuru WhatsApp mesajı gönderildi.', [
            'listing_request_id' => $listing->id,
            'kind' => ApplicantWhatsAppMessage::kind($listing),
            'directory' => $directory->domain,
        ]);
    }
}
