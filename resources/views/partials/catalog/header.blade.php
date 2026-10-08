@php
    $katName = $directory?->name ?? $settings->site_name ?? 'Firma Rehberi';
    $katLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
    $katMonths = ['Ocak', 'Şubat', 'Mart', 'Nisan', 'Mayıs', 'Haziran', 'Temmuz', 'Ağustos', 'Eylül', 'Ekim', 'Kasım', 'Aralık'];
    $katDate = now()->format('j').' '.$katMonths[now()->month - 1].' '.now()->format('Y');
@endphp
<header class="kat kat-header">
    <div class="kat-header__strip">
        <div class="kat-wrap">
            <span>{{ $katDate }}</span>
            <span>Yerel işletme kataloğu</span>
            <a href="{{ route('owner.dashboard') }}">Firma paneli ↗</a>
        </div>
    </div>
    <div class="kat-wrap kat-header__main">
        <a class="kat-brand" href="{{ route('home') }}" aria-label="{{ $katName }} ana sayfa">
            @if($katLogo)
                <img src="{{ asset('storage/'.$katLogo) }}" alt="{{ $katName }}" width="200" height="44">
            @else
                {{ $katName }}<i>.</i>
            @endif
        </a>
        <nav class="kat-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}">Firmalar</a>
            <a href="{{ route('blog.index') }}">Yazılar</a>
            <a href="{{ route('jobs.index') }}">İş ilanları</a>
            <a href="{{ route('packages.index') }}">Paketler</a>
            <a href="{{ route('pages.about') }}">Hakkımızda</a>
        </nav>
        <div class="kat-header__cta">
            <a class="kat-link" href="{{ route('search') }}">Ara</a>
            <a class="kat-btn" href="{{ route('owner.register') }}">Firma ekle</a>
            <details class="kat-menu">
                <summary aria-label="Menüyü aç">☰</summary>
                <div class="kat-menu__panel">
                    <form action="{{ route('search') }}" method="GET" role="search">
                        <input type="search" name="q" placeholder="Firma, hizmet veya şehir ara" aria-label="Ara">
                    </form>
                    <a href="{{ route('companies.index') }}">Firmalar <span>→</span></a>
                    <a href="{{ route('blog.index') }}">Yazılar <span>→</span></a>
                    <a href="{{ route('jobs.index') }}">İş ilanları <span>→</span></a>
                    <a href="{{ route('packages.index') }}">Paketler <span>→</span></a>
                    <a href="{{ route('pages.about') }}">Hakkımızda <span>→</span></a>
                    <a href="{{ route('owner.register') }}">Firma ekle <span>→</span></a>
                </div>
            </details>
        </div>
    </div>
</header>
