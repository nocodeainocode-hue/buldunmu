@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? $settings->site_name ?? 'Mahalle Panosu')
@section('meta_description', $settings->meta_description ?? 'Mahalledeki işletmeleri, ustaları, iş ilanlarını ve yeni fırsatları tek panoda keşfedin.')

@section('content')
@php
    $bdPinned = $premiumCompanies->take(4);
    $bdNewToday = \App\Models\Company::active()->where('created_at', '>=', now()->subDays(7))->count();
    $bdTotal = \App\Models\Company::active()->count();
    $bdJobTypes = ['part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj'];
@endphp
<div class="bd">
    @include('partials.board.header')

    <div class="bd-wrap">
        <section class="bd-hero">
            <div class="bd-hero__row">
                <div>
                    <h1>{{ $settings->homepage_title ?? 'Mahallenin panosu, işini bulduran yer.' }}</h1>
                    <p class="bd-hero__sub">{{ $settings->homepage_subtitle ?? 'Usta, işletme ve iş ilanlarını tek panoda gör; telefonu aç, hemen ara.' }}</p>
                </div>
                <div class="bd-pulse" aria-label="Pano özeti">
                    <div><strong>{{ number_format($bdTotal, 0, ',', '.') }}</strong><span>aktif ilan</span></div>
                    <div><strong><i></i>{{ $bdNewToday }}</strong><span>bu hafta yeni</span></div>
                    <div><strong>{{ $categories->count() }}</strong><span>kategori</span></div>
                </div>
            </div>

            @if($bdPinned->isNotEmpty())
                <section class="bd-pinboard" aria-labelledby="bd-pin-h">
                    <div class="bd-pinboard__head"><h2 id="bd-pin-h">📌 Panoya sabitlenenler</h2><span>Öne çıkan firmalar</span></div>
                    <div class="bd-pinboard__grid">
                        @foreach($bdPinned as $company)
                            <a class="bd-note" href="{{ route('companies.show', $company->slug) }}">
                                <span class="bd-note__cat">{{ $company->category?->name ?? 'İşletme' }}</span>
                                <h3>{{ $company->name }}</h3>
                                <p>{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Hizmetleri ve iletişim bilgileri için ilanı açın.', 110) }}</p>
                                <div class="bd-note__foot"><span>{{ $company->city?->name ?? 'Türkiye' }}</span><b>İlanı aç →</b></div>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif
        </section>

        <div class="bd-layout">
            <aside class="bd-rail bd-rail--left" aria-label="Kategori ve şehirler">
                <section class="bd-box">
                    <div class="bd-box__head"><h2>Kategoriler</h2><a href="{{ route('companies.index') }}">Hepsi</a></div>
                    <div class="bd-list">
                        @forelse($categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}"><span class="bd-list__ico">{{ $category->icon ?? '◆' }}</span><span class="bd-list__txt">{{ $category->name }}</span><span class="bd-list__n">{{ $category->companies_count }}</span></a>
                        @empty
                            <p class="bd-muted" style="padding:8px 10px">Henüz kategori yok.</p>
                        @endforelse
                    </div>
                </section>
                @if($cities->isNotEmpty())
                    <section class="bd-box">
                        <div class="bd-box__head"><h2>Şehirler</h2><span>yakınında ara</span></div>
                        <div class="bd-list">
                            @foreach($cities->take(10) as $city)
                                <a href="{{ route('cities.show', $city->slug) }}"><span class="bd-list__ico">📍</span><span class="bd-list__txt">{{ $city->name }}</span><span class="bd-list__n">{{ $city->companies_count }}</span></a>
                            @endforeach
                        </div>
                    </section>
                @endif
            </aside>

            <div class="bd-stack">
                <section aria-labelledby="bd-latest-h">
                    <div class="bd-sec-title"><h2 id="bd-latest-h">Yeni eklenen ilanlar</h2><a href="{{ route('companies.index') }}">Tümünü gör →</a></div>
                    <div class="bd-view">
                        <input type="radio" name="bdview" id="bdv-list" checked>
                        <input type="radio" name="bdview" id="bdv-grid">
                        <div class="bd-view__bar">
                            <span class="bd-view__count">{{ $latestCompanies->count() }} ilan gösteriliyor</span>
                            <div class="bd-view__toggle" role="group" aria-label="Görünüm"><label for="bdv-list">☰ Liste</label><label for="bdv-grid">▦ Galeri</label></div>
                        </div>
                        <div class="bd-items">
                            @forelse($latestCompanies as $company)
                                @include('partials.board.item', ['company' => $company])
                            @empty
                                <div class="bd-empty">Henüz ilan yok. <a href="{{ route('owner.register') }}">İlk ilanı sen ver.</a></div>
                            @endforelse
                        </div>
                    </div>
                </section>

                <section aria-labelledby="bd-cats-h">
                    <div class="bd-sec-title"><h2 id="bd-cats-h">Kategoriye göz at</h2></div>
                    <div class="bd-cats">
                        @foreach($categories as $category)
                            <a class="bd-cat" href="{{ route('categories.show', $category->slug) }}"><span class="bd-cat__ico">{{ $category->icon ?? '◆' }}</span><strong>{{ $category->name }}</strong><span>{{ $category->companies_count }} ilan</span></a>
                        @endforeach
                    </div>
                </section>
            </div>

            <aside class="bd-rail bd-rail--right" aria-label="Fırsatlar ve yazılar">
                <div class="bd-cta">
                    <h2>İşletmen mi var?</h2>
                    <p>Ücretsiz ilan ver, mahalledeki müşteriler seni panoda bulsun.</p>
                    <a class="bd-btn" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a>
                </div>
                <section class="bd-box">
                    <div class="bd-box__head"><h2>İş ilanları</h2><a href="{{ route('jobs.index') }}">Tümü</a></div>
                    @forelse($homeJobs as $job)
                        <a class="bd-mini" href="{{ route('jobs.show', $job->slug) }}"><strong>{{ $job->title }}</strong><span>{{ $job->company?->name }} · {{ $bdJobTypes[$job->employment_type] ?? 'Tam zamanlı' }}</span></a>
                    @empty
                        <p class="bd-muted" style="padding:4px 16px 14px;font-size:14px">Şu an açık pozisyon yok.</p>
                    @endforelse
                </section>
                @if($posts->isNotEmpty())
                    <section class="bd-box">
                        <div class="bd-box__head"><h2>Okuma köşesi</h2><a href="{{ route('blog.index') }}">Blog</a></div>
                        @foreach($posts as $post)
                            <a class="bd-mini" href="{{ route('blog.show', $post->slug) }}"><strong>{{ $post->title }}</strong><span>{{ $post->published_at?->format('d.m.Y') }}</span></a>
                        @endforeach
                    </section>
                @endif
            </aside>
        </div>
    </div>

    @include('partials.board.footer')
</div>
@endsection
