@if($posts->isNotEmpty())
<section class="py-16" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Blog</div>
                <h2 class="text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Son yazılar</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">Sektörel içerikler, ipuçları ve güncel yazılar.</p>
            </div>
            <a href="{{ route('blog.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tum yazılar -></a>
        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($posts as $post)
            <article class="overflow-hidden rounded-2xl border shadow-sm transition hover:-translate-y-1 hover:shadow-lg" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius,16px);">
                <a href="{{ route('blog.show', $post->slug) }}" class="block">
                    @if($post->image)
                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" class="h-48 w-full object-cover">
                    @else
                        <div class="flex h-48 w-full items-center justify-center text-5xl" style="background:var(--primary_light);color:var(--primary);">📝</div>
                    @endif
                </a>
                <div class="p-5">
                    <div class="mb-2 text-xs" style="color:var(--text_muted);">
                        {{ $post->published_at->format('d.m.Y') }}
                        @if($post->directories->isNotEmpty())
                            · {{ $post->directories->first()->name }}
                        @endif
                    </div>
                    <h3 class="mb-2 text-lg font-black" style="color:var(--text);">
                        <a href="{{ route('blog.show', $post->slug) }}" class="hover:underline">{{ $post->title }}</a>
                    </h3>
                    <p class="line-clamp-2 text-sm" style="color:var(--text_muted);">{{ $post->excerpt }}</p>
                </div>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif