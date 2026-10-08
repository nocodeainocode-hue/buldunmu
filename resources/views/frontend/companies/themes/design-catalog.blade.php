@php
    $katDetailImage = $company->cover_image;
    $katDetailLogo = $company->logo;
    $katFolio = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp
<div class="kat kat-detail">
    @include('partials.catalog.header')

    @if($katDetailImage)
        <div class="kat-detail__cover"><img src="{{ asset('storage/'.$katDetailImage) }}" alt="{{ $company->name }} kapak görseli" loading="eager"></div>
    @endif

    <div class="kat-wrap">
        <nav class="kat-crumb" aria-label="Gezinme yolu" style="padding-top:26px;margin-bottom:0">
            <a href="{{ route('home') }}">Ana sayfa</a>
            <span>/</span>
            @if($company->category)
                <a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>
                <span>/</span>
            @endif
            <span>{{ $company->name }}</span>
        </nav>

        <header class="kat-detail__title">
            <div class="kat-detail__logo">
                @if($katDetailLogo)
                    <img src="{{ asset('storage/'.$katDetailLogo) }}" alt="{{ $company->name }} logosu">
                @else
                    {{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}
                @endif
            </div>
            <div>
                <span class="kat-kicker">Profil Nº {{ $katFolio }} · {{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }}</span>
                <h1>{{ $company->name }}</h1>
                <p class="kat-band__lede">{{ $company->short_description ?: $company->name.' işletmesinin hizmetlerini, iletişim ve konum bilgilerini tek sayfada görüntüleyin.' }}</p>
                <div class="kat-detail__actions">
                    @if($company->phone)<a class="kat-btn" href="tel:{{ $phoneClean }}">Ara →</a>@endif
                    @if($company->whatsapp)<a class="kat-btn kat-btn--ghost" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>@endif
                    @if($company->website)<a class="kat-btn kat-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi ↗</a>@endif
                    @if($company->hasActivePremium())<span class="kat-detail__flag">Editörün seçkisi</span>@endif
                </div>
            </div>
        </header>

        <dl class="kat-colophon">
            <div>
                <dt>Hizmet alanı</dt>
                <dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd>
            </div>
            <div>
                <dt>Konum</dt>
                <dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif{{ $districtName ? ' / '.$districtName : '' }}</dd>
            </div>
            <div>
                <dt>Ziyaretçi puanı</dt>
                <dd>{{ $ratingAvg ? $ratingAvg.' / 5 · '.$reviewCount.' yorum' : 'Henüz puan yok' }}</dd>
            </div>
            <div>
                <dt>Profil durumu</dt>
                <dd>{{ $company->is_verified ? 'Doğrulanmış' : 'Beklemede' }} · %{{ $profileScore }} dolu</dd>
            </div>
        </dl>
    </div>

    <div class="kat-wrap kat-detail__body">
        <div class="kat-detail__main">
            @include('frontend.companies.themes.sections')
        </div>
        <aside class="kat-detail__side">
            @include('frontend.companies.themes.sidebar')
        </aside>
    </div>

    @if(str_starts_with((string) $company->external_id, 'osm:'))
        <div class="kat-wrap" style="padding-block:12px;font-size:12px;color:var(--text_muted)">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--secondary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
    @endif

    @include('partials.catalog.cta')
    @include('partials.catalog.footer')
</div>
