@php
    $depContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => \Illuminate\Support\Str::limit($post->title, 42),
        'eyebrow' => 'Yolculuk notu / '.$depContentType,
        'title' => $post->title,
        'description' => $post->excerpt,
    ])
    <div class="dep-wrap dep-page__body">
        <div class="dep-columns">
            <div class="dep-main">
                <article class="dep-panel dep-article">
                    <p class="dep-code" style="color:var(--amber-deep)">
                        {{ $post->published_at?->format('d.m.Y') }}
                        {{ $post->author_name ? ' · Kalem: '.$post->author_name : '' }}
                        {{ $post->reviewer_name ? ' · Kontrol: '.$post->reviewer_name : '' }}
                    </p>
                    @if($post->image)
                        <img class="dep-article__image" src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager">
                    @endif
                    @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
                        <div class="dep-prose">
                            @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                            @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                        </div>
                    @endif
                    <div class="blog-prose dep-prose">{!! $post->content !!}</div>
                    @if(!empty($post->faq_items))
                        <div class="dep-prose">
                            <h2>Sık sorulan sorular</h2>
                            @foreach($post->faq_items as $faq)
                                <details class="dep-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($post->sources))
                        <div class="dep-prose">
                            <h2>Kaynaklar</h2>
                            <ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
                        </div>
                    @endif
                    <div class="dep-article__foot">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
                </article>
                @if($relatedPosts->isNotEmpty())
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>İlgili yazılar</h2><span class="dep-code">Devamı</span></div>
                        <div class="dep-link-list">@foreach($relatedPosts as $related)<a href="{{ route('blog.show', $related->slug) }}"><span>{{ $related->title }}</span><b>→</b></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="dep-side">
                @if(($targetCity ?? null) || ($targetCategory ?? null))
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>Bu yazının hattı</h2><span class="dep-code">Bağlantı</span></div>
                        <div class="dep-link-list">
                            @if($targetCategory)<a href="{{ route('categories.show', $targetCategory->slug) }}"><span>{{ $targetCategory->name }} firmaları</span><b>→</b></a>@endif
                            @if($targetCity)<a href="{{ route('cities.show', $targetCity->slug) }}"><span>{{ $targetCity->name }} tarifesi</span><b>→</b></a>@endif
                        </div>
                    </section>
                @endif
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Keşfe devam</span>
                    <h2>Panodaki firmalar</h2>
                    <p>Yazıyı okudunuz; şimdi uygun işletmeyi seçin.</p>
                    <a class="dep-btn" href="{{ route('companies.index') }}">Firmalar →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
