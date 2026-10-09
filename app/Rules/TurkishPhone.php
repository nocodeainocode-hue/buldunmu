<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/**
 * Türkiye telefon numarası: cep (5xx), sabit (2xx/3xx/4xx), 850 ve 444'lü hizmet numaraları.
 * "0532 123 45 67", "+90 532 123 45 67", "(0282) 123 45 67", "5321234567" kabul edilir;
 * harf içeren, kısa ya da 5555555555 gibi tekrarlı numaralar reddedilir. Boş değer kurala takılmaz
 * (zorunluluk için ayrıca 'required' kullanılır).
 */
class TurkishPhone implements ValidationRule
{
    public const MESSAGE = 'Geçerli bir telefon numarası girin (örn. 0532 123 45 67).';

    public static function isValid(?string $value): bool
    {
        $raw = trim((string) $value);

        if ($raw === '') {
            return true;
        }

        if (! preg_match('/^\+?[\d\s().\-]+$/', $raw)) {
            return false;
        }

        $digits = preg_replace('/\D+/', '', $raw);

        if (str_starts_with($digits, '0090')) {
            $digits = substr($digits, 4);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, '90')) {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        // 444 xx xx gibi 7 haneli kurumsal hizmet numaraları
        if (preg_match('/^444\d{4}$/', $digits)) {
            return true;
        }

        if (! preg_match('/^(?:[2-5]\d{2}|850)\d{7}$/', $digits)) {
            return false;
        }

        // 5555555555 / 5050505050 gibi uydurma numaraları ele
        return count(array_unique(str_split($digits))) > 2;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! self::isValid(is_scalar($value) ? (string) $value : '')) {
            $fail(self::MESSAGE);
        }
    }
}
