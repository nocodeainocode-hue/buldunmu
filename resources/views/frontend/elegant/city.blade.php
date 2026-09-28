<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => $city->name,
        'eyebrow' => $city->name.' rehberi',
        'title' => $city->name.' firmaları',
        'description' => $city->name.' içindeki işletmeleri, ilçeleri ve hizmet kategorilerini zarif bir düzende keşfedin.',
    ])
    <div class="el-wrap">
        <form class="el-filters" action="{{ url()->current() }}" method="GET" style="margin-top:34px">
            @if($districts->isNotEmpty())
                <label>İlçe<select name="district"><option value="">Tüm ilçeler</option>@foreach($districts as $district)<option value="{{ $district->slug }}" @selected(request('district') == $district->slug)>{{ $district->name }}</option>@endforeach</select></label>
            @endif
            <label>Kategori<select name="category"><option value="">Tüm kategoriler</option>@foreach($popularCategories as $cat)<option value="{{ $cat->slug }}" @selected(request('category') == $cat->slug)>{{ $cat->name }}</option>@endforeach</select></label>
            <button type="submit" class="el-btn">Firmaları bul</button>
            @if(request()->anyFilled(['district','category']))<a href="{{ url()->current() }}">Temizle</a>@endif
        </form>
        <div class="el-cols">
            <aside class="el-side">
                @if($districts->isNotEmpty())
                    <section class="el-box">
                        <div class="el-box__head"><h2>İlçeler</h2></div>
                        <nav class="el-rail">@foreach($districts->take(15) as $district)<a href="{{ url()->current() }}?district={{ $district->slug }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $district->name }}</span></a>@endforeach</nav>
                    </section>
                @endif
                <section class="el-box">
                    <div class="el-box__head"><h2>Kategoriler</h2><span class="el-box__note">{{ $totalInCity }} firma</span></div>
                    <nav class="el-rail">@foreach($popularCategories as $cat)<a href="{{ route('categories.show', $cat->slug) }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $cat->name }}</span>@if($cat->companies_count)<span class="el-rail__count">{{ $cat->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                @if(($nearbyCities ?? collect())->isNotEmpty())
                    <section class="el-box">
                        <div class="el-box__head"><h2>Yakın şehirler</h2></div>
                        <nav class="el-links">@foreach($nearbyCities as $nearby)<a href="{{ route('cities.show', $nearby->slug) }}"><span class="el-links__txt">{{ $nearby->name }}</span><span class="el-links__count">→</span></a>@endforeach</nav>
                    </section>
                @endif
                <div class="el-promo">
                    <h2>{{ $city->name }}'de görünür olun</h2>
                    <p>Firma profilinizi oluşturun, ilçe ve kategorilerde yer alın.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.elegant.company-list', ['listTitle' => $city->name.' firmaları'])
                @if(!empty($seoContent))
                    <section class="el-box">
                        <div class="el-box__head"><h2>{{ $city->name }} hakkında</h2></div>
                        <div class="el-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="el-box">
                        <div class="el-box__head"><h2>Şehirden yazılar</h2><span class="el-box__note">{{ $posts->count() }} yazı</span></div>
                        <div class="el-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="el-links__txt">{{ $post->title }}</span><span class="el-links__count">→</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
