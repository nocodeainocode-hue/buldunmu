<?php

namespace App\Observers;

use App\Models\ListingRequest;
use App\Services\TelegramCompanyApplicationNotifier;
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
                app(TelegramCompanyApplicationNotifier::class)->send($listing);
            } catch (Throwable $e) {
                // Never include the exception message: an HTTP error may contain
                // the bot token in its URL.
                Log::warning('Yeni firma başvurusu Telegram bildirimi gönderilemedi.', [
                    'listing_request_id' => $listing->id,
                    'exception' => $e::class,
                ]);
            }
        });
    }
}
