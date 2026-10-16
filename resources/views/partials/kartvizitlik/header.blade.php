@php
    $kvHeaderName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $kvHeaderLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
@endphp
<header class="kv-header">
    <div class="kv-wrap kv-header__row">
        <a class="kv-brand" href="{{ route('home') }}" aria-label="{{ $kvHeaderName }} ana sayfa">
            @if($kvHeaderLogo)
                <img src="{{ asset('storage/'.$kvHeaderLogo) }}" alt="{{ $kvHeaderName }}" width="160" height="36">
            @else
                <span class="kv-mark" aria-hidden="true"><i></i><i></i></span>
                <span class="kv-brand__name">{{ $kvHeaderName }}</span>
            @endif
        </a>
        <nav class="kv-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}" @if(request()->routeIs('companies.*')) aria-current="page" @endif>Firmalar</a>
            <a href="{{ route('home') }}#cekmeceler">Çekmeceler</a>
            <a href="{{ route('home') }}#sehirler">Şehirler</a>
            <a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a>
            <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
        </nav>
        <div class="kv-header__actions">
            @auth
                <a class="kv-btn kv-btn--line kv-btn--sm" href="{{ route('owner.dashboard') }}">Kartım</a>
            @else
                <a class="kv-btn kv-btn--red kv-btn--sm" href="{{ route('owner.register') }}"><span class="kv-cta-long">Kartını bırak</span><span class="kv-cta-short">Kart ekle</span></a>
            @endauth
            <button id="mobile-menu-btn" type="button" class="kv-menu" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
