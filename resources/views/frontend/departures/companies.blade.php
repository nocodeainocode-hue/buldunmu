<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar',
        'eyebrow' => request()->routeIs('search') ? 'Sefer kaydı / Arama sonucu' : 'Sefer kaydı / Tüm firmalar',
        'title' => $metaTitle ?? 'Firmalar',
        'description' => 'Firmayı seç, peronunu öğren. Kategori ve şehir filtresiyle aramanı daralt.',
    ])
    <div class="dep-wrap dep-page__body">
        <form class="dep-filter" action="{{ url()->current() }}" method="GET">
            <label>Aranacak kelime<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit">Panoyu tara</button>
            @if(request()->anyFilled(['q','category','city']))<a href="{{ url()->current() }}">Filtreleri temizle</a>@endif
        </form>
        <div class="dep-columns">
            <div class="dep-main">
                @include('frontend.departures.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Kalkıştaki firmalar'])
            </div>
            <aside class="dep-side">
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Hatlar</h2><span class="dep-code">Kategori</span></div>
                    <div class="dep-link-list">@foreach($categories->take(12) as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span>{{ $cat->name }}</span><b>→</b></a>@endforeach</div>
                </section>
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Tarifeler</h2><span class="dep-code">Şehir</span></div>
                    <div class="dep-link-list">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span>{{ $ct->name }}</span><b>→</b></a>@endforeach</div>
                </section>
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Sıra sizde</span>
                    <h2>Firmanızı panoya yazın</h2>
                    <p>Profilinizi oluşturun, şehrin kalkış listesinde yerinizi alın.</p>
                    <a class="dep-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
