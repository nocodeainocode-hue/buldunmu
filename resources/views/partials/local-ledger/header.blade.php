<header class="yl-header">
    <div class="yl-wrap yl-header__row">
        <a class="yl-brand" href="{{ route('home') }}" aria-label="{{ $directory->name ?? 'Firma Rehberi' }} ana sayfa">
            @php $ledgerLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null); @endphp
            @if($ledgerLogo)
                <img src="{{ asset('storage/'.$ledgerLogo) }}" alt="{{ $directory->name ?? 'Firma Rehberi' }}" width="180" height="44">
            @else
                <span class="yl-brand__mark" aria-hidden="true">{{ mb_substr($directory->name ?? 'Y', 0, 1) }}</span>
                <strong>{{ $directory->name ?? 'Firma Rehberi' }}</strong>
            @endif
        </a>
        <nav class="yl-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}">Firmalar</a>
            <a href="{{ route('home') }}#hizmetler">Hizmetler</a>
            <a href="{{ route('home') }}#sehirler">Şehirler</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="{{ route('owner.dashboard') }}">Firma Paneli</a>
        </nav>
        <div class="yl-header__actions">
            <a class="yl-button yl-button--small" href="{{ route('owner.register') }}" aria-label="Ücretsiz Firma Ekle"><span class="yl-header__cta-long">Ücretsiz Firma Ekle</span><span class="yl-header__cta-short" aria-hidden="true">Firma Ekle</span><span aria-hidden="true">+</span></a>
            <button id="mobile-menu-btn" class="yl-menu" aria-label="Menüyü aç">
                <svg width="22" height="22" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="1.8" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
