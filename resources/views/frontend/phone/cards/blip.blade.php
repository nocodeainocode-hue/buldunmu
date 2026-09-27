{{-- CEP · sinyal kartı (radar blip satırı) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phStrength = min(4, max(1, (int) ceil(($phRate / 5) * 4) + ($company->hasActivePremium() ? 1 : 0)));
    $phDistance = (string) (($company->id % 7) + 1);
@endphp
<a class="ph-blip" href="{{ route('companies.show', $company->slug) }}">
    <span class="ph-blip__dot" aria-hidden="true"></span>
    <span class="ph-blip__txt">
        <strong>{{ $company->name }}</strong>
        <span>{{ $company->category?->name ?? 'İŞLETME' }} · {{ $company->city?->name ?? 'TÜRKİYE' }} · ~{{ $phDistance }}.0 km</span>
    </span>
    <span class="ph-blip__sig" aria-hidden="true">
        @for($i = 1; $i <= 4; $i++)<i style="@if($i > $phStrength)opacity:.18;@endif"></i>@endfor
    </span>
</a>
