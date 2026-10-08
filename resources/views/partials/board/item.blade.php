{{-- İlan satırı/kartı: $company --}}
@php
    $bdImg = $company->cover_image ?: $company->logo;
    $bdTel = $company->phone ? preg_replace('/[^\d+]/', '', $company->phone) : null;
    $bdIsNew = $company->created_at && $company->created_at->gt(now()->subDays(7));
    $bdAgo = $company->created_at?->locale('tr')->diffForHumans();
@endphp
<article class="bd-item {{ $company->hasActivePremium() ? 'bd-item--pin' : '' }}">
    <div class="bd-item__thumb">
        @if($bdImg)<img src="{{ asset('storage/'.$bdImg) }}" alt="{{ $company->name }}" loading="lazy">@else{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}@endif
    </div>
    <div class="bd-item__body">
        <div class="bd-item__meta">
            <b>{{ $company->category?->name ?? 'İşletme' }}</b><span>·</span>
            <span>{{ $company->city?->name ?? 'Türkiye' }}{{ $company->district?->name ? ' / '.$company->district->name : '' }}</span>
            @if($bdAgo)<span>·</span><span>{{ $bdAgo }}</span>@endif
        </div>
        <h3><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>
        <p class="bd-item__text">{{ \Illuminate\Support\Str::limit($company->short_description ?: 'Hizmetleri, iletişim ve konum bilgileri için ilanı açın.', 140) }}</p>
        <div class="bd-item__badges">
            @if($company->hasActivePremium())<span class="bd-badge bd-badge--pin">📌 Sabit</span>@endif
            @if($company->is_verified)<span class="bd-badge bd-badge--ok">✓ Doğrulanmış</span>@endif
            @if($bdIsNew)<span class="bd-badge bd-badge--new">Yeni</span>@endif
            @if(($company->reviews_avg_rating ?? null))<span class="bd-badge">★ {{ number_format((float) $company->reviews_avg_rating, 1) }}</span>@endif
        </div>
    </div>
    <div class="bd-item__act">
        @if($bdTel)<a class="bd-btn bd-btn--hot bd-btn--sm" href="tel:{{ $bdTel }}">📞 Ara</a>@endif
        <a class="bd-btn bd-btn--ghost bd-btn--sm" href="{{ route('companies.show', $company->slug) }}">İlanı aç</a>
    </div>
</article>
