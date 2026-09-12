<?php

namespace Tests\Feature;

use App\Models\Campaign;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Services\CampaignPlanService;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
