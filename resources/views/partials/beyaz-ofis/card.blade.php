@php
    $boOpen = $company->isOpenNow();
    $boPremium = $company->hasActivePremium();
    $boRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $boReviews = (int) ($company->approved_reviews_count ?? 0);
    $boPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $boD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($boD, '00')) { $boD = substr($boD, 2); }
    if (strlen($boD) === 12 && str_starts_with($boD, '90')) { $boD = '0'.substr($boD, 2); }
    if (strlen($boD) === 10 && in_array($boD[0], ['2', '3', '4', '5', '8'], true)) { $boD = '0'.$boD; }
    $boNormal = strlen($boD) === 11 && $boD[0] === '0';
    $boTel = $boNormal ? $boD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $boPhone = $boNormal
        ? substr($boD, 0, 4).' '.substr($boD, 4, 3).' '.substr($boD, 7, 2).' '.substr($boD, 9, 2)
        : trim((string) $company->phone);
    $boWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($boWa, '00')) { $boWa = substr($boWa, 2); }
    if (str_starts_with($boWa, '0')) { $boWa = '9'.$boWa; }
    elseif (strlen($boWa) === 10 && str_starts_with($boWa, '5')) { $boWa = '90'.$boWa; }
    $boUrl = route('companies.show', $company->slug);
    $boInitial = mb_strtoupper(mb_substr(trim((string) $company->name), 0, 1));
    $boNameTag = in_array($boNameTag ?? 'h3', ['h3', 'p'], true) ? ($boNameTag ?? 'h3') : 'h3';
@endphp
<article class="bo-card{{ $boPremium ? ' bo-card--premium' : '' }}" lang="tr">
    <div class="bo-card__top">
        <a class="bo-card__avatar" href="{{ $boUrl }}" tabindex="-1" aria-hidden="true">
            @if($company->logo)
                <img src="{{ asset('storage/'.$company->logo) }}" alt="" width="56" height="56" loading="lazy">
            @else
                <span>{{ $boInitial }}</span>
            @endif
        </a>
        <div class="bo-card__head">
            <span class="bo-card__cat">{{ $company->category->name ?? 'İşletme' }}</span>
            <{{ $boNameTag }} class="bo-card__name"><a href="{{ $boUrl }}">{{ $company->name }}</a></{{ $boNameTag }}>
        </div>
        @if($boPremium)<span class="bo-card__seal">Öne çıkan</span>@endif
    </div>
    @if(filled($company->short_description))<p class="bo-card__desc">{{ $company->short_description }}</p>@endif
    <p class="bo-card__meta">
        @if($company->is_verified)<span class="bo-chip bo-chip--ok"><svg width="12" height="12" aria-hidden="true"><use href="#bo-ico-check"/></svg>Onaylı</span>@endif
        @if($boOpen === true)<span class="bo-chip bo-chip--open">Şu an açık</span>@elseif($boOpen === false)<span class="bo-chip">Kapalı</span>@endif
        @if($boRating)<span class="bo-card__rating"><span aria-hidden="true">★</span> {{ number_format($boRating, 1, ',', '') }} · {{ $boReviews }} yorum</span>@endif
        @if($boPlace !== '')<span class="bo-card__place"><svg width="13" height="13" aria-hidden="true"><use href="#bo-ico-pin"/></svg>{{ $boPlace }}</span>@endif
    </p>
    <div class="bo-card__acts">
        @if($boTel !== '')
            <a class="bo-call" href="tel:{{ $boTel }}" aria-label="{{ $company->name }} ara: {{ $boPhone }}"><svg width="17" height="17" aria-hidden="true"><use href="#bo-ico-phone"/></svg><span>{{ $boPhone }}</span></a>
        @elseif($boWa !== '')
            <a class="bo-call bo-call--wa" href="https://wa.me/{{ $boWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="17" height="17" aria-hidden="true"><use href="#bo-ico-wa"/></svg><span>WhatsApp'tan yaz</span></a>
        @else
            <a class="bo-call bo-call--line" href="{{ $boUrl }}"><span>Firmayı incele</span></a>
        @endif
        @if($boTel !== '' && $boWa !== '')
            <a class="bo-wa" href="https://wa.me/{{ $boWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="20" height="20" aria-hidden="true"><use href="#bo-ico-wa"/></svg></a>
        @endif
        @if($boTel !== '' || $boWa !== '')
            <a class="bo-more" href="{{ $boUrl }}" aria-label="{{ $company->name }} firma sayfası"><svg width="18" height="18" aria-hidden="true"><use href="#bo-ico-arrow"/></svg></a>
        @endif
    </div>
</article>
