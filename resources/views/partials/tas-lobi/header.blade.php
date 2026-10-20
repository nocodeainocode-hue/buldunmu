@php
    $tlHeaderName = $directory->name ?? ($settings->site_name ?? 'Firma Rehberi');
    $tlHeaderLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
@endphp
<svg width="0" height="0" style="position:absolute" aria-hidden="true" focusable="false">
    <symbol id="tl-ico-phone" viewBox="0 0 24 24"><path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1Z"/></symbol>
    <symbol id="tl-ico-wa" viewBox="0 0 24 24"><path fill="currentColor" d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Zm-2.6 3.6c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.2-1.6-.3-.4-.6-.8-.8-1.2-.1-.2 0-.4.1-.5l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.8c-.1-.4-.3-.4-.5-.4h-.4Z"/></symbol>
    <symbol id="tl-ico-clock" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M12 7v5l3 2"/><circle cx="12" cy="12" r="8.5" fill="none" stroke="currentColor" stroke-width="1.8"/></symbol>
    <symbol id="tl-ico-plus" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" d="M12 5v14M5 12h14"/></symbol>
    <symbol id="tl-ico-pin" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round" d="M12 21s-6.5-5.6-6.5-11a6.5 6.5 0 0 1 13 0c0 5.4-6.5 11-6.5 11Z"/><circle cx="12" cy="10" r="2.4" fill="none" stroke="currentColor" stroke-width="1.8"/></symbol>
    <symbol id="tl-ico-arrow" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" d="M5 12h14M13 6l6 6-6 6"/></symbol>
    {{-- Marka: bronz çerçeveli levha, içinde baş harf yerine iki ince çizgi. --}}
    <symbol id="tl-mark" viewBox="0 0 40 40"><rect x="1.5" y="1.5" width="37" height="37" rx="3" fill="#f7f4ee" stroke="#9a7b4f" stroke-width="1.5"/><rect x="6.5" y="6.5" width="27" height="27" rx="1.5" fill="none" stroke="#9a7b4f" stroke-width="1"/><path d="M13 24V16h14M27 16v8" fill="none" stroke="#24272b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></symbol>
</svg>
<header class="tl-header">
    <div class="tl-wrap tl-header__row">
        <a class="tl-brand" href="{{ route('home') }}" aria-label="{{ $tlHeaderName }} ana sayfa">
            @if($tlHeaderLogo)
                <img src="{{ asset('storage/'.$tlHeaderLogo) }}" alt="{{ $tlHeaderName }}" width="170" height="40">
            @else
                <span class="tl-brand__mark" aria-hidden="true"><svg viewBox="0 0 40 40" width="38" height="38"><use href="#tl-mark"/></svg></span>
                <span class="tl-brand__name">{{ $tlHeaderName }}</span>
            @endif
        </a>
        <nav class="tl-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}" @if(request()->routeIs('companies.*')) aria-current="page" @endif>Firmalar</a>
            <a href="{{ route('home') }}#hizmetler">Hizmetler</a>
            <a href="{{ route('home') }}#sehirler">Şehirler</a>
            <a href="{{ route('blog.index') }}" @if(request()->routeIs('blog.*')) aria-current="page" @endif>Blog</a>
            <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
        </nav>
        <div class="tl-header__actions">
            @auth
                <a class="tl-btn tl-btn--line tl-btn--sm" href="{{ route('owner.dashboard') }}">Panelim</a>
            @else
                <a class="tl-btn tl-btn--ink tl-btn--sm" href="{{ route('owner.register') }}"><span class="tl-cta-long">Firmanızı ekleyin</span><span class="tl-cta-short">Firma ekle</span></a>
            @endauth
            <button id="mobile-menu-btn" type="button" class="tl-menu" aria-label="Menüyü aç" aria-controls="mobile-menu" aria-expanded="false">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="1.8" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
