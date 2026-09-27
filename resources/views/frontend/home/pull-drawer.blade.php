@extends('layouts.app')

@php
    // Cep teması · çekmece: açık arama paneli + öneri satırları öne çıkar
    $phCompanies = \App\Models\Company::active()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phFeed = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Aramanı çekmeceden aç: firmalar, ilanlar ve rehber yazıları cep ekranında.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">Çekmece arama · {{ $directory?->name ?? ($settings->site_name ?? 'Cep Rehberi') }}</span>
        <h1 class="ph-h1">Ne aramıştın?<br>Aşağı çek, gelsin.</h1>
        <p class="ph-lead">Rehber kapalı bir liste değil; yazdıkça açılan bir çekmece. Bir kelime yaz, öneriler anında dökülsün.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma ara" placeholder="Anahtar kelime yaz…" required>
            <button type="submit">Aç</button>
        </form>
    </section>

    <section class="ph-section" style="padding-top:6px">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Sık arananlar</span>
                <h2 class="ph-h2">Çekmeceden öneriler</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div style="padding:0 16px 6px">
            <div class="ph-pull">
                @forelse($phFeed->take(5) as $company)
                    @include('frontend.phone.cards.drawer')
                @empty
                    <div class="ph-empty">Çekmece henüz boş. İlk firmayı ekleyerek doldurun.</div>
                @endforelse
            </div>
        </div>
        <div class="ph-chips">
            @foreach($categories->take(8) as $category)
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
                <span class="ph-eyebrow">Semtler</span>
                <h2 class="ph-h2">Şehre göre aç</h2>
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
        <h2>Aradığını bulamıyor musun?</h2>
        <p>Firmanı ekle; çekmeceyi senin için de açalım.</p>
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
            <div style="padding:0 16px 6px">
                <div class="ph-pull">
                    @foreach($homeJobs->take(5) as $homeJob)
                        <a href="{{ route('jobs.show', $homeJob->slug) }}">
                            <span class="ph-pull__ico" aria-hidden="true">{{ mb_substr($homeJob->company->name ?? 'İ', 0, 1) }}</span>
                            <span class="ph-pull__txt">
                                <strong>{{ $homeJob->title }}</strong>
                                <span>{{ $homeJob->company->name ?? 'Firma' }} · {{ $homeJob->location ?: ($homeJob->company->city?->name ?? 'Türkiye') }}</span>
                            </span>
                            <kbd>↵</kbd>
                        </a>
                    @endforeach
                </div>
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
            <div style="padding:0 16px 6px">
                <div class="ph-pull">
                    @foreach($featuredOfferings->take(4) as $offering)
                        <a href="{{ route('companies.show', $offering->company->slug) }}">
                            <span class="ph-pull__ico" aria-hidden="true">◈</span>
                            <span class="ph-pull__txt">
                                <strong>{{ $offering->name }}</strong>
                                <span>{{ $offering->company->name ?? 'Firma' }}{{ $offering->price > 0 ? ' · ' . number_format((float) $offering->price, 0, ',', '.') . ' ₺' : '' }}</span>
                            </span>
                            <kbd>↵</kbd>
                        </a>
                    @endforeach
                </div>
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
                        <span class="ph-row__av">✦</span>
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
