@php
    $depLead = $posts->isNotEmpty() ? $posts->first() : null;
@endphp
<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => 'Yazılar',
        'eyebrow' => 'Yolculuk notları / Şehir rehberi',
        'title' => 'Peron yazıları',
        'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine güncel yazılar.',
    ])
    <div class="dep-wrap dep-page__body">
        <form class="dep-filter" action="{{ route('blog.index') }}" method="GET">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>

        @if($depLead)
            <article class="dep-panel" style="margin-top:24px">
                <div class="dep-panel__head"><h2>Bugünün yazısı</h2><span class="dep-code">{{ $depLead->published_at?->format('d.m.Y') }}</span></div>
                <div class="dep-lead @if($depLead->image) dep-lead--split @endif">
                    <div class="dep-article" style="border-top:0">
                        <span class="dep-kicker">{{ $depLead->author_name ?: 'Editör' }} · {{ $depLead->published_at?->format('d.m.Y') }}</span>
                        <h2 class="dep-h2" style="margin:14px 0 12px">{{ $depLead->title }}</h2>
                        <p style="color:var(--muted);font-size:15.5px">{{ $depLead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($depLead->content), 200) }}</p>
                        <p style="margin-top:20px"><a class="dep-btn" href="{{ route('blog.show', $depLead->slug) }}">Yazıyı oku →</a></p>
                    </div>
                    @if($depLead->image)
                        <a href="{{ route('blog.show', $depLead->slug) }}" aria-label="{{ $depLead->title }} yazısını aç" style="display:block;overflow:hidden">
                            <img src="{{ asset('storage/'.$depLead->image) }}" alt="{{ $depLead->title }}" loading="eager">
                        </a>
                    @endif
                </div>
            </article>
        @endif

        <div class="dep-columns">
            <div class="dep-main">
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Yayın seçkisi</h2><span class="dep-code">{{ $posts->total() }} yazı</span></div>
                    @forelse($posts as $post)
                        <article class="dep-row">
                            <a class="dep-row__mark" href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }} yazısını aç">
                                @if($post->image)
                                    <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                @else
                                    Y
                                @endif
                            </a>
                            <div>
                                <p class="dep-code" style="color:var(--amber-deep)">{{ $post->author_name ?: 'Editör' }} / {{ $post->published_at?->format('d.m.Y') }}</p>
                                <h3 class="dep-h3" style="margin:5px 0"><a href="{{ route('blog.show', $post->slug) }}" style="text-decoration:none">{{ $post->title }}</a></h3>
                                <p class="dep-card__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 132) }}</p>
                            </div>
                            <a class="dep-row__go" href="{{ route('blog.show', $post->slug) }}">Oku →</a>
                        </article>
                    @empty
                        <div class="dep-empty">Bu aramada yazı bulunamadı.</div>
                    @endforelse
                    @if($posts->hasPages())<div class="dep-pagination">{{ $posts->links() }}</div>@endif
                </section>
            </div>
            <aside class="dep-side">
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Keşfe devam</h2><span class="dep-code">Pano</span></div>
                    <div class="dep-link-list">
                        <a href="{{ route('companies.index') }}"><span>Firmalar</span><b>→</b></a>
                        <a href="{{ route('jobs.index') }}"><span>İş ilanları</span><b>→</b></a>
                        <a href="{{ route('packages.index') }}"><span>Paketler</span><b>→</b></a>
                    </div>
                </section>
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Hikâyeniz</span>
                    <h2>Firmanızı panoya taşıyın</h2>
                    <p>İşletme profilinizi oluşturun, yazılarda yer alın.</p>
                    <a class="dep-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
