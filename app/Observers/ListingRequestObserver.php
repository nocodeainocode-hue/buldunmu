<?php

namespace App\Observers;

use App\Jobs\SendApplicantWhatsApp;
use App\Models\ListingRequest;
use App\Services\TelegramCompanyApplicationNotifier;
use App\Services\WahaClient;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListingRequestObserver
{
    /**
     * Notify the site admin when a new company application arrives.
     * Covers every entry point (manual add, claim, owner registration)
     * because all of them create a ListingRequest row.
     */
    public function created(ListingRequest $listing): void
    {
        if ($listing->status !== 'new') {
            return;
        }

        // Defer to after the surrounding transaction commits so a rolled-back
        // registration never produces a phantom notification. Delivery stays
        // synchronous (no queue worker required).
        DB::afterCommit(function () use ($listing) {
            try {
                app(TelegramCompanyApplicationNotifier::class)->send($listing, $listing->is_partial ? 'partial' : null);
            } catch (Throwable $e) {
                // Never include the exception message: an HTTP error may contain
                // the bot token in its URL.
                Log::warning('Yeni firma başvurusu Telegram bildirimi gönderilemedi.', [
                    'listing_request_id' => $listing->id,
                    'exception' => $e::class,
                ]);
            }

            // Başvuru sahibine WhatsApp: 1. adımda kalan biri kaydı tamamlayabilsin diye daha uzun beklenir.
            $this->queueWhatsApp($listing, $listing->is_partial
                ? (int) config('services.waha.partial_delay_minutes', 15)
                : (int) config('services.waha.delay_minutes', 2));
        });
    }

    private function queueWhatsApp(ListingRequest $listing, int $minutes): void
    {
        if (! app(WahaClient::class)->configured()) {
            return;
        }

        try {
            SendApplicantWhatsApp::dispatch($listing->id)->delay(now()->addMinutes(max(0, $minutes)));
        } catch (Throwable $e) {
            Log::warning('Başvuru WhatsApp işi kuyruğa alınamadı.', ['listing_request_id' => $listing->id, 'exception' => $e::class]);
        }
    }

    /** Yarım kalan başvuru 2. adımla tamamlandığında yöneticiye bir kez daha haber verir. */
    public function updated(ListingRequest $listing): void
    {
        if (! $listing->wasChanged('is_partial') || $listing->is_partial) {
            return;
        }

        DB::afterCommit(function () use ($listing) {
            try {
                app(TelegramCompanyApplicationNotifier::class)->send($listing, 'completed');
            } catch (Throwable $e) {
                Log::warning('Tamamlanan firma başvurusu Telegram bildirimi gönderilemedi.', [
                    'listing_request_id' => $listing->id,
                    'exception' => $e::class,
                ]);
            }

            // Yarım iş henüz çalışmadıysa bu iş önce çalışır ve "başvurunuz alındı" gider; diğeri kilit yüzünden atlanır.
            $this->queueWhatsApp($listing, (int) config('services.waha.delay_minutes', 2));
        });
    }
}
