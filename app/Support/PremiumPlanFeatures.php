<?php

namespace App\Support;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/** Premium üyelik paketlerinin özellik listesine yeni maddeleri ekler (tekrar çalıştırılabilir). */
class PremiumPlanFeatures
{
    public const AD_FREE_TITLE = 'Reklamsız profil';
    public const AD_FREE_DESCRIPTION = 'Profilinizde reklam ve başka firma tanıtımı (benzer firmalar) gösterilmez.';

    /** Eklenen paket sayısını döndürür. */
    public static function addAdFree(): int
    {
        $updated = 0;

        foreach (DB::table('membership_plans')->get(['id', 'name', 'slug', 'features']) as $plan) {
            $haystack = Str::lower(Str::ascii($plan->name.' '.$plan->slug));

            if (! str_contains($haystack, 'premium')) {
                continue;
            }

            $features = json_decode((string) $plan->features, true);
            $features = is_array($features) ? $features : [];

            $already = collect($features)->contains(function ($feature) {
                $title = is_array($feature) ? ($feature['title'] ?? '') : (string) $feature;

                return Str::contains(Str::lower((string) $title), 'reklamsız profil');
            });

            if ($already) {
                continue;
            }

            // Paket maddeleri başlık + açıklama olarak saklanır (yönetim panelindeki "Plan Özellikleri" biçimi).
            $features[] = ['title' => self::AD_FREE_TITLE, 'description' => self::AD_FREE_DESCRIPTION];

            DB::table('membership_plans')->where('id', $plan->id)->update([
                'features' => json_encode(array_values($features), JSON_UNESCAPED_UNICODE),
                'updated_at' => now(),
            ]);

            $updated++;
        }

        return $updated;
    }
}
