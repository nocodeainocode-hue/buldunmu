<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use App\Models\DiscoveredCompany;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DiscoveredCompanyApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approval_copies_openstreetmap_location_data_to_company(): void
    {
        $directory = Directory::create([
            'name' => 'Test Rehber',
            'slug' => 'test-rehber',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
        $category = Category::create([
            'name' => 'Eczane',
            'slug' => 'eczane',
            'status' => 'active',
            'directory_id' => $directory->id,
        ]);
        $city = City::create([
            'name' => 'İstanbul',
            'slug' => 'istanbul',
            'directory_id' => $directory->id,
        ]);
        $discovered = DiscoveredCompany::create([
            'name' => 'Örnek Eczane',
            'external_id' => 'osm:node:123',
            'source' => 'openstreetmap',
            'source_url' => 'https://www.openstreetmap.org/node/123',
            'search_keyword' => 'eczane',
            'search_city' => 'İstanbul',
            'latitude' => 41.01,
            'longitude' => 29.02,
            'opening_hours' => 'Mo-Sa 09:00-19:00',
            'raw_data' => [
                'google_maps_url' => 'https://www.google.com/maps/search/?api=1&query=41.01,29.02',
            ],
            'status' => 'pending',
            'directory_id' => $directory->id,
        ]);

        $company = $discovered->approve([
            'category_id' => $category->id,
            'city_id' => $city->id,
        ]);

        $this->assertSame('osm:node:123', $company->external_id);
        $this->assertSame('41.0100000', $company->latitude);
        $this->assertSame('29.0200000', $company->longitude);
        $this->assertSame('Mo-Sa 09:00-19:00', $company->opening_hours);
        $this->assertStringContainsString('41.01,29.02', $company->google_maps_url);
        $this->assertSame('approved', $discovered->fresh()->status);
    }
}
