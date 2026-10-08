@php
    $bdContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => \Illuminate\Support\Str::limit($post->title, 42),
            'tag' => $bdContentType,
            'title' => $post->title,
            'description' => $post->excerpt,
        ])
        <div class="bd-cols">
            <div>
                <article class="bd-panel">
                    <p class="bd-muted" style="font-size:13px;margin-bottom:16px">
                        {{ $post->published_at?->format('d.m.Y') }}
                        {{ $post->author_name ? ' · Kalem: '.$post->author_name : '' }}
                        {{ $post->reviewer_name ? ' · Kontrol: '.$post->reviewer_name : '' }}
                    </p>
                    @if($post->image)
                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:14px;margin-bottom:22px">
                    @endif
                    @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
                        <div class="bd-prose">
                            @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                            @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                        </div>
                    @endif
                    <div class="blog-prose bd-prose">{!! $post->content !!}</div>
                    @if(!empty($post->faq_items))
                        <div class="bd-prose">
                            <h2>Sık sorulan sorular</h2>
                            @foreach($post->faq_items as $faq)
                                <details class="td-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($post->sources))
                        <div class="bd-prose"><h2>Kaynaklar</h2><ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol></div>
                    @endif
                    <div style="margin-top:26px;padding-top:16px;border-top:1px dashed var(--border)">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
                </article>
                @if($relatedPosts->isNotEmpty())
                    <section class="bd-box" style="margin-top:22px"><div class="bd-box__head"><h2>İlgili yazılar</h2></div><div class="bd-links">@foreach($relatedPosts as $related)<a href="{{ route('blog.show', $related->slug) }}"><span class="bd-links__txt">{{ $related->title }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                @endif
            </div>
            <aside class="bd-side">
                @if(($targetCity ?? null) || ($targetCategory ?? null))
                    <section class="bd-box"><div class="bd-box__head"><h2>Bu yazının konusu</h2></div><div class="bd-links">
                        @if($targetCategory)<a href="{{ route('categories.show', $targetCategory->slug) }}"><span class="bd-links__txt">{{ $targetCategory->name }} ilanları</span><span class="bd-links__n">→</span></a>@endif
                        @if($targetCity)<a href="{{ route('cities.show', $targetCity->slug) }}"><span class="bd-links__txt">{{ $targetCity->name }} ilanları</span><span class="bd-links__n">→</span></a>@endif
                    </div></section>
                @endif
                <div class="bd-cta"><h2>Şimdi ara</h2><p>Yazıyı okudun; uygun işletmeyi panoda bul.</p><a class="bd-btn" href="{{ route('companies.index') }}">İlanlara git</a></div>
            </aside>
        </div>
    </div>
</div>
