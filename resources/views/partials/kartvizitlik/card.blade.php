@php
    $kvOpen = $company->isOpenNow();
    $kvPremium = $company->hasActivePremium();
    $kvRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $kvReviews = (int) ($company->approved_reviews_count ?? 0);
    $kvPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    $kvD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($kvD, '00')) { $kvD = substr($kvD, 2); }
    if (strlen($kvD) === 12 && str_starts_with($kvD, '90')) { $kvD = '0'.substr($kvD, 2); }
    if (strlen($kvD) === 10 && in_array($kvD[0], ['2', '3', '4', '5', '8'], true)) { $kvD = '0'.$kvD; }
    $kvNormal = strlen($kvD) === 11 && $kvD[0] === '0';
    // Gösterilen numara ile aranan numara aynı olsun: biçimlenebildiyse bağlantı da normalleştirilmiş rakamlardan kurulur.
    $kvTel = $kvNormal ? $kvD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $kvPhone = $kvNormal
        ? substr($kvD, 0, 4).' '.substr($kvD, 4, 3).' '.substr($kvD, 7, 2).' '.substr($kvD, 9, 2)
        : trim((string) $company->phone);
    $kvNameTag = in_array($kvNameTag ?? 'h3', ['h3', 'p'], true) ? ($kvNameTag ?? 'h3') : 'h3';
    $kvWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($kvWa, '00')) { $kvWa = substr($kvWa, 2); }
    if (str_starts_with($kvWa, '0')) { $kvWa = '9'.$kvWa; }
    elseif (strlen($kvWa) === 10 && str_starts_with($kvWa, '5')) { $kvWa = '90'.$kvWa; }
    $kvWaShow = '';
    if ($kvWa !== '') {
        $kvWaD = str_starts_with($kvWa, '90') && strlen($kvWa) === 12 ? '0'.substr($kvWa, 2) : $kvWa;
        $kvWaShow = strlen($kvWaD) === 11 && $kvWaD[0] === '0'
            ? substr($kvWaD, 0, 4).' '.substr($kvWaD, 4, 3).' '.substr($kvWaD, 7, 2).' '.substr($kvWaD, 9, 2)
            : trim((string) $company->whatsapp);
    }
    $kvUrl = route('companies.show', $company->slug);
@endphp
<article class="kv-card{{ $kvPremium ? ' kv-card--premium' : '' }}{{ $kvOpen === true ? ' kv-card--open' : '' }}{{ $company->is_verified ? ' kv-card--verified' : '' }}" lang="tr">
    @if($company->is_verified)<span class="kv-seal">Onaylı</span>@endif
    <div class="kv-card__who">
        @if($company->logo)<img class="kv-card__logo" src="{{ asset('storage/'.$company->logo) }}" alt="" width="36" height="36" loading="lazy">@endif
        <{{ $kvNameTag }} class="kv-card__name"><a href="{{ $kvUrl }}"><span class="kv-card__name-text">{{ $company->name }}</span></a></{{ $kvNameTag }}>
    </div>
    <p class="kv-card__unvan"><span class="kv-card__unvan-rule" aria-hidden="true"></span><span>{{ $company->category->name ?? 'İşletme' }}</span>@if($kvPremium) <span class="kv-card__premium">✦ Öne çıkan</span>@endif</p>
    <p class="kv-card__desc">{{ $company->short_description ?: 'Ayrıntılar kartın tamamında.' }}</p>
    <p class="kv-card__where">
        @if($kvOpen === true)<span class="kv-card__open"><span class="kv-dot" aria-hidden="true"></span>Şu an açık</span>@elseif($kvOpen === false)<span class="kv-card__closed">Kapalı</span>@endif
        @if($kvPlace !== '')<span class="kv-card__place"><svg width="12" height="12" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>{{ $kvPlace }}</span>@endif
        @if($kvRating)<span class="kv-card__rating"><span class="kv-star" aria-hidden="true">★</span> {{ number_format($kvRating, 1, ',', '') }} · {{ $kvReviews }} yorum</span>@endif
    </p>
    @if($kvTel !== '' || $kvWa !== '')
        <div class="kv-strip">
            @if($kvTel !== '')
                <a class="kv-call" href="tel:{{ $kvTel }}" aria-label="{{ $company->name }} ara: {{ $kvPhone }}"><span class="kv-call__k">Ara</span><span class="kv-call__n">{{ $kvPhone }}</span></a>
                @if($kvWa !== '')
                    <a class="kv-wa" href="https://wa.me/{{ $kvWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz"><svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Zm-2.6 3.6c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.2-1.6-.3-.4-.6-.8-.8-1.2-.1-.2 0-.4.1-.5l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.8c-.1-.4-.3-.4-.5-.4h-.4Z"/></svg></a>
                @endif
            @else
                <a class="kv-call kv-call--wa" href="https://wa.me/{{ $kvWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz: {{ $kvWaShow }}"><span class="kv-call__k">Yaz</span><span class="kv-call__n">{{ $kvWaShow }}</span></a>
            @endif
            <a class="kv-more" href="{{ $kvUrl }}" aria-label="{{ $company->name }} kartının tamamı"><span aria-hidden="true">›</span></a>
        </div>
    @else
        <div class="kv-strip"><a class="kv-strip__all" href="{{ $kvUrl }}">Kartın tamamını gör ›</a></div>
    @endif
</article>
