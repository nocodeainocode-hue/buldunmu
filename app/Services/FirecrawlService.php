<?php

namespace App\Services;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class FirecrawlService
{
    protected Client $client;
    protected string $apiKey;
    protected string $baseUrl = 'https://api.firecrawl.dev/v1';

    public function __construct()
    {
        $this->apiKey = config('services.firecrawl.key', env('FIRECRAWL_API_KEY', ''));
        $this->client = new Client([
            'base_uri' => $this->baseUrl,
            'timeout' => 120,
            'headers' => [
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    /**
     * Discover companies by keyword + city using Firecrawl search.
     *
     * @param string $keyword  e.g. "restoran"
     * @param string $city     e.g. "İstanbul"
     * @param string $source   'google_maps' | 'search' | 'custom_url'
     * @param string|null $customUrl  Required if source is 'custom_url'
     * @return array<int, array{name:string, phone:string|null, address:string|null, website:string|null, logo_url:string|null, email:string|null, description:string|null}>
     */
    public function discoverCompanies(string $keyword, string $city, string $source = 'search', ?string $customUrl = null): array
    {
        if ($source === 'custom_url' && $customUrl) {
            return $this->scrapeUrl($customUrl);
        }

        if ($source === 'google_maps') {
            return $this->searchGoogleMaps($keyword, $city);
        }

        return $this->searchWeb($keyword, $city);
    }

    /**
     * Search the web for companies matching keyword + city.
     */
    protected function searchWeb(string $keyword, string $city): array
    {
        $query = "{$keyword} {$city} firma telefon adres website";

        try {
            $response = $this->client->post('/search', [
                'json' => [
                    'query' => $query,
                    'limit' => 20,
                    'scrapeOptions' => [
                        'formats' => ['markdown'],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $this->parseSearchResults($data, $keyword, $city, 'search');
        } catch (GuzzleException $e) {
            Log::error('Firecrawl search failed: ' . $e->getMessage());
            throw new \RuntimeException('Firma keşfi sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Search Google Maps for businesses.
     */
    protected function searchGoogleMaps(string $keyword, string $city): array
    {
        $query = "site:google.com/maps {$keyword} {$city}";

        try {
            $response = $this->client->post('/search', [
                'json' => [
                    'query' => $query,
                    'limit' => 20,
                    'scrapeOptions' => [
                        'formats' => ['markdown'],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);
            return $this->parseSearchResults($data, $keyword, $city, 'google_maps');
        } catch (GuzzleException $e) {
            Log::error('Firecrawl Google Maps search failed: ' . $e->getMessage());
            throw new \RuntimeException('Google Maps araması sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Scrape a specific URL to extract company data.
     */
    protected function scrapeUrl(string $url): array
    {
        try {
            $response = $this->client->post('/scrape', [
                'json' => [
                    'url' => $url,
                    'formats' => ['markdown', 'extract'],
                    'extract' => [
                        'schema' => [
                            'type' => 'object',
                            'properties' => [
                                'companies' => [
                                    'type' => 'array',
                                    'items' => [
                                        'type' => 'object',
                                        'properties' => [
                                            'name' => ['type' => 'string'],
                                            'phone' => ['type' => 'string'],
                                            'address' => ['type' => 'string'],
                                            'website' => ['type' => 'string'],
                                            'email' => ['type' => 'string'],
                                            'description' => ['type' => 'string'],
                                        ],
                                    ],
                                ],
                            ],
                        ],
                    ],
                ],
            ]);

            $data = json_decode($response->getBody()->getContents(), true);

            if (!empty($data['data']['extract']['companies'])) {
                return array_map(function ($company) use ($url) {
                    return [
                        'name' => $company['name'] ?? 'Bilinmeyen Firma',
                        'phone' => $company['phone'] ?? null,
                        'address' => $company['address'] ?? null,
                        'website' => $company['website'] ?? null,
                        'logo_url' => null,
                        'email' => $company['email'] ?? null,
                        'description' => $company['description'] ?? null,
                    ];
                }, $data['data']['extract']['companies']);
            }

            // Fallback: parse markdown content
            $markdown = $data['data']['markdown'] ?? '';
            return $this->parseMarkdownForCompanies($markdown, $url);

        } catch (GuzzleException $e) {
            Log::error('Firecrawl scrape failed: ' . $e->getMessage());
            throw new \RuntimeException('URL kazıma sırasında bir hata oluştu: ' . $e->getMessage());
        }
    }

    /**
     * Parse Firecrawl search API response into structured company data.
     */
    protected function parseSearchResults(array $data, string $keyword, string $city, string $source): array
    {
        $results = [];

        $items = $data['data'] ?? [];

        foreach ($items as $item) {
            $markdown = $item['markdown'] ?? '';
            $metadata = $item['metadata'] ?? [];
            $url = $metadata['sourceURL'] ?? $item['url'] ?? '';

            $company = $this->extractCompanyFromMarkdown($markdown, $url);
            $company['name'] = $company['name'] ?: ($metadata['title'] ?? ($metadata['og:title'] ?? 'Bilinmeyen Firma'));

            // Deduplicate by name
            $key = mb_strtolower(trim($company['name']));
            if (!isset($results[$key]) && $company['name'] !== 'Bilinmeyen Firma') {
                $results[$key] = $company;
            }
        }

        return array_values($results);
    }

    /**
     * Extract company details from markdown text using heuristics.
     */
    protected function extractCompanyFromMarkdown(string $markdown, string $sourceUrl = ''): array
    {
        $company = [
            'name' => null,
            'phone' => null,
            'address' => null,
            'website' => null,
            'logo_url' => null,
            'email' => null,
            'description' => null,
        ];

        // Try to find company name from first heading
        if (preg_match('/^#\s+(.+)$/m', $markdown, $m)) {
            $company['name'] = trim($m[1]);
        }

        // Phone patterns (Turkish and international)
        if (preg_match('/(?:Tel(?:efon)?|Phone|Telefon)\s*:?\s*([+\d][\d\s\-()]+)/i', $markdown, $m)) {
            $company['phone'] = trim($m[1]);
        } elseif (preg_match('/(0\d{3}[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2})/', $markdown, $m)) {
            $company['phone'] = trim($m[1]);
        } elseif (preg_match('/(\+\d{1,3}[\s\-]?\d{3}[\s\-]?\d{3}[\s\-]?\d{2}[\s\-]?\d{2})/', $markdown, $m)) {
            $company['phone'] = trim($m[1]);
        }

        // Email
        if (preg_match('/([a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,})/', $markdown, $m)) {
            $company['email'] = trim($m[1]);
        }

        // Website
        if (preg_match('/(?:Web(?:site)?|Site|URL)\s*:?\s*(https?:\/\/[^\s]+)/i', $markdown, $m)) {
            $company['website'] = trim($m[1]);
        } elseif (preg_match('/(https?:\/\/[^\s\)]+)/', $markdown, $m)) {
            $candidate = trim($m[1]);
            // Filter out common non-business URLs
            if (!str_contains($candidate, 'google.com') && !str_contains($candidate, 'facebook.com') && !str_contains($candidate, 'twitter.com') && !str_contains($candidate, 'instagram.com')) {
                $company['website'] = $candidate;
            }
        }

        // Address
        if (preg_match('/(?:Adres|Address|Adresi)\s*:?\s*(.+)$/im', $markdown, $m)) {
            $company['address'] = trim($m[1]);
        } elseif (preg_match('/((?:Mah\.|Cad\.|Sok\.|Blv\.|Bulvarı|Caddesi|Sokağı|Mahallesi)\s+.+)/i', $markdown, $m)) {
            $company['address'] = trim($m[1]);
        }

        // Description - first paragraph after name
        $paragraphs = explode("\n\n", $markdown);
        if (count($paragraphs) > 1) {
            $text = trim(strip_tags($paragraphs[1]));
            if (strlen($text) > 20 && strlen($text) < 500) {
                $company['description'] = $text;
            }
        }

        // Logo - try to find an image URL
        if (preg_match('/!\[.*?\]\((https?:\/\/[^\s\)]+\.(?:png|jpg|jpeg|gif|webp|svg))\)/i', $markdown, $m)) {
            $company['logo_url'] = trim($m[1]);
        }

        // If we have a source URL and no website, use domain
        if (empty($company['website']) && !empty($sourceUrl)) {
            $parsed = parse_url($sourceUrl);
            if ($parsed && isset($parsed['host'])) {
                $company['website'] = ($parsed['scheme'] ?? 'https') . '://' . $parsed['host'];
            }
        }

        return $company;
    }

    /**
     * Fallback: parse markdown for company info when structured extraction fails.
     */
    protected function parseMarkdownForCompanies(string $markdown, string $sourceUrl): array
    {
        $companies = [];

        // Split by headings or horizontal rules
        $sections = preg_split('/\n(?=#{1,3}\s)|(\n---\n)/', $markdown);

        foreach ($sections as $section) {
            $section = trim($section);
            if (empty($section) || strlen($section) < 30) {
                continue;
            }

            $company = $this->extractCompanyFromMarkdown($section, $sourceUrl);
            if ($company['name'] || $company['phone']) {
                $company['name'] = $company['name'] ?: 'Bilinmeyen Firma';
                $companies[] = $company;
            }
        }

        // If no sections found, try the whole document
        if (empty($companies)) {
            $company = $this->extractCompanyFromMarkdown($markdown, $sourceUrl);
            if ($company['name'] || $company['phone'] || $company['website']) {
                $company['name'] = $company['name'] ?: 'Bilinmeyen Firma';
                $companies[] = $company;
            }
        }

        return $companies;
    }
}
