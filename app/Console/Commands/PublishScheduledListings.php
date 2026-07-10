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
                    $source = $item->company;
                    $directoryId = $item->directory_id;

                    // Find matching category in target directory
                    $categoryId = null;
                    if ($source->category_id) {
                        $sourceCat = \App\Models\Category::find($source->category_id);
                        if ($sourceCat) {
                            $targetCat = \App\Models\Category::where('directory_id', $directoryId)
                                ->where('name', $sourceCat->name)
                                ->first();
                            if (!$targetCat) {
                                $targetCat = \App\Models\Category::create([
                                    'name' => $sourceCat->name,
                                    'slug' => \Illuminate\Support\Str::slug($sourceCat->name . '-' . $directoryId),
                                    'icon' => $sourceCat->icon,
                                    'status' => 'active',
                                    'directory_id' => $directoryId,
                                ]);
                            }
                            $categoryId = $targetCat->id;
                        }
                    }

                    // Find matching city in target directory
                    $cityId = null;
                    if ($source->city_id) {
                        $sourceCity = \App\Models\City::find($source->city_id);
                        if ($sourceCity) {
                            $targetCity = \App\Models\City::where('directory_id', $directoryId)
                                ->where('name', $sourceCity->name)
                                ->first();
                            $cityId = $targetCity?->id;
                        }
                    }

                    // Fallback to first city in target directory
                    if (!$cityId) {
                        $cityId = \App\Models\City::where('directory_id', $directoryId)->value('id');
                    }
                    if (!$categoryId) {
                        $categoryId = \App\Models\Category::where('directory_id', $directoryId)->value('id');
                    }

                    $targetCompany = Company::firstOrCreate(
                        ['slug' => $item->slug, 'directory_id' => $directoryId],
                        [
                            'name' => $source->name,
                            'category_id' => $categoryId ?? 1,
                            'city_id' => $cityId ?? 1,
                            'phone' => $source->phone,
                            'whatsapp' => $source->whatsapp,
                            'email' => $source->email,
                            'website' => $source->website,
                            'address' => $source->address,
                            'google_maps_url' => $source->google_maps_url,
                            'opening_hours' => $source->opening_hours,
                            'short_description' => $source->short_description,
                            'description' => $source->description,
                            'services' => $source->services,
                            'why_us_items' => $source->why_us_items,
                            'external_links' => $source->external_links,
                            'status' => 'active',
                        ]
                    );

                    // Copy logo
                    if ($source->logo && !$targetCompany->logo) {
                        $targetCompany->logo = $this->copyFile($source->logo, 'logos');
                        $targetCompany->save();
                    }

                    // Copy cover
                    if ($source->cover_image && !$targetCompany->cover_image) {
                        $targetCompany->cover_image = $this->copyFile($source->cover_image, 'covers');
                        $targetCompany->save();
                    }

                    // Copy gallery
                    if ($source->images && $source->images->isNotEmpty() && $targetCompany->images()->count() === 0) {
                        foreach ($source->images as $image) {
                            $newPath = $this->copyFile($image->image_path, 'gallery');
                            if ($newPath) {
                                $targetCompany->images()->create([
                                    'image_path' => $newPath,
                                    'alt_text' => $image->alt_text,
                                    'sort_order' => $image->sort_order,
                                ]);
                            }
                        }
                    }

                    $item->update(['status' => 'published', 'published_at' => now()]);
                    $this->info("Published: {$item->slug} on directory {$directoryId}");
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

    protected function copyFile(?string $path, string $type): ?string
    {
        if (!$path || !\Illuminate\Support\Facades\Storage::disk('public')->exists($path)) {
            return null;
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $newPath = match($type) {
            'logos' => 'companies/logos/' . \Illuminate\Support\Str::uuid() . '.' . $ext,
            'covers' => 'companies/covers/' . \Illuminate\Support\Str::uuid() . '.' . $ext,
            default => 'firmalar/galeri/' . \Illuminate\Support\Str::uuid() . '.' . $ext,
        };

        \Illuminate\Support\Facades\Storage::disk('public')->copy($path, $newPath);
        return $newPath;
    }
}
