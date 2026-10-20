@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Şehrinizdeki işletmeleri bulun; firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $boTotal = (int) ($totalCompanies ?? 0);
    $boName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Öne çıkan + son eklenen firmalar, en fazla 8; listenin sonuna her zaman "boş yer" kartı eklenir (9. yuva).
    $boFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(8)->values();
    $boSearchCities = $cities->where('slug', '!=', 'diger-iller');
    $boIndex = $categories->take(6);
    $boPlates = [
        ['bo-ico-phone', 'Doğrudan iletişim', 'Telefon ve WhatsApp düğmesi her kartta aynı yerde. Aracı yok, firmayla doğrudan konuşursunuz.'],
        ['bo-ico-clock', 'Güncel bilgi', 'Adres, çalışma saatleri ve hizmetler firma sahibinin panelinden güncellenir.'],
        ['bo-ico-plus', 'Ücretsiz kayıt', 'İşletmenizi ücretsiz ekleyin; müşteriler sizi hizmet ve şehir sayfalarında bulsun.'],
    ];
@endphp
<div class="bo-home">
    {{-- Üst bölüm: solda başlık ve arama, sağda hizmet dizini. --}}
    <section class="bo-hero" id="ara">
        <div class="bo-wrap bo-hero__grid">
            <div class="bo-hero__main">
                <p class="bo-eyebrow">{{ $boName }}</p>
                <h1 class="bo-h1">{{ $settings->homepage_title ?: 'Şehrinizdeki işletmelere doğrudan ulaşın' }}</h1>
                <p class="bo-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}</p>

                <form id="bo-ara" class="bo-search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                    <div class="bo-field bo-field--q">
                        <label for="bo-q">Ne arıyorsunuz?</label>
                        <input id="bo-q" name="q" type="search" placeholder="Örn. kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                    </div>
                    <div class="bo-field bo-field--city">
                        <label for="bo-city">Şehir</label>
                        <select id="bo-city" name="city">
                            <option value="">Tüm şehirler</option>
                            @foreach($boSearchCities as $boCity)
                                <option value="{{ $boCity->slug }}">{{ $boCity->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <button class="bo-search__btn" type="submit">Firma bul</button>
                </form>

                @if($categories->isNotEmpty())
                    <p class="bo-often"><span class="bo-often__k">Sık bakılan:</span>
                        @foreach($categories->take(4) as $boCat)
                            <a href="{{ route('categories.show', $boCat->slug) }}">{{ $boCat->name }}</a>
                        @endforeach
                    </p>
                @endif
            </div>
            <aside class="bo-hero__side" aria-label="Hizmet dizini">
                <div class="bo-index">
                    <p class="bo-index__title">Hizmet dizini</p>
                    @if($boIndex->isNotEmpty())
                        <ol class="bo-index__list">
                            @foreach($boIndex as $boCat)
                                @php $boCatCount = (int) ($boCat->companies_count ?? 0); @endphp
                                <li><a href="{{ route('categories.show', $boCat->slug) }}"><span class="bo-index__no">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="bo-index__name">{{ $boCat->name }}</span>@if($boCatCount > 0)<span class="bo-index__n">{{ number_format($boCatCount, 0, ',', '.') }}</span>@endif</a></li>
                            @endforeach
                        </ol>
                        <a class="bo-index__all" href="{{ route('home') }}#hizmetler">Tüm hizmet alanları <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                    @else
                        <p class="bo-index__empty">Hizmet alanları firmalar eklendikçe burada listelenecek.</p>
                        <a class="bo-index__all" href="{{ route('owner.register') }}">Firmanızı ekleyin <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                    @endif
                    @if($boTotal >= 25)
                        <p class="bo-index__total"><strong>{{ number_format($boTotal, 0, ',', '.') }}</strong> kayıtlı firma</p>
                    @endif
                </div>
            </aside>
        </div>
    </section>

    {{-- Firmalar --}}
    <section id="firmalar" class="bo-section">
        <div class="bo-wrap">
            <div class="bo-head">
                <div>
                    <p class="bo-kicker">Firmalar</p>
                    <h2 class="bo-h2">Rehberdeki işletmeler</h2>
                </div>
                @if($boFirms->isNotEmpty())<a class="bo-link" href="{{ route('companies.index') }}">Tüm firmalar @if($boTotal >= 25)({{ number_format($boTotal, 0, ',', '.') }})@endif <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>@endif
            </div>
            @if($boFirms->isNotEmpty())
                <ul class="bo-cards" role="list">
                    @foreach($boFirms as $boCo)
                        <li>@include('partials.beyaz-ofis.card', ['company' => $boCo])</li>
                    @endforeach
                    <li class="bo-cards__cta">
                        <div class="bo-slot">
                            <span class="bo-slot__tag">Boş yer</span>
                            <h3>Burada sizin firmanız olabilir</h3>
                            <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                            <div class="bo-slot__acts">
                                <a class="bo-btn bo-btn--navy" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                                <a class="bo-link" href="{{ route('companies.index') }}">Tüm firmalar <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <div class="bo-empty">
                    <span class="bo-slot__tag">Boş yer</span>
                    <h3>İlk kaydı siz oluşturun</h3>
                    <p>Henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, müşteriler sizi buradan arasın.</p>
                    <a class="bo-btn bo-btn--navy" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Hizmet alanları (üst menü #hizmetler bağlantısı için bölüm her zaman basılır) --}}
    <section id="hizmetler" class="bo-section bo-section--tint">
        <div class="bo-wrap">
            <div class="bo-head">
                <div>
                    <p class="bo-kicker">Hizmetler</p>
                    <h2 class="bo-h2">Hizmet alanları</h2>
                </div>
                <a class="bo-link" href="{{ route('owner.register') }}">Hizmetiniz listede yok mu? Firmanızı ekleyin <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
            </div>
            @if($categories->isNotEmpty())
                <ul class="bo-services" role="list">
                    @foreach($categories as $boCat)
                        @php $boCatCount = (int) ($boCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="bo-service" href="{{ route('categories.show', $boCat->slug) }}" aria-label="{{ $boCatCount > 0 ? $boCat->name.', '.$boCatCount.' firma' : $boCat->name }}">
                                <span class="bo-service__no">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="bo-service__name">{{ $boCat->name }}</span>
                                @if($boCatCount > 0)<span class="bo-service__n">{{ number_format($boCatCount, 0, ',', '.') }} firma</span>@endif
                                <svg class="bo-service__arrow" width="18" height="18" aria-hidden="true"><use href="#bo-ico-arrow"/></svg>
                            </a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="bo-empty-line">Hizmet alanları firmalar eklendikçe listelenecek. <a class="bo-link" href="{{ route('owner.register') }}">Firmanızı ekleyin</a></p>
            @endif
        </div>
    </section>

    {{-- Şehirler (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="bo-section bo-section--rule">
        <div class="bo-wrap">
            <div class="bo-head">
                <div>
                    <p class="bo-kicker">Şehirler</p>
                    <h2 class="bo-h2">Şehre göre firmalar</h2>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <ul class="bo-cities" role="list">
                    @foreach($cities as $boCity)
                        @php $boCityN = (int) ($boCity->companies_count ?? 0); @endphp
                        <li><a class="bo-city" href="{{ route('cities.show', $boCity->slug) }}"><span>{{ $boCity->name }}</span>@if($boCityN > 0)<small>{{ number_format($boCityN, 0, ',', '.') }} firma</small>@endif</a></li>
                    @endforeach
                </ul>
            @else
                <p class="bo-empty-line">Şehirler firmalar eklendikçe burada listelenecek. <a class="bo-link" href="{{ route('companies.index') }}">Tüm firmalar</a></p>
            @endif
        </div>
    </section>

    {{-- Neden --}}
    <section class="bo-section">
        <div class="bo-wrap">
            <div class="bo-head">
                <div>
                    <p class="bo-kicker">Neden</p>
                    <h2 class="bo-h2">Neden burada aramalı?</h2>
                </div>
            </div>
            <ul class="bo-why" role="list">
                @foreach($boPlates as $boPlate)
                    <li class="bo-why__item">
                        <span class="bo-why__ico" aria-hidden="true"><svg width="22" height="22"><use href="#{{ $boPlate[0] }}"/></svg></span>
                        <h3>{{ $boPlate[1] }}</h3>
                        <p>{{ $boPlate[2] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="bo-section bo-section--tint">
            <div class="bo-wrap">
                <div class="bo-head">
                    <div>
                        <p class="bo-kicker">Blog</p>
                        <h2 class="bo-h2">Rehber yazıları</h2>
                    </div>
                    <a class="bo-link" href="{{ route('blog.index') }}">Tüm yazılar <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                </div>
                <ul class="bo-posts" role="list">
                    @foreach($posts as $boPost)
                        <li>
                            <article class="bo-post">
                                @if($boPost->published_at)<time datetime="{{ $boPost->published_at->toDateString() }}">{{ $boPost->published_at->format('d.m.Y') }}</time>@endif
                                <h3><a href="{{ route('blog.show', $boPost->slug) }}">{{ $boPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($boPost->excerpt ?: strip_tags((string) $boPost->content), 140) }}</p>
                                <a class="bo-link" href="{{ route('blog.show', $boPost->slug) }}" aria-label="{{ $boPost->title }}: yazıyı oku">Oku <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="bo-section">
            <div class="bo-wrap">
                <div class="bo-head">
                    <div>
                        <p class="bo-kicker">Kariyer</p>
                        <h2 class="bo-h2">İş ilanları</h2>
                    </div>
                    <a class="bo-link" href="{{ route('jobs.index') }}">Tüm ilanlar <svg width="16" height="16" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
                </div>
                <ul class="bo-jobs" role="list">
                    @foreach($homeJobs as $boJob)
                        <li>
                            <a class="bo-job" href="{{ route('jobs.show', $boJob->slug) }}">
                                <span class="bo-job__type">{{ match($boJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="bo-job__title">{{ $boJob->title }}</span>
                                <span class="bo-job__meta">{{ collect([$boJob->company->name ?? null, $boJob->location ?: ($boJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Kayıt çağrısı --}}
    <section id="kayit" class="bo-section bo-cta-sec">
        <div class="bo-wrap">
            <div class="bo-cta">
                <div class="bo-cta__txt">
                    <p class="bo-kicker bo-kicker--light">Firma sahipleri için</p>
                    <h2>Firmanızı rehbere ekleyin</h2>
                    <p>Ücretsiz kayıt; müşteriler telefonla ya da WhatsApp üzerinden size doğrudan ulaşsın.</p>
                </div>
                <form class="bo-cta__form" action="{{ route('owner.register') }}" method="GET">
                    <label for="bo-firma">Firmanızın adı</label>
                    <div class="bo-cta__row">
                        <input id="bo-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                        <button class="bo-btn bo-btn--white bo-btn--lg" type="submit">Ücretsiz kayıt</button>
                    </div>
                    <p class="bo-cta__links">
                        <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
                        <a href="{{ route('packages.index') }}">Öne çıkma seçenekleri</a>
                    </p>
                </form>
            </div>
        </div>
    </section>

    <div class="bo-dock"><div class="bo-dock__bar"><a class="bo-btn bo-btn--line" href="#bo-ara">Firma ara</a><a class="bo-btn bo-btn--navy" href="{{ route('owner.register') }}">Firma ekle</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.bo-dock'),f=document.getElementById('bo-ara');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
