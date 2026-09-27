<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar',
        'eyebrow' => request()->routeIs('search') ? 'Sinyal kaydı / Arama sonucu' : 'Sinyal kaydı / Tüm firmalar',
        'title' => $metaTitle ?? 'Firmalar',
        'description' => 'Firmayı seç, sinyalini incele. Kategori ve şehir filtresiyle aramanı daralt.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <form class="sig-filters" action="{{ url()->current() }}" method="GET">
            <label>Aranacak kelime<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit" class="sig-btn">Sinyalı tara</button>
            @if(request()->anyFilled(['q','category','city']))<a href="{{ url()->current() }}">Filtreleri temizle</a>@endif
        </form>
        <div class="sig-cols">
            <div>
                @include('frontend.signal.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Yayındaki firmalar'])
            </div>
            <aside class="sig-side">
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Hizmet hatları</h2><span class="sig-code">Kategori</span></div>
                    <div class="sig-links">@foreach($categories->take(12) as $i => $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="sig-links__no">{{ str_pad($i + 1, 2, '0', STR_PAD_LEFT) }}</span><span class="sig-links__txt">{{ $cat->name }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                </section>
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Şehir sinyalleri</h2><span class="sig-code">Bölge</span></div>
                    <div class="sig-links">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="sig-links__txt">{{ $ct->name }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                </section>
                <div class="sig-promo">
                    <span class="sig-kicker">Sıra sizde</span>
                    <h2 style="margin-top:10px">Firmanızı sinyale bağlayın</h2>
                    <p>Profilinizi oluşturun, şehrin keşif listesinde yerinizi alın.</p>
                    <a class="sig-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
