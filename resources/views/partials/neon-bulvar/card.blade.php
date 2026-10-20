@php
    $nbOpen = $company->isOpenNow();
    $nbPremium = $company->hasActivePremium();
    $nbRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $nbReviews = (int) ($company->approved_reviews_count ?? 0);
    $nbPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $nbD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($nbD, '00')) { $nbD = substr($nbD, 2); }
    if (strlen($nbD) === 12 && str_starts_with($nbD, '90')) { $nbD = '0'.substr($nbD, 2); }
    if (strlen($nbD) === 10 && in_array($nbD[0], ['2', '3', '4', '5', '8'], true)) { $nbD = '0'.$nbD; }
    $nbNormal = strlen($nbD) === 11 && $nbD[0] === '0';
    $nbTel = $nbNormal ? $nbD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $nbPhone = $nbNormal
        ? substr($nbD, 0, 4).' '.substr($nbD, 4, 3).' '.substr($nbD, 7, 2).' '.substr($nbD, 9, 2)
        : trim((string) $company->phone);
    $nbWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($nbWa, '00')) { $nbWa = substr($nbWa, 2); }
    if (str_starts_with($nbWa, '0')) { $nbWa = '9'.$nbWa; }
    elseif (strlen($nbWa) === 10 && str_starts_with($nbWa, '5')) { $nbWa = '90'.$nbWa; }
    $nbUrl = route('companies.show', $company->slug);
    // Vitrinin neon rengi kategoriye bağlıdır: aynı meslek tüm sayfada aynı renkte yanar.
    $nbTone = abs(crc32((string) ($company->category->slug ?? $company->slug))) % 6;
    $nbNameTag = in_array($nbNameTag ?? 'h3', ['h3', 'p'], true) ? ($nbNameTag ?? 'h3') : 'h3';
@endphp
<article class="nb-vitrin nb-c{{ $nbTone }}{{ $nbPremium ? ' nb-vitrin--premium' : '' }}" lang="tr">
    <a class="nb-vitrin__cam" href="{{ $nbUrl }}" tabindex="-1" aria-hidden="true">
        @if($company->cover_image)
            <img class="nb-vitrin__cover" src="{{ asset('storage/'.$company->cover_image) }}" alt="" width="520" height="140" loading="lazy">
        @elseif($company->logo)
            <span class="nb-vitrin__logo"><img src="{{ asset('storage/'.$company->logo) }}" alt="" width="120" height="56" loading="lazy"></span>
        @else
            <span class="nb-vitrin__glow" aria-hidden="true"></span>
        @endif
        <span class="nb-vitrin__cat">{{ $company->category->name ?? 'İşletme' }}</span>
    </a>
    @if($nbPremium)<span class="nb-vitrin__seal nb-neon nb-neon--pink">Öne çıkan</span>@endif
    <div class="nb-vitrin__body">
        <{{ $nbNameTag }} class="nb-vitrin__name"><a href="{{ $nbUrl }}">{{ $company->name }}</a></{{ $nbNameTag }}>
        @if(filled($company->short_description))<p class="nb-vitrin__desc">{{ $company->short_description }}</p>@endif
        <p class="nb-vitrin__meta">
            @if($company->is_verified)<span class="nb-chip nb-chip--ok">Onaylı</span>@endif
            @if($nbOpen === true)<span class="nb-chip nb-chip--open">Şu an açık</span>@elseif($nbOpen === false)<span class="nb-chip">Kapalı</span>@endif
            @if($nbRating)<span class="nb-vitrin__rating"><span aria-hidden="true">★</span> {{ number_format($nbRating, 1, ',', '') }} · {{ $nbReviews }} yorum</span>@endif
            @if($nbPlace !== '')<span class="nb-vitrin__place"><svg width="14" height="14" aria-hidden="true"><use href="#nb-ico-pin"/></svg>{{ $nbPlace }}</span>@endif
        </p>
        <div class="nb-vitrin__acts">
            @if($nbTel !== '')
                <a class="nb-call" href="tel:{{ $nbTel }}" aria-label="{{ $company->name }} ara: {{ $nbPhone }}"><svg width="18" height="18" aria-hidden="true"><use href="#nb-ico-phone"/></svg><span>{{ $nbPhone }}</span></a>
            @elseif($nbWa !== '')
                <a class="nb-call nb-call--wa" href="https://wa.me/{{ $nbWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="18" height="18" aria-hidden="true"><use href="#nb-ico-wa"/></svg><span>WhatsApp'tan yaz</span></a>
            @else
                <a class="nb-call nb-call--line" href="{{ $nbUrl }}"><span>Firmayı incele</span></a>
            @endif
            @if($nbTel !== '' && $nbWa !== '')
                <a class="nb-wa" href="https://wa.me/{{ $nbWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="22" height="22" aria-hidden="true"><use href="#nb-ico-wa"/></svg></a>
            @endif
            @if($nbTel !== '' || $nbWa !== '')
                <a class="nb-more" href="{{ $nbUrl }}" aria-label="{{ $company->name }} firma sayfası"><span aria-hidden="true">›</span></a>
            @endif
        </div>
    </div>
</article>
