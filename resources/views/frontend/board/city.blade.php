<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => $city->name,
            'tag' => '📍 Şehir',
            'title' => $city->name.' ilanları',
            'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hizmetleri tek panoda gör.',
        ])
        <form class="bd-filters" action="{{ url()->current() }}" method="GET">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit" class="bd-btn">İlanları bul</button>
            @if(request()->anyFilled(['district', 'category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="bd-cols">
            <div class="bd-stack">
                @include('frontend.board.company-list', ['listTitle' => $city->name.' ilanları'])
                @if(!empty($seoContent))
                    <section class="bd-panel"><h2>{{ $city->name }} hakkında</h2><div class="bd-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div></section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="bd-box"><div class="bd-box__head"><h2>Şehirden yazılar</h2></div><div class="bd-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="bd-links__txt">{{ $post->title }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                @endif
            </div>
            <aside class="bd-side">
                @if($districts->isNotEmpty())
                    <section class="bd-box"><div class="bd-box__head"><h2>İlçeler</h2></div><div class="bd-links">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span class="bd-links__txt">{{ $district->name }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                @endif
                <section class="bd-box"><div class="bd-box__head"><h2>Kategoriler</h2><span>{{ $totalInCity }} ilan</span></div><div class="bd-links">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="bd-links__txt">{{ $cat->icon ?? '◆' }} {{ $cat->name }}</span><span class="bd-links__n">{{ $cat->companies_count }}</span></a>@endforeach</div></section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="bd-box"><div class="bd-box__head"><h2>Yakın şehirler</h2></div><div class="bd-links">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span class="bd-links__txt">📍 {{ $nearby->name }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                @endif
                <div class="bd-cta"><h2>{{ $city->name }}'da mısın?</h2><p>İşletmeni panoya ekle, çevrendeki müşteriler bulsun.</p><a class="bd-btn" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a></div>
            </aside>
        </div>
    </div>
</div>
