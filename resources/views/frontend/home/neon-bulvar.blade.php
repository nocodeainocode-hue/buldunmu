@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Şehrinizdeki işletmeleri bulun; firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $nbTotal = (int) ($totalCompanies ?? 0);
    $nbName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Öne çıkan + son eklenen firmalar, en fazla 8; vitrin sırasının sonuna her zaman "boş vitrin" kartı eklenir (9. yuva).
    $nbFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(8)->values();
    $nbSearchCities = $cities->where('slug', '!=', 'diger-iller');
    $nbTone = fn (string $slug): int => abs(crc32($slug)) % 6;
    $nbPlates = [
        ['nb-ico-phone', 'Doğrudan iletişim', 'Telefon ve WhatsApp düğmesi her vitrinde aynı yerde. Aracı yok, firmayla doğrudan konuşursunuz.'],
        ['nb-ico-clock', 'Güncel bilgi', 'Adres, çalışma saatleri ve hizmetler firma sahibinin panelinden güncellenir.'],
        ['nb-ico-plus', 'Ücretsiz kayıt', 'İşletmenizi ücretsiz ekleyin; müşteriler sizi hizmet ve şehir sayfalarında bulsun.'],
    ];
@endphp
<div class="nb-home">
    {{-- Gece göğü, üç katmanlı şehir silueti ve neon başlık; arama ışıklı vitrin camının içinde. --}}
    <section class="nb-hero" id="ara">
        @include('partials.neon-bulvar.skyline')
        <div class="nb-wrap nb-hero__in">
            <p class="nb-eyebrow"><span class="nb-tube nb-tube--cyan" aria-hidden="true"></span>{{ $nbName }}<span class="nb-tube nb-tube--cyan" aria-hidden="true"></span></p>
            <h1 class="nb-h1 nb-neon nb-neon--pink nb-flicker">{{ $settings->homepage_title ?: 'Şehrin ışıkları, firmanın kapısı' }}</h1>
            <p class="nb-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}</p>

            <form id="nb-ara" class="nb-search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                <div class="nb-field nb-field--q">
                    <label for="nb-q">Ne arıyorsunuz?</label>
                    <input id="nb-q" name="q" type="search" placeholder="Örn. kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                </div>
                <div class="nb-field nb-field--city">
                    <label for="nb-city">Şehir</label>
                    <select id="nb-city" name="city">
                        <option value="">Tüm şehirler</option>
                        @foreach($nbSearchCities as $nbCity)
                            <option value="{{ $nbCity->slug }}">{{ $nbCity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="nb-search__btn" type="submit">Bul</button>
            </form>

            @if($categories->isNotEmpty())
                <p class="nb-often"><span class="nb-often__k">Sık bakılan:</span>
                    @foreach($categories->take(4) as $nbCat)
                        <a class="nb-often__a nb-c{{ $nbTone($nbCat->slug) }}" href="{{ route('categories.show', $nbCat->slug) }}">{{ $nbCat->name }}</a>
                    @endforeach
                </p>
            @endif
            @if($nbTotal >= 25)
                <p class="nb-counter"><span class="nb-counter__n nb-neon nb-neon--yellow">{{ number_format($nbTotal, 0, ',', '.') }}</span> kayıtlı firma</p>
            @endif
        </div>
    </section>

    {{-- Bulvarın vitrinleri --}}
    <section id="firmalar" class="nb-section">
        <div class="nb-wrap">
            <div class="nb-head">
                <div>
                    <h2 class="nb-h2"><span class="nb-neon nb-neon--cyan">Bulvarın vitrinleri</span></h2>
                    <p>Numara her vitrinde aynı yerde: dokunun, arayın ya da WhatsApp'tan yazın.</p>
                </div>
                @if($nbFirms->isNotEmpty())<a class="nb-link" href="{{ route('companies.index') }}">Tüm firmalar @if($nbTotal >= 25)({{ number_format($nbTotal, 0, ',', '.') }})@endif <span aria-hidden="true">›</span></a>@endif
            </div>
            @if($nbFirms->isNotEmpty())
                <ul class="nb-vitrinler" role="list">
                    @foreach($nbFirms as $nbCo)
                        <li>@include('partials.neon-bulvar.card', ['company' => $nbCo])</li>
                    @endforeach
                    <li class="nb-vitrinler__cta">
                        <div class="nb-bos">
                            <span class="nb-bos__sign" aria-hidden="true">Kiralık vitrin</span>
                            <h3>Burada sizin firmanız olabilir</h3>
                            <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                            <div class="nb-bos__acts">
                                <a class="nb-btn nb-btn--yellow" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                                <a class="nb-link" href="{{ route('companies.index') }}">Tüm firmalar <span aria-hidden="true">›</span></a>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <div class="nb-empty">
                    <span class="nb-bos__sign" aria-hidden="true">Kiralık vitrin</span>
                    <h3>Bu bulvarda ilk neonu siz yakın</h3>
                    <p>Henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, müşteriler sizi buradan arasın.</p>
                    <a class="nb-btn nb-btn--yellow" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Neon tabelalar (üst menü #tabelalar bağlantısı için bölüm her zaman basılır) --}}
    <section id="tabelalar" class="nb-section nb-section--wall">
        <div class="nb-wrap">
            <div class="nb-head nb-head--center">
                <div>
                    <h2 class="nb-h2"><span class="nb-neon nb-neon--pink">Neon tabelalar</span></h2>
                    <p>Bir tabelaya dokunun, o alandaki işletmeler yansın.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <ul class="nb-tabelalar" role="list">
                    @foreach($categories as $nbCat)
                        @php $nbCatCount = (int) ($nbCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="nb-sign nb-c{{ $nbTone($nbCat->slug) }}" href="{{ route('categories.show', $nbCat->slug) }}" aria-label="{{ $nbCatCount > 0 ? $nbCat->name.', '.$nbCatCount.' firma' : $nbCat->name }}">
                                <span class="nb-sign__hook" aria-hidden="true"></span>
                                <span class="nb-sign__name nb-neon">{{ $nbCat->name }}</span>
                                @if($nbCatCount > 0)<span class="nb-sign__n">{{ number_format($nbCatCount, 0, ',', '.') }} firma</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
                <p class="nb-after"><a class="nb-link" href="{{ route('owner.register') }}">Hizmetiniz listede yok mu? Firmanızı ekleyin <span aria-hidden="true">›</span></a></p>
            @else
                <p class="nb-empty-line">Tabelalar firmalar eklendikçe yanacak. <a class="nb-link" href="{{ route('owner.register') }}">Firmanızı ekleyin <span aria-hidden="true">›</span></a></p>
            @endif
        </div>
    </section>

    {{-- Şehir durakları (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="nb-section nb-section--road">
        <div class="nb-wrap">
            <div class="nb-head nb-head--center">
                <div>
                    <h2 class="nb-h2"><span class="nb-neon nb-neon--yellow">Şehir durakları</span></h2>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <ul class="nb-cities" role="list">
                    @foreach($cities as $nbCity)
                        @php $nbCityN = (int) ($nbCity->companies_count ?? 0); @endphp
                        <li>
                            <a class="nb-city{{ $loop->index < 3 ? ' nb-city--big' : '' }}" href="{{ route('cities.show', $nbCity->slug) }}">
                                <span class="nb-city__dot" aria-hidden="true"></span>
                                <span class="nb-city__name">{{ $nbCity->name }}</span>
                                @if($nbCityN > 0)<small>{{ number_format($nbCityN, 0, ',', '.') }} firma</small>@endif
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="nb-empty-line">Şehirler firmalar eklendikçe burada yazılacak. <a class="nb-link" href="{{ route('companies.index') }}">Tüm firmalar <span aria-hidden="true">›</span></a></p>
            @endif
        </div>
    </section>

    {{-- Reklam panoları --}}
    <section class="nb-section">
        <div class="nb-wrap">
            <div class="nb-head nb-head--center">
                <div>
                    <h2 class="nb-h2"><span class="nb-neon nb-neon--cyan">Neden burada aramalı?</span></h2>
                </div>
            </div>
            <ul class="nb-panolar" role="list">
                @foreach($nbPlates as $nbPlate)
                    <li class="nb-pano nb-pano--{{ $loop->iteration }}">
                        <span class="nb-pano__ico" aria-hidden="true"><svg width="28" height="28"><use href="#{{ $nbPlate[0] }}"/></svg></span>
                        <h3>{{ $nbPlate[1] }}</h3>
                        <p>{{ $nbPlate[2] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Gece okumaları --}}
    @if($posts->isNotEmpty())
        <section class="nb-section nb-section--wall">
            <div class="nb-wrap">
                <div class="nb-head">
                    <div>
                        <h2 class="nb-h2"><span class="nb-neon nb-neon--pink">Gece okumaları</span></h2>
                        <p>Firma seçerken işinize yarayacak kısa yazılar.</p>
                    </div>
                    <a class="nb-link" href="{{ route('blog.index') }}">Tüm yazılar <span aria-hidden="true">›</span></a>
                </div>
                <ul class="nb-posts" role="list">
                    @foreach($posts as $nbPost)
                        <li>
                            <article class="nb-post nb-c{{ $loop->index % 6 }}">
                                <span class="nb-post__no" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                @if($nbPost->published_at)<time datetime="{{ $nbPost->published_at->toDateString() }}">{{ $nbPost->published_at->format('d.m.Y') }}</time>@endif
                                <h3><a href="{{ route('blog.show', $nbPost->slug) }}">{{ $nbPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($nbPost->excerpt ?: strip_tags((string) $nbPost->content), 140) }}</p>
                                <a class="nb-link" href="{{ route('blog.show', $nbPost->slug) }}" aria-label="{{ $nbPost->title }}: yazıyı oku">Oku <span aria-hidden="true">›</span></a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="nb-section">
            <div class="nb-wrap">
                <div class="nb-head">
                    <div>
                        <h2 class="nb-h2"><span class="nb-neon nb-neon--yellow">İş ilanları</span></h2>
                        <p>Bulvardaki firmalardan yeni ilanlar.</p>
                    </div>
                    <a class="nb-link" href="{{ route('jobs.index') }}">Tüm ilanlar <span aria-hidden="true">›</span></a>
                </div>
                <ul class="nb-jobs" role="list">
                    @foreach($homeJobs as $nbJob)
                        <li>
                            <a class="nb-job" href="{{ route('jobs.show', $nbJob->slug) }}">
                                <span class="nb-job__type">{{ match($nbJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="nb-job__title">{{ $nbJob->title }}</span>
                                <span class="nb-job__meta">{{ collect([$nbJob->company->name ?? null, $nbJob->location ?: ($nbJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Sönük tabela: forma odaklanınca yanar. --}}
    <section id="tabelaniz" class="nb-section nb-cta-sec">
        <div class="nb-wrap">
            <div class="nb-cta">
                <div class="nb-cta__sign" aria-hidden="true">
                    <span class="nb-cta__frame">
                        <span class="nb-cta__word nb-cta__word--1">Sizin</span>
                        <span class="nb-cta__word nb-cta__word--2">Firmanız</span>
                        <span class="nb-cta__open">AÇIK</span>
                    </span>
                </div>
                <div class="nb-cta__txt">
                    <h2 class="nb-h2">Bulvarda sizin tabelanız sönük</h2>
                    <p>İşletmenizi ücretsiz ekleyin; müşteriler telefonla ya da WhatsApp üzerinden size doğrudan ulaşsın.</p>
                    <form class="nb-cta__form" action="{{ route('owner.register') }}" method="GET">
                        <label for="nb-firma">Firmanızın adı</label>
                        <div class="nb-cta__row">
                            <input id="nb-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                            <button class="nb-btn nb-btn--yellow nb-btn--lg" type="submit">Tabelanızı yakın</button>
                        </div>
                    </form>
                    <p class="nb-cta__links">
                        <a class="nb-link" href="{{ route('owner.dashboard') }}">Firma paneli <span aria-hidden="true">›</span></a>
                        <a class="nb-link" href="{{ route('packages.index') }}">Öne çıkma seçenekleri <span aria-hidden="true">›</span></a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="nb-dock"><div class="nb-dock__bar"><a class="nb-btn nb-btn--line" href="#nb-ara">Ara</a><a class="nb-btn nb-btn--yellow" href="{{ route('owner.register') }}">Tabelanı yak</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.nb-dock'),f=document.getElementById('nb-ara');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
