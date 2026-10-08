<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => $pageTitle,
        'eyebrow' => 'Künye / Bilgi',
        'title' => $pageTitle,
        'description' => $pageDescription,
    ])
    <div class="kat-wrap" style="padding-top:44px;max-width:780px">
        <article class="kat-prose kat-prose--drop">
            @if($content)
                {!! $content !!}
            @else
                @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
            @endif
        </article>
        <p style="margin-top:40px"><a class="kat-btn kat-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz at →</a></p>
    </div>
</div>
