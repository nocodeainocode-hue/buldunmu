@php
    $tlOpen = $company->isOpenNow();
    $tlPremium = $company->hasActivePremium();
    $tlRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $tlReviews = (int) ($company->approved_reviews_count ?? 0);
    $tlPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $tlD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($tlD, '00')) { $tlD = substr($tlD, 2); }
    if (strlen($tlD) === 12 && str_starts_with($tlD, '90')) { $tlD = '0'.substr($tlD, 2); }
    if (strlen($tlD) === 10 && in_array($tlD[0], ['2', '3', '4', '5', '8'], true)) { $tlD = '0'.$tlD; }
    $tlNormal = strlen($tlD) === 11 && $tlD[0] === '0';
    $tlTel = $tlNormal ? $tlD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $tlPhone = $tlNormal
        ? substr($tlD, 0, 4).' '.substr($tlD, 4, 3).' '.substr($tlD, 7, 2).' '.substr($tlD, 9, 2)
        : trim((string) $company->phone);
    $tlWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($tlWa, '00')) { $tlWa = substr($tlWa, 2); }
    if (str_starts_with($tlWa, '0')) { $tlWa = '9'.$tlWa; }
    elseif (strlen($tlWa) === 10 && str_starts_with($tlWa, '5')) { $tlWa = '90'.$tlWa; }
    $tlUrl = route('companies.show', $company->slug);
    $tlNameTag = in_array($tlNameTag ?? 'h3', ['h3', 'p'], true) ? ($tlNameTag ?? 'h3') : 'h3';
@endphp
<article class="tl-plate{{ $tlPremium ? ' tl-plate--premium' : '' }}" lang="tr">
    <div class="tl-plate__head">
        <span class="tl-plate__cat">{{ $company->category->name ?? 'İşletme' }}</span>
        @if($tlPremium)<span class="tl-plate__seal">Öne çıkan</span>@endif
    </div>
    @if($company->logo)
        <a class="tl-plate__logo" href="{{ $tlUrl }}" tabindex="-1" aria-hidden="true"><img src="{{ asset('storage/'.$company->logo) }}" alt="" width="120" height="48" loading="lazy"></a>
    @endif
    <{{ $tlNameTag }} class="tl-plate__name"><a href="{{ $tlUrl }}">{{ $company->name }}</a></{{ $tlNameTag }}>
    @if(filled($company->short_description))<p class="tl-plate__desc">{{ $company->short_description }}</p>@endif
    <p class="tl-plate__meta">
        @if($company->is_verified)<span class="tl-chip tl-chip--ok">Onaylı</span>@endif
        @if($tlOpen === true)<span class="tl-chip tl-chip--open">Şu an açık</span>@elseif($tlOpen === false)<span class="tl-chip">Kapalı</span>@endif
        @if($tlRating)<span class="tl-plate__rating"><span aria-hidden="true">★</span> {{ number_format($tlRating, 1, ',', '') }} · {{ $tlReviews }} yorum</span>@endif
        @if($tlPlace !== '')<span class="tl-plate__place"><svg width="13" height="13" aria-hidden="true"><use href="#tl-ico-pin"/></svg>{{ $tlPlace }}</span>@endif
    </p>
    <div class="tl-plate__acts">
        @if($tlTel !== '')
            <a class="tl-call" href="tel:{{ $tlTel }}" aria-label="{{ $company->name }} ara: {{ $tlPhone }}"><svg width="17" height="17" aria-hidden="true"><use href="#tl-ico-phone"/></svg><span>{{ $tlPhone }}</span></a>
        @elseif($tlWa !== '')
            <a class="tl-call tl-call--wa" href="https://wa.me/{{ $tlWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="17" height="17" aria-hidden="true"><use href="#tl-ico-wa"/></svg><span>WhatsApp'tan yaz</span></a>
        @else
            <a class="tl-call tl-call--line" href="{{ $tlUrl }}"><span>Firmayı incele</span></a>
        @endif
        @if($tlTel !== '' && $tlWa !== '')
            <a class="tl-wa" href="https://wa.me/{{ $tlWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="20" height="20" aria-hidden="true"><use href="#tl-ico-wa"/></svg></a>
        @endif
        @if($tlTel !== '' || $tlWa !== '')
            <a class="tl-more" href="{{ $tlUrl }}" aria-label="{{ $company->name }} firma sayfası"><svg width="18" height="18" aria-hidden="true"><use href="#tl-ico-arrow"/></svg></a>
        @endif
    </div>
</article>
