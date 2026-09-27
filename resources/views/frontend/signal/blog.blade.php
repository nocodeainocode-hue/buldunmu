@php
    $sigLead = $posts->isNotEmpty() ? $posts->first() : null;
@endphp
<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => 'Yazılar',
        'eyebrow' => 'Keşif notları / Şehir rehberi',
        'title' => 'İstasyon yazıları',
        'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine güncel yazılar.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <form class="sig-filters" action="{{ route('blog.index') }}" method="GET">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit" class="sig-btn">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>

        @if($sigLead)
            <section class="sig-panel" style="margin-top:22px">
                <div class="sig-panel__head"><h2>Günün yazısı</h2><span class="sig-code">{{ $sigLead->published_at?->format('d.m.Y') }}</span></div>
                <div class="sig-lead">
                    <div class="sig-lead__body">
                        <span class="sig-kicker">{{ $sigLead->author_name ?: 'Editör' }} · {{ $sigLead->published_at?->format('d.m.Y') }}</span>
                        <h2>{{ $sigLead->title }}</h2>
                        <p>{{ $sigLead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($sigLead->content), 200) }}</p>
                        <p style="margin-top:18px"><a class="sig-btn" href="{{ route('blog.show', $sigLead->slug) }}">Yazıyı oku →</a></p>
                    </div>
                    @if($sigLead->image)
                        <a class="sig-lead__media" href="{{ route('blog.show', $sigLead->slug) }}" aria-label="{{ $sigLead->title }} yazısını aç">
                            <img src="{{ asset('storage/'.$sigLead->image) }}" alt="{{ $sigLead->title }}" loading="eager">
                        </a>
                    @endif
                </div>
            </section>
        @endif

        <div class="sig-cols" style="margin-top:22px">
            <div>
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Yayın seçkisi</h2><span class="sig-code">{{ $posts->total() }} yazı</span></div>
                    <div class="sig-rows">
                        @forelse($posts as $post)
                            <article class="sig-row">
                                <a class="sig-row__mark" href="{{ route('blog.show', $post->slug) }}" aria-label="{{ $post->title }} yazısını aç">
                                    @if($post->image)
                                        <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                    @else
                                        Y
                                    @endif
                                </a>
                                <div class="sig-row__txt">
                                    <p class="sig-row__cat">{{ $post->author_name ?: 'Editör' }} / {{ $post->published_at?->format('d.m.Y') }}</p>
                                    <h3><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h3>
                                    <p class="sig-row__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 132) }}</p>
                                </div>
                                <a class="sig-row__go" href="{{ route('blog.show', $post->slug) }}">Oku →</a>
                            </article>
                        @empty
                            <div class="sig-empty">Bu aramada yazı bulunamadı.</div>
                        @endforelse
                    </div>
                    @if($posts->hasPages())<div class="sig-pag">{{ $posts->links() }}</div>@endif
                </section>
            </div>
            <aside class="sig-side">
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Keşfe devam</h2><span class="sig-code">İstasyon</span></div>
                    <div class="sig-links">
                        <a href="{{ route('companies.index') }}"><span class="sig-links__txt">Firmalar</span><span class="sig-links__go">›</span></a>
                        <a href="{{ route('jobs.index') }}"><span class="sig-links__txt">İş ilanları</span><span class="sig-links__go">›</span></a>
                        <a href="{{ route('packages.index') }}"><span class="sig-links__txt">Paketler</span><span class="sig-links__go">›</span></a>
                    </div>
                </section>
                <div class="sig-promo">
                    <span class="sig-kicker">Hikâyeniz</span>
                    <h2 style="margin-top:10px">Firmanızı sinyale taşıyın</h2>
                    <p>İşletme profilinizi oluşturun, yazılarda yer alın.</p>
                    <a class="sig-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
