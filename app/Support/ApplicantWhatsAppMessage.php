<?php

namespace App\Support;

use App\Models\Directory;
use App\Models\ListingRequest;
use Illuminate\Support\Str;

/**
 * Başvuru sahibine giden WhatsApp mesajı. Mesaj her zaman başvurunun geldiği rehberin adı ve alan adıyla gider.
 * Metinler burada; düzenlemek için yalnızca TEMPLATES değiştirilir.
 */
class ApplicantWhatsAppMessage
{
    public const TEMPLATES = [
        // Kayıt formunun 1. adımında kalan başvuru (gönderim anında hâlâ yarım)
        'partial' => "{selam}\n\n*{rehber}* ({alanadi}) üzerinde *{firma}* için kayda başladınız ancak son adım tamamlanmadı.\n\nDilerseniz eksik bilgileri (adres, hizmetleriniz, çalışma saatleri) bu mesaja yazın, kaydınızı biz tamamlayalım. Kendiniz devam etmek isterseniz: {kayit_linki}\n\n{rehber}",
        // Kaydı tamamlanmış firma sahibi başvurusu
        'owner_registration' => "{selam}\n\n*{rehber}* ({alanadi}) üzerinden yaptığınız *{firma}* başvurusu bize ulaştı, teşekkür ederiz.\n\nBilgilerinizi kontrol edip kısa süre içinde sizinle iletişime geçeceğiz. Firmanız yayına alındığında buradan haber vereceğiz. Sorunuz olursa bu numaraya yazabilirsiniz.\n\n{rehber}",
        // Profil sahiplenme talebi
        'claim' => "{selam}\n\n*{rehber}* ({alanadi}) üzerinde *{firma}* profilini sahiplenmek için başvurdunuz.\n\nTalebinizi inceliyoruz; onaylandığında profilinizi kendiniz güncelleyebileceksiniz. Sorunuz olursa bu numaraya yazabilirsiniz.\n\n{rehber}",
        // "Firma ekle" formu ve diğer başvurular
        'default' => "{selam}\n\n*{rehber}* ({alanadi}) üzerinden *{firma}* için firma ekleme talebiniz bize ulaştı.\n\nBilgileri kontrol edip kısa süre içinde yayına alacağız. Eklemek istediğiniz bilgi (adres, hizmetler, çalışma saatleri) varsa bu mesaja yazabilirsiniz.\n\n{rehber}",
    ];

    /** Mesaj türü: gönderim anındaki duruma göre seçilir (yarımken tamamlanan başvuru "tamamlandı" mesajı alır). */
    public static function kind(ListingRequest $listing): string
    {
        if ($listing->is_partial) {
            return 'partial';
        }

        return in_array($listing->source, ['owner_registration', 'claim'], true) ? $listing->source : 'default';
    }

    public static function text(ListingRequest $listing, Directory $directory): string
    {
        $name = trim(preg_replace('/\s+/u', ' ', (string) $listing->contact_name));
        $domain = $directory->domain;

        return strtr(self::TEMPLATES[self::kind($listing)], [
            '{selam}' => $name !== '' ? 'Merhaba '.Str::limit($name, 60, '').',' : 'Merhaba,',
            '{rehber}' => $directory->name,
            '{alanadi}' => $domain,
            '{firma}' => Str::limit(trim(preg_replace('/\s+/u', ' ', (string) $listing->company_name)), 120),
            '{kayit_linki}' => 'https://'.$domain.'/firma-kayit',
        ]);
    }

    /** WhatsApp numarası (önce whatsapp alanı, yoksa telefon). Yalnızca Türkiye cep numaraları: 90 5XX XXX XX XX. */
    public static function phone(ListingRequest $listing): ?string
    {
        foreach ([$listing->whatsapp, $listing->phone] as $raw) {
            $digits = preg_replace('/\D+/', '', (string) $raw);
            if (str_starts_with($digits, '00')) {
                $digits = substr($digits, 2);
            }
            if (strlen($digits) === 11 && str_starts_with($digits, '0')) {
                $digits = '9'.$digits;
            } elseif (strlen($digits) === 10) {
                $digits = '90'.$digits;
            }
            if (preg_match('/^905\d{9}$/', $digits)) {
                return $digits;
            }
        }

        return null;
    }
}
