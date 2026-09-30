<?php

namespace Tests\Feature;

use App\Filament\Resources\Campaigns\CampaignResource;
use App\Models\Campaign;
use App\Models\CampaignItem;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CampaignProgressTest extends TestCase
{
    use RefreshDatabase;

    public function test_campaign_progress_uses_published_items_and_handles_unplanned_campaigns(): void
    {
        $source = Directory::create(['name' => 'Kaynak', 'slug' => 'kaynak', 'domain' => 'kaynak.test', 'status' => 'active']);
        $category = Category::create(['name' => 'Hizmet', 'slug' => 'hizmet', 'status' => 'active']);
        $city = City::create(['name' => 'Ankara', 'slug' => 'ankara']);
        $company = Company::create([
            'name' => 'Örnek Firma', 'directory_id' => $source->id,
            'category_id' => $category->id, 'city_id' => $city->id, 'status' => 'active',
        ]);
        $campaign = Campaign::create([
            'directory_id' => $source->id, 'company_id' => $company->id,
            'name' => 'Yayın Planı', 'total_directories' => 3, 'daily_limit' => 1, 'status' => 'active',
        ]);
        $unplanned = Campaign::create([
            'directory_id' => $source->id, 'company_id' => $company->id,
            'name' => 'Yeni Plan', 'total_directories' => 3, 'daily_limit' => 1, 'status' => 'draft',
        ]);

        foreach (['published', 'published', 'failed'] as $index => $status) {
            $target = Directory::create([
                'name' => 'Hedef '.($index + 1), 'slug' => 'hedef-'.($index + 1),
                'domain' => 'hedef-'.($index + 1).'.test', 'status' => 'active',
            ]);
            CampaignItem::create([
                'campaign_id' => $campaign->id, 'company_id' => $company->id,
                'directory_id' => $target->id, 'slug' => 'ornek-'.($index + 1), 'status' => $status,
            ]);
        }

        $plannedRecord = CampaignResource::getEloquentQuery()->findOrFail($campaign->id);
        $this->assertSame(3, $plannedRecord->items_count);
        $this->assertSame(2, $plannedRecord->published_items_count);
        $plannedHtml = view('filament.tables.columns.campaign-progress', ['record' => $plannedRecord])->render();
        $this->assertStringContainsString('2 / 3 yayınlandı', $plannedHtml);
        $this->assertStringContainsString('%67', $plannedHtml);
        $this->assertStringContainsString('aria-valuenow="67"', $plannedHtml);

        $unplannedRecord = CampaignResource::getEloquentQuery()->findOrFail($unplanned->id);
        $unplannedHtml = view('filament.tables.columns.campaign-progress', ['record' => $unplannedRecord])->render();
        $this->assertStringContainsString('0 / 0 yayınlandı', $unplannedHtml);
        $this->assertStringContainsString('aria-valuenow="0"', $unplannedHtml);
    }
}
