<article class="cinema-card">
    <div class="cinema-card__visual">
        @if($company->cover_image)
            <img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }}" loading="lazy">
        @endif
        <span class="cinema-card__initial">{{ mb_substr($company->name, 0, 1) }}</span>
        <span class="cinema-card__number">{{ $company->hasActivePremium() ? 'VİTRİN' : 'KEŞİF' }} / {{ str_pad($loop->iteration ?? 1, 2, '0', STR_PAD_LEFT) }}</span>
    </div>
    <div class="cinema-card__body">
        <p class="cinema-card__meta">{{ $company->category?->name ?? 'İşletme' }} / {{ $company->city?->name ?? 'Türkiye' }}</p>
        <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
        <p>{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Firma profilini, iletişim bilgilerini ve sunduğu hizmetleri inceleyin.', 115) }}</p>
        <div class="cinema-card__foot"><span>{{ $company->district?->name ?? $company->city?->name ?? 'Yerel keşif' }}</span><a href="{{ route('companies.show', $company->slug) }}">Profili aç ↗</a></div>
    </div>
</article>
