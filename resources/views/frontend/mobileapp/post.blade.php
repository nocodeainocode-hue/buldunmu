@php
    $apContentType = match ($post->content_type ?? null) {
        'comparison' => 'Karşılaştırma',
        'alternatives' => 'Alternatifler',
        'local' => 'Yerel rehber',
        'answers' => 'Uzman cevabı',
        'data' => 'Veri araştırması',
        default => 'Seçim rehberi',
    };
@endphp
@if($post->image)
    <div style="aspect-ratio:16/9;overflow:hidden;background:var(--primary_light)"><img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="eager" style="width:100%;height:100%;object-fit:cover"></div>
@endif
<div class="ap-sec">
    <span class="ap-kicker">{{ $apContentType }}</span>
    <h1 style="font-size:24px;font-weight:800;margin-top:6px">{{ $post->title }}</h1>
    <p style="margin-top:8px;font-size:12px;color:var(--text_muted)">{{ $post->published_at?->format('d.m.Y') }}{{ $post->author_name ? ' · '.$post->author_name : '' }}</p>
</div>
<div class="ap-prose blog-prose">
    @if(($blogLayout ?? null) === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
        @if(!empty($post->pros))<h2>Artılar</h2><ul>@foreach($post->pros as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
        @if(!empty($post->cons))<h2>Eksiler</h2><ul>@foreach($post->cons as $item)<li>{{ $item }}</li>@endforeach</ul>@endif
    @endif
    {!! $post->content !!}
    @if(!empty($post->faq_items))
        <h2>Sık sorulan sorular</h2>
        @foreach($post->faq_items as $faq)
            <details><summary>{{ $faq['question'] ?? '' }}</summary><p>{{ $faq['answer'] ?? '' }}</p></details>
        @endforeach
    @endif
    @if(!empty($post->sources))
        <h2>Kaynaklar</h2>
        <ol>@foreach($post->sources as $source)<li><a href="{{ $source }}" target="_blank" rel="nofollow noopener">{{ $source }}</a></li>@endforeach</ol>
    @endif
</div>
<div class="ap-sec" style="padding-top:0">@include('partials.share-buttons', ['url' => route('blog.show', $post->slug), 'title' => $post->title])</div>
@if(($targetCity ?? null) || ($targetCategory ?? null))
    <div class="ap-sec"><div class="ap-sec__head"><h2>Bağlantılar</h2></div></div>
    <div class="ap-list">
        @if($targetCategory)<a class="ap-row" href="{{ route('categories.show', $targetCategory->slug) }}"><span class="ap-row__main"><h3>{{ $targetCategory->name }} firmaları</h3></span><span class="ap-row__chev">›</span></a>@endif
        @if($targetCity)<a class="ap-row" href="{{ route('cities.show', $targetCity->slug) }}"><span class="ap-row__main"><h3>{{ $targetCity->name }} rehberi</h3></span><span class="ap-row__chev">›</span></a>@endif
    </div>
@endif
@if($relatedPosts->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>İlgili yazılar</h2></div></div>
    <div class="ap-list">
        @foreach($relatedPosts as $related)
            <a class="ap-row" href="{{ route('blog.show', $related->slug) }}"><span class="ap-row__main"><h3>{{ $related->title }}</h3></span><span class="ap-row__chev">›</span></a>
        @endforeach
    </div>
@endif
