@php
    $depBrand = $directory?->name ?? $settings->site_name ?? 'Firma Rehberi';
    $depTicker = ($categories ?? collect())
        ->sortBy('name')
        ->take(10)
        ->pluck('name');
    if ($depTicker->isEmpty()) {
        $depTicker = \App\Models\Category::active()
            ->visibleForDirectory($directory ?? null)
            ->orderBy('name')
            ->take(10)
            ->pluck('name');
    }
    if ($depTicker->isEmpty()) {
        $depTicker = collect(['Firmalar', 'Hizmetler', 'Şehirler', 'İş ilanları']);
    }
@endphp
<div class="dep dep-header">
    <div class="dep-header__strip">
        <div class="dep-wrap">
            <span>Peron rehberi / {{ $depBrand }}</span>
            <span>Canlı pano <b>{{ now()->format('d.m.Y') }} · {{ now()->format('H:i') }}</b></span>
            <a href="{{ route('owner.dashboard') }}">Firma paneli →</a>
        </div>
    </div>
    <div class="dep-wrap dep-header__main">
        <a class="dep-brand" href="{{ route('home') }}">
            <span class="dep-brand__mark" aria-hidden="true">P</span>
            <span class="dep-brand__text">{{ $depBrand }}<span>.</span>
                <small class="dep-brand__sub">Kalkış panosu / şehir rehberi</small>
            </span>
        </a>
        <nav class="dep-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}">Firmalar</a>
            <a href="{{ route('jobs.index') }}">İş ilanları</a>
            <a href="{{ route('blog.index') }}">Yazılar</a>
            <a href="{{ route('packages.index') }}">Paketler</a>
            <a class="dep-nav__cta" href="{{ route('owner.register') }}">+ Firma ekle</a>
        </nav>
    </div>
    <div class="dep-ticker" aria-hidden="true">
        <span class="dep-ticker__label">Sefer bantı</span>
        <div class="dep-ticker__track">
            @for($group = 0; $group < 2; $group++)
                <span>
                    @foreach($depTicker as $tickerItem)
                        <i>◆</i> {{ $tickerItem }}
                    @endforeach
                </span>
            @endfor
        </div>
    </div>
</div>
