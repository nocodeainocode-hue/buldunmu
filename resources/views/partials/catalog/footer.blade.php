@php
    $katFootName = $directory?->name ?? $settings->site_name ?? 'Firma Rehberi';
    $katFootCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->withCount('companies')->orderByDesc('companies_count')->take(6)->get();
@endphp
<footer class="kat kat-footer">
    <div class="kat-wrap kat-footer__grid">
        <div>
            <a class="kat-footer__brand" href="{{ route('home') }}">{{ $katFootName }}<i>.</i></a>
            <p class="kat-footer__about">{{ $directory?->meta_description ?? $settings->meta_description ?? 'Şehrinizdeki işletmeleri kategori, şehir ve yorumlarla keşfedin.' }}</p>
        </div>
        <div>
            <h3>Katalog</h3>
            <ul>
                <li><a href="{{ route('companies.index') }}">Tüm firmalar</a></li>
                <li><a href="{{ route('blog.index') }}">Yazılar</a></li>
                <li><a href="{{ route('jobs.index') }}">İş ilanları</a></li>
                <li><a href="{{ route('packages.index') }}">Üyelik paketleri</a></li>
            </ul>
        </div>
        <div>
            <h3>Dizin</h3>
            <ul>
                @foreach($katFootCategories as $cat)
                    <li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>
                @endforeach
            </ul>
        </div>
        <div>
            <h3>Künye</h3>
            <ul>
                <li><a href="{{ route('pages.about') }}">Hakkımızda</a></li>
                <li><a href="{{ route('pages.contact') }}">İletişim</a></li>
                <li><a href="{{ route('pages.privacy') }}">Gizlilik politikası</a></li>
                <li><a href="{{ route('pages.terms') }}">Kullanım şartları</a></li>
                <li><a href="{{ route('owner.register') }}">Firma ekle</a></li>
            </ul>
        </div>
    </div>
    <div class="kat-footer__word" aria-hidden="true">{{ $katFootName }}</div>
    <div class="kat-wrap kat-footer__legal">
        <span>© {{ date('Y') }} {{ $katFootName }}</span>
        <span>Her işletmenin bir sayfası var.</span>
    </div>
</footer>
