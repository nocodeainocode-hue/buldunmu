@extends('layouts.app')

@section('title', $homeSeo['title'] ?? ($directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi'))
@section('meta_description', ($settings->meta_description ?? null) ?: 'Şehrinizdeki işletmeleri bulun; firmayı telefonla ya da WhatsApp üzerinden doğrudan arayın. Firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $tlTotal = (int) ($totalCompanies ?? 0);
    $tlName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    // Öne çıkan + son eklenen firmalar, en fazla 8; levha sırasının sonuna her zaman "boş levha" eklenir (9. yuva).
    $tlFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(8)->values();
    $tlSearchCities = $cities->where('slug', '!=', 'diger-iller');
    $tlPlates = [
        ['tl-ico-phone', 'Doğrudan iletişim', 'Telefon ve WhatsApp düğmesi her levhada aynı yerde. Aracı yok, firmayla doğrudan konuşursunuz.'],
        ['tl-ico-clock', 'Güncel bilgi', 'Adres, çalışma saatleri ve hizmetler firma sahibinin panelinden güncellenir.'],
        ['tl-ico-plus', 'Ücretsiz kayıt', 'İşletmenizi ücretsiz ekleyin; müşteriler sizi hizmet ve şehir sayfalarında bulsun.'],
    ];
@endphp
<div class="tl-home">
    {{-- Lobi: ortalanmış serif başlık, bronz çizgiler, çerçeveli arama levhası. --}}
    <section class="tl-hero" id="ara">
        <div class="tl-wrap">
            <p class="tl-eyebrow"><span class="tl-rule" aria-hidden="true"></span>{{ $tlName }}<span class="tl-rule" aria-hidden="true"></span></p>
            <h1 class="tl-h1">{{ $settings->homepage_title ?: 'Şehrinizin işletmeleri, tek adreste' }}</h1>
            <p class="tl-lede">{{ $settings->homepage_subtitle ?: "Hizmeti yazın, şehrinizi seçin; firmayı telefonla ya da WhatsApp'tan doğrudan arayın." }}</p>

            <form id="tl-ara" class="tl-search" action="{{ route('search') }}" method="GET" role="search" aria-label="Firma ara">
                <div class="tl-field tl-field--q">
                    <label for="tl-q">Ne arıyorsunuz?</label>
                    <input id="tl-q" name="q" type="search" placeholder="Örn. kuaför, tesisatçı, firma adı" autocomplete="off" enterkeyhint="search">
                </div>
                <div class="tl-field tl-field--city">
                    <label for="tl-city">Şehir</label>
                    <select id="tl-city" name="city">
                        <option value="">Tüm şehirler</option>
                        @foreach($tlSearchCities as $tlCity)
                            <option value="{{ $tlCity->slug }}">{{ $tlCity->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="tl-search__btn" type="submit">Firma bul</button>
            </form>

            @if($categories->isNotEmpty())
                <p class="tl-often"><span class="tl-often__k">Sık bakılan</span>
                    @foreach($categories->take(4) as $tlCat)
                        @if(! $loop->first)<span class="tl-dia" aria-hidden="true"></span>@endif<a href="{{ route('categories.show', $tlCat->slug) }}">{{ $tlCat->name }}</a>
                    @endforeach
                </p>
            @endif
            @if($tlTotal >= 25)
                <p class="tl-counter"><span class="tl-rule" aria-hidden="true"></span><strong>{{ number_format($tlTotal, 0, ',', '.') }}</strong> kayıtlı firma<span class="tl-rule" aria-hidden="true"></span></p>
            @endif
        </div>
    </section>

    {{-- Lobi levhaları --}}
    <section id="firmalar" class="tl-section">
        <div class="tl-wrap">
            <div class="tl-head">
                <div>
                    <p class="tl-kicker">Firmalar</p>
                    <h2 class="tl-h2">Lobideki işletmeler</h2>
                </div>
                @if($tlFirms->isNotEmpty())<a class="tl-link" href="{{ route('companies.index') }}">Tüm firmalar @if($tlTotal >= 25)({{ number_format($tlTotal, 0, ',', '.') }})@endif <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>@endif
            </div>
            @if($tlFirms->isNotEmpty())
                <ul class="tl-plates" role="list">
                    @foreach($tlFirms as $tlCo)
                        <li>@include('partials.tas-lobi.card', ['company' => $tlCo])</li>
                    @endforeach
                    <li class="tl-plates__cta">
                        <div class="tl-blank">
                            <span class="tl-blank__tag">Boş levha</span>
                            <h3>Burada sizin firmanız olabilir</h3>
                            <p>İşletmenizi ücretsiz ekleyin; müşteriler sizi telefonla ya da WhatsApp'tan arasın.</p>
                            <div class="tl-blank__acts">
                                <a class="tl-btn tl-btn--ink" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                                <a class="tl-link" href="{{ route('companies.index') }}">Tüm firmalar <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                            </div>
                        </div>
                    </li>
                </ul>
            @else
                <div class="tl-empty">
                    <span class="tl-blank__tag">Boş levha</span>
                    <h3>Lobideki ilk levhayı siz asın</h3>
                    <p>Henüz firma eklenmedi. İşletmenizi ücretsiz ekleyin, müşteriler sizi buradan arasın.</p>
                    <a class="tl-btn tl-btn--ink" href="{{ route('owner.register') }}">Firmanızı ekleyin</a>
                </div>
            @endif
        </div>
    </section>

    {{-- Kat planı: hizmet alanları (üst menü #hizmetler bağlantısı için bölüm her zaman basılır) --}}
    <section id="hizmetler" class="tl-section tl-section--stone">
        <div class="tl-wrap">
            <div class="tl-head tl-head--center">
                <div>
                    <p class="tl-kicker">Hizmetler</p>
                    <h2 class="tl-h2">Kat planı</h2>
                    <p class="tl-sub">Bir hizmet alanı seçin, o alandaki işletmeler listelensin.</p>
                </div>
            </div>
            @if($categories->isNotEmpty())
                <ol class="tl-plan" role="list">
                    @foreach($categories as $tlCat)
                        @php $tlCatCount = (int) ($tlCat->companies_count ?? 0); @endphp
                        <li>
                            <a class="tl-plan__row" href="{{ route('categories.show', $tlCat->slug) }}" aria-label="{{ $tlCatCount > 0 ? $tlCat->name.', '.$tlCatCount.' firma' : $tlCat->name }}">
                                <span class="tl-plan__no">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                <span class="tl-plan__name">{{ $tlCat->name }}</span>
                                <span class="tl-plan__dots" aria-hidden="true"></span>
                                @if($tlCatCount > 0)<span class="tl-plan__n">{{ number_format($tlCatCount, 0, ',', '.') }} firma</span>@endif
                            </a>
                        </li>
                    @endforeach
                </ol>
                <p class="tl-after"><a class="tl-link" href="{{ route('owner.register') }}">Hizmetiniz listede yok mu? Firmanızı ekleyin <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a></p>
            @else
                <p class="tl-empty-line">Kat planı firmalar eklendikçe dolacak. <a class="tl-link" href="{{ route('owner.register') }}">Firmanızı ekleyin</a></p>
            @endif
        </div>
    </section>

    {{-- Şehirler (üst menü #sehirler bağlantısı için bölüm her zaman basılır) --}}
    <section id="sehirler" class="tl-section tl-section--rule">
        <div class="tl-wrap">
            <div class="tl-head tl-head--center">
                <div>
                    <p class="tl-kicker">Şehirler</p>
                    <h2 class="tl-h2">Şehre göre firmalar</h2>
                </div>
            </div>
            @if($cities->isNotEmpty())
                <ul class="tl-cities" role="list">
                    @foreach($cities as $tlCity)
                        @php $tlCityN = (int) ($tlCity->companies_count ?? 0); @endphp
                        <li>
                            @if(! $loop->first)<span class="tl-dia" aria-hidden="true"></span>@endif
                            <a class="tl-city{{ $loop->index < 3 ? ' tl-city--big' : '' }}" href="{{ route('cities.show', $tlCity->slug) }}">{{ $tlCity->name }}@if($tlCityN > 0)<small>{{ number_format($tlCityN, 0, ',', '.') }} firma</small>@endif</a>
                        </li>
                    @endforeach
                </ul>
            @else
                <p class="tl-empty-line">Şehirler firmalar eklendikçe burada yazılacak. <a class="tl-link" href="{{ route('companies.index') }}">Tüm firmalar</a></p>
            @endif
        </div>
    </section>

    {{-- Neden --}}
    <section class="tl-section">
        <div class="tl-wrap">
            <div class="tl-head tl-head--center">
                <div>
                    <p class="tl-kicker">Neden</p>
                    <h2 class="tl-h2">Neden burada aramalı?</h2>
                </div>
            </div>
            <ul class="tl-why" role="list">
                @foreach($tlPlates as $tlPlate)
                    <li class="tl-why__item">
                        <span class="tl-why__ico" aria-hidden="true"><svg width="24" height="24"><use href="#{{ $tlPlate[0] }}"/></svg></span>
                        <h3>{{ $tlPlate[1] }}</h3>
                        <p>{{ $tlPlate[2] }}</p>
                    </li>
                @endforeach
            </ul>
        </div>
    </section>

    {{-- Blog --}}
    @if($posts->isNotEmpty())
        <section class="tl-section tl-section--stone">
            <div class="tl-wrap">
                <div class="tl-head">
                    <div>
                        <p class="tl-kicker">Blog</p>
                        <h2 class="tl-h2">Rehber yazıları</h2>
                    </div>
                    <a class="tl-link" href="{{ route('blog.index') }}">Tüm yazılar <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                </div>
                <ul class="tl-posts" role="list">
                    @foreach($posts as $tlPost)
                        <li>
                            <article class="tl-post">
                                @if($tlPost->published_at)<time datetime="{{ $tlPost->published_at->toDateString() }}">{{ $tlPost->published_at->format('d.m.Y') }}</time>@endif
                                <h3><a href="{{ route('blog.show', $tlPost->slug) }}">{{ $tlPost->title }}</a></h3>
                                <p>{{ \Illuminate\Support\Str::limit($tlPost->excerpt ?: strip_tags((string) $tlPost->content), 150) }}</p>
                                <a class="tl-link" href="{{ route('blog.show', $tlPost->slug) }}" aria-label="{{ $tlPost->title }}: yazıyı oku">Oku <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                            </article>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- İş ilanları --}}
    @if(isset($homeJobs) && $homeJobs->isNotEmpty())
        <section class="tl-section">
            <div class="tl-wrap">
                <div class="tl-head">
                    <div>
                        <p class="tl-kicker">Kariyer</p>
                        <h2 class="tl-h2">İş ilanları</h2>
                    </div>
                    <a class="tl-link" href="{{ route('jobs.index') }}">Tüm ilanlar <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                </div>
                <ul class="tl-jobs" role="list">
                    @foreach($homeJobs as $tlJob)
                        <li>
                            <a class="tl-job" href="{{ route('jobs.show', $tlJob->slug) }}">
                                <span class="tl-job__type">{{ match($tlJob->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }}</span>
                                <span class="tl-job__title">{{ $tlJob->title }}</span>
                                <span class="tl-job__meta">{{ collect([$tlJob->company->name ?? null, $tlJob->location ?: ($tlJob->company->city->name ?? null)])->filter()->implode(' · ') }}</span>
                            </a>
                        </li>
                    @endforeach
                </ul>
            </div>
        </section>
    @endif

    {{-- Levhanızı asın --}}
    <section id="levhaniz" class="tl-section tl-cta-sec">
        <div class="tl-wrap">
            <div class="tl-cta">
                <div class="tl-cta__frame" aria-hidden="true">
                    <span class="tl-cta__plate"><span class="tl-cta__line"></span><span class="tl-cta__word">Firmanız</span><span class="tl-cta__line"></span></span>
                </div>
                <div class="tl-cta__txt">
                    <p class="tl-kicker">Firma sahipleri için</p>
                    <h2 class="tl-h2">Lobide levhanızı asın</h2>
                    <p>İşletmenizi ücretsiz ekleyin; müşteriler telefonla ya da WhatsApp üzerinden size doğrudan ulaşsın.</p>
                    <form class="tl-cta__form" action="{{ route('owner.register') }}" method="GET">
                        <label for="tl-firma">Firmanızın adı</label>
                        <div class="tl-cta__row">
                            <input id="tl-firma" name="company_name" maxlength="120" autocomplete="organization" placeholder="Firmanızın adını yazın">
                            <button class="tl-btn tl-btn--ink tl-btn--lg" type="submit">Ücretsiz kayıt</button>
                        </div>
                    </form>
                    <p class="tl-cta__links">
                        <a class="tl-link" href="{{ route('owner.dashboard') }}">Firma paneli <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                        <a class="tl-link" href="{{ route('packages.index') }}">Öne çıkma seçenekleri <svg width="16" height="16" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
                    </p>
                </div>
            </div>
        </div>
    </section>

    <div class="tl-dock"><div class="tl-dock__bar"><a class="tl-btn tl-btn--line" href="#tl-ara">Firma ara</a><a class="tl-btn tl-btn--ink" href="{{ route('owner.register') }}">Firma ekle</a></div></div>
</div>
<script>(()=>{const d=document.querySelector('.tl-dock'),f=document.getElementById('tl-ara');if(!d||!f)return;if(!('IntersectionObserver'in window)){d.classList.add('is-on');return;}new IntersectionObserver(([e])=>d.classList.toggle('is-on',!e.isIntersecting)).observe(f);})();</script>
@endsection
