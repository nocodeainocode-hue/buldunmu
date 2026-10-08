@php
    $bdName = $directory?->name ?? $settings->site_name ?? 'Firma Rehberi';
    $bdLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
    $bdChips = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->withCount('companies')->orderByDesc('companies_count')->take(14)->get();
@endphp
<header class="bd bd-header">
    <div class="bd-wrap bd-header__row">
        <a class="bd-brand" href="{{ route('home') }}" aria-label="{{ $bdName }} ana sayfa">
            @if($bdLogo)
                <img src="{{ asset('storage/'.$bdLogo) }}" alt="{{ $bdName }}" width="180" height="40">
            @else
                <span class="bd-brand__pin" aria-hidden="true"></span>{{ $bdName }}
            @endif
        </a>
        <form class="bd-find" action="{{ route('search') }}" method="GET" role="search">
            <label for="bd-q" class="bd-sr">Firma, hizmet veya şehir ara</label>
            <input id="bd-q" name="q" type="search" value="{{ request('q') }}" placeholder="Ne arıyorsun? Usta, kuaför, avukat…" autocomplete="off">
            <button type="submit">Ara</button>
        </form>
        <div class="bd-header__act">
            <a class="bd-header__link" href="{{ route('jobs.index') }}">İş ilanları</a>
            <a class="bd-header__link" href="{{ route('blog.index') }}">Yazılar</a>
            <a class="bd-btn bd-btn--hot bd-btn--sm" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a>
            <details class="bd-menu">
                <summary aria-label="Menüyü aç">☰</summary>
                <div class="bd-menu__panel">
                    <a href="{{ route('companies.index') }}">Tüm firmalar</a>
                    <a href="{{ route('jobs.index') }}">İş ilanları</a>
                    <a href="{{ route('blog.index') }}">Yazılar</a>
                    <a href="{{ route('packages.index') }}">Paketler</a>
                    <a href="{{ route('pages.about') }}">Hakkımızda</a>
                    <a href="{{ route('owner.dashboard') }}">Firma paneli</a>
                    <a href="{{ route('owner.register') }}">+ Ücretsiz ekle</a>
                </div>
            </details>
        </div>
    </div>
    <nav class="bd-chips" aria-label="Kategoriler">
        <div class="bd-wrap bd-chips__row">
            <a class="bd-chip bd-chip--all" href="{{ route('companies.index') }}">☰ Tümü</a>
            @foreach($bdChips as $chip)
                <a class="bd-chip" href="{{ route('categories.show', $chip->slug) }}">{{ $chip->icon ?? '' }} {{ $chip->name }}</a>
            @endforeach
        </div>
    </nav>
</header>
