{{-- Sayfa başlığı: $crumb, $eyebrow, $title, $description, isteğe bağlı $folio --}}
<section class="kat-band">
    <div class="kat-wrap">
        <nav class="kat-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span>/</span>
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <div class="kat-band__grid">
            <div>
                <span class="kat-kicker">{{ $eyebrow ?? 'Katalog' }}</span>
                <h1>{{ $title }}</h1>
                @if(!empty($description))<p class="kat-band__lede">{{ $description }}</p>@endif
            </div>
            @if(!empty($folio))<div class="kat-band__folio" aria-hidden="true">{{ $folio }}</div>@endif
        </div>
    </div>
</section>
