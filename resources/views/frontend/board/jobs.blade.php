<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => 'İş ilanları',
            'tag' => '💼 Kariyer',
            'title' => 'Açık pozisyonlar',
            'description' => 'Panodaki firmaların güncel iş ilanlarını incele, doğrudan başvur.',
        ])
        <form class="bd-filters" action="{{ route('jobs.index') }}" method="GET">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı"></label>
            <button type="submit" class="bd-btn">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="bd-cols">
            <div>
                <p class="bd-view__count" style="margin-bottom:12px"><strong>{{ $jobs->total() }}</strong> pozisyon</p>
                <div class="bd-items">
                    @forelse($jobs as $job)
                        <article class="bd-item">
                            <div class="bd-item__thumb">{{ mb_strtoupper(mb_substr($job->company?->name ?? 'İ', 0, 1)) }}</div>
                            <div class="bd-item__body">
                                <div class="bd-item__meta"><b>{{ $job->company?->name ?? 'Firma' }}</b><span>·</span><span>{{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</span></div>
                                <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                                <p class="bd-item__text">{{ \Illuminate\Support\Str::limit($job->description, 150) }}</p>
                            </div>
                            <div class="bd-item__act"><a class="bd-btn bd-btn--hot bd-btn--sm" href="{{ route('jobs.show', $job->slug) }}">İlanı aç</a></div>
                        </article>
                    @empty
                        <div class="bd-empty">Şu anda açık iş ilanı yok.</div>
                    @endforelse
                </div>
                @if($jobs->hasPages())<div class="bd-pag">{{ $jobs->links() }}</div>@endif
            </div>
            <aside class="bd-side">
                <div class="bd-cta"><h2>Eleman mı arıyorsun?</h2><p>Açık pozisyonlarını firma panelinden ekle.</p><a class="bd-btn" href="{{ route('owner.dashboard') }}">Firma paneli</a></div>
                <section class="bd-box"><div class="bd-box__head"><h2>Panoya dön</h2></div><div class="bd-links">
                    <a href="{{ route('companies.index') }}"><span class="bd-links__txt">Tüm ilanlar</span><span class="bd-links__n">→</span></a>
                    <a href="{{ route('blog.index') }}"><span class="bd-links__txt">Yazılar</span><span class="bd-links__n">→</span></a>
                    <a href="{{ route('packages.index') }}"><span class="bd-links__txt">Paketler</span><span class="bd-links__n">→</span></a>
                </div></section>
            </aside>
        </div>
    </div>
</div>
