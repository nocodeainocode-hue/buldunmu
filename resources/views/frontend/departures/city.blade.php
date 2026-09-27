<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => $city->name,
        'eyebrow' => 'Tarife / Şehir',
        'title' => $city->name.' peronları',
        'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hatları tek kalkış panosunda inceleyin.',
    ])
    <div class="dep-wrap dep-page__body">
        <form class="dep-filter" action="{{ url()->current() }}" method="GET">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit">Firmaları bul</button>
            @if(request()->anyFilled(['district','category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="dep-columns">
            <div class="dep-main">
                @include('frontend.departures.company-list', ['listTitle' => $city->name.' kalkış listesi'])
                @if(!empty($seoContent))
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>{{ $city->name }} hakkında</h2><span class="dep-code">Şehir rehberi</span></div>
                        <div class="dep-copy">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>Şehirden yazılar</h2><span class="dep-code">{{ $posts->count() }} kayıt</span></div>
                        <div class="dep-link-list">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span>{{ $post->title }}</span><b>→</b></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="dep-side">
                @if($districts->isNotEmpty())
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>İlçe durakları</h2><span class="dep-code">Tarife</span></div>
                        <div class="dep-link-list">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span>{{ $district->name }}</span><b>→</b></a>@endforeach</div>
                    </section>
                @endif
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Hatlar</h2><span class="dep-code">{{ $totalInCity }} firma</span></div>
                    <div class="dep-link-list">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span>{{ $cat->name }}</span><b>{{ $cat->companies_count }}</b></a>@endforeach</div>
                </section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>Yakın şehirler</h2><span class="dep-code">Aktarma</span></div>
                        <div class="dep-link-list">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span>{{ $nearby->name }}</span><b>→</b></a>@endforeach</div>
                    </section>
                @endif
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">{{ $city->name }} durağı</span>
                    <h2>Panoda yerinizi alın</h2>
                    <p>Firma profilinizi oluşturun, {{ $city->name }} içinde aranın.</p>
                    <a class="dep-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
