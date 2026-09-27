@extends('layouts.app')

@php
    // Cep teması · rampa: numaralı sıralama + A-Z kategori endeksi
    $phCompanies = \App\Models\Company::active()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phFeed = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
    // Kategorileri ilk harfe göre grupla (A-Z endeksi)
    $phIndex = $categories->sortBy(fn ($c) => mb_strtoupper($c->name))->groupBy(fn ($c) => mb_strtoupper(mb_substr($c->name, 0, 1)));
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrin firmaları rampada sıraya giriyor: cep ekranında hızlı rehber.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">Şehir rampası · {{ $directory?->name ?? ($settings->site_name ?? 'Cep Rehberi') }}</span>
        <h1 class="ph-h1">Sıraya gir.<br>Buradan bulunur.</h1>
        <p class="ph-lead">Firmalar numaralı rampada, kategoriler A-Z indeksinde. Kaydır, sıranın sana gelmesini bekle.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma ara" placeholder="Ramada ara…" required>
            <button type="submit">Bul</button>
        </form>
    </section>

    <section class="ph-section" style="padding-top:6px">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">İlk 100</span>
                <h2 class="ph-h2">Rampada öne çıkanlar</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div style="padding:0 16px 6px">
            <div class="ph-rally">
                @forelse($phFeed->take(8) as $company)
                    @include('frontend.phone.cards.rally')
                @empty
                    <div class="ph-empty">Rampa boş. İlk firmayı sıraya al.</div>
                @endforelse
            </div>
        </div>
    </section>

    <div class="ph-stats">
        <div><b>{{ number_format($phCompanies, 0, ',', '.') }}</b><span>Kayıtlı firma</span></div>
        <div><b>{{ $phCategories }}</b><span>Kategori</span></div>
        <div><b>{{ number_format($phJobs, 0, ',', '.') }}</b><span>Açık ilan</span></div>
        <div><b>{{ $phPosts }}</b><span>Rehber yazısı</span></div>
    </div>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">A-Z</span>
                <h2 class="ph-h2">Kategori endeksi</h2>
            </div>
        </div>
        <div class="ph-list">
            @forelse($phIndex as $letter => $letterCategories)
                <div>
                    <span class="ph-letter">{{ $letter }}</span>
                    <div class="ph-chips" style="padding:0 0 6px">
                        @foreach($letterCategories as $category)
                            <a class="ph-chip" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="ph-empty">Kategori listesi hazırlanıyor.</div>
            @endforelse
        </div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Semtler</span>
                <h2 class="ph-h2">Şehre göre sırala</h2>
            </div>
        </div>
        <div style="padding:0 16px 6px">
            <div class="ph-rally">
                @forelse($cities->take(6) as $city)
                    <a href="{{ route('cities.show', $city->slug) }}">
                        <span class="ph-rally__txt">
                            <strong>{{ $city->name }}</strong>
                            <span>{{ $city->companies_count ?? 0 }} firma kayıtlı</span>
                        </span>
                        <span class="ph-rally__go" aria-hidden="true">›</span>
                    </a>
                @empty
                    <div class="ph-empty">Şehir listesi hazırlanıyor.</div>
                @endforelse
            </div>
        </div>
    </section>

    <div class="ph-cta">
        <h2>Rampada yerin hazır.</h2>
        <p>Firmanı ekle, sıraya gir; müşteriler seni hızlısında bulsun.</p>
        <a class="ph-btn" href="{{ route('owner.register') }}">Firma kaydı aç →</a>
    </div>

    @if($homeJobs->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Kariyer</span>
                    <h2 class="ph-h2">Açık pozisyonlar</h2>
                </div>
                <a class="ph-section__more" href="{{ route('jobs.index') }}">Tümü ›</a>
            </div>
            <div class="ph-list">
                @foreach($homeJobs->take(5) as $homeJob)
                    <a class="ph-row" href="{{ route('jobs.show', $homeJob->slug) }}">
                        <span class="ph-row__av">{{ mb_substr($homeJob->company->name ?? 'İ', 0, 1) }}</span>
                        <div class="ph-row__txt">
                            <strong>{{ $homeJob->title }}</strong>
                            <span>{{ $homeJob->company->name ?? 'Firma' }} · {{ $homeJob->location ?: ($homeJob->company->city?->name ?? 'Türkiye') }}</span>
                        </div>
                        <span class="ph-row__go">›</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if($featuredOfferings->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Vitrin ürünleri</span>
                    <h2 class="ph-h2">Şehrin rafları</h2>
                </div>
            </div>
            <div class="ph-list">
                @foreach($featuredOfferings->take(4) as $offering)
                    <a class="ph-row" href="{{ route('companies.show', $offering->company->slug) }}">
                        <span class="ph-row__av">🛍️</span>
                        <div class="ph-row__txt">
                            <strong>{{ $offering->name }}</strong>
                            <span>{{ $offering->company->name ?? 'Firma' }}{{ $offering->price > 0 ? ' · ' . number_format((float) $offering->price, 0, ',', '.') . ' ₺' : '' }}</span>
                        </div>
                        <span class="ph-row__go">›</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @if($posts->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Okuma</span>
                    <h2 class="ph-h2">Rehber yazıları</h2>
                </div>
                <a class="ph-section__more" href="{{ route('blog.index') }}">Tümü ›</a>
            </div>
            <div class="ph-list">
                @foreach($posts->take(4) as $post)
                    <a class="ph-row" href="{{ route('blog.show', $post->slug) }}">
                        <span class="ph-row__av">📖</span>
                        <div class="ph-row__txt">
                            <strong>{{ $post->title }}</strong>
                            <span>{{ $post->published_at?->format('d.m.Y') }}</span>
                        </div>
                        <span class="ph-row__go">›</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

    @include('partials.phone.footer')

@include('partials.phone.device-end')
@endsection
