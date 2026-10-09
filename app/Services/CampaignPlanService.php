<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\City;
use App\Models\Directory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CampaignPlanService
{
    /**
     * @return array{created: int, total: int, daily_limit: int, already_generated: bool}
     */
    public function generate(Campaign $campaign): array
    {
        return DB::transaction(function () use ($campaign): array {
            $campaign = Campaign::withoutGlobalScope('directory')
                ->lockForUpdate()
                ->with('company')
                ->findOrFail($campaign->id);

            $existingItems = $campaign->items()->count();

            if ($existingItems > 0 || $campaign->status === 'cancelled') {
                return [
                    'created' => 0,
                    'total' => $existingItems,
                    'daily_limit' => $campaign->daily_limit,
                    'already_generated' => true,
                ];
            }

            $company = $campaign->company;
            $citySlug = $company->city_id
                ? City::withoutGlobalScope('directory')->whereKey($company->city_id)->value('slug')
                : null;

            // Firmaya uymayan rehberler elenir (ör. Ankara firması Tekirdağ'a odaklı rehbere eklenmez);
            // uygun olanlar arasından kampanya hedefi kadarı seçilir.
            $directories = Directory::query()
                ->where('status', 'active')
                ->whereKeyNot($campaign->directory_id)
                ->orderBy('id')
                ->get()
                ->filter(fn (Directory $directory) => self::directoryFitsCity($directory, $citySlug))
                ->take($campaign->total_directories)
                ->values();

            $created = 0;
            $dailyLimit = max(1, $campaign->daily_limit);
            $firstPublicationAt = $campaign->start_date?->isFuture()
                ? $campaign->start_date->copy()
                : now();

            foreach ($directories as $index => $directory) {
                $day = intdiv($index, $dailyLimit);
                $anchor = AnchorTextService::generate($company, $directory);

                $item = CampaignItem::firstOrCreate(
                    [
                        'campaign_id' => $campaign->id,
                        'directory_id' => $directory->id,
                    ],
                    [
                        'company_id' => $company->id,
                        'slug' => Str::slug($company->name.'-'.($directory->plate_code ?? $directory->slug ?? $index)),
                        'description' => $company->short_description ?? $company->name.' - '.$directory->name,
                        'anchor_text' => $anchor['anchor_text'],
                        'link_type' => $anchor['link_type'],
                        'scheduled_for' => $firstPublicationAt->copy()->addDays($day),
                        'status' => 'scheduled',
                    ],
                );

                if ($item->wasRecentlyCreated) {
                    $created++;
                }
            }

            if ($created > 0) {
                $campaign->update([
                    'status' => 'active',
                    'start_date' => $campaign->start_date ?? $firstPublicationAt,
                ]);
            } elseif ($existingItems === 0) {
                $campaign->update(['status' => 'draft']);
            }

            return [
                'created' => $created,
                'total' => $directories->count(),
                'daily_limit' => $dailyLimit,
                'already_generated' => false,
            ];
        });
    }

    /**
     * Ulusal rehberler her firmaya uygundur. Şehir odaklı rehberler yalnızca kendi şehirlerindeki
     * firmaları alır; öne çıkan şehir listeli rehber, "diğer iller" grubunu açtıysa herkese açıktır.
     */
    public static function directoryFitsCity(Directory $directory, ?string $citySlug): bool
    {
        return match ($directory->geography_mode) {
            'local' => $citySlug !== null && $citySlug === $directory->primary_city_slug,
            'custom' => (bool) $directory->group_other_cities
                || ($citySlug !== null && in_array($citySlug, $directory->featured_city_slugs ?? [], true)),
            default => true,
        };
    }
}
