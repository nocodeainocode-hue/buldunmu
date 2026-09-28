<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => $pageTitle,
        'eyebrow' => 'Bilgi merkezi',
        'title' => $pageTitle,
    ])
    <div class="ib-wrap">
        <article class="ib-box" style="margin-top:14px;max-width:860px;margin-inline:auto">
            <div class="ib-box__body">
                <div class="ib-prose">
                    @if($content)
                        {!! $content !!}
                    @else
                        @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                    @endif
                </div>
                <p style="margin-top:22px"><a class="ib-btn ib-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz at</a></p>
            </div>
        </article>
    </div>
</div>
