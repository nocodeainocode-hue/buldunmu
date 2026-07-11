<?php

namespace App\Support;

use App\Models\Company;
use App\Models\Directory;
use App\Models\Post;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class SeoSchema
{
    public static function home(?SiteSetting $settings, ?Directory $directory): array
    {
        $name = $directory?->name ?: $settings?->site_name ?: config('app.name');
        $home = route('home');
        $logoPath = $directory?->logo ?: $settings?->logo;
        $organizationId = $home . '#organization';

        $organization = [
            '@type' => 'Organization',
            '@id' => $organizationId,
            'name' => $name,
            'url' => $home,
            'description' => $directory?->meta_description ?: $settings?->meta_description,
            'logo' => $logoPath ? [
                '@type' => 'ImageObject',
                'url' => asset('storage/' . $logoPath),
                'contentUrl' => asset('storage/' . $logoPath),
            ] : null,
            'email' => $settings?->email,
            'telephone' => $settings?->phone,
            'address' => $settings?->address ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $settings->address,
                'addressCountry' => 'TR',
            ] : null,
        ];

        $website = [
            '@type' => 'WebSite',
            '@id' => $home . '#website',
            'url' => $home,
            'name' => $name,
            'alternateName' => array_values(array_unique(array_filter([
                Str::lower(request()->getHost()),
                $directory?->slug,
            ]))),
            'inLanguage' => 'tr-TR',
            'publisher' => ['@id' => $organizationId],
        ];

        return self::graph([$website, $organization]);
    }

    public static function company(Company $company): array
    {
        $url = route('companies.show', $company->slug);
        $businessId = $url . '#business';
        $city = $company->city?->name;
        $district = $company->district?->name;
        $images = collect([$company->cover_image, $company->logo])
            ->filter()->map(fn(string $path) => asset('storage/' . $path))->values()->all();
        $reviews = $company->approvedReviews->map(fn($review) => [
            '@type' => 'Review',
            'author' => ['@type' => 'Person', 'name' => $review->name],
            'datePublished' => ($review->approved_at ?: $review->created_at)?->toIso8601String(),
            'reviewBody' => $review->comment,
            'reviewRating' => [
                '@type' => 'Rating',
                'ratingValue' => (int) $review->rating,
                'bestRating' => 5,
                'worstRating' => 1,
            ],
        ])->all();

        $business = [
            '@type' => self::businessType($company->category?->name),
            '@id' => $businessId,
            'name' => $company->name,
            'url' => $url,
            'mainEntityOfPage' => ['@id' => $url . '#webpage'],
            'description' => $company->short_description ?: Str::limit(strip_tags($company->description ?? ''), 300),
            'image' => $images,
            'logo' => $company->logo ? asset('storage/' . $company->logo) : null,
            'telephone' => $company->phone,
            'email' => $company->email,
            'sameAs' => array_values(array_filter([$company->website])),
            'address' => ($company->address || $city) ? [
                '@type' => 'PostalAddress',
                'streetAddress' => $company->address,
                'addressLocality' => $district ?: $city,
                'addressRegion' => $city,
                'addressCountry' => 'TR',
            ] : null,
            'areaServed' => $city ? ['@type' => 'AdministrativeArea', 'name' => $city] : null,
            'geo' => ($company->latitude && $company->longitude) ? [
                '@type' => 'GeoCoordinates',
                'latitude' => (float) $company->latitude,
                'longitude' => (float) $company->longitude,
            ] : null,
            'openingHoursSpecification' => self::openingHours($company->opening_hours),
            'aggregateRating' => count($reviews) && $company->reviews_avg_rating ? [
                '@type' => 'AggregateRating',
                'ratingValue' => round((float) $company->reviews_avg_rating, 1),
                'reviewCount' => count($reviews),
                'bestRating' => 5,
                'worstRating' => 1,
            ] : null,
            'review' => $reviews,
        ];

        $webPage = [
            '@type' => 'WebPage',
            '@id' => $url . '#webpage',
            'url' => $url,
            'name' => $company->meta_title ?: $company->name,
            'description' => $company->meta_description ?: $company->short_description,
            'inLanguage' => 'tr-TR',
            'mainEntity' => ['@id' => $businessId],
            'dateModified' => $company->updated_at?->toIso8601String(),
        ];

        return self::graph([
            $webPage,
            self::breadcrumb([
                ['name' => 'Ana Sayfa', 'url' => route('home')],
                $company->category ? ['name' => $company->category->name, 'url' => route('categories.show', $company->category->slug)] : null,
                $company->city ? ['name' => $company->city->name, 'url' => route('cities.show', $company->city->slug)] : null,
                ['name' => $company->name, 'url' => $url],
            ], $url),
            $business,
        ]);
    }

    public static function listing(string $name, string $description, string $url, Collection $companies, array $breadcrumbs): array
    {
        return self::graph([
            [
                '@type' => 'CollectionPage',
                '@id' => $url . '#webpage',
                'url' => $url,
                'name' => $name,
                'description' => $description,
                'inLanguage' => 'tr-TR',
                'mainEntity' => ['@id' => $url . '#itemlist'],
            ],
            self::breadcrumb($breadcrumbs, $url),
            [
                '@type' => 'ItemList',
                '@id' => $url . '#itemlist',
                'name' => $name,
                'numberOfItems' => $companies->count(),
                'itemListElement' => $companies->values()->map(fn(Company $company, int $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('companies.show', $company->slug),
                    'name' => $company->name,
                ])->all(),
            ],
        ]);
    }

    public static function blogPost(Post $post, ?Directory $directory, ?SiteSetting $settings): array
    {
        $url = route('blog.show', $post->slug);
        $publisherName = $directory?->name ?: $settings?->site_name ?: config('app.name');
        $logoPath = $directory?->logo ?: $settings?->logo;

        return self::graph([
            [
                '@type' => 'BlogPosting',
                '@id' => $url . '#article',
                'mainEntityOfPage' => ['@type' => 'WebPage', '@id' => $url],
                'headline' => $post->title,
                'description' => $post->excerpt,
                'image' => $post->image ? [asset('storage/' . $post->image)] : null,
                'datePublished' => $post->published_at?->toIso8601String(),
                'dateModified' => $post->updated_at?->toIso8601String(),
                'inLanguage' => 'tr-TR',
                'author' => ['@type' => 'Organization', 'name' => $publisherName, 'url' => route('home')],
                'publisher' => [
                    '@type' => 'Organization',
                    'name' => $publisherName,
                    'url' => route('home'),
                    'logo' => $logoPath ? ['@type' => 'ImageObject', 'url' => asset('storage/' . $logoPath)] : null,
                ],
            ],
            self::breadcrumb([
                ['name' => 'Ana Sayfa', 'url' => route('home')],
                ['name' => 'Blog', 'url' => route('blog.index')],
                ['name' => $post->title, 'url' => $url],
            ], $url),
        ]);
    }

    public static function blogListing(Collection $posts): array
    {
        $url = route('blog.index');

        return self::graph([
            [
                '@type' => 'CollectionPage',
                '@id' => $url . '#webpage',
                'url' => $url,
                'name' => 'Blog',
                'inLanguage' => 'tr-TR',
                'mainEntity' => ['@id' => $url . '#itemlist'],
            ],
            self::breadcrumb([
                ['name' => 'Ana Sayfa', 'url' => route('home')],
                ['name' => 'Blog', 'url' => $url],
            ], $url),
            [
                '@type' => 'ItemList',
                '@id' => $url . '#itemlist',
                'numberOfItems' => $posts->count(),
                'itemListElement' => $posts->values()->map(fn(Post $post, int $index) => [
                    '@type' => 'ListItem',
                    'position' => $index + 1,
                    'url' => route('blog.show', $post->slug),
                    'name' => $post->title,
                ])->all(),
            ],
        ]);
    }

    public static function breadcrumb(array $items, string $pageUrl): array
    {
        $items = array_values(array_filter($items));

        return [
            '@type' => 'BreadcrumbList',
            '@id' => $pageUrl . '#breadcrumb',
            'itemListElement' => collect($items)->map(fn(array $item, int $index) => [
                '@type' => 'ListItem',
                'position' => $index + 1,
                'name' => $item['name'],
                'item' => $item['url'] ?? null,
            ])->all(),
        ];
    }

    private static function businessType(?string $category): string
    {
        $name = Str::lower($category ?? '');

        return match (true) {
            Str::contains($name, ['restoran', 'lokanta', 'kebap', 'ciğer']) => 'Restaurant',
            Str::contains($name, ['diş', 'dentist']) => 'Dentist',
            Str::contains($name, ['eczane']) => 'Pharmacy',
            Str::contains($name, ['otel', 'konaklama']) => 'Hotel',
            Str::contains($name, ['oto tamir', 'oto servis']) => 'AutoRepair',
            Str::contains($name, ['avukat', 'hukuk']) => 'Attorney',
            Str::contains($name, ['emlak']) => 'RealEstateAgent',
            Str::contains($name, ['kuaför', 'güzellik']) => 'BeautySalon',
            Str::contains($name, ['spor salonu', 'fitness']) => 'HealthClub',
            Str::contains($name, ['market', 'mağaza']) => 'Store',
            default => 'LocalBusiness',
        };
    }

    private static function openingHours(?string $hours): array
    {
        if (blank($hours)) {
            return [];
        }

        $days = [
            'pazartesi' => 'Monday', 'salı' => 'Tuesday', 'sali' => 'Tuesday',
            'çarşamba' => 'Wednesday', 'carsamba' => 'Wednesday',
            'perşembe' => 'Thursday', 'persembe' => 'Thursday', 'cuma' => 'Friday',
            'cumartesi' => 'Saturday', 'pazar' => 'Sunday',
        ];
        $text = Str::lower($hours);

        if (preg_match('/\b(7\s*\/\s*24|24\s*saat)\b/u', $text)) {
            return collect(array_unique($days))->map(fn(string $day) => [
                '@type' => 'OpeningHoursSpecification', 'dayOfWeek' => $day,
                'opens' => '00:00', 'closes' => '23:59',
            ])->values()->all();
        }

        $specifications = [];
        foreach (preg_split('/\R/u', $text) as $line) {
            foreach ($days as $needle => $schemaDay) {
                if (!Str::contains($line, $needle)) {
                    continue;
                }
                preg_match_all('/\b([01]?\d|2[0-3])[:.]([0-5]\d)\b/', $line, $matches, PREG_SET_ORDER);
                if (count($matches) >= 2) {
                    $specifications[] = [
                        '@type' => 'OpeningHoursSpecification',
                        'dayOfWeek' => $schemaDay,
                        'opens' => sprintf('%02d:%02d', $matches[0][1], $matches[0][2]),
                        'closes' => sprintf('%02d:%02d', $matches[1][1], $matches[1][2]),
                    ];
                }
                break;
            }
        }

        return $specifications;
    }

    private static function graph(array $nodes): array
    {
        return self::clean([
            '@context' => 'https://schema.org',
            '@graph' => array_values(array_filter($nodes)),
        ]);
    }

    private static function clean(array $data): array
    {
        foreach ($data as $key => $value) {
            if (is_array($value)) {
                $value = self::clean($value);
            }
            if ($value === null || $value === '' || $value === []) {
                unset($data[$key]);
            } else {
                $data[$key] = $value;
            }
        }

        return $data;
    }
}
