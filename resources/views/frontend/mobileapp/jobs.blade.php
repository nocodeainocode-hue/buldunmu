<div class="ap-search">
    <form action="{{ route('jobs.index') }}" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Pozisyon veya firma ara">
        <button class="ap-search__go" type="submit" aria-label="Ara">⌕</button>
    </form>
</div>
<div class="ap-sec">
    <div class="ap-sec__head">
        <h2>Açık pozisyonlar</h2>
        <span style="font-size:12px;color:var(--text_muted);font-weight:600">{{ $jobs->total() }} ilan</span>
    </div>
</div>
<div class="ap-list">
    @forelse($jobs as $job)
        <a class="ap-row" href="{{ route('jobs.show', $job->slug) }}">
            <span class="ap-row__av">{{ mb_substr($job->company?->name ?? 'İ', 0, 1) }}</span>
            <span class="ap-row__main">
                <h3>{{ $job->title }}</h3>
                <p class="ap-row__meta">{{ $job->company?->name ?? 'Firma' }} · {{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
            </span>
            <span class="ap-row__chev">›</span>
        </a>
    @empty
        <div class="ap-empty">Şu anda açık iş ilanı yok. Yeni ilanlar borsaya düşecek.</div>
    @endforelse
</div>
@if($jobs->hasPages())<div class="ap-pag">{{ $jobs->links() }}</div>@endif
<div class="ap-note">Firmanıza ait pozisyonları <a href="{{ route('owner.dashboard') }}" style="color:var(--primary);font-weight:700">firma panelinden</a> yayınlayın.</div>
