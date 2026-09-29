@extends('layouts.app')

@section('title', $settings->homepage_title ?? ($directory->name ?? 'Türkiye Firma Atlası'))
@section('meta_description', $settings->meta_description ?? 'Türkiye’nin 81 ilinde firmaları harita üzerinde keşfedin. İl ve kategori seçerek işletme konumlarına ulaşın.')

@push('head')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css">
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" defer></script>
    <link rel="stylesheet" href="{{ asset('css/atlas.css') }}">
@endpush

@section('content')
<div class="atlas-theme-home">
    @include('frontend.atlas.content')

    @if($categories->isNotEmpty())
        <section class="atlas-home-section" aria-labelledby="atlas-category-title">
            <div class="atlas-home-section-head">
                <div><span class="atlas-sidebar-kicker">SEKTÖRÜNÜ SEÇ</span><h2 id="atlas-category-title">Ne arıyorsun?</h2></div>
                <a href="{{ route('companies.index') }}">Tüm firmalar <span aria-hidden="true">→</span></a>
            </div>
            <div class="atlas-category-grid">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}">
                        <span class="atlas-category-icon">{{ $category->icon ?? '◆' }}</span>
                        <strong>{{ $category->name }}</strong>
                        <small>{{ number_format($category->companies_count, 0, ',', '.') }} firma</small>
                        <span class="atlas-category-arrow" aria-hidden="true">↗</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if($premiumCompanies->isNotEmpty() || $latestCompanies->isNotEmpty())
        <section class="atlas-home-section atlas-home-companies" aria-labelledby="atlas-featured-title">
            <div class="atlas-home-section-head">
                <div><span class="atlas-sidebar-kicker">FİRMA VİTRİNİ</span><h2 id="atlas-featured-title">Haritanın öne çıkanları</h2></div>
                <a href="{{ route('companies.index') }}">Firmaları keşfet <span aria-hidden="true">→</span></a>
            </div>
            <div class="atlas-featured-grid">
                @foreach(($premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies)->take(6) as $company)
                    @include('partials.cards.horizontal', ['company' => $company, 'premium' => $company->is_premium])
                @endforeach
            </div>
        </section>
    @endif

    @include('partials.blog-section')
</div>
@endsection

@push('scripts')
<script id="atlas-provinces-data" type="application/json">@json($provinces)</script>
<script src="{{ asset('js/atlas.js') }}" defer></script>
@endpush
