@php
    $ibContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => \Illuminate\Support\Str::limit($post->title, 42),
        'eyebrow' => $ibContentType,
        'title' => $post->title,
    ])
    <div class="ib-wrap">
        <div class="ib-cols" style="margin-top:14px">
            <aside class="ib-side">
                @if(($targetCity ?? null) || ($targetCategory ?? null))
                    <section class="ib-box">
                        <div class="ib-box__head"><h2>Yazının bağlantıları</h2></div>
                        <div class="ib-links">
                            @if($targetCategory)<a href="{{ route('categories.show', $targetCategory->slug) }}"><span class="ib-links__txt">{{ $targetCategory->name }} firmaları</span><span class="ib-links__count">›</span></a>@endif
                            @if($targetCity)<a href="{{ route('cities.show', $targetCity->slug) }}"><span class="ib-links__txt">{{ $targetCity->name }} rehberi</span><span class="ib-links__count">›</span></a>@endif
                        </div>
                    </section>
                @endif
                <div class="ib-promo">
                    <h2>Doğru firmayı bulun</h2>
                    <p>Yazıyı okudunuz; şimdi uygun işletmeyi seçin.</p>
                    <a class="ib-btn" href="{{ route('companies.index') }}">Firmalar</a>
                </div>
            </aside>
            <div>
                <article class="ib-box">
                    <div class="ib-box__body">
                        <p style="font-size:12px;color:var(--text_muted)">
                            {{ $post->published_at?->format('d.m.Y') }}
                            {{ $post->author_name ? ' · Kalem: '.$post->author_name : '' }}
                            {{ $post->reviewer_name ? ' · Kontrol: '.$post->reviewer_name : '' }}
                            · <span class="ib-tag">{{ $ibContentType }}</span>
                        </p>
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager" style="width:100%;aspect-ratio:16/9;object-fit:cover;border:1px solid var(--border);border-radius:var(--border_radius);margin:12px 0">
                        @endif
                        @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
                            <div class="ib-prose">
                                @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                                @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                            </div>
                        @endif
                        <div class="blog-prose ib-prose">{!! $post->content !!}</div>
                        @if(!empty($post->faq_items))
                            <div class="ib-prose">
                                <h2>Sık sorulan sorular</h2>
                                @foreach($post->faq_items as $faq)
                                    <details class="td-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($post->sources))
                            <div class="ib-prose">
                                <h2>Kaynaklar</h2>
                                <ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
                            </div>
                        @endif
                        <div style="margin-top:18px;padding-top:12px;border-top:1px solid var(--border)">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
                    </div>
                </article>
                @if($relatedPosts->isNotEmpty())
                    <section class="ib-box" style="margin-top:12px">
                        <div class="ib-box__head"><h2>İlgili yazılar</h2></div>
                        <div class="ib-links">@foreach($relatedPosts as $related)<a href="{{ route('blog.show', $related->slug) }}"><span class="ib-links__txt">{{ $related->title }}</span><span class="ib-links__count">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
