@php
    $katContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => \Illuminate\Support\Str::limit($post->title, 42),
        'eyebrow' => 'Yazı / '.$katContentType,
        'title' => $post->title,
        'description' => $post->excerpt,
    ])
    <div class="kat-wrap">
        <div class="kat-cols">
            <div>
                <article>
                    <p class="kat-caps kat-muted" style="margin-bottom:22px">
                        {{ $post->published_at?->format('d.m.Y') }}
                        {{ $post->author_name ? ' · Kalem: '.$post->author_name : '' }}
                        {{ $post->reviewer_name ? ' · Kontrol: '.$post->reviewer_name : '' }}
                    </p>
                    @if($post->image)
                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager" style="width:100%;aspect-ratio:16/9;object-fit:cover;margin-bottom:30px;border:1px solid var(--border)">
                    @endif
                    @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
                        <div class="kat-prose">
                            @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                            @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                        </div>
                    @endif
                    <div class="blog-prose kat-prose kat-prose--drop">{!! $post->content !!}</div>
                    @if(!empty($post->faq_items))
                        <div class="kat-prose">
                            <h2>Sık sorulan sorular</h2>
                            @foreach($post->faq_items as $faq)
                                <details class="td-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
                            @endforeach
                        </div>
                    @endif
                    @if(!empty($post->sources))
                        <div class="kat-prose">
                            <h2>Kaynaklar</h2>
                            <ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
                        </div>
                    @endif
                    <div style="margin-top:34px;padding-top:18px;border-top:1px solid var(--border)">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
                </article>
                @if($relatedPosts->isNotEmpty())
                    <section class="kat-panel" style="margin-top:50px">
                        <div class="kat-panel__head"><h2>İlgili yazılar</h2><span>Devamı</span></div>
                        <div class="kat-links">@foreach($relatedPosts as $related)<a href="{{ route('blog.show', $related->slug) }}"><span class="kat-links__txt">{{ $related->title }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="kat-side">
                @if(($targetCity ?? null) || ($targetCategory ?? null))
                    <section class="kat-panel">
                        <div class="kat-panel__head"><h2>Bu yazının konusu</h2><span>Bağlantı</span></div>
                        <div class="kat-links">
                            @if($targetCategory)<a href="{{ route('categories.show', $targetCategory->slug) }}"><span class="kat-links__txt">{{ $targetCategory->name }} firmaları</span><span class="kat-links__go">→</span></a>@endif
                            @if($targetCity)<a href="{{ route('cities.show', $targetCity->slug) }}"><span class="kat-links__txt">{{ $targetCity->name }} rehberi</span><span class="kat-links__go">→</span></a>@endif
                        </div>
                    </section>
                @endif
                <div class="kat-promo">
                    <span class="kat-kicker">Keşfe devam</span>
                    <h2>Katalogdaki <em>firmalar</em></h2>
                    <p>Yazıyı okudunuz; şimdi size uygun işletmeyi seçin.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('companies.index') }}">Firmalar →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
