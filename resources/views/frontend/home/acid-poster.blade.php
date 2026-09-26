@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Asit Afiş')
@section('meta_description', $settings->meta_description ?? 'Şehrin firmalarını, hizmetlerini ve iş ilanlarını başka bir gözle keşfedin.')

@push('head')
<style>
    html.theme-acid-poster body > header, html.theme-acid-poster body > footer { display:none; }
    .acid-page { overflow:hidden; background:#10140e; color:#f2f7e8; font-family:'Space Grotesk',Arial,sans-serif; }
    .acid-page .ap-container { width:min(100% - 44px, 1400px); margin-inline:auto; }
    .acid-page a { text-decoration:none; }
    .acid-page .ap-topbar { display:flex; align-items:center; justify-content:space-between; gap:18px; padding:15px 0; border-bottom:1px solid #697955; }
    .acid-page .ap-brand { color:#d3ff48; font-size:18px; font-weight:900; letter-spacing:-.05em; text-transform:uppercase; }
    .acid-page .ap-brand span { color:#ff5a95; }
    .acid-page .ap-topbar nav { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:9px 23px; }
    .acid-page .ap-topbar nav a { color:#f2f7e8; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:.08em; }
    .acid-page .ap-topbar nav a:hover { color:#d3ff48; }
    .acid-page .ap-topbar nav a:last-child { color:#d3ff48; }
    .acid-page .ap-hero { position:relative; isolation:isolate; background:#d3ff48; color:#10140e; }
    .acid-page .ap-hero:before { content:''; position:absolute; inset:0; z-index:-1; opacity:.19; background-image:radial-gradient(#10140e 1.4px, transparent 1.5px); background-size:15px 15px; mask-image:linear-gradient(90deg,transparent 43%,black); }
    .acid-page .ap-hero-grid { display:grid; grid-template-columns:minmax(0,1.4fr) minmax(250px,.6fr); gap:35px; padding-block:clamp(55px,8vw,118px) 60px; }
    .acid-page .ap-kicker { display:inline-flex; align-items:center; gap:10px; padding:8px 11px; border:2px solid #10140e; background:#f2f7e8; color:#10140e; font-size:11px; font-weight:900; letter-spacing:.18em; text-transform:uppercase; box-shadow:5px 5px 0 #10140e; }
    .acid-page .ap-hero h1 { max-width:1000px; margin-top:23px; font-size:clamp(56px,9.3vw,150px); line-height:.86; letter-spacing:-.095em; text-transform:uppercase; font-weight:900; overflow-wrap:anywhere; }
    .acid-page .ap-hero h1 em { color:#ff347e; font-style:normal; text-shadow:3px 3px 0 #10140e; }
    .acid-page .ap-hero-sub { max-width:600px; margin-top:28px; font-size:clamp(16px,2vw,21px); line-height:1.45; font-weight:700; }
    .acid-page .ap-search { display:flex; gap:0; max-width:680px; margin-top:30px; border:3px solid #10140e; box-shadow:8px 8px 0 #ff5a95; }
    .acid-page .ap-search input { min-width:0; flex:1; padding:16px 18px; border:0; border-radius:0; background:#f2f7e8; color:#10140e; font-size:16px; outline:none; }
    .acid-page .ap-search button { padding:13px 26px; background:#10140e; color:#d3ff48; font-size:15px; font-weight:900; cursor:pointer; text-transform:uppercase; }
    .acid-page .ap-search button:hover { background:#ff347e; color:#10140e; }
    .acid-page .ap-hero-side { align-self:end; transform:rotate(3deg); border:3px solid #10140e; background:#ff5a95; padding:24px; box-shadow:12px 12px 0 #10140e; }
    .acid-page .ap-hero-side small { display:block; font-size:12px; font-weight:900; letter-spacing:.17em; text-transform:uppercase; }
    .acid-page .ap-hero-side strong { display:block; margin:12px 0 6px; font-size:clamp(50px,6vw,95px); letter-spacing:-.1em; line-height:.9; }
    .acid-page .ap-hero-side p { font-size:14px; font-weight:700; line-height:1.35; }
    .acid-page .ap-hero-side a { display:inline-block; margin-top:18px; border-bottom:3px solid #10140e; color:#10140e; font-size:14px; font-weight:900; }
    .acid-page .ap-ticker { overflow:hidden; border-top:4px solid #10140e; border-bottom:4px solid #10140e; background:#ff5a95; color:#10140e; }
    .acid-page .ap-ticker-inner { display:flex; width:max-content; gap:30px; align-items:center; padding:12px 22px; font-size:13px; font-weight:900; text-transform:uppercase; letter-spacing:.13em; }
    .acid-page .ap-ticker a { color:#10140e; white-space:nowrap; text-decoration:underline; text-underline-offset:3px; }
    .acid-page .ap-ticker b { font-size:18px; }
    .acid-page .ap-section { padding-block:clamp(65px,8vw,115px); }
    .acid-page .ap-section-head { display:flex; flex-wrap:wrap; align-items:end; justify-content:space-between; gap:18px; padding-bottom:20px; border-bottom:2px solid #d3ff48; }
    .acid-page .ap-eyebrow { color:#ff5a95; font-size:12px; font-weight:900; letter-spacing:.18em; text-transform:uppercase; }
    .acid-page .ap-section-head h2 { margin-top:7px; font-size:clamp(37px,5vw,75px); letter-spacing:-.08em; line-height:.98; font-weight:900; text-transform:uppercase; }
    .acid-page .ap-section-head > a { color:#d3ff48; font-size:14px; font-weight:900; text-transform:uppercase; border-bottom:2px solid #d3ff48; }
    .acid-page .ap-firm-list { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:0 30px; }
    .acid-page .ap-firm { display:grid; grid-template-columns:68px minmax(0,1fr) auto; align-items:center; gap:16px; padding:22px 0; border-bottom:1px solid #526044; }
    .acid-page .ap-number { color:#d3ff48; font-size:clamp(37px,5vw,65px); line-height:1; font-weight:900; letter-spacing:-.1em; }
    .acid-page .ap-firm h3 { font-size:clamp(18px,2.1vw,29px); font-weight:900; line-height:1.05; letter-spacing:-.04em; }
    .acid-page .ap-firm h3 a { color:#f2f7e8; }
    .acid-page .ap-firm h3 a:hover { color:#d3ff48; }
    .acid-page .ap-firm p { margin-top:7px; color:#adbca0; font-size:12px; }
    .acid-page .ap-arrow { display:grid; place-items:center; width:36px; height:36px; border:1px solid #526044; color:#d3ff48; font-size:21px; }
    .acid-page .ap-categories { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:12px; margin-top:28px; }
    .acid-page .ap-category { display:flex; min-height:138px; flex-direction:column; justify-content:space-between; border:2px solid #10140e; padding:16px; background:#f2f7e8; color:#10140e; box-shadow:5px 5px 0 #ff5a95; }
    .acid-page .ap-category:nth-child(3n+2) { background:#d3ff48; box-shadow:5px 5px 0 #a58aff; }
    .acid-page .ap-category:nth-child(3n+3) { background:#ff5a95; box-shadow:5px 5px 0 #d3ff48; }
    .acid-page .ap-category small { font-size:11px; font-weight:900; letter-spacing:.12em; }
    .acid-page .ap-category strong { font-size:clamp(18px,2vw,30px); line-height:1; letter-spacing:-.05em; font-weight:900; overflow-wrap:anywhere; }
    .acid-page .ap-category:hover { transform:translate(-3px,-3px); }
    .acid-page .ap-offerings { background:#ff5a95; color:#10140e; }
    .acid-page .ap-offerings .ap-section-head { border-color:#10140e; }
    .acid-page .ap-offerings .ap-eyebrow, .acid-page .ap-offerings .ap-section-head > a { color:#10140e; border-color:#10140e; }
    .acid-page .ap-offering-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:16px; margin-top:28px; }
    .acid-page .ap-offering { display:flex; flex-direction:column; min-height:250px; border:3px solid #10140e; background:#f2f7e8; box-shadow:8px 8px 0 #10140e; color:#10140e; }
    .acid-page .ap-offering img { display:block; width:100%; height:155px; object-fit:cover; border-bottom:3px solid #10140e; }
    .acid-page .ap-offering > div { display:flex; flex:1; flex-direction:column; padding:16px; }
    .acid-page .ap-offering small { font-size:10px; font-weight:900; letter-spacing:.13em; text-transform:uppercase; }
    .acid-page .ap-offering strong { display:block; margin-top:10px; font-size:22px; line-height:1.05; font-weight:900; letter-spacing:-.05em; }
    .acid-page .ap-offering span { display:block; margin-top:auto; padding-top:15px; font-size:12px; font-weight:800; }
    .acid-page .ap-jobs-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:16px; margin-top:30px; }
    .acid-page .ap-job { min-height:260px; display:flex; flex-direction:column; border:2px solid #d3ff48; padding:23px; background:#20271b; color:#f2f7e8; }
    .acid-page .ap-job small { color:#ff5a95; font-size:11px; font-weight:900; text-transform:uppercase; letter-spacing:.14em; }
    .acid-page .ap-job strong { display:block; margin-top:35px; font-size:clamp(22px,2.3vw,35px); line-height:1.02; letter-spacing:-.05em; font-weight:900; }
    .acid-page .ap-job span { margin-top:auto; padding-top:20px; color:#adbca0; font-size:12px; }
    .acid-page .ap-job:hover { background:#2e3b21; transform:translateY(-4px); }
    .acid-page .ap-citystrip { padding-block:40px; background:#a58aff; color:#10140e; }
    .acid-page .ap-citystrip h2 { font-size:clamp(40px,6vw,90px); line-height:.9; letter-spacing:-.08em; font-weight:900; text-transform:uppercase; }
    .acid-page .ap-citylinks { display:flex; flex-wrap:wrap; gap:8px; margin-top:24px; }
    .acid-page .ap-citylinks a { border:2px solid #10140e; padding:10px 14px; color:#10140e; background:#f2f7e8; font-size:13px; font-weight:900; }
    .acid-page .ap-citylinks a:hover { background:#d3ff48; }
    .acid-page .ap-blog { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:15px; margin-top:28px; }
    .acid-page .ap-blog a { border-top:4px solid #ff5a95; padding:18px 0; color:#f2f7e8; font-size:20px; font-weight:900; line-height:1.15; }
    .acid-page .ap-blog small { display:block; margin-top:12px; color:#adbca0; font-size:11px; }
    .acid-page .ap-bottom { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:20px; border-top:2px solid #d3ff48; padding:38px 0; }
    .acid-page .ap-bottom strong { font-size:clamp(23px,3vw,42px); letter-spacing:-.05em; line-height:1; }
    .acid-page .ap-bottom a { display:inline-block; border:3px solid #d3ff48; padding:13px 18px; background:#d3ff48; color:#10140e; font-size:13px; font-weight:900; text-transform:uppercase; box-shadow:6px 6px 0 #ff5a95; }
    @media(max-width:1050px) { .acid-page .ap-hero-grid { grid-template-columns:1fr; } .acid-page .ap-hero-side { max-width:390px; justify-self:end; } .acid-page .ap-offering-grid, .acid-page .ap-categories { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:680px) { .acid-page .ap-container { width:min(100% - 28px, 1400px); } .acid-page .ap-topbar { align-items:flex-start; flex-direction:column; } .acid-page .ap-topbar nav { justify-content:flex-start; gap:10px 15px; } .acid-page .ap-hero-grid { padding-block:50px; } .acid-page .ap-hero h1 { font-size:clamp(53px,14vw,88px); } .acid-page .ap-hero-side { justify-self:start; transform:rotate(1deg); } .acid-page .ap-search { flex-direction:column; } .acid-page .ap-firm-list, .acid-page .ap-jobs-grid, .acid-page .ap-blog { grid-template-columns:1fr; } .acid-page .ap-firm { grid-template-columns:52px minmax(0,1fr) 32px; } .acid-page .ap-number { font-size:45px; } .acid-page .ap-offering-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .acid-page .ap-offering strong { font-size:18px; } }
</style>
@endpush

@section('content')
<div class="acid-page">
    <div class="ap-container ap-topbar"><a class="ap-brand" href="{{ route('home') }}">{{ $directory?->name ?? $settings->site_name ?? 'Asit Afiş' }}<span>★</span></a><nav aria-label="Ana bağlantılar"><a href="{{ route('companies.index') }}">Firmalar</a><a href="{{ route('jobs.index') }}">İş İlanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('owner.dashboard') }}">Firma Paneli</a><a href="{{ route('owner.register') }}">+ Firma Ekle</a></nav></div>

    <section class="ap-hero"><div class="ap-container ap-hero-grid"><div><span class="ap-kicker">01 / keşfet · bağlantı kur · harekete geç</span><h1>{{ $settings->homepage_title ?: 'Şehrin işi burada' }}<em>.</em></h1><p class="ap-hero-sub">{{ $settings->homepage_subtitle ?: 'İşletmeler, ürünler, hizmetler ve iş fırsatları tek bir canlı rehberde.' }}</p><form class="ap-search" action="{{ route('search') }}" method="GET"><input name="q" required aria-label="İşletme veya hizmet ara" placeholder="Ne arıyorsun? Firma, hizmet, şehir…"><button type="submit">BUL ↗</button></form></div><aside class="ap-hero-side"><small>Canlı rehber / {{ now()->year }}</small><strong>{{ \App\Models\Company::active()->count() }}</strong><p>işletme keşfedilmeyi bekliyor. Sıradaki bağlantın burada olabilir.</p><a href="{{ route('companies.index') }}">Tümünü aç ↗</a></aside></div></section>

    <div class="ap-ticker" aria-label="Popüler kategoriler"><div class="ap-ticker-inner"><b>✳</b><span>SEKTÖRLER</span>@foreach($categories->take(8) as $category)<a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }}</a><b>✳</b>@endforeach<a href="{{ route('jobs.index') }}">İŞ İLANLARI</a><b>✳</b></div></div>

    <section class="ap-section ap-container"><div class="ap-section-head"><div><p class="ap-eyebrow">02 / yeni gelenler</p><h2>Radarımıza girenler.</h2></div><a href="{{ route('companies.index') }}">Tüm firmalar ↗</a></div><div class="ap-firm-list">@forelse($latestCompanies->take(8) as $company)<article class="ap-firm"><span class="ap-number">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><div><h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3><p>{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }} @if($company->hasActivePremium()) · PREMIUM @endif</p></div><a class="ap-arrow" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">↗</a></article>@empty<p style="padding:25px 0;color:#adbca0;">Yeni firmalar burada görünecek.</p>@endforelse</div></section>

    <section class="ap-section ap-container" style="padding-top:0"><div class="ap-section-head"><div><p class="ap-eyebrow">03 / kapıları aç</p><h2>Bir sektör seç.</h2></div><a href="{{ route('companies.index') }}">Firmalarda ara ↗</a></div><div class="ap-categories">@forelse($categories->take(8) as $category)<a class="ap-category" href="{{ route('categories.show', $category->slug) }}"><small>{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }} / {{ $category->companies_count }} firma ↗</small><strong>{{ $category->name }}</strong></a>@empty<p>Kategoriler hazırlandığında burada görünecek.</p>@endforelse</div></section>

    @if($featuredOfferings->isNotEmpty())
    <section class="ap-offerings ap-section"><div class="ap-container"><div class="ap-section-head"><div><p class="ap-eyebrow">04 / premium vitrin</p><h2>Vitrindeki işler.</h2></div><a href="{{ route('packages.index') }}">Premium'u keşfet ↗</a></div><div class="ap-offering-grid">@foreach($featuredOfferings as $offering)<a class="ap-offering" href="{{ route('companies.show', $offering->company->slug) }}#urunler-hizmetler">@if($offering->image_path)<img src="{{ asset('storage/'.$offering->image_path) }}" alt="{{ $offering->name }}" loading="lazy">@endif<div><small>{{ $offering->type === 'product' ? 'ÜRÜN' : 'HİZMET' }} / {{ $offering->company->name }}</small><strong>{{ $offering->name }}</strong><span>{{ $offering->price !== null ? number_format((float) $offering->price, 2, ',', '.').' TL · ' : '' }}İncele ↗</span></div></a>@endforeach</div></div></section>
    @endif

    <section class="ap-section ap-container"><div class="ap-section-head"><div><p class="ap-eyebrow">05 / yeni fırsatlar</p><h2>Açık pozisyonlar.</h2></div><a href="{{ route('jobs.index') }}">Bütün ilanlar ↗</a></div><div class="ap-jobs-grid">@forelse($homeJobs as $job)<a class="ap-job" href="{{ route('jobs.show', $job->slug) }}"><small>{{ match($job->employment_type) {'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj', default => 'Tam zamanlı'} }} / {{ $job->published_at?->format('d.m.Y') }}</small><strong>{{ $job->title }}</strong><span>{{ $job->company->name }} · {{ $job->location ?: $job->company->city?->name }} ↗</span></a>@empty<a class="ap-job" href="{{ route('jobs.index') }}"><small>İŞ FIRSATLARI / ŞİMDİ</small><strong>Yeni ilanları takip et.</strong><span>İş ilanları sayfasını aç ↗</span></a>@endforelse</div></section>

    <section class="ap-citystrip"><div class="ap-container"><p class="ap-eyebrow" style="color:#10140e">06 / haritada gezin</p><h2>Şehir şehir keşfet.</h2><div class="ap-citylinks">@forelse($cities->take(12) as $city)<a href="{{ route('cities.show', $city->slug) }}">{{ $city->name }} ↗</a>@empty<a href="{{ route('companies.index') }}">Tüm firmalar ↗</a>@endforelse</div></div></section>

    @if($posts->isNotEmpty())<section class="ap-section ap-container"><div class="ap-section-head"><div><p class="ap-eyebrow">07 / fikirler</p><h2>Okunacaklar.</h2></div><a href="{{ route('blog.index') }}">Tüm yazılar ↗</a></div><div class="ap-blog">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}<small>{{ $post->published_at?->format('d.m.Y') }} ↗</small></a>@endforeach</div></section>@endif

    <div class="ap-container ap-bottom"><strong>İşletmen burada eksik.</strong><a href="{{ route('owner.register') }}">Şimdi firma ekle ↗</a></div>
</div>
@endsection
