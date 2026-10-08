<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => 'İş ilanları',
        'eyebrow' => 'Kariyer / Açık pozisyonlar',
        'title' => 'Açık pozisyonlar',
        'description' => 'Katalogdaki firmaların güncel iş ilanlarını inceleyin, doğrudan başvurun.',
        'folio' => str_pad((string) $jobs->total(), 2, '0', STR_PAD_LEFT),
    ])
    <div class="kat-wrap">
        <form class="kat-filters" action="{{ route('jobs.index') }}" method="GET">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı"></label>
            <button type="submit" class="kat-btn">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="kat-cols">
            <div>
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Güncel ilanlar</h2><span>{{ $jobs->total() }} pozisyon</span></div>
                    <div class="kat-entries" style="border-top:0">
                        @forelse($jobs as $job)
                            <article class="kat-entry">
                                <span class="kat-entry__no">{{ str_pad((string) (($jobs->firstItem() ?? 1) - 1 + $loop->iteration), 2, '0', STR_PAD_LEFT) }}</span>
                                <a class="kat-entry__mark" href="{{ route('jobs.show', $job->slug) }}" aria-label="{{ $job->title }} ilanını aç">{{ mb_strtoupper(mb_substr($job->company?->name ?? 'İ', 0, 1)) }}</a>
                                <div>
                                    <p class="kat-entry__cat">{{ $job->company?->name ?? 'Firma' }} / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                                    <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                                    <p class="kat-entry__text">{{ \Illuminate\Support\Str::limit($job->description, 150) }}</p>
                                </div>
                                <a class="kat-entry__go" href="{{ route('jobs.show', $job->slug) }}">İlanı aç →</a>
                            </article>
                        @empty
                            <div class="kat-empty">Şu anda açık iş ilanı yok. Yeni ilanlar burada yayınlanacak.</div>
                        @endforelse
                    </div>
                    @if($jobs->hasPages())<div class="kat-pag">{{ $jobs->links() }}</div>@endif
                </section>
            </div>
            <aside class="kat-side">
                <div class="kat-promo">
                    <span class="kat-kicker">Kadro sizde</span>
                    <h2>İlanınızı <em>yayınlayın</em></h2>
                    <p>Firmanıza ait açık pozisyonları firma panelinden ekleyin.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Keşfe devam</h2><span>Katalog</span></div>
                    <div class="kat-links">
                        <a href="{{ route('companies.index') }}"><span class="kat-links__txt">Firmalar</span><span class="kat-links__go">→</span></a>
                        <a href="{{ route('blog.index') }}"><span class="kat-links__txt">Yazılar</span><span class="kat-links__go">→</span></a>
                        <a href="{{ route('packages.index') }}"><span class="kat-links__txt">Premium paketler</span><span class="kat-links__go">→</span></a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
