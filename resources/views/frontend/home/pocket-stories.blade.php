@extends('layouts.app')

@php
    // Cepte Hikâyeler: ortak ve bu rehbere özel firmalar aynı sayaçta.
    $phCompanies = \App\Models\Company::active()->count();
    $phJobs = \App\Models\JobPosting::visible()->when($directory, fn ($q) => $q->where('directory_id', $directory->id))->count();
    $phPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $phCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $phFresh = $latestCompanies->take(6);
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrindeki firmalar, kategoriler ve iş ilanları cep ekranında.')

@section('content')
@include('partials.phone.device-start')

    <section class="ph-hero">
        <span class="ph-eyebrow">{{ $directory?->name ?? ($settings->site_name ?? 'Cep Rehberi') }} · Yerel rehber</span>
        <h1 class="ph-h1">Şehrin iyi adresleri, bir arada.</h1>
        <p class="ph-lead">İhtiyacınıza uygun işletmeleri, hizmetleri ve yerel önerileri kolayca keşfedin.</p>
        <form class="ph-search" action="{{ route('search') }}" method="GET">
            <input type="text" name="q" aria-label="Firma veya hizmet ara" placeholder="Firma, hizmet veya semt" required>
            <button type="submit">Ara</button>
        </form>
    </section>

    <section class="ph-section" style="padding-top:6px">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Keşfet</span>
                <h2 class="ph-h2">Neye ihtiyacınız var?</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Tümü ›</a>
        </div>
        <div class="ph-rings">
            @forelse($categories->take(10) as $category)
                <a class="ph-ring" href="{{ route('categories.show', $category->slug) }}">
                    <span class="ph-ring__av">
                        <span class="ph-ring__in">{{ mb_substr($category->name, 0, 1) }}</span>
                    </span>
                    <span class="ph-ring__label">{{ $category->name }}</span>
                </a>
            @empty
                <div class="ph-empty">Kategoriler hazırlanıyor.</div>
            @endforelse
        </div>
    </section>

    <div class="ph-stats">
        <div><b>{{ number_format($phCompanies, 0, ',', '.') }}</b><span>Firma</span></div>
        <div><b>{{ $phCategories }}</b><span>Kategori</span></div>
        <div><b>{{ $cities->count() }}</b><span>Şehir</span></div>
        <div><b>{{ number_format($phJobs, 0, ',', '.') }}</b><span>İş ilanı</span></div>
    </div>

    @if($premiumCompanies->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Vitrin</span>
                    <h2 class="ph-h2">Öne çıkan işletmeler</h2>
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
                <span class="ph-eyebrow">Yeni eklenenler</span>
                <h2 class="ph-h2">Bu haftanın kayıtları</h2>
            </div>
            <a class="ph-section__more" href="{{ route('companies.index') }}">Liste ›</a>
        </div>
        <div class="ph-grid">
            @forelse($phFresh as $company)
                @include('frontend.phone.cards.pocket')
            @empty
                <div class="ph-empty" style="grid-column:1/-1">Rehber henüz boş. İlk firmayı siz ekleyin.</div>
            @endforelse
        </div>
    </section>

    <div class="ph-promo">
        <span class="ph-eyebrow">Firman için</span>
        <h2>Cep ekranında yerini al</h2>
        <p>Profilini oluştur; halkalarda ve vitrinde göründüğün ilk günü bugün yap.</p>
        <a class="ph-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
    </div>

    <section class="ph-section">
        <div class="ph-section__head">
            <div>
                <span class="ph-eyebrow">Şehirler</span>
                <h2 class="ph-h2">Bölgeni seç</h2>
            </div>
        </div>
        <div class="ph-chips" style="padding:0 16px 10px">
            @foreach($cities->take(12) as $city)
                <a class="ph-chip" href="{{ route('cities.show', $city->slug) }}">{{ $city->name }} · {{ $city->companies_count ?? 0 }}</a>
            @endforeach
        </div>
    </section>

    @if($trustedCompanies->isNotEmpty())
        <section class="ph-section">
            <div class="ph-section__head">
                <div>
                    <span class="ph-eyebrow">Güvenilenler</span>
                    <h2 class="ph-h2">Doğrulanmış profiller</h2>
                </div>
            </div>
            <div class="ph-list">
                @foreach($trustedCompanies->take(5) as $company)
                    <a class="ph-row" href="{{ route('companies.show', $company->slug) }}">
                        <span class="ph-row__av">
                            @if($company->logo)<img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">@else{{ mb_substr($company->name, 0, 1) }}@endif
                        </span>
                        <div class="ph-row__txt">
                            <strong>{{ $company->name }}</strong>
                            <span>{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}</span>
                        </div>
                        <span class="ph-fact">✓</span>
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
                    <h2 class="ph-h2">Açık iş ilanları</h2>
                </div>
                <a class="ph-section__more" href="{{ route('jobs.index') }}">Tümü ›</a>
            </div>
            <div class="ph-list">
                @foreach($homeJobs->take(4) as $homeJob)
                    <a class="ph-row" href="{{ route('jobs.show', $homeJob->slug) }}">
                        <span class="ph-row__av">💼</span>
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
                    <span class="ph-eyebrow">Raflar</span>
                    <h2 class="ph-h2">Vitrin ürünleri</h2>
                </div>
            </div>
            <div class="ph-grid">
                @foreach($featuredOfferings->take(4) as $offering)
                    <a class="ph-card" href="{{ route('companies.show', $offering->company->slug) }}">
                        <div class="ph-card__media">
                            @if($offering->image_path)
                                <img src="{{ asset('storage/' . $offering->image_path) }}" alt="{{ $offering->name }}" loading="lazy">
                            @else
                                <span class="ph-card__initial" aria-hidden="true">{{ mb_substr($offering->name, 0, 1) }}</span>
                            @endif
                            <span class="ph-card__badge">Vitrin</span>
                        </div>
                        <div class="ph-card__body">
                            <h3>{{ $offering->name }}</h3>
                            <p>{{ $offering->company->name ?? 'Firma' }}</p>
                            <div class="ph-card__foot">
                                <span>{{ $offering->price > 0 ? number_format((float) $offering->price, 0, ',', '.') . ' ₺' : 'Teklif iste' }}</span>
                                <span style="color:var(--ph-primary)">Gör ›</span>
                            </div>
                        </div>
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
                    <h2 class="ph-h2">Şehir yazıları</h2>
                </div>
                <a class="ph-section__more" href="{{ route('blog.index') }}">Tümü ›</a>
            </div>
            <div class="ph-list">
                @foreach($posts->take(4) as $post)
                    <a class="ph-row" href="{{ route('blog.show', $post->slug) }}">
                        <span class="ph-row__av">📖</span>
                        <div class="ph-row__txt">
                            <strong>{{ $post->title }}</strong>
                            <span>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 84) }}</span>
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
