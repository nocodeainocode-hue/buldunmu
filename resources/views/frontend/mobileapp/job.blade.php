@php
    $apJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="ap-sec">
    <span class="ap-kicker">Açık pozisyon</span>
    <h1 style="font-size:22px;font-weight:800;margin-top:6px">{{ $job->title }}</h1>
    <p style="margin-top:6px;font-size:13px;color:var(--text_muted)">{{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</p>
</div>
<dl class="ap-facts">
    <div><dt>Çalışma biçimi</dt><dd>{{ $apJobType }}</dd></div>
    <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
    <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
    <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
</dl>
<div class="ap-sec"><div class="ap-sec__head"><h2>Görev tanımı</h2></div></div>
<div class="ap-prose" style="white-space:pre-line">{{ $job->description }}</div>
@if($job->apply_url || $job->apply_email)
    <div class="ap-actions" style="padding:0 16px 8px">
        @if($job->apply_url)
            <a class="ap-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvur</a>
        @else
            <a class="ap-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur</a>
        @endif
        <a class="ap-btn ap-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
    </div>
@endif
@if($job->company)
    <div class="ap-note">İlan sahibi: <a href="{{ route('companies.show', $job->company->slug) }}" style="color:var(--primary);font-weight:700">{{ $job->company->name }}</a> profilini inceleyin.</div>
@endif
