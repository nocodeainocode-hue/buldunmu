<div class="bd bd-page">
    <div class="bd-wrap" style="max-width:820px">
        @include('partials.board.band', [
            'crumb' => $pageTitle,
            'tag' => 'Bilgi',
            'title' => $pageTitle,
            'description' => $pageDescription,
        ])
        <article class="bd-panel">
            <div class="bd-prose">
                @if($content){!! $content !!}@else @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach @endif
            </div>
            <p style="margin-top:26px"><a class="bd-btn bd-btn--ghost" href="{{ route('companies.index') }}">İlanlara göz at →</a></p>
        </article>
    </div>
</div>
