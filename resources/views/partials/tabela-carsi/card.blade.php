@php
    // Dükkân kartı: tabela (firma adı), tente, gövde (meslek, yer, rozetler), eşik (ara + WhatsApp).
    $tcPremium = $company->hasActivePremium();
    $tcCatSlug = $company->category->slug ?? 'isletme';
    $tcToneClass = 'tc-c'.(crc32((string) $tcCatSlug) % 6);
    $tcRating = $company->reviews_avg_rating ? round((float) $company->reviews_avg_rating, 1) : null;
    $tcReviews = (int) ($company->approved_reviews_count ?? 0);
    $tcPlace = collect([$company->district->name ?? null, $company->city->name ?? null])->filter()->implode(' / ');
    $tcD = preg_replace('/\D/', '', (string) $company->phone);
    if (str_starts_with($tcD, '00')) { $tcD = substr($tcD, 2); }
    if (strlen($tcD) === 12 && str_starts_with($tcD, '90')) { $tcD = '0'.substr($tcD, 2); }
    if (strlen($tcD) === 10 && in_array($tcD[0], ['2', '3', '4', '5', '8'], true)) { $tcD = '0'.$tcD; }
    $tcNormal = strlen($tcD) === 11 && $tcD[0] === '0';
    // Gösterilen ve aranan numara aynıdır; rakam içermeyen telefon alanı bağlantı üretmez.
    $tcTel = $tcNormal ? $tcD : (preg_match('/\d/', (string) $company->phone) ? preg_replace('/[^0-9+]/', '', (string) $company->phone) : '');
    $tcPhone = $tcNormal
        ? substr($tcD, 0, 4).' '.substr($tcD, 4, 3).' '.substr($tcD, 7, 2).' '.substr($tcD, 9, 2)
        : trim((string) $company->phone);
    $tcWa = preg_replace('/\D/', '', (string) $company->whatsapp);
    if (str_starts_with($tcWa, '00')) { $tcWa = substr($tcWa, 2); }
    if (str_starts_with($tcWa, '0')) { $tcWa = '9'.$tcWa; }
    elseif (strlen($tcWa) === 10 && str_starts_with($tcWa, '5')) { $tcWa = '90'.$tcWa; }
    $tcUrl = route('companies.show', $company->slug);
    $tcIcon = $company->category->icon ?? null;
@endphp
<article class="tc-shop {{ $tcToneClass }}{{ $tcPremium ? ' is-premium' : '' }}">
    <div class="tc-shop__sign tc-bolts">
        <h3 class="tc-shop__name"><a href="{{ $tcUrl }}">{{ $company->name }}</a></h3>
        @if($tcPremium)<span class="tc-vitrinde">Vitrinde</span>@endif
    </div>
    <div class="tc-awn" aria-hidden="true"><div class="tc-tente"></div></div>
    <div class="tc-shop__body">
        <div class="tc-shop__top{{ $company->logo || $tcIcon ? '' : ' tc-shop__top--plain' }}">
            @if($company->logo)
                <span class="tc-decal" aria-hidden="true"><img src="{{ asset('storage/'.$company->logo) }}" alt="" width="56" height="56" loading="lazy"></span>
            @elseif($tcIcon)
                <span class="tc-decal" aria-hidden="true">{{ $tcIcon }}</span>
            @endif
            <div class="tc-shop__who">
                @if($company->category)
                    <a class="tc-cat" href="{{ route('categories.show', $company->category->slug) }}">{{ $company->category->name }}</a>
                @else
                    <span class="tc-cat">İşletme</span>
                @endif
                @if($tcPlace !== '')
                    <span class="tc-place"><svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2a7 7 0 0 0-7 7c0 5.25 7 13 7 13s7-7.75 7-13a7 7 0 0 0-7-7Zm0 9.5A2.5 2.5 0 1 1 12 6.5a2.5 2.5 0 0 1 0 5Z"/></svg>{{ $tcPlace }}</span>
                @endif
            </div>
        </div>
        @if($company->is_verified || $tcRating)
            <p class="tc-badges">
                @if($company->is_verified)<span class="tc-ok">✓ Onaylı esnaf</span>@endif
                @if($tcRating)<span class="tc-star"><b aria-hidden="true">★</b> {{ number_format($tcRating, 1, ',', '') }} · {{ $tcReviews }} yorum</span>@endif
            </p>
        @endif
        @if(filled($company->short_description))<p class="tc-desc">{{ $company->short_description }}</p>@endif
    </div>
    <div class="tc-esik">
        @if($tcTel !== '')
            <a class="tc-call" href="tel:{{ $tcTel }}" aria-label="{{ $company->name }} ara: {{ $tcPhone }}">
                <svg viewBox="0 0 24 24" width="20" height="20" aria-hidden="true"><path fill="currentColor" d="M6.6 10.8a15.1 15.1 0 0 0 6.6 6.6l2.2-2.2a1 1 0 0 1 1-.25c1.1.37 2.3.57 3.6.57a1 1 0 0 1 1 1V20a1 1 0 0 1-1 1A17 17 0 0 1 3 4a1 1 0 0 1 1-1h3.5a1 1 0 0 1 1 1c0 1.25.2 2.45.57 3.57a1 1 0 0 1-.25 1z"/></svg>
                <span class="tc-call__k">Ara</span><span class="tc-call__n">{{ $tcPhone }}</span>
            </a>
        @endif
        <div class="tc-esik__row">
            @if($tcWa !== '')
                <a class="tc-btn tc-btn--wa" href="https://wa.me/{{ $tcWa }}" target="_blank" rel="noopener" aria-label="{{ $company->name }} WhatsApp'tan yaz">
                    <svg viewBox="0 0 24 24" width="18" height="18" aria-hidden="true"><path fill="currentColor" d="M12 3a9 9 0 0 0-7.8 13.5L3 21l4.6-1.2A9 9 0 1 0 12 3Zm0 2a7 7 0 1 1-3.6 13l-.3-.2-2.3.6.6-2.2-.2-.3A7 7 0 0 1 12 5Zm-2.6 3.6c-.2 0-.5 0-.7.3-.2.3-.9.9-.9 2.1s.9 2.4 1 2.6c.1.2 1.8 2.8 4.4 3.8 2.1.8 2.6.7 3 .6.5 0 1.5-.6 1.7-1.2.2-.6.2-1.1.1-1.2l-.5-.3-1.7-.8c-.2-.1-.4-.1-.6.1l-.8 1c-.1.2-.3.2-.5.1-.9-.4-1.6-.9-2.2-1.6-.3-.4-.6-.8-.8-1.2-.1-.2 0-.4.1-.5l.4-.5.3-.5c.1-.2 0-.4 0-.5l-.8-1.8c-.1-.4-.3-.4-.5-.4h-.4Z"/></svg>
                    WhatsApp
                </a>
            @endif
            <a class="tc-btn tc-btn--line" href="{{ $tcUrl }}" aria-label="{{ $company->name }}: dükkâna gir">Dükkâna gir <span aria-hidden="true">→</span></a>
        </div>
    </div>
</article>
