@php
    // Referans kodu: slug'ın ilk 3 harfi + firma kimliğinden türeyen 3 haneli seri
    $elCodePrefix = strtoupper(substr(preg_replace('/[^a-z]/i', '', $company->slug) ?: 'gen', 0, 3));
    $elCodeSerial = str_pad((string) (($company->id % 900) + 100), 3, '0', STR_PAD_LEFT);
@endphp

<div class="el el-detail el-page">
    <div class="el-wrap">
        <header class="el-detail__head">
            <nav class="el-crumb" aria-label="Gezinme yolu">
                <a href="{{ route('home') }}">Ana sayfa</a>
                <span class="el-crumb__sep">/</span>
                @if($company->category)
                    <a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>
                    <span class="el-crumb__sep">/</span>
                @endif
                <span>{{ $company->name }}</span>
            </nav>
            <span class="el-kicker">Kayıt {{ $elCodePrefix }}-{{ $elCodeSerial }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            <h1>{{ $company->name }}@if($company->hasActivePremium()) <span style="font-size:14px;font-family:var(--font_body);letter-spacing:.18em;text-transform:uppercase;color:var(--accent);vertical-align:middle">Seçkin</span>@endif</h1>
            <p style="margin-top:16px;font-size:16px;font-weight:300;color:var(--text_muted);max-width:64ch;line-height:1.7">{{ $company->short_description ?: $company->name . ' işletmesinin hizmetlerini, iletişim ve konum bilgilerini tek zarif sayfada görüntüleyin.' }}</p>
            <div class="el-detail__actions">
                @if($company->phone)<a class="el-btn" href="tel:{{ $phoneClean }}">Telefon edin</a>@endif
                @if($company->whatsapp)<a class="el-btn el-btn--gold" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>@endif
                @if($company->website)<a class="el-btn el-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi ↗</a>@endif
            </div>
        </header>

        <dl class="el-meta">
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
                <dd>{{ $ratingAvg ? $ratingAvg . ' / 5' : 'Puan yok' }}</dd>
            </div>
            <div>
                <dt>Profil durumu</dt>
                <dd>{{ $company->is_verified ? 'Doğrulanmış' : 'Beklemede' }} · %{{ $profileScore }}</dd>
            </div>
        </dl>

        <div class="el-detail__body">
            <div class="el-detail__main">
                @include('frontend.companies.themes.sections')
            </div>
            <aside class="el-detail__side">
                @include('frontend.companies.themes.sidebar')
            </aside>
        </div>

        @if(str_starts_with((string) $company->external_id, 'osm:'))
            <div style="padding-block:16px;font-size:12.5px;color:var(--text_muted);border-top:1px solid var(--border);margin-top:32px">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="color:var(--primary)">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>
        @endif
    </div>
</div>
