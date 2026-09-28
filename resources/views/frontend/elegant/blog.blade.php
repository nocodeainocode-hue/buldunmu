@php
    $elLead = $posts->isNotEmpty() ? $posts->first() : null;
@endphp
<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => 'Yazılar',
        'eyebrow' => 'Editöryal içerikler',
        'title' => 'Rehber yazıları',
        'description' => $directory?->editorial_voice ?: 'İşletmeler, hizmetler ve doğru kararlar üzerine özenle hazırlanmış yazılar.',
    ])
    <div class="el-wrap">
        <form class="el-filters" action="{{ route('blog.index') }}" method="GET" style="margin-top:34px">
            <label>Yazılarda ara<input type="search" name="q" value="{{ request('q') }}" placeholder="Başlık veya konu"></label>
            <button type="submit" class="el-btn">Ara</button>
            @if(request()->filled('q'))<a href="{{ route('blog.index') }}">Temizle</a>@endif
        </form>

        @if($elLead)
            <section class="el-box" style="margin-top:16px">
                <div class="el-box__head"><span class="el-eyebrow">Günün yazısı</span><span class="el-box__note">{{ $elLead->published_at?->format('d.m.Y') }}</span></div>
                <div style="display:flex;gap:32px;flex-wrap:wrap;align-items:stretch">
                    @if($elLead->image)
                        <a href="{{ route('blog.show', $elLead->slug) }}" aria-label="{{ $elLead->title }} yazısını aç" style="flex:0 1 380px;display:block;min-height:220px;overflow:hidden">
                            <img src="{{ asset('storage/'.$elLead->image) }}" alt="{{ $elLead->title }}" loading="eager" style="width:100%;height:100%;object-fit:cover">
                        </a>
                    @endif
                    <div style="flex:1 1 320px;min-width:0;display:flex;flex-direction:column;justify-content:center">
                        <p style="font-size:12px;letter-spacing:.12em;text-transform:uppercase;color:var(--text_muted)">{{ $elLead->author_name ?: 'Editör' }} · {{ $elLead->published_at?->format('d.m.Y') }}</p>
                        <h2 style="font-size:34px;font-weight:500;margin-top:12px"><a href="{{ route('blog.show', $elLead->slug) }}" style="text-decoration:none;color:var(--primary)">{{ $elLead->title }}</a></h2>
                        <p style="margin-top:14px;font-size:16px;color:var(--text_muted);font-weight:300;line-height:1.7">{{ $elLead->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($elLead->content), 220) }}</p>
                        <p style="margin-top:22px"><a class="el-btn el-btn--gold" href="{{ route('blog.show', $elLead->slug) }}">Yazıyı okuyun</a></p>
                    </div>
                </div>
            </section>
        @endif

        <div class="el-cols" style="margin-top:44px">
            <aside class="el-side">
                <section class="el-box">
                    <div class="el-box__head"><h2>Keşfe devam</h2></div>
                    <div class="el-links">
                        <a href="{{ route('companies.index') }}"><span class="el-links__txt">Firmalar</span><span class="el-links__count">→</span></a>
                        <a href="{{ route('jobs.index') }}"><span class="el-links__txt">İş ilanları</span><span class="el-links__count">→</span></a>
                        <a href="{{ route('packages.index') }}"><span class="el-links__txt">Paketler</span><span class="el-links__count">→</span></a>
                    </div>
                </section>
                <div class="el-promo">
                    <h2>Firmanızı gösterin</h2>
                    <p>İşletme profilinizi oluşturun, rehber içeriklerde yer alın.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                <div class="el-box__head"><h2>Tüm yazılar</h2><span class="el-box__note">{{ $posts->total() }} yazı</span></div>
                <div class="el-items">
                    @forelse($posts as $post)
                        <a class="el-item" href="{{ route('blog.show', $post->slug) }}">
                            <span class="el-item__thumb">
                                @if($post->image)
                                    <img src="{{ asset('storage/'.$post->image) }}" alt="{{ $post->title }}" loading="lazy">
                                @else
                                    ✦
                                @endif
                            </span>
                            <span class="el-item__body">
                                <span class="el-item__meta"><b>{{ $post->author_name ?: 'Editör' }}</b> · {{ $post->published_at?->format('d.m.Y') }}</span>
                                <h3>{{ $post->title }}</h3>
                                <span class="el-item__text">{{ $post->excerpt ?: \Illuminate\Support\Str::limit(strip_tags($post->content), 150) }}</span>
                            </span>
                            <span class="el-item__side">
                                <span class="el-item__date">{{ $post->published_at?->format('d.m.Y') }}</span>
                                <span class="el-item__go">Okuyun →</span>
                            </span>
                        </a>
                    @empty
                        <div class="el-empty">Bu aramada yazı bulunamadı.</div>
                    @endforelse
                </div>
                @if($posts->hasPages())<div class="el-pag">{{ $posts->links() }}</div>@endif
            </div>
        </div>
    </div>
</div>
