@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Kategori ve şehirlerde binlerce firma kaydı, güncel iş ilanları.')

@section('content')
@php
    $apFeatured = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(6) : $latestCompanies->take(6);
@endphp
@include('partials.appshell.device-start')

<div class="ap-search">
    <form action="{{ route('search') }}" method="GET" role="search">
        <input type="search" name="q" placeholder="Firma, hizmet veya şehir ara" aria-label="Ara">
        <button class="ap-search__go" type="submit" aria-label="Ara">⌕</button>
    </form>
</div>

@if($categories->isNotEmpty())
    <nav class="ap-chips" aria-label="Kategoriler">
        @foreach($categories->take(14) as $category)
            <a class="ap-chip" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}<span class="ap-chip__n">{{ $category->companies_count }}</span></a>
        @endforeach
    </nav>
@endif

<div class="ap-stats">
    <div><b>{{ number_format($cities->count() ?: ($settings->city_count ?? 0)) }}</b><span>Şehir</span></div>
    <div><b>{{ number_format($settings->company_count ?? $apFeatured->count()) }}</b><span>Firma</span></div>
    <div><b>{{ number_format($categories->count()) }}</b><span>Kategori</span></div>
</div>

<div class="ap-sec">
    <div class="ap-sec__head"><h2>Öne çıkan firmalar</h2><a href="{{ route('companies.index') }}" style="font-size:12px;font-weight:700;color:var(--primary)">Tümü ›</a></div>
</div>
<div class="ap-list">
    @forelse($apFeatured as $company)
        <a class="ap-row" href="{{ route('companies.show', $company->slug) }}">
            <span class="ap-row__av">
                @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
            </span>
            <span class="ap-row__main">
                <h3>{{ $company->name }}@if($company->is_premium) ★@endif</h3>
                <p class="ap-row__meta">{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}</p>
            </span>
            <span class="ap-row__chev">›</span>
        </a>
    @empty
        <div class="ap-empty">Henüz firma kaydı bulunmuyor. <a href="{{ route('owner.register') }}">İlk firmayı siz ekleyin.</a></div>
    @endforelse
</div>

<div class="ap-sec">
    <div class="ap-sec__head"><h2>Şehirler</h2><a href="{{ route('companies.index') }}" style="font-size:12px;font-weight:700;color:var(--primary)">Tümü ›</a></div>
</div>
<div class="ap-grid">
    @foreach($cities->take(8) as $city)
        <a class="ap-tile" href="{{ route('cities.show', $city->slug) }}"><b>{{ $city->name }}</b><small>{{ $city->companies_count }} firma</small></a>
    @endforeach
</div>

@if($homeJobs->isNotEmpty())
    <div class="ap-sec">
        <div class="ap-sec__head"><h2>Güncel iş ilanları</h2><a href="{{ route('jobs.index') }}" style="font-size:12px;font-weight:700;color:var(--primary)">Tümü ›</a></div>
    </div>
    <div class="ap-list">
        @foreach($homeJobs as $job)
            <a class="ap-row" href="{{ route('jobs.show', $job->slug) }}">
                <span class="ap-row__main"><h3>{{ $job->title }}</h3><p class="ap-row__meta">{{ $job->company?->name ?? 'Firma' }} · {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p></span>
                <span class="ap-row__chev">›</span>
            </a>
        @endforeach
    </div>
@endif

@if($posts->isNotEmpty())
    <div class="ap-sec">
        <div class="ap-sec__head"><h2>Rehber yazıları</h2><a href="{{ route('blog.index') }}" style="font-size:12px;font-weight:700;color:var(--primary)">Tümü ›</a></div>
    </div>
    <div class="ap-feed">
        @foreach($posts->take(3) as $post)
            <a class="ap-post" href="{{ route('blog.show', $post->slug) }}">
                <span class="ap-post__cover @if(!$post->image) ap-post__cover--ph @endif">
                    @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">@else✎@endif
                </span>
                <span class="ap-post__body">
                    <span class="ap-post__cat">{{ $post->author_name ?: 'Editör' }}</span>
                    <h3>{{ $post->title }}</h3>
                </span>
            </a>
        @endforeach
    </div>
@endif

<div style="padding:8px 16px 20px"><a class="ap-btn ap-btn--block" href="{{ route('owner.register') }}">Ücretsiz firma ekle</a></div>

@include('partials.appshell.device-end')
@endsection
