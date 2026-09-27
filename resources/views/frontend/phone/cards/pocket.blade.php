{{-- CEP · pocket kartı (2 sütun ızgara) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phBadge = $company->hasActivePremium() ? 'Vitrin' : ($company->is_verified ? 'Doğru' : ($company->created_at?->gt(now()->subDays(14)) ? 'Yeni' : null));
@endphp
<a class="ph-card" href="{{ route('companies.show', $company->slug) }}">
    <div class="ph-card__media">
        @if($company->cover_image || $company->logo)
            <img src="{{ asset('storage/' . ($company->cover_image ?: $company->logo)) }}" alt="{{ $company->name }} görseli" loading="lazy">
        @else
            <span class="ph-card__initial" aria-hidden="true">{{ mb_substr($company->name, 0, 1) }}</span>
        @endif
        @if($phBadge)<span class="ph-card__badge">{{ $phBadge }}</span>@endif
        @if($phRate > 0)<span class="ph-card__rate">{{ number_format($phRate, 1, ',', '.') }} ★</span>@endif
    </div>
    <div class="ph-card__body">
        <h3>{{ $company->name }}</h3>
        <p>{{ $company->category?->name ?? 'İşletme' }}</p>
        <div class="ph-card__foot">
            <span>{{ $company->district?->name ?? $company->city?->name ?? 'Türkiye' }}</span>
            <span style="color:var(--ph-primary)">Aç ›</span>
        </div>
    </div>
</a>
