@push('head')
<style>
html.theme-classifieds-board body > header, html.theme-classifieds-board body > footer { display:none; }
.cb-detail { min-height:100vh; background:#f5f4ef; color:#232338; font:14px/1.5 Arial,sans-serif; }
.cb-detail a { color:#3834a7; text-decoration:underline; text-underline-offset:2px; }
.cb-detail a:hover { color:#bd4e2a; }
.cb-detail .cd-wrap { width:min(100% - 40px,1460px); margin:auto; }
.cb-detail .cd-strip { background:#3834a7; color:#fff; font-size:11px; font-weight:800; letter-spacing:.08em; text-transform:uppercase; }
.cb-detail .cd-strip .cd-wrap { display:flex; justify-content:space-between; gap:15px; padding:9px 0; }
.cb-detail .cd-strip a { color:#fff; }
.cb-detail .cd-mast { background:#fffefa; border-bottom:3px double #3834a7; }
.cb-detail .cd-mast .cd-wrap { display:flex; align-items:end; justify-content:space-between; flex-wrap:wrap; gap:15px; padding:20px 0; }
.cb-detail .cd-brand { font:900 clamp(29px,3.6vw,50px)/1 Georgia,serif; letter-spacing:-.06em; color:#232338; text-decoration:none; }
.cb-detail .cd-brand span { color:#3834a7; }
.cb-detail .cd-mast nav { display:flex; flex-wrap:wrap; gap:8px 18px; font-size:12px; font-weight:800; }
.cb-detail .cd-crumb { display:flex; flex-wrap:wrap; gap:7px; padding:16px 0; color:#626277; font-size:12px; }
.cb-detail .cd-hero { border:1px solid #cbc9d2; background:#fffefa; }
.cb-detail .cd-cover { width:100%; height:220px; object-fit:cover; display:block; border-bottom:1px solid #cbc9d2; }
.cb-detail .cd-hero-inner { display:grid; grid-template-columns:80px minmax(0,1fr) auto; gap:20px; align-items:start; padding:26px; }
.cb-detail .cd-logo { display:grid; place-items:center; width:80px; height:80px; border:2px solid #3834a7; background:#eeecfb; color:#3834a7; font:900 35px Georgia,serif; }
.cb-detail .cd-logo img { width:100%; height:100%; object-fit:contain; background:#fff; }
.cb-detail .cd-kicker { color:#3834a7; font-size:11px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
.cb-detail h1 { margin:4px 0 9px; font:900 clamp(32px,4vw,57px)/1.05 Georgia,serif; letter-spacing:-.06em; overflow-wrap:anywhere; }
.cb-detail .cd-description { max-width:850px; color:#626277; font-size:15px; }
.cb-detail .cd-badges { display:flex; flex-wrap:wrap; gap:8px; margin-top:15px; }
.cb-detail .cd-badges span { padding:4px 8px; border:1px solid #cbc9d2; background:#f5f4ef; color:#3834a7; font-size:10px; font-weight:900; text-transform:uppercase; }
.cb-detail .cd-badges .cd-premium { background:#fff4d9; border-color:#d89f42; color:#7f4c00; }
.cb-detail .cd-hero-action { display:grid; gap:8px; min-width:138px; }
.cb-detail .cd-hero-action a { padding:9px 12px; border:1px solid #3834a7; background:#3834a7; color:#fff; text-align:center; text-decoration:none; font-size:12px; font-weight:900; }
.cb-detail .cd-hero-action a:nth-child(2) { background:#fff; color:#3834a7; }
.cb-detail .cd-anchorbar { display:flex; flex-wrap:wrap; gap:8px 20px; padding:12px 0 20px; font-size:12px; font-weight:800; }
.cb-detail .cd-columns { display:grid; grid-template-columns:175px minmax(0,1fr) 280px; gap:18px; align-items:start; padding-bottom:55px; }
.cb-detail .cd-index { border:1px solid #cbc9d2; background:#fffefa; padding:14px; position:sticky; top:16px; }
.cb-detail .cd-index h2 { border-bottom:1px solid #cbc9d2; padding-bottom:8px; font:800 18px Georgia,serif; }
.cb-detail .cd-index a { display:block; border-bottom:1px dotted #cbc9d2; padding:8px 0; font-size:12px; }
.cb-detail .cd-main, .cb-detail .cd-side { display:grid; gap:18px; min-width:0; }
.cb-detail .td-section, .cb-detail .td-side-card { border:1px solid #cbc9d2; background:#fffefa; padding:22px; }
.cb-detail .td-section-head { display:flex; flex-wrap:wrap; align-items:baseline; gap:10px; border-bottom:1px solid #cbc9d2; padding-bottom:12px; margin-bottom:17px; }
.cb-detail .td-index { color:#3834a7; font:900 19px Georgia,serif; }
.cb-detail .td-section-head h2, .cb-detail .td-side-card h2 { color:#232338; font:800 clamp(20px,2vw,27px)/1.15 Georgia,serif; }
.cb-detail .td-section-head > a, .cb-detail .td-count { margin-left:auto; font-size:11px; font-weight:800; }
.cb-detail .td-prose { color:#484859; font-size:14px; line-height:1.75; }
.cb-detail .td-prose p + p { margin-top:12px; }
.cb-detail .td-prose h2, .cb-detail .td-prose h3 { margin:15px 0 6px; color:#232338; font:800 20px Georgia,serif; }
.cb-detail .td-prose ul { list-style:disc; padding-left:22px; }
.cb-detail .td-share { margin-top:20px; border-top:1px dotted #cbc9d2; padding-top:12px; }
.cb-detail .td-card-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:10px; }
.cb-detail .td-card { display:block; min-width:0; border:1px solid #cbc9d2; background:#f9f8f4; color:#232338; text-decoration:none; }
.cb-detail .td-card-image { width:100%; height:155px; object-fit:cover; border-bottom:1px solid #cbc9d2; }
.cb-detail .td-card-body { padding:14px; }
.cb-detail .td-card small { color:#3834a7; font-size:10px; font-weight:900; letter-spacing:.1em; }
.cb-detail .td-card h3 { margin:5px 0; font-size:16px; font-weight:800; }
.cb-detail .td-card p { color:#626277; font-size:12px; line-height:1.5; }
.cb-detail .td-card .td-linebreak { white-space:pre-line; }
.cb-detail .td-price { display:block; margin-top:10px; color:#3834a7; }
.cb-detail .td-job { padding:15px; }
.cb-detail .td-job strong { display:block; margin-top:14px; font-size:12px; }
.cb-detail .td-gallery { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:8px; }
.cb-detail .td-gallery img { width:100%; aspect-ratio:1; object-fit:cover; border:1px solid #cbc9d2; }
.cb-detail .td-map { aspect-ratio:16/9; }
.cb-detail .td-map iframe { width:100%; height:100%; border:0; }
.cb-detail .td-review { border-bottom:1px dotted #cbc9d2; padding:12px 0; }
.cb-detail .td-review > div { display:flex; justify-content:space-between; gap:10px; font-size:12px; }
.cb-detail .td-review span { color:#626277; }
.cb-detail .td-review > p:last-child { color:#484859; font-size:13px; }
.cb-detail .td-stars { color:#a66b0e; letter-spacing:.12em; }
.cb-detail .td-empty { color:#626277; font-size:13px; }
.cb-detail .td-form-title { margin:19px 0 11px; font:800 18px Georgia,serif; }
.cb-detail .td-review-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
.cb-detail .td-review-form input, .cb-detail .td-review-form select, .cb-detail .td-review-form textarea { min-width:0; border:1px solid #aaa8bc; border-radius:0; background:#fff; padding:10px; color:#232338; font-size:13px; }
.cb-detail .td-review-form textarea { grid-column:1/-1; }
.cb-detail .td-review-form button { width:max-content; border:1px solid #3834a7; background:#3834a7; padding:10px 14px; color:#fff; font-size:12px; font-weight:900; cursor:pointer; }
.cb-detail .td-message { border:1px solid #d89f42; background:#fff4d9; padding:10px; margin-bottom:12px; }
.cb-detail .td-faq { border-bottom:1px dotted #cbc9d2; padding:10px 0; }
.cb-detail .td-faq summary { cursor:pointer; font-weight:800; }
.cb-detail .td-faq p { padding-top:8px; color:#626277; }
.cb-detail .td-side-label { margin-bottom:8px; color:#3834a7; font-size:10px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
.cb-detail .td-side-card > p:not(.td-side-label) { margin-top:9px; color:#626277; font-size:12px; }
.cb-detail .td-contact dl { margin:12px 0; }
.cb-detail .td-contact dl div { border-bottom:1px dotted #cbc9d2; padding:8px 0; overflow-wrap:anywhere; }
.cb-detail .td-contact dt { color:#626277; font-size:10px; text-transform:uppercase; }
.cb-detail .td-contact dd { font-weight:800; }
.cb-detail .td-actions { display:grid; gap:7px; margin-top:15px; }
.cb-detail .td-actions a, .cb-detail .td-claim > a { display:block; padding:9px 10px; border:1px solid #3834a7; background:#eeecfb; color:#3834a7; text-align:center; font-size:12px; font-weight:900; }
.cb-detail .td-claim > a { margin:14px 0 8px; background:#3834a7; color:#fff; }
.cb-detail .td-claim small { color:#626277; }
.cb-detail .td-related a { display:block; border-bottom:1px dotted #cbc9d2; padding:9px 0; }
.cb-detail .td-related strong, .cb-detail .td-related span { display:block; }
.cb-detail .td-related span { color:#626277; font-size:11px; }
.cb-detail .cd-foot { border-top:3px double #3834a7; background:#fffefa; padding:18px 0; font-size:12px; }
@media(max-width:1120px) { .cb-detail .cd-columns { grid-template-columns:minmax(0,1fr) 280px; } .cb-detail .cd-index { display:none; } }
@media(max-width:760px) { .cb-detail .cd-wrap { width:min(100% - 24px,1460px); } .cb-detail .cd-hero-inner { grid-template-columns:62px minmax(0,1fr); padding:17px; gap:13px; } .cb-detail .cd-logo { width:62px; height:62px; } .cb-detail .cd-hero-action { grid-column:1/-1; display:flex; flex-wrap:wrap; } .cb-detail .cd-columns { grid-template-columns:1fr; } .cb-detail .td-card-grid { grid-template-columns:1fr; } .cb-detail .td-gallery { grid-template-columns:repeat(2,1fr); } }
</style>
@endpush
<div class="cb-detail">
    <div class="cd-strip"><div class="cd-wrap"><span>Yerel işletme panosu / Firma profili</span><a href="{{ route('owner.dashboard') }}">Firma paneli ↗</a></div></div>
    <header class="cd-mast"><div class="cd-wrap"><a class="cd-brand" href="{{ route('home') }}">{{ $directory?->name ?? 'Mahalle' }} <span>panosu.</span></a><nav><a href="{{ route('companies.index') }}">Tüm firmalar</a><a href="{{ route('jobs.index') }}">İş ilanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('owner.register') }}">+ Firma ekle</a></nav></div></header>
    <div class="cd-wrap">
        <nav class="cd-crumb" aria-label="Gezinme yolu"><a href="{{ route('home') }}">Ana sayfa</a><span>/</span>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a><span>/</span>@endif<span>{{ $company->name }}</span></nav>
        <div class="cd-hero">@if($company->cover_image)<img class="cd-cover" src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">@endif<div class="cd-hero-inner"><div class="cd-logo">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu">@else{{ mb_substr($company->name,0,1) }}@endif</div><div><p class="cd-kicker">{{ $categoryName }} / {{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }}</p><h1>{{ $company->name }}</h1><p class="cd-description">{{ $company->short_description ?: $company->name.' işletme profili, iletişim bilgileri ve kullanıcı yorumları.' }}</p><div class="cd-badges">@if($company->hasActivePremium())<span class="cd-premium">Premium vitrin</span>@endif @if($company->is_verified)<span>Doğrulanmış</span>@endif @if($ratingAvg)<span>★ {{ $ratingAvg }} / 5 · {{ $reviewCount }} yorum</span>@endif<span>Profil #{{ $company->id }}</span></div></div><div class="cd-hero-action">@if($company->phone)<a href="tel:{{ $phoneClean }}">Telefon et ↗</a>@endif @if($company->whatsapp)<a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>@endif</div></div></div>
        <nav class="cd-anchorbar" aria-label="Firma bölümleri"><a href="#hakkinda">Hakkında</a>@if($offerings->isNotEmpty())<a href="#urunler-hizmetler">Ürün ve hizmetler</a>@endif @if($recentJobs->isNotEmpty())<a href="#is-ilanlari">İş ilanları</a>@endif @if($company->images->isNotEmpty())<a href="#galeri">Fotoğraflar</a>@endif <a href="#yorumlar">Yorumlar</a><a href="#iletisim">İletişim</a></nav>
        <div class="cd-columns"><aside class="cd-index"><h2>Bu sayfada</h2><a href="#hakkinda">Firma hakkında</a>@if($offerings->isNotEmpty())<a href="#urunler-hizmetler">Ürünler ve hizmetler</a>@endif @if($recentJobs->isNotEmpty())<a href="#is-ilanlari">İş ilanları</a>@endif @if($company->images->isNotEmpty())<a href="#galeri">Galeri</a>@endif <a href="#yorumlar">Değerlendirmeler</a><a href="#iletisim">İletişim</a></aside><div class="cd-main">@include('frontend.companies.themes.sections')</div><aside class="cd-side">@include('frontend.companies.themes.sidebar')</aside></div>
    </div>
    <footer class="cd-foot"><div class="cd-wrap"><a href="{{ route('home') }}">← {{ $directory?->name ?? 'Ana sayfa' }}</a> · <a href="{{ route('companies.index') }}">Diğer firmaları keşfet</a> · <a href="{{ route('pages.contact') }}">İletişim</a></div></footer>
    @if(str_starts_with((string) $company->external_id, 'osm:'))<div class="cd-wrap" style="padding:9px 0;font-size:11px;">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>@endif
</div>
