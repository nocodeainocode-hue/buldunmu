@extends('layouts.app')

@php
    // Cep teması · reels: sayaçlar kiracıya göre, akış controller verisinden
    $phCompanies = \App\Models\Company::active()->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phGreeting = now()->hour < 11 ? 'Günaydın' : (now()->hour < 18 ? 'İyi günler' : 'İyi akşamlar');
    $phFeed = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrin firmaları, iş ilanları ve rehber yazıları cep ekranında.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">{{ $phGreeting }} · {{ $directory?->name ?? ($settings->site_name ?? 'Cep Rehberi') }}</span>
        <h1 class="ph-h1">Şehirde <br>bu an neler oluyor?</h1>
        <p class="ph-lead">Firmalar, açık pozisyonlar ve rehber yazıları dikey akışta. Kaydır, seç, tek dokunuşta ara.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma ara" placeholder="Ne arıyorsun?" required>
            <button type="submit">Bul</button>
        </form>
        <div class="ph-chips" style="padding:0">
            @foreach($categories->take(6) as $category)
                <a class="ph-chip" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
            @endforeach
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
                <span class="ph-eyebrow">Vitrin halkaları</span>
                <h2 class="ph-h2">Bugün öne çıkanlar</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div class="ph-rings">
            @foreach($premiumCompanies->take(10) as $ringCompany)
                <a class="ph-ring" href="{{ route('companies.show', $ringCompany->slug) }}">
                    <span class="ph-ring__av">
                        <span class="ph-ring__in">
                            @if($ringCompany->logo)
                                <img src="{{ asset('storage/' . $ringCompany->logo) }}" alt="{{ $ringCompany->name }} logosu" loading="lazy">
                            @else
                                {{ mb_substr($ringCompany->name, 0, 1) }}
                            @endif
                        </span>
                    </span>
                    <span class="ph-ring__label">{{ $ringCompany->name }}</span>
                </a>
            @endforeach
            <a class="ph-ring ph-ring--add" href="{{ route('owner.register') }}">
                <span class="ph-ring__av"><span class="ph-ring__in">＋</span></span>
                <span class="ph-ring__label">Siz de</span>
            </a>
        </div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Akış</span>
                <h2 class="ph-h2">Firma reels'leri</h2>
            </div>
            <span class="ph-meta">{{ $phFeed->count() }} kayıt</span>
        </div>
        <div class="ph-reels">
            @forelse($phFeed->take(6) as $company)
                @include('frontend.phone.cards.reels')
            @empty
                <div class="ph-empty">Akış henüz boş. İlk firmayı ekleyerek başlatın.</div>
            @endforelse
        </div>
        <div style="padding:0 16px"><a class="ph-btn ph-btn--ghost ph-btn--wide" href="{{ route('companies.index') }}">Akışı büyüt →</a></div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Semtler</span>
                <h2 class="ph-h2">Şehre göre keşfet</h2>
            </div>
        </div>
        <div class="ph-list">
            @forelse($cities->take(6) as $city)
                <a class="ph-row" href="{{ route('cities.show', $city->slug) }}">
                    <span class="ph-row__av">⌖</span>
                    <div class="ph-row__txt">
                        <strong>{{ $city->name }}</strong>
                        <span>{{ $city->companies_count ?? 0 }} firma kayıtlı</span>
                    </div>
                    <span class="ph-row__go">›</span>
                </a>
            @empty
                <div class="ph-empty">Şehir listesi hazırlanıyor.</div>
            @endforelse
        </div>
    </section>

    <div class="ph-cta">
        <h2>Firman bu akışta olsun.</h2>
        <p>Profilini oluştur; telefonundan dakikalar içinde şehir rehberine katıl.</p>
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
