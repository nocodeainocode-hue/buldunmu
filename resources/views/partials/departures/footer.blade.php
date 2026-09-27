<div class="dep dep-hazard" aria-hidden="true"></div>
<footer class="dep dep-footer">
    <div class="dep-wrap dep-footer__main">
        <div>
            <h2>{{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }}</h2>
            <p>{{ $directory?->meta_description ?: ($settings->meta_description ?? 'Şehrindeki işletmeleri, hizmetleri ve fırsatları tek panoda toplayan yerel rehber.') }}</p>
        </div>
        <div>
            <h3>İniş biniş</h3>
            <a href="{{ route('companies.index') }}">Tüm firmalar</a>
            <a href="{{ route('jobs.index') }}">İş ilanları</a>
            <a href="{{ route('blog.index') }}">Şehir yazıları</a>
            <a href="{{ route('packages.index') }}">Premium paketler</a>
        </div>
        <div>
            <h3>Sayaç &amp; bilgi</h3>
            <a href="{{ route('owner.register') }}">Firma ekle</a>
            <a href="{{ route('pages.about') }}">Hakkımızda</a>
            <a href="{{ route('pages.contact') }}">İletişim</a>
            <a href="{{ route('pages.privacy') }}">Gizlilik politikası</a>
            <a href="{{ route('pages.terms') }}">Kullanım şartları</a>
        </div>
    </div>
    <div class="dep-wrap dep-footer__bottom">
        <span>© {{ date('Y') }} {{ $directory?->name ?? $settings->site_name ?? 'Firma Rehberi' }} · Tüm hakları saklıdır.</span>
        <span>Pano bilgileri halka açık kaynaklardan derlenir.</span>
    </div>
</footer>
