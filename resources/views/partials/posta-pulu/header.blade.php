@php
    $ppHeaderName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $ppHeaderLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
    $ppHeaderInitial = mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'], mb_substr(trim($ppHeaderName), 0, 1)), 'UTF-8');
@endphp
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="pp-ico-phone" viewBox="0 0 24 24"><path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1Z"/></symbol>
    <symbol id="pp-ico-wa" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Zm-2.6 3.6c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.2-1.6-.3-.4-.6-.8-.8-1.2-.1-.2 0-.4.1-.5l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.8c-.1-.4-.3-.4-.5-.4h-.4Z"/></symbol>
    <symbol id="pp-ico-pin" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.4" fill="none" stroke="currentColor" stroke-width="2"/></symbol>
    <symbol id="pp-ico-arrow" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></symbol>
    <symbol id="pp-ico-send" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" d="M21 3 3 10.5l7 2.8L21 3Zm0 0-7.8 18-3.2-7.7"/></symbol>
    <symbol id="pp-ico-waves" viewBox="0 0 220 64"><g fill="none" stroke="currentColor" stroke-width="2.6" stroke-linecap="round"><path d="M2 8c14-8 22 8 36 0s22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0"/><path d="M2 24c14-8 22 8 36 0s22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0"/><path d="M2 40c14-8 22 8 36 0s22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0"/><path d="M2 56c14-8 22 8 36 0s22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0 22 8 36 0"/></g></symbol>
</svg>
<header class="pp-header">
    <div class="pp-header__stripe" aria-hidden="true"></div>
    <div class="pp-wrap pp-header__row">
        <a class="pp-brand" href="{{ route('home') }}" aria-label="{{ $ppHeaderName }} ana sayfa">
            @if($ppHeaderLogo)
                <img src="{{ asset('storage/'.$ppHeaderLogo) }}" alt="{{ $ppHeaderName }}" width="170" height="40">
            @else
                <span class="pp-brand__mark" aria-hidden="true"><span>{{ $ppHeaderInitial }}</span></span>
                <span class="pp-brand__name">{{ $ppHeaderName }}</span>
            @endif
        </a>
        <nav class="pp-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}" @if(request()->routeIs('companies.*')) aria-current="page" @endif>Firmalar</a>
            <a href="{{ route('home') }}#hizmetler">Hizmetler</a>
            <a href="{{ route('home') }}#sehirler">Şehirler</a>
            <a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Yazılar</a>
            <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
        </nav>
        <div class="pp-header__actions">
            @auth
                <a class="pp-btn pp-btn--line pp-btn--sm" href="{{ route('owner.dashboard') }}">Panelim</a>
            @else
                <a class="pp-btn pp-btn--red pp-btn--sm" href="{{ route('owner.register') }}"><span class="pp-cta-long">Pulunuzu yapıştırın</span><span class="pp-cta-short">Firma ekle</span></a>
            @endauth
            <button id="mobile-menu-btn" type="button" class="pp-menu" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
