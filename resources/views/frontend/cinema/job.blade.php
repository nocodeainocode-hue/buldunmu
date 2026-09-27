<div class="cinema cinema-page">
    @include('partials.cinema.page-hero', ['crumb' => 'İş ilanı / '.$job->title, 'eyebrow' => 'Kariyer / Açık pozisyon', 'title' => $job->title, 'description' => $job->company->name.' · '.($job->location ?: $job->company->city?->name)])
    <div class="cinema-wrap cinema-page__body"><div class="cinema-columns"><div class="cinema-main"><article class="cinema-panel cinema-article">
        <span class="cinema-kicker">{{ match($job->employment_type) {'part_time'=>'Yarı zamanlı','contract'=>'Sözleşmeli','internship'=>'Staj',default=>'Tam zamanlı'} }} / {{ $job->published_at?->format('d.m.Y') }}</span>
        <h2 class="cinema-small-title" style="margin-top:16px">Pozisyon hakkında</h2>
        <div class="cinema-prose" style="white-space:pre-line">{{ $job->description }}</div>
        @if($job->expires_at)<p style="margin-top:20px;color:#6c625a">Son başvuru: {{ $job->expires_at->format('d.m.Y') }}</p>@endif
        @if($job->apply_url || $job->apply_email)<div style="margin-top:28px">@if($job->apply_url)<a class="cinema-btn" href="{{ $job->apply_url }}" target="_blank" rel="noopener noreferrer">Başvuru sayfasına git ↗</a>@else<a class="cinema-btn" href="mailto:{{ $job->apply_email }}?subject={{ rawurlencode($job->title.' iş ilanı başvurusu') }}">E-posta ile başvur ↗</a>@endif</div>@endif
    </article></div><aside class="cinema-side"><section class="cinema-panel"><div class="cinema-panel__head"><h2>İlan sahibi</h2></div><div class="cinema-copy"><strong><a href="{{ route('companies.show',$job->company->slug) }}">{{ $job->company->name }}</a></strong><p>{{ $job->company->city?->name }}</p><p><a href="{{ route('companies.show',$job->company->slug) }}">Firma profilini aç ↗</a></p></div></section><div class="cinema-promo"><h2>Başka fırsatlar.</h2><p>Tüm açık pozisyonlara dönün.</p><a class="cinema-btn" href="{{ route('jobs.index') }}">İlanlar ↗</a></div></aside></div></div>
</div>
