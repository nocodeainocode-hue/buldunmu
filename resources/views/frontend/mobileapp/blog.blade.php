<div class="ap-search">
    <form action="{{ route('blog.index') }}" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Yazılarda ara">
        <button class="ap-search__go" type="submit" aria-label="Ara">⌕</button>
    </form>
</div>
<div class="ap-sec"><div class="ap-sec__head"><h2>Rehber yazıları</h2><span style="font-size:12px;color:var(--text_muted);font-weight:600">{{ $posts->total() }} yazı</span></div></div>
<div class="ap-feed">
    @forelse($posts as $post)
        <a class="ap-post" href="{{ route('blog.show', $post->slug) }}">
            <span class="ap-post__cover @if(!$post->image) ap-post__cover--ph @endif">
                @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">@else✎@endif
            </span>
            <span class="ap-post__body">
                <span class="ap-post__cat">{{ $post->author_name ?: 'Editör' }}</span>
                <h3>{{ $post->title }}</h3>
                <p class="ap-post__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                <span class="ap-post__row">{{ $post->published_at?->format('d.m.Y') }}</span>
            </span>
        </a>
    @empty
        <div class="ap-empty">Bu aramada yazı bulunamadı.</div>
    @endforelse
</div>
@if($posts->hasPages())<div class="ap-pag">{{ $posts->links() }}</div>@endif
