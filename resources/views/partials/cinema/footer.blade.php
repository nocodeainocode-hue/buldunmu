<footer class="cinema cinema-footer">
    <div class="cinema-wrap cinema-footer__main">
        <div><h2>{{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }}.</h2><p>Şehrin işletmeleri, hizmetleri, hikâyeleri ve yeni fırsatları bir arada.</p></div>
        <div><h3>Keşfet</h3><a href="{{ route('companies.index') }}">Tüm firmalar</a><a href="{{ route('jobs.index') }}">İş ilanları</a><a href="{{ route('blog.index') }}">Yazılar</a><a href="{{ route('packages.index') }}">Premium paketler</a></div>
        <div><h3>Bilgi</h3><a href="{{ route('owner.register') }}">Firma ekle</a><a href="{{ route('pages.about') }}">Hakkımızda</a><a href="{{ route('pages.contact') }}">İletişim</a><a href="{{ route('pages.privacy') }}">Gizlilik</a><a href="{{ route('pages.terms') }}">Kullanım şartları</a></div>
    </div>
    <div class="cinema-wrap cinema-footer__bottom"><span>© {{ date('Y') }} {{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }}</span><span>Her işletmenin bir hikâyesi var.</span></div>
</footer>
