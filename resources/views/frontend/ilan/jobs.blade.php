<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => 'İş ilanları',
        'eyebrow' => 'Personel / Açık pozisyonlar',
        'title' => 'Güncel iş ilanları',
        'description' => 'Rehberdeki firmaların açık pozisyonlarını inceleyin, doğrudan başvurun.',
    ])
    <div class="ib-wrap">
        <form class="ib-filters" action="{{ route('jobs.index') }}" method="GET" style="margin-top:14px">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı"></label>
            <button type="submit" class="ib-btn">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="ib-cols">
            <aside class="ib-side">
                <div class="ib-promo">
                    <h2>İlanınızı yayınlayın</h2>
                    <p>Firmanıza ait açık pozisyonları firma panelinden ekleyin.</p>
                    <a class="ib-btn" href="{{ route('owner.dashboard') }}">Firma paneli</a>
                </div>
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Keşfe devam</h2></div>
                    <div class="ib-links">
                        <a href="{{ route('companies.index') }}"><span class="ib-links__txt">Firmalar</span><span class="ib-links__count">›</span></a>
                        <a href="{{ route('blog.index') }}"><span class="ib-links__txt">Rehber yazıları</span><span class="ib-links__count">›</span></a>
                        <a href="{{ route('packages.index') }}"><span class="ib-links__txt">Premium paketler</span><span class="ib-links__count">›</span></a>
                    </div>
                </section>
            </aside>
            <div>
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Açık pozisyonlar</h2><span class="ib-box__note">{{ $jobs->total() }} ilan</span></div>
                    <div class="ib-items">
                        @forelse($jobs as $job)
                            <article class="ib-item">
                                <a class="ib-item__thumb" href="{{ route('jobs.show', $job->slug) }}" aria-label="{{ $job->title }} ilanını aç">İ</a>
                                <div class="ib-item__body">
                                    <p class="ib-item__meta"><b>{{ $job->company?->name ?? 'Firma' }}</b> / {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
                                    <h3><a href="{{ route('jobs.show', $job->slug) }}">{{ $job->title }}</a></h3>
                                    <p class="ib-item__text">{{ \Illuminate\Support\Str::limit($job->description, 140) }}</p>
                                </div>
                                <div class="ib-item__side">
                                    <span class="ib-item__date">{{ $job->published_at?->format('d.m.Y') }}</span>
                                    <a class="ib-item__go" href="{{ route('jobs.show', $job->slug) }}">İlanı aç ›</a>
                                </div>
                            </article>
                        @empty
                            <div class="ib-empty">Şu anda açık iş ilanı yok. Yeni ilanlar borsaya düşecek.</div>
                        @endforelse
                    </div>
                    @if($jobs->hasPages())<div class="ib-pag">{{ $jobs->links() }}</div>@endif
                </section>
            </div>
        </div>
    </div>
</div>
