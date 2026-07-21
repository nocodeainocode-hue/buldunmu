<?php

namespace Tests\Feature;

use App\Models\Directory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PwaManifestTest extends TestCase
{
    use RefreshDatabase;

    public function test_manifest_returns_json(): void
    {
        $response = $this->get('/site.webmanifest');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'application/manifest+json');
        $json = $response->json();
        $this->assertArrayHasKey('name', $json);
        $this->assertEquals('standalone', $json['display']);
        $this->assertEquals('/?utm_source=pwa', $json['start_url']);
    }

    public function test_offline_page_loads(): void
    {
        $response = $this->get('/offline');
        $response->assertStatus(200);
        $response->assertSee('evrimd');
    }
}
