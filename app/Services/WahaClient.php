<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

/** Aynı sunucudaki WAHA (WhatsApp HTTP API) üzerinden metin mesajı gönderir. */
class WahaClient
{
    public function configured(): bool
    {
        return filled(config('services.waha.url')) && filled(config('services.waha.session')) && filled(config('services.waha.api_key'));
    }

    /** @param string $phone 90XXXXXXXXXX biçiminde, yalnızca rakam */
    public function sendText(string $phone, string $text): void
    {
        $response = Http::timeout(15)
            ->withHeaders(['X-Api-Key' => config('services.waha.api_key')])
            ->post(rtrim(config('services.waha.url'), '/').'/api/sendText', [
                'session' => config('services.waha.session'),
                'chatId' => $phone.'@c.us',
                'text' => $text,
            ]);

        if (! $response->successful()) {
            // Gövde ve anahtar loglanmaz; yalnızca durum kodu.
            throw new RuntimeException('WAHA mesajı gönderilemedi (HTTP '.$response->status().').');
        }
    }
}
