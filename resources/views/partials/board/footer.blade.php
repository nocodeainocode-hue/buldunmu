@php
    $bdFootName = $directory?->name ?? $settings->site_name ?? 'Firma Rehberi';
    $bdFootCats = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->withCount('companies')->orderByDesc('companies_count')->take(6)->get();
@endphp
<footer class="bd bd-footer">
    <div class="bd-wrap bd-footer__grid">
        <div>
            <a class="bd-brand" href="{{ route('home') }}"><span class="bd-brand__pin" aria-hidden="true"></span>{{ $bdFootName }}</a>
            <p class="bd-footer__about">{{ $directory?->meta_description ?? $settings->meta_description ?? 'Mahalledeki işletmeleri, ustaları ve fırsatları tek panoda bul.' }}</p>
        </div>
        <div>
            <h3>Pano</h3>
            <ul>
                <li><a href="{{ route('companies.index') }}">Tüm firmalar</a></li>
                <li><a href="{{ route('jobs.index') }}">İş ilanları</a></li>
                <li><a href="{{ route('blog.index') }}">Yazılar</a></li>
                <li><a href="{{ route('packages.index') }}">Paketler</a></li>
            </ul>
        </div>
        <div>
            <h3>Kategoriler</h3>
            <ul>@foreach($bdFootCats as $cat)<li><a href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a></li>@endforeach</ul>
        </div>
        <div>
            <h3>Bilgi</h3>
            <ul>
                <li><a href="{{ route('pages.about') }}">Hakkımızda</a></li>
                <li><a href="{{ route('pages.contact') }}">İletişim</a></li>
                <li><a href="{{ route('pages.privacy') }}">Gizlilik</a></li>
                <li><a href="{{ route('pages.terms') }}">Kullanım şartları</a></li>
            </ul>
        </div>
    </div>
    <div class="bd-wrap bd-footer__legal"><span>© {{ date('Y') }} {{ $bdFootName }}</span><span>Mahallenin panosu, herkesin ilanı.</span></div>
</footer>
