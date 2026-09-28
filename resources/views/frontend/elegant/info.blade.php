<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => $pageTitle,
        'eyebrow' => 'Bilgi merkezi',
        'title' => $pageTitle,
    ])
    <div class="el-wrap">
        <article class="el-box" style="margin-top:34px;max-width:820px;margin-inline:auto">
            <div class="el-prose">
                @if($content)
                    {!! $content !!}
                @else
                    @foreach($fallbackParagraphs as $paragraph)<p>{{ $paragraph }}</p>@endforeach
                @endif
            </div>
            <p style="margin-top:34px"><a class="el-btn el-btn--ghost" href="{{ route('companies.index') }}">Firmalara göz atın</a></p>
        </article>
    </div>
</div>
