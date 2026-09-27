@extends('layouts.app')

@php
    // Cep teması · swipe: kart destesi; üst kart öne alınarak gezilir
    $phCompanies = \App\Models\Company::active()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phDeck = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(4) : $latestCompanies->take(4);
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Firmaları kart kart kaydır, doğru işletmeyi bul.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">Destede {{ $phDeck->count() }} firma</span>
        <h1 class="ph-h1">Kaydır, seç, ara.</h1>
        <p class="ph-lead">Şehrin işletmeleri kart destesinde. Beğendiğini aç, iletişim hattını tek dokunuşta kullan.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma ara" placeholder="Ne lazım?" required>
            <button type="submit">Bul</button>
        </form>
    </section>

    <section class="ph-section" style="padding-top:4px">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Öne çıkanlar</span>
                <h2 class="ph-h2">Bugünün destesi</h2>
            </div>
            <span class="ph-meta">Kart {{ $phDeck->count() }}</span>
        </div>

        <div class="ph-deck" id="ph-deck">
            @forelse($phDeck as $deckCompany)
                <article class="ph-deck__card">
                    <div class="ph-deck__media">
                        @if($deckCompany->cover_image)
                            <img src="{{ asset('storage/' . $deckCompany->cover_image) }}" alt="{{ $deckCompany->name }} kapak görseli" loading="lazy">
                        @endif
                        @if($deckCompany->hasActivePremium())<span class="ph-card__badge" style="top:12px;left:12px">Vitrin</span>@endif
                    </div>
                    <div class="ph-deck__body">
                        <h3>{{ $deckCompany->name }}</h3>
                        <p>{{ \Illuminate\Support\Str::limit($deckCompany->short_description ?: 'Hizmetleri, adresi ve iletişim bilgileri profilde.', 118) }}</p>
                        <div class="ph-deck__facts">
                            <span class="ph-fact">{{ $deckCompany->category?->name ?? 'İşletme' }}</span>
                            <span class="ph-fact">{{ $deckCompany->city?->name ?? 'Türkiye' }}</span>
                            @if(($deckCompany->reviews_avg_rating ?? 0) > 0)
                                <span class="ph-fact">{{ number_format((float) $deckCompany->reviews_avg_rating, 1, ',', '.') }} ★</span>
                            @endif
                        </div>
                        <div style="display:grid;gap:8px;margin-top:14px">
                            <a class="ph-btn" href="{{ route('companies.show', $deckCompany->slug) }}">Kartı aç →</a>
                            @if($deckCompany->phone)
                                <a class="ph-btn ph-btn--ghost" href="tel:{{ preg_replace('/[^0-9]/', '', $deckCompany->phone) }}">Ara · {{ $deckCompany->phone }}</a>
                            @endif
                        </div>
                    </div>
                </article>
            @empty
                <div class="ph-empty">Destede henüz kart yok. <a href="{{ route('owner.register') }}" style="color:var(--ph-primary);font-weight:700">İlk firmayı ekleyin.</a></div>
            @endforelse
        </div>

        <div class="ph-deck__acts">
            <button class="ph-deck__btn ph-deck__btn--no" type="button" id="ph-deck-prev" aria-label="Önceki kart">‹</button>
            <button class="ph-deck__btn ph-deck__btn--yes" type="button" id="ph-deck-next" aria-label="Sonraki kart">›</button>
        </div>
        <p class="ph-deck__hint">Kartı kaydırarak da çevirebilirsin</p>
    </section>

    <div class="ph-stats">
        <div><b>{{ number_format($phCompanies, 0, ',', '.') }}</b><span>Firma</span></div>
        <div><b>{{ $phCategories }}</b><span>Kategori</span></div>
        <div><b>{{ $cities->count() }}</b><span>Şehir</span></div>
        <div><b>{{ number_format($phJobs, 0, ',', '.') }}</b><span>İlan</span></div>
    </div>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Kategoriler</span>
                <h2 class="ph-h2">Hangi deste?</h2>
            </div>
        </div>
        <div class="ph-chips">
            @foreach($categories->take(12) as $category)
                <a class="ph-chip" href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>
            @endforeach
        </div>
    </section>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Yeni kayıtlar</span>
                <h2 class="ph-h2">Desteye eklenenler</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div class="ph-list">
            @forelse($latestCompanies->take(6) as $company)
                @include('frontend.phone.cards.swipe')
            @empty
                <div class="ph-empty">Yeni kayıt yok. Rehber büyümeye hazır.</div>
            @endforelse
        </div>
    </section>

    <div class="ph-cta">
        <h2>Kartının arkası sensin.</h2>
        <p>Firma profilini oluştur, desteye sen de gir.</p>
        <a class="ph-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
    </div>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Şehirler</span>
                <h2 class="ph-h2">Bölgeni seç</h2>
            </div>
        </div>
        <div class="ph-list">
            @forelse($cities->take(6) as $city)
                <a class="ph-row" href="{{ route('cities.show', $city->slug) }}">
                    <span class="ph-row__av">⌖</span>
                    <div class="ph-row__txt">
                        <strong>{{ $city->name }}</strong>
                        <span>{{ $city->companies_count ?? 0 }} firma</span>
                    </div>
                    <span class="ph-row__go">›</span>
                </a>
            @empty
                <div class="ph-empty">Şehir listesi hazırlanıyor.</div>
            @endforelse
        </div>
    </section>

    @if($mapCompanies->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Haritada</span>
                    <h2 class="ph-h2">Yakınındakiler</h2>
                </div>
            </div>
            <div class="ph-list">
                @foreach($mapCompanies->take(5) as $company)
                    <a class="ph-row" href="{{ route('companies.show', $company->slug) }}">
                        <span class="ph-row__av">◎</span>
                        <div class="ph-row__txt">
                            <strong>{{ $company->name }}</strong>
                            <span>{{ $company->district?->name ?? $company->city?->name ?? 'Türkiye' }} · {{ $company->category?->name ?? 'İşletme' }}</span>
                        </div>
                        <span class="ph-row__go">›</span>
                    </a>
                @endforeach
            </div>
        </section>
    @endif

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
                        <span class="ph-row__av">◈</span>
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
                    <span class="ph-eyebrow">Vitrin</span>
                    <h2 class="ph-h2">Ürün ve hizmetler</h2>
                </div>
            </div>
            <div class="ph-list">
                @foreach($featuredOfferings->take(4) as $offering)
                    <a class="ph-row" href="{{ route('companies.show', $offering->company->slug) }}">
                        <span class="ph-row__av">
                            @if($offering->image_path)<img src="{{ asset('storage/' . $offering->image_path) }}" alt="{{ $offering->name }}" loading="lazy">@else◈@endif
                        </span>
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

<script>
    (function () {
        var deck = document.getElementById('ph-deck');
        if (!deck) return;
        var rotate = function (backward) {
            var cards = Array.prototype.slice.call(deck.querySelectorAll('.ph-deck__card'));
            if (cards.length < 2) return;
            var moving = backward ? cards[cards.length - 1] : cards[0];
            if (backward) deck.insertBefore(moving, cards[0]); else deck.appendChild(moving);
            moving.setAttribute('aria-hidden', 'false');
        };
        var next = document.getElementById('ph-deck-next');
        var prev = document.getElementById('ph-deck-prev');
        if (next) next.addEventListener('click', function () { rotate(false); });
        if (prev) prev.addEventListener('click', function () { rotate(true); });

        var startX = null;
        deck.addEventListener('pointerdown', function (event) { startX = event.clientX; });
        deck.addEventListener('pointerup', function (event) {
            if (startX === null) return;
            var delta = event.clientX - startX;
            startX = null;
            if (Math.abs(delta) > 56) rotate(delta > 0);
        });
    })();
</script>
@endsection
