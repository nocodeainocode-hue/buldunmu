<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => 'İş ilanları',
        'eyebrow' => 'Mürettebat / Açık pozisyonlar',
        'title' => 'Kadroya alınan seferler',
        'description' => 'Panodaki firmaların güncel iş ilanlarını inceleyin, doğrudan başvurun.',
    ])
    <div class="dep-wrap dep-page__body">
        <form class="dep-filter" action="{{ route('jobs.index') }}" method="GET">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı"></label>
            <button type="submit">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="dep-columns">
            <div class="dep-main">
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Güncel ilanlar</h2><span class="dep-code">{{ $jobs->total() }} pozisyon</span></div>
                    @forelse($jobs as $job)
                        <article class="dep-row">
                            <a class="dep-row__mark" href="{{ route('jobs.show', $job->slug) }}" aria-label="{{ $job->title }} ilanını aç">İ</a>
                            <div>
                                <p class="dep-code" style="color:var(--amber-deep)">{{ $job->company?->name ?? 'Firma' }} / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                                <h3 class="dep-h3" style="margin:5px 0"><a href="{{ route('jobs.show', $job->slug) }}" style="text-decoration:none">{{ $job->title }}</a></h3>
                                <p class="dep-card__text">{{ \Illuminate\Support\Str::limit($job->description, 138) }}</p>
                            </div>
                            <a class="dep-row__go" href="{{ route('jobs.show', $job->slug) }}">İlanı aç →</a>
                        </article>
                    @empty
                        <div class="dep-empty">Şu anda açık iş ilanı yok. Yeni ilanlar panoya düşecek.</div>
                    @endforelse
                    @if($jobs->hasPages())<div class="dep-pagination">{{ $jobs->links() }}</div>@endif
                </section>
            </div>
            <aside class="dep-side">
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Kadro sizde</span>
                    <h2>İlanınızı yayınlayın</h2>
                    <p>Firmanıza ait açık pozisyonları firma panelinden panoya ekleyin.</p>
                    <a class="dep-btn" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Keşfe devam</h2><span class="dep-code">Panolar</span></div>
                    <div class="dep-link-list">
                        <a href="{{ route('companies.index') }}"><span>Firmalar</span><b>→</b></a>
                        <a href="{{ route('blog.index') }}"><span>Şehir yazıları</span><b>→</b></a>
                        <a href="{{ route('packages.index') }}"><span>Premium paketler</span><b>→</b></a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
