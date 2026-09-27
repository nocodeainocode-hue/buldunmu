{{-- CEP · yazı detayı --}}
@php
    $phType = match($post->content_type) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><a href="{{ route('blog.index') }}">Yazılar</a><span>/</span><span>{{ Str::limit($post->title, 24) }}</span>
    </nav>
    <span class="ph-eyebrow">{{ $phType }} · {{ $post->published_at?->format('d.m.Y') }}</span>
    <h1>{{ $post->title }}</h1>
    <p>{{ $post->excerpt }}</p>
</div>

<div class="ph-article">
    @if($post->image)
        <img class="ph-article__img" src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="eager">
    @endif

    <p class="ph-meta" style="margin-top:12px">
        <span>{{ $post->author_name ?: 'Editör' }}</span>
        @if($post->reviewer_name)<span>Kontrol: {{ $post->reviewer_name }}</span>@endif
        @if($post->reading_time)<span>{{ $post->reading_time }} dk okuma</span>@endif
    </p>

    @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
        <div class="ph-sheet" style="margin:14px 0">
            <div class="ph-sheet__head"><h2>Artılar & eksiler</h2><span class="ph-meta">Karşılaştırma</span></div>
            <div class="ph-prose" style="padding:14px">
                @if(!empty($post->pros))<h3 style="color:var(--ph-ok)">Artılar</h3><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
                @if(!empty($post->cons))<h3 style="color:var(--ph-primary2)">Eksiler</h3><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
            </div>
        </div>
    @endif

    <div class="blog-prose ph-prose" style="margin-top:14px">{!! $post->content !!}</div>

    @if(!empty($post->faq_items))
        <h2 style="margin:20px 0 10px;font-size:18px">Sık sorulanlar</h2>
        @foreach($post->faq_items as $faq)
            <details class="ph-faq"><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
        @endforeach
    @endif

    @if(!empty($post->sources))
        <h2 style="margin:20px 0 10px;font-size:18px">Kaynaklar</h2>
        <ol class="ph-prose">@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
    @endif
</div>

@if($targetCity || $targetCategory)
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>İlgili rehberler</h2><span class="ph-meta">Devam</span></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            @if($targetCity)
                <a class="ph-row" href="{{ route('cities.show', $targetCity->slug) }}">
                    <span class="ph-row__av">⌖</span>
                    <div class="ph-row__txt"><strong>{{ $targetCity->name }} firmaları</strong><span>Şehir listesi</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endif
            @if($targetCategory)
                <a class="ph-row" href="{{ route('categories.show', $targetCategory->slug) }}">
                    <span class="ph-row__av">🏷️</span>
                    <div class="ph-row__txt"><strong>{{ $targetCategory->name }} firmaları</strong><span>Kategori listesi</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endif
        </div>
    </section>
@endif

@if($relatedPosts->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>İlgili yazılar</h2></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            @foreach($relatedPosts as $related)
                <a class="ph-row" href="{{ route('blog.show', $related->slug) }}">
                    <span class="ph-row__av">📖</span>
                    <div class="ph-row__txt"><strong>{{ $related->title }}</strong><span>{{ $related->published_at?->format('d.m.Y') }}</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

<div class="ph-cta">
    <h2>Keşfe devam.</h2>
    <p>Yeni yazıları ve işletmeleri tek akışta gör.</p>
    <a class="ph-btn" href="{{ route('blog.index') }}">Tüm yazılar →</a>
</div>
