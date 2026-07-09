<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class PublishScheduledListings extends Command
{
    protected $signature = 'listings:publish-daily';
    protected $description = 'Publish scheduled campaign items for today';

    public function handle(): void
    {
        $activeCampaigns = Campaign::where('status', 'active')->get();

        foreach ($activeCampaigns as $campaign) {
            $limit = $campaign->daily_limit;
            $publishedToday = CampaignItem::where('campaign_id', $campaign->id)
                ->whereDate('published_at', today())
                ->count();

            $remaining = $limit - $publishedToday;
            if ($remaining <= 0) continue;

            $items = CampaignItem::where('campaign_id', $campaign->id)
                ->where('status', 'scheduled')
                ->where('scheduled_for', '<=', now())
                ->orderBy('scheduled_for')
                ->take($remaining)
                ->get();

            foreach ($items as $item) {
                try {
                    // Publish: create/update company in target directory
                    $company = Company::firstOrCreate(
                        ['slug' => $item->slug, 'directory_id' => $item->directory_id],
                        [
                            'name' => $item->company->name ?? 'Firma',
                            'category_id' => 1,
                            'city_id' => 1,
                            'phone' => $item->company->phone ?? null,
                            'website' => $item->company->website ?? null,
                            'short_description' => $item->description,
                            'status' => 'active',
                        ]
                    );

                    $item->update([
                        'status' => 'published',
                        'published_at' => now(),
                    ]);

                    $this->info("Published: {$item->slug} on directory {$item->directory_id}");
                } catch (\Exception $e) {
                    $item->update([
                        'status' => 'failed',
                        'error_message' => $e->getMessage(),
                    ]);
                    $this->error("Failed: {$item->slug} — {$e->getMessage()}");
                }
            }

            // Check if campaign is complete
            $totalItems = CampaignItem::where('campaign_id', $campaign->id)->count();
            $publishedItems = CampaignItem::where('campaign_id', $campaign->id)->where('status', 'published')->count();

            if ($publishedItems >= $totalItems) {
                $campaign->update(['status' => 'completed']);
                $this->info("Campaign #{$campaign->id} completed!");
            }
        }

        $this->info('Daily publish complete.');
    }
}
