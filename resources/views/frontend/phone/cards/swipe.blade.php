{{-- CEP · swipe satırı (kart destesi için kompakt kayıt) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
@endphp
<a class="ph-row" href="{{ route('companies.show', $company->slug) }}">
    <span class="ph-row__av">
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu" loading="lazy">
        @else
            {{ mb_substr($company->name, 0, 1) }}
        @endif
    </span>
    <div class="ph-row__txt">
        <strong>{{ $company->name }}</strong>
        <span>{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}{{ $phRate > 0 ? ' · ' . number_format($phRate, 1, ',', '.') . '★' : '' }}</span>
    </div>
    @if($company->hasActivePremium())<span class="ph-fact">Vitrin</span>@endif
    <span class="ph-row__go" aria-hidden="true">›</span>
</a>
