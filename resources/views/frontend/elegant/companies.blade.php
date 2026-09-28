<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => request()->routeIs('search') ? 'Arama' : 'Firmalar',
        'eyebrow' => request()->routeIs('search') ? 'Arama sonuçları' : 'Zengin rehber',
        'title' => $metaTitle ?? 'Firmalar',
        'description' => 'Kategori ve şehirlerde seçkin işletmeleri tek bir zarif rehberde keşfedin.',
    ])
    <div class="el-wrap">
        <form class="el-filters" action="{{ url()->current() }}" method="GET" style="margin-top:34px">
            <label>Kelime<input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet adı"></label>
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($categories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select></label>
            <button type="submit" class="el-btn">Listele</button>
            @if(request()->anyFilled(['q','category','city']))<a href="{{ url()->current() }}">Filtreleri temizle</a>@endif
        </form>
        <div class="el-cols">
            <aside class="el-side">
                <section class="el-box">
                    <div class="el-box__head"><h2>Kategoriler</h2></div>
                    <nav class="el-rail" aria-label="Kategoriler">@foreach($categories->take(15) as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $cat->name }}</span>@if($cat->companies_count ?? null)<span class="el-rail__count">{{ $cat->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                <section class="el-box">
                    <div class="el-box__head"><h2>Şehirler</h2></div>
                    <nav class="el-rail" aria-label="Şehirler">@foreach($cities->take(12) as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $ct->name }}</span></a>@endforeach</nav>
                </section>
                <div class="el-promo">
                    <h2>Firmanızı taşıyın</h2>
                    <p>Ücretsiz kayıt olun, zarif rehberde yerinizi alın.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.elegant.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Yayındaki firmalar'])
            </div>
        </div>
    </div>
</div>
