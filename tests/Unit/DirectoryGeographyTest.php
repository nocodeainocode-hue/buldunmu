<?php

namespace Tests\Unit;

use App\Models\Directory;
use PHPUnit\Framework\TestCase;

class DirectoryGeographyTest extends TestCase
{
    public function test_national_directory_has_no_city_restriction(): void
    {
        $directory = new Directory(['geography_mode' => 'national']);

        $this->assertSame([], $directory->visibleCitySlugs());
    }

    public function test_local_directory_exposes_only_primary_city(): void
    {
        $directory = new Directory([
            'geography_mode' => 'local',
            'primary_city_slug' => 'tekirdag',
        ]);

        $this->assertSame(['tekirdag'], $directory->visibleCitySlugs());
    }

    public function test_custom_directory_exposes_selected_cities(): void
    {
        $directory = new Directory([
            'geography_mode' => 'custom',
            'featured_city_slugs' => ['tekirdag', 'edirne'],
        ]);

        $this->assertSame(['tekirdag', 'edirne'], $directory->visibleCitySlugs());
    }
}
