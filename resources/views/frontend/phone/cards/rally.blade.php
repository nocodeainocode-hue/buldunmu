{{-- CEP · rampa kartı (numaralı sıralama satırı) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phBadge = $company->hasActivePremium() ? 'Vitrin' : ($company->is_verified ? '✓' : 'Yeni');
@endphp
<a href="{{ route('companies.show', $company->slug) }}">
    <span class="ph-rally__txt">
        <strong>{{ $company->name }}</strong>
        <span>{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}@if($phRate > 0) · {{ number_format($phRate, 1, ',', '.') }} ★@endif</span>
    </span>
    <span class="ph-rally__go" aria-hidden="true">›</span>
</a>
