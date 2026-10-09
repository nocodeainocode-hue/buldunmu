<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/** Firmaya özel sahiplenme daveti: kısa link, hazır WhatsApp mesajı ve tıklama/sahiplenme takibi. */
class ClaimInvite extends Model
{
    public const STATUSES = [
        'ready' => 'Hazır',
        'sent' => 'Gönderildi',
        'clicked' => 'Tıkladı',
        'claimed' => 'Sahiplendi',
        'opted_out' => 'Bir daha yazma',
    ];

    public const DEFAULT_TEMPLATE = "Merhaba, {firma} {rehber} sitesinde yayında. Bilgilerinizi (telefon, adres, çalışma saatleri) ücretsiz güncellemek için: {link}\nİstemezseniz \"İSTEMİYORUM\" yazmanız yeterli.";

    protected $fillable = [
        'company_id', 'directory_id', 'token', 'phone', 'message', 'status', 'clicks',
        'sent_at', 'first_clicked_at', 'last_clicked_at', 'claimed_at', 'listing_request_id', 'opted_out_at',
    ];

    protected $casts = [
        'sent_at' => 'datetime',
        'first_clicked_at' => 'datetime',
        'last_clicked_at' => 'datetime',
        'claimed_at' => 'datetime',
        'opted_out_at' => 'datetime',
    ];

    public function company()
    {
        return $this->belongsTo(Company::class)->withoutGlobalScopes();
    }

    public function directory()
    {
        return $this->belongsTo(Directory::class);
    }

    public static function newToken(): string
    {
        do {
            $token = Str::random(8);
        } while (static::where('token', $token)->exists());

        return $token;
    }

    /** Türkiye cep numarasını 10 haneye indirger (5xxxxxxxxx); cep değilse null. */
    public static function mobileDigits(?string $phone): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '0090')) {
            $digits = substr($digits, 4);
        } elseif (strlen($digits) === 12 && str_starts_with($digits, '90')) {
            $digits = substr($digits, 2);
        } elseif (strlen($digits) === 11 && str_starts_with($digits, '0')) {
            $digits = substr($digits, 1);
        }

        return preg_match('/^5\d{9}$/', $digits) ? $digits : null;
    }

    public function url(): string
    {
        return 'https://'.($this->directory?->domain ?? '').'/s/'.$this->token;
    }

    public static function renderMessage(string $template, string $company, string $directoryName, string $link): string
    {
        return strtr($template, ['{firma}' => $company, '{rehber}' => $directoryName, '{link}' => $link]);
    }

    public function whatsappUrl(): ?string
    {
        $digits = self::mobileDigits($this->phone);

        return $digits ? 'https://wa.me/90'.$digits.'?text='.rawurlencode($this->message) : null;
    }
}
