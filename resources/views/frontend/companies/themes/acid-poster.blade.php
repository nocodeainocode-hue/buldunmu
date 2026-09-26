@push('head')
<style>
html.theme-acid-poster body > header, html.theme-acid-poster body > footer { display:none; }
.ap-detail { min-height:100vh; overflow:hidden; background:#10140e; color:#f2f7e8; font-family:'Space Grotesk',Arial,sans-serif; }
.ap-detail .ad-wrap { width:min(100% - 44px,1400px); margin:auto; }
.ap-detail a { text-decoration:none; }
.ap-detail .ad-top { display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:14px; padding:16px 0; border-bottom:1px solid #697955; }
.ap-detail .ad-brand { color:#d3ff48; font-size:20px; font-weight:900; text-transform:uppercase; letter-spacing:-.06em; }
.ap-detail .ad-brand span { color:#ff5a95; }
.ap-detail .ad-top nav { display:flex; flex-wrap:wrap; gap:10px 23px; }
.ap-detail .ad-top nav a { color:#f2f7e8; font-size:11px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
.ap-detail .ad-top nav a:hover { color:#d3ff48; }
.ap-detail .ad-hero { background:#d3ff48; color:#10140e; border-bottom:5px solid #10140e; }
.ap-detail .ad-crumb { display:flex; flex-wrap:wrap; gap:7px; padding-top:26px; color:#263219; font-size:12px; font-weight:800; }
.ap-detail .ad-crumb a { color:#263219; text-decoration:underline; text-underline-offset:3px; }
.ap-detail .ad-hero-grid { display:grid; grid-template-columns:minmax(0,1.3fr) minmax(250px,.7fr); gap:35px; align-items:end; padding:42px 0 55px; }
.ap-detail .ad-kicker { display:inline-block; border:2px solid #10140e; background:#f2f7e8; padding:7px 10px; box-shadow:5px 5px 0 #10140e; font-size:11px; font-weight:900; letter-spacing:.14em; text-transform:uppercase; }
.ap-detail h1 { margin:25px 0 15px; max-width:950px; font-size:clamp(52px,8vw,135px); line-height:.87; letter-spacing:-.095em; text-transform:uppercase; font-weight:900; overflow-wrap:anywhere; }
.ap-detail .ad-sub { max-width:700px; font-size:clamp(15px,2vw,21px); line-height:1.4; font-weight:700; }
.ap-detail .ad-badges { display:flex; flex-wrap:wrap; gap:7px; margin-top:23px; }
.ap-detail .ad-badges span { border:2px solid #10140e; padding:5px 9px; background:#f2f7e8; font-size:11px; font-weight:900; text-transform:uppercase; }
.ap-detail .ad-badges .ad-premium { background:#ff5a95; }
.ap-detail .ad-visual { position:relative; min-height:290px; display:flex; align-items:center; justify-content:center; transform:rotate(2deg); border:4px solid #10140e; background:#ff5a95; box-shadow:14px 14px 0 #10140e; }
.ap-detail .ad-visual img { width:100%; height:290px; object-fit:cover; }
.ap-detail .ad-visual img.ad-logo { width:170px; height:170px; object-fit:contain; padding:12px; background:#f2f7e8; border:3px solid #10140e; }
.ap-detail .ad-initial { font-size:clamp(130px,18vw,270px); line-height:1; font-weight:900; text-transform:uppercase; }
.ap-detail .ad-visual small { position:absolute; top:13px; left:14px; border:2px solid #10140e; background:#f2f7e8; padding:5px 8px; font-size:10px; font-weight:900; text-transform:uppercase; }
.ap-detail .ad-ticker { border-bottom:4px solid #10140e; background:#ff5a95; color:#10140e; }
.ap-detail .ad-ticker .ad-wrap { display:flex; flex-wrap:wrap; gap:9px 22px; padding:12px 0; font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:.1em; }
.ap-detail .ad-ticker a { color:#10140e; text-decoration:underline; text-underline-offset:3px; }
.ap-detail .ad-columns { display:grid; grid-template-columns:minmax(0,1fr) 340px; align-items:start; gap:32px; padding:70px 0 85px; }
.ap-detail .ad-main, .ap-detail .ad-side { min-width:0; display:grid; gap:55px; }
.ap-detail .ad-side { gap:20px; }
.ap-detail .td-section { border-top:3px solid #d3ff48; padding-top:22px; }
.ap-detail .td-section-head { display:flex; flex-wrap:wrap; align-items:baseline; gap:10px; margin-bottom:23px; }
.ap-detail .td-index { color:#ff5a95; font-size:13px; font-weight:900; letter-spacing:.14em; }
.ap-detail .td-section-head h2 { font-size:clamp(30px,4vw,58px); line-height:.95; letter-spacing:-.075em; font-weight:900; text-transform:uppercase; overflow-wrap:anywhere; }
.ap-detail .td-section-head > a, .ap-detail .td-count { margin-left:auto; color:#d3ff48; font-size:12px; font-weight:900; text-transform:uppercase; }
.ap-detail .td-prose { max-width:850px; color:#d7e4ce; font-size:16px; line-height:1.75; }
.ap-detail .td-prose p + p { margin-top:15px; }
.ap-detail .td-prose h2, .ap-detail .td-prose h3 { margin:20px 0 10px; color:#f2f7e8; font-size:24px; font-weight:900; }
.ap-detail .td-prose a { color:#d3ff48; text-decoration:underline; }
.ap-detail .td-prose ul { list-style:disc; padding-left:22px; }
.ap-detail .td-share { margin-top:20px; border-top:1px solid #526044; padding-top:12px; }
.ap-detail .td-card-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:16px; }
.ap-detail .td-card { display:block; min-width:0; border:3px solid #d3ff48; background:#20271b; color:#f2f7e8; box-shadow:7px 7px 0 #ff5a95; }
.ap-detail .td-card:hover { transform:translate(-3px,-3px); box-shadow:10px 10px 0 #ff5a95; }
.ap-detail .td-card-image { display:block; width:100%; height:190px; object-fit:cover; border-bottom:3px solid #d3ff48; }
.ap-detail .td-card-body { padding:20px; }
.ap-detail .td-card small { color:#ff5a95; font-size:10px; font-weight:900; letter-spacing:.13em; }
.ap-detail .td-card h3 { margin:8px 0; font-size:clamp(20px,2vw,30px); line-height:1.05; font-weight:900; letter-spacing:-.05em; overflow-wrap:anywhere; }
.ap-detail .td-card p { color:#adbca0; font-size:13px; line-height:1.55; }
.ap-detail .td-card .td-linebreak { white-space:pre-line; }
.ap-detail .td-price { display:block; margin-top:12px; color:#d3ff48; }
.ap-detail .td-job { display:flex; flex-direction:column; min-height:190px; padding:21px; }
.ap-detail .td-job strong { margin-top:auto; padding-top:22px; color:#d3ff48; font-size:12px; text-transform:uppercase; }
.ap-detail .td-gallery { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:10px; }
.ap-detail .td-gallery img { width:100%; aspect-ratio:1; object-fit:cover; border:2px solid #d3ff48; }
.ap-detail .td-map { aspect-ratio:16/9; border:3px solid #d3ff48; }
.ap-detail .td-map iframe { width:100%; height:100%; border:0; }
.ap-detail .td-review { border-bottom:1px solid #526044; padding:18px 0; }
.ap-detail .td-review > div { display:flex; justify-content:space-between; gap:10px; font-size:13px; }
.ap-detail .td-review span, .ap-detail .td-review > p:last-child, .ap-detail .td-empty { color:#adbca0; }
.ap-detail .td-stars { color:#d3ff48; letter-spacing:.12em; }
.ap-detail .td-form-title { margin:25px 0 12px; color:#d3ff48; font-size:22px; font-weight:900; text-transform:uppercase; }
.ap-detail .td-review-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:11px; }
.ap-detail .td-review-form input, .ap-detail .td-review-form select, .ap-detail .td-review-form textarea { min-width:0; border:2px solid #d3ff48; border-radius:0; background:#20271b; padding:13px; color:#f2f7e8; font-size:13px; }
.ap-detail .td-review-form textarea { grid-column:1/-1; }
.ap-detail .td-review-form input::placeholder, .ap-detail .td-review-form textarea::placeholder { color:#adbca0; }
.ap-detail .td-review-form button { width:max-content; border:3px solid #d3ff48; background:#d3ff48; padding:11px 17px; color:#10140e; font-size:12px; font-weight:900; text-transform:uppercase; box-shadow:5px 5px 0 #ff5a95; cursor:pointer; }
.ap-detail .td-message { border:2px solid #d3ff48; padding:10px; margin-bottom:12px; }
.ap-detail .td-faq { border-bottom:1px solid #526044; padding:13px 0; }
.ap-detail .td-faq summary { cursor:pointer; font-weight:900; }
.ap-detail .td-faq p { padding-top:10px; color:#adbca0; }
.ap-detail .td-side-card { border:3px solid #d3ff48; background:#20271b; padding:22px; box-shadow:7px 7px 0 #ff5a95; }
.ap-detail .td-side-label { margin-bottom:10px; color:#ff5a95; font-size:10px; font-weight:900; letter-spacing:.15em; text-transform:uppercase; }
.ap-detail .td-side-card h2 { color:#f2f7e8; font-size:clamp(23px,2.6vw,35px); line-height:1; letter-spacing:-.06em; font-weight:900; text-transform:uppercase; overflow-wrap:anywhere; }
.ap-detail .td-side-card > p:not(.td-side-label) { margin-top:13px; color:#adbca0; font-size:13px; }
.ap-detail .td-contact dl { margin:17px 0; }
.ap-detail .td-contact dl div { border-bottom:1px solid #526044; padding:10px 0; overflow-wrap:anywhere; }
.ap-detail .td-contact dt { color:#adbca0; font-size:10px; text-transform:uppercase; }
.ap-detail .td-contact dd { font-weight:800; }
.ap-detail .td-contact dd a { color:#f2f7e8; }
.ap-detail .td-actions { display:grid; gap:8px; }
.ap-detail .td-actions a, .ap-detail .td-claim > a { display:block; border:2px solid #d3ff48; padding:10px; color:#d3ff48; text-align:center; font-size:12px; font-weight:900; text-transform:uppercase; }
.ap-detail .td-actions a:first-child, .ap-detail .td-claim > a { background:#d3ff48; color:#10140e; }
.ap-detail .td-claim > a { margin:17px 0 12px; }
.ap-detail .td-claim small { color:#adbca0; }
.ap-detail .td-related a { display:block; border-bottom:1px solid #526044; padding:10px 0; color:#f2f7e8; }
.ap-detail .td-related strong, .ap-detail .td-related span { display:block; }
.ap-detail .td-related span { color:#adbca0; font-size:11px; }
.ap-detail .ad-bottom { background:#a58aff; color:#10140e; padding:45px 0; }
.ap-detail .ad-bottom .ad-wrap { display:flex; flex-wrap:wrap; align-items:center; justify-content:space-between; gap:20px; }
.ap-detail .ad-bottom strong { max-width:750px; font-size:clamp(28px,4vw,60px); line-height:.94; letter-spacing:-.08em; font-weight:900; text-transform:uppercase; }
.ap-detail .ad-bottom a { border:3px solid #10140e; background:#d3ff48; padding:13px 18px; color:#10140e; font-size:13px; font-weight:900; text-transform:uppercase; box-shadow:6px 6px 0 #10140e; }
@media(max-width:1050px) { .ap-detail .ad-hero-grid { grid-template-columns:1fr; } .ap-detail .ad-visual { max-width:480px; } .ap-detail .ad-columns { grid-template-columns:1fr; } .ap-detail .ad-side { grid-template-columns:repeat(2,minmax(0,1fr)); } }
@media(max-width:680px) { .ap-detail .ad-wrap { width:min(100% - 28px,1400px); } .ap-detail .ad-top nav { gap:8px 13px; } .ap-detail .ad-hero-grid { gap:24px; padding:30px 0 45px; } .ap-detail h1 { font-size:clamp(52px,14vw,88px); } .ap-detail .ad-visual { min-height:200px; transform:rotate(1deg); } .ap-detail .ad-visual img { height:210px; } .ap-detail .ad-initial { font-size:150px; } .ap-detail .ad-columns { padding:50px 0; } .ap-detail .td-card-grid, .ap-detail .ad-side, .ap-detail .td-review-form { grid-template-columns:1fr; } .ap-detail .td-review-form textarea { grid-column:auto; } .ap-detail .td-gallery { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush
<div class="ap-detail">
    <div class="ad-wrap ad-top"><a class="ad-brand" href="{{ route('home') }}">{{ $directory?->name ?? 'Asit Afiş' }}<span>★</span></a><nav><a href="{{ route('companies.index') }}">Firmalar</a><a href="{{ route('jobs.index') }}">İş ilanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('owner.dashboard') }}">Firma paneli</a><a href="{{ route('owner.register') }}">+ Firma ekle</a></nav></div>
    <header class="ad-hero"><div class="ad-wrap"><nav class="ad-crumb" aria-label="Gezinme yolu"><a href="{{ route('home') }}">Ana sayfa</a><span>/</span>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a><span>/</span>@endif<span>{{ $company->name }}</span></nav><div class="ad-hero-grid"><div><span class="ad-kicker">Firma profili / {{ $categoryName }}</span><h1>{{ $company->name }}<span style="color:#ff347e;text-shadow:3px 3px 0 #10140e">.</span></h1><p class="ad-sub">{{ $company->short_description ?: $cityName.' bölgesinde '.$categoryName.' alanında faaliyet gösteren işletme.' }}</p><div class="ad-badges">@if($company->hasActivePremium())<span class="ad-premium">Premium vitrin</span>@endif @if($company->is_verified)<span>Doğrulanmış</span>@endif <span>{{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }}</span>@if($ratingAvg)<span>★ {{ $ratingAvg }} / 5</span>@endif</div></div><div class="ad-visual"><small>Keşif kartı / #{{ $company->id }}</small>@if($company->cover_image)<img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">@elseif($company->logo)<img class="ad-logo" src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu" loading="eager">@else<span class="ad-initial">{{ mb_substr($company->name,0,1) }}</span>@endif</div></div></div></header>
    <nav class="ad-ticker" aria-label="Firma bölümleri"><div class="ad-wrap"><span>KEŞFET ✳</span><a href="#hakkinda">Hakkında ↗</a>@if($offerings->isNotEmpty())<a href="#urunler-hizmetler">Ürün ve hizmetler ↗</a>@endif @if($recentJobs->isNotEmpty())<a href="#is-ilanlari">İş ilanları ↗</a>@endif @if($company->images->isNotEmpty())<a href="#galeri">Fotoğraflar ↗</a>@endif <a href="#yorumlar">Yorumlar ↗</a><a href="#iletisim">İletişim ↗</a></div></nav>
    <div class="ad-wrap ad-columns"><div class="ad-main">@include('frontend.companies.themes.sections')</div><aside class="ad-side">@include('frontend.companies.themes.sidebar')</aside></div>
    <div class="ad-bottom"><div class="ad-wrap"><strong>Bir sonraki keşfin nerede?</strong><a href="{{ route('companies.index') }}">Tüm firmaları aç ↗</a></div></div>
    @if(str_starts_with((string) $company->external_id, 'osm:'))<div class="ad-wrap" style="padding:13px 0;color:#adbca0;font-size:11px;">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:#d3ff48">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>@endif
</div>
