@php
    $tcHeaderName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $tcHeaderLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
@endphp
<header class="tc-header">
    <div class="tc-header__board tc-bolts">
        <div class="tc-wrap tc-header__row">
            <a class="tc-brand" href="{{ route('home') }}" aria-label="{{ $tcHeaderName }} ana sayfa">
                @if($tcHeaderLogo)
                    <span class="tc-brand__logo"><img src="{{ asset('storage/'.$tcHeaderLogo) }}" alt="" width="120" height="32"></span>
                @endif
                <span class="tc-brand__name tc-paint">{{ $tcHeaderName }}</span>
            </a>
            <nav class="tc-nav" aria-label="Ana menü">
                <a href="{{ route('companies.index') }}" @if(request()->routeIs('companies.*')) aria-current="page" @endif>Firmalar</a>
                <a href="{{ route('home') }}#tc-sokaklar">Meslekler</a>
                <a href="{{ route('home') }}#tc-mahalleler">Şehirler</a>
                <a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a>
                <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
            </nav>
            <div class="tc-header__actions">
                @if(request()->routeIs('home'))
                    <a class="tc-ghost" href="#tc-ara">Esnafa sor</a>
                @endif
                @auth
                    <a class="tc-btn tc-btn--mustard tc-btn--sm" href="{{ route('owner.dashboard') }}">Dükkânım</a>
                @else
                    <a class="tc-btn tc-btn--mustard tc-btn--sm" href="{{ route('owner.register') }}" aria-label="Tabelanı as: firmanı ücretsiz ekle">Tabelanı as</a>
                @endauth
                <button id="mobile-menu-btn" type="button" class="tc-menu" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                    <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2.2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
                </button>
            </div>
        </div>
    </div>
    <div class="tc-awn" aria-hidden="true"><div class="tc-tente"></div></div>
</header>
