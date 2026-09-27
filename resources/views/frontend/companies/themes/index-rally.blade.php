@php
    // Cep · rampa detayı: numaralı sıra başlığı + aksiyonlar rampa satırları
    $phMapsUrl = $company->latitude && $company->longitude
        ? 'https://www.google.com/maps/dir/?api=1&destination=' . $company->latitude . ',' . $company->longitude
        : ($company->address ? 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode(($company->name . ' ' . $cityName)) : null);
    $phRank = str_pad((string) (($company->id % 99) + 1), 2, '0', STR_PAD_LEFT);
@endphp

@include('partials.phone.device-start')

    <section class="ph-detail__cover">
        @if($company->cover_image)
            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">
        @endif
        <span class="ph-card__badge" style="top:12px;left:12px">Sıra №{{ $phRank }}</span>
    </section>

    <div class="ph-detail__id">
        <span class="ph-avatar">
            @if($company->logo)<img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu">@else{{ mb_substr($company->name, 0, 1) }}@endif
        </span>
        <nav class="ph-crumb" aria-label="Gezinme">
            <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span>
            @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif
            <span>{{ Str::limit($company->name, 20) }}</span>
        </nav>
        <h1>{{ $company->name }}</h1>
        <p class="ph-lead" style="margin:0">{{ $company->short_description ?: $company->name . ' hizmetleri, adres ve iletişim bilgileriyle profilde.' }}</p>
        <div class="ph-chips" style="padding:0">
            <span class="ph-chip">{{ $categoryName }}</span>
            <span class="ph-chip">{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            @if($company->is_verified)<span class="ph-chip is-on">✓ Doğrulanmış</span>@endif
            @if($ratingAvg)<span class="ph-chip">{{ $ratingAvg }} ★ ({{ $reviewCount }})</span>@endif
        </div>
    </div>

    <div style="padding:12px 16px 4px">
        <div class="ph-rally">
            @if($phoneClean)
                <a href="tel:{{ $phoneClean }}"><span class="ph-rally__txt"><strong>Hemen ara</strong><span>{{ $company->phone }}</span></span><span class="ph-rally__go" aria-hidden="true">›</span></a>
            @endif
            @if($whatsappClean)
                <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer"><span class="ph-rally__txt"><strong>WhatsApp'tan yaz</strong><span>Anlık mesajlaşma hattı</span></span><span class="ph-rally__go" aria-hidden="true">›</span></a>
            @endif
            @if($company->website)
                <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer"><span class="ph-rally__txt"><strong>Web sitesini aç</strong><span>{{ $company->website }}</span></span><span class="ph-rally__go" aria-hidden="true">›</span></a>
            @endif
            @if($phMapsUrl)
                <a href="{{ $phMapsUrl }}" target="_blank" rel="noopener noreferrer"><span class="ph-rally__txt"><strong>Yol tarifi al</strong><span>{{ $company->address ?: ($cityName . ' · ' . $company->name) }}</span></span><span class="ph-rally__go" aria-hidden="true">›</span></a>
            @endif
        </div>
    </div>

    <dl class="ph-dl">
        <div><dt>Sıra no</dt><dd>№{{ $phRank }} · {{ $categoryName }}</dd></div>
        <div><dt>Bölge</dt><dd>{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</dd></div>
        <div><dt>Değerlendirme</dt><dd>{{ $ratingAvg ? $ratingAvg . ' / 5 · ' . $reviewCount . ' yorum' : 'Henüz yorum yok' }}</dd></div>
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
