@php
    $sigEyebrow = $eyebrow ?? 'Sinyal istasyonu / Keşif noktası';
@endphp
<section class="sig-band">
    <div class="sig-wrap">
        <nav class="sig-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span class="sig-crumb__sep">/</span>
            <span>{{ $crumb ?? $title }}</span>
        </nav>
        <span class="sig-kicker">{{ $sigEyebrow }}</span>
        <h1 class="sig-display">{{ $title }}</h1>
        @if(!empty($description))<p class="sig-lede">{{ $description }}</p>@endif
    </div>
</section>
