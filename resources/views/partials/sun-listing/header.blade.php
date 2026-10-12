<header class="sl-header">
    <div class="sl-wrap sl-header__row">
        <a class="sl-brand" href="{{ route('home') }}" aria-label="{{ $directory->name ?? 'Firma Rehberi' }} ana sayfa">
            @php $sunLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null); @endphp
            @if($sunLogo)
                <img src="{{ asset('storage/'.$sunLogo) }}" alt="{{ $directory->name ?? 'Firma Rehberi' }}" width="170" height="40">
            @else
                <span class="sl-brand__mark" aria-hidden="true">{{ mb_substr($directory->name ?? 'F', 0, 1) }}</span>
                <span>{{ $directory->name ?? 'Firma Rehberi' }}</span>
            @endif
        </a>
        <nav class="sl-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}">Firmalar</a>
            <a href="{{ route('home') }}#kategoriler">Kategoriler</a>
            <a href="{{ route('home') }}#nasil">Nasıl çalışır?</a>
            <a href="{{ route('blog.index') }}">Blog</a>
            <a href="{{ route('owner.dashboard') }}">Firma Paneli</a>
        </nav>
        <div class="sl-header__actions">
            <a class="sl-pill" href="{{ route('owner.register') }}" aria-label="Ücretsiz Firma Ekle"><span aria-hidden="true">+</span><span class="sl-header__cta-long">Ücretsiz Firma Ekle</span><span class="sl-header__cta-short" aria-hidden="true">Firma Ekle</span></a>
            <button id="mobile-menu-btn" class="sl-menu" aria-label="Menüyü aç">
                <svg width="24" height="24" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true"><path stroke-width="2" stroke-linecap="round" d="M4 7h16M4 12h16M4 17h16"/></svg>
            </button>
        </div>
    </div>
</header>
