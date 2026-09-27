<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => $pageTitle,
        'eyebrow' => 'Bilgi merkezi / Rehber',
        'title' => $pageTitle,
        'description' => $pageDescription,
    ])
    <div class="sig-wrap" style="padding-top:26px;max-width:820px">
        <article class="sig-panel">
            <div class="sig-panel__body">
                <div class="sig-prose">
                    @if($content)
                        {!! $content !!}
                    @else
                        @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                    @endif
                </div>
                <p style="margin-top:26px"><a class="sig-btn sig-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz at →</a></p>
            </div>
        </article>
    </div>
</div>
