<?php

namespace App\Services;

use App\Models\AdCampaign;
use App\Models\AdDailyStat;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;

/**
 * Reklam seçimi: ücretli reklamveren > hedefi en dar kendi reklamımız > genel kendi reklamımız.
 * Aynı öncelik grubunda ağırlıklı rastgele seçim yapılır.
 */
class AdServer
{
    /** @var array<string, array<int, string>> */
    private array $slugCache = [];

    public function pick(string $placement, ?Directory $directory = null, ?string $citySlug = null, ?string $categorySlug = null): ?AdCampaign
    {
        $candidates = $this->active()
            ->filter(fn (AdCampaign $ad) => $ad->isLive()
                && in_array($placement, $ad->placements ?? [], true)
                && $this->matches($ad, $directory, $citySlug, $categorySlug));

        if ($candidates->isEmpty()) {
            return null;
        }

        $best = $candidates->groupBy(fn (AdCampaign $ad) => $this->score($ad))->sortKeysDesc()->first();

        return $this->weightedRandom($best);
    }

    /** Hedefleme ne kadar darsa öncelik o kadar yüksek; ücretli reklamveren her zaman önde. */
    public function score(AdCampaign $ad): int
    {
        return ($ad->type === 'paid' ? 100 : 0)
            + (filled($ad->city_ids) ? 20 : 0)
            + (filled($ad->category_ids) ? 10 : 0)
            + (filled($ad->directory_ids) ? 5 : 0);
    }

    public function matches(AdCampaign $ad, ?Directory $directory, ?string $citySlug, ?string $categorySlug): bool
    {
        if (filled($ad->directory_ids) && ! ($directory && in_array($directory->id, array_map('intval', $ad->directory_ids), true))) {
            return false;
        }

        if (filled($ad->city_ids)) {
            $slugs = $this->slugs(City::class, $ad->city_ids);
            $localSlug = $directory && $directory->geography_mode === 'local' ? $directory->primary_city_slug : null;

            $cityMatch = ($citySlug && in_array($citySlug, $slugs, true))
                || ($localSlug && in_array($localSlug, $slugs, true));

            if (! $cityMatch) {
                return false;
            }
        }

        if (filled($ad->category_ids)) {
            $slugs = $this->slugs(Category::class, $ad->category_ids);

            if (! ($categorySlug && in_array($categorySlug, $slugs, true))) {
                return false;
            }
        }

        return true;
    }

    public function recordImpression(AdCampaign $ad, ?int $directoryId): void
    {
        AdCampaign::whereKey($ad->id)->increment('impressions_total');
        $this->bumpDaily($ad->id, $directoryId, 'impressions');
    }

    public function recordClick(AdCampaign $ad, ?int $directoryId): void
    {
        AdCampaign::whereKey($ad->id)->increment('clicks_total');
        $this->bumpDaily($ad->id, $directoryId, 'clicks');
    }

    public function isBot(?string $userAgent): bool
    {
        if (blank($userAgent)) {
            return true;
        }

        return (bool) preg_match('/bot|crawl|spider|slurp|preview|monitor|headless|facebookexternalhit|curl|wget|python|php|java|go-http|axios|lighthouse/i', $userAgent);
    }

    /**
     * Sayfa bağlamını görünüm değişkenlerinden değil ROTADAN çözer (Blade, alt görünümlerdeki
     * döngü değişkenlerini layout'a sızdırabildiği için değişkenlere güvenilmez).
     *
     * @return array{city: ?string, category: ?string}
     */
    public function contextFromRequest(Request $request): array
    {
        $name = $request->route()?->getName();

        return match ($name) {
            'cities.show' => ['city' => (string) $request->route('slug'), 'category' => null],
            'categories.show' => [
                'city' => $request->filled('city') ? (string) $request->query('city') : null,
                'category' => (string) $request->route('slug'),
            ],
            'companies.show' => $this->companyContext((string) $request->route('slug')),
            default => ['city' => null, 'category' => null],
        };
    }

    /** @return array{city: ?string, category: ?string} */
    private function companyContext(string $slug): array
    {
        $company = Company::where('slug', $slug)->with(['city:id,slug', 'category:id,slug'])->first(['id', 'city_id', 'category_id']);

        return ['city' => $company?->city?->slug, 'category' => $company?->category?->slug];
    }

    /** @return Collection<int, AdCampaign> */
    private function active(): Collection
    {
        return Cache::remember('ads.active', 60, fn () => AdCampaign::where('status', 'active')->get());
    }

    /** @param Collection<int, AdCampaign> $group */
    private function weightedRandom(Collection $group): AdCampaign
    {
        $total = max(1, $group->sum(fn (AdCampaign $ad) => max(1, $ad->weight)));
        $roll = random_int(1, $total);

        foreach ($group as $ad) {
            $roll -= max(1, $ad->weight);

            if ($roll <= 0) {
                return $ad;
            }
        }

        return $group->first();
    }

    /** @return array<int, string> */
    private function slugs(string $model, array $ids): array
    {
        $key = $model.':'.implode(',', $ids);

        return $this->slugCache[$key] ??= $model::withoutGlobalScope('directory')
            ->whereIn('id', array_map('intval', $ids))
            ->pluck('slug')
            ->all();
    }

    private function bumpDaily(int $adId, ?int $directoryId, string $column): void
    {
        $keys = ['ad_campaign_id' => $adId, 'date' => now()->toDateString(), 'directory_id' => $directoryId ?? 0];

        if (AdDailyStat::where($keys)->increment($column) > 0) {
            return;
        }

        try {
            AdDailyStat::create($keys + [$column => 1]);
        } catch (QueryException) {
            AdDailyStat::where($keys)->increment($column);
        }
    }
}
