<div class="cinema cinema-detail">
    @include('partials.cinema.header')
    <section class="cinema-detail__cover">
        @if($company->cover_image)<img src="{{ asset('storage/'.$company->cover_image) }}" alt="{{ $company->name }} kapak görseli" loading="eager">@endif
        <div class="cinema-wrap">
            <nav class="cinema-crumb" aria-label="Gezinme yolu"><a href="{{ route('home') }}">Ana sayfa</a><span>/</span>@if($company->category)<a href="{{ route('categories.show',$company->category->slug) }}">{{ $categoryName }}</a><span>/</span>@endif<span>{{ $company->name }}</span></nav>
            <span class="cinema-kicker" style="color:#e8aa62">Firma portresi / {{ $cityName }}{{ $districtName ? ' · '.$districtName : '' }}</span>
            <h1 class="cinema-title">{{ $company->name }}</h1>
            <p>{{ $company->short_description ?: $company->name.' işletmesinin hizmetlerini, iletişim ve konum bilgilerini keşfedin.' }}</p>
            <div style="display:flex;flex-wrap:wrap;gap:10px;margin-top:25px">
                @if($company->phone)<a class="cinema-btn" href="tel:{{ $phoneClean }}">Telefon et ↗</a>@endif
                @if($company->whatsapp)<a class="cinema-btn" href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>@endif
                @if($company->hasActivePremium())<span class="cinema-btn" style="background:#e8aa62;border-color:#e8aa62;color:#17282d!important;cursor:default">Öne çıkan firma</span>@endif
            </div>
        </div>
    </section>
    <div class="cinema-wrap cinema-detail__body"><div class="cinema-detail__main">
        @include('frontend.companies.themes.sections')
    </div><aside class="cinema-detail__side">
        @include('frontend.companies.themes.sidebar')
    </aside></div>
    @include('partials.cinema.footer')
    @if(str_starts_with((string)$company->external_id,'osm:'))<div class="cinema-wrap" style="padding:10px 0;font-size:11px">Konum verileri <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener">&copy; OpenStreetMap katkıda bulunanlar</a> tarafından sağlanmıştır.</div>@endif
</div>
