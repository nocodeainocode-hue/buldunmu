<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => 'İş ilanları',
        'eyebrow' => 'Personel / Açık pozisyonlar',
        'title' => 'İstasyona alınan pozisyonlar',
        'description' => 'Sinyaldeki firmaların güncel iş ilanlarını inceleyin, doğrudan başvurun.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <form class="sig-filters" action="{{ route('jobs.index') }}" method="GET">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı"></label>
            <button type="submit" class="sig-btn">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="sig-cols">
            <div>
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Güncel ilanlar</h2><span class="sig-code">{{ $jobs->total() }} pozisyon</span></div>
                    <div class="sig-rows">
                        @forelse($jobs as $job)
                            <article class="sig-row">
                                <a class="sig-row__mark" href="{{ route('jobs.show', $job->slug) }}" aria-label="{{ $job->title }} ilanını aç">İ</a>
                                <div class="sig-row__txt">
                                    <p class="sig-row__cat">{{ $job->company?->name ?? 'Firma' }} / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                                    <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                                    <p class="sig-row__text">{{ \Illuminate\Support\Str::limit($job->description, 138) }}</p>
                                </div>
                                <a class="sig-row__go" href="{{ route('jobs.show', $job->slug) }}">İlanı aç →</a>
                            </article>
                        @empty
                            <div class="sig-empty">Şu anda açık iş ilanı yok. Yeni ilanlar sinyale düşecek.</div>
                        @endforelse
                    </div>
                    @if($jobs->hasPages())<div class="sig-pag">{{ $jobs->links() }}</div>@endif
                </section>
            </div>
            <aside class="sig-side">
                <div class="sig-promo">
                    <span class="sig-kicker">Kadro sizde</span>
                    <h2 style="margin-top:10px">İlanınızı yayınlayın</h2>
                    <p>Firmanıza ait açık pozisyonları firma panelinden sinyale ekleyin.</p>
                    <a class="sig-btn" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Keşfe devam</h2><span class="sig-code">İstasyon</span></div>
                    <div class="sig-links">
                        <a href="{{ route('companies.index') }}"><span class="sig-links__txt">Firmalar</span><span class="sig-links__go">›</span></a>
                        <a href="{{ route('blog.index') }}"><span class="sig-links__txt">Şehir yazıları</span><span class="sig-links__go">›</span></a>
                        <a href="{{ route('packages.index') }}"><span class="sig-links__txt">Premium paketler</span><span class="sig-links__go">›</span></a>
                    </div>
                </section>
            </aside>
        </div>
    </div>
</div>
