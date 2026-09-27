{{-- CEP · firma listesi ve arama sonuçları --}}
<div class="ph-pagehero">
    <nav class="ph-crumb" aria-label="Gezinme">
        <a href="{{ route('home') }}">Ana Sayfa</a><span>/</span>
        <span>{{ request()->routeIs('search') ? 'Arama' : 'Firmalar' }}</span>
    </nav>
    <span class="ph-eyebrow">{{ request()->routeIs('search') ? 'Arama sonucu' : 'Tüm firma listesi' }}</span>
    <h1>{{ $metaTitle ?? 'Firmalar' }}</h1>
    <p>Kategori ve şehir filtresini kullan; firmayı aç, tek dokunuşta ara.</p>
</div>

<form class="ph-filter" action="{{ url()->current() }}" method="GET">
    <label class="ph-field">Aranacak kelime
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı">
    </label>
    <label class="ph-field">Kategori
        <select name="category">
            <option value="">Tüm kategoriler</option>
            @foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach
        </select>
    </label>
    <label class="ph-field">Şehir
        <select name="city">
            <option value="">Tüm şehirler</option>
            @foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach
        </select>
    </label>
    <div style="display:grid;gap:8px">
        <button class="ph-btn" type="submit">Listele</button>
        @if(request()->anyFilled(['q', 'category', 'city']))
            <a class="ph-btn ph-btn--ghost" href="{{ url()->current() }}">Filtreleri temizle</a>
        @endif
    </div>
</form>

@include('frontend.phone.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Kayıtlı firmalar'])

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Kategoriler</h2><span class="ph-meta">Sektör</span></div>
    <div class="ph-chips" style="padding:12px 12px 14px">
        @foreach($categories->take(14) as $cat)
            <a class="ph-chip" href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}</a>
        @endforeach
    </div>
</section>

<section class="ph-sheet">
    <div class="ph-sheet__head"><h2>Şehirler</h2><span class="ph-meta">Bölge</span></div>
    <div class="ph-list" style="padding:12px 12px 14px">
        @foreach($cities->take(10) as $ct)
            <a class="ph-row" href="{{ route('cities.show', $ct->slug) }}">
                <span class="ph-row__av">📍</span>
                <div class="ph-row__txt"><strong>{{ $ct->name }}</strong><span>{{ $ct->companies_count ?? 0 }} firma</span></div>
                <span class="ph-row__go">›</span>
            </a>
        @endforeach
    </div>
</section>

<div class="ph-cta">
    <h2>Firman burada yok mu?</h2>
    <p>Profilini oluştur, cep telefonundan dakikalar içinde listeye katıl.</p>
    <a class="ph-btn" href="{{ route('owner.register') }}">Hemen ekle →</a>
</div>
