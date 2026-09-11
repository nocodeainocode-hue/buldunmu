<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SystemPreflightTest extends TestCase
{
    use RefreshDatabase;

    public function test_preflight_succeeds_with_shared_catalog_and_directory_settings(): void
    {
        Directory::create([
            'name' => 'Test Rehber',
            'slug' => 'test-rehber',
            'domain' => 'test.local',
            'status' => 'active',
        ]);

        Category::create([
            'name' => 'Genel',
            'slug' => 'genel',
            'status' => 'active',
            'directory_id' => null,
        ]);

        foreach (range(1, 81) as $plate) {
            City::create([
                'name' => 'Şehir '.$plate,
                'slug' => 'sehir-'.$plate,
                'plate_code' => $plate,
                'directory_id' => null,
            ]);
        }

        $this->artisan('system:preflight')->assertSuccessful();
    }
}
