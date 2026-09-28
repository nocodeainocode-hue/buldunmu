<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ScheduledListingCatalogTest extends TestCase
{
    use RefreshDatabase;

    public function test_scheduled_listing_reuses_shared_category_and_city(): void
    {
        $sourceDirectory = Directory::create([
            'name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active',
        ]);
        $targetDirectory = Directory::create([
            'name' => 'Hedef', 'slug' => 'hedef', 'domain' => 'hedef.test', 'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Avukat', 'slug' => 'avukat', 'status' => 'active']);
        $city = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $sourceCompany = Company::create([
            'name' => 'Örnek Hukuk',
            'directory_id' => $sourceDirectory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'status' => 'active',
        ]);
        $campaign = Campaign::create([
            'directory_id' => $sourceDirectory->id,
            'company_id' => $sourceCompany->id,
            'name' => 'Yayın Testi',
            'daily_limit' => 5,
            'total_directories' => 1,
            'status' => 'active',
        ]);
        $item = CampaignItem::create([
            'campaign_id' => $campaign->id,
            'directory_id' => $targetDirectory->id,
            'company_id' => $sourceCompany->id,
            'slug' => 'ornek-hukuk',
            'scheduled_for' => now()->subMinute(),
            'status' => 'scheduled',
        ]);

        $this->artisan('listings:publish-daily')->assertSuccessful();

        $this->assertSame('published', $item->fresh()->status, (string) $item->fresh()->error_message);

        $this->assertDatabaseHas('companies', [
            'directory_id' => $targetDirectory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
        ]);
        $this->assertDatabaseMissing('categories', ['directory_id' => $targetDirectory->id]);
        $this->assertDatabaseMissing('cities', ['directory_id' => $targetDirectory->id]);
    }

    public function test_daily_limit_uses_turkey_calendar_day(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-28 22:30:00', 'UTC'));

        try {
            $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
            $firstTarget = Directory::create(['name' => 'Birinci', 'slug' => 'birinci', 'domain' => 'birinci.test', 'status' => 'active']);
            $secondTarget = Directory::create(['name' => 'İkinci', 'slug' => 'ikinci', 'domain' => 'ikinci.test', 'status' => 'active']);
            $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
            $city = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
            $company = Company::create([
                'name' => 'Kaynak Firma',
                'directory_id' => $source->id,
                'category_id' => $category->id,
                'city_id' => $city->id,
                'status' => 'active',
            ]);
            $campaign = Campaign::create([
                'directory_id' => $source->id,
                'company_id' => $company->id,
                'name' => 'Günlük Sınır',
                'total_directories' => 2,
                'daily_limit' => 1,
                'status' => 'active',
            ]);
            CampaignItem::create([
                'campaign_id' => $campaign->id,
                'directory_id' => $firstTarget->id,
                'company_id' => $company->id,
                'slug' => 'ilk',
                'scheduled_for' => now()->subDay(),
                'published_at' => Carbon::parse('2026-09-28 22:05:00', 'UTC'),
                'status' => 'published',
            ]);
            $due = CampaignItem::create([
                'campaign_id' => $campaign->id,
                'directory_id' => $secondTarget->id,
                'company_id' => $company->id,
                'slug' => 'ikinci',
                'scheduled_for' => now()->subMinute(),
                'status' => 'scheduled',
            ]);

            $this->artisan('listings:publish-daily')->assertSuccessful();

            $this->assertSame('scheduled', $due->fresh()->status);
            $this->assertSame('active', $campaign->fresh()->status);
        } finally {
            Carbon::setTestNow();
        }
    }
}
