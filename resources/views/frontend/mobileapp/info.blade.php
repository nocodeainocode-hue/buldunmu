<div class="ap-sec">
    <div class="ap-sec__head"><h2>{{ $pageTitle }}</h2></div>
</div>
<div class="ap-prose">
    @if($content)
        {!! $content !!}
    @else
        @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
    @endif
</div>
<div style="padding:0 16px 16px"><a class="ap-btn ap-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz at</a></div>
