@php
    $cnOpen = $company->isOpenNow();
    $cnPremium = $company->hasActivePremium();
    $cnRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $cnReviews = (int) ($company->approved_reviews_count ?? 0);
    $cnPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    // Telefon: rakamlar normalleştirilir (+90 / 0090 / 10 hane -> 0XXX...); gösterilen ve aranan numara aynı olur.
    $cnD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($cnD, '00')) { $cnD = substr($cnD, 2); }
    if (strlen($cnD) === 12 && str_starts_with($cnD, '90')) { $cnD = '0'.substr($cnD, 2); }
    if (strlen($cnD) === 10 && in_array($cnD[0], ['2', '3', '4', '5', '8'], true)) { $cnD = '0'.$cnD; }
    $cnNormal = strlen($cnD) === 11 && $cnD[0] === '0';
    $cnTel = $cnNormal ? $cnD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $cnPhone = $cnNormal
        ? substr($cnD, 0, 4).' '.substr($cnD, 4, 3).' '.substr($cnD, 7, 2).' '.substr($cnD, 9, 2)
        : trim((string) $company->phone);
    $cnWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($cnWa, '00')) { $cnWa = substr($cnWa, 2); }
    if (str_starts_with($cnWa, '0')) { $cnWa = '9'.$cnWa; }
    elseif (strlen($cnWa) === 10 && str_starts_with($cnWa, '5')) { $cnWa = '90'.$cnWa; }
    $cnUrl = route('companies.show', $company->slug);
    // Logosuz firmaya baş harf yerine, adresinden türeyen sabit bir karo deseni düşer.
    $cnGlaze = (abs(crc32((string) $company->slug)) % 4) + 1;
    $cnNameTag = in_array($cnNameTag ?? 'h3', ['h3', 'p'], true) ? ($cnNameTag ?? 'h3') : 'h3';
@endphp
<article class="cn-kart{{ $cnPremium ? ' cn-kart--premium' : '' }}" lang="tr">
    <a class="cn-kart__karo cn-sir cn-sir--{{ $cnGlaze }}" href="{{ $cnUrl }}" tabindex="-1" aria-hidden="true">
        @if($company->cover_image)
            <img class="cn-kart__cover" src="{{ asset('storage/'.$company->cover_image) }}" alt="" width="520" height="128" loading="lazy">
        @elseif($company->logo)
            <span class="cn-kart__logo"><img src="{{ asset('storage/'.$company->logo) }}" alt="" width="120" height="56" loading="lazy"></span>
        @endif
    </a>
    @if($cnPremium)<span class="cn-kart__seal">Öne çıkan</span>@endif
    <span class="cn-kart__cat"><span>{{ $company->category->name ?? 'İşletme' }}</span></span>
    <div class="cn-kart__body">
        <{{ $cnNameTag }} class="cn-kart__name"><a href="{{ $cnUrl }}">{{ $company->name }}</a></{{ $cnNameTag }}>
        @if(filled($company->short_description))<p class="cn-kart__desc">{{ $company->short_description }}</p>@endif
        <p class="cn-kart__meta">
            @if($company->is_verified)<span class="cn-chip cn-chip--ok">Onaylı</span>@endif
            @if($cnOpen === true)<span class="cn-chip cn-chip--open">Şu an açık</span>@elseif($cnOpen === false)<span class="cn-chip">Kapalı</span>@endif
            @if($cnRating)<span class="cn-kart__rating"><span aria-hidden="true">★</span> {{ number_format($cnRating, 1, ',', '') }} · {{ $cnReviews }} yorum</span>@endif
            @if($cnPlace !== '')<span class="cn-kart__place">{{ $cnPlace }}</span>@endif
        </p>
        <div class="cn-kart__acts">
            @if($cnTel !== '')
                <a class="cn-call" href="tel:{{ $cnTel }}" aria-label="{{ $company->name }} ara: {{ $cnPhone }}"><svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2c.3-.3.7-.4 1-.2 1.1.4 2.3.6 3.6.6.6 0 1 .4 1 1V20c0 .6-.4 1-1 1A17 17 0 0 1 3 4c0-.6.4-1 1-1h3.5c.6 0 1 .4 1 1 0 1.3.2 2.5.6 3.6.1.3 0 .7-.2 1Z"/></svg><span>{{ $cnPhone }}</span></a>
            @elseif($cnWa !== '')
                <a class="cn-call cn-call--wa" href="https://wa.me/{{ $cnWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><span>WhatsApp'tan yaz</span></a>
            @else
                <a class="cn-call cn-call--line" href="{{ $cnUrl }}"><span>Firmayı incele</span></a>
            @endif
            @if($cnTel !== '' && $cnWa !== '')
                <a class="cn-wa" href="https://wa.me/{{ $cnWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Zm-2.6 3.6c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.2-1.6-.3-.4-.6-.8-.8-1.2-.1-.2 0-.4.1-.5l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.8c-.1-.4-.3-.4-.5-.4h-.4Z"/></svg></a>
            @endif
            @if($cnTel !== '' || $cnWa !== '')
                <a class="cn-more" href="{{ $cnUrl }}" aria-label="{{ $company->name }} firma sayfası"><span aria-hidden="true">›</span></a>
            @endif
        </div>
    </div>
</article>
