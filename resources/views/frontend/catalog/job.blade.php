@php
    $katJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => $job->title,
        'eyebrow' => 'Kariyer / Açık pozisyon',
        'title' => $job->title,
        'description' => ($job->company?->name ?? 'Firma').' · '.($job->location ?: ($job->company?->city?->name ?? 'Türkiye')),
    ])
    <div class="kat-wrap">
        <dl class="kat-colophon">
            <div><dt>Çalışma biçimi</dt><dd>{{ $katJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <div class="kat-cols">
            <div>
                <article class="kat-panel">
                    <div class="kat-panel__head"><h2>Görev tanımı</h2><span>Pozisyon</span></div>
                    <div class="kat-panel__body">
                        <div class="kat-prose" style="white-space:pre-line">{{ $job->description }}</div>
                        @if($job->apply_url || $job->apply_email)
                            <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:30px">
                                @if($job->apply_url)
                                    <a class="kat-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git →</a>
                                @else
                                    <a class="kat-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur →</a>
                                @endif
                                <a class="kat-btn kat-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                            </div>
                        @endif
                    </div>
                </article>
            </div>
            <aside class="kat-side">
                @if($job->company)
                    <section class="kat-panel kat-panel--card">
                        <div class="kat-panel__head"><h2>İlan sahibi</h2><span>Firma</span></div>
                        <div class="kat-panel__body">
                            <h3 style="font-size:26px"><a href="{{ route('companies.show', $job->company->slug) }}" style="text-decoration:none">{{ $job->company->name }}</a></h3>
                            <p class="kat-muted" style="margin-top:8px">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                            <p style="margin-top:16px"><a class="kat-link" href="{{ route('companies.show', $job->company->slug) }}">Firma profilini aç →</a></p>
                        </div>
                    </section>
                @endif
                <div class="kat-promo">
                    <span class="kat-kicker">Kadro sizde</span>
                    <h2>İlan <em>yayınlamak</em> ister misiniz?</h2>
                    <p>Firma panelinizden yeni pozisyon ekleyin, katalogda anında görünsün.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
