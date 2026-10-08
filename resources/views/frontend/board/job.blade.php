@php
    $bdJobType = match ($job->employment_type) {
        'part_time' => 'Yarı zamanlı',
        'contract' => 'Sözleşmeli',
        'internship' => 'Staj',
        default => 'Tam zamanlı',
    };
@endphp
<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => $job->title,
            'tag' => '💼 '.$bdJobType,
            'title' => $job->title,
            'description' => ($job->company?->name ?? 'Firma').' · '.($job->location ?: ($job->company?->city?->name ?? 'Türkiye')),
        ])
        <dl class="bd-facts" style="margin-top:0;margin-bottom:22px">
            <div><dt>Çalışma biçimi</dt><dd>{{ $bdJobType }}</dd></div>
            <div><dt>İlan sahibi</dt><dd>@if($job->company)<a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a>@else — @endif</dd></div>
            <div><dt>Yayın tarihi</dt><dd>{{ $job->published_at?->format('d.m.Y') ?? '—' }}</dd></div>
            <div><dt>Son başvuru</dt><dd>{{ $job->expires_at?->format('d.m.Y') ?? 'Başvurular açık' }}</dd></div>
        </dl>
        <div class="bd-cols">
            <div>
                <article class="bd-panel">
                    <h2>Görev tanımı</h2>
                    <div class="bd-prose" style="white-space:pre-line">{{ $job->description }}</div>
                    @if($job->apply_url || $job->apply_email)
                        <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:24px">
                            @if($job->apply_url)
                                <a class="bd-btn bd-btn--hot" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git →</a>
                            @else
                                <a class="bd-btn bd-btn--hot" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur →</a>
                            @endif
                            <a class="bd-btn bd-btn--ghost" href="{{ route('jobs.index') }}">Diğer ilanlar</a>
                        </div>
                    @endif
                </article>
            </div>
            <aside class="bd-side">
                @if($job->company)
                    <section class="bd-contact">
                        <h2>İlan sahibi</h2>
                        <h3 style="font-size:22px;margin-top:10px"><a href="{{ route('companies.show', $job->company->slug) }}">{{ $job->company->name }}</a></h3>
                        <p class="bd-muted" style="margin-top:6px">{{ $job->company->city?->name ?? 'Türkiye' }}{{ $job->company->category?->name ? ' · '.$job->company->category->name : '' }}</p>
                        <div class="bd-contact__btns"><a class="bd-btn bd-btn--ghost" href="{{ route('companies.show', $job->company->slug) }}">Firma ilanını aç</a></div>
                    </section>
                @endif
                <div class="bd-cta"><h2>İlan vermek ister misin?</h2><p>Firma panelinden yeni pozisyon ekle.</p><a class="bd-btn" href="{{ route('owner.dashboard') }}">Firma paneli</a></div>
            </aside>
        </div>
    </div>
</div>
