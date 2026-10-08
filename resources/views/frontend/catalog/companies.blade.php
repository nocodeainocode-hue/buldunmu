<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar',
        'eyebrow' => request()->routeIs('search') ? 'Katalog / Arama sonucu' : 'Katalog / Tüm firmalar',
        'title' => $metaTitle ?? 'Firmalar',
        'description' => 'Firmayı seçin, profilini inceleyin. Kategori ve şehir filtresiyle aramanızı daraltın.',
        'folio' => 'A–Z',
    ])
    <div class="kat-wrap">
        <form class="kat-filters" action="{{ url()->current() }}" method="GET">
            <label>Aranacak kelime<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit" class="kat-btn">Listele</button>
            @if(request()->anyFilled(['q', 'category', 'city']))<a href="{{ url()->current() }}">Filtreleri temizle</a>@endif
        </form>
        <div class="kat-cols">
            <div>
                @include('frontend.catalog.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Yayındaki firmalar'])
            </div>
            <aside class="kat-side">
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Hizmet dizini</h2><span>Kategori</span></div>
                    <div class="kat-links">@foreach($categories->take(12) as $i => $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="kat-links__no">{{ str_pad((string) ($i + 1), 2, '0', STR_PAD_LEFT) }}</span><span class="kat-links__txt">{{ $cat->name }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                </section>
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Şehirler</h2><span>Bölge</span></div>
                    <div class="kat-links">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="kat-links__txt">{{ $ct->name }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                </section>
                <div class="kat-promo">
                    <span class="kat-kicker">Sıra sizde</span>
                    <h2>Firmanızı <em>kataloğa</em> ekleyin</h2>
                    <p>Profilinizi oluşturun, şehrin aradığı yerde yerinizi alın.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
