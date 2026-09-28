@php
    $ibJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => $job->title,
        'eyebrow' => 'Personel / Açık pozisyon',
        'title' => 'İş ilanı',
    ])
    <div class="ib-wrap">
        <dl class="ib-meta" style="margin-top:14px">
            <div><dt>Çalışma biçimi</dt><dd>{{ $ibJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <div class="ib-jobbar">
            <h1 style="flex:1 1 auto;min-width:0">{{ $job->title }}</h1>
            <span style="font-size:12.5px;color:var(--text_muted)">{{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</span>
        </div>
        <div class="ib-cols">
            <aside class="ib-side">
                @if($job->company)
                    <section class="ib-box">
                        <div class="ib-box__head"><h2>İlan sahibi</h2></div>
                        <div class="ib-box__body">
                            <h3 style="font-size:15px"><a href="{{ route('companies.show', $job->company->slug) }}" style="text-decoration:none;color:var(--primary_hover)">{{ $job->company->name }}</a></h3>
                            <p style="margin-top:6px;color:var(--text_muted);font-size:12.5px">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                            <p style="margin-top:8px"><a href="{{ route('companies.show', $job->company->slug) }}" style="color:var(--primary);font-weight:700;font-size:13px">Firma profilini aç ›</a></p>
                        </div>
                    </section>
                @endif
                <div class="ib-promo">
                    <h2>İlan yayınlamak ister misiniz?</h2>
                    <p>Firma panelinizden yeni pozisyon ekleyin, borsada anında görünsün.</p>
                    <a class="ib-btn" href="{{ route('owner.dashboard') }}">Firma paneli</a>
                </div>
            </aside>
            <div>
                <article class="ib-box">
                    <div class="ib-box__body">
                        <h2 style="font-size:16px;color:var(--primary_hover);margin-bottom:10px">Görev tanımı</h2>
                        <div class="ib-prose" style="white-space:pre-line">{{ $job->description }}</div>
                        @if($job->apply_url || $job->apply_email)
                            <div style="display:flex;flex-wrap:wrap;gap:8px;margin-top:20px">
                                @if($job->apply_url)
                                    <a class="ib-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git</a>
                                @else
                                    <a class="ib-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur</a>
                                @endif
                                <a class="ib-btn ib-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                            </div>
                        @endif
                    </div>
                </article>
            </div>
        </div>
    </div>
</div>
