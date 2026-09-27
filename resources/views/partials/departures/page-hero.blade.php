@php
    $depEyebrow = $eyebrow ?? 'Kalkış panosu / Şehir rehberi';
@endphp
<section class="dep dep-page__hero">
    <div class="dep-wrap">
        <nav class="dep-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span>/</span>
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <span class="dep-kicker dep-kicker--light">{{ $depEyebrow }}</span>
        <h1 class="dep-display">{{ $title }}</h1>
        @if(!empty($description))<p>{{ $description }}</p>@endif
    </div>
</section>
