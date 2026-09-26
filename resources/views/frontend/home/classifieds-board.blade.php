@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory?->name ?? 'Mahalle İlan Panosu')
@section('meta_description', $settings->meta_description ?? 'Şehir, kategori, firma ve iş ilanlarını tek panoda keşfedin.')

@push('head')
<style>
    html.theme-classifieds-board body > header, html.theme-classifieds-board body > footer { display:none; }
    .classifieds-page { min-height:100vh; background:#f5f4ef; color:#232338; font-family:Arial,sans-serif; }
    .classifieds-page a { color:#3834a7; text-decoration:underline; text-underline-offset:2px; }
    .classifieds-page a:hover { color:#bd4e2a; }
    .classifieds-page .cb-wrap { width:min(100% - 40px, 1460px); margin-inline:auto; }
    .classifieds-page .cb-top { background:#3834a7; color:white; font-size:12px; font-weight:700; letter-spacing:.08em; text-transform:uppercase; }
    .classifieds-page .cb-top .cb-wrap { display:flex; justify-content:space-between; gap:16px; padding-block:9px; }
    .classifieds-page .cb-top a { color:white; }
    .classifieds-page .cb-mast { border-bottom:3px double #3834a7; background:#fffefa; }
    .classifieds-page .cb-mast .cb-wrap { display:grid; grid-template-columns:minmax(0,1fr) auto; align-items:end; gap:20px; padding-block:25px 21px; }
    .classifieds-page .cb-brand { font-family:Georgia,serif; font-size:clamp(32px,4vw,60px); font-weight:900; letter-spacing:-.065em; line-height:1; color:#232338; text-decoration:none; }
    .classifieds-page .cb-brand:hover { color:#3834a7; }
    .classifieds-page .cb-brand em { display:inline-block; padding:.05em .18em .1em; background:#e7e4fb; color:#3834a7; font-style:normal; transform:rotate(-2deg); }
    .classifieds-page .cb-sub { margin-top:10px; color:#626277; font-size:14px; }
    .classifieds-page .cb-mast nav { display:flex; flex-wrap:wrap; gap:9px 17px; justify-content:flex-end; font-size:13px; font-weight:700; }
    .classifieds-page .cb-search { display:grid; grid-template-columns:minmax(0,1fr) auto; gap:9px; margin-block:21px; padding:16px; border:1px solid #aaa8bc; background:#eeecfb; }
    .classifieds-page .cb-search input { min-width:0; width:100%; border:1px solid #aaa8bc; border-radius:0; background:#fff; padding:12px 14px; font-size:16px; color:#232338; }
    .classifieds-page .cb-search button { border:1px solid #29247f; background:#3834a7; padding:10px 24px; color:white; font-size:14px; font-weight:800; cursor:pointer; }
    .classifieds-page .cb-search button:hover { background:#251f85; }
    .classifieds-page .cb-statline { display:flex; flex-wrap:wrap; gap:8px 22px; padding-bottom:20px; font-size:12px; color:#626277; }
    .classifieds-page .cb-statline strong { color:#232338; }
    .classifieds-page .cb-columns { display:grid; grid-template-columns:minmax(195px, .82fr) minmax(0,2fr) minmax(220px,1fr); align-items:start; gap:18px; padding-bottom:40px; }
    .classifieds-page .cb-panel { border:1px solid #cbc9d2; background:#fffefa; }
    .classifieds-page .cb-panel + .cb-panel { margin-top:18px; }
    .classifieds-page .cb-heading { display:flex; align-items:center; justify-content:space-between; gap:12px; margin:0; border-bottom:1px solid #cbc9d2; background:#eeecfb; padding:10px 13px; color:#29247f; font-family:Georgia,serif; font-size:18px; font-weight:800; }
    .classifieds-page .cb-heading small { font:700 11px Arial,sans-serif; color:#626277; white-space:nowrap; }
    .classifieds-page .cb-links { columns:2; column-gap:13px; padding:9px 13px 13px; }
    .classifieds-page .cb-links a { display:block; break-inside:avoid; padding:6px 0; border-bottom:1px dotted #d7d5de; font-size:13px; line-height:1.3; }
    .classifieds-page .cb-links b { float:right; color:#85839c; font-size:11px; text-decoration:none; }
    .classifieds-page .cb-links--one { columns:1; }
    .classifieds-page .cb-list { padding:3px 13px; }
    .classifieds-page .cb-row { display:grid; grid-template-columns:42px minmax(0,1fr) auto; gap:12px; align-items:start; padding:15px 0; border-bottom:1px dotted #cbc9d2; }
    .classifieds-page .cb-row:last-child { border:0; }
    .classifieds-page .cb-initial { display:grid; place-items:center; width:42px; height:42px; border:1px solid #cbc9d2; background:#f4f2fa; color:#3834a7; font:900 20px Georgia,serif; text-decoration:none !important; }
    .classifieds-page .cb-row a.cb-title { font-size:16px; font-weight:800; line-height:1.25; }
    .classifieds-page .cb-row p { margin-top:4px; color:#626277; font-size:12px; line-height:1.45; }
    .classifieds-page .cb-badge { border:1px solid #d89f42; background:#fff4d9; padding:3px 5px; color:#7f4c00; font-size:10px; font-weight:900; text-transform:uppercase; }
    .classifieds-page .cb-minirow { display:block; padding:11px 13px; border-bottom:1px dotted #cbc9d2; }
    .classifieds-page .cb-minirow:last-child { border:0; }
    .classifieds-page .cb-minirow strong { display:block; font-size:14px; }
    .classifieds-page .cb-minirow span { display:block; margin-top:4px; color:#626277; font-size:11px; }
    .classifieds-page .cb-callout { border:2px solid #3834a7; background:#eeecfb; padding:16px; }
    .classifieds-page .cb-callout h2 { font:800 22px Georgia,serif; color:#29247f; }
    .classifieds-page .cb-callout p { margin-top:7px; font-size:13px; line-height:1.5; color:#4f4b70; }
    .classifieds-page .cb-callout a { display:inline-block; margin-top:11px; font-size:13px; font-weight:800; }
    .classifieds-page .cb-end { border-top:3px double #3834a7; padding:16px 0 25px; color:#626277; font-size:12px; }
    .classifieds-page .cb-end .cb-wrap { display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px; }
    @media(max-width:1050px) { .classifieds-page .cb-columns { grid-template-columns:minmax(190px, .8fr) minmax(0,2fr); } .classifieds-page .cb-right { grid-column:1/-1; display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; } .classifieds-page .cb-right .cb-panel { margin:0; } }
    @media(max-width:650px) { .classifieds-page .cb-wrap { width:min(100% - 24px, 1460px); } .classifieds-page .cb-top .cb-wrap { font-size:10px; } .classifieds-page .cb-mast .cb-wrap { grid-template-columns:1fr; } .classifieds-page .cb-mast nav { justify-content:flex-start; } .classifieds-page .cb-columns, .classifieds-page .cb-right { grid-template-columns:1fr; } .classifieds-page .cb-right { grid-column:auto; } .classifieds-page .cb-search { grid-template-columns:1fr; } }
</style>
@endpush

@section('content')
<div class="classifieds-page">
    <div class="cb-top"><div class="cb-wrap"><span>Yerel keşif ağı / {{ now()->translatedFormat('d F Y') }}</span><a href="{{ route('owner.dashboard') }}">Firma paneli ↗</a></div></div>
    <header class="cb-mast"><div class="cb-wrap">
        <div><a class="cb-brand" href="{{ route('home') }}">{{ $directory?->name ?? $settings->site_name ?? 'Mahalle' }} <em>panosu.</em></a><p class="cb-sub">İşletmeleri, hizmetleri ve yeni fırsatları bir arada görün.</p></div>
        <nav aria-label="Pano bağlantıları"><a href="{{ route('companies.index') }}">Tüm firmalar</a><a href="{{ route('jobs.index') }}">İş ilanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('owner.register') }}">+ Firma ekle</a></nav>
    </div></header>

    <div class="cb-wrap">
        <form action="{{ route('search') }}" method="GET" class="cb-search"><input name="q" aria-label="Firma veya hizmet ara" placeholder="Firma, hizmet, kategori veya şehir ara…" required><button type="submit">Panoda ara →</button></form>
        <div class="cb-statline"><span><strong>{{ \App\Models\Company::active()->count() }}</strong> firma</span><span><strong>{{ $categories->count() }}</strong> öne çıkan kategori</span><span><strong>{{ $cities->count() }}</strong> şehir bağlantısı</span><span>Yeni kayıtlar aşağıda ↓</span></div>

        <div class="cb-columns">
            <aside class="cb-left" aria-label="Kategori ve şehirler">
                <section class="cb-panel"><h2 class="cb-heading">Kategoriler <small>sektör seç</small></h2><div class="cb-links cb-links--one">@forelse($categories as $category)<a href="{{ route('categories.show', $category->slug) }}">{{ $category->name }} <b>{{ $category->companies_count }}</b></a>@empty<p style="padding:12px;font-size:13px;">Kategoriler yakında eklenecek.</p>@endforelse</div></section>
                <section class="cb-panel"><h2 class="cb-heading">Şehirler <small>yakınındaki firmalar</small></h2><div class="cb-links cb-links--one">@forelse($cities as $city)<a href="{{ route('cities.show', $city->slug) }}">{{ $city->name }} <b>{{ $city->companies_count ?? '' }}</b></a>@empty<p style="padding:12px;font-size:13px;">Şehir bağlantıları hazırlanıyor.</p>@endforelse</div></section>
            </aside>

            <div class="cb-center">
                <section class="cb-panel"><h2 class="cb-heading">Yeni açılan kayıtlar <small><a href="{{ route('companies.index') }}">hepsini gör →</a></small></h2><div class="cb-list">
                    @forelse($latestCompanies as $company)
                        <article class="cb-row"><a class="cb-initial" href="{{ route('companies.show', $company->slug) }}" aria-label="{{ $company->name }} profilini aç">{{ mb_substr($company->name, 0, 1) }}</a><div><a class="cb-title" href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a><p>{{ $company->category?->name ?? 'Firma' }} · {{ $company->city?->name ?? 'Türkiye' }}{{ $company->short_description ? ' · '.\Illuminate\Support\Str::limit($company->short_description, 95) : '' }}</p></div>@if($company->hasActivePremium())<span class="cb-badge">Vitrin</span>@endif</article>
                    @empty<p style="padding:18px 0;font-size:13px;color:#626277;">İlk firma kaydını siz oluşturabilirsiniz.</p>@endforelse
                </div></section>
                <section class="cb-panel"><h2 class="cb-heading">Açık iş ilanları <small><a href="{{ route('jobs.index') }}">tüm ilanlar →</a></small></h2>@forelse($homeJobs as $job)<a class="cb-minirow" href="{{ route('jobs.show', $job->slug) }}"><strong>{{ $job->title }}</strong><span>{{ $job->company->name }} · {{ $job->location ?: $job->company->city?->name }} · {{ $job->published_at?->format('d.m.Y') }}</span></a>@empty<p style="padding:13px;font-size:13px;color:#626277;">Henüz açık ilan yok. <a href="{{ route('jobs.index') }}">İlan bölümünü açın.</a></p>@endforelse</section>
            </div>

            <aside class="cb-right" aria-label="Öne çıkanlar">
                <div class="cb-callout"><h2>Firmanız da burada olsun.</h2><p>İşletme profilinizi oluşturun; ürünlerinizi, hizmetlerinizi ve ilanlarınızı yayınlayın.</p><a href="{{ route('owner.register') }}">Firma ekleme sayfası →</a></div>
                <section class="cb-panel"><h2 class="cb-heading">Vitrin bağlantıları <small>premium</small></h2>@forelse($featuredOfferings as $offering)<a class="cb-minirow" href="{{ route('companies.show', $offering->company->slug) }}#urunler-hizmetler"><strong>{{ $offering->name }}</strong><span>{{ $offering->type === 'product' ? 'Ürün' : 'Hizmet' }} · {{ $offering->company->name }}</span></a>@empty @foreach($premiumCompanies->take(3) as $company)<a class="cb-minirow" href="{{ route('companies.show', $company->slug) }}"><strong>{{ $company->name }}</strong><span>{{ $company->category?->name }} · {{ $company->city?->name }}</span></a>@endforeach @endforelse</section>
                <section class="cb-panel"><h2 class="cb-heading">Okuma köşesi <small><a href="{{ route('blog.index') }}">blog →</a></small></h2>@forelse($posts as $post)<a class="cb-minirow" href="{{ route('blog.show', $post->slug) }}"><strong>{{ $post->title }}</strong><span>{{ $post->published_at?->format('d.m.Y') }}</span></a>@empty<p style="padding:13px;font-size:13px;color:#626277;">Yeni yazılar burada listelenecek.</p>@endforelse</section>
            </aside>
        </div>
    </div>
    <div class="cb-end"><div class="cb-wrap"><span>{{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }} · Yerel işletme panosu</span><span><a href="{{ route('pages.contact') }}">İletişim</a> · <a href="{{ route('pages.privacy') }}">Gizlilik</a> · <a href="{{ route('packages.index') }}">Premium</a></span></div></div>
</div>
@endsection
