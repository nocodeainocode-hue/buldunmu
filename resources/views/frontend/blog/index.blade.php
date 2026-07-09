@extends('layouts.app')
@section('title', 'Blog')
@section('content')
<div class="mx-auto px-4 sm:px-6 lg:px-8 py-12" style="max-width: var(--page_width, 1280px);">
    <h1 class="text-3xl font-bold mb-8" style="color: var(--text);">Blog</h1>
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @foreach($posts as $post)
        <article class="rounded-xl border overflow-hidden transition-shadow hover:shadow-lg" style="background: var(--bg_card); border-color: var(--border); border-radius: var(--border_radius);">
            @if($post->image)
                <img src="{{ asset('storage/'.$post->image) }}" class="w-full h-48 object-cover">
            @else
                <div class="w-full h-48 flex items-center justify-center text-4xl" style="background: linear-gradient(135deg, var(--primary), var(--secondary)); color: white;">📝</div>
            @endif
            <div class="p-5">
                <div class="text-xs mb-2" style="color: var(--text_muted);">{{ $post->published_at->format('d.m.Y') }} · {{ $post->directories->first()->name ?? '' }}</div>
                <h2 class="font-semibold text-lg mb-2" style="color: var(--text);">
                    <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                </h2>
                <p class="text-sm line-clamp-2" style="color: var(--text_muted);">{{ $post->excerpt }}</p>
            </div>
        </article>
        @endforeach
    </div>
    <div class="mt-10">{{ $posts->links() }}</div>
</div>
@endsection
