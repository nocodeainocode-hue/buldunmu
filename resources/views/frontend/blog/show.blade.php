@extends('layouts.app')
@section('title', $post->title)
@section('meta_description', $post->excerpt)
@section('canonical', route('blog.show', $post->slug))
@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::blogPost($post, $directory ?? null, $settings ?? null)])
@endpush
@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-12" style="max-width: var(--page_width, 1280px);">
    <article class="max-w-3xl mx-auto">
        <x-breadcrumb :items="[['label' => 'Blog', 'url' => route('blog.index')], ['label' => $post->title]]" />
        <div class="text-sm mb-4" style="color: var(--text_muted);">
            {{ $post->published_at->format('d.m.Y') }}
            @if($post->directories->first())
                <span class="mx-2">·</span>
                {{ $post->directories->first()->name ?? '' }}
            @endif
        </div>
        <h1 class="text-3xl sm:text-4xl font-bold mb-6" style="color: var(--text);">{{ $post->title }}</h1>
        <div class="prose max-w-none text-lg leading-relaxed" style="color: var(--text);">
            {!! $post->content !!}
        </div>
    </article>

    @if($relatedPosts->isNotEmpty())
    <div class="max-w-3xl mx-auto mt-16 pt-12 border-t" style="border-color: var(--border);">
        <h3 class="text-xl font-bold mb-6" style="color: var(--text);">Ilgili Yazilar</h3>
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
            @foreach($relatedPosts as $rp)
            <a href="{{ route('blog.show', $rp->slug) }}" class="block rounded-xl border p-4 hover:shadow-md transition" style="background: var(--bg_card); border-color: var(--border);">
                <h4 class="font-semibold text-sm" style="color: var(--text);">{{ $rp->title }}</h4>
                <p class="text-xs mt-2" style="color: var(--text_muted);">{{ $rp->published_at->format('d.m.Y') }}</p>
            </a>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
