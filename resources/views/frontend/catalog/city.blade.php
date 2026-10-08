<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => $city->name,
        'eyebrow' => 'Şehir rehberi',
        'title' => $city->name,
        'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hizmet alanlarını tek sayfada inceleyin.',
        'folio' => str_pad((string) $totalInCity, 2, '0', STR_PAD_LEFT),
    ])
    <div class="kat-wrap">
        <form class="kat-filters" action="{{ url()->current() }}" method="GET">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit" class="kat-btn">Firmaları bul</button>
            @if(request()->anyFilled(['district', 'category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="kat-cols">
            <div>
                @include('frontend.catalog.company-list', ['listTitle' => $city->name.' firmaları'])
                @if(!empty($seoContent))
                    <section class="kat-panel" style="margin-top:44px">
                        <div class="kat-panel__head"><h2>{{ $city->name }} hakkında</h2><span>Şehir rehberi</span></div>
                        <div class="kat-panel__body kat-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="kat-panel">
                        <div class="kat-panel__head"><h2>Şehirden yazılar</h2><span>{{ $posts->count() }} yazı</span></div>
                        <div class="kat-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="kat-links__txt">{{ $post->title }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="kat-side">
                @if($districts->isNotEmpty())
                    <section class="kat-panel">
                        <div class="kat-panel__head"><h2>İlçeler</h2><span>Bölge</span></div>
                        <div class="kat-links">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span class="kat-links__txt">{{ $district->name }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                    </section>
                @endif
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Hizmet alanları</h2><span>{{ $totalInCity }} firma</span></div>
                    <div class="kat-links">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="kat-links__txt">{{ $cat->name }}</span><span class="kat-links__count">{{ $cat->companies_count }}</span></a>@endforeach</div>
                </section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="kat-panel">
                        <div class="kat-panel__head"><h2>Yakın şehirler</h2><span>Komşu</span></div>
                        <div class="kat-links">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span class="kat-links__txt">{{ $nearby->name }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                    </section>
                @endif
                <div class="kat-promo">
                    <span class="kat-kicker">{{ $city->name }}</span>
                    <h2>Katalogda <em>yerinizi</em> alın</h2>
                    <p>Firma profilinizi oluşturun, {{ $city->name }} içinde aranın.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
