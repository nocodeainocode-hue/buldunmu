@php
    // Sinyal kodu: slug'ın ilk 3 harfi + firma kimliğinden türeyen 3 haneli seri
    $sigCodePrefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $company->slug) ?: 'gen', 0, 3));
    $sigCodeSerial = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp

<div class="sig sig-detail">
    <section class="sig-band">
        <div class="sig-wrap">
            <nav class="sig-crumb" aria-label="Gezinme yolu">
                <a href="{{ route('home') }}">Ana sayfa</a>
                <span class="sig-crumb__sep">/</span>
                @if($company->category)
                    <a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>
                    <span class="sig-crumb__sep">/</span>
                @endif
                <span>{{ $company->name }}</span>
            </nav>
            <span class="sig-kicker">Sinyal {{ $sigCodePrefix }}-{{ $sigCodeSerial }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            <h1 class="sig-display">{{ $company->name }}</h1>
            <p class="sig-lede">{{ $company->short_description ?: $company->name . ' işletmesinin hizmetlerini, iletişim ve konum bilgilerini tek ekranda görüntüleyin.' }}</p>
            <div class="sig-detail__actions">
                @if($company->phone)<a class="sig-btn" href="tel:{{ $phoneClean }}">Telefon et →</a>@endif
                @if($company->whatsapp)<a class="sig-btn sig-btn--ghost" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp →</a>@endif
                @if($company->website)<a class="sig-btn sig-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi ↗</a>@endif
                @if($company->hasActivePremium())<span class="sig-card__vip">Vitrinde</span>@endif
            </div>
        </div>
    </section>

    <div class="sig-wrap">
        <dl class="sig-meta">
            <div>
                <dt>Hizmet hattı</dt>
                <dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd>
            </div>
            <div>
                <dt>Keşif noktası</dt>
                <dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif{{ $districtName ? ' / ' . $districtName : '' }}</dd>
            </div>
            <div>
                <dt>Ziyaretçi puanı</dt>
                <dd>{{ $ratingAvg ? $ratingAvg . ' / 5 · ' . $reviewCount . ' değerlendirme' : 'Henüz puan yok' }}</dd>
            </div>
            <div>
                <dt>Profil durumu</dt>
                <dd>{{ $company->is_verified ? 'Doğrulanmış profil' : 'Beklemede' }} · Doluluk %{{ $profileScore }}</dd>
            </div>
        </dl>
    </div>

    <div class="sig-wrap sig-detail__body">
        <div class="sig-detail__main">
            @include('frontend.companies.themes.sections')
        </div>
        <aside class="sig-detail__side">
            @include('frontend.companies.themes.sidebar')
        </aside>
    </div>

    @if(str_starts_with((string) $company->external_id, 'osm:'))
        <div class="sig-wrap" style="padding-block:12px;font-size:12px;color:var(--text_muted)">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--primary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
    @endif
</div>
