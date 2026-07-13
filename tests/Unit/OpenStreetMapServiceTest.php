<?php

namespace Tests\Unit;

use App\Services\OpenStreetMapService;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OpenStreetMapServiceTest extends TestCase
{
    public function test_it_discovers_and_normalizes_companies(): void
    {
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([[
                'boundingbox' => ['40.8', '41.2', '28.5', '29.5'],
            ]]),
            'overpass-api.de/*' => Http::response([
                'elements' => [[
                    'type' => 'node',
                    'id' => 123,
                    'lat' => 41.01,
                    'lon' => 29.02,
                    'tags' => [
                        'name' => 'Örnek Diş Kliniği',
                        'phone' => '+90 212 555 00 00',
                        'website' => 'https://example.test',
                        'addr:street' => 'Bağdat Caddesi',
                        'addr:housenumber' => '10',
                        'addr:district' => 'Kadıköy',
                        'opening_hours' => 'Mo-Sa 09:00-19:00',
                    ],
                ]],
            ]),
        ]);

        $results = app(OpenStreetMapService::class)
            ->discoverCompanies('diş kliniği', 'İstanbul', 25);

        $this->assertCount(1, $results);
        $this->assertSame('Örnek Diş Kliniği', $results[0]['name']);
        $this->assertSame('osm:node:123', $results[0]['external_id']);
        $this->assertSame(41.01, $results[0]['latitude']);
        $this->assertSame(29.02, $results[0]['longitude']);
        $this->assertStringContainsString('Kadıköy', $results[0]['address']);
        $this->assertStringContainsString('41.01,29.02', $results[0]['google_maps_url']);

        Http::assertSent(fn($request) =>
            str_contains($request->url(), 'overpass-api.de')
            && str_contains((string) $request['data'], '["amenity"="dentist"]')
        );
    }

    public function test_it_throws_when_city_cannot_be_found(): void
    {
        Http::fake([
            'nominatim.openstreetmap.org/*' => Http::response([]),
        ]);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('konumu bulunamadı');

        app(OpenStreetMapService::class)->discoverCompanies('eczane', 'Olmayan Şehir');
    }
}
