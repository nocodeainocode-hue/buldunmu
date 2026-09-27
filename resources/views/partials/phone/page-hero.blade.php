{{-- CEP KABUĞU · alt sayfa başlığı --}}
<section class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a>
        <span>/</span>
        <span>{{ $crumb ?? 'Sayfa' }}</span>
    </nav>
    <span class="ph-eyebrow">{{ $eyebrow ?? 'Rehber' }}</span>
    <h1>{{ $title ?? '' }}</h1>
    @if(!empty($description))<p>{{ $description }}</p>@endif
    @if(!empty($meta))
        <div class="ph-meta" style="margin-top:10px">
            @foreach($meta as $item)<span>{{ $item }}</span>@endforeach
        </div>
    @endif
</section>
