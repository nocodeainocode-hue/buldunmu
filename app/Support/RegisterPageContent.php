<?php

namespace App\Support;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\RegisterVariant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

/**
 * Kayıt sayfasının başlık ve fayda metinleri. Reklam adresindeki ?v=anahtar bir varyantı seçer;
 * {rehber}, {sehir} (?sehir=slug) ve {kategori} (?kategori=slug) yer tutucuları doldurulur.
 * Çözülemeyen yer tutucu varsa güvenli olan varsayılan metin gösterilir.
 */
class RegisterPageContent
{
    public static function defaults(): array
    {
        return [
            'variant' => null,
            'badge' => 'Ücretsiz firma kaydı',
            'headline' => 'Müşteriler firmanızı kolayca bulsun',
            'subheadline' => 'İki kısa adımda firma profilinizi oluşturun. Bilgilerinizi daha sonra panelinizden dilediğiniz zaman tamamlayabilirsiniz.',
            'button_text' => 'Ücretsiz Profilimi Oluştur',
            'benefits' => [
                ['title' => 'Doğrudan ulaşılabilir olun', 'text' => 'Ziyaretçiler telefon ve WhatsApp üzerinden size ulaşsın.'],
                ['title' => 'Bilgileriniz kontrolünüzde', 'text' => 'Telefon, adres ve hizmetlerinizi panelden güncelleyin.'],
                ['title' => 'Profilinizi zamanla güçlendirin', 'text' => 'Görsel, açıklama ve çalışma saatlerini sonradan ekleyin.'],
            ],
        ];
    }

    public static function resolve(Request $request, ?Directory $directory): array
    {
        $content = self::defaults();
        $key = trim((string) $request->query('v', ''));

        if ($key === '' || ! preg_match('/^[a-z0-9_-]{1,40}$/i', $key)) {
            return $content;
        }

        $variant = RegisterVariant::where('key', $key)->where('status', 'active')->first();

        if (! $variant) {
            return $content;
        }

        $tokens = [
            '{rehber}' => (string) ($directory?->name ?? ''),
            '{sehir}' => self::lookup(City::class, (string) $request->query('sehir', '')),
            '{kategori}' => self::lookup(Category::class, (string) $request->query('kategori', '')),
        ];

        $fill = function (?string $text) use ($tokens, &$unresolved): string {
            $text = (string) $text;
            foreach ($tokens as $token => $value) {
                if (str_contains($text, $token)) {
                    if ($value === '') {
                        $unresolved = true;
                    }
                    $text = str_replace($token, $value, $text);
                }
            }

            return $text;
        };

        $unresolved = false;
        $resolved = [
            'variant' => $variant->key,
            'badge' => $fill($variant->badge) ?: $content['badge'],
            'headline' => $fill($variant->headline),
            'subheadline' => $fill($variant->subheadline) ?: $content['subheadline'],
            'button_text' => $variant->button_text ?: $content['button_text'],
            'benefits' => collect($variant->benefits ?? [])
                ->filter(fn ($item) => filled($item['title'] ?? null))
                ->map(fn ($item) => ['title' => $fill($item['title']), 'text' => $fill($item['text'] ?? '')])
                ->values()->all() ?: $content['benefits'],
        ];

        return $unresolved || trim($resolved['headline']) === '' ? $content : $resolved;
    }

    /** Rehberdeki yayında firma sayısı; küçük sayılarda sosyal kanıt olarak gösterilmez. */
    public static function companyCount(?Directory $directory): ?int
    {
        if (! $directory) {
            return null;
        }

        $count = (int) Cache::remember('register.company_count.'.$directory->id, 600, fn () => Company::active()->count());

        return $count >= 25 ? $count : null;
    }

    private static function lookup(string $model, string $slug): string
    {
        if ($slug === '' || ! preg_match('/^[a-z0-9-]{1,80}$/i', $slug)) {
            return '';
        }

        return (string) $model::withoutGlobalScope('directory')->where('slug', $slug)->value('name');
    }
}
