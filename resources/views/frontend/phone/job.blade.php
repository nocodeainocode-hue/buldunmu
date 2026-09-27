{{-- CEP · iş ilanı detayı --}}
@php
    $phEmployment = match($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        'freelance' => 'Serbest',
        default => 'Tam zamanlı',
    };
    $phCompany = $job->company;
@endphp
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><a href="{{ route('jobs.index') }}">İş ilanları</a><span>/</span><span>{{ Str::limit($job->title, 26) }}</span>
    </nav>
    <span class="ph-eyebrow">{{ $phEmployment }} · {{ $job->published_at?->format('d.m.Y') }}</span>
    <h1>{{ $job->title }}</h1>
    <p>{{ $phCompany?->name ?? 'Firma' }} · {{ $job->location ?: ($phCompany?->city?->name ?? 'Türkiye') }}</p>
</div>

<div class="ph-quick">
    @if($job->apply_url)
        <a href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfası ↗</a>
    @elseif($job->apply_email)
        <a href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title . ' iş ilanı başvurusu') }}">E-posta ile başvur</a>
    @else
        <a href="{{ route('companies.show', $phCompany->slug) }}">Firmayı ara</a>
    @endif
    <a href="{{ route('jobs.index') }}">Tüm ilanlar</a>
</div>

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Görev tanımı</h2><span class="ph-meta">İlan</span></div>
    <div class="ph-prose" style="padding:14px;white-space:pre-line">{{ $job->description }}</div>
</section>

<dl class="ph-dl">
    <div><dt>Çalışma şekli</dt><dd>{{ $phEmployment }}</dd></div>
    <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
    <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Belirtilmemiş' }}</dd></div>
    <div><dt>Konum</dt><dd>{{ $job->location ?: ($phCompany?->city?->name ?? 'Türkiye') }}</dd></div>
</dl>

@if($phCompany)
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>İlan sahibi</h2><span class="ph-meta">Firma</span></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            <a class="ph-row" href="{{ route('companies.show', $phCompany->slug) }}">
                <span class="ph-row__av">
                    @if($phCompany->logo)<img src="{{ asset('storage/' . $phCompany->logo) }}" alt="{{ $phCompany->name }} logosu" loading="lazy">@else{{ mb_substr($phCompany->name, 0, 1) }}@endif
                </span>
                <div class="ph-row__txt">
                    <strong>{{ $phCompany->name }}</strong>
                    <span>{{ $phCompany->category?->name ?? 'İşletme' }} · {{ $phCompany->city?->name ?? 'Türkiye' }}</span>
                </div>
                <span class="ph-row__go">›</span>
            </a>
        </div>
    </section>
@endif

<div class="ph-cta">
    <h2>Benzer ilanları kaçırma.</h2>
    <p>Tüm açık pozisyonları tek akışta gez.</p>
    <a class="ph-btn" href="{{ route('jobs.index') }}">İlanlar →</a>
</div>
