<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => $city->name,
        'eyebrow' => 'Keşif noktası / Şehir',
        'title' => $city->name.' sinyalleri',
        'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hizmet hatlarını tek ekranda inceleyin.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <form class="sig-filters" action="{{ url()->current() }}" method="GET">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit" class="sig-btn">Firmaları bul</button>
            @if(request()->anyFilled(['district','category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="sig-cols">
            <div>
                @include('frontend.signal.company-list', ['listTitle' => $city->name.' keşif listesi'])
                @if(!empty($seoContent))
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>{{ $city->name }} hakkında</h2><span class="sig-code">Şehir rehberi</span></div>
                        <div class="sig-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>Şehirden yazılar</h2><span class="sig-code">{{ $posts->count() }} kayıt</span></div>
                        <div class="sig-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="sig-links__txt">{{ $post->title }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="sig-side">
                @if($districts->isNotEmpty())
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>İlçe durakları</h2><span class="sig-code">Bölge</span></div>
                        <div class="sig-links">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span class="sig-links__txt">{{ $district->name }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                    </section>
                @endif
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Hizmet hatları</h2><span class="sig-code">{{ $totalInCity }} firma</span></div>
                    <div class="sig-links">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="sig-links__txt">{{ $cat->name }}</span><span class="sig-links__count">{{ $cat->companies_count }}</span></a>@endforeach</div>
                </section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>Yakın şehirler</h2><span class="sig-code">Aktarma</span></div>
                        <div class="sig-links">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span class="sig-links__txt">{{ $nearby->name }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                    </section>
                @endif
                <div class="sig-promo">
                    <span class="sig-kicker">{{ $city->name }} durağı</span>
                    <h2 style="margin-top:10px">Sinyalde yerinizi alın</h2>
                    <p>Firma profilinizi oluşturun, {{ $city->name }} içinde aranın.</p>
                    <a class="sig-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
