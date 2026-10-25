@php
    $ppCOpen = $company->isOpenNow();
    $ppCPremium = $company->hasActivePremium();
    $ppCRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $ppCReviews = (int) ($company->approved_reviews_count ?? 0);
    $ppCPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    $ppCTone = ['green', 'ochre', 'blue', 'red'][$company->id % 4];
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $ppCD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($ppCD, '00')) { $ppCD = substr($ppCD, 2); }
    if (strlen($ppCD) === 12 && str_starts_with($ppCD, '90')) { $ppCD = '0'.substr($ppCD, 2); }
    if (strlen($ppCD) === 10 && in_array($ppCD[0], ['2', '3', '4', '5', '8'], true)) { $ppCD = '0'.$ppCD; }
    $ppCNormal = strlen($ppCD) === 11 && $ppCD[0] === '0';
    $ppCTel = $ppCNormal ? $ppCD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $ppCPhone = $ppCNormal
        ? substr($ppCD, 0, 4).' '.substr($ppCD, 4, 3).' '.substr($ppCD, 7, 2).' '.substr($ppCD, 9, 2)
        : trim((string) $company->phone);
    $ppCWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($ppCWa, '00')) { $ppCWa = substr($ppCWa, 2); }
    if (str_starts_with($ppCWa, '0')) { $ppCWa = '9'.$ppCWa; }
    elseif (strlen($ppCWa) === 10 && str_starts_with($ppCWa, '5')) { $ppCWa = '90'.$ppCWa; }
    $ppCUrl = route('companies.show', $company->slug);
@endphp
<div class="pp-letter pp-tone--{{ $ppCTone }}">
    <article class="pp-perf pp-letter__card" lang="tr">
        <div class="pp-letter__in">
            <div class="pp-letter__top">
                <span class="pp-letter__cat">{{ $company->category->name ?? 'İşletme' }}</span>
                @if($ppCPremium || $company->is_verified)
                    <span class="pp-letter__marks">
                        @if($ppCPremium)<span class="pp-rubber pp-rubber--red">Taahhütlü</span>@endif
                        @if($company->is_verified)<span class="pp-rubber pp-rubber--green">Onaylı</span>@endif
                    </span>
                @endif
            </div>
            <h3 class="pp-letter__name"><a href="{{ $ppCUrl }}">{{ $company->name }}</a></h3>
            @if($ppCPlace !== '')<p class="pp-letter__place"><svg width="14" height="14" aria-hidden="true"><use href="#pp-ico-pin"/></svg>{{ $ppCPlace }}</p>@endif
            @if(filled($company->short_description))<p class="pp-letter__desc">{{ $company->short_description }}</p>@endif
            @if($ppCOpen === true || $ppCRating)
                <p class="pp-letter__meta">
                    @if($ppCOpen === true)<span class="pp-chip pp-chip--open">Şu an açık</span>@endif
                    @if($ppCRating)<span class="pp-letter__rating"><span aria-hidden="true">★</span> {{ number_format($ppCRating, 1, ',', '') }} · {{ $ppCReviews }} yorum</span>@endif
                </p>
            @endif
            <div class="pp-letter__acts">
                @if($ppCTel !== '')
                    <a class="pp-call" href="tel:{{ $ppCTel }}" aria-label="{{ $company->name }} ara: {{ $ppCPhone }}"><svg width="18" height="18" aria-hidden="true"><use href="#pp-ico-phone"/></svg><span>{{ $ppCPhone }}</span></a>
                @elseif($ppCWa !== '')
                    <a class="pp-call pp-call--wa" href="https://wa.me/{{ $ppCWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="18" height="18" aria-hidden="true"><use href="#pp-ico-wa"/></svg><span>WhatsApp'tan yaz</span></a>
                @else
                    <a class="pp-call pp-call--line" href="{{ $ppCUrl }}"><span>Firmayı incele</span></a>
                @endif
                @if($ppCTel !== '' && $ppCWa !== '')
                    <a class="pp-sq pp-sq--wa" href="https://wa.me/{{ $ppCWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="21" height="21" aria-hidden="true"><use href="#pp-ico-wa"/></svg></a>
                @endif
                @if($ppCTel !== '' || $ppCWa !== '')
                    <a class="pp-sq" href="{{ $ppCUrl }}" aria-label="{{ $company->name }} firma sayfası"><svg width="18" height="18" aria-hidden="true"><use href="#pp-ico-arrow"/></svg></a>
                @endif
            </div>
        </div>
    </article>
</div>
