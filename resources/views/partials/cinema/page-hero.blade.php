<section class="cinema-page__hero"><div class="cinema-wrap">
    <nav class="cinema-crumb" aria-label="Gezinme yolu"><a href="{{ route('home') }}">Ana sayfa</a><span>/</span><span>{{ $crumb ?? $title }}</span></nav>
    <span class="cinema-kicker">{{ $eyebrow ?? 'Sinematik Atlas / Keşif' }}</span>
    <h1 class="cinema-small-title">{{ $title }}</h1>
    @if(!empty($description))<p>{{ $description }}</p>@endif
</div></section>
