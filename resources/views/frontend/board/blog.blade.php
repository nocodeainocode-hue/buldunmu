<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => 'Yazılar',
            'tag' => '📰 Okuma köşesi',
            'title' => 'Yazılar ve rehberler',
            'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine güncel yazılar.',
        ])
        <form class="bd-filters" action="{{ route('blog.index') }}" method="GET">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit" class="bd-btn">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>
        <div class="bd-cols">
            <div>
                <div class="bd-items">
                    @forelse($posts as $post)
                        <article class="bd-item">
                            <div class="bd-item__thumb">@if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">@else{{ mb_strtoupper(mb_substr($post->title, 0, 1)) }}@endif</div>
                            <div class="bd-item__body">
                                <div class="bd-item__meta"><b>{{ $post->author_name ?: 'Editör' }}</b><span>·</span><span>{{ $post->published_at?->format('d.m.Y') }}</span></div>
                                <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                <p class="bd-item__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                            </div>
                            <div class="bd-item__act"><a class="bd-btn bd-btn--ghost bd-btn--sm" href="{{ route('blog.show', $post->slug) }}">Oku</a></div>
                        </article>
                    @empty
                        <div class="bd-empty">Bu aramada yazı bulunamadı.</div>
                    @endforelse
                </div>
                @if($posts->hasPages())<div class="bd-pag">{{ $posts->links() }}</div>@endif
            </div>
            <aside class="bd-side">
                <section class="bd-box"><div class="bd-box__head"><h2>Panoya dön</h2></div><div class="bd-links">
                    <a href="{{ route('companies.index') }}"><span class="bd-links__txt">Tüm ilanlar</span><span class="bd-links__n">→</span></a>
                    <a href="{{ route('jobs.index') }}"><span class="bd-links__txt">İş ilanları</span><span class="bd-links__n">→</span></a>
                    <a href="{{ route('packages.index') }}"><span class="bd-links__txt">Paketler</span><span class="bd-links__n">→</span></a>
                </div></section>
                <div class="bd-cta"><h2>Hikâyeni anlat</h2><p>İşletmeni ekle, yazılarda yer al.</p><a class="bd-btn" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a></div>
            </aside>
        </div>
    </div>
</div>
