<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryDirectorySetting;
use App\Models\City;
use App\Models\Company;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MergeStaleCategoriesTest extends TestCase
{
    use RefreshDatabase;

    public function test_tenant_category_copy_is_merged_without_losing_company_or_visibility(): void
    {
        $directory = Directory::create([
            'name' => 'Test Rehber',
            'slug' => 'test-rehber',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
        $shared = Category::create([
            'name' => 'Sağlık',
            'slug' => 'saglik',
            'status' => 'active',
            'directory_id' => null,
        ]);
        $copy = Category::create([
            'name' => 'Sağlık',
            'slug' => 'saglik',
            'status' => 'active',
            'directory_id' => $directory->id,
        ]);
        $city = City::create([
            'name' => 'Tekirdağ',
            'slug' => 'tekirdag',
            'directory_id' => null,
        ]);
        $company = Company::create([
            'name' => 'Örnek Klinik',
            'category_id' => $copy->id,
            'city_id' => $city->id,
            'directory_id' => $directory->id,
            'status' => 'active',
        ]);
        CategoryDirectorySetting::create([
            'category_id' => $copy->id,
            'directory_id' => $directory->id,
            'is_visible' => false,
            'sort_order' => 7,
        ]);

        $this->artisan('categories:report-stale --merge')->assertSuccessful();

        $this->assertSame($shared->id, $company->fresh()->category_id);
        $this->assertDatabaseMissing('categories', ['id' => $copy->id]);
        $this->assertDatabaseHas('category_directory_settings', [
            'category_id' => $shared->id,
            'directory_id' => $directory->id,
            'is_visible' => false,
            'sort_order' => 7,
        ]);
    }
}
