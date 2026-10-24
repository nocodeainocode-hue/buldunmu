@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Her gün yeni bir yaprak: günün firmasını görün, aradığınız hizmeti bulun, firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $ytTotal = (int) ($totalCompanies ?? 0);
    $ytName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Uygulama saati UTC; Türkiye'de gece yarısından sonraki ilk 3 saat dünün yaprağı görünmesin.
    $ytNow = now('Europe/Istanbul')->locale('tr');
    $ytSearchCities = $cities->where('slug', '!=', 'diger-iller');

    // Günün firması: önce öne çıkanlar, sonra son eklenenler; yılın gününe göre sırayla seçilir.
    // Aynı gün içinde hep aynı firma, ertesi gün sıradaki firma. Önceki yapraklar aynı kuralla dünün, evvelsi günün firmasıdır.
    $ytPool = $premiumCompanies->concat($latestCompanies)->unique('id')->values();
    $ytN = $ytPool->count();
    $ytPick = fn (int $back) => $ytN > 0 ? $ytPool[((($ytNow->dayOfYear - $back) % $ytN) + $ytN) % $ytN] : null;
    $ytToday = $ytPick(0);
    $ytEarlier = collect(range(1, min(3, max(0, $ytN - 1))))
        ->filter(fn ($k) => $ytN > 1)
        ->map(fn ($k) => ['company' => $ytPick($k), 'date' => $ytNow->copy()->subDays($k), 'label' => match ($k) { 1 => 'Dünün yaprağı', 2 => 'Evvelsi günün yaprağı', default => $k.' gün önceki yaprak' }])
        ->values();

    // Bu haftanın yaprakları: hero'da görünmeyen firmalar; az firma varsa yalnızca günün firması çıkarılır.
    $ytHeroIds = collect([$ytToday?->id])->concat($ytEarlier->pluck('company.id'))->filter()->all();
    $ytRest = $ytPool->whereNotIn('id', $ytHeroIds)->values();
    $ytWeekSrc = ($ytRest->count() >= 3 ? $ytRest : $ytPool->where('id', '!=', $ytToday?->id))->values();
    // Boş yaprakla birlikte masaüstü sırası tam dolsun (3'ün katı); bu mümkün değilse eldeki firmalar gösterilir.
    $ytWeekN = min(8, $ytWeekSrc->count());
    while ($ytWeekN > 2 && ($ytWeekN + 1) % 3 !== 0) { $ytWeekN--; }
    $ytWeek = $ytWeekSrc->take($ytWeekN)->values();
    // Hizmet yaprakları: geniş ekranda sütun sayısı, son sıra boş kalmayacak şekilde seçilir.
    $ytCatCount = $categories->count();
    $ytMonthCols = collect([6, 5, 4, 3])->first(fn ($c) => $ytCatCount >= $c && $ytCatCount % $c === 0) ?? min(6, max(1, $ytCatCount));
    $ytCityCols = max(1, min(3, $cities->count()));

    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $ytDigits = function ($raw) {
        $d = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($d, '00')) { $d = substr($d, 2); }
        if (strlen($d) === 12 && str_starts_with($d, '90')) { $d = '0'.substr($d, 2); }
        if (strlen($d) === 10 && in_array($d[0], ['2', '3', '4', '5', '8'], true)) { $d = '0'.$d; }
        return $d;
    };
    $ytFmt = function ($raw) use ($ytDigits) {
        $d = $ytDigits($raw);
        return strlen($d) === 11 && $d[0] === '0'
            ? substr($d, 0, 4).' '.substr($d, 4, 3).' '.substr($d, 7, 2).' '.substr($d, 9, 2)
            : trim((string) $raw);
    };
    $ytTelHref = function ($raw) use ($ytDigits) {
        $d = $ytDigits($raw);
        if (strlen($d) === 11 && $d[0] === '0') { return $d; }
        return preg_match('/\d/', (string) $raw) ? preg_replace('/[^0-9+]/', '', (string) $raw) : '';
    };
    $ytWaNum = function ($raw) {
        $w = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($w, '00')) { $w = substr($w, 2); }
        if (str_starts_with($w, '0')) { $w = '9'.$w; }
        elseif (strlen($w) === 10 && str_starts_with($w, '5')) { $w = '90'.$w; }
        return $w;
    };
    $ytPlace = fn ($c) => collect([$c->district->name ?? null, $c->city->name ?? null])->filter()->implode(' / ');

    if ($ytToday) {
        $ytTodayTel = $ytTelHref($ytToday->phone);
        $ytTodayWa = $ytWaNum($ytToday->whatsapp);
        $ytTodayOpen = $ytToday->isOpenNow();
    }
    $ytOpenN = collect($openCompanies ?? [])->count();
