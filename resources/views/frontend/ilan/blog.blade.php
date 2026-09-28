@php
    $ibLead = $posts->isNotEmpty() ? $posts->first() : null;
@endphp
<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => 'Yazılar',
        'eyebrow' => 'Rehber içerikler',
        'title' => 'Blog yazıları',
        'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine güncel yazılar.',
    ])
    <div class="ib-wrap">
        <form class="ib-filters" action="{{ route('blog.index') }}" method="GET" style="margin-top:14px">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit" class="ib-btn">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>

        @if($ibLead)
            <section class="ib-box" style="margin-top:14px">
                <div class="ib-box__head"><h2>Günün yazısı</h2><span class="ib-box__note">{{ $ibLead->published_at?->format('d.m.Y') }}</span></div>
                <div style="display:flex;gap:14px;flex-wrap:wrap">
                    <div style="flex:1 1 320px;min-width:0;padding:14px">
                        <p style="font-size:11.5px;color:var(--text_muted)">{{ $ibLead->author_name ?: 'Editör' }} · {{ $ibLead->published_at?->format('d.m.Y') }}</p>
                        <h2 style="font-size:19px;margin-top:6px;color:var(--primary_hover)"><a href="{{ route('blog.show', $ibLead->slug) }}" style="text-decoration:none">{{ $ibLead->title }}</a></h2>
                        <p style="margin-top:8px;font-size:13px;color:var(--text_muted)">{{ $ibLead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($ibLead->content), 200) }}</p>
                        <p style="margin-top:12px"><a class="ib-btn" href="{{ route('blog.show', $ibLead->slug) }}">Yazıyı oku</a></p>
                    </div>
                    @if($ibLead->image)
                        <a href="{{ route('blog.show', $ibLead->slug) }}" aria-label="{{ $ibLead->title }} yazısını aç" style="flex:0 0 300px;display:block;min-height:160px;overflow:hidden">
                            <img src="{{ asset('storage/'.$ibLead->image) }}" alt="{{ $ibLead->title }}" loading="eager" style="width:100%;height:100%;object-fit:cover">
                        </a>
                    @endif
                </div>
            </section>
        @endif

        <div class="ib-cols" style="margin-top:14px">
            <aside class="ib-side">
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Keşfe devam</h2></div>
                    <div class="ib-links">
                        <a href="{{ route('companies.index') }}"><span class="ib-links__txt">Firmalar</span><span class="ib-links__count">›</span></a>
                        <a href="{{ route('jobs.index') }}"><span class="ib-links__txt">İş ilanları</span><span class="ib-links__count">›</span></a>
                        <a href="{{ route('packages.index') }}"><span class="ib-links__txt">Paketler</span><span class="ib-links__count">›</span></a>
                    </div>
                </section>
                <div class="ib-promo">
                    <h2>Firmanızı yazılarda gösterin</h2>
                    <p>İşletme profilinizi oluşturun, rehber içeriklerde yer alın.</p>
                    <a class="ib-btn" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Tüm yazılar</h2><span class="ib-box__note">{{ $posts->total() }} yazı</span></div>
                    <div class="ib-items">
                        @forelse($posts as $post)
                            <article class="ib-item">
                                <a class="ib-item__thumb" href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }} yazısını aç">
                                    @if($post->image)
                                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                    @else
                                        Y
                                    @endif
                                </a>
                                <div class="ib-item__body">
                                    <p class="ib-item__meta"><b>{{ $post->author_name ?: 'Editör' }}</b> / {{ $post->published_at?->format('d.m.Y') }}</p>
                                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                    <p class="ib-item__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 140) }}</p>
                                </div>
                                <div class="ib-item__side">
                                    <span class="ib-item__date">{{ $post->published_at?->format('d.m.Y') }}</span>
                                    <a class="ib-item__go" href="{{ route('blog.show', $post->slug) }}">Oku ›</a>
                                </div>
                            </article>
                        @empty
                            <div class="ib-empty">Bu aramada yazı bulunamadı.</div>
                        @endforelse
                    </div>
                    @if($posts->hasPages())<div class="ib-pag">{{ $posts->links() }}</div>@endif
                </section>
            </div>
        </div>
    </div>
</div>
