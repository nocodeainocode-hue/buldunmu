<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => $pageTitle,
        'eyebrow' => 'Servis bürosu / Bilgi',
        'title' => $pageTitle,
        'description' => $pageDescription,
    ])
    <div class="dep-wrap dep-info">
        <article class="dep-panel">
            <div class="dep-prose" style="margin-top:0;padding-top:0;border-top:0">
                @if($content)
                    {!! $content !!}
                @else
                    @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                @endif
            </div>
            <p style="margin-top:26px"><a class="dep-btn dep-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz at →</a></p>
        </article>
    </div>
</div>
