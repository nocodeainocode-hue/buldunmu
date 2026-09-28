@php
    $elJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => $job->title,
        'eyebrow' => 'Kariyer / Açık pozisyon',
        'title' => 'İş ilanı',
    ])
    <div class="el-wrap">
        <dl class="el-meta" style="margin-top:34px">
            <div><dt>Çalışma biçimi</dt><dd>{{ $elJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <header style="border-top:1px solid var(--border);padding-top:24px;margin-bottom:34px">
            <span class="el-eyebrow">{{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</span>
            <h1 style="font-size:clamp(28px,4vw,42px);font-weight:500;margin-top:12px">{{ $job->title }}</h1>
        </header>
        <div class="el-cols">
            <aside class="el-side">
                @if($job->company)
                    <section class="el-box">
                        <div class="el-box__head"><h2>İlan sahibi</h2></div>
                        <div class="el-box__body">
                            <h3 style="font-size:22px;font-weight:500"><a href="{{ route('companies.show', $job->company->slug) }}" style="text-decoration:none;color:var(--primary)">{{ $job->company->name }}</a></h3>
                            <p style="margin-top:8px;color:var(--text_muted);font-size:14px">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                            <p style="margin-top:14px"><a href="{{ route('companies.show', $job->company->slug) }}" style="color:var(--accent);font-size:11px;letter-spacing:.14em;text-transform:uppercase;text-decoration:none">Firma profili →</a></p>
                        </div>
                    </section>
                @endif
                <div class="el-promo">
                    <h2>İlan yayınlamak ister misiniz?</h2>
                    <p>Firma panelinizden yeni pozisyon ekleyin, rehberde anında görünsün.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.dashboard') }}">Firma paneli</a>
                </div>
            </aside>
            <div>
                <article class="el-box">
                    <div class="el-box__head"><h2>Görev tanımı</h2></div>
                    <div class="el-prose" style="white-space:pre-line">{{ $job->description }}</div>
                    @if($job->apply_url || $job->apply_email)
                        <div style="display:flex;flex-wrap:wrap;gap:12px;margin-top:30px">
                            @if($job->apply_url)
                                <a class="el-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git</a>
                            @else
                                <a class="el-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur</a>
                            @endif
                            <a class="el-btn el-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                        </div>
                    @endif
                </article>
            </div>
        </div>
    </div>
</div>
