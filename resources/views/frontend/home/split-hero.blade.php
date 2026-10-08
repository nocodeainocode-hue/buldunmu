@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'İhtiyacınızı seçin, bölgenizdeki firmaları inceleyin ve doğrudan iletişime geçin.')

@push('head')
<style>
    .sp { background: var(--bg); color: var(--text); font-family: var(--font_body, Inter, sans-serif); line-height: 1.6; }
    .sp *, .sp *::before, .sp *::after { box-sizing: border-box; }
    .sp :where(a) { color: inherit; text-decoration: none; }
    .sp :where(h1,h2,h3) { margin: 0; font-family: var(--font_heading, Sora, sans-serif); letter-spacing: -.03em; line-height: 1.06; }
    .sp :where(p) { margin: 0; }
    .sp-pad { padding-inline: clamp(20px, 4vw, 56px); }
    .sp-eyebrow { font-size: 12.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--secondary); }

    /* ── Bölünmüş kapak ──────────────────────────────────────────────── */
    .sp-hero { display: grid; grid-template-columns: 1fr 1fr; min-height: min(86vh, 720px); }
    .sp-hero__left { display: flex; flex-direction: column; justify-content: center; gap: 22px; padding-block: 64px; background: var(--primary); color: #fff; }
    .sp-hero__left .sp-eyebrow { color: var(--accent); }
    .sp-hero h1 { font-size: clamp(38px, 5vw, 70px); font-weight: 800; max-width: 13ch; }
    .sp-hero__sub { max-width: 46ch; font-size: 18px; color: rgba(255,255,255,.8); }
    .sp-search { display: flex; max-width: 540px; padding: 6px; border-radius: 999px; background: #fff; box-shadow: 0 18px 40px rgba(0,0,0,.25); }
    .sp-search input { flex: 1 1 auto; min-width: 0; padding: 14px 20px; border: 0; background: transparent; color: var(--text); font: inherit; font-size: 16px; outline: none; }
    .sp-search button { padding: 0 28px; border: 0; border-radius: 999px; background: var(--secondary); color: #fff; font: inherit; font-weight: 700; cursor: pointer; transition: transform .15s ease, filter .15s ease; }
    .sp-search button:hover { transform: scale(1.04); filter: brightness(1.08); }
    .sp-chips { display: flex; flex-wrap: wrap; gap: 8px; max-width: 540px; }
    .sp-chips a { padding: 7px 14px; border: 1px solid rgba(255,255,255,.3); border-radius: 999px; font-size: 14px; font-weight: 600; transition: background .15s ease, color .15s ease; }
    .sp-chips a:hover { background: #fff; color: var(--primary); }
    .sp-stats { display: flex; gap: 34px; padding-top: 22px; border-top: 1px solid rgba(255,255,255,.18); max-width: 540px; }
    .sp-stats strong { display: block; font-family: var(--font_heading, Sora, sans-serif); font-size: 32px; line-height: 1; letter-spacing: -.04em; }
    .sp-stats span { font-size: 13px; color: rgba(255,255,255,.7); }

    .sp-hero__right { position: relative; display: flex; flex-direction: column; justify-content: center; gap: 14px; padding-block: 64px; overflow: hidden; background: var(--primary_light); }
    .sp-hero__right::before { content: ""; position: absolute; width: 520px; height: 520px; right: -160px; top: -140px; border-radius: 50%; background: var(--accent); opacity: .5; }
    .sp-hero__right::after { content: ""; position: absolute; width: 360px; height: 360px; left: -120px; bottom: -130px; border-radius: 50%; background: var(--secondary); opacity: .22; }
    .sp-hero__right > * { position: relative; z-index: 1; }
    .sp-hero__img { position: absolute; inset: 0; z-index: 0; width: 100%; height: 100%; object-fit: cover; }
    .sp-hero__img + .sp-veil { position: absolute; inset: 0; z-index: 0; background: linear-gradient(180deg, rgba(15,61,62,.2), rgba(15,61,62,.7)); }
    .sp-hero__label { font-size: 12.5px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--primary); }
    .sp-card { display: flex; align-items: center; gap: 16px; max-width: 520px; padding: 16px 18px; border-radius: 20px; background: #fff; box-shadow: 0 14px 34px rgba(15,61,62,.16); transition: transform .2s ease; }
    .sp-card:nth-of-type(2) { margin-left: clamp(0px, 4vw, 48px); }
    .sp-card:nth-of-type(3) { margin-left: clamp(0px, 8vw, 96px); }
    .sp-card:hover { transform: translateY(-4px) rotate(-.4deg); }
    .sp-card__mark { flex: 0 0 auto; width: 56px; height: 56px; display: grid; place-items: center; border-radius: 16px; background: var(--primary); color: #fff; font-family: var(--font_heading, Sora, sans-serif); font-size: 24px; font-weight: 800; overflow: hidden; }
    .sp-card:nth-of-type(2) .sp-card__mark { background: var(--secondary); }
    .sp-card:nth-of-type(3) .sp-card__mark { background: var(--accent); color: var(--text); }
    .sp-card__mark img { width: 100%; height: 100%; object-fit: cover; }
    .sp-card__body { min-width: 0; flex: 1 1 auto; }
    .sp-card__body small { display: block; font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--secondary); }
    .sp-card__body strong { display: block; font-family: var(--font_heading, Sora, sans-serif); font-size: 18px; letter-spacing: -.02em; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sp-card__body span { font-size: 13.5px; color: var(--text_muted); }
    .sp-card__go { flex: 0 0 auto; font-size: 20px; color: var(--primary); }
    @media (max-width: 900px) { .sp-hero { grid-template-columns: 1fr; min-height: 0; } .sp-hero__left, .sp-hero__right { padding-block: 44px; } .sp-card:nth-of-type(n) { margin-left: 0; } }

    /* ── Bölünmüş bölümler ───────────────────────────────────────────── */
    .sp-split { display: grid; grid-template-columns: minmax(0, 5fr) minmax(0, 7fr); gap: clamp(28px, 5vw, 80px); padding-block: clamp(54px, 7vw, 104px); border-bottom: 1px solid var(--border); }
    .sp-split--rev { grid-template-columns: minmax(0, 7fr) minmax(0, 5fr); }
    .sp-split--rev .sp-split__head { order: 2; }
    .sp-split__head { align-self: start; position: sticky; top: 90px; }
    .sp-split__head h2 { margin-top: 12px; font-size: clamp(30px, 3.8vw, 52px); font-weight: 800; }
    .sp-split__head p { margin-top: 14px; max-width: 38ch; color: var(--text_muted); }
    .sp-link { display: inline-flex; align-items: center; gap: 8px; margin-top: 22px; padding-bottom: 3px; border-bottom: 2px solid var(--secondary); font-weight: 700; font-size: 15px; }
    .sp-link:hover { color: var(--secondary); }
    @media (max-width: 900px) { .sp-split, .sp-split--rev { grid-template-columns: 1fr; } .sp-split--rev .sp-split__head { order: 0; } .sp-split__head { position: static; } }

    .sp-tiles { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
    .sp-tile { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 20px 22px; border: 1px solid var(--border); border-radius: 18px; background: var(--bg_card); transition: transform .15s ease, background .15s ease, color .15s ease, border-color .15s ease; }
    .sp-tile strong { font-family: var(--font_heading, Sora, sans-serif); font-size: 18px; letter-spacing: -.02em; }
    .sp-tile span { flex: 0 0 auto; padding: 3px 11px; border-radius: 999px; background: var(--primary_light); color: var(--primary); font-size: 13px; font-weight: 700; }
    .sp-tile:hover { background: var(--primary); border-color: var(--primary); color: #fff; transform: translateX(4px); }
    .sp-tile:hover span { background: var(--accent); color: var(--text); }
    @media (max-width: 560px) { .sp-tiles { grid-template-columns: 1fr; } }

    .sp-rows { display: grid; gap: 12px; }
    .sp-row { display: grid; grid-template-columns: 60px minmax(0, 1fr) auto; align-items: center; gap: 18px; padding: 16px 20px; border: 1px solid var(--border); border-radius: 20px; background: var(--bg_card); transition: border-color .15s ease, box-shadow .15s ease; }
    .sp-row:hover { border-color: var(--primary); box-shadow: 0 12px 28px rgba(15,61,62,.1); }
    .sp-row__mark { width: 60px; height: 60px; display: grid; place-items: center; border-radius: 16px; background: var(--primary); color: #fff; font-family: var(--font_heading, Sora, sans-serif); font-size: 26px; font-weight: 800; overflow: hidden; }
    .sp-row__mark img { width: 100%; height: 100%; object-fit: cover; }
    .sp-row small { font-size: 12px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--secondary); }
    .sp-row h3 { margin-top: 2px; font-size: 20px; font-weight: 700; }
    .sp-row p { margin-top: 3px; font-size: 14px; color: var(--text_muted); display: -webkit-box; -webkit-line-clamp: 1; line-clamp: 1; -webkit-box-orient: vertical; overflow: hidden; }
    .sp-row__btns { display: flex; gap: 8px; }
    .sp-btn { display: inline-flex; align-items: center; justify-content: center; padding: 10px 18px; border: 2px solid var(--primary); border-radius: 999px; background: var(--primary); color: #fff !important; font-size: 14px; font-weight: 700; transition: transform .15s ease; }
    .sp-btn:hover { transform: translateY(-2px); }
    .sp-btn--ghost { background: transparent; color: var(--primary) !important; }
    .sp-btn--hot { background: var(--secondary); border-color: var(--secondary); }
    @media (max-width: 640px) { .sp-row { grid-template-columns: 52px minmax(0, 1fr); } .sp-row__btns { grid-column: 1 / -1; } .sp-row__btns .sp-btn { flex: 1; } .sp-row__mark { width: 52px; height: 52px; } }

    .sp-cities { display: flex; flex-wrap: wrap; gap: 10px; }
    .sp-cities a { display: inline-flex; align-items: baseline; gap: 10px; padding: 12px 22px; border: 2px solid var(--text); border-radius: 999px; font-family: var(--font_heading, Sora, sans-serif); font-size: clamp(18px, 2vw, 26px); font-weight: 700; letter-spacing: -.02em; transition: background .15s ease, color .15s ease, transform .15s ease; }
    .sp-cities a small { font-family: var(--font_body, Inter, sans-serif); font-size: 13px; font-weight: 600; opacity: .6; }
    .sp-cities a:hover { background: var(--text); color: #fff; transform: rotate(-1.2deg); }

    .sp-list { display: grid; }
    .sp-list a { display: flex; justify-content: space-between; align-items: baseline; gap: 16px; padding: 16px 0; border-bottom: 1px solid var(--border); transition: padding-left .15s ease, color .15s ease; }
    .sp-list a:first-child { border-top: 1px solid var(--border); }
    .sp-list a:hover { padding-left: 10px; color: var(--secondary); }
    .sp-list strong { font-family: var(--font_heading, Sora, sans-serif); font-size: 19px; letter-spacing: -.02em; line-height: 1.2; }
    .sp-list span { flex: 0 0 auto; font-size: 13px; color: var(--text_muted); }

    .sp-cta { display: grid; grid-template-columns: 1fr 1fr; }
    .sp-cta > div { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 16px; padding-block: clamp(48px, 7vw, 96px); }
    .sp-cta h2 { font-size: clamp(30px, 4vw, 54px); font-weight: 800; max-width: 12ch; }
    .sp-cta p { max-width: 36ch; opacity: .85; }
    .sp-cta__a { background: var(--secondary); color: #fff; }
    .sp-cta__b { background: var(--text); color: #fff; }
    .sp-cta .sp-btn { background: #fff; border-color: #fff; color: var(--text) !important; }
    .sp-cta__b .sp-btn { background: var(--accent); border-color: var(--accent); }
    @media (max-width: 760px) { .sp-cta { grid-template-columns: 1fr; } }
    @media (prefers-reduced-motion: reduce) { .sp *, .sp *::before, .sp *::after { transition: none !important; } }
</style>
@endpush

@section('content')
@php
    $spFeatured = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies;
    $spList = $spFeatured->concat($latestCompanies->whereNotIn('id', $spFeatured->pluck('id')))->take(5);
    $spHeroCards = $spList->take(3);
    $spHeroImage = $directory?->hero_image ? asset('storage/'.$directory->hero_image) : null;
    $spTotal = \App\Models\Company::active()->count();
    $spJobTypes = ['part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj'];
@endphp
<main class="sp">
    <section class="sp-hero">
        <div class="sp-hero__left sp-pad">
            <span class="sp-eyebrow">{{ $directory?->name ?? $settings->site_name ?? 'Firma rehberi' }}</span>
            <h1>{{ $settings->homepage_title ?? 'İhtiyacın olan firma, tek aramada.' }}</h1>
            <p class="sp-hero__sub">{{ $settings->homepage_subtitle ?? 'Hizmeti seç, bölgendeki firmaları incele ve doğrudan iletişime geç.' }}</p>
            <form class="sp-search" action="{{ route('search') }}" method="GET" role="search">
                <label for="sp-q" style="position:absolute;left:-9999px">Firma, hizmet veya şehir ara</label>
                <input id="sp-q" name="q" type="search" placeholder="Örn. diş kliniği, oto servis" autocomplete="off">
                <button type="submit">Ara</button>
            </form>
            @if($categories->isNotEmpty())
                <div class="sp-chips" aria-label="Popüler kategoriler">
                    @foreach($categories->take(5) as $category)<a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a>@endforeach
                </div>
            @endif
            <div class="sp-stats">
                <div><strong>{{ number_format($spTotal, 0, ',', '.') }}</strong><span>firma</span></div>
                <div><strong>{{ $categories->count() }}</strong><span>kategori</span></div>
                <div><strong>{{ $cities->count() }}</strong><span>şehir</span></div>
            </div>
        </div>
        <div class="sp-hero__right sp-pad">
            @if($spHeroImage)<img class="sp-hero__img" src="{{ $spHeroImage }}" alt="" loading="eager"><span class="sp-veil"></span>@endif
            <span class="sp-hero__label" @if($spHeroImage) style="color:#fff" @endif>Öne çıkan firmalar</span>
            @forelse($spHeroCards as $company)
                <a class="sp-card" href="{{ route('companies.show', $company->slug) }}">
                    <span class="sp-card__mark">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="">@else{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}@endif</span>
                    <span class="sp-card__body"><small>{{ $company->category?->name ?? 'Firma' }}</small><strong>{{ $company->name }}</strong><span>{{ $company->city?->name ?? 'Türkiye' }}</span></span>
                    <span class="sp-card__go" aria-hidden="true">→</span>
                </a>
            @empty
                <a class="sp-card" href="{{ route('owner.register') }}"><span class="sp-card__body"><strong>İlk firma sen ol</strong><span>Ücretsiz profil oluştur</span></span><span class="sp-card__go">→</span></a>
            @endforelse
        </div>
    </section>

    <div class="sp-pad">
        <section class="sp-split" aria-labelledby="sp-cat-h">
            <div class="sp-split__head">
                <span class="sp-eyebrow">01 · Kategoriler</span>
                <h2 id="sp-cat-h">Ne arıyorsan, burada.</h2>
                <p>Hizmet alanını seç; o alandaki tüm firmalar yan yana.</p>
                <a class="sp-link" href="{{ route('companies.index') }}">Tüm firmalar →</a>
            </div>
            <div class="sp-tiles">
                @foreach($categories as $category)
                    <a class="sp-tile" href="{{ route('categories.show', $category->slug) }}"><strong>{{ $category->name }}</strong><span>{{ $category->companies_count }}</span></a>
                @endforeach
            </div>
        </section>

        <section class="sp-split sp-split--rev" aria-labelledby="sp-feat-h">
            <div class="sp-rows">
                @forelse($spList as $company)
                    <article class="sp-row">
                        <a class="sp-row__mark" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="">@else{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}@endif</a>
                        <div style="min-width:0">
                            <small>{{ $company->category?->name ?? 'Firma' }} · {{ $company->city?->name ?? 'Türkiye' }}</small>
                            <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
                            <p>{{ $company->short_description ?: 'Hizmetleri ve iletişim bilgileri için profili açın.' }}</p>
                        </div>
                        <div class="sp-row__btns">
                            <a class="sp-btn sp-btn--ghost" href="{{ route('companies.show', $company->slug) }}">Profil</a>
                            @if($company->phone)<a class="sp-btn sp-btn--hot" href="tel:{{ preg_replace('/[^\d+]/', '', $company->phone) }}">Ara</a>@endif
                        </div>
                    </article>
                @empty
                    <p class="sp-eyebrow">Henüz firma yok.</p>
                @endforelse
            </div>
            <div class="sp-split__head">
                <span class="sp-eyebrow">02 · Seçkiler</span>
                <h2 id="sp-feat-h">Önce bunlara bak.</h2>
                <p>Öne çıkan ve en yeni eklenen firmalar.</p>
                <a class="sp-link" href="{{ route('companies.index') }}">Hepsini gör →</a>
            </div>
        </section>

        @if($cities->isNotEmpty())
            <section class="sp-split" aria-labelledby="sp-city-h">
                <div class="sp-split__head">
                    <span class="sp-eyebrow">03 · Şehirler</span>
                    <h2 id="sp-city-h">Yakınındakini bul.</h2>
                    <p>Bulunduğun şehri seç, çevrendeki firmalara ulaş.</p>
                </div>
                <div class="sp-cities">
                    @foreach($cities->take(14) as $city)<a href="{{ route('cities.show', $city->slug) }}">{{ $city->name }} <small>{{ $city->companies_count }}</small></a>@endforeach
                </div>
            </section>
        @endif

        @if($posts->isNotEmpty() || $homeJobs->isNotEmpty())
            <section class="sp-split" style="border-bottom:0">
                <div>
                    <span class="sp-eyebrow">04 · Yazılar</span>
                    <h2 style="margin-top:12px;font-size:clamp(26px,3vw,40px);font-weight:800">Okuma köşesi</h2>
                    <div class="sp-list" style="margin-top:22px">
                        @forelse($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><strong>{{ $post->title }}</strong><span>{{ $post->published_at?->format('d.m.Y') }}</span></a>@empty<p class="sp-eyebrow">Yakında yazılar eklenecek.</p>@endforelse
                    </div>
                    <a class="sp-link" href="{{ route('blog.index') }}">Tüm yazılar →</a>
                </div>
                <div>
                    <span class="sp-eyebrow">05 · İş ilanları</span>
                    <h2 style="margin-top:12px;font-size:clamp(26px,3vw,40px);font-weight:800">Açık pozisyonlar</h2>
                    <div class="sp-list" style="margin-top:22px">
                        @forelse($homeJobs as $job)<a href="{{ route('jobs.show', $job->slug) }}"><strong>{{ $job->title }}<br><span style="font-family:var(--font_body);font-weight:500;letter-spacing:0">{{ $job->company?->name }}</span></strong><span>{{ $spJobTypes[$job->employment_type] ?? 'Tam zamanlı' }}</span></a>@empty<p class="sp-eyebrow">Şu an açık pozisyon yok.</p>@endforelse
                    </div>
                    <a class="sp-link" href="{{ route('jobs.index') }}">Tüm ilanlar →</a>
                </div>
            </section>
        @endif
    </div>

    <section class="sp-cta">
        <div class="sp-cta__a sp-pad">
            <h2>Firman mı var?</h2>
            <p>Ücretsiz profilini oluştur, müşteriler seni bulsun.</p>
            <a class="sp-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
        </div>
        <div class="sp-cta__b sp-pad">
            <h2>Öne çık.</h2>
            <p>Premium paketlerle listelerin en üstünde yer al.</p>
            <a class="sp-btn" href="{{ route('packages.index') }}">Paketleri incele →</a>
        </div>
    </section>
</main>
@endsection
