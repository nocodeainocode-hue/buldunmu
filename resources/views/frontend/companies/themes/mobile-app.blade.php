@php
    // Mobil uygulama · detay: kapak + alt bilgi çekmecesi + ortak section/sidebar
    $apCodePrefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', (string) $company->slug) ?: 'gen', 0, 3));
    $apCodeSerial = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp

@include('partials.appshell.device-start')

<div class="ap-detail">
    <section class="ap-detail__cover @if(!$company->cover_image && !$company->logo) ap-detail__cover--ph @endif">
        @if($company->cover_image)
            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">
        @elseif($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu" loading="eager">
        @else
            {{ mb_substr($company->name, 0, 1) }}
        @endif
    </section>

    <div class="ap-detail__sheet">
        <nav class="ap-crumb" style="font-size:11px;color:var(--text_muted)" aria-label="Gezinme">
            <a href="{{ route('home') }}" style="color:var(--text_muted);text-decoration:none">Ana Sayfa</a> ›
            @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}" style="color:var(--text_muted);text-decoration:none">{{ $categoryName }}</a> › @endif
            <span>{{ $company->name }}</span>
        </nav>
        <span class="ap-kicker" style="display:block;margin-top:8px">Kayıt {{ $apCodePrefix }}-{{ $apCodeSerial }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
        <h1 style="margin-top:4px">{{ $company->name }}@if($company->hasActivePremium()) ★@endif</h1>
        <p style="margin-top:8px;font-size:13.5px;color:var(--text_muted);line-height:1.6">{{ $company->short_description ?: $company->name . ' işletmesinin hizmetleri, iletişim ve konum bilgileri.' }}</p>
        <div class="ap-actions">
            @if($company->phone)<a class="ap-btn" href="tel:{{ $phoneClean }}">Ara</a>@endif
            @if($company->whatsapp)<a class="ap-btn" style="background:#1faa54;border-color:#1faa54" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif
            @if($company->website)<a class="ap-btn ap-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Site ↗</a>@endif
        </div>
    </div>

    <dl class="ap-facts">
        <div><dt>Kategori</dt><dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd></div>
        <div><dt>Konum</dt><dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif</dd></div>
        <div><dt>Puan</dt><dd>{{ $ratingAvg ? $ratingAvg . ' / 5' : 'Puan yok' }}{{ $reviewCount ? ' ('.$reviewCount.')' : '' }}</dd></div>
        <div><dt>Profil</dt><dd>{{ $company->is_verified ? 'Doğrulanmış' : 'Beklemede' }} · %{{ $profileScore }}</dd></div>
    </dl>

    @include('frontend.companies.themes.sections')
    @include('frontend.companies.themes.sidebar')

    @if(str_starts_with((string) $company->external_id, 'osm:'))
        <div style="padding:10px 16px 4px;font-size:11.5px;color:var(--text_muted)">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--primary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
    @endif
</div>

@include('partials.appshell.device-end')
