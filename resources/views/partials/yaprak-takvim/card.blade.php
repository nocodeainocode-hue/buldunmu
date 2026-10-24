@php
    $ytOpen = $company->isOpenNow();
    $ytPremium = $company->hasActivePremium();
    $ytRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $ytReviews = (int) ($company->approved_reviews_count ?? 0);
    $ytPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $ytD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($ytD, '00')) { $ytD = substr($ytD, 2); }
    if (strlen($ytD) === 12 && str_starts_with($ytD, '90')) { $ytD = '0'.substr($ytD, 2); }
    if (strlen($ytD) === 10 && in_array($ytD[0], ['2', '3', '4', '5', '8'], true)) { $ytD = '0'.$ytD; }
    $ytNormal = strlen($ytD) === 11 && $ytD[0] === '0';
    $ytTel = $ytNormal ? $ytD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $ytPhone = $ytNormal
        ? substr($ytD, 0, 4).' '.substr($ytD, 4, 3).' '.substr($ytD, 7, 2).' '.substr($ytD, 9, 2)
        : trim((string) $company->phone);
    $ytWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($ytWa, '00')) { $ytWa = substr($ytWa, 2); }
    if (str_starts_with($ytWa, '0')) { $ytWa = '9'.$ytWa; }
    elseif (strlen($ytWa) === 10 && str_starts_with($ytWa, '5')) { $ytWa = '90'.$ytWa; }
    $ytUrl = route('companies.show', $company->slug);
@endphp
<article class="yt-leaf{{ $ytPremium ? ' yt-leaf--premium' : '' }}" lang="tr">
    <div class="yt-leaf__band">
        <span class="yt-leaf__cat">{{ $company->category->name ?? 'İşletme' }}</span>
        @if($ytPremium)<span class="yt-leaf__seal">Öne çıkan</span>@endif
    </div>
    <div class="yt-leaf__body">
        <h3 class="yt-leaf__name"><a href="{{ $ytUrl }}">{{ $company->name }}</a></h3>
        @if($ytPlace !== '')<p class="yt-leaf__place"><svg width="14" height="14" aria-hidden="true"><use href="#yt-ico-pin"/></svg>{{ $ytPlace }}</p>@endif
        @if(filled($company->short_description))<p class="yt-leaf__desc">{{ $company->short_description }}</p>@endif
        @if($company->is_verified || $ytOpen === true || $ytRating)
            <p class="yt-leaf__meta">
                @if($company->is_verified)<span class="yt-chip yt-chip--ok">Onaylı</span>@endif
                @if($ytOpen === true)<span class="yt-chip yt-chip--open">Şu an açık</span>@endif
                @if($ytRating)<span class="yt-leaf__rating"><span aria-hidden="true">★</span> {{ number_format($ytRating, 1, ',', '') }} · {{ $ytReviews }} yorum</span>@endif
            </p>
        @endif
        <div class="yt-leaf__acts">
            @if($ytTel !== '')
                <a class="yt-call" href="tel:{{ $ytTel }}" aria-label="{{ $company->name }} ara: {{ $ytPhone }}"><svg width="18" height="18" aria-hidden="true"><use href="#yt-ico-phone"/></svg><span>{{ $ytPhone }}</span></a>
            @elseif($ytWa !== '')
                <a class="yt-call yt-call--wa" href="https://wa.me/{{ $ytWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="18" height="18" aria-hidden="true"><use href="#yt-ico-wa"/></svg><span>WhatsApp'tan yaz</span></a>
            @else
                <a class="yt-call yt-call--line" href="{{ $ytUrl }}"><span>Firmayı incele</span></a>
            @endif
            @if($ytTel !== '' && $ytWa !== '')
                <a class="yt-wa" href="https://wa.me/{{ $ytWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="21" height="21" aria-hidden="true"><use href="#yt-ico-wa"/></svg></a>
            @endif
            @if($ytTel !== '' || $ytWa !== '')
                <a class="yt-more" href="{{ $ytUrl }}" aria-label="{{ $company->name }} firma sayfası"><svg width="18" height="18" aria-hidden="true"><use href="#yt-ico-arrow"/></svg></a>
            @endif
        </div>
    </div>
</article>
