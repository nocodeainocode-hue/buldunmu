@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki firmaları ve hizmetleri inceleyin. İşletme profillerine ulaşın, firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $sunFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(6);
    $sunTitle = trim($settings->homepage_title ?: 'Aradığınız firmayı hızla bulun');
    $sunWords = preg_split('/\s+/u', $sunTitle) ?: [$sunTitle];
    $sunSplit = count($sunWords) >= 4 ? count($sunWords) - 2 : (count($sunWords) >= 2 ? count($sunWords) - 1 : 0);
    $sunLead = implode(' ', array_slice($sunWords, 0, $sunSplit));
    $sunMark = implode(' ', array_slice($sunWords, $sunSplit));
    $sunTotal = (int) ($totalCompanies ?? 0);
@endphp
<div class="sl-home">
    <section class="sl-hero">
        <div class="sl-wrap">
            <span class="sl-badge">{{ $directory->name ?? 'Firma Rehberi' }} · güncel işletme rehberi</span>
            <h1>@if($sunLead !== ''){{ $sunLead }} @endif<mark>{{ $sunMark }}</mark></h1>
            <p class="sl-hero__lede">{{ $settings->homepage_subtitle ?: 'İhtiyacınız olan hizmeti ya da firmayı yazın, şehrinizi seçin ve işletmelerle doğrudan iletişime geçin.' }}</p>

            <form id="ara" class="sl-search" action="{{ route('search') }}" method="GET" role="search">
                <div class="sl-search__field">
                    <label for="sl-q">Hizmet veya işletme</label>
                    <input id="sl-q" name="q" type="search" placeholder="Örn. su arıtma, kuaför, tesisatçı" autocomplete="off">
                </div>
                <div class="sl-search__field">
                    <label for="sl-city">Nerede?</label>
                    <select id="sl-city" name="city">
                        <option value="">Tüm şehirler</option>
                        @foreach($cities->where('slug', '!=', 'diger-iller') as $city)
                            <option value="{{ $city->slug }}">{{ $city->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="sl-pill" type="submit">Firma Bul</button>
            </form>

            @if($sunTotal >= 25 || $categories->count() >= 3 || $cities->count() >= 3)
                <div class="sl-stats" aria-label="Rehber özeti">
                    @if($sunTotal >= 25)<div class="sl-stat"><strong>{{ number_format($sunTotal, 0, ',', '.') }}</strong><span>Aktif firma</span></div>@endif
                    @if($categories->count() >= 3)<div class="sl-stat"><strong>{{ $categories->count() }}+</strong><span>Kategori</span></div>@endif
                    @if($cities->count() >= 3)<div class="sl-stat"><strong>{{ $cities->count() }}</strong><span>Şehir</span></div>@endif
                </div>
            @endif
        </div>
    </section>

    <section class="sl-section" id="firmalar">
        <div class="sl-wrap">
            <div class="sl-head">
                <div><h2>Öne çıkan işletmeler</h2><p>Profilini tamamlayan ve güncel tutan firmaları keşfedin.</p></div>
                <a class="sl-link" href="{{ route('companies.index') }}">Tüm firmalar →</a>
            </div>
            <div class="sl-cards">
                @forelse($sunFirms as $company)
                    @php
                        $open = $company->isOpenNow();
                        $rating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
                        $place = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
                        $tel = preg_replace('/[^0-9+]/', '', (string) $company->phone);
                    @endphp
                    <article class="sl-card">
                        <a class="sl-card__media" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profili">
                            @if($company->cover_image)<img src="{{ asset('storage/'.$company->cover_image) }}" alt="" loading="lazy" width="520" height="170">
                            @elseif($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="" loading="lazy" width="520" height="170" style="object-fit:contain;padding:22px;">
                            @else<span class="sl-card__initial" aria-hidden="true">{{ mb_substr($company->name, 0, 1) }}</span>@endif
                        </a>
                        <div class="sl-card__chips">
                            @if($open !== null)<span class="sl-chip {{ $open ? 'sl-chip--open' : 'sl-chip--closed' }}">{{ $open ? 'AÇIK' : 'KAPALI' }}</span>@endif
                            @if($company->hasActivePremium())<span class="sl-chip sl-chip--star">★ Öne çıkan</span>@endif
                            @if($rating)<span class="sl-chip">{{ $rating }} ★</span>@endif
                        </div>
                        <div class="sl-card__body">
                            <p class="sl-card__cat">{{ $company->category->name ?? 'İşletme' }}</p>
                            <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
                            <p class="sl-card__tag">{{ $company->short_description ?: 'Hizmetleri ve iletişim bilgilerini firma profilinde inceleyin.' }}</p>
                            @if($place !== '')<p class="sl-card__place">📍 {{ $place }}</p>@endif
                            <div class="sl-card__actions">
                                <a class="sl-pill" href="{{ route('companies.show', $company->slug) }}">İncele</a>
                                @if($tel !== '')<a class="sl-card__phone" href="tel:{{ $tel }}" aria-label="{{ $company->name }} ara">Ara</a>@endif
                            </div>
                        </div>
                    </article>
                @empty
                    <p class="sl-empty">Bu rehberde ilk firma sizinki olabilir. <a class="sl-link" href="{{ route('owner.register') }}">Ücretsiz firma ekleyin</a></p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="sl-section sl-section--white" id="nasil">
        <div class="sl-wrap">
            <div class="sl-head"><div><h2>Dört adımda aradığınıza ulaşın</h2><p>Karmaşık formlar yok: arayın, karşılaştırın, doğrudan görüşün.</p></div></div>
            <div class="sl-steps">
                <div class="sl-step"><span class="sl-step__no">1</span><h3>Ara</h3><p>Hizmeti, firmayı ya da şehri yazın; sonuçlar kategoriye ve konuma göre listelenir.</p></div>
                <div class="sl-step"><span class="sl-step__no">2</span><h3>Karşılaştır</h3><p>Firma profillerini, hizmetleri ve çalışma saatlerini yan yana inceleyin.</p></div>
                <div class="sl-step"><span class="sl-step__no">3</span><h3>İletişime geç</h3><p>Telefonla ya da WhatsApp ile firmayla doğrudan görüşün, aracıya gerek yok.</p></div>
                <div class="sl-step"><span class="sl-step__no">4</span><h3>Değerlendir</h3><p>Hizmet aldıktan sonra deneyiminizi paylaşın, başkalarına yol gösterin.</p></div>
            </div>
        </div>
    </section>

    <section class="sl-section" id="kategoriler">
        <div class="sl-wrap">
            <div class="sl-head"><div><h2>Hızlı keşfet</h2><p>Merak ettiğiniz kategoriyi seçin, firmalara geçin.</p></div></div>
            <div class="sl-quick">
                @forelse($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }} <small>{{ number_format($category->companies_count, 0, ',', '.') }}</small></a>
                @empty
                    <p class="sl-empty">Kategoriler, firmalar eklendikçe burada görünecek.</p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="sl-section sl-section--white">
        <div class="sl-wrap">
            <div class="sl-head"><div><h2>Neden {{ $directory->name ?? 'bu rehber' }}?</h2></div></div>
            <div class="sl-why">
                <div><h3>Doğrudan iletişim</h3><p>Firma telefonu ve WhatsApp bilgisi profilde açıktır. Komisyon ya da aracı yoktur.</p></div>
                <div><h3>Güncel bilgi</h3><p>Firmalar adres, hizmet ve çalışma saatlerini kendi panellerinden güncel tutar.</p></div>
                <div><h3>Ücretsiz başlangıç</h3><p>İşletmenizi eklemek ve profilinizi yönetmek ücretsizdir; isterseniz daha görünür olursunuz.</p></div>
            </div>
        </div>
    </section>

    @if($posts->isNotEmpty())
        <section class="sl-section">
            <div class="sl-wrap">
                <div class="sl-head"><div><h2>Rehber yazıları</h2><p>Doğru işletmeyi seçmek için kısa ve işe yarar yazılar.</p></div><a class="sl-link" href="{{ route('blog.index') }}">Tüm yazılar →</a></div>
                <div class="sl-posts">
                    @foreach($posts as $post)
                        <a class="sl-post" href="{{ route('blog.show', $post->slug) }}"><h3>{{ $post->title }}</h3>@if($post->excerpt)<p>{{ $post->excerpt }}</p>@endif<span class="sl-link">Oku →</span></a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    @if($cities->isNotEmpty())
        <section class="sl-section sl-section--white" id="sehirler">
            <div class="sl-wrap">
                <div class="sl-head"><div><h2>Popüler şehirler</h2></div></div>
                <div class="sl-cloud">
                    @foreach($cities->where('slug', '!=', 'diger-iller') as $city)
                        <a href="{{ route('cities.show', $city->slug) }}">{{ $city->name }}</a>
                    @endforeach
                </div>
            </div>
        </section>
    @endif

    <section class="sl-section">
        <div class="sl-wrap">
            <div class="sl-banner">
                <div><h2>İşletmenizi bugün ekleyin</h2><p>Birkaç dakikada profilinizi oluşturun, müşteriler sizi arayarak bulsun.</p></div>
                <a class="sl-pill sl-pill--dark" href="{{ route('owner.register') }}">Ücretsiz Firma Ekle</a>
            </div>
        </div>
    </section>

    <a class="sl-pill sl-float" id="sl-float" href="#ara">Firma Bul</a>
</div>
<script>
    // Mobilde yüzen "Firma Bul" düğmesi, arama kutusu ekrandayken gizlenir.
    (() => {
        const search = document.getElementById('ara');
        const button = document.getElementById('sl-float');
        if (!search || !button || !('IntersectionObserver' in window)) return;
        new IntersectionObserver(([entry]) => { button.style.visibility = entry.isIntersecting ? 'hidden' : 'visible'; }).observe(search);
    })();
</script>
@endsection
