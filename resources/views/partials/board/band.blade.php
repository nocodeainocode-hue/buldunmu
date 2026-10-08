{{-- $crumb, $title, $description, $tag --}}
<section class="bd-band">
    <div class="bd-wrap">
        <nav class="bd-crumb" aria-label="Gezinme yolu">
            <a href="{{ route('home') }}">Pano</a><span>›</span><span>{{ $crumb ?? $title }}</span>
        </nav>
        @if(!empty($tag))<span class="bd-badge bd-badge--pin bd-band__tag">{{ $tag }}</span>@endif
        <h1>{{ $title }}</h1>
        @if(!empty($description))<p class="bd-band__lede">{{ $description }}</p>@endif
    </div>
</section>
