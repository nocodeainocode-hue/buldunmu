@php
    $cnHeaderName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $cnHeaderLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
@endphp
{{-- İznik motifleri bir kez tanımlanır, sayfada <use> ile tekrar kullanılır (görsel dosyası yok).
     Renkler <use> öğesinden CSS değişkenleriyle gelir: --cn-a (lacivert), --cn-b (turkuaz), --cn-c (mercan), --cn-l (yaprak). --}}
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <defs>
        <path id="cn-pet" d="M0-13C10-22 10-37 0-49C-10-37-10-22 0-13Z"/>
        <path id="cn-pet-in" d="M0-21C3.6-27 3.6-35 0-41C-3.6-35-3.6-27 0-21Z"/>
        <path id="cn-bud" d="M0-15C7-18 9-26 6.5-34L2.5-29.5 0-36-2.5-29.5-6.5-34C-9-26-7-18 0-15Z"/>
        <path id="cn-dot" d="M0-4C2.6-6.5 2.6-9 0-11C-2.6-9-2.6-6.5 0-4Z"/>
    </defs>
    {{-- Hatayi gülü: dört uzun saz yaprağı, aralarda dört lale goncası, ortada küçük çiçek. --}}
    <symbol id="cn-rozet" viewBox="-50 -50 100 100">
        <g style="fill:var(--cn-a,#1d3a7a)"><use href="#cn-pet"/><use href="#cn-pet" transform="rotate(90)"/><use href="#cn-pet" transform="rotate(180)"/><use href="#cn-pet" transform="rotate(270)"/></g>
        <g style="fill:var(--cn-in,#fffdf7)"><use href="#cn-pet-in"/><use href="#cn-pet-in" transform="rotate(90)"/><use href="#cn-pet-in" transform="rotate(180)"/><use href="#cn-pet-in" transform="rotate(270)"/></g>
        <g style="fill:var(--cn-b,#1f8a8c)"><use href="#cn-bud" transform="rotate(45)"/><use href="#cn-bud" transform="rotate(135)"/><use href="#cn-bud" transform="rotate(225)"/><use href="#cn-bud" transform="rotate(315)"/></g>
        <circle r="12.5" style="fill:var(--cn-a,#1d3a7a)"/>
        <g style="fill:var(--cn-in,#fffdf7)"><use href="#cn-dot" transform="rotate(45)"/><use href="#cn-dot" transform="rotate(135)"/><use href="#cn-dot" transform="rotate(225)"/><use href="#cn-dot" transform="rotate(315)"/></g>
        <circle r="3.2" style="fill:var(--cn-c,#c8402e)"/>
    </symbol>
    {{-- Lale: üç taç yaprak, sap ve iki kıvrık yaprak. --}}
    <symbol id="cn-lale" viewBox="0 0 40 56">
        <path style="fill:var(--cn-l,#1f8a8c)" d="M20 52C12 48 6 40 4.5 31C11 35 17 41 20 52ZM20 47C28 43 34 36 35.5 28C29 31.5 23 37 20 47Z"/>
        <path style="fill:var(--cn-l,#1f8a8c)" d="M19.1 27h1.8v27h-1.8z"/>
        <path style="fill:var(--cn-a,#1d3a7a)" d="M20 29C10.5 28 6.5 19 8.5 8.5C12 15 16 21 20 29ZM20 29C29.5 28 33.5 19 31.5 8.5C28 15 24 21 20 29Z"/>
        <path style="fill:var(--cn-c,#c8402e)" d="M20 3C25.5 9.5 26.5 20 20 29C13.5 20 14.5 9.5 20 3Z"/>
    </symbol>
    {{-- Karanfil: dişli yelpaze taç, çanak, sap. --}}
    <symbol id="cn-karanfil" viewBox="0 0 40 56">
        <path style="fill:var(--cn-l,#1f8a8c)" d="M19.1 33h1.8v21h-1.8zM20 50C13 47 8 41 6.5 34C12 37 17 42 20 50ZM20 46C27 43 32 37 33.5 30C28 33 23 38 20 46Z"/>
        <path style="fill:var(--cn-a,#1d3a7a)" d="M20 30L7 14C6 9.5 9.5 7.5 12 10C12 5.5 15.5 4.5 16.5 8C17.5 3.5 19 2.5 20 6C21 2.5 22.5 3.5 23.5 8C24.5 4.5 28 5.5 28 10C30.5 7.5 34 9.5 33 14Z"/>
        <path style="fill:var(--cn-c,#c8402e)" d="M20 26L13.5 15.5C15.5 13 18 13.5 20 16C22 13.5 24.5 13 26.5 15.5Z"/>
        <path style="fill:var(--cn-b,#1f8a8c)" d="M14.5 29h11l-2.5 6h-6z"/>
    </symbol>
    {{-- Saz yaprağı: kıvrık, kenarı dişli uzun yaprak. --}}
    <symbol id="cn-saz" viewBox="0 0 64 28">
        <path style="fill:var(--cn-l,#1f8a8c)" d="M2 24C10 10 26 3 44 3C51 3 57 5 62 8L56 9.5 59 12 52.5 12.5 54.5 16 47.5 15.5 48.5 19 42 17.5 41.5 21 36 18.5C26 20 14 22 2 24Z"/>
        <path style="fill:none;stroke:var(--cn-in,#fffdf7);stroke-width:1.4;stroke-linecap:round" d="M8 21C20 13 36 8 56 8.5"/>
    </symbol>
</svg>
<header class="cn-header">
    <div class="cn-wrap cn-header__row">
        <a class="cn-brand" href="{{ route('home') }}" aria-label="{{ $cnHeaderName }} ana sayfa">
            @if($cnHeaderLogo)
                <img src="{{ asset('storage/'.$cnHeaderLogo) }}" alt="{{ $cnHeaderName }}" width="170" height="40">
            @else
                <span class="cn-mark" aria-hidden="true"><svg viewBox="0 0 40 40" width="40" height="40"><use href="#cn-rozet" x="-20" y="-20" width="40" height="40"/><use href="#cn-rozet" x="20" y="-20" width="40" height="40"/><use href="#cn-rozet" x="-20" y="20" width="40" height="40"/><use href="#cn-rozet" x="20" y="20" width="40" height="40"/><circle cx="20" cy="20" r="4" fill="#1f8a8c"/></svg></span>
                <span class="cn-brand__name">{{ $cnHeaderName }}</span>
            @endif
        </a>
        <nav class="cn-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}" @if(request()->routeIs('companies.*')) aria-current="page" @endif>Firmalar</a>
            <a href="{{ route('home') }}#karolar">Hizmetler</a>
            <a href="{{ route('home') }}#sehirler">Şehirler</a>
            <a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a>
            <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
        </nav>
        <div class="cn-header__actions">
            @auth
                <a class="cn-btn cn-btn--line cn-btn--sm" href="{{ route('owner.dashboard') }}">Panelim</a>
            @else
                <a class="cn-btn cn-btn--coral cn-btn--sm" href="{{ route('owner.register') }}"><span class="cn-cta-long">Firmanızı ekleyin</span><span class="cn-cta-short">Firma ekle</span></a>
            @endauth
            <button id="mobile-menu-btn" type="button" class="cn-menu" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
