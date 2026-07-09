<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        <changefreq>daily</changefreq>
        <priority>1.0</priority>
    </url>
    <url>
        <loc>{{ route('companies.index') }}</loc>
        <changefreq>daily</changefreq>
        <priority>0.9</priority>
    </url>
    <url>
        <loc>{{ route('listing.create') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.6</priority>
    </url>
    <url>
        <loc>{{ route('pages.about') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    <url>
        <loc>{{ route('pages.contact') }}</loc>
        <changefreq>monthly</changefreq>
        <priority>0.5</priority>
    </url>
    @foreach($categories as $cat)
    <url>
        <loc>{{ route('categories.show', $cat->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach($cities as $city)
    <url>
        <loc>{{ route('cities.show', $city->slug) }}</loc>
        <changefreq>weekly</changefreq>
        <priority>0.7</priority>
    </url>
    @endforeach
    @foreach($companies as $company)
    <url>
        <loc>{{ route('companies.show', $company->slug) }}</loc>
        <lastmod>{{ $company->updated_at->format('Y-m-d') }}</lastmod>
        <changefreq>weekly</changefreq>
        <priority>0.8</priority>
    </url>
    @endforeach
</urlset>