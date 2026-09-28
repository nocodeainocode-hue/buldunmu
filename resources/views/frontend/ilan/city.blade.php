<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => $city->name,
        'eyebrow' => $city->name.' rehberi',
        'title' => $city->name.' firmaları',
        'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hizmet kategorilerini tek ekranda inceleyin.',
    ])
    <div class="ib-wrap">
        <form class="ib-filters" action="{{ url()->current() }}" method="GET" style="margin-top:14px">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit" class="ib-btn">Firmaları bul</button>
            @if(request()->anyFilled(['district','category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="ib-cols">
            <aside class="ib-side">
                @if($districts->isNotEmpty())
                    <section class="ib-box">
                        <div class="ib-box__head"><h2>İlçeler</h2></div>
                        <nav class="ib-catnav">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span>{{ $district->name }}</span></a>@endforeach</nav>
                    </section>
                @endif
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Kategoriler</h2><span class="ib-box__note">{{ $totalInCity }} firma</span></div>
                    <nav class="ib-catnav">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span>{{ $cat->name }}</span>@if($cat->companies_count)<span class="ib-catnav__count">{{ $cat->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="ib-box">
                        <div class="ib-box__head"><h2>Yakın şehirler</h2></div>
                        <nav class="ib-catnav">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span>{{ $nearby->name }}</span></a>@endforeach</nav>
                    </section>
                @endif
                <div class="ib-promo">
                    <h2>{{ $city->name }}'de görünür olun</h2>
                    <p>Firma profilinizi oluşturun, ilçe ve kategorilerde yer alın.</p>
                    <a class="ib-btn" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.ilan.company-list', ['listTitle' => $city->name.' firmaları'])
                @if(!empty($seoContent))
                    <section class="ib-box" style="margin-top:12px">
                        <div class="ib-box__head"><h2>{{ $city->name }} hakkında</h2></div>
                        <div class="ib-box__body ib-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="ib-box" style="margin-top:12px">
                        <div class="ib-box__head"><h2>Şehirden yazılar</h2><span class="ib-box__note">{{ $posts->count() }} yazı</span></div>
                        <div class="ib-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="ib-links__txt">{{ $post->title }}</span><span class="ib-links__count">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
