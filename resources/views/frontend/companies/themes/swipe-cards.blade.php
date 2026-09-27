@php
    // Cep · swipe detayı: tek büyük kart, tam genişlik aksiyon butonları
    $phCardNo = str_pad((string) (($company->id % 90) + 1), 2, '0', STR_PAD_LEFT);
    $phMapsUrl = $company->latitude && $company->longitude
        ? 'https://www.google.com/maps/dir/?api=1&destination=' . $company->latitude . ',' . $company->longitude
        : ($company->address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($company->name . ' ' . $cityName) : null);
@endphp

@include('partials.phone.device-start')

    <div style="padding:14px 16px 0">
        <article class="ph-deck__card">
            <div class="ph-deck__media" style="height:212px">
                @if($company->cover_image)
                    <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">
                @endif
                @if($company->hasActivePremium())<span class="ph-card__badge" style="top:12px;left:12px">Vitrin · Kart {{ $phCardNo }}</span>@endif
            </div>
            <div class="ph-deck__body">
                <nav class="ph-crumb" aria-label="Gezinme">
                    <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span>
                    @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif
                    <span>{{ Str::limit($company->name, 18) }}</span>
                </nav>
                <h3 style="margin-top:10px;font-size:23px">{{ $company->name }}</h3>
                <p>{{ $company->short_description ?: $company->name . ' işletmesinin hizmetleri, adres ve iletişim bilgileri.' }}</p>
                <div class="ph-deck__facts">
                    <span class="ph-fact">{{ $categoryName }}</span>
                    <span class="ph-fact">{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
                    @if($company->is_verified)<span class="ph-fact" style="border-color:var(--ph-ok);color:var(--ph-ok)">✓ Doğrulanmış</span>@endif
                    @if($ratingAvg)<span class="ph-fact">{{ $ratingAvg }} ★ · {{ $reviewCount }}</span>@endif
                </div>
            </div>
        </article>
    </div>

    <div style="display:grid;gap:9px;padding:14px 16px 4px">
        @if($phoneClean)<a class="ph-btn ph-btn--wide" href="tel:{{ $phoneClean }}">Telefon et →</a>@endif
        @if($whatsappClean)<a class="ph-btn ph-btn--ghost ph-btn--wide" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp'tan yaz</a>@endif
        @if($company->website)<a class="ph-btn ph-btn--ghost ph-btn--wide" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesini aç</a>@endif
        @if($phMapsUrl)<a class="ph-btn ph-btn--ghost ph-btn--wide" href="{{ $phMapsUrl }}" target="_blank" rel="noopener noreferrer">Yol tarifi al</a>@endif
    </div>

    <dl class="ph-dl">
        <div><dt>Kart no</dt><dd>{{ $phCardNo }} · {{ strtoupper(mb_substr(preg_replace('/[^a-z]/i', '', (string) $company->slug) ?: 'gen', 0, 3)) }}</dd></div>
        <div><dt>Sektör</dt><dd>{{ $categoryName }}</dd></div>
        <div><dt>Bölge</dt><dd>{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</dd></div>
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
