<?php

namespace App\Observers;

use App\Mail\NewCompanyApplicationMail;
use App\Models\ListingRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
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

        $recipient = config('services.admin.email');
        if (blank($recipient)) {
            return;
        }

        // Defer to after the surrounding transaction commits so a rolled-back
        // registration never produces a phantom notification. Delivery stays
        // synchronous (no queue worker required).
        DB::afterCommit(function () use ($listing, $recipient) {
            try {
                Mail::to($recipient)->send(new NewCompanyApplicationMail($listing));
            } catch (Throwable $e) {
                // A mail failure must never block the company application itself.
                Log::warning('Yeni firma başvurusu bildirimi gönderilemedi: '.$e->getMessage(), [
                    'listing_request_id' => $listing->id,
                ]);
            }
        });
    }
}
