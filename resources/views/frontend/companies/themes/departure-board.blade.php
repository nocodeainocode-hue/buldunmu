@php
    // Bilet kodu: slug'ın ilk 3 harfi + firma kimliğinden türeyen 3 haneli seri
    $depTicketPrefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $company->slug) ?: 'gen', 0, 3));
    $depTicketSerial = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp

<div class="dep dep-detail">
    @include('partials.departures.header')

    <section class="dep-detail__cover">
        @if($company->cover_image)
            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">
        @endif
        <div class="dep-wrap">
            <nav class="dep-crumb" aria-label="Gezinme yolu">
                <a href="{{ route('home') }}">Ana sayfa</a>
                <span>/</span>
                @if($company->category)
                    <a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>
                    <span>/</span>
                @endif
                <span>{{ $company->name }}</span>
            </nav>
            <span class="dep-kicker dep-kicker--light">Bilet {{ $depTicketPrefix }}-{{ $depTicketSerial }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            <h1 class="dep-display">{{ $company->name }}</h1>
            <p>{{ $company->short_description ?: $company->name . ' işletmesinin hizmetlerini, iletişim ve konum bilgilerini kalkış panosundan görüntüleyin.' }}</p>
            <div class="dep-detail__actions">
                @if($company->phone)<a class="dep-btn" href="tel:{{ $phoneClean }}">Telefon et →</a>@endif
                @if($company->whatsapp)<a class="dep-btn dep-btn--on-dark" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp →</a>@endif
                @if($company->website)<a class="dep-btn dep-btn--on-dark" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi ↗</a>@endif
                @if($company->hasActivePremium())<span class="dep-detail__vip">Vitrin hattı</span>@endif
            </div>
        </div>
    </section>

    <div class="dep-wrap">
        <dl class="dep-ticketbar">
            <div>
                <dt>Peron / sektör</dt>
                <dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd>
            </div>
            <div>
                <dt>Sefer noktası</dt>
                <dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif{{ $districtName ? ' / ' . $districtName : '' }}</dd>
            </div>
            <div>
                <dt>Yolcu puanı</dt>
                <dd>{{ $ratingAvg ? $ratingAvg . ' / 5 · ' . $reviewCount . ' değerlendirme' : 'Henüz puan yok' }}</dd>
            </div>
            <div>
                <dt>Kalkış durumu</dt>
                <dd>{{ $company->is_verified ? 'Doğrulanmış profil' : 'Beklemede' }} · Doluluk %{{ $profileScore }}</dd>
            </div>
        </dl>
    </div>

    <div class="dep-wrap dep-detail__body">
        <div class="dep-detail__main">
            @include('frontend.companies.themes.sections')
        </div>
        <aside class="dep-detail__side">
            @include('frontend.companies.themes.sidebar')
        </aside>
    </div>

    @include('partials.departures.footer')

    @if(str_starts_with((string) $company->external_id, 'osm:'))
        <div class="dep-wrap dep-code" style="padding-block:12px">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
    @endif
</div>
