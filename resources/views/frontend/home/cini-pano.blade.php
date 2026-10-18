@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Şehrinizdeki işletmeleri bulun; firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $cnTotal = (int) ($totalCompanies ?? 0);
    $cnName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Öne çıkan + son eklenen firmalar, en fazla 8; ızgaranın sonuna her zaman "eksik karo" kartı eklenir (9. yuva).
    $cnFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(8)->values();
    $cnSearchCities = $cities->where('slug', '!=', 'diger-iller');
    // Karo paneli: son sıra, süs karolarıyla her sütun sayısında (2/3/4) tamamlanır; gül motifleri yarım kalmaz.
    $cnCatN = $categories->count();
    $cnFill = [];
    foreach ([2, 3, 4] as $cnC) { $cnFill[$cnC] = ($cnC - $cnCatN % $cnC) % $cnC; }
    $cnFillMax = $cnCatN > 0 ? max($cnFill) : 0;
    $cnPlates = [
        ['Doğrudan iletişim', 'Telefon ve WhatsApp düğmesi firmanın kartında. Aracı yok, firmayla doğrudan konuşursunuz.', 'cn-lale', 'cn-niche--1'],
        ['Güncel bilgi', 'Adres, çalışma saatleri ve hizmetler firma sahibinin panelinden güncellenir.', 'cn-karanfil', 'cn-niche--2'],
        ['Ücretsiz kayıt', 'İşletmenizi ücretsiz ekleyin; müşteriler sizi hizmet ve şehir sayfalarında bulsun.', 'cn-saz', 'cn-niche--3'],
    ];
