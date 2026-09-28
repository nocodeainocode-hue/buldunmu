<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Services\CampaignPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class CampaignPlanServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_plan_excludes_the_source_directory_and_cannot_be_generated_twice(): void
    {
        $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
        $firstTarget = Directory::create(['name' => 'Birinci', 'slug' => 'birinci', 'domain' => 'birinci.test', 'status' => 'active']);
        $secondTarget = Directory::create(['name' => 'İkinci', 'slug' => 'ikinci', 'domain' => 'ikinci.test', 'status' => 'active']);
        $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
        $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
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
            'name' => 'Yayın Planı',
            'total_directories' => 100,
            'daily_limit' => 1,
            'status' => 'draft',
        ]);

        $service = app(CampaignPlanService::class);
        $firstResult = $service->generate($campaign);
        $secondResult = $service->generate($campaign);

        $this->assertSame(2, $firstResult['created']);
        $this->assertFalse($firstResult['already_generated']);
        $this->assertTrue($secondResult['already_generated']);
        $this->assertDatabaseMissing('campaign_items', ['campaign_id' => $campaign->id, 'directory_id' => $source->id]);
        $this->assertDatabaseHas('campaign_items', ['campaign_id' => $campaign->id, 'directory_id' => $firstTarget->id]);
        $this->assertDatabaseHas('campaign_items', ['campaign_id' => $campaign->id, 'directory_id' => $secondTarget->id]);
        $this->assertSame(2, $campaign->items()->count());
    }

    public function test_plan_preserves_future_start_date_and_recovers_an_empty_completed_campaign(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-09-28 12:00:00', 'UTC'));

        try {
            $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
            Directory::create(['name' => 'Birinci', 'slug' => 'birinci', 'domain' => 'birinci.test', 'status' => 'active']);
            Directory::create(['name' => 'İkinci', 'slug' => 'ikinci', 'domain' => 'ikinci.test', 'status' => 'active']);
            $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
            $city = City::create(['name' => 'Tekirdağ', 'slug' => 'tekirdag']);
            $company = Company::create([
                'name' => 'Kaynak Firma',
                'directory_id' => $source->id,
                'category_id' => $category->id,
                'city_id' => $city->id,
                'status' => 'active',
            ]);
            $start = Carbon::parse('2026-10-01 09:00:00', 'UTC');
            $campaign = Campaign::create([
                'directory_id' => $source->id,
                'company_id' => $company->id,
                'name' => 'Gelecek Planı',
                'total_directories' => 2,
                'daily_limit' => 1,
                'start_date' => $start,
                'status' => 'completed',
            ]);

            $result = app(CampaignPlanService::class)->generate($campaign);

            $this->assertSame(2, $result['created']);
            $this->assertSame('active', $campaign->fresh()->status);
            $this->assertTrue($campaign->fresh()->start_date->equalTo($start));
            $this->assertSame([
                '2026-10-01 09:00:00',
                '2026-10-02 09:00:00',
            ], $campaign->items()->orderBy('scheduled_for')->get()->map(fn ($item) => $item->scheduled_for->format('Y-m-d H:i:s'))->all());
        } finally {
            Carbon::setTestNow();
        }
    }

    public function test_campaign_without_an_active_target_stays_draft(): void
    {
        $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
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
            'name' => 'Hedefsiz Plan',
            'total_directories' => 2,
            'daily_limit' => 1,
            'status' => 'completed',
        ]);

        $result = app(CampaignPlanService::class)->generate($campaign);

        $this->assertSame(0, $result['created']);
        $this->assertSame('draft', $campaign->fresh()->status);
        $this->assertSame(0, $campaign->items()->count());
    }
}
