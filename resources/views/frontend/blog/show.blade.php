@extends('layouts.app')

@section('title', $post->title)
@section('meta_description', $post->excerpt)
@section('robots', $post->robotsDirective())
@section('canonical', $post->canonical_url ?: route('blog.show', $post->slug))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::blogPost($post, $directory ?? null, $settings ?? null)])
@endpush

@section('content')
<div class="py-10 sm:py-14" style="background:var(--bg);">
    <article class="mx-auto px-4 sm:px-6" style="max-width:920px;">
        <x-breadcrumb :items="[['label'=>'Blog','url'=>route('blog.index')],['label'=>$post->title]]" />

        <header class="mt-6 border-b pb-8" style="border-color:var(--border);">
            <div class="flex flex-wrap items-center gap-2 text-xs font-bold" style="color:var(--primary);">
                <span class="rounded-md px-2 py-1" style="background:var(--primary_light);">{{ match($post->content_type){'comparison'=>'Karşılaştırma','alternatives'=>'Alternatifler','local'=>'Yerel Rehber','answers'=>'Uzman Cevabı','data'=>'Veri / Araştırma',default=>'Seçim Rehberi'} }}</span>
                @if($post->target_city_slug)<span>{{ \Illuminate\Support\Str::headline($post->target_city_slug) }}</span>@endif
                <span style="color:var(--text_muted);">{{ $post->published_at->format('d.m.Y') }}</span>
            </div>
            <h1 class="mt-4 text-3xl font-black leading-tight sm:text-5xl" style="color:var(--text);">{{ $post->title }}</h1>
            @if($post->excerpt)<p class="mt-5 text-lg leading-8" style="color:var(--text_muted);">{{ $post->excerpt }}</p>@endif
            @if($post->author_name || $post->reviewer_name)<div class="mt-5 flex flex-wrap gap-5 text-sm" style="color:var(--text_muted);">@if($post->author_name)<span><strong style="color:var(--text);">Yazar:</strong> {{ $post->author_name }}</span>@endif @if($post->reviewer_name)<span><strong style="color:var(--text);">Kontrol:</strong> {{ $post->reviewer_name }}</span>@endif</div>@endif
        </header>

        @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="mt-8 max-h-[520px] w-full rounded-lg object-cover">@endif

        @if($blogLayout === 'comparison' && (!empty($post->pros) || !empty($post->cons)))
            <div class="mt-8 grid gap-4 sm:grid-cols-2"><div class="rounded-lg border p-5" style="border-color:#bfe6d2;background:#f2fbf6;"><h2 class="font-black" style="color:#16633f;">Artılar</h2><ul class="mt-3 space-y-2 text-sm">@foreach($post->pros ?? [] as $item)<li>+ {{ $item }}</li>@endforeach</ul></div><div class="rounded-lg border p-5" style="border-color:#f0c9c9;background:#fff7f7;"><h2 class="font-black" style="color:#9b2c2c;">Eksiler</h2><ul class="mt-3 space-y-2 text-sm">@foreach($post->cons ?? [] as $item)<li>− {{ $item }}</li>@endforeach</ul></div></div>
        @endif

        <div class="prose prose-lg mt-9 max-w-none leading-relaxed" style="color:var(--text);">{!! $post->content !!}</div>

        @if($targetCity || $targetCategory)
            <nav class="mt-10 flex flex-wrap gap-3 rounded-lg border p-5" style="border-color:var(--border);background:var(--primary_light);" aria-label="İlgili rehber bağlantıları">
                <strong class="w-full text-sm" style="color:var(--text);">İlgili firma rehberleri</strong>
                @if($targetCity)<a href="{{ route('cities.show',$targetCity->slug) }}" class="rounded-md bg-white px-3 py-2 text-sm font-black" style="color:var(--primary);">{{ $targetCity->name }} firmaları</a>@endif
                @if($targetCategory)<a href="{{ route('categories.show',$targetCategory->slug) }}" class="rounded-md bg-white px-3 py-2 text-sm font-black" style="color:var(--primary);">{{ $targetCategory->name }} firmaları</a>@endif
            </nav>
        @endif

        @if(!empty($post->faq_items))
            <section class="mt-12 border-t pt-10" style="border-color:var(--border);"><h2 class="text-2xl font-black" style="color:var(--text);">Sık Sorulan Sorular</h2><div class="mt-5 space-y-3">@foreach($post->faq_items as $faq)<details class="rounded-lg border p-4" style="border-color:var(--border);background:var(--bg_card);"><summary class="cursor-pointer font-black" style="color:var(--text);">{{ $faq['question'] ?? '' }}</summary><p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">{{ $faq['answer'] ?? '' }}</p></details>@endforeach</div></section>
        @endif

        @if(!empty($post->sources))
            <section class="mt-10 rounded-lg border p-5" style="border-color:var(--border);background:var(--bg_card);"><h2 class="text-sm font-black uppercase tracking-wider" style="color:var(--text);">Kaynaklar</h2><ol class="mt-3 space-y-2 text-sm" style="color:var(--text_muted);">@foreach($post->sources as $source)<li><a href="{{ $source }}" rel="nofollow noopener" target="_blank" class="break-all hover:underline" style="color:var(--primary);">{{ $source }}</a></li>@endforeach</ol></section>
        @endif
    </article>

    @if($relatedPosts->isNotEmpty())<section class="mx-auto mt-14 border-t px-4 pt-10 sm:px-6" style="max-width:920px;border-color:var(--border);"><h2 class="text-xl font-black" style="color:var(--text);">İlgili Yazılar</h2><div class="mt-5 grid gap-4 sm:grid-cols-3">@foreach($relatedPosts as $related)<a href="{{ route('blog.show',$related->slug) }}" class="rounded-lg border p-4 text-sm font-bold" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">{{ $related->title }}</a>@endforeach</div></section>@endif
</div>
@endsection
