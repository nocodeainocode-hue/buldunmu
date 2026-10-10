@extends('layouts.app')

@section('title', $directory->meta_title ?? ($directory->name ?? 'Firma Rehberi').' — Firma ve Hizmet Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki firmaları ve hizmetleri inceleyin. İşletme profillerine ulaşın, firmanızı ücretsiz ekleyin.')

@section('content')
@php
    $ledgerFirms = $premiumCompanies->concat($latestCompanies)->unique('id')->take(6);
@endphp
<div class="yl-home">
    <section class="yl-hero">
        <div class="yl-wrap yl-hero__grid">
            <div>
                <p class="yl-kicker">Yerel işletmeler, gerçek hizmetler</p>
                <h1>{{ $settings->homepage_title ?: 'İyi bir iş, doğru firmayla başlar.' }}</h1>
                <p class="yl-hero__lede">{{ $settings->homepage_subtitle ?: 'İhtiyacınız olan hizmeti bulun, şehrinizdeki işletmeleri inceleyin ve doğrudan iletişime geçin.' }}</p>
                <form class="yl-search" action="{{ route('search') }}" method="GET" role="search">
                    <div class="yl-search__field">
                        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><circle cx="10.5" cy="10.5" r="6.5" stroke-width="1.7"/><path d="m16 16 4 4" stroke-width="1.7" stroke-linecap="round"/></svg>
                        <label for="yl-q" class="sr-only">Firma, hizmet veya şehir ara</label>
                        <input id="yl-q" name="q" type="search" placeholder="Firma, hizmet veya şehir…" autocomplete="off">
                    </div>
                    <button class="yl-button" type="submit">Ara <span aria-hidden="true">→</span></button>
                </form>
                @if($categories->isNotEmpty())
                    <div class="yl-popular"><span>Hizmetlere göz at:</span>@foreach($categories->take(3) as $category)<a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>@endforeach</div>
                @endif
                <a class="yl-hero__business" href="{{ route('owner.register') }}">İşletmeniz de burada yer alsın. Ücretsiz ekleyin <span aria-hidden="true">↗</span></a>
            </div>
            <aside class="yl-city-panel" aria-label="Şehir rehberleri">
                <div class="yl-city-panel__head">
                    <div><span class="yl-city-panel__label">Şehir rehberi</span><h2>Şehrinden başla.</h2></div>
                    <span class="yl-city-panel__symbol" aria-hidden="true"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor"><path d="M19 10c0 5-7 10-7 10S5 15 5 10a7 7 0 1 1 14 0Z" stroke-width="1.5"/><circle cx="12" cy="10" r="2.5" stroke-width="1.5"/></svg></span>
                </div>
                <div class="yl-city-panel__cities">
                    @forelse($cities->take(5) as $city)
                        <a class="yl-city-row" href="{{ route('cities.show', $city->slug) }}"><span class="yl-city-row__no">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="yl-city-row__name">{{ $city->name }}</span><span class="yl-city-row__count">{{ number_format($city->companies_count, 0, ',', '.') }} firma</span><span class="yl-city-row__arrow" aria-hidden="true">↗</span></a>
                    @empty
                        <p class="yl-empty">Şehir rehberleri, firmalar eklendikçe burada yerini alacak.</p>
                    @endforelse
                </div>
                <p class="yl-city-panel__foot">Konumunuza göre işletme profillerini inceleyin.<br><a class="yl-link" href="#sehirler">Diğer şehirlere göz at <span aria-hidden="true">→</span></a></p>
            </aside>
        </div>
    </section>

    <section class="yl-section" id="hizmetler">
        <div class="yl-wrap">
            <div class="yl-section__head"><div><p class="yl-section__label">Hizmet dizini</p><h2>Ne arıyorsunuz?</h2><p class="yl-section__intro">Günlük ihtiyaçlardan profesyonel hizmetlere, işe kategoriden başlayın.</p></div><a class="yl-link" href="{{ route('companies.index') }}">Tüm firmalar <span aria-hidden="true">→</span></a></div>
            <div class="yl-categories">
                @forelse($categories as $category)
                    <a class="yl-category" href="{{ route('categories.show', $category->slug) }}"><span class="yl-category__icon" aria-hidden="true">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span><strong class="yl-category__name">{{ $category->name }}</strong><span class="yl-category__count">{{ number_format($category->companies_count, 0, ',', '.') }} firma</span></span></a>
                @empty
                    <p class="yl-empty">Hizmet kategorileri yakında burada olacak. <a href="{{ route('owner.register') }}">Firmanızı ekleyerek başlayın.</a></p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="yl-section yl-section--white">
        <div class="yl-wrap">
            <div class="yl-section__head"><div><p class="yl-section__label">Firma vitrini</p><h2>İşletmeleri tanıyın.</h2><p class="yl-section__intro">Öne çıkan ve yeni eklenen firmaların hizmetlerini ve iletişim bilgilerini inceleyin.</p></div><a class="yl-link" href="{{ route('companies.index') }}">Rehberi incele <span aria-hidden="true">→</span></a></div>
            <div class="yl-firms">
                @forelse($ledgerFirms as $company)
                    <article class="yl-firm">
                        <div class="yl-firm__top"><a class="yl-firm__logo" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profili">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" width="54" height="54" alt="" loading="lazy">@else<span aria-hidden="true">{{ mb_substr($company->name, 0, 1) }}</span>@endif</a>@if($company->is_premium)<span class="yl-firm__badge">Öne çıkan</span>@endif</div>
                        <p class="yl-firm__category">{{ $company->category->name ?? 'İşletme' }}</p>
                        <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
                        <p class="yl-firm__description">{{ $company->short_description ?: 'Hizmet kapsamını ve iletişim bilgilerini firma profilinde inceleyin.' }}</p>
                        <div class="yl-firm__bottom"><span class="yl-firm__place"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" aria-hidden="true"><path d="M19 10c0 5-7 10-7 10S5 15 5 10a7 7 0 1 1 14 0Z" stroke-width="1.7"/><circle cx="12" cy="10" r="2.5" stroke-width="1.7"/></svg>{{ $company->city->name ?? 'Türkiye' }}</span><a class="yl-link" href="{{ route('companies.show', $company->slug) }}">Profili incele <span aria-hidden="true">↗</span></a></div>
                    </article>
                @empty
                    <p class="yl-empty">Bu rehberde ilk firma sizinki olabilir. <a href="{{ route('owner.register') }}">Ücretsiz firma başvurusu oluşturun.</a></p>
                @endforelse
            </div>
        </div>
    </section>

    <section class="yl-section" id="sehirler">
        <div class="yl-wrap">
            <div class="yl-section__head"><div><p class="yl-section__label">Yerel keşif</p><h2>Size yakın, işinize uygun.</h2><p class="yl-section__intro">Şehir sayfalarından bölgenizdeki işletmelere ulaşın.</p></div></div>
            <div class="yl-city-grid">@forelse($cities as $city)<a class="yl-city-tile" href="{{ route('cities.show', $city->slug) }}"><span><strong>{{ $city->name }}</strong><small>{{ number_format($city->companies_count, 0, ',', '.') }} firma</small></span><span aria-hidden="true">↗</span></a>@empty<p class="yl-empty">Firma kayıtlarıyla birlikte şehirler burada listelenecek.</p>@endforelse</div>
        </div>
    </section>

    <section class="yl-section" aria-labelledby="yl-business-title">
        <div class="yl-wrap"><div class="yl-banner"><div><span class="yl-banner__label">İşletme sahiplerine</span><h2 id="yl-business-title">İyi yaptığınız işi,<br>daha çok kişiye anlatın.</h2><p>Hizmetlerinizi ve iletişim bilgilerinizi tek bir firma profilinde sunun. Ücretsiz kayıt başvurunuzla başlayın, profilinizi firma panelinden yönetin.</p></div><a class="yl-button" href="{{ route('owner.register') }}">Firmanı Ücretsiz Ekle <span aria-hidden="true">↗</span></a></div></div>
    </section>

    @if($posts->isNotEmpty())
        <section class="yl-section yl-section--white">
            <div class="yl-wrap"><div class="yl-section__head"><div><p class="yl-section__label">İşletme rehberi</p><h2>Biraz bilgi, daha iyi bir karar.</h2></div><a class="yl-link" href="{{ route('blog.index') }}">Tüm yazılar <span aria-hidden="true">→</span></a></div><div class="yl-blog">@foreach($posts as $post)<article class="yl-article"><time datetime="{{ $post->published_at?->toDateString() }}">{{ $post->published_at?->format('d.m.Y') }}</time><h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3><p>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 140) }}</p><a class="yl-link" href="{{ route('blog.show', $post->slug) }}">Yazıyı oku <span aria-hidden="true">→</span></a></article>@endforeach</div></div>
        </section>
    @endif
</div>
@endsection
