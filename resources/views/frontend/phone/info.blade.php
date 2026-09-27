{{-- CEP · bilgi sayfaları (hakkımızda, gizlilik, kullanım şartları) --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>{{ $pageTitle }}</span>
    </nav>
    <span class="ph-eyebrow">Bilgi · Servis</span>
    <h1>{{ $pageTitle }}</h1>
    <p>{{ $pageDescription }}</p>
</div>

<article class="ph-sheet">
    <div class="ph-prose" style="padding:16px">
        @if(!empty($content))
            {!! $content !!}
        @else
            @foreach($fallbackParagraphs ?? [] as $paragraph)<p>{{ $paragraph }}</p>@endforeach
        @endif
    </div>
</article>

<div class="ph-cta">
    <h2>Aklına takılan bir şey mi var?</h2>
    <p>İletişim formundan yaz, ekibiz sana dönsün.</p>
    <a class="ph-btn" href="{{ route('pages.contact') }}">İletişim →</a>
</div>
