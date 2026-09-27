<div class="cinema cinema-page">
    @include('partials.cinema.page-hero', ['crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar', 'eyebrow' => request()->routeIs('search') ? 'Arşiv / Arama sonuçları' : 'Arşiv / Tüm firmalar', 'title' => $metaTitle ?? 'Firmalar', 'description' => 'İşletmeleri kategori ve şehir üzerinden keşfedin. Her profil ayrı bir hikâye.'])
    <div class="cinema-wrap cinema-page__body">
        <form class="cinema-filter" action="{{ url()->current() }}" method="GET">
            <label>Aranacak kelime<input name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit">Keşfet ↗</button>
            @if(request()->anyFilled(['q','category','city']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="cinema-columns"><div class="cinema-main">@include('frontend.cinema.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Gösterimdeki firmalar'])</div><aside class="cinema-side">
            <section class="cinema-panel"><div class="cinema-panel__head"><h2>Sahne seçimi</h2><small>Kategoriler</small></div><div class="cinema-link-list">@foreach($categories->take(12) as $cat)<a href="{{ route('categories.show',$cat->slug) }}"><span>{{ $cat->name }}</span><span>↗</span></a>@endforeach</div></section>
            <section class="cinema-panel"><div class="cinema-panel__head"><h2>Şehir rotaları</h2><small>Keşfet</small></div><div class="cinema-link-list">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show',$ct->slug) }}"><span>{{ $ct->name }}</span><span>↗</span></a>@endforeach</div></section>
            <div class="cinema-promo"><span class="cinema-kicker" style="color:#e8aa62">Sıra sizin hikâyenizde</span><h2>Firmanızı sahneye çıkarın.</h2><p>Profilinizi oluşturun, ziyaretçilerin sizi keşfetmesini sağlayın.</p><a class="cinema-btn" href="{{ route('owner.register') }}">Firma ekle ↗</a></div>
        </aside></div>
    </div>
</div>
