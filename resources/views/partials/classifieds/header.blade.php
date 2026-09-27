<div class="board-shell">
    <div class="bs-topline"><div class="bs-wrap"><span>Yerel keşif ağı / {{ now()->translatedFormat('d F Y') }}</span><a href="{{ route('owner.dashboard') }}">Firma paneli ↗</a></div></div>
    <header class="bs-mainhead"><div class="bs-wrap">
        <div><a class="bs-brand" href="{{ route('home') }}">{{ $directory?->name ?? $settings->site_name ?? 'Mahalle' }} <em>panosu.</em></a><p class="bs-sub">İşletmeler, hizmetler, işler ve yerel bağlantılar tek panoda.</p></div>
        <nav class="bs-nav" aria-label="Pano bağlantıları"><a href="{{ route('companies.index') }}">Tüm firmalar</a><a href="{{ route('jobs.index') }}">İş ilanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('owner.register') }}">+ Firma ekle</a></nav>
        <form class="bs-search" action="{{ route('search') }}" method="GET"><input name="q" value="{{ request()->routeIs('search') ? request('q') : '' }}" aria-label="Firma, hizmet veya şehir ara" placeholder="Firma, hizmet, kategori veya şehir ara…" required><button type="submit">Panoda ara →</button></form>
    </div></header>
</div>
