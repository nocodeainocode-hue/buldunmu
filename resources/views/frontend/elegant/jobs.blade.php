<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => 'İş ilanları',
        'eyebrow' => 'Kariyer / Açık pozisyonlar',
        'title' => 'Güncel iş ilanları',
        'description' => 'Rehberdeki seçkin firmaların açık pozisyonlarını inceleyin, doğrudan başvurun.',
    ])
    <div class="el-wrap">
        <form class="el-filters" action="{{ route('jobs.index') }}" method="GET" style="margin-top:34px">
            <label>Pozisyon veya firma<input type="search" name="q" value="{{ request('q') }}" placeholder="Örneğin satış danışmanı"></label>
            <button type="submit" class="el-btn">İlan ara</button>
            @if(request()->filled('q'))<a href="{{ route('jobs.index') }}">Temizle</a>@endif
        </form>
        <div class="el-cols">
            <aside class="el-side">
                <div class="el-promo">
                    <h2>İlanınızı yayınlayın</h2>
                    <p>Firmanıza ait açık pozisyonları firma panelinden ekleyin.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.dashboard') }}">Firma paneli</a>
                </div>
                <section class="el-box">
                    <div class="el-box__head"><h2>Keşfe devam</h2></div>
                    <div class="el-links">
                        <a href="{{ route('companies.index') }}"><span class="el-links__txt">Firmalar</span><span class="el-links__count">→</span></a>
                        <a href="{{ route('blog.index') }}"><span class="el-links__txt">Rehber yazıları</span><span class="el-links__count">→</span></a>
                        <a href="{{ route('packages.index') }}"><span class="el-links__txt">Premium paketler</span><span class="el-links__count">→</span></a>
                    </div>
                </section>
            </aside>
            <div>
                <div class="el-box__head"><h2>Açık pozisyonlar</h2><span class="el-box__note">{{ $jobs->total() }} ilan</span></div>
                <div class="el-items">
                    @forelse($jobs as $job)
                        <a class="el-item" href="{{ route('jobs.show', $job->slug) }}">
                            <span class="el-item__thumb">‹›</span>
                            <span class="el-item__body">
                                <span class="el-item__meta"><b>{{ $job->company?->name ?? 'Firma' }}</b> · {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</span>
                                <h3>{{ $job->title }}</h3>
                                <span class="el-item__text">{{ \Illuminate\Support\Str::limit($job->description, 150) }}</span>
                            </span>
                            <span class="el-item__side">
                                <span class="el-item__date">{{ $job->published_at?->format('d.m.Y') }}</span>
                                <span class="el-item__go">İlanı açın →</span>
                            </span>
                        </a>
                    @empty
                        <div class="el-empty">Şu anda açık iş ilanı yok. Yeni pozisyonlar yakında eklenecek.</div>
                    @endforelse
                </div>
                @if($jobs->hasPages())<div class="el-pag">{{ $jobs->links() }}</div>@endif
            </div>
        </div>
    </div>
</div>
