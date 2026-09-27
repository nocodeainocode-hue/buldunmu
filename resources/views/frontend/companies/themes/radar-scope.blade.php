@php
    // Cep · radar detayı: sinyal şeridi + hedef koordinatları, aksiyonlar blip satırları
    $phMapsUrl = $company->latitude && $company->longitude
        ? 'https://www.google.com/maps/dir/?api=1&destination=' . $company->latitude . ',' . $company->longitude
        : ($company->address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(($company->name . ' ' . $cityName)) : null);
    $phRate = (float) ($company->reviews_avg_rating ?? 0);
    $phStrength = min(4, max(1, (int) ceil(($phRate / 5) * 4) + ($company->hasActivePremium() ? 1 : 0)));
    $phCoord = $company->latitude && $company->longitude
        ? number_format((float) $company->latitude, 4, '.', ' ') . ' / ' . number_format((float) $company->longitude, 4, '.', ' ')
        : 'KONUM VERISI YOK';
@endphp

@include('partials.phone.device-start')

    <div style="padding:14px 16px 0">
        <div class="ph-blip" style="grid-template-columns:auto 1fr;border-radius:var(--ph-radius)">
            <span class="ph-avatar" style="width:56px;height:56px;border-radius:16px;box-shadow:none;border-color:var(--ph-line)">
                @if($company->logo)<img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu">@else{{ mb_substr($company->name, 0, 1) }}@endif
            </span>
            <div style="min-width:0">
                <span class="ph-eyebrow">Hedef kilidi · {{ strtoupper(mb_substr((string) $company->slug, 0, 6)) }}</span>
                <h1 style="font-size:22px;margin:6px 0 4px">{{ $company->name }}</h1>
                <div class="ph-blip__sig" aria-hidden="true">
                    @for($i = 1; $i <= 4; $i++)<i style="@if($i > $phStrength)opacity:.18;@endif"></i>@endfor
                </div>
            </div>
        </div>
    </div>

    <div class="ph-pad" style="padding-top:12px;padding-bottom:0">
        <nav class="ph-crumb" aria-label="Gezinme">
            <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span>
            @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif
            <span>{{ Str::limit($company->name, 18) }}</span>
        </nav>
        <p class="ph-lead" style="margin-top:8px">{{ $company->short_description ?: $company->name . ' hizmetleri, adres ve iletişim bilgileriyle profilde.' }}</p>
    </div>

    <div style="padding:12px 16px 4px">
        <div class="ph-blips">
            @if($phoneClean)
                <a class="ph-blip" href="tel:{{ $phoneClean }}"><span class="ph-blip__dot"></span><span class="ph-blip__txt"><strong>Hemen ara</strong><span>{{ $company->phone }}</span></span><span class="ph-blip__sig" aria-hidden="true"><i></i><i></i><i></i><i></i></span></a>
            @endif
            @if($whatsappClean)
                <a class="ph-blip" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer"><span class="ph-blip__dot"></span><span class="ph-blip__txt"><strong>WhatsApp sinyali</strong><span>Anlık mesajlaşma hattı</span></span><span class="ph-blip__sig" aria-hidden="true"><i></i><i></i><i></i><i></i></span></a>
            @endif
            @if($company->website)
                <a class="ph-blip" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer"><span class="ph-blip__dot"></span><span class="ph-blip__txt"><strong>Web sitesi</strong><span>{{ $company->website }}</span></span><span class="ph-blip__sig" aria-hidden="true"><i></i><i></i><i></i><i></i></span></a>
            @endif
            @if($phMapsUrl)
                <a class="ph-blip" href="{{ $phMapsUrl }}" target="_blank" rel="noopener noreferrer"><span class="ph-blip__dot"></span><span class="ph-blip__txt"><strong>Yol tarifi</strong><span>{{ $company->address ?: ($cityName . ' · ' . $company->name) }}</span></span><span class="ph-blip__sig" aria-hidden="true"><i></i><i></i><i></i><i></i></span></a>
            @endif
        </div>
    </div>

    <dl class="ph-dl">
        <div><dt>Sektör</dt><dd>{{ $categoryName }}</dd></div>
        <div><dt>Bölge</dt><dd>{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</dd></div>
        <div><dt>Koordinat</dt><dd>{{ $phCoord }}</dd></div>
        <div><dt>Sinyal gücü</dt><dd>{{ $ratingAvg ? $ratingAvg . ' / 5 · ' . $reviewCount . ' yorum' : 'Henüz yorum yok' }}</dd></div>
        <div><dt>Profil doluluk</dt><dd>%{{ $profileScore }}</dd></div>
    </dl>

    <div class="ph-detail">
        @include('frontend.companies.themes.sections')
        @include('frontend.companies.themes.sidebar')
    </div>

    @include('partials.phone.footer')

    @if(str_starts_with((string) $company->external_id, 'osm:'))
        <div class="ph-note">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--ph-primary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
    @endif

@include('partials.phone.device-end')
