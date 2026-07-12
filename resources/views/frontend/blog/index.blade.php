@extends('layouts.app')

@php
    $headings = [
        'editorial' => ['Yayın', 'Güncel rehberler ve işletme içerikleri'],
        'local' => ['Yerel Gündem', ($directory->name ?? 'Şehriniz') . ' için yerel yaşam ve hizmet rehberi'],
        'comparison' => ['Karar Masası', 'Seçenekleri ölçütleriyle değerlendirin'],
        'alternatives' => ['Alternatif Kataloğu', 'İhtiyacınıza uygun farklı çözümleri keşfedin'],
        'answers' => ['Uzman Cevapları', 'Hizmetler hakkında merak edilen sorular'],
    ];
    [$eyebrow, $heading] = $headings[$blogLayout] ?? $headings['editorial'];
@endphp

@section('title', $heading)
@section('canonical', route('blog.index'))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::blogListing($posts->getCollection())])
@endpush

@section('content')
<div style="background:var(--bg);">
    <section class="border-b py-12" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <x-breadcrumb :items="[['label' => 'Blog']]" />
            <div class="mt-5 flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
                <div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">{{ $eyebrow }}</div><h1 class="mt-2 max-w-3xl text-3xl font-black sm:text-4xl" style="color:var(--text);">{{ $heading }}</h1>@if($directory?->editorial_voice)<p class="mt-3 max-w-2xl text-sm leading-6" style="color:var(--text_muted);">{{ $directory->editorial_voice }}</p>@endif</div>
                <form action="{{ route('blog.index') }}" class="flex overflow-hidden rounded-md border" style="border-color:var(--border);"><input name="q" value="{{ request('q') }}" class="min-w-0 px-4 py-3 text-sm outline-none" placeholder="Yazılarda ara"><button class="px-4 text-sm font-black text-white" style="background:var(--primary);">Ara</button></form>
            </div>
        </div>
    </section>

    <section class="mx-auto px-4 py-10 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        @if($blogLayout === 'comparison')
            <div class="overflow-hidden rounded-lg border" style="border-color:var(--border);background:var(--bg_card);">
                @forelse($posts as $post)<article class="grid gap-4 border-b p-5 last:border-0 md:grid-cols-[160px_minmax(0,1fr)_auto] md:items-center" style="border-color:var(--border);"><div class="text-xs font-black uppercase" style="color:var(--primary);">{{ match($post->content_type){'comparison'=>'Karşılaştırma','alternatives'=>'Alternatifler',default=>'Seçim Rehberi'} }}</div><div><h2 class="text-lg font-black" style="color:var(--text);"><a href="{{ route('blog.show',$post->slug) }}">{{ $post->title }}</a></h2><p class="mt-1 line-clamp-2 text-sm" style="color:var(--text_muted);">{{ $post->excerpt }}</p></div><a href="{{ route('blog.show',$post->slug) }}" class="rounded-md px-4 py-2 text-center text-xs font-black text-white" style="background:var(--primary);">İncele</a></article>@empty@include('frontend.blog.partials.empty')@endforelse
            </div>
        @elseif($blogLayout === 'answers')
            <div class="mx-auto max-w-4xl space-y-3">@forelse($posts as $post)<article class="rounded-lg border p-5" style="border-color:var(--border);background:var(--bg_card);"><div class="text-xs font-black" style="color:var(--primary);">SORU {{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</div><h2 class="mt-2 text-xl font-black" style="color:var(--text);"><a href="{{ route('blog.show',$post->slug) }}">{{ $post->title }}</a></h2><p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">{{ $post->excerpt }}</p></article>@empty@include('frontend.blog.partials.empty')@endforelse</div>
        @elseif($blogLayout === 'local')
            @if($posts->count())@php $lead=$posts->first(); @endphp<div class="mb-8 grid overflow-hidden rounded-lg border lg:grid-cols-[1.2fr_0.8fr]" style="border-color:var(--border);background:var(--bg_card);"><div class="min-h-64" style="background:var(--primary_light);">@if($lead->image)<img src="{{ asset('storage/'.$lead->image) }}" alt="{{ $lead->title }}" class="h-full w-full object-cover">@endif</div><div class="p-7"><div class="text-xs font-black uppercase" style="color:var(--primary);">Öne çıkan yerel rehber</div><h2 class="mt-3 text-2xl font-black" style="color:var(--text);"><a href="{{ route('blog.show',$lead->slug) }}">{{ $lead->title }}</a></h2><p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">{{ $lead->excerpt }}</p></div></div>@endif
            <div class="grid gap-5 md:grid-cols-2">@foreach($posts->getCollection()->skip(1) as $post)@include('frontend.blog.partials.card',['post'=>$post])@endforeach</div>
        @else
            <div class="grid gap-5 {{ $blogLayout === 'alternatives' ? 'md:grid-cols-2' : 'sm:grid-cols-2 lg:grid-cols-3' }}">@forelse($posts as $post)@include('frontend.blog.partials.card',['post'=>$post])@empty@include('frontend.blog.partials.empty')@endforelse</div>
        @endif
        <div class="mt-10">{{ $posts->links() }}</div>
    </section>
</div>
@endsection
