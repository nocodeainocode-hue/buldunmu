<?php

namespace App\Services;

use App\Models\Directory;
use App\Models\ListingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class TelegramCompanyApplicationNotifier
{
    /** @param string|null $stage null = standart, 'partial' = yarım kalan, 'completed' = yarım başvuru tamamlandı */
    public function send(ListingRequest $listing, ?string $stage = null): void
    {
        $token = config('services.telegram.bot_token');
        $chatId = config('services.telegram.chat_id');

        if (blank($token) || blank($chatId)) {
            Log::warning('Yeni firma başvurusu Telegram bildirimi yapılandırılmadığı için gönderilemedi.', [
                'listing_request_id' => $listing->id,
            ]);

            return;
        }

        $response = Http::timeout(5)->post("https://api.telegram.org/bot{$token}/sendMessage", [
            'chat_id' => $chatId,
            'text' => $this->message($listing, $stage),
        ]);

        if (! $response->successful() || $response->json('ok') !== true) {
            Log::warning('Yeni firma başvurusu Telegram bildirimi gönderilemedi.', [
                'listing_request_id' => $listing->id,
                'http_status' => $response->status(),
                'telegram_error_code' => $response->json('error_code'),
            ]);
        }
    }

    private function message(ListingRequest $listing, ?string $stage = null): string
    {
        $directory = $listing->directory_id
            ? Directory::withoutGlobalScope('directory')->find($listing->directory_id)
            : null;

        $type = match ($listing->source) {
            'claim' => 'Profil sahiplenme',
            'owner_registration' => 'Firma sahibi kaydı',
            default => 'Firma ekleme',
        };

        $companyName = Str::limit(trim(preg_replace('/\s+/u', ' ', $listing->company_name)), 180);
        $directoryName = Str::limit(trim(preg_replace('/\s+/u', ' ', $directory?->name ?? 'Bilinmiyor')), 120);
        $adminUrl = rtrim(config('services.telegram.admin_url'), '/');

        $title = match ($stage) {
            'partial' => 'Yarım kalan firma başvurusu (2. adım bekleniyor)',
            'completed' => 'Firma başvurusu tamamlandı',
            default => 'Yeni firma başvurusu',
        };

        $lines = [
            $title,
            'Rehber: '.$directoryName,
            'Firma: '.$companyName,
            'Talep: '.$type,
        ];

        // Yarım kalan başvuruda telefon, geri dönüş için asıl bilgidir.
        if ($stage === 'partial') {
            $lines[] = 'Telefon: '.Str::limit(trim((string) $listing->phone), 40);
            if (filled($listing->whatsapp)) {
                $lines[] = 'WhatsApp: '.Str::limit(trim((string) $listing->whatsapp), 40);
            }
            if (filled($listing->utm_source)) {
                $lines[] = 'Kaynak: '.Str::limit($listing->utm_source.' / '.($listing->utm_campaign ?: '-'), 80);
            }
        }

        $lines[] = 'Başvuru: '.$adminUrl.'/listing-requests/'.$listing->id.'/open';

        return implode("\n", $lines);
    }
}
