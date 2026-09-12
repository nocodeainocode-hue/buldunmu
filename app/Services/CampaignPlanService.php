<?php

namespace App\Services;

use App\Models\Campaign;
use App\Models\CampaignItem;
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

            if ($campaign->status !== 'draft') {
                return [
                    'created' => 0,
                    'total' => $campaign->items()->count(),
                    'daily_limit' => $campaign->daily_limit,
                    'already_generated' => true,
                ];
            }

            $directories = Directory::query()
                ->where('status', 'active')
                ->whereKeyNot($campaign->directory_id)
                ->orderBy('id')
                ->limit($campaign->total_directories)
                ->get();

            $created = 0;
            $company = $campaign->company;
            $dailyLimit = max(1, $campaign->daily_limit);

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
                        'scheduled_for' => now()->addDays($day),
                        'status' => 'scheduled',
                    ],
                );

                if ($item->wasRecentlyCreated) {
                    $created++;
                }
            }

            $campaign->update([
                'status' => $directories->isEmpty() ? 'completed' : 'active',
                'start_date' => now(),
            ]);

            return [
                'created' => $created,
                'total' => $directories->count(),
                'daily_limit' => $dailyLimit,
                'already_generated' => false,
            ];
        });
    }
}
