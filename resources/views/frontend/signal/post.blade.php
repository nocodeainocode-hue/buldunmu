@php
    $sigContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => \Illuminate\Support\Str::limit($post->title, 42),
        'eyebrow' => 'Keşif notu / '.$sigContentType,
        'title' => $post->title,
        'description' => $post->excerpt,
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <div class="sig-cols">
            <div>
                <article class="sig-panel">
                    <div class="sig-panel__body">
                        <p class="sig-row__cat">
                            {{ $post->published_at?->format('d.m.Y') }}
                            {{ $post->author_name ? ' · Kalem: '.$post->author_name : '' }}
                            {{ $post->reviewer_name ? ' · Kontrol: '.$post->reviewer_name : '' }}
                        </p>
                        @if($post->image)
                            <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager" style="width:100%;aspect-ratio:16/9;object-fit:cover;border-radius:.125rem;margin:14px 0">
                        @endif
                        @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
                            <div class="sig-prose">
                                @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                                @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                            </div>
                        @endif
                        <div class="blog-prose sig-prose">{!! $post->content !!}</div>
                        @if(!empty($post->faq_items))
                            <div class="sig-prose">
                                <h2>Sık sorulan sorular</h2>
                                @foreach($post->faq_items as $faq)
                                    <details class="td-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
                                @endforeach
                            </div>
                        @endif
                        @if(!empty($post->sources))
                            <div class="sig-prose">
                                <h2>Kaynaklar</h2>
                                <ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
                            </div>
                        @endif
                        <div style="margin-top:22px;padding-top:16px;border-top:1px dashed var(--border)">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
                    </div>
                </article>
                @if($relatedPosts->isNotEmpty())
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>İlgili yazılar</h2><span class="sig-code">Devamı</span></div>
                        <div class="sig-links">@foreach($relatedPosts as $related)<a href="{{ route('blog.show', $related->slug) }}"><span class="sig-links__txt">{{ $related->title }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="sig-side">
                @if(($targetCity ?? null) || ($targetCategory ?? null))
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>Bu yazının hattı</h2><span class="sig-code">Bağlantı</span></div>
                        <div class="sig-links">
                            @if($targetCategory)<a href="{{ route('categories.show', $targetCategory->slug) }}"><span class="sig-links__txt">{{ $targetCategory->name }} firmaları</span><span class="sig-links__go">›</span></a>@endif
                            @if($targetCity)<a href="{{ route('cities.show', $targetCity->slug) }}"><span class="sig-links__txt">{{ $targetCity->name }} sinyali</span><span class="sig-links__go">›</span></a>@endif
                        </div>
                    </section>
                @endif
                <div class="sig-promo">
                    <span class="sig-kicker">Keşfe devam</span>
                    <h2 style="margin-top:10px">Sinyaldeki firmalar</h2>
                    <p>Yazıyı okudunuz; şimdi uygun işletmeyi seçin.</p>
                    <a class="sig-btn" href="{{ route('companies.index') }}">Firmalar →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
