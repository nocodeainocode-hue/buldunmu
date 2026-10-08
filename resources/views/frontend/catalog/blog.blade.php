@php $katLead = $posts->isNotEmpty() ? $posts->first() : null; @endphp
<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => 'Yazılar',
        'eyebrow' => 'Dergi / Şehir rehberi',
        'title' => 'Yazılar ve rehberler',
        'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine güncel yazılar.',
        'folio' => str_pad((string) $posts->total(), 2, '0', STR_PAD_LEFT),
    ])
    <div class="kat-wrap">
        <form class="kat-filters" action="{{ route('blog.index') }}" method="GET">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit" class="kat-btn">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>

        @if($katLead)
            <section class="kat-lead">
                <div>
                    <span class="kat-kicker">Günün yazısı · {{ $katLead->published_at?->format('d.m.Y') }}</span>
                    <h2>{{ $katLead->title }}</h2>
                    <p>{{ $katLead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($katLead->content), 200) }}</p>
                    <p style="margin-top:22px"><a class="kat-btn" href="{{ route('blog.show', $katLead->slug) }}">Yazıyı oku →</a></p>
                </div>
                @if($katLead->image)
                    <a class="kat-lead__fig" href="{{ route('blog.show', $katLead->slug) }}" aria-label="{{ $katLead->title }} yazısını aç"><img src="{{ asset('storage/'.$katLead->image) }}" alt="{{ $katLead->title }}" loading="eager"></a>
                @endif
            </section>
        @endif

        <div class="kat-cols">
            <div>
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Tüm yazılar</h2><span>{{ $posts->total() }} yazı</span></div>
                    <div class="kat-entries" style="border-top:0">
                        @forelse($posts as $post)
                            <article class="kat-entry">
                                <span class="kat-entry__no">{{ str_pad((string) (($posts->firstItem() ?? 1) - 1 + $loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                                <a class="kat-entry__mark" href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }} yazısını aç">
                                    @if($post->image)<img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">@else{{ mb_strtoupper(mb_substr($post->title, 0, 1)) }}@endif
                                </a>
                                <div>
                                    <p class="kat-entry__cat">{{ $post->author_name ?: 'Editör' }} / {{ $post->published_at?->format('d.m.Y') }}</p>
                                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                    <p class="kat-entry__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</p>
                                </div>
                                <a class="kat-entry__go" href="{{ route('blog.show', $post->slug) }}">Oku →</a>
                            </article>
                        @empty
                            <div class="kat-empty">Bu aramada yazı bulunamadı.</div>
                        @endforelse
                    </div>
                    @if($posts->hasPages())<div class="kat-pag">{{ $posts->links() }}</div>@endif
                </section>
            </div>
            <aside class="kat-side">
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Keşfe devam</h2><span>Katalog</span></div>
                    <div class="kat-links">
                        <a href="{{ route('companies.index') }}"><span class="kat-links__txt">Firmalar</span><span class="kat-links__go">→</span></a>
                        <a href="{{ route('jobs.index') }}"><span class="kat-links__txt">İş ilanları</span><span class="kat-links__go">→</span></a>
                        <a href="{{ route('packages.index') }}"><span class="kat-links__txt">Paketler</span><span class="kat-links__go">→</span></a>
                    </div>
                </section>
                <div class="kat-promo">
                    <span class="kat-kicker">Hikâyeniz</span>
                    <h2>İşletmenizi <em>kataloğa</em> taşıyın</h2>
                    <p>Profilinizi oluşturun, yazılarda yer alın.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
