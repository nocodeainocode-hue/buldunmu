<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use App\Models\District;
use App\Models\ListingRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MergeStaleCitiesTest extends TestCase
{
    use RefreshDatabase;

    public function test_it_moves_related_records_before_deleting_a_stale_city(): void
    {
        $directory = Directory::create([
            'name' => 'Yerel Rehber',
            'slug' => 'yerel-rehber',
            'domain' => 'yerel.test',
            'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Market', 'slug' => 'market', 'status' => 'active']);
        $canonicalCity = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $staleCity = City::create([
            'name' => 'İstanbul',
            'slug' => 'istanbul',
            'directory_id' => $directory->id,
        ]);
        $canonicalDistrict = District::create([
            'city_id' => $canonicalCity->id,
            'name' => 'Kadıköy',
            'slug' => 'kadikoy',
        ]);
        $staleDistrict = District::create([
            'city_id' => $staleCity->id,
            'name' => 'Kadıköy',
            'slug' => 'kadikoy',
            'directory_id' => $directory->id,
        ]);
        $company = Company::create([
            'name' => 'Test Firma',
            'slug' => 'test-firma',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $staleCity->id,
            'district_id' => $staleDistrict->id,
            'status' => 'active',
        ]);
        $request = ListingRequest::create([
            'company_name' => 'Başvuru Firması',
            'directory_id' => $directory->id,
            'city_id' => $staleCity->id,
            'district_id' => $staleDistrict->id,
            'status' => 'new',
        ]);

        $this->artisan('cities:report-stale --merge')->assertSuccessful();

        $this->assertDatabaseMissing('cities', ['id' => $staleCity->id]);
        $this->assertDatabaseMissing('districts', ['id' => $staleDistrict->id]);
        $this->assertDatabaseHas('companies', [
            'id' => $company->id,
            'city_id' => $canonicalCity->id,
            'district_id' => $canonicalDistrict->id,
        ]);
        $this->assertDatabaseHas('listing_requests', [
            'id' => $request->id,
            'city_id' => $canonicalCity->id,
            'district_id' => $canonicalDistrict->id,
        ]);
    }
}
