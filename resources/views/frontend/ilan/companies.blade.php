<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar',
        'eyebrow' => request()->routeIs('search') ? 'Arama sonuçları' : 'Tüm firmalar',
        'title' => $metaTitle ?? 'Firmalar',
    ])
    <div class="ib-wrap">
        <form class="ib-filters" action="{{ url()->current() }}" method="GET" style="margin-top:14px">
            <label>Kelime<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit" class="ib-btn">Listele</button>
            @if(request()->anyFilled(['q','category','city']))<a href="{{ url()->current() }}">Filtreleri temizle</a>@endif
        </form>
        <div class="ib-cols">
            <aside class="ib-side">
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Kategoriler</h2></div>
                    <nav class="ib-catnav" aria-label="Kategoriler">@foreach($categories->take(15) as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span>{{ $cat->name }}</span>@if($cat->companies_count ?? null)<span class="ib-catnav__count">{{ $cat->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Şehirler</h2></div>
                    <nav class="ib-catnav" aria-label="Şehirler">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span>{{ $ct->name }}</span></a>@endforeach</nav>
                </section>
                <div class="ib-promo">
                    <h2>Firmanızı buraya taşıyın</h2>
                    <p>Ücretsiz kayıt olun, listelerde yerinizi alın.</p>
                    <a class="ib-btn" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.ilan.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Yayındaki firmalar'])
            </div>
        </div>
    </div>
</div>
