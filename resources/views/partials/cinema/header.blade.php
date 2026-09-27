<div class="cinema cinema-header">
    <div class="cinema-header__top">
        <div class="cinema-wrap"><span>Yerel keşif / Her işletmenin bir hikâyesi var</span><a href="{{ route('owner.dashboard') }}">Firma paneli ↗</a></div>
    </div>
    <div class="cinema-wrap cinema-header__main">
        <a class="cinema-brand" href="{{ route('home') }}">{{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }}<span>.</span><small>Şehrin sahnesi burada</small></a>
        <nav class="cinema-nav" aria-label="Ana menü">
            <a href="{{ route('companies.index') }}">Firmalar</a>
            <a href="{{ route('jobs.index') }}">İş ilanları</a>
            <a href="{{ route('blog.index') }}">Yazılar</a>
            <a href="{{ route('owner.register') }}">+ Firma ekle</a>
        </nav>
    </div>
</div>
<div class="cinema cinema-film" aria-hidden="true"><div class="cinema-film__track">@for($i=0;$i<8;$i++)<span>KEŞFET ◆ YAKININDA ◆ ŞEHRİN SAHNESİ ◆</span>@endfor</div></div>
