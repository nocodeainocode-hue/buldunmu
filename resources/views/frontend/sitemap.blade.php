<?php echo '<?xml version="1.0" encoding="UTF-8"?>'; ?>
@php $kvDate = fn ($value) => $value ? \Illuminate\Support\Carbon::parse($value)->format('Y-m-d') : null; @endphp
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>{{ url('/') }}</loc>
        @if($lastChange)<lastmod>{{ $kvDate($lastChange) }}</lastmod>@endif
    </url>
    <url>
        <loc>{{ route('companies.index') }}</loc>
        @if($companies->isNotEmpty())<lastmod>{{ $kvDate($companies->max('updated_at')) }}</lastmod>@endif
    </url>
    <url>
        <loc>{{ route('owner.register') }}</loc>
    </url>
    @if($posts->isNotEmpty())
    <url>
        <loc>{{ route('blog.index') }}</loc>
        <lastmod>{{ $kvDate($posts->max('updated_at')) }}</lastmod>
    </url>
    @endif
    @if($jobs->isNotEmpty())
    <url>
        <loc>{{ route('jobs.index') }}</loc>
        <lastmod>{{ $kvDate($jobs->max('updated_at')) }}</lastmod>
    </url>
    @endif
    <url>
        <loc>{{ route('pages.about') }}</loc>
    </url>
    <url>
        <loc>{{ route('pages.contact') }}</loc>
    </url>
    @foreach($categories as $cat)
    <url>
        <loc>{{ route('categories.show', $cat->slug) }}</loc>
        @if($cat->companies_updated_at)<lastmod>{{ $kvDate($cat->companies_updated_at) }}</lastmod>@endif
    </url>
    @endforeach
    @foreach($cities as $city)
    <url>
        <loc>{{ route('cities.show', $city->slug) }}</loc>
        @if($city->companies_updated_at)<lastmod>{{ $kvDate($city->companies_updated_at) }}</lastmod>@endif
    </url>
    @endforeach
    @if($otherCities)
    <url>
        <loc>{{ route('cities.other') }}</loc>
    </url>
    @endif
    @foreach($companies as $company)
    <url>
        <loc>{{ route('companies.show', $company->slug) }}</loc>
        <lastmod>{{ $company->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
    @foreach($posts as $post)
    <url>
        <loc>{{ route('blog.show', $post->slug) }}</loc>
        <lastmod>{{ $post->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
    @foreach($jobs as $job)
    <url>
        <loc>{{ route('jobs.show', $job->slug) }}</loc>
        <lastmod>{{ $job->updated_at->format('Y-m-d') }}</lastmod>
    </url>
    @endforeach
</urlset>
