@php
    $depJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => $job->title,
        'eyebrow' => 'Mürettebat / Açık pozisyon',
        'title' => $job->title,
        'description' => ($job->company?->name ?? 'Firma').' · '.($job->location ?: ($job->company?->city?->name ?? 'Türkiye')),
    ])
    <div class="dep-wrap dep-page__body">
        <dl class="dep-ticketbar">
            <div><dt>Çalışma biçimi</dt><dd>{{ $depJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <div class="dep-columns">
            <div class="dep-main">
                <article class="dep-panel dep-article">
                    <span class="dep-kicker">Pozisyon künyesi</span>
                    <h2 class="dep-h2" style="margin-top:14px">Görev tanımı</h2>
                    <div class="dep-prose" style="white-space:pre-line">{{ $job->description }}</div>
                    @if($job->apply_url || $job->apply_email)
                        <div style="display:flex;flex-wrap:wrap;gap:11px;margin-top:28px">
                            @if($job->apply_url)
                                <a class="dep-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git →</a>
                            @else
                                <a class="dep-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur →</a>
                            @endif
                            <a class="dep-btn dep-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                        </div>
                    @endif
                </article>
            </div>
            <aside class="dep-side">
                @if($job->company)
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>İlan sahibi</h2><span class="dep-code">Firma</span></div>
                        <div class="dep-copy">
                            <h3 class="dep-h3"><a href="{{ route('companies.show', $job->company->slug) }}" style="text-decoration:none">{{ $job->company->name }}</a></h3>
                            <p style="margin-top:8px">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                            <p style="margin-top:10px"><a href="{{ route('companies.show', $job->company->slug) }}" style="color:var(--amber-deep);font-weight:600">Firma profilini aç →</a></p>
                        </div>
                    </section>
                @endif
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Kadro sizde</span>
                    <h2>İlan yayınlamak ister misiniz?</h2>
                    <p>Firma panelinizden yeni pozisyon ekleyin, panoda anında görünsün.</p>
                    <a class="dep-btn" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
