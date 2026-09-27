<div class="cinema cinema-page">
    @include('partials.cinema.page-hero', ['crumb' => $pageTitle, 'eyebrow' => 'Sahne arkası / Bilgi', 'title' => $pageTitle, 'description' => $pageDescription])
    <div class="cinema-wrap cinema-info"><article class="cinema-panel"><div class="cinema-prose">
        @if($content)
            {!! $content !!}
        @else
            @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
        @endif
    </div></article></div>
</div>
