{{-- CEP · şehir detayı --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span><span>{{ $city->name }}</span>
    </nav>
    <span class="ph-eyebrow">Şehir · {{ $totalInCity }} firma</span>
    <h1>{{ $city->name }}</h1>
    <p>{{ $city->name }} içindeki işletmeleri, semtleri ve sektörleri tek elde gez.</p>
</div>

<form class="ph-filter" action="{{ url()->current() }}" method="GET">
    @if($districts->isNotEmpty())
        <label class="ph-field">İlçe
            <select name="district">
                <option value="">Tüm ilçeler</option>
                @foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach
            </select>
        </label>
    @endif
    <label class="ph-field">Kategori
        <select name="category">
            <option value="">Tüm kategoriler</option>
            @foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach
        </select>
    </label>
    <div style="display:grid;gap:8px">
        <button class="ph-btn" type="submit">Firmaları bul</button>
        @if(request()->anyFilled(['district', 'category']))<a class="ph-btn ph-btn--ghost" href="{{ url()->current() }}">Temizle</a>@endif
    </div>
</form>

@include('frontend.phone.company-list', ['listTitle' => $city->name . ' firmaları'])

@if($popularCategories->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>{{ $city->name }} kategorileri</h2><span class="ph-meta">Sektör</span></div>
        <div class="ph-chips" style="padding:12px 12px 14px">
            @foreach($popularCategories->take(16) as $cat)
                <a class="ph-chip" href="{{ route('categories.show', $cat->slug) }}?city={{ $city->slug }}">{{ $cat->name }} ({{ $cat->companies_count ?? 0 }})</a>
            @endforeach
        </div>
    </section>
@endif

@if($districts->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>İlçeler</h2><span class="ph-meta">Rota</span></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            @foreach($districts->take(12) as $district)
                <a class="ph-row" href="{{ url()->current() }}?district={{ $district->slug }}">
                    <span class="ph-row__av">⌖</span>
                    <div class="ph-row__txt"><strong>{{ $district->name }}</strong><span>{{ $city->name }} / ilçe listesi</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

@if(!empty($seoContent))
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>{{ $city->name }} rehberi</h2></div>
        <div class="ph-prose" style="padding:14px">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
    </section>
@endif

@if($posts->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>Şehirden yazılar</h2></div>
        <div class="ph-list" style="padding:12px 12px 14px">
            @foreach($posts as $post)
                <a class="ph-row" href="{{ route('blog.show', $post->slug) }}">
                    <span class="ph-row__av">📖</span>
                    <div class="ph-row__txt"><strong>{{ $post->title }}</strong><span>{{ $post->published_at?->format('d.m.Y') }}</span></div>
                    <span class="ph-row__go">›</span>
                </a>
            @endforeach
        </div>
    </section>
@endif

@if(($nearbyCities ?? collect())->isNotEmpty())
    <section class="ph-sheet">
        <div class="ph-sheet__head"><h2>Diğer şehirler</h2></div>
        <div class="ph-chips" style="padding:12px 12px 14px">
            @foreach($nearbyCities as $nearby)<a class="ph-chip" href="{{ route('cities.show', $nearby->slug) }}">{{ $nearby->name }}</a>@endforeach
        </div>
    </section>
@endif

<div class="ph-cta">
    <h2>{{ $city->name }}'de yerini al.</h2>
    <p>Firma profilini oluştur, şehirde aranan işletmeler arasında öne çık.</p>
    <a class="ph-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
</div>
