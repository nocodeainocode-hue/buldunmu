{{-- CEP · reels kartı (tam ekran dikey akış) --}}
@php
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phBadge = $company->hasActivePremium() ? 'Öne çıkan' : ($company->is_verified ? 'Doğrulanmış' : ($company->created_at?->gt(now()->subDays(14)) ? 'Yeni' : null));
@endphp
<a class="ph-reel" href="{{ route('companies.show', $company->slug) }}">
    <div class="ph-reel__media @if(!$company->cover_image) ph-reel__media--ph @endif">
        @if($company->cover_image)
            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="lazy">
        @endif
    </div>
    <div class="ph-reel__side" aria-hidden="true">
        <span class="ph-reel__act">↗</span>
        <span class="ph-reel__act">♡</span>
        <span class="ph-reel__act">{{ mb_substr($company->name, 0, 1) }}</span>
    </div>
    <div class="ph-reel__body">
        <span class="ph-reel__tag">{{ $company->category?->name ?? 'İşletme' }} · {{ $company->city?->name ?? 'Türkiye' }}</span>
        <h3>{{ $company->name }}</h3>
        <p>{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Profilini aç; hizmetleri, adresi ve iletişim hattını tek dokunuşta gör.', 104) }}</p>
    </div>
    @if($phBadge)<span class="ph-card__badge" style="top:14px;left:14px">{{ $phBadge }}</span>@endif
    @if($phRate > 0)<span class="ph-reel__tag" style="position:absolute;top:14px;right:14px">{{ number_format($phRate, 1, ',', '.') }} ★</span>@endif
</a>
