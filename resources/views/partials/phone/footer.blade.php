{{-- CEP KABUĞU · cihaz içi alt bilgi --}}
<footer class="ph-sheet" style="margin-top:18px">
    <div class="ph-sheet__head">
        <h2>{{ $directory?->name ?? config('app.name', 'Firma Rehberi') }}</h2>
        <a class="ph-section__more" href="{{ route('home') }}">Başa dön ↑</a>
    </div>
    <div class="ph-sheet__body">
        <div class="ph-chips" style="padding:0">
            <a class="ph-chip" href="{{ route('pages.about') }}">Hakkımızda</a>
            <a class="ph-chip" href="{{ route('packages.index') }}">Paketler</a>
            <a class="ph-chip" href="{{ route('pages.contact') }}">İletişim</a>
            <a class="ph-chip" href="{{ route('pages.privacy') }}">Gizlilik</a>
            <a class="ph-chip" href="{{ route('pages.terms') }}">Şartlar</a>
        </div>
        <p class="ph-copy">{{ $settings->homepage_subtitle ?? 'Şehrindeki firmaları tek dokunuşta keşfet.' }} · © {{ now()->year }}</p>
    </div>
</footer>
