<?php

namespace Tests\Feature;

use App\Jobs\DiscoverCompaniesJob;
use App\Models\Directory;
use App\Models\DiscoveredCompany;
use App\Services\FirecrawlService;
use App\Services\OpenStreetMapService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class DiscoverCompaniesJobTest extends TestCase
{
    use RefreshDatabase;

    public function test_discovery_truncates_provider_fields_and_keeps_the_raw_payload(): void
    {
        $directory = $this->directory('birinci', 'Birinci');
        $longUrl = 'https://example.test/'.str_repeat('very-long-path/', 30);
        $result = [[
            'name' => str_repeat('Uzun Firma Adi ', 30),
            'external_id' => str_repeat('external-', 40),
            'website' => $longUrl,
            'logo_url' => $longUrl,
            'source_url' => $longUrl,
        ]];

        $this->jobFor($directory, $result)->handle($this->firecrawlReturning($result), Mockery::mock(OpenStreetMapService::class));

        $company = DiscoveredCompany::withoutGlobalScope('directory')->firstOrFail();
        $this->assertLessThanOrEqual(255, mb_strlen($company->name));
        $this->assertLessThanOrEqual(255, mb_strlen($company->external_id));
        $this->assertLessThanOrEqual(255, mb_strlen($company->source_url));
        $this->assertSame($longUrl, $company->raw_data['source_url']);
    }

    public function test_discovery_deduplication_is_scoped_to_the_selected_directory(): void
    {
        $first = $this->directory('birinci', 'Birinci');
        $second = $this->directory('ikinci', 'Ikinci');
        $result = [[
            'name' => 'Ornek Eczane',
            'external_id' => 'maps:example-123',
        ]];

        $this->jobFor($first, $result)->handle($this->firecrawlReturning($result), Mockery::mock(OpenStreetMapService::class));
        $this->jobFor($second, $result)->handle($this->firecrawlReturning($result), Mockery::mock(OpenStreetMapService::class));

        $this->assertSame(2, DiscoveredCompany::withoutGlobalScope('directory')->count());
    }

    private function directory(string $slug, string $name): Directory
    {
        return Directory::create([
            'name' => $name,
            'slug' => $slug,
            'domain' => $slug.'.test',
            'status' => 'active',
        ]);
    }

    private function jobFor(Directory $directory, array $results): DiscoverCompaniesJob
    {
        return new DiscoverCompaniesJob([
            'keyword' => 'eczane',
            'city' => 'Istanbul',
            'source' => 'google_maps',
        ], null, $directory->id);
    }

    private function firecrawlReturning(array $results): FirecrawlService
    {
        $firecrawl = Mockery::mock(FirecrawlService::class);
        $firecrawl->shouldReceive('discoverCompanies')->once()->andReturn($results);

        return $firecrawl;
    }
}
