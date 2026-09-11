<?php

namespace App\Console\Commands;

use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\District;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
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
            if ($remaining <= 0) {
                continue;
            }

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

                    $sourceCategory = Category::withoutGlobalScope('directory')->find($source->category_id);
                    $sourceCity = City::withoutGlobalScope('directory')->find($source->city_id);
                    $categoryId = $sourceCategory
                        ? Category::withoutGlobalScope('directory')->whereNull('directory_id')->where('slug', $sourceCategory->slug)->value('id')
                        : null;
                    $cityId = $sourceCity
                        ? City::withoutGlobalScope('directory')->whereNull('directory_id')->where('slug', $sourceCity->slug)->value('id')
                        : null;

                    if (! $categoryId || ! $cityId) {
                        throw new \RuntimeException('Firmanın kategori veya şehri ortak katalogda bulunamadı.');
                    }

                    $districtId = null;
                    $sourceDistrict = District::withoutGlobalScope('directory')->find($source->district_id);
                    if ($sourceDistrict) {
                        $districtId = District::withoutGlobalScope('directory')
                            ->whereNull('directory_id')
                            ->where('city_id', $cityId)
                            ->where('slug', $sourceDistrict->slug)
                            ->value('id');
                    }

                    $targetCompany = Company::firstOrCreate(
                        ['external_id' => 'campaign-company-'.$source->id, 'directory_id' => $directoryId],
                        [
                            'name' => $source->name,
                            'category_id' => $categoryId,
                            'city_id' => $cityId,
                            'district_id' => $districtId,
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
                    $item->update(['slug' => $targetCompany->slug]);

                    // Copy logo
                    if ($source->logo && ! $targetCompany->logo) {
                        $targetCompany->logo = $this->copyFile($source->logo, 'logos');
                        $targetCompany->save();
                    }

                    // Copy cover
                    if ($source->cover_image && ! $targetCompany->cover_image) {
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
        if (! $path || ! Storage::disk('public')->exists($path)) {
            return null;
        }

        $ext = pathinfo($path, PATHINFO_EXTENSION);
        $newPath = match ($type) {
            'logos' => 'companies/logos/'.Str::uuid().'.'.$ext,
            'covers' => 'companies/covers/'.Str::uuid().'.'.$ext,
            default => 'firmalar/galeri/'.Str::uuid().'.'.$ext,
        };

        Storage::disk('public')->copy($path, $newPath);

        return $newPath;
    }
}