@endphp
<div class="cn-home">
    {{-- Şemse: krem çini duvarın ortasında madalyon; arama madalyonun içinde. --}}
    <section class="cn-hero" id="ara">
        <div class="cn-wrap">
            <div class="cn-semse">
                <svg class="cn-semse__band" aria-hidden="true" focusable="false"><rect class="cn-b1" width="100%" height="100%"/><rect class="cn-b2" width="100%" height="100%"/><rect class="cn-b3" width="100%" height="100%"/><rect class="cn-b4" width="100%" height="100%"/></svg>
                <svg class="cn-salbek cn-salbek--l" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><use href="#cn-lale"/></svg>
                <svg class="cn-salbek cn-salbek--r" viewBox="0 0 40 56" aria-hidden="true" focusable="false"><use href="#cn-lale"/></svg>
                <div class="cn-semse__in">
                    <p class="cn-eyebrow"><span class="cn-star" aria-hidden="true"></span>{{ $cnName }}<span class="cn-star" aria-hidden="true"></span></p>
                    <h1 class="cn-h1">{{ $settings->homepage_title ?: 'Şehrinizdeki işletmeler, tek bakışta' }}</h1>
                    <p class="cn-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}</p>

                    <div class="cn-search-wrap">
                        <form id="cn-ara" class="cn-search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                            <div class="cn-field cn-field--q">
                                <label for="cn-q">Ne arıyorsunuz?</label>
                                <input id="cn-q" name="q" type="search" placeholder="Örn. kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                            </div>
                            <div class="cn-field cn-field--city">
                                <label for="cn-city">Şehir</label>
                                <select id="cn-city" name="city">
                                    <option value="">Tüm şehirler</option>
                                    @foreach($cnSearchCities as $cnCity)
                                        <option value="{{ $cnCity->slug }}">{{ $cnCity->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button class="cn-search__btn" type="submit">Bul</button>
                        </form>
                    </div>

                    @if($categories->isNotEmpty())
                        <p class="cn-often"><span class="cn-often__k">Sık bakılan:</span>
                            @foreach($categories->take(4) as $cnCat)
                                @if(! $loop->first)<span class="cn-loz" aria-hidden="true"></span>@endif<a href="{{ route('categories.show', $cnCat->slug) }}">{{ $cnCat->name }}</a>
                            @endforeach
                        </p>
                    @endif
                </div>
            </div>
            @if($cnTotal >= 25)
                <p class="cn-kitabe"><span class="cn-star" aria-hidden="true"></span>{{ number_format($cnTotal, 0, ',', '.') }} kayıtlı firma<span class="cn-star" aria-hidden="true"></span></p>
            @endif
        </div>
    </section>

    {{-- Panodaki işletmeler --}}
    <section id="firmalar" class="cn-section">
        <div class="cn-wrap">
            <div class="cn-head">
                <div>
                    <h2 class="cn-kartus">Panodaki işletmeler</h2>
                    <p>Numara her kartta aynı yerde: dokunun, arayın ya da WhatsApp'tan yazın.</p>
                </div>
                @if($cnFirms->isNotEmpty())<a class="cn-link" href="{{ route('companies.index') }}">Tüm firmalar @if($cnTotal >= 25)({{ number_format($cnTotal, 0, ',', '.') }})@endif<span class="cn-loz" aria-hidden="true"></span></a>@endif
            </div>
            @if($cnFirms->isNotEmpty())
                <ul class="cn-kartlar" role="list">
                    @foreach($cnFirms as $cnCo)
                        <li>@include('partials.cini-pano.card', ['company' => $cnCo])</li>
                    @endforeach
                    <li class="cn-kartlar__cta">
                        <div class="cn-eksik">
                            <span class="cn-socket" aria-hidden="true"><span>+</span></span>
                            <div class="cn-eksik__txt">
                                <h3>Burada sizin firmanız olabilir</h3>
                                <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                                <div class="cn-eksik__acts">
                                    <a class="cn-btn cn-btn--coral" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                                    <a class="cn-link" href="{{ route('companies.index') }}">Tüm firmalar<span class="cn-loz" aria-hidden="true"></span></a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <div class="cn-empty">
                    <span class="cn-socket" aria-hidden="true"><span>+</span></span>
                    <div>
                        <h3>Bu panoya ilk karoyu siz koyun</h3>
                        <p>Henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, müşteriler sizi buradan arasın.</p>
                        <a class="cn-btn cn-btn--coral" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                    </div>
                </div>
            @endif
        </div>
    </section>

    {{-- Hizmet karoları (üst menü #karolar bağlantısı için bölüm her zaman basılır) --}}
    <section id="karolar" class="cn-section cn-section--tint">
        <div class="cn-wrap">
            <div class="cn-head cn-head--center">
                <div>
                    <h2 class="cn-kartus">Hizmet karoları</h2>
                    <p>Bir karoya dokunun, o alandaki işletmeler açılsın.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <div class="cn-panel">
                    <ul class="cn-karolar" role="list">
                        @foreach($categories as $cnCat)
                            @php $cnCatCount = (int) ($cnCat->companies_count ?? 0); @endphp
                            <li>
                                <a class="cn-karo" href="{{ route('categories.show', $cnCat->slug) }}" aria-label="{{ $cnCatCount > 0 ? $cnCat->name.', '.$cnCatCount.' firma' : $cnCat->name }}">
                                    @include('partials.cini-pano.motif')
                                    <span class="cn-karo__txt">
                                        <span class="cn-karo__name">{{ $cnCat->name }}</span>
                                        @if($cnCatCount > 0)<span class="cn-karo__n">{{ number_format($cnCatCount, 0, ',', '.') }} firma</span>@endif
                                    </span>
                                </a>
                            </li>
                        @endforeach
                        @for($cnI = 0; $cnI < $cnFillMax; $cnI++)
                            <li class="cn-dolgu{{ $cnI < $cnFill[2] ? ' cn-dolgu--c2' : '' }}{{ $cnI < $cnFill[3] ? ' cn-dolgu--c3' : '' }}{{ $cnI < $cnFill[4] ? ' cn-dolgu--c4' : '' }}" aria-hidden="true">
                                <span class="cn-karo cn-karo--dolgu">
                                    @include('partials.cini-pano.motif')
                                    <svg class="cn-karo__center" viewBox="0 0 40 56" focusable="false"><use href="#cn-karanfil"/></svg>
                                </span>
                            </li>
                        @endfor
                    </ul>
                </div>
                <p class="cn-after"><a class="cn-link" href="{{ route('owner.register') }}">Hizmetiniz listede yok mu? Firmanızı ekleyin<span class="cn-loz" aria-hidden="true"></span></a></p>
            @else
                <p class="cn-empty-line">Hizmet karoları firmalar eklendikçe dizilecek. <a class="cn-link" href="{{ route('owner.register') }}">Firmanızı ekleyin<span class="cn-loz" aria-hidden="true"></span></a></p>
            @endif
        </div>
    </section>

    {{-- Şehir kitabesi (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="cn-band">
        <div class="cn-wrap">
            <h2 class="cn-kartus cn-kartus--light">Şehirler</h2>
            @if($cities->isNotEmpty())
                <ul class="cn-cities" role="list">
                    @foreach($cities as $cnCity)
                        @php $cnCityN = (int) ($cnCity->companies_count ?? 0); @endphp
                        <li>
                            @if(! $loop->first)<span class="cn-star cn-star--light" aria-hidden="true"></span>@endif
                            <a class="{{ $loop->index < 3 ? 'cn-city cn-city--big' : 'cn-city' }}" href="{{ route('cities.show', $cnCity->slug) }}">{{ $cnCity->name }}@if($cnCityN > 0)<small>{{ number_format($cnCityN, 0, ',', '.') }} firma</small>@endif</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="cn-band__empty">Şehirler firmalar eklendikçe burada yazılacak. <a href="{{ route('companies.index') }}">Tüm firmalar ›</a></p>
            @endif
        </div>
    </section>

    {{-- Kemer nişleri --}}
    <section class="cn-section">
        <div class="cn-wrap">
            <div class="cn-head cn-head--center">
                <div>
                    <h2 class="cn-kartus">Neden burada aramalı?</h2>
                </div>
            </div>
            <ul class="cn-niches" role="list">
                @foreach($cnPlates as $cnPlate)
                    <li class="cn-niche {{ $cnPlate[3] }}">
                        <svg class="cn-niche__ico" viewBox="{{ $cnPlate[2] === 'cn-saz' ? '0 0 64 28' : '0 0 40 56' }}" aria-hidden="true" focusable="false"><use href="#{{ $cnPlate[2] }}"/></svg>
                        <h3>{{ $cnPlate[0] }}</h3>
                        <p>{{ $cnPlate[1] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Okuma köşesi --}}
    @if($posts->isNotEmpty())
        <section class="cn-section cn-section--tint">
            <div class="cn-wrap">
                <div class="cn-head">
                    <div>
                        <h2 class="cn-kartus">Okuma köşesi</h2>
                        <p>Firma seçerken işinize yarayacak kısa yazılar.</p>
                    </div>
                    <a class="cn-link" href="{{ route('blog.index') }}">Tüm yazılar<span class="cn-loz" aria-hidden="true"></span></a>
                </div>
                <ul class="cn-posts" role="list">
                    @foreach($posts as $cnPost)
                        <li>
                            <article class="cn-post cn-post--{{ $loop->index % 3 + 1 }}">
                                <div class="cn-post__band" aria-hidden="true"><svg viewBox="0 0 40 56" focusable="false"><use href="#{{ ['cn-lale', 'cn-karanfil', 'cn-lale'][$loop->index % 3] }}"/></svg></div>
                                @if($cnPost->published_at)<time datetime="{{ $cnPost->published_at->toDateString() }}">{{ $cnPost->published_at->format('d.m.Y') }}</time>@endif
                                <h3><a href="{{ route('blog.show', $cnPost->slug) }}">{{ $cnPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($cnPost->excerpt ?: strip_tags((string) $cnPost->content), 140) }}</p>
                                <a class="cn-link" href="{{ route('blog.show', $cnPost->slug) }}" aria-label="{{ $cnPost->title }}: yazıyı oku">Oku<span class="cn-loz" aria-hidden="true"></span></a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="cn-section">
            <div class="cn-wrap">
                <div class="cn-head">
                    <div>
                        <h2 class="cn-kartus">İş ilanları</h2>
                        <p>Panodaki firmalardan yeni ilanlar.</p>
                    </div>
                    <a class="cn-link" href="{{ route('jobs.index') }}">Tüm ilanlar<span class="cn-loz" aria-hidden="true"></span></a>
                </div>
                <ul class="cn-jobs" role="list">
                    @foreach($homeJobs as $cnJob)
                        <li>
                            <a class="cn-job" href="{{ route('jobs.show', $cnJob->slug) }}">
                                <span class="cn-job__type">{{ match($cnJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="cn-job__title">{{ $cnJob->title }}</span>
                                <span class="cn-job__meta">{{ collect([$cnJob->company->name ?? null, $cnJob->location ?: ($cnJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Eksik karo --}}
    <section id="karonuz" class="cn-section cn-cta-sec">
        <div class="cn-wrap">
            <div class="cn-cta">
                <div class="cn-mosaic" aria-hidden="true">
                    @for($cnM = 0; $cnM < 6; $cnM++)
                        @if($cnM === 4)
                            <span class="cn-mosaic__gap"><span>+</span></span>
                        @else
                            <span class="cn-karo cn-karo--mini">@include('partials.cini-pano.motif')</span>
                        @endif
                    @endfor
                </div>
                <div class="cn-cta__txt">
                    <h2>Panoda sizin karonuz eksik</h2>
                    <p>İşletmenizi ücretsiz ekleyin; müşteriler telefonla ya da WhatsApp üzerinden size doğrudan ulaşsın.</p>
                    <form class="cn-cta__form" action="{{ route('owner.register') }}" method="GET">
                        <label for="cn-firma">Firmanızın adı</label>
                        <div class="cn-cta__row">
                            <input id="cn-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                            <button class="cn-btn cn-btn--coral cn-btn--lg" type="submit">Firmanızı ücretsiz ekleyin</button>
                        </div>
                    </form>
                    <p class="cn-cta__links">
                        <a class="cn-link" href="{{ route('owner.dashboard') }}">Firma paneli<span class="cn-loz" aria-hidden="true"></span></a>
                        <a class="cn-link" href="{{ route('packages.index') }}">Öne çıkma seçenekleri<span class="cn-loz" aria-hidden="true"></span></a>
                    </p>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection
