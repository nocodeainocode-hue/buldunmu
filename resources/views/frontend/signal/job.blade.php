@php
    $sigJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => $job->title,
        'eyebrow' => 'Personel / Açık pozisyon',
        'title' => $job->title,
        'description' => ($job->company?->name ?? 'Firma').' · '.($job->location ?: ($job->company?->city?->name ?? 'Türkiye')),
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <dl class="sig-meta sig-jobbar">
            <div><dt>Çalışma biçimi</dt><dd>{{ $sigJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <div class="sig-cols">
            <div>
                <article class="sig-panel">
                    <div class="sig-panel__body">
                        <span class="sig-kicker">Pozisyon künyesi</span>
                        <h2 style="margin:14px 0 10px;font-size:22px">Görev tanımı</h2>
                        <div class="sig-prose" style="white-space:pre-line">{{ $job->description }}</div>
                        @if($job->apply_url || $job->apply_email)
                            <div style="display:flex;flex-wrap:wrap;gap:11px;margin-top:26px">
                                @if($job->apply_url)
                                    <a class="sig-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git →</a>
                                @else
                                    <a class="sig-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur →</a>
                                @endif
                                <a class="sig-btn sig-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                            </div>
                        @endif
                    </div>
                </article>
            </div>
            <aside class="sig-side">
                @if($job->company)
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>İlan sahibi</h2><span class="sig-code">Firma</span></div>
                        <div class="sig-panel__body">
                            <h3 style="font-size:18px"><a href="{{ route('companies.show', $job->company->slug) }}" style="text-decoration:none">{{ $job->company->name }}</a></h3>
                            <p style="margin-top:8px;color:var(--text_muted)">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                            <p style="margin-top:10px"><a href="{{ route('companies.show', $job->company->slug) }}" style="color:var(--primary);font-weight:800">Firma profilini aç →</a></p>
                        </div>
                    </section>
                @endif
                <div class="sig-promo">
                    <span class="sig-kicker">Kadro sizde</span>
                    <h2 style="margin-top:10px">İlan yayınlamak ister misiniz?</h2>
                    <p>Firma panelinizden yeni pozisyon ekleyin, sinyalde anında görünsün.</p>
                    <a class="sig-btn" href="{{ route('owner.dashboard') }}">Firma paneli →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
