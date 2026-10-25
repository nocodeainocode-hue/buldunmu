@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Aradığınız hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin, pulunuz mektup kutusunda yerini alsın.')

@section('content')
@php
    $ppTotal = (int) ($totalCompanies ?? 0);
    $ppName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Uygulama saati UTC; damga tarihi ve günün pulu İstanbul saatine göre.
    $ppNow = now('Europe/Istanbul')->locale('tr');
    $ppSearchCities = $cities->where('slug', '!=', 'diger-iller');
    $ppUp = fn ($s) => mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'], (string) $s), 'UTF-8');
    $ppMono = function ($name) use ($ppUp) {
        $words = collect(preg_split('/\s+/u', trim((string) $name)))->filter(fn ($w) => preg_match('/\p{L}/u', $w))->values();
        return $ppUp($words->take(2)->map(fn ($w) => mb_substr(preg_replace('/^[^\p{L}]+/u', '', $w), 0, 1))->implode(''));
    };
    $ppPlace = fn ($c) => collect([$c->district->name ?? null, $c->city->name ?? null])->filter()->implode(' / ');

    // Günün pulu: önce öne çıkanlar, sonra son eklenenler; yılın gününe göre sırayla seçilir. Yanındaki pullar sıradakilerdir.
    $ppPool = $premiumCompanies->concat($latestCompanies)->unique('id')->values();
    $ppN = $ppPool->count();
    $ppStart = $ppN > 0 ? $ppNow->dayOfYear % $ppN : 0;
    $ppAt = fn (int $k) => $ppPool[($ppStart + $k) % $ppN];
    $ppToday = $ppN > 0 ? $ppAt(0) : null;
    // Pul renkleri sıraya göre: hero'daki pulların hiçbiri aynı renkte olmaz.
    $ppToneAt = fn (int $k) => ['green', 'ochre', 'blue', 'red'][($ppStart + $k) % 4];
    $ppSide = $ppN > 1 ? collect(range(1, min(3, $ppN - 1)))->map(fn ($k) => $ppAt($k))->values() : collect();

    // Yeni gelen mektuplar: hero'da görünmeyen firmalar; az firma varsa yalnızca günün pulu çıkarılır.
    $ppHeroIds = collect([$ppToday?->id])->concat($ppSide->pluck('id'))->filter()->all();
    $ppRest = $ppPool->whereNotIn('id', $ppHeroIds)->values();
    $ppLetterSrc = ($ppRest->count() >= 3 ? $ppRest : $ppPool->where('id', '!=', $ppToday?->id))->values();
    // Boş pul yeri kartıyla birlikte masaüstü sırası tam dolsun (3'ün katı).
    $ppLetterN = min(8, $ppLetterSrc->count());
    while ($ppLetterN > 2 && ($ppLetterN + 1) % 3 !== 0) { $ppLetterN--; }
    $ppLetters = $ppLetterSrc->take($ppLetterN)->values();

    // Pul tabakası: geniş ekranda sütun sayısı, son sıra boş kalmayacak şekilde seçilir.
    $ppCatCount = $categories->count();
    $ppSheetCols = collect([6, 5, 4, 3])->first(fn ($c) => $ppCatCount >= $c && $ppCatCount % $c === 0) ?? min(6, max(1, $ppCatCount));
    $ppCityCount = $cities->count();
    $ppCityCols = collect([6, 5, 4, 3])->first(fn ($c) => $ppCityCount >= $c && $ppCityCount % $c === 0) ?? min(6, max(1, $ppCityCount));

    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $ppDigits = function ($raw) {
        $d = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($d, '00')) { $d = substr($d, 2); }
        if (strlen($d) === 12 && str_starts_with($d, '90')) { $d = '0'.substr($d, 2); }
        if (strlen($d) === 10 && in_array($d[0], ['2', '3', '4', '5', '8'], true)) { $d = '0'.$d; }
        return $d;
    };
    $ppFmt = function ($raw) use ($ppDigits) {
        $d = $ppDigits($raw);
        return strlen($d) === 11 && $d[0] === '0'
            ? substr($d, 0, 4).' '.substr($d, 4, 3).' '.substr($d, 7, 2).' '.substr($d, 9, 2)
            : trim((string) $raw);
    };
    $ppTelHref = function ($raw) use ($ppDigits) {
        $d = $ppDigits($raw);
        if (strlen($d) === 11 && $d[0] === '0') { return $d; }
        return preg_match('/\d/', (string) $raw) ? preg_replace('/[^0-9+]/', '', (string) $raw) : '';
    };
    $ppWaNum = function ($raw) {
        $w = preg_replace('/\D/', '', (string) $raw);
        if (str_starts_with($w, '00')) { $w = substr($w, 2); }
        if (str_starts_with($w, '0')) { $w = '9'.$w; }
        elseif (strlen($w) === 10 && str_starts_with($w, '5')) { $w = '90'.$w; }
        return $w;
    };

    if ($ppToday) {
        $ppTodayTel = $ppTelHref($ppToday->phone);
        $ppTodayWa = $ppWaNum($ppToday->whatsapp);
        $ppTodayOpen = $ppToday->isOpenNow();
        $ppTodayCity = $ppToday->city->name ?? ($ppSearchCities->first()->name ?? $ppName);
    }
    $ppDateShort = $ppNow->format('d.m.Y');
