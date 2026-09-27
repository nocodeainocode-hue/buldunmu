@extends('layouts.app')

@php
    // Pano sayacları kiracıya (directory) göre sayılır; vitrin satırları controller verisinden gelir.
    $depCompanies = \App\Models\Company::active()->when($directory, fn ($query) => $query->where('directory_id', $directory->id))->count();
    $depJobs = \App\Models\JobPosting::visible()->when($directory, fn ($query) => $query->where('directory_id', $directory->id))->count();
    $depPosts = \App\Models\Post::publishedForDirectory($directory ?? null)->count();
    $depCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->count();
    $depBoard = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
@endphp

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Kalkış Panosu')
@section('meta_description', $settings->meta_description ?? 'Şehrin firmaları, hizmetleri, iş ilanları ve yazıları tek panoda.')

@section('content')
<div class="dep">
    @include('partials.departures.header')

    <section class="dep-hero">
        <div class="dep-wrap">
            <div class="dep-hero__grid">
                <div class="dep-hero__copy">
                    <span class="dep-kicker dep-kicker--light">Peron 01 / {{ now()->format('Y') }} seferleri</span>
                    <h1 class="dep-display">Şehirde <em>nerede</em> inmek istersiniz?</h1>
                    <p class="dep-hero__lede">İşletmeler, ustalar, açık pozisyonlar ve şehir rehberi aynı panoda. Bir isim, kategori veya şehir yazın; kalkış saatinizi öğrenin.</p>
                    <form class="dep-gate" action="{{ route('search') }}" method="GET">
                        <span class="dep-gate__icon" aria-hidden="true">⌕</span>
                        <input type="text" name="q" aria-label="Firma, hizmet veya şehir ara" placeholder="Firma, hizmet, kategori veya şehir ara…" required>
                        <button type="submit">Panoyu ara</button>
                    </form>
                    <div class="dep-hero__quick">
                        <span>Hızlı geçiş</span>
                        <a href="{{ route('companies.index') }}">Tüm firmalar</a>
                        <a href="{{ route('jobs.index') }}">İş ilanları</a>
                        <a href="{{ route('owner.register') }}">Firmanı ekle</a>
                    </div>
                </div>
                <aside class="dep-board" style="margin-top:0" aria-label="Pano özeti">
                    <div class="dep-board__head" style="grid-template-columns:1fr auto"><span>Panoda bugün</span><span>Durum</span></div>
                    <div class="dep-board__row" style="--i:0;grid-template-columns:1fr auto">
                        <span class="dep-board__cell">{{ number_format($depCompanies, 0, ',', '.') }} firma</span>
                        <span class="dep-board__state">Açık</span>
                    </div>
                    <div class="dep-board__row" style="--i:1;grid-template-columns:1fr auto">
                        <span class="dep-board__cell">{{ number_format($depJobs, 0, ',', '.') }} iş ilanı</span>
                        <span class="dep-board__state dep-board__state--new">{{ $depJobs > 0 ? 'Yayında' : 'Boş' }}</span>
                    </div>
                    <div class="dep-board__row" style="--i:2;grid-template-columns:1fr auto">
                        <span class="dep-board__cell">{{ number_format($depPosts, 0, ',', '.') }} şehir yazısı</span>
                        <span class="dep-board__state">{{ $depPosts > 0 ? 'Yayında' : 'Hazır' }}</span>
                    </div>
                    <div class="dep-board__row" style="--i:3;grid-template-columns:1fr auto">
                        <span class="dep-board__cell">{{ $depCategories }} kategori · {{ $cities->count() }} şehir</span>
                        <span class="dep-board__state">Aktif</span>
                    </div>
                    <div class="dep-board__foot"><span>Bilgi noktası</span><span>{{ $directory?->name ?? ($settings->site_name ?? 'Firma Rehberi') }}</span></div>
                </aside>
            </div>

            <div class="dep-board" style="margin-top:clamp(34px,4vw,54px)">
                <div class="dep-board__head">
                    <span>Peron</span><span>Firma</span><span>Kategori</span><span>Şehir</span><span>Durum</span>
                </div>
                @forelse($depBoard->take(5) as $boardCompany)
                    <a class="dep-board__row" style="--i:{{ $loop->iteration }}" href="{{ route('companies.show', $boardCompany->slug) }}">
                        <span class="dep-board__cell dep-board__plat">P{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span class="dep-board__name">{{ $boardCompany->name }}</span>
                        <span class="dep-board__cell dep-board__tag">{{ $boardCompany->category?->name ?? 'İşletme' }}</span>
                        <span class="dep-board__cell">{{ $boardCompany->city?->name ?? 'Türkiye' }}</span>
                        <span class="dep-board__state">{{ $boardCompany->hasActivePremium() ? 'Vitrinde' : 'Kalkışta' }}</span>
                    </a>
                @empty
                    <div class="dep-board__row" style="grid-template-columns:1fr">
                        <span class="dep-board__cell">Pano henüz boş — ilk firmayı ekleyin.</span>
                    </div>
                @endforelse
                <div class="dep-board__foot">
                    <span>Kalkış sırası: öne çıkanlar</span>
                    <a href="{{ route('companies.index') }}" style="color:var(--amber)">Tüm seferler →</a>
                </div>
            </div>
        </div>
    </section>

    <div class="dep-wrap">
        <div class="dep-stats">
            <div><strong>{{ number_format($depCompanies, 0, ',', '.') }}</strong><span>Panodaki firma</span></div>
            <div><strong>{{ $depCategories }}</strong><span>Hat / kategori</span></div>
            <div><strong>{{ $cities->count() }}</strong><span>İniş biniş noktası</span></div>
            <div><strong>{{ number_format($depJobs, 0, ',', '.') }}</strong><span>Açık pozisyon</span></div>
        </div>
    </div>

    <section class="dep-section">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">01 / Vitrin hatları</span>
                    <h2 class="dep-h2">Işıklar bu firmalarda</h2>
                    <p>Premium işletmeler; ürünlerini, hizmetlerini ve iletişim hatlarını doğrudan panoya taşıyor.</p>
                </div>
                <a class="dep-section__more" href="{{ route('companies.index') }}">Tüm firmalar →</a>
            </div>
            <div class="dep-grid">
                @forelse($premiumCompanies->take(3) as $company)
                    @include('frontend.departures.company-card')
                @empty
                    @foreach($latestCompanies->take(3) as $company)
                        @include('frontend.departures.company-card')
                    @endforeach
                @endforelse
            </div>
        </div>
    </section>

    <section class="dep-section dep-section--slab">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">02 / Sefer noktaları</span>
                    <h2 class="dep-h2">Nereye gideceksiniz?</h2>
                    <p>İlgilendiğiniz hattan başlayın; o kategorideki işletmelere doğrudan ulaşın.</p>
                </div>
            </div>
            <div class="dep-dest-grid">
                @forelse($categories->take(8) as $category)
                    <a class="dep-dest" href="{{ route('categories.show', $category->slug) }}">
                        <span class="dep-dest__no">HAT {{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                        <span>
                            <span class="dep-dest__name">{{ $category->name }}</span>
                            <span class="dep-dest__count">{{ $category->companies_count }} firma · iniş hazır</span>
                        </span>
                    </a>
                @empty
                    <div class="dep-empty">Hatlar henüz hazırlanıyor.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dep-section">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">03 / Yeni biletler</span>
                    <h2 class="dep-h2">Panoya yeni düşenler</h2>
                    <p>Rehbere katılan son işletmeler.</p>
                </div>
                <a class="dep-section__more" href="{{ route('companies.index') }}">Listeyi aç →</a>
            </div>
            <div class="dep-grid">
                @forelse($latestCompanies->take(6) as $company)
                    @include('frontend.departures.company-card')
                @empty
                    <div class="dep-empty">İlk kayıt için pano hazır. <a href="{{ route('owner.register') }}">Firma ekleyin.</a></div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dep-wrap" style="padding-bottom:clamp(48px,6vw,74px)">
        <div class="dep-cta">
            <div class="dep-cta__copy">
                <span class="dep-kicker dep-kicker--light">04 / Sıra sizde</span>
                <h2>Firmanızı panoya yazın.</h2>
                <p>Profilinizi oluşturun; ürünlerinizi, hizmetlerinizi ve açık pozisyonlarınızı şehrin kalkış panosunda görünür kılın.</p>
                <a class="dep-btn" href="{{ route('owner.register') }}">Firma kaydı oluştur →</a>
            </div>
            <div class="dep-cta__art" aria-hidden="true"></div>
        </div>
    </section>

    <section class="dep-section dep-section--slab">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">05 / Tarife</span>
                    <h2 class="dep-h2">Şehre göre iniş biniş</h2>
                    <p>Şehrinizi seçin, o bölgedeki tüm hatları tek bakışta görün.</p>
                </div>
            </div>
            <div class="dep-timetable">
                @forelse($cities as $city)
                    <a class="dep-timetable__row" href="{{ route('cities.show', $city->slug) }}">
                        <b>{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</b>
                        <strong>{{ $city->name }}</strong>
                        <span class="dep-code">{{ $city->companies_count ?? 0 }} firma</span>
                        <i>Perona git →</i>
                    </a>
                @empty
                    <div class="dep-empty">Şehir tarifesi hazırlanıyor.</div>
                @endforelse
            </div>
        </div>
    </section>

    <section class="dep-section">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">06 / Mürettebat arayanlar</span>
                    <h2 class="dep-h2">Açık pozisyonlar</h2>
                    <p>Panoyu kontrol eden firmalar; güncel iş ilanları.</p>
                </div>
                <a class="dep-section__more" href="{{ route('jobs.index') }}">Tüm ilanlar →</a>
            </div>
            <div class="dep-grid">
                @forelse($homeJobs as $job)
                    <article class="dep-card" style="grid-template-columns:1fr">
                        <div class="dep-card__body" style="padding-top:20px">
                            <p class="dep-code" style="color:var(--amber-deep)">İlan / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                            <h3 class="dep-h3" style="margin:8px 0"><a href="{{ route('jobs.show', $job->slug) }}" style="text-decoration:none">{{ $job->title }}</a></h3>
                            <p class="dep-card__text">{{ $job->company?->name }} · {{ \Illuminate\Support\Str::limit($job->description, 104) }}</p>
                            <div class="dep-card__foot">
                                <span>{{ $job->published_at?->format('d.m.Y') }}</span>
                                <a href="{{ route('jobs.show', $job->slug) }}">İlanı aç →</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="dep-empty">Şu an açık pozisyon yok; yeni ilanlar panoya düşecek.</div>
                @endforelse
            </div>
        </div>
    </section>

    @if($featuredOfferings->isNotEmpty())
        <section class="dep-section dep-section--slab">
            <div class="dep-wrap">
                <div class="dep-section__head">
                    <div>
                        <span class="dep-kicker">07 / El bagajı</span>
                        <h2 class="dep-h2">Ürünler ve hizmetler</h2>
                        <p>Vitrindeki firmaların öne çıkan kayıtları.</p>
                    </div>
                </div>
                <div class="dep-grid">
                    @foreach($featuredOfferings->take(4) as $offering)
                        <article class="dep-card" style="grid-template-columns:1fr">
                            <div class="dep-card__body" style="padding-top:20px">
                                <p class="dep-code" style="color:var(--amber-deep)">{{ $offering->type === 'product' ? 'Ürün' : 'Hizmet' }} / {{ $offering->company?->name }}</p>
                                <h3 class="dep-h3" style="margin:8px 0"><a href="{{ route('companies.show', $offering->company->slug) }}#urunler-hizmetler" style="text-decoration:none">{{ $offering->name }}</a></h3>
                                <p class="dep-card__text">{{ \Illuminate\Support\Str::limit($offering->description ?: 'Firma vitrinindeki kaydı inceleyin.', 110) }}</p>
                                <div class="dep-card__foot">
                                    <span>{{ $offering->price !== null ? number_format((float) $offering->price, 2, ',', '.').' TL' : 'Detaylar profilde' }}</span>
                                    <a href="{{ route('companies.show', $offering->company->slug) }}#urunler-hizmetler">İncele →</a>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="dep-section">
        <div class="dep-wrap">
            <div class="dep-section__head">
                <div>
                    <span class="dep-kicker">08 / Yolculuk notları</span>
                    <h2 class="dep-h2">Şehirden yazılar</h2>
                    <p>Seçim rehberleri, karşılaştırmalar ve yerel anlatılar.</p>
                </div>
                <a class="dep-section__more" href="{{ route('blog.index') }}">Tüm yazılar →</a>
            </div>
            <div class="dep-grid">
                @forelse($posts as $post)
                    <article class="dep-card" style="grid-template-columns:1fr">
                        <div class="dep-card__body" style="padding-top:20px">
                            <p class="dep-code" style="color:var(--amber-deep)">Yazı / {{ $post->published_at?->format('d.m.Y') }}</p>
                            <h3 class="dep-h3" style="margin:8px 0"><a href="{{ route('blog.show', $post->slug) }}" style="text-decoration:none">{{ $post->title }}</a></h3>
                            <p class="dep-card__text">{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 126) }}</p>
                            <div class="dep-card__foot">
                                <span>Okuma köşesi</span>
                                <a href="{{ route('blog.show', $post->slug) }}">Oku →</a>
                            </div>
                        </div>
                    </article>
                @empty
                    <div class="dep-empty">Yeni yazılar yakında panoda.</div>
                @endforelse
            </div>
        </div>
    </section>

    @include('partials.departures.footer')
</div>
@endsection
