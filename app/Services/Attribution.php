<?php

namespace App\Services;

use Illuminate\Http\Request;

/**
 * Reklam/kaynak bilgisi (UTM, tıklama kimliği, yönlendiren site). İlk temas çerezde 30 gün
 * tutulur; kayıt sırasında kullanıcıya yazılır. Yeni bir reklam tıklaması eskisinin yerine geçer.
 */
class Attribution
{
    public const COOKIE = 'fr_attr';
    public const MINUTES = 60 * 24 * 30;

    private const UTM = ['utm_source', 'utm_medium', 'utm_campaign', 'utm_term', 'utm_content'];
    private const CLICK_IDS = ['gclid', 'fbclid', 'msclkid', 'ttclid'];

    /** İstekten (yeni) bir kaynak çıkarır; yoksa null. */
    public function fromRequest(Request $request): ?array
    {
        $data = [];

        foreach (self::UTM as $key) {
            if ($value = $this->clean($request->query($key))) {
                $data[$key] = $value;
            }
        }

        foreach (self::CLICK_IDS as $key) {
            if ($value = $this->clean($request->query($key))) {
                $data['click_id'] = $key.':'.$value;
                break;
            }
        }

        if ($data !== []) {
            return $data;
        }

        return null;
    }

    /** Aynı siteden olmayan bir yönlendirenin alan adı. */
    public function externalReferrer(Request $request): ?string
    {
        $referer = (string) $request->headers->get('referer');
        $host = strtolower((string) parse_url($referer, PHP_URL_HOST));

        if ($host === '' || $host === strtolower($request->getHost())) {
            return null;
        }

        return substr(preg_replace('/^www\./', '', $host), 0, 191);
    }

    /** @return array<string, string> Kullanıcıya yazılabilecek alanlar. */
    public function forUser(Request $request): array
    {
        $stored = json_decode((string) $request->cookie(self::COOKIE), true);
        $stored = is_array($stored) ? $stored : [];

        $fields = [];
        foreach ([...self::UTM, 'click_id', 'referrer_host'] as $key) {
            if (! empty($stored[$key]) && is_string($stored[$key])) {
                $fields[$key] = $this->clean($stored[$key]);
            }
        }

        return array_filter($fields);
    }

    /** Başvuru kaydına yazılacak kaynak alanları (UTM ve yönlendiren site). */
    public function forListing(Request $request): array
    {
        return array_intersect_key(
            $this->forUser($request),
            array_flip(['utm_source', 'utm_medium', 'utm_campaign', 'referrer_host']),
        );
    }

    private function clean(mixed $value): ?string
    {
        if (! is_string($value) || trim($value) === '') {
            return null;
        }

        return mb_substr(trim(strip_tags($value)), 0, 191);
    }
}
