@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Şehrinizdeki firmaların kartlarına bakın; numara kartın sol altında, dokunup arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $kvTotal = (int) ($totalCompanies ?? 0);
    $kvName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $kvFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->values();
    $kvFan = $kvFirms->count() >= 4 ? $kvFirms->first() : null;
    $kvRack = $kvFirms->take(9);
    // Kategori ve şehir listeleri ana sayfada 12 ile sınırlı; etikete yalnızca gerçek firma toplamı yazılır.
    $kvLabelText = implode(' · ', array_filter([
        $kvName.' · kart dosyası',
        $kvTotal >= 25 ? number_format($kvTotal, 0, ',', '.').' firma kartı' : null,
    ]));
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $kvDigits = function ($raw) {
        $d = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($d, '00')) { $d = substr($d, 2); }
        if (strlen($d) === 12 && str_starts_with($d, '90')) { $d = '0'.substr($d, 2); }
        if (strlen($d) === 10 && in_array($d[0], ['2', '3', '4', '5', '8'], true)) { $d = '0'.$d; }
        return $d;
    };
    $kvFmt = function ($raw) use ($kvDigits) {
        $d = $kvDigits($raw);
        return strlen($d) === 11 && $d[0] === '0'
            ? substr($d, 0, 4).' '.substr($d, 4, 3).' '.substr($d, 7, 2).' '.substr($d, 9, 2)
            : trim((string) $raw);
    };
    $kvTelHref = function ($raw) use ($kvDigits) {
        $d = $kvDigits($raw);
        if (strlen($d) === 11 && $d[0] === '0') { return $d; }
        return $d === '' ? '' : preg_replace('/[^0-9+]/', '', (string) $raw);
    };
    $kvRackIds = $kvRack->pluck('id')->all();
    $kvQuick = collect([
        ['Şu an açık, hemen ara', collect($openCompanies ?? [])],
        ['Onaylı firmalar, hemen ara', collect($trustedCompanies ?? [])],
        // Yedek kaynak, hemen üstteki kart rafıyla aynı havuz: rafta duran firmalar tekrar listelenmez.
        ['Hızlı arama fişi', collect($latestCompanies)->whereNotIn('id', $kvRackIds)],
    ])->map(fn ($s) => [$s[0], $s[1]->filter(fn ($c) => $kvTelHref($c->phone) !== '')->take(6)->values()])
      ->first(fn ($s) => $s[1]->count() >= 3);
    $kvTabs = ['var(--kv-tab-1)', 'var(--kv-tab-2)', 'var(--kv-tab-3)', 'var(--kv-tab-4)'];
    $kvWaNum = function ($raw) {
        $w = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($w, '00')) { $w = substr($w, 2); }
        if (str_starts_with($w, '0')) { $w = '9'.$w; }
        elseif (strlen($w) === 10 && str_starts_with($w, '5')) { $w = '90'.$w; }
        return $w;
    };
    $kvSearchCities = $cities->where('slug', '!=', 'diger-iller');
