<?php

namespace App\Support;

use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Str;

/**
 * İletişim formu spam koruması: oturuma bağlı olmayan, imzalı tek kullanımlık işlem sorusu ve zaman tuzağı.
 * Soru ve cevap şifreli bir belirteçle forma gömülür; bot cevabı hesaplasa bile belirteci yeniden kullanamaz
 * ve formu birkaç saniyeden kısa sürede gönderemez.
 */
class FormGuard
{
    public const MIN_SECONDS = 4;

    public const MAX_SECONDS = 7200;

    public const OK = 'ok';

    public const TOO_FAST = 'too_fast';

    public const WRONG = 'wrong';

    /** @return array{label: string, token: string} */
    public static function challenge(): array
    {
        $operation = random_int(0, 2);

        if ($operation === 0) {
            $a = random_int(2, 15);
            $b = random_int(2, 15);
            [$label, $answer] = ["$a + $b", $a + $b];
        } elseif ($operation === 1) {
            $a = random_int(8, 20);
            $b = random_int(2, $a - 1);
            [$label, $answer] = ["$a - $b", $a - $b];
        } else {
            $a = random_int(2, 9);
            $b = random_int(2, 9);
            [$label, $answer] = ["$a × $b", $a * $b];
        }

        return [
            'label' => $label,
            'token' => Crypt::encryptString(json_encode(['a' => $answer, 't' => time(), 'i' => Str::random(16)])),
        ];
    }

    public static function verify(?string $token, mixed $answer): string
    {
        try {
            $data = json_decode(Crypt::decryptString((string) $token), true);
        } catch (DecryptException) {
            return self::WRONG;
        }

        if (! is_array($data) || ! isset($data['a'], $data['t'], $data['i'])) {
            return self::WRONG;
        }

        $age = time() - (int) $data['t'];

        if ($age > self::MAX_SECONDS) {
            return self::WRONG;
        }

        // Aynı belirteç ikinci kez kabul edilmez (cevabı yeniden kullanan botlar).
        if (! Cache::add('form-guard:'.$data['i'], 1, 600)) {
            return self::WRONG;
        }

        if ((int) $answer !== (int) $data['a'] || ! is_numeric($answer)) {
            return self::WRONG;
        }

        return $age < self::MIN_SECONDS ? self::TOO_FAST : self::OK;
    }

    /** Mesaj içeriği spam görünümlü mü: çok bağlantı, ad alanında bağlantı ya da bilinen kalıplar. */
    public static function looksLikeSpam(string $name, string $message): bool
    {
        if (preg_match_all('~https?://|www\.|\b[a-z0-9-]+\.(?:com|net|org|ru|xyz|top|info|biz|site|online)\b~i', $message) > 2) {
            return true;
        }

        if (preg_match('~https?://|www\.|@|\.(?:com|ru|xyz)\b~i', $name)) {
            return true;
        }

        return (bool) preg_match('~\b(?:seo (?:service|hizmet)|backlink|casino|bahis|bitcoin|crypto|viagra|forex|telegram|whatsapp group|guest post|web ?site(?:niz)? (?:ranking|trafik))\b~iu', $message);
    }
}