@endphp
<div class="pp-home">
    {{-- Zarf: adres bloğu arama formu, sağ üstte günün pulu, yanında masadaki pullar --}}
    <section class="pp-hero" id="ara">
        <div class="pp-wrap">
            <div class="pp-hero__head">
                <p class="pp-kicker">{{ $ppName }}</p>
                <h1 class="pp-h1">{{ $settings->homepage_title ?: 'Aradığınız firma bir mektup uzağınızda' }}</h1>
                <p class="pp-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}@if($ppTotal >= 25) <span class="pp-lede__n">Mektup kutusunda {{ number_format($ppTotal, 0, ',', '.') }} firma var.</span>@endif</p>
            </div>

            <div class="pp-hero__grid{{ $ppToday ? '' : ' pp-hero__grid--solo' }}">
                <div class="pp-env pp-airmail">
                    <div class="pp-env__in">
                        <div class="pp-env__from">
                            <span class="pp-airlabel">Uçak ile</span>
                            <p class="pp-env__sender"><span class="pp-hand">Gönderen:</span> {{ $ppName }}</p>
                        </div>

                        <div class="pp-env__corner">
                            @if($ppToday)
                                <div class="pp-cancel" aria-hidden="true">
                                    @include('partials.posta-pulu.postmark', ['id' => 'pp-pm-hero', 'top' => $ppTodayCity, 'mid' => $ppDateShort, 'bottom' => 'Günün pulu'])
                                    <svg class="pp-waves" width="220" height="64"><use href="#pp-ico-waves"/></svg>
                                </div>
                                <div class="pp-shadow pp-env__stamp">
                                    <article class="pp-perf pp-stamp pp-stamp--lg pp-tone--{{ $ppToneAt(0) }}" lang="tr">
                                        <div class="pp-stamp__in">
                                            <div class="pp-stamp__row">
                                                <span class="pp-stamp__den">{{ $ppNow->day }}<small>{{ $ppUp($ppNow->isoFormat('MMMM')) }}</small></span>
                                                <span class="pp-stamp__tag">Günün pulu</span>
                                            </div>
                                            <span class="pp-stamp__mono" aria-hidden="true">{{ $ppMono($ppToday->name) }}</span>
                                            <h2 class="pp-stamp__name"><a href="{{ route('companies.show', $ppToday->slug) }}">{{ $ppToday->name }}</a></h2>
                                            <p class="pp-stamp__cat">{{ $ppToday->category->name ?? 'İşletme' }}</p>
                                        </div>
                                    </article>
                                </div>
                            @else
                                <div class="pp-slot pp-slot--env">
                                    <span class="pp-slot__t">Pul yeri</span>
                                    <span class="pp-hand">henüz boş</span>
                                </div>
                            @endif
                        </div>

                        @if($ppToday)
                            <div class="pp-env__feat">
                                <p class="pp-feat__meta">
                                    @if($ppPlace($ppToday) !== '')<span class="pp-feat__place"><svg width="15" height="15" aria-hidden="true"><use href="#pp-ico-pin"/></svg>{{ $ppPlace($ppToday) }}</span>@endif
                                    @if($ppToday->hasActivePremium())<span class="pp-rubber pp-rubber--red">Taahhütlü</span>@endif
                                    @if($ppToday->is_verified)<span class="pp-rubber pp-rubber--green">Onaylı</span>@endif
                                    @if($ppTodayOpen === true)<span class="pp-chip pp-chip--open">Şu an açık</span>@endif
                                </p>
                                <div class="pp-feat__acts">
                                    @if($ppTodayTel !== '')
                                        <a class="pp-call pp-call--big" href="tel:{{ $ppTodayTel }}" aria-label="{{ $ppToday->name }} ara: {{ $ppFmt($ppToday->phone) }}"><svg width="20" height="20" aria-hidden="true"><use href="#pp-ico-phone"/></svg><span>{{ $ppFmt($ppToday->phone) }}</span></a>
                                    @endif
                                    @if($ppTodayWa !== '')
                                        <a class="pp-call pp-call--wa{{ $ppTodayTel === '' ? ' pp-call--big' : '' }}" href="https://wa.me/{{ $ppTodayWa }}" target="_blank" rel="noopener" aria-label="{{ $ppToday->name }} WhatsApp'tan yaz"><svg width="20" height="20" aria-hidden="true"><use href="#pp-ico-wa"/></svg><span>WhatsApp</span></a>
                                    @endif
                                    <a class="pp-link" href="{{ route('companies.show', $ppToday->slug) }}">Firma sayfası <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                                </div>
                            </div>
                        @endif

                        <form id="pp-ara" class="pp-address" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                            <div class="pp-address__line">
                                <label for="pp-q"><span class="pp-hand">Kime:</span> <span class="pp-address__hint">ne arıyorsunuz?</span></label>
                                <input id="pp-q" name="q" type="search" placeholder="kuaför, tesisatçı…" autocomplete="off" enterkeyhint="search">
                            </div>
                            <div class="pp-address__line">
                                <label for="pp-city"><span class="pp-hand">Nereye:</span> <span class="pp-address__hint">şehir</span></label>
                                <select id="pp-city" name="city">
                                    <option value="">Tüm şehirler</option>
                                    @foreach($ppSearchCities as $ppCity)
                                        <option value="{{ $ppCity->slug }}">{{ $ppCity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="pp-address__send">
                                <button class="pp-btn pp-btn--red pp-btn--lg" type="submit"><svg width="19" height="19" aria-hidden="true"><use href="#pp-ico-send"/></svg>Gönder · Firma bul</button>
                            </div>
                        </form>

                        @unless($ppToday)
                            <p class="pp-env__empty">Mektup kutusu henüz boş. İlk pulu siz yapıştırın: <a class="pp-link" href="{{ route('owner.register') }}">Firmanızı ücretsiz ekleyin</a></p>
                        @endunless
                    </div>
                </div>

                @if($ppToday)
                    <aside class="pp-pile" aria-label="Masadaki pullar">
                        <p class="pp-pile__title">Masadaki pullar</p>
                        <ul class="pp-pile__list" role="list">
                            @foreach($ppSide as $ppSi => $ppS)
                                @php $ppSTel = $ppTelHref($ppS->phone); @endphp
                                <li class="pp-pile__item">
                                    <div class="pp-shadow">
                                        <article class="pp-perf pp-stamp pp-tone--{{ $ppToneAt($ppSi + 1) }}" lang="tr">
                                            <div class="pp-stamp__in">
                                                <div class="pp-stamp__row"><span class="pp-stamp__tag">{{ $ppS->category->name ?? 'İşletme' }}</span></div>
                                                <span class="pp-stamp__mono" aria-hidden="true">{{ $ppMono($ppS->name) }}</span>
                                                <h3 class="pp-stamp__name"><a href="{{ route('companies.show', $ppS->slug) }}">{{ $ppS->name }}</a></h3>
                                                @if($ppSTel !== '')
                                                    <a class="pp-stamp__tel" href="tel:{{ $ppSTel }}" aria-label="{{ $ppS->name }} ara: {{ $ppFmt($ppS->phone) }}"><svg width="14" height="14" aria-hidden="true"><use href="#pp-ico-phone"/></svg>{{ $ppFmt($ppS->phone) }}</a>
                                                @endif
                                            </div>
                                        </article>
                                    </div>
                                </li>
                            @endforeach
                            @if($ppSide->count() < 3)
                                <li class="pp-pile__item pp-pile__item--slot">
                                    <a class="pp-slot" href="{{ route('owner.register') }}">
                                        <span class="pp-slot__t">Pul yeri</span>
                                        <span class="pp-hand">sizin firmanız</span>
                                        <span class="pp-slot__go">Ücretsiz ekleyin ›</span>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </aside>
                @endif
            </div>
        </div>
    </section>

    {{-- Yeni gelen mektuplar --}}
    <section id="firmalar" class="pp-section">
        <div class="pp-wrap">
            <div class="pp-head">
                <div>
                    <p class="pp-kicker">Firmalar</p>
                    <h2 class="pp-h2">Yeni gelen mektuplar</h2>
                </div>
                @if($ppN > 0)<a class="pp-link" href="{{ route('companies.index') }}">Tüm firmalar @if($ppTotal >= 25)({{ number_format($ppTotal, 0, ',', '.') }})@endif <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>@endif
            </div>
            @if($ppN > 0)
                <ul class="pp-letters" role="list">
                    @foreach($ppLetters as $ppCo)
                        <li>@include('partials.posta-pulu.card', ['company' => $ppCo])</li>
                    @endforeach
                    <li class="pp-letters__cta">
                        <div class="pp-blank">
                            <span class="pp-blank__slot" aria-hidden="true">Pul</span>
                            <h3>Bu zarfta sizin firmanız olabilir</h3>
                            <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                            <a class="pp-btn pp-btn--red" href="{{ route('owner.register') }}">Pulunuzu yapıştırın</a>
                        </div>
                    </li>
                </ul>
            @else
                <div class="pp-blank pp-blank--empty">
                    <span class="pp-blank__slot" aria-hidden="true">Pul</span>
                    <h3>Henüz mektup gelmedi</h3>
                    <p>Bu kutuya henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, ilk mektup sizden gelsin.</p>
                    <a class="pp-btn pp-btn--red" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Hizmetler: pul tabakası (üst menü #hizmetler bağlantısı için bölüm her zaman basılır) --}}
    <section id="hizmetler" class="pp-section pp-section--alt">
        <div class="pp-wrap">
            <div class="pp-head">
                <div>
                    <p class="pp-kicker">Hizmetler</p>
                    <h2 class="pp-h2">Pul tabakası</h2>
                    <p class="pp-sub">Bir pula dokunun, o hizmetteki firmalar listelensin.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <div class="pp-sheet">
                    <ul class="pp-sheet__grid" role="list" style="--pp-cols: {{ $ppSheetCols }}">
                        @foreach($categories as $ppCat)
                            @php $ppCatN = (int) ($ppCat->companies_count ?? 0); @endphp
                            <li>
                                <a class="pp-perf pp-mini pp-tone--{{ ['green', 'ochre', 'blue', 'red'][$loop->index % 4] }}" href="{{ route('categories.show', $ppCat->slug) }}" aria-label="{{ $ppCatN > 0 ? $ppCat->name.', '.$ppCatN.' firma' : $ppCat->name }}">
                                    <span class="pp-mini__in">
                                        @if($ppCatN > 0)
                                            <span class="pp-mini__den"><b>{{ number_format($ppCatN, 0, ',', '.') }}</b> firma</span>
                                        @else
                                            <span class="pp-mini__den pp-mini__den--go" aria-hidden="true">›</span>
                                        @endif
                                        <span class="pp-mini__name">{{ $ppCat->name }}</span>
                                    </span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>
                <p class="pp-after"><a class="pp-link" href="{{ route('owner.register') }}">Hizmetiniz yok mu? Firmanızı ekleyin <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a></p>
            @else
                <p class="pp-empty-line">Pul tabakası firmalar eklendikçe dolacak. <a class="pp-link" href="{{ route('owner.register') }}">Firmanızı ekleyin</a></p>
            @endif
        </div>
    </section>

    {{-- Şehirler: damgalar (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="pp-section">
        <div class="pp-wrap"><div class="pp-cityblock{{ $ppCityCount > 0 && $ppCityCount < 4 ? ' pp-cityrow' : '' }}">
            <div class="pp-head">
                <div>
                    <p class="pp-kicker">Şehirler</p>
                    <h2 class="pp-h2">Şehir damgaları</h2>
                    <p class="pp-sub">Bir damgaya dokunun, o şehirdeki firmalar listelensin.</p>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <ul class="pp-marks{{ $ppCityCount < 4 ? ' pp-marks--few' : '' }}" role="list" style="--pp-cols: {{ $ppCityCols }}">
                    @foreach($cities as $ppCity)
                        @php $ppCityN = (int) ($ppCity->companies_count ?? 0); @endphp
                        <li>
                            <a class="pp-mark pp-mark--{{ ['ink', 'red', 'blue'][$loop->index % 3] }}" href="{{ route('cities.show', $ppCity->slug) }}" aria-label="{{ $ppCityN > 0 ? $ppCity->name.', '.$ppCityN.' firma' : $ppCity->name }}">
                                @include('partials.posta-pulu.postmark', ['id' => 'pp-pm-c'.$loop->index, 'top' => $ppCity->name, 'mid' => $ppNow->format('d.m.y'), 'bottom' => $ppCityN > 0 ? number_format($ppCityN, 0, ',', '.').' firma' : ''])
                                <span class="pp-mark__name">{{ $ppCity->name }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="pp-empty-line">Şehir damgaları firmalar eklendikçe basılacak. <a class="pp-link" href="{{ route('companies.index') }}">Tüm firmalar</a></p>
            @endif
        </div></div>
    </section>

    {{-- Kartpostallar --}}
    @if($posts->isNotEmpty())
        <section class="pp-section pp-section--alt">
            <div class="pp-wrap">
                <div class="pp-head">
                    <div>
                        <p class="pp-kicker">Yazılar</p>
                        <h2 class="pp-h2">Kartpostallar</h2>
                    </div>
                    <a class="pp-link" href="{{ route('blog.index') }}">Tüm yazılar <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                </div>
                <ul class="pp-cards pp-cards--{{ $posts->count() % 3 === 0 ? '3' : '2' }}" role="list">
                    @foreach($posts as $ppPost)
                        <li>
                            <article class="pp-card">
                                <div class="pp-card__msg">
                                    <h3><a href="{{ route('blog.show', $ppPost->slug) }}">{{ $ppPost->title }}</a></h3>
                                    <p>{{ \Illuminate\Support\Str::limit($ppPost->excerpt ?: strip_tags((string) $ppPost->content), 140) }}</p>
                                    <a class="pp-link" href="{{ route('blog.show', $ppPost->slug) }}" aria-label="{{ $ppPost->title }}: yazıyı oku">Yazıyı oku <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                                </div>
                                <div class="pp-card__addr" aria-hidden="true">
                                    <span class="pp-card__slot">Pul</span>
                                    <span class="pp-card__line"><span class="pp-hand">Sevgili okur,</span></span>
                                    <span class="pp-card__line">@if($ppPost->published_at)<span class="pp-hand">{{ $ppPost->published_at->copy()->timezone('Europe/Istanbul')->locale('tr')->isoFormat('D MMMM YYYY') }}</span>@endif</span>
                                    <span class="pp-card__line"></span>
                                </div>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="pp-section">
            <div class="pp-wrap">
                <div class="pp-head">
                    <div>
                        <p class="pp-kicker">Kariyer</p>
                        <h2 class="pp-h2">İş ilanları</h2>
                    </div>
                    <a class="pp-link" href="{{ route('jobs.index') }}">Tüm ilanlar <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                </div>
                <ul class="pp-jobs" role="list">
                    @foreach($homeJobs as $ppJob)
                        <li>
                            <a class="pp-job" href="{{ route('jobs.show', $ppJob->slug) }}">
                                <span class="pp-job__type">{{ match($ppJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="pp-job__title">{{ $ppJob->title }}</span>
                                <span class="pp-job__meta">{{ collect([$ppJob->company->name ?? null, $ppJob->location ?: ($ppJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Pulunuzu yapıştırın --}}
    <section id="pulunuz" class="pp-section pp-cta-sec">
        <div class="pp-wrap">
            <div class="pp-cta pp-airmail">
                <div class="pp-cta__in">
                    <div class="pp-cta__txt">
                        <p class="pp-kicker">Firma sahipleri için</p>
                        <h2 class="pp-h2">Burada sizin pulunuz olabilir</h2>
                        <p>İşletmenizi ücretsiz ekleyin; pulunuzda telefon ve WhatsApp düğmesi dursun, müşteriler size doğrudan ulaşsın.</p>
                        <form class="pp-cta__form" action="{{ route('owner.register') }}" method="GET">
                            <label for="pp-firma"><span class="pp-hand">Gönderen:</span> firmanızın adı</label>
                            <div class="pp-cta__row">
                                <input id="pp-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                                <button class="pp-btn pp-btn--red pp-btn--lg" type="submit">Ücretsiz kayıt</button>
                            </div>
                        </form>
                        <p class="pp-cta__links">
                            <a class="pp-link" href="{{ route('owner.dashboard') }}">Firma paneli <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                            <a class="pp-link" href="{{ route('packages.index') }}">Öne çıkma seçenekleri <svg width="16" height="16" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                        </p>
                    </div>
                    <div class="pp-cta__corner" aria-hidden="true">
                        <div class="pp-slot pp-slot--cta">
                            <span class="pp-slot__t">Pul yeri</span>
                            <span class="pp-hand">pulunuzu buraya yapıştırın</span>
                        </div>
                        <svg class="pp-waves pp-waves--cta" width="220" height="64"><use href="#pp-ico-waves"/></svg>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="pp-dock"><div class="pp-dock__bar"><a class="pp-btn pp-btn--line" href="#pp-ara">Firma bul</a><a class="pp-btn pp-btn--red" href="{{ route('owner.register') }}">Pulunu yapıştır</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.pp-dock'),f=document.querySelector('.pp-env');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
