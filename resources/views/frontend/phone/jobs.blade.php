{{-- CEP · iş ilanları listesi --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>İş ilanları</span>
    </nav>
    <span class="ph-eyebrow">Kariyer · {{ $jobs->total() }} açık pozisyon</span>
    <h1>İş ilanları</h1>
    <p>Yerel işletmelerin yayınladığı fırsatları gez, dilediğine doğrudan başvur.</p>
</div>

<form class="ph-filter" action="{{ route('jobs.index') }}" method="GET">
    <label class="ph-field">Pozisyon veya firma
        <input name="q" value="{{ request('q') }}" placeholder="Örneğin satış uzmanı">
    </label>
    <div style="display:grid;gap:8px">
        <button class="ph-btn" type="submit">Ara</button>
        @if(request()->filled('q'))<a class="ph-btn ph-btn--ghost" href="{{ route('jobs.index') }}">Temizle</a>@endif
    </div>
</form>

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Güncel ilanlar</h2><span class="ph-meta">{{ $jobs->total() }} fırsat</span></div>
    <div class="ph-list" style="padding:12px 12px 14px">
        @forelse($jobs as $job)
            @php
                $phEmployment = match($job->employment_type) {
                    'part_time' => 'Yarı zamanlı',
                    'contract' => 'Sözleşmeli',
                    'internship' => 'Staj',
                    'freelance' => 'Serbest',
                    default => 'Tam zamanlı',
                };
            @endphp
            <a class="ph-row" href="{{ route('jobs.show', $job->slug) }}">
                <span class="ph-row__av">{{ mb_substr($job->company->name ?? 'İ', 0, 1) }}</span>
                <div class="ph-row__txt">
                    <strong>{{ $job->title }}</strong>
                    <span>{{ $job->company->name ?? 'Firma' }} · {{ $job->location ?: ($job->company->city?->name ?? 'Türkiye') }}</span>
                    <span>{{ $phEmployment }} · {{ $job->published_at?->format('d.m.Y') }}</span>
                </div>
                <span class="ph-row__go">›</span>
            </a>
        @empty
            <div class="ph-empty">Şu anda açık iş ilanı bulunmuyor.</div>
        @endforelse
    </div>
    @if($jobs->hasPages())<div class="ph-pagination">{{ $jobs->links() }}</div>@endif
</section>

<div class="ph-cta">
    <h2>Ekibine adam arıyorsun.</h2>
    <p>Firma panelinden ilanını yayımla, adaylar sana tek dokunuşta ulaşsın.</p>
    <a class="ph-btn" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
</div>