@endphp
<div class="yt-home">
    {{-- Duvar: bugünün yaprağı ve koparılan önceki yapraklar --}}
    <section class="yt-hero" id="ara">
        <div class="yt-wrap">
            <div class="yt-hero__head">
                <p class="yt-kicker">{{ $ytName }} · duvar takvimi</p>
                <h1 class="yt-h1">{{ $settings->homepage_title ?: 'Her gün yeni bir yaprak, her yaprakta bir firma' }}</h1>
                <p class="yt-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}</p>
            </div>

            <div class="yt-hero__grid{{ $ytEarlier->isEmpty() && ! $ytToday ? ' yt-hero__grid--solo' : '' }}">
                <div class="yt-pad">
                    <div class="yt-pad__board" aria-hidden="true">
                        <span class="yt-pad__nail"></span>
                        <span class="yt-pad__brand">{{ $ytName }}</span>
                        <span class="yt-pad__year">{{ $ytNow->year }}</span>
                    </div>
                    <div class="yt-pad__stub" aria-hidden="true"></div>
                    <article class="yt-today" aria-label="Bugünün yaprağı: {{ $ytNow->isoFormat('D MMMM YYYY dddd') }}">
                        <div class="yt-today__top">
                            <span>{{ $ytNow->year }}</span>
                            <span class="yt-today__month">{{ $ytNow->isoFormat('MMMM') }}</span>
                            <span>{{ $ytNow->isoWeek() }}. hafta</span>
                        </div>
                        <div class="yt-today__main">
                            <div class="yt-today__date">
                                <time class="yt-today__num" datetime="{{ $ytNow->toDateString() }}">{{ $ytNow->day }}</time>
                                <span class="yt-today__wd">{{ $ytNow->isoFormat('dddd') }}</span>
                                <dl class="yt-today__rows">
                                    <div><dt>Tarih</dt><dd>{{ $ytNow->format('d.m.Y') }}</dd></div>
                                    <div><dt>Yarın</dt><dd>{{ $ytNow->copy()->addDay()->isoFormat('D MMMM dddd') }}</dd></div>
                                    @if($ytOpenN >= 3)<div><dt>Şu an açık</dt><dd>{{ $ytOpenN }} firma</dd></div>@endif
                                    @if($ytTotal >= 25)<div><dt>Kayıtlı</dt><dd>{{ number_format($ytTotal, 0, ',', '.') }} firma</dd></div>@endif
                                </dl>
                            </div>
                            <div class="yt-today__firm">
                                <p class="yt-today__label"><span>Günün firması</span></p>
                                @if($ytToday)
                                    <h2 class="yt-today__name"><a href="{{ route('companies.show', $ytToday->slug) }}">{{ $ytToday->name }}</a></h2>
                                    <p class="yt-today__meta">
                                        <span class="yt-today__cat">{{ $ytToday->category->name ?? 'İşletme' }}</span>
                                        @if($ytPlace($ytToday) !== '')<span class="yt-today__place"><svg width="14" height="14" aria-hidden="true"><use href="#yt-ico-pin"/></svg>{{ $ytPlace($ytToday) }}</span>@endif
                                    </p>
                                    @if(filled($ytToday->short_description))<p class="yt-today__quote">{{ $ytToday->short_description }}</p>@endif
                                    @if($ytToday->hasActivePremium() || $ytToday->is_verified || $ytTodayOpen === true)
                                        <p class="yt-today__chips">
                                            @if($ytToday->hasActivePremium())<span class="yt-chip yt-chip--red">Öne çıkan</span>@endif
                                            @if($ytToday->is_verified)<span class="yt-chip yt-chip--ok">Onaylı</span>@endif
                                            @if($ytTodayOpen === true)<span class="yt-chip yt-chip--open">Şu an açık</span>@endif
                                        </p>
                                    @endif
                                    <div class="yt-today__acts">
                                        @if($ytTodayTel !== '')
                                            <a class="yt-call yt-call--big" href="tel:{{ $ytTodayTel }}" aria-label="{{ $ytToday->name }} ara: {{ $ytFmt($ytToday->phone) }}"><svg width="20" height="20" aria-hidden="true"><use href="#yt-ico-phone"/></svg><span>{{ $ytFmt($ytToday->phone) }}</span></a>
                                        @endif
                                        @if($ytTodayWa !== '')
                                            <a class="yt-call yt-call--wa{{ $ytTodayTel === '' ? ' yt-call--big' : '' }}" href="https://wa.me/{{ $ytTodayWa }}" target="_blank" rel="noopener" aria-label="{{ $ytToday->name }} WhatsApp'tan yaz"><svg width="20" height="20" aria-hidden="true"><use href="#yt-ico-wa"/></svg><span>WhatsApp</span></a>
                                        @endif
                                        <a class="yt-link" href="{{ route('companies.show', $ytToday->slug) }}">Firma sayfası <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                                    </div>
                                @else
                                    <h2 class="yt-today__name">Bu yaprak henüz boş</h2>
                                    <p class="yt-today__quote">Takvimin ilk firması siz olun: işletmenizi ücretsiz ekleyin, müşteriler sizi buradan arasın.</p>
                                    <div class="yt-today__acts">
                                        <a class="yt-call yt-call--big" href="{{ route('owner.register') }}"><span>Firmanızı ekleyin</span></a>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <form id="yt-ara" class="yt-today__search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                            <div class="yt-field yt-field--q">
                                <label for="yt-q">Ne arıyorsunuz?</label>
                                <input id="yt-q" name="q" type="search" placeholder="Örn. kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                            </div>
                            <div class="yt-field yt-field--city">
                                <label for="yt-city">Şehir</label>
                                <select id="yt-city" name="city">
                                    <option value="">Tüm şehirler</option>
                                    @foreach($ytSearchCities as $ytCity)
                                        <option value="{{ $ytCity->slug }}">{{ $ytCity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="yt-search__btn" type="submit"><svg width="18" height="18" aria-hidden="true"><use href="#yt-ico-search"/></svg>Firma bul</button>
                        </form>
                    </article>
                </div>

                @if($ytToday)
                    <aside class="yt-torn" aria-label="Önceki günlerin yaprakları">
                        <p class="yt-torn__title">Koparılan yapraklar</p>
                        <ol class="yt-torn__list" role="list">
                            @foreach($ytEarlier as $ytE)
                                @php $ytEC = $ytE['company']; $ytETel = $ytTelHref($ytEC->phone); @endphp
                                <li class="yt-torn__item yt-torn__item--{{ $loop->iteration }}">
                                    <article class="yt-torn__leaf">
                                        <div class="yt-torn__date">
                                            <time class="yt-torn__num" datetime="{{ $ytE['date']->toDateString() }}">{{ $ytE['date']->day }}</time>
                                            <span class="yt-torn__md">{{ $ytE['date']->isoFormat('MMMM') }}<br>{{ $ytE['date']->isoFormat('dddd') }}</span>
                                        </div>
                                        <div class="yt-torn__info">
                                            <p class="yt-torn__label">{{ $ytE['label'] }}</p>
                                            <p class="yt-torn__name"><a href="{{ route('companies.show', $ytEC->slug) }}">{{ $ytEC->name }}</a></p>
                                            <p class="yt-torn__meta">{{ collect([$ytEC->category->name ?? null, $ytEC->city->name ?? null])->filter()->implode(' · ') }}</p>
                                            @if($ytETel !== '')
                                                <a class="yt-torn__tel" href="tel:{{ $ytETel }}" aria-label="{{ $ytEC->name }} ara: {{ $ytFmt($ytEC->phone) }}"><svg width="15" height="15" aria-hidden="true"><use href="#yt-ico-phone"/></svg>{{ $ytFmt($ytEC->phone) }}</a>
                                            @endif
                                        </div>
                                    </article>
                                </li>
                            @endforeach
                            @if($ytEarlier->count() < 3)
                                <li class="yt-torn__item yt-torn__item--cta">
                                    <a class="yt-torn__leaf yt-torn__leaf--blank" href="{{ route('owner.register') }}">
                                        <span class="yt-torn__label">Boş yaprak</span>
                                        <span class="yt-torn__name">Takvimde yeriniz hazır</span>
                                        <span class="yt-torn__meta">Firmanızı ücretsiz ekleyin ›</span>
                                    </a>
                                </li>
                            @endif
                        </ol>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    {{-- Bu haftanın yaprakları --}}
    <section id="firmalar" class="yt-section">
        <div class="yt-wrap">
            <div class="yt-head">
                <div>
                    <p class="yt-kicker">Firmalar</p>
                    <h2 class="yt-h2">Bu haftanın yaprakları</h2>
                </div>
                @if($ytN > 0)<a class="yt-link" href="{{ route('companies.index') }}">Tüm firmalar @if($ytTotal >= 25)({{ number_format($ytTotal, 0, ',', '.') }})@endif <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>@endif
            </div>
            @if($ytN > 0)
                <ul class="yt-leaves" role="list">
                    @foreach($ytWeek as $ytCo)
                        <li>@include('partials.yaprak-takvim.card', ['company' => $ytCo])</li>
                    @endforeach
                    <li class="yt-leaves__cta">
                        <div class="yt-blank">
                            <span class="yt-blank__tag">Boş yaprak</span>
                            <h3>Bu yaprakta sizin firmanız olabilir</h3>
                            <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                            <a class="yt-btn yt-btn--red" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                        </div>
                    </li>
                </ul>
            @else
                <div class="yt-blank yt-blank--empty">
                    <span class="yt-blank__tag">Boş yaprak</span>
                    <h3>Takvimin yaprakları henüz boş</h3>
                    <p>Henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, ilk yaprak sizin olsun.</p>
                    <a class="yt-btn yt-btn--red" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Hizmetler: ay sekmeleri gibi küçük yapraklar (üst menü #hizmetler bağlantısı için bölüm her zaman basılır) --}}
    <section id="hizmetler" class="yt-section yt-section--wall">
        <div class="yt-wrap">
            <div class="yt-head">
                <div>
                    <p class="yt-kicker">Hizmetler</p>
                    <h2 class="yt-h2">Hizmet takvimi</h2>
                    <p class="yt-sub">Bir hizmet yaprağını açın, o alandaki firmalar listelensin.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <ul class="yt-months" role="list" style="--yt-cols: {{ $ytMonthCols }}">
                    @foreach($categories as $ytCat)
                        @php $ytCatN = (int) ($ytCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="yt-month" href="{{ route('categories.show', $ytCat->slug) }}" aria-label="{{ $ytCatN > 0 ? $ytCat->name.', '.$ytCatN.' firma' : $ytCat->name }}">
                                <span class="yt-month__band" aria-hidden="true"></span>
                                @if($ytCatN > 0)
                                    <span class="yt-month__num">{{ number_format($ytCatN, 0, ',', '.') }}</span>
                                    <span class="yt-month__unit">firma</span>
                                @endif
                                <span class="yt-month__name">{{ $ytCat->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="yt-after"><a class="yt-link" href="{{ route('owner.register') }}">Hizmetiniz listede yok mu? Firmanızı ekleyin <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a></p>
            @else
                <p class="yt-empty-line">Hizmet yaprakları firmalar eklendikçe dolacak. <a class="yt-link" href="{{ route('owner.register') }}">Firmanızı ekleyin</a></p>
            @endif
        </div>
    </section>

    {{-- Şehir almanağı (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="yt-section">
        <div class="yt-wrap">
            <div class="yt-head">
                <div>
                    <p class="yt-kicker">Şehirler</p>
                    <h2 class="yt-h2">Şehir almanağı</h2>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <div class="yt-almanac yt-almanac--c{{ $ytCityCols }}">
                    <ul class="yt-almanac__list" role="list">
                        @foreach($cities as $ytCity)
                            @php $ytCityN = (int) ($ytCity->companies_count ?? 0); @endphp
                            <li>
                                <a class="yt-almanac__row" href="{{ route('cities.show', $ytCity->slug) }}">
                                    <span class="yt-almanac__name">{{ $ytCity->name }}</span>
                                    <span class="yt-almanac__dots" aria-hidden="true"></span>
                                    <span class="yt-almanac__n">@if($ytCityN > 0){{ number_format($ytCityN, 0, ',', '.') }}@else<span aria-hidden="true">›</span>@endif</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
            @else
                <p class="yt-empty-line">Şehirler firmalar eklendikçe almanağa yazılacak. <a class="yt-link" href="{{ route('companies.index') }}">Tüm firmalar</a></p>
            @endif
        </div>
    </section>

    {{-- Takvim arkası yazıları --}}
    @if($posts->isNotEmpty())
        <section class="yt-section yt-section--wall">
            <div class="yt-wrap">
                <div class="yt-head">
                    <div>
                        <p class="yt-kicker">Yaprağın arkası</p>
                        <h2 class="yt-h2">Takvim arkası yazıları</h2>
                    </div>
                    <a class="yt-link" href="{{ route('blog.index') }}">Tüm yazılar <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                </div>
                <ul class="yt-backs" role="list">
                    @foreach($posts as $ytPost)
                        <li>
                            <article class="yt-back">
                                @if($ytPost->published_at)
                                    @php $ytPd = $ytPost->published_at->copy()->locale('tr'); @endphp
                                    <time class="yt-back__date" datetime="{{ $ytPd->toDateString() }}"><span class="yt-back__num">{{ $ytPd->day }}</span><span class="yt-back__mon">{{ $ytPd->isoFormat('MMMM YYYY') }}</span></time>
                                @endif
                                <h3><a href="{{ route('blog.show', $ytPost->slug) }}">{{ $ytPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($ytPost->excerpt ?: strip_tags((string) $ytPost->content), 150) }}</p>
                                <a class="yt-link" href="{{ route('blog.show', $ytPost->slug) }}" aria-label="{{ $ytPost->title }}: yazıyı oku">Yazıyı oku <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="yt-section">
            <div class="yt-wrap">
                <div class="yt-head">
                    <div>
                        <p class="yt-kicker">Kariyer</p>
                        <h2 class="yt-h2">İş ilanları</h2>
                    </div>
                    <a class="yt-link" href="{{ route('jobs.index') }}">Tüm ilanlar <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                </div>
                <ul class="yt-jobs" role="list">
                    @foreach($homeJobs as $ytJob)
                        <li>
                            <a class="yt-job" href="{{ route('jobs.show', $ytJob->slug) }}">
                                <span class="yt-job__type">{{ match($ytJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="yt-job__title">{{ $ytJob->title }}</span>
                                <span class="yt-job__meta">{{ collect([$ytJob->company->name ?? null, $ytJob->location ?: ($ytJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Takvimde yerinizi alın --}}
    <section id="yeriniz" class="yt-section yt-cta-sec">
        <div class="yt-wrap">
            <div class="yt-cta">
                <div class="yt-cta__leaf" aria-hidden="true">
                    <span class="yt-cta__band"></span>
                    <span class="yt-cta__num">?</span>
                    <span class="yt-cta__line"></span>
                    <span class="yt-cta__word">Firmanız</span>
                    <span class="yt-cta__line yt-cta__line--short"></span>
                </div>
                <div class="yt-cta__txt">
                    <p class="yt-kicker">Firma sahipleri için</p>
                    <h2 class="yt-h2">Takvimde yerinizi alın</h2>
                    <p>İşletmenizi ücretsiz ekleyin; yaprağınızda telefon ve WhatsApp düğmesi dursun, müşteriler size doğrudan ulaşsın.</p>
                    <form class="yt-cta__form" action="{{ route('owner.register') }}" method="GET">
                        <label for="yt-firma">Firmanızın adı</label>
                        <div class="yt-cta__row">
                            <input id="yt-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                            <button class="yt-btn yt-btn--red yt-btn--lg" type="submit">Ücretsiz kayıt</button>
                        </div>
                    </form>
                    <p class="yt-cta__links">
                        <a class="yt-link" href="{{ route('owner.dashboard') }}">Firma paneli <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                        <a class="yt-link" href="{{ route('packages.index') }}">Öne çıkma seçenekleri <svg width="16" height="16" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="yt-dock"><div class="yt-dock__bar"><a class="yt-btn yt-btn--line" href="#yt-ara">Firma ara</a><a class="yt-btn yt-btn--red" href="{{ route('owner.register') }}">Firma ekle</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.yt-dock'),f=document.querySelector('.yt-pad');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
