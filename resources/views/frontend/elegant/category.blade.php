<div class="el el-page">
    @include('partials.elegant.band', [
        'crumb' => $category->name,
        'eyebrow' => 'Kategori rehberi',
        'title' => $category->name.' firmaları',
        'description' => $category->description ?: $category->name.' alanındaki işletmeleri şehir seçerek zarifçe inceleyin.',
    ])
    <div class="el-wrap">
        <form class="el-filters" action="{{ url()->current() }}" method="GET" style="margin-top:34px">
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit" class="el-btn">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="el-cols">
            <aside class="el-side">
                <section class="el-box">
                    <div class="el-box__head"><h2>Şehirler</h2><span class="el-box__note">{{ $totalInCategory }} firma</span></div>
                    <nav class="el-rail">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $ct->name }}</span>@if($ct->companies_count)<span class="el-rail__count">{{ $ct->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                <section class="el-box">
                    <div class="el-box__head"><h2>Benzer kategoriler</h2></div>
                    <nav class="el-rail">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span class="el-idx">{{ str_pad($loop->iteration, 2, '0', STR_PAD_LEFT) }}</span><span class="el-rail__txt">{{ $related->name }}</span></a>@endforeach</nav>
                </section>
                <div class="el-promo">
                    <h2>{{ $category->name }} listelerinde çıkın</h2>
                    <p>Firmanızı bu kategoriye ekleyin, misafirleriniz sizi bulsun.</p>
                    <a class="el-btn el-btn--gold" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.elegant.company-list', ['listTitle' => $category->name.' kayıtları'])
                @if(!empty($seoContent))
                    <section class="el-box">
                        <div class="el-box__head"><h2>{{ $category->name }} hakkında</h2></div>
                        <div class="el-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="el-box">
                        <div class="el-box__head"><h2>Bu kategoriden yazılar</h2><span class="el-box__note">{{ $posts->count() }} yazı</span></div>
                        <div class="el-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="el-links__txt">{{ $post->title }}</span><span class="el-links__count">→</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
