@php
    $bdAgo = $company->created_at?->locale('tr')->diffForHumans();
    $bdAdNo = str_pad((string) $company->id, 5, '0', STR_PAD_LEFT);
@endphp
<div class="bd bd-detail">
    @include('partials.board.header')

    <div class="bd-wrap">
        <nav class="bd-crumb" aria-label="Gezinme yolu" style="padding-top:18px">
            <a href="{{ route('home') }}">Pano</a><span>›</span>
            @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a><span>›</span>@endif
            <span>{{ $company->name }}</span>
        </nav>

        <div class="bd-detail__cover">
            @if($company->cover_image)<img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">@endif
        </div>
        <div class="bd-detail__head">
            <div class="bd-detail__logo">
                @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }} logosu">@else{{ mb_strtoupper(mb_substr($company->name, 0, 1)) }}@endif
            </div>
            <div class="bd-detail__title">
                <h1>{{ $company->name }}</h1>
                <div class="bd-detail__meta">
                    <span>📍 {{ $cityName }}{{ $districtName ? ' / '.$districtName : '' }}</span>
                    @if($bdAgo)<span>🕒 {{ $bdAgo }} eklendi</span>@endif
                    <span>İlan no #{{ $bdAdNo }}</span>
                    @if($company->hasActivePremium())<span class="bd-badge bd-badge--pin">📌 Sabit</span>@endif
                    @if($company->is_verified)<span class="bd-badge bd-badge--ok">✓ Doğrulanmış</span>@endif
                </div>
            </div>
        </div>
        <p class="bd-detail__lede">{{ $company->short_description ?: $company->name.' işletmesinin hizmetlerini, iletişim ve konum bilgilerini tek sayfada görüntüleyin.' }}</p>

        <dl class="bd-facts">
            <div><dt>Kategori</dt><dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd></div>
            <div><dt>Şehir</dt><dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif</dd></div>
            <div><dt>Puan</dt><dd>{{ $ratingAvg ? '★ '.$ratingAvg.' · '.$reviewCount.' yorum' : 'Henüz puan yok' }}</dd></div>
            <div><dt>Profil</dt><dd>%{{ $profileScore }} dolu</dd></div>
        </dl>

        <div class="bd-detail__body">
            <div class="bd-detail__main">
                @include('frontend.companies.themes.sections')
            </div>
            <aside class="bd-detail__side">
                <div class="bd-contact">
                    <h2>İletişime geç</h2>
                    <div class="bd-contact__btns">
                        @if($company->phone)<a class="bd-btn bd-btn--hot" href="tel:{{ $phoneClean }}">📞 {{ $company->phone }}</a>@endif
                        @if($company->whatsapp)<a class="bd-btn" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">💬 WhatsApp</a>@endif
                        @if($company->website)<a class="bd-btn bd-btn--ghost" href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">🌐 Web sitesi</a>@endif
                        @if(!$company->phone && !$company->whatsapp && !$company->website)<p class="bd-muted" style="font-size:14px">İletişim bilgisi henüz eklenmemiş.</p>@endif
                    </div>
                    <dl>
                        <div><dt>İlan no</dt><dd>#{{ $bdAdNo }}</dd></div>
                        <div><dt>Konum</dt><dd>{{ $cityName }}</dd></div>
                    </dl>
                </div>
                @include('frontend.companies.themes.sidebar')
            </aside>
        </div>

        @if(str_starts_with((string) $company->external_id, 'osm:'))
            <p class="bd-muted" style="padding-block:12px;font-size:12px">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" style="text-decoration:underline">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</p>
        @endif
    </div>

    @include('partials.board.footer')
</div>