@endphp
<div class="kv-home">
    {{-- Masa --}}
    <section class="kv-hero kv-desk">
        <div class="kv-wrap kv-hero__grid">
            <div class="kv-hero__main">
                <p class="kv-label">{{ $kvLabelText }}</p>
                <h1 class="kv-h1">{{ $settings->homepage_title ?: 'Aradığınız firmanın kartı burada' }}</h1>
                <p class="kv-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmanın kartından doğrudan arayın ya da WhatsApp'tan yazın." }}</p>

                <form id="kv-ara" class="kv-fis kv-search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                    <div class="kv-fis__head" aria-hidden="true">Arama fişi</div>
                    <div class="kv-row">
                        <label for="kv-q">Ne arıyorsunuz?</label>
                        <input id="kv-q" name="q" type="search" placeholder="kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                    </div>
                    <div class="kv-row">
                        <label for="kv-city">Hangi şehirde?</label>
                        <select id="kv-city" name="city">
                            <option value="">Tüm şehirler</option>
                            @foreach($kvSearchCities as $kvCity)
                                <option value="{{ $kvCity->slug }}">{{ $kvCity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="kv-fis__foot">
                        <button class="kv-btn kv-btn--ink kv-btn--lg" type="submit">Kartları getir</button>
                    </div>
                </form>

                @if($categories->isNotEmpty())
                    <div class="kv-quicktabs">
                        <span class="kv-quicktabs__k">Sık açılan çekmeceler:</span>
                        <div class="kv-quicktabs__row">
                            @foreach($categories->take(4) as $kvCat)
                                <a class="kv-qtab" href="{{ route('categories.show', $kvCat->slug) }}" style="--tab: {{ $kvTabs[$loop->index % 4] }}">{{ $kvCat->name }}</a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="kv-fan">
                <span class="kv-fan__back kv-fan__back--1" aria-hidden="true"></span>
                <span class="kv-fan__back kv-fan__back--2" aria-hidden="true"></span>
                <div class="kv-fan__top">
                    @if($kvFan)
                        @include('partials.kartvizitlik.card', ['company' => $kvFan, 'kvNameTag' => 'p'])
                    @elseif($kvFirms->isNotEmpty())
                        @include('partials.kartvizitlik.blank-card', ['kvBlankTitle' => 'Senin kartın burada olabilir'])
                    @else
                        @include('partials.kartvizitlik.blank-card', ['kvBlankTitle' => 'İlk kart senin olsun'])
                    @endif
                </div>
            </div>
        </div>
    </section>

    {{-- Masadaki kartlar --}}
    <section id="kartlar" class="kv-section">
        <div class="kv-wrap">
            <div class="kv-head">
                <div>
                    <h2>Masadaki kartlar</h2>
                    <p>Her kartta numara aynı yerde durur: sol altta, iri rakamlarla. Dokun, ara.</p>
                </div>
            </div>
            @if($kvRack->isNotEmpty())
                <ul class="kv-rack" role="list">
                    @foreach($kvRack as $kvCo)
                        <li class="kv-rack__item{{ $kvFan && $loop->first ? ' kv-rack__item--fanned' : '' }}">@include('partials.kartvizitlik.card', ['company' => $kvCo])</li>
                        @if($loop->iteration === ($kvFan ? 4 : 3))
                            <li class="kv-rack__item">@include('partials.kartvizitlik.blank-card')</li>
                        @endif
                    @endforeach
                    @if($kvRack->count() < ($kvFan ? 4 : 3))
                        <li class="kv-rack__item">@include('partials.kartvizitlik.blank-card')</li>
                    @endif
                </ul>
                <div class="kv-more-row">
                    <a class="kv-btn kv-btn--line" href="{{ route('companies.index') }}">Tüm kartları gör @if($kvTotal >= 25)({{ number_format($kvTotal, 0, ',', '.') }} kart)@endif</a>
                </div>
            @else
                <div class="kv-empty">
                    <div class="kv-card kv-card--blank kv-card--xl kv-empty__card">
                        <span class="kv-tag">Senin yerin</span>
                        <h3 class="kv-card__name">Henüz kart yok</h3>
                        <p class="kv-empty__text">Masa boş. Firmanı ekle, müşterin seni kartından arasın.</p>
                        <div><a class="kv-btn kv-btn--red" href="{{ route('owner.register') }}">Kartını bırak</a></div>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Hızlı arama fişi --}}
    @if($kvQuick)
        <section class="kv-section">
            <div class="kv-wrap">
                <div class="kv-head">
                    <div>
                        <h2>{{ $kvQuick[0] }}</h2>
                        <p>Numaralar tek sütunda, alt alta. Firmayı bul, numaraya dokun.</p>
                    </div>
                </div>
                <div class="kv-fis kv-quick">
                    <div class="kv-quick__cols" aria-hidden="true"><span>Firma</span><span>Telefon</span></div>
                    <ul class="kv-quick__list" role="list">
                        @foreach($kvQuick[1] as $kvQ)
                            @php
                                $kvQTel = $kvTelHref($kvQ->phone);
                                $kvQWa = $kvWaNum($kvQ->whatsapp);
                            @endphp
                            <li class="kv-quick__row">
                                <span class="kv-quick__dot">@if($kvQ->isOpenNow() === true)<span class="kv-dot" aria-hidden="true"></span><span class="kv-sr">Şu an açık</span>@endif</span>
                                <span class="kv-quick__who">
                                    <a class="kv-quick__name" href="{{ route('companies.show', $kvQ->slug) }}">{{ $kvQ->name }}</a>
                                    <span class="kv-quick__cat">{{ $kvQ->category->name ?? 'İşletme' }}</span>
                                </span>
                                <a class="kv-quick__tel" href="tel:{{ $kvQTel }}" aria-label="{{ $kvQ->name }} ara: {{ $kvFmt($kvQ->phone) }}">{{ $kvFmt($kvQ->phone) }}</a>
                                @if($kvQWa !== '')
                                    <a class="kv-wa" href="https://wa.me/{{ $kvQWa }}" target="_blank" rel="noopener" aria-label="{{ $kvQ->name }} WhatsApp'tan yaz"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Z"/></svg></a>
                                @else
                                    <span class="kv-wa-gap" aria-hidden="true"></span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </section>
    @endif

    {{-- Çekmeceler (üst menü #cekmeceler bağlantısı için bölüm her zaman basılır) --}}
    <section id="cekmeceler" class="kv-section">
        <div class="kv-wrap">
            <div class="kv-head">
                <div>
                    <h2>Çekmeceler</h2>
                    <p>Her çekmecede bir hizmet alanının kartları durur.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <ul class="kv-drawers" role="list">
                    @foreach($categories as $kvCat)
                        @php $kvCatN = (int) ($kvCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="kv-drawer" href="{{ route('categories.show', $kvCat->slug) }}" style="--tab: {{ $kvTabs[$loop->index % 4] }}" aria-label="{{ $kvCatN > 0 ? $kvCat->name.', '.$kvCatN.' kart' : $kvCat->name }}">
                                <span class="kv-drawer__tab">{{ $kvCat->name }}</span>
                                <span class="kv-drawer__body">
                                    @if($kvCatN > 0)<span class="kv-drawer__count"><strong>{{ number_format($kvCatN, 0, ',', '.') }}</strong> kart</span>@endif
                                    <span class="kv-drawer__open">Çekmeceyi aç ›</span>
                                </span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="kv-after"><a class="kv-redlink" href="{{ route('owner.register') }}">Çekmecende kartın yok mu? Kartını bırak ›</a></p>
            @else
                <p class="kv-empty-line">Çekmeceler henüz boş. <a class="kv-link" href="{{ route('companies.index') }}">Tüm kartlar ›</a></p>
            @endif
        </div>
    </section>

    {{-- Şehir kutuları (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="kv-section">
        <div class="kv-wrap">
            <div class="kv-head">
                <div>
                    <h2>Şehir kutuları</h2>
                    <p>Şehrini seç, o kutudaki kartlara bak.</p>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <ul class="kv-boxes" role="list">
                    @foreach($cities as $kvCity)
                        @php $kvCityN = (int) ($kvCity->companies_count ?? 0); @endphp
                        <li>
                            <a class="kv-box" href="{{ route('cities.show', $kvCity->slug) }}">
                                <span class="kv-box__name">{{ $kvCity->name }}</span>
                                @if($kvCityN > 0)<span class="kv-box__n">{{ number_format($kvCityN, 0, ',', '.') }} kart</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="kv-empty-line">Şehir kutuları henüz boş. <a class="kv-link" href="{{ route('companies.index') }}">Tüm kartlar ›</a></p>
            @endif
        </div>
    </section>

    {{-- Masadaki notlar --}}
    @if($posts->isNotEmpty())
        <section class="kv-section">
            <div class="kv-wrap">
                <div class="kv-head">
                    <div>
                        <h2>Masadaki notlar</h2>
                        <p>Firma seçerken işine yarayacak kısa yazılar.</p>
                    </div>
                    <a class="kv-link" href="{{ route('blog.index') }}">Tüm notlar ›</a>
                </div>
                <ul class="kv-notes" role="list">
                    @foreach($posts as $kvPost)
                        <li>
                            <article class="kv-note">
                                <div class="kv-note__band">@if($kvPost->published_at)<time datetime="{{ $kvPost->published_at->toDateString() }}">{{ $kvPost->published_at->format('d.m.Y') }}</time>@endif</div>
                                <h3><a href="{{ route('blog.show', $kvPost->slug) }}">{{ $kvPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($kvPost->excerpt ?: strip_tags((string) $kvPost->content), 140) }}</p>
                                <a class="kv-link" href="{{ route('blog.show', $kvPost->slug) }}" aria-label="{{ $kvPost->title }}: notu oku">Notu oku ›</a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="kv-section">
            <div class="kv-wrap">
                <div class="kv-head">
                    <div>
                        <h2>İş ilanları</h2>
                        <p>Masadaki firmalardan yeni ilanlar.</p>
                    </div>
                    <a class="kv-link" href="{{ route('jobs.index') }}">Tüm ilanlar ›</a>
                </div>
                <ul class="kv-notes" role="list">
                    @foreach($homeJobs as $kvJob)
                        <li>
                            <a class="kv-job" href="{{ route('jobs.show', $kvJob->slug) }}">
                                <span class="kv-job__type">{{ match($kvJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="kv-job__title">{{ $kvJob->title }}</span>
                                <span class="kv-job__meta">{{ $kvJob->company->name ?? '' }} · {{ $kvJob->location ?: ($kvJob->company->city->name ?? '') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Boş kart --}}
    <section id="kartini-birak" class="kv-desk kv-cta">
        <div class="kv-wrap">
            <div class="kv-head kv-head--desk">
                <div>
                    <h2>Masada senin kartın eksik</h2>
                    <p>Firmanın adını boş karta yaz, kartını bırak. Kartın, buradaki kartlarla aynı düzende yayına girer.</p>
                </div>
            </div>
            <div class="kv-cta__grid">
                <form class="kv-card kv-card--blank kv-card--xl kv-cta__card" action="{{ route('owner.register') }}" method="GET">
                    <span class="kv-tag">Senin yerin</span>
                    <label for="kv-firma" class="kv-ghost-label">Firma adın</label>
                    <span class="kv-ghost-field"><input id="kv-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanın adını yaz"><span class="kv-pen" aria-hidden="true">✎</span></span>
                    <span class="kv-card__unvan kv-ghost-line"><span class="kv-card__unvan-rule" aria-hidden="true"></span>Hizmetin · Şehrin</span>
                    <span class="kv-strip kv-strip--form">
                        <span class="kv-ghost-phone" aria-hidden="true">0 5__ ___ __ __</span>
                        <button class="kv-btn kv-btn--red kv-btn--lg" type="submit">Kartını bırak</button>
                    </span>
                </form>
                <div class="kv-facts">
                    <ul role="list">
                        <li><span class="kv-tick" aria-hidden="true"></span>Kayıt ücretsiz</li>
                        <li><span class="kv-tick" aria-hidden="true"></span>Telefon ve WhatsApp butonu kartında</li>
                        <li><span class="kv-tick" aria-hidden="true"></span>Müşterin seni kartından arar</li>
                    </ul>
                    <a class="kv-link" href="{{ route('owner.dashboard') }}">Zaten kartın var mı? Firma paneline gir ›</a>
                </div>
            </div>
        </div>
    </section>

    <div class="kv-dock"><div class="kv-dock__bar"><a class="kv-btn kv-btn--ink" href="#kv-ara">Kart ara</a><a class="kv-btn kv-btn--red" href="{{ route('owner.register') }}">Kartını bırak</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.kv-dock'),f=document.getElementById('kv-ara');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
