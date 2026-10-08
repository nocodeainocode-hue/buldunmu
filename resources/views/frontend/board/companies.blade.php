<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => request()->routeIs('search') ? 'Arama' : 'Tüm ilanlar',
            'tag' => request()->routeIs('search') ? 'Arama sonucu' : 'Tüm ilanlar',
            'title' => $metaTitle ?? 'Firmalar',
            'description' => 'Kategori ve şehir filtreleriyle aradığın işletmeyi hızlıca bul.',
        ])
        <form class="bd-filters" action="{{ url()->current() }}" method="GET">
            <label>Ne arıyorsun?<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit" class="bd-btn">Filtrele</button>
            @if(request()->anyFilled(['q', 'category', 'city']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="bd-cols">
            <div>@include('frontend.board.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Yayındaki ilanlar'])</div>
            <aside class="bd-side">
                <section class="bd-box">
                    <div class="bd-box__head"><h2>Kategoriler</h2></div>
                    <div class="bd-links">@foreach($categories->take(12) as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="bd-links__txt">{{ $cat->icon ?? '◆' }} {{ $cat->name }}</span><span class="bd-links__n">→</span></a>@endforeach</div>
                </section>
                <section class="bd-box">
                    <div class="bd-box__head"><h2>Şehirler</h2></div>
                    <div class="bd-links">@foreach($cities->take(10) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="bd-links__txt">📍 {{ $ct->name }}</span><span class="bd-links__n">→</span></a>@endforeach</div>
                </section>
                <div class="bd-cta"><h2>İlan ver</h2><p>İşletmeni panoya ekle, müşteriler seni bulsun.</p><a class="bd-btn" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a></div>
            </aside>
        </div>
    </div>
</div>
