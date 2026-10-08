@extends('layouts.app')

@php
    // Cep teması · radar: kategori kadranı + sinyal (blip) listeleri
    $phCompanies = \App\Models\Company::active()->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phFeed = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
    $phPips = $categories->take(8);
    $phPipCount = max($phPips->count(), 1);
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehri radarından geçen firmalar, ilanlar ve rehber yazıları cep ekranında.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">Radar aktif · {{ $directory?->name ?? ($settings->site_name ?? 'Cep Rehberi') }}</span>
        <h1 class="ph-h1">Tarama sürüyor.<br>Yakında ne var?</h1>
        <p class="ph-lead">Kadranı döndür, kategoriye kilitlen; şehrin sinyalleri tek tek ekrana düşsün.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma ara" placeholder="Hedef ara…" required>
            <button type="submit">Tara</button>
        </form>
    </section>

    <div class="ph-dial" role="group" aria-label="Kategori kadranı">
        <a class="ph-dial__hub" href="{{ route('companies.index') }}">TARA<br>{{ number_format($phCompanies, 0, ',', '.') }}</a>
        @foreach($phPips as $index => $pip)
            @php $phAngle = (360 / $phPipCount) * $index; @endphp
            <a class="ph-pip" style="transform: rotate({{ $phAngle }}deg) translateY(-128px) rotate(-{{ $phAngle }}deg)" href="{{ route('categories.show', $pip->slug) }}">
                <span class="ph-pip__dot" aria-hidden="true"></span>
                <span>{{ $pip->name }}</span>
            </a>
        @endforeach
    </div>

    <section class="ph-section" style="padding-top:0">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Sinyaller</span>
                <h2 class="ph-h2">Ekrana düşenler</h2>
            </div>
            <span class="ph-meta">{{ $phFeed->count() }} kayıt</span>
        </div>
        <div style="padding:0 16px 6px">
            <div class="ph-blips">
                @forelse($phFeed->take(6) as $company)
                    @include('frontend.phone.cards.blip')
                @empty
                    <div class="ph-empty">Radar henüz sinyal yakalamadı. İlk firmayı ekleyin.</div>
                @endforelse
            </div>
        </div>
        <div style="padding:2px 16px"><a class="ph-btn ph-btn--ghost ph-btn--wide" href="{{ route('companies.index') }}">Tüm taramayı aç →</a></div>
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
                <span class="ph-eyebrow">Sektörler</span>
                <h2 class="ph-h2">Kadrandaki başlıklar</h2>
            </div>
        </div>
        <div class="ph-chips">
            @foreach($categories->take(10) as $category)
                <a class="ph-chip" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Üsler</span>
                <h2 class="ph-h2">Şehre göre tara</h2>
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
        <h2>Firman radara girsın.</h2>
        <p>Profilini oluştur; şehrin taramasında yerini al.</p>
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
