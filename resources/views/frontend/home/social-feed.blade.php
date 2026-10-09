@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Akış Rehberi')
@section('meta_description', $settings->meta_description ?? 'Firmaları sosyal medya akışı rahatlığında keşfedin; kaydırın, dokunun, ulaşın.')

@section('content')
@include('partials.phone.device-start')

    {{-- Hikâye halkaları: kategoriler --}}
    <div class="ph-rings" style="padding-top:12px">
        @foreach($categories->take(10) as $category)
            <a class="ph-ring" href="{{ route('categories.show', $category->slug) }}">
                <span class="ph-ring__av"><span class="ph-ring__in">{{ mb_substr($category->name, 0, 1) }}</span></span>
                <span class="ph-ring__label">{{ $category->name }}</span>
            </a>
        @endforeach
    </div>

    <form class="ph-search" action="{{ route('search') }}" method="GET">
        <input type="text" name="q" aria-label="Firma veya hizmet ara" placeholder="Ne aramıştınız?" required>
        <button type="submit">Ara</button>
    </form>

    @if($premiumCompanies->isNotEmpty())
        <section class="ph-section" style="padding-top:0">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Vitrin</span>
                    <h2 class="ph-h2">Öne çıkanlar</h2>
                </div>
            </div>
            <div class="ph-grid">
                @foreach($premiumCompanies->take(4) as $company)
                    @include('frontend.phone.cards.pocket')
                @endforeach
            </div>
        </section>
    @endif

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Akış</span>
                <h2 class="ph-h2">Yeni eklenenler</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div class="ph-grid">
            @forelse($latestCompanies->take(6) as $company)
                @include('frontend.phone.cards.pocket')
            @empty
                <div class="ph-empty" style="grid-column:1/-1">Akışta henüz firma yok.</div>
            @endforelse
        </div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Şehirler</span>
                <h2 class="ph-h2">Bölgeni seç</h2>
            </div>
        </div>
        <div class="ph-chips" style="padding:0 16px 10px">
            @foreach($cities->take(12) as $city)
                <a class="ph-chip" href="{{ route('cities.show', $city->slug) }}">{{ $city->name }}</a>
            @endforeach
        </div>
    </section>

@include('partials.phone.device-end')
@endsection
