<?php

namespace App\Services;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class OpenStreetMapService
{
    private const NOMINATIM_URL = 'https://nominatim.openstreetmap.org/search';
    private const OVERPASS_URL = 'https://overpass-api.de/api/interpreter';

    /** @return array<int, array<string, mixed>> */
    public function discoverCompanies(string $keyword, string $city, int $limit = 50): array
    {
        $limit = max(1, min($limit, 100));
        $bounds = $this->findCityBounds($city);

        if (!$bounds) {
            throw new \RuntimeException("OpenStreetMap üzerinde '{$city}' konumu bulunamadı.");
        }

        $response = $this->client(60)->asForm()->post(self::OVERPASS_URL, [
            'data' => $this->buildOverpassQuery($keyword, $bounds, $limit),
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('OpenStreetMap firma sorgusu şu anda yanıt vermiyor.');
        }

        return collect($response->json('elements', []))
            ->map(fn(array $element) => $this->normalizeElement($element, $city))
            ->filter(fn(array $company) => filled($company['name']))
            ->unique('external_id')
            ->take($limit)
            ->values()
            ->all();
    }

    /** @return array{south: float, west: float, north: float, east: float}|null */
    private function findCityBounds(string $city): ?array
    {
        $response = $this->client(20)->get(self::NOMINATIM_URL, [
            'q' => trim($city) . ', Türkiye',
            'format' => 'jsonv2',
            'countrycodes' => 'tr',
            'limit' => 1,
            'email' => config('services.openstreetmap.contact'),
        ]);

        if (!$response->successful()) {
            throw new \RuntimeException('OpenStreetMap şehir araması şu anda yanıt vermiyor.');
        }

        $box = $response->json('0.boundingbox');
        if (!is_array($box) || count($box) !== 4) {
            return null;
        }

        return [
            'south' => (float) $box[0],
            'north' => (float) $box[1],
            'west' => (float) $box[2],
            'east' => (float) $box[3],
        ];
    }

    /** @param array{south: float, west: float, north: float, east: float} $bounds */
    private function buildOverpassQuery(string $keyword, array $bounds, int $limit): string
    {
        $bbox = implode(',', [$bounds['south'], $bounds['west'], $bounds['north'], $bounds['east']]);
        $statements = collect($this->selectorsFor($keyword))
            ->map(fn(string $selector) => "nwr{$selector}({$bbox});")
            ->implode("\n");

        return "[out:json][timeout:40];\n(\n{$statements}\n);\nout center {$limit};";
    }

    /** @return array<int, string> */
    private function selectorsFor(string $keyword): array
    {
        $key = Str::lower(Str::ascii(trim($keyword)));
        $groups = [
            [['restoran', 'restaurant', 'lokanta'], ['["amenity"="restaurant"]']],
            [['kafe', 'cafe', 'kahve'], ['["amenity"="cafe"]']],
            [['eczane', 'pharmacy'], ['["amenity"="pharmacy"]']],
            [['dis klinigi', 'dis hekimi', 'dentist'], ['["amenity"="dentist"]', '["healthcare"="dentist"]']],
            [['doktor', 'klinik', 'clinic'], ['["amenity"="clinic"]', '["healthcare"="clinic"]', '["healthcare"="doctor"]']],
            [['hastane', 'hospital'], ['["amenity"="hospital"]']],
            [['veteriner', 'veterinary'], ['["amenity"="veterinary"]']],
            [['avukat', 'hukuk', 'lawyer'], ['["office"="lawyer"]']],
            [['berber', 'kuafor', 'hairdresser'], ['["shop"="hairdresser"]']],
            [['oto tamir', 'arac tamir', 'car repair'], ['["shop"="car_repair"]']],
            [['emlak', 'gayrimenkul', 'estate agent'], ['["office"="estate_agent"]']],
            [['market', 'supermarket'], ['["shop"="supermarket"]', '["shop"="convenience"]']],
            [['otel', 'hotel'], ['["tourism"="hotel"]']],
            [['spor salonu', 'fitness'], ['["leisure"="fitness_centre"]']],
            [['firin', 'pastane', 'bakery'], ['["shop"="bakery"]']],
            [['elektronik', 'electronics'], ['["shop"="electronics"]']],
            [['tesisatci', 'plumber'], ['["craft"="plumber"]']],
            [['elektrikci', 'electrician'], ['["craft"="electrician"]']],
        ];

        foreach ($groups as [$terms, $selectors]) {
            if (collect($terms)->contains(fn(string $term) => Str::contains($key, $term))) {
                return $selectors;
            }
        }

        $escaped = addcslashes(trim($keyword), '\\"');
        return ['["name"~"' . $escaped . '",i]'];
    }

    /** @return array<string, mixed> */
    private function normalizeElement(array $element, string $fallbackCity): array
    {
        $tags = $element['tags'] ?? [];
        $latitude = $element['lat'] ?? data_get($element, 'center.lat');
        $longitude = $element['lon'] ?? data_get($element, 'center.lon');
        $type = $element['type'] ?? 'node';
        $id = $element['id'] ?? null;
        $address = collect([
            $tags['addr:street'] ?? null,
            $tags['addr:housenumber'] ?? null,
            $tags['addr:neighbourhood'] ?? $tags['addr:suburb'] ?? null,
            $tags['addr:district'] ?? null,
            $tags['addr:city'] ?? $fallbackCity,
        ])->filter()->implode(' ');

        $sourceUrl = $id ? "https://www.openstreetmap.org/{$type}/{$id}" : null;

        return [
            'name' => $tags['name'] ?? $tags['brand'] ?? null,
            'phone' => $tags['contact:phone'] ?? $tags['phone'] ?? null,
            'address' => $address ?: null,
            'website' => $tags['contact:website'] ?? $tags['website'] ?? null,
            'logo_url' => null,
            'email' => $tags['contact:email'] ?? $tags['email'] ?? null,
            'description' => $tags['description'] ?? null,
            'opening_hours' => $tags['opening_hours'] ?? null,
            'latitude' => is_numeric($latitude) ? (float) $latitude : null,
            'longitude' => is_numeric($longitude) ? (float) $longitude : null,
            'external_id' => $id ? "osm:{$type}:{$id}" : null,
            'source_url' => $sourceUrl,
            'google_maps_url' => is_numeric($latitude) && is_numeric($longitude)
                ? "https://www.google.com/maps/search/?api=1&query={$latitude},{$longitude}"
                : null,
            'raw_data' => $element,
        ];
    }

    private function client(int $timeout): PendingRequest
    {
        $contact = config('services.openstreetmap.contact', config('mail.from.address'));
        $appUrl = config('app.url', 'http://localhost');

        return Http::acceptJson()->timeout($timeout)->retry(2, 750)->withHeaders([
            'User-Agent' => "FirmaRehberi/1.0 ({$appUrl}; {$contact})",
        ]);
    }
}
