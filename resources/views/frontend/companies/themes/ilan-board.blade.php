@php
    // Referans kodu: slug'ın ilk 3 harfi + firma kimliğinden türeyen 3 haneli seri
    $ibCodePrefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $company->slug) ?: 'gen', 0, 3));
    $ibCodeSerial = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp

<div class="ib ib-detail ib-page">
    <div class="ib-wrap">
        <header class="ib-detail__head">
            <nav class="ib-crumb" aria-label="Gezinme yolu" style="color:var(--text_muted)">
                <a href="{{ route('home') }}" style="color:var(--primary)">Ana sayfa</a>
                <span class="ib-crumb__sep">/</span>
                @if($company->category)
                    <a href="{{ route('categories.show', $company->category->slug) }}" style="color:var(--primary)">{{ $categoryName }}</a>
                    <span class="ib-crumb__sep">/</span>
                @endif
                <span>{{ $company->name }}</span>
            </nav>
            <span class="ib-kicker">Kayıt {{ $ibCodePrefix }}-{{ $ibCodeSerial }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            <h1>{{ $company->name }}@if($company->hasActivePremium()) <span class="ib-tag">Spot</span>@endif</h1>
            <p style="margin-top:8px;font-size:13.5px;color:var(--text_muted);max-width:80ch">{{ $company->short_description ?: $company->name . ' işletmesinin hizmetlerini, iletişim ve konum bilgilerini tek ekranda görüntüleyin.' }}</p>
            <div class="ib-detail__actions">
                @if($company->phone)<a class="ib-btn" href="tel:{{ $phoneClean }}">Telefon et</a>@endif
                @if($company->whatsapp)<a class="ib-btn ib-btn--primary" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif
                @if($company->website)<a class="ib-btn ib-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi ↗</a>@endif
            </div>
        </header>

        <dl class="ib-meta">
            <div>
                <dt>Kategori</dt>
                <dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd>
            </div>
            <div>
                <dt>Konum</dt>
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

        <div class="ib-detail__body">
            <div class="ib-detail__main">
                @include('frontend.companies.themes.sections')
            </div>
            <aside class="ib-detail__side">
                @include('frontend.companies.themes.sidebar')
            </aside>
        </div>

        @if(str_starts_with((string) $company->external_id, 'osm:'))
            <div style="padding-block:10px;font-size:11.5px;color:var(--text_muted)">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--primary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
        @endif
    </div>
</div>
