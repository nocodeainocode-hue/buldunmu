@php
    // Cep · reels detayı: kapak tam ekran, başlık overlay üzerinde
    $phStory = strtoupper(substr(preg_replace('/[^a-z]/i', '', (string) $company->slug) ?: 'gen', 0, 3));
@endphp

@include('partials.phone.device-start')

    <section class="ph-detail__cover">
        @if($company->cover_image)
            <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">
        @endif
        <div style="position:absolute;z-index:1;left:0;right:0;bottom:0;padding:16px">
            <nav class="ph-crumb" aria-label="Gezinme">
                <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span>
                @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif
                <span style="color:var(--ph-ink)">{{ $company->name }}</span>
            </nav>
            <span class="ph-reel__tag" style="margin-top:10px;background:var(--ph-card);color:var(--ph-ink)">{{ $phStory }} · {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
            <h1 style="margin-top:10px;font-size:26px;line-height:1.1;color:var(--ph-ink)">{{ $company->name }}</h1>
        </div>
    </section>

    <div class="ph-detail__id">
        <span class="ph-avatar">
            @if($company->logo)<img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu">@else{{ mb_substr($company->name, 0, 1) }}@endif
        </span>
        <p class="ph-lead" style="margin:0">{{ $company->short_description ?: $company->name . ' işletmesinin hizmetleri, adres ve iletişim bilgileri.' }}</p>
        <div class="ph-meta">
            @if($company->is_verified)<span style="color:var(--ph-ok)">✓ Doğrulanmış</span>@endif
            @if($company->hasActivePremium())<span style="color:var(--ph-primary)">◆ Vitrinde</span>@endif
            <span>{{ $categoryName }}</span>
            @if($ratingAvg)<span>{{ $ratingAvg }} ★ · {{ $reviewCount }} yorum</span>@endif
        </div>
    </div>

    <div class="ph-quick">
        @if($phoneClean)<a href="tel:{{ $phoneClean }}">Telefon et</a>@endif
        @if($whatsappClean)<a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp</a>
        @elseif($company->website)<a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesi</a>
        @else<a href="{{ route('companies.index') }}">Benzerleri</a>@endif
    </div>
    @if($whatsappClean && $company->website)
        <div class="ph-quick"><a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesini aç</a></div>
    @endif

    <dl class="ph-dl">
        <div><dt>Sektör</dt><dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd></div>
        <div><dt>Bölge</dt><dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif{{ $districtName ? ' / ' . $districtName : '' }}</dd></div>
        <div><dt>Puan</dt><dd>{{ $ratingAvg ? $ratingAvg . ' / 5 · ' . $reviewCount . ' değerlendirme' : 'Henüz puan yok' }}</dd></div>
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
