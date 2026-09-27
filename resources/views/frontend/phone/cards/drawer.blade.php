{{-- CEP · çekmece kartı (açık arama paneli satırı) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phBadge = $company->hasActivePremium() ? 'Vitrin' : ($company->is_verified ? 'Doğrulanmış' : ($company->created_at?->gt(now()->subDays(14)) ? 'Yeni' : null));
@endphp
<a href="{{ route('companies.show', $company->slug) }}">
    <span class="ph-pull__ico" aria-hidden="true">{{ $company->logo ? '' : mb_substr($company->name, 0, 1) }}@if($company->logo)<img src="{{ asset('storage/' . $company->logo) }}" alt="" loading="lazy" style="width:100%;height:100%;object-fit:cover;border-radius:inherit">@endif</span>
    <span class="ph-pull__txt">
        <strong>{{ $company->name }}</strong>
        <span>{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}@if($phBadge) · {{ $phBadge }}@endif</span>
    </span>
    @if($phRate > 0)<kbd>{{ number_format($phRate, 1, ',', '.') }} ★</kbd>@else<kbd>↵</kbd>@endif
</a>
