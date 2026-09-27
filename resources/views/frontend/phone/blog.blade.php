{{-- CEP · blog listesi --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>Yazılar</span>
    </nav>
    <span class="ph-eyebrow">Okuma · {{ $posts->total() }} yazı</span>
    <h1>Şehirden hikâyeler</h1>
    <p>{{ $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve iyi kararlar üzerine güncel yazılar.' }}</p>
</div>

<form class="ph-filter" action="{{ route('blog.index') }}" method="GET">
    <label class="ph-field">Yazılarda ara
        <input name="q" value="{{ request('q') }}" placeholder="Başlık veya konu">
    </label>
    <div style="display:grid;gap:8px">
        <button class="ph-btn" type="submit">Ara</button>
        @if(request()->filled('q'))<a class="ph-btn ph-btn--ghost" href="{{ route('blog.index') }}">Temizle</a>@endif
    </div>
</form>

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Yayın seçkisi</h2><span class="ph-meta">{{ $posts->total() }} yazı</span></div>
    <div class="ph-list" style="padding:12px 12px 14px">
        @forelse($posts as $post)
            <a class="ph-row" href="{{ route('blog.show', $post->slug) }}">
                <span class="ph-row__av">
                    @if($post->image)<img src="{{ asset('storage/' . $post->image) }}" alt="{{ $post->title }}" loading="lazy">@else✦@endif
                </span>
                <div class="ph-row__txt">
                    <strong>{{ $post->title }}</strong>
                    <span>{{ $post->author_name ?: 'Editör' }} · {{ $post->published_at?->format('d.m.Y') }}</span>
                    <span>{{ \Illuminate\Support\Str::limit($post->excerpt ?: strip_tags($post->content), 96) }}</span>
                </div>
                <span class="ph-row__go">›</span>
            </a>
        @empty
            <div class="ph-empty">Bu aramada yazı bulunamadı.</div>
        @endforelse
    </div>
    @if($posts->hasPages())<div class="ph-pagination">{{ $posts->links() }}</div>@endif
</section>

<div class="ph-cta">
    <h2>Firman hikâyeni yaz.</h2>
    <p>Profilini oluştur, rehberde görünür ol.</p>
    <a class="ph-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
</div>
