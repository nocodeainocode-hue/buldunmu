<section class="td-side-card td-contact" id="iletisim">
    <p class="td-side-label">Firma bilgileri / iletişim</p>
    <h2>{{ $company->name }}</h2>
    <dl>
        <div><dt>Kategori</dt><dd>@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd></div>
        <div><dt>Şehir</dt><dd>@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}">{{ $cityName }}</a>@else{{ $cityName }}@endif{{ $districtName ? ' / '.$districtName : '' }}</dd></div>
        @if($company->address)<div><dt>Adres</dt><dd>{{ $company->address }}</dd></div>@endif
        @if($company->phone)<div><dt>Telefon</dt><dd><a href="tel:{{ $phoneClean }}">{{ $company->phone }}</a></dd></div>@endif
        @if($company->email)<div><dt>E-posta</dt><dd><a href="mailto:{{ $company->email }}">{{ $company->email }}</a></dd></div>@endif
    </dl>
    <div class="td-actions">
        @if($company->phone)<a href="tel:{{ $phoneClean }}">Telefon et ↗</a>@endif
        @if($company->whatsapp)<a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer">WhatsApp ↗</a>@endif
        @if($company->website)<a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer">Web sitesini aç ↗</a>@endif
        @if($company->email)<a href="mailto:{{ $company->email }}">E-posta gönder ↗</a>@endif
    </div>
</section>

<section class="td-side-card td-claim"><p class="td-side-label">Firma sahipleri için</p><h2>Bu profil size mi ait?</h2><p>Bilgilerinizi doğrulayın ve profilinizi yönetin.</p><a href="{{ route('companies.claim', $company->slug) }}">Profili sahiplen ↗</a><small>Profil doluluğu %{{ $profileScore }} · Son güncelleme {{ $company->updated_at?->format('d.m.Y') }}</small></section>

@if($similarCompanies->isNotEmpty())
<section class="td-side-card"><p class="td-side-label">Keşfe devam et</p><h2>Benzer firmalar</h2><div class="td-related">@foreach($similarCompanies->take(4) as $similar)<a href="{{ route('companies.show', $similar->slug) }}"><strong>{{ $similar->name }}</strong><span>{{ $similar->category?->name }} · {{ $similar->city?->name }}</span></a>@endforeach</div></section>
@endif

@if($nearbyCompanies->isNotEmpty())
<section class="td-side-card"><p class="td-side-label">Yakın çevre</p><h2>Yakındaki firmalar</h2><div class="td-related">@foreach($nearbyCompanies->take(3) as $nearby)<a href="{{ route('companies.show', $nearby->slug) }}"><strong>{{ $nearby->name }}</strong><span>{{ $nearby->category?->name }}</span></a>@endforeach</div></section>
@endif

@if($sameCategoryCompanies->isNotEmpty())
<section class="td-side-card"><p class="td-side-label">Aynı sektör</p><h2>Diğer seçenekler</h2><div class="td-related">@foreach($sameCategoryCompanies->take(3) as $sameCat)<a href="{{ route('companies.show', $sameCat->slug) }}"><strong>{{ $sameCat->name }}</strong><span>{{ $sameCat->city?->name }}</span></a>@endforeach</div></section>
@endif
