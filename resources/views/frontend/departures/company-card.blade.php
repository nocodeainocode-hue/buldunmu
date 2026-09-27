@php
    $depIndex = str_pad($loop->iteration ?? 1, 2, '0', STR_PAD_LEFT);
    $depBadge = $company->hasActivePremium()
        ? ['Vitrinde', '']
        : ($company->is_verified
            ? ['Doğrulandı', 'dep-card__badge--mint']
            : ($company->created_at?->gt(now()->subDays(14)) ? ['Yeni hat', ''] : null));
@endphp
<article class="dep-card">
    <div class="dep-card__stub">
        <span class="dep-card__plat">P{{ $depIndex }}</span>
        <span class="dep-card__vert">{{ $company->category?->name ?? 'İşletme' }}</span>
        <span class="dep-card__barcode" aria-hidden="true"></span>
    </div>
    <div>
        <div class="dep-card__media">
            @if($company->cover_image)
                <img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="lazy">
            @endif
            <span class="dep-card__initial" aria-hidden="true">{{ mb_substr($company->name, 0, 1) }}</span>
            @if($depBadge)<span class="dep-card__badge {{ $depBadge[1] }}">{{ $depBadge[0] }}</span>@endif
        </div>
        <div class="dep-card__body">
            <p class="dep-code">{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}</p>
            <h3 class="dep-h3"><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
            <p class="dep-card__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim bilgilerini ve sunduğu hizmetleri inceleyin.', 116) }}</p>
            <div class="dep-card__foot">
                <span>{{ $company->district?->name ?? $company->city?->name ?? 'Yerel' }} @if(($company->reviews_avg_rating ?? 0) > 0)· {{ number_format((float) $company->reviews_avg_rating, 1, ',', '.') }}★@endif</span>
                <a href="{{ route('companies.show', $company->slug) }}">Bileti aç →</a>
            </div>
        </div>
    </div>
</article>
