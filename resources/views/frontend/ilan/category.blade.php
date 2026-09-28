<div class="ib ib-page">
    @include('partials.ilan.band', [
        'crumb' => $category->name,
        'eyebrow' => 'Kategori rehberi',
        'title' => $category->name.' firmaları',
        'description' => $category->description ?: $category->name.' alanındaki işletmeleri şehir seçerek inceleyin.',
    ])
    <div class="ib-wrap">
        <form class="ib-filters" action="{{ url()->current() }}" method="GET" style="margin-top:14px">
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit" class="ib-btn">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="ib-cols">
            <aside class="ib-side">
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Şehirler</h2><span class="ib-box__note">{{ $totalInCategory }} firma</span></div>
                    <nav class="ib-catnav">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span>{{ $ct->name }}</span>@if($ct->companies_count)<span class="ib-catnav__count">{{ $ct->companies_count }}</span>@endif</a>@endforeach</nav>
                </section>
                <section class="ib-box">
                    <div class="ib-box__head"><h2>Benzer kategoriler</h2></div>
                    <nav class="ib-catnav">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span>{{ $related->name }}</span></a>@endforeach</nav>
                </section>
                <div class="ib-promo">
                    <h2>{{ $category->name }} listelerinde çıkın</h2>
                    <p>Firmanızı bu kategoriye ekleyin, alıcılar sizi bulsun.</p>
                    <a class="ib-btn" href="{{ route('owner.register') }}">Firma ekle</a>
                </div>
            </aside>
            <div>
                @include('frontend.ilan.company-list', ['listTitle' => $category->name.' kayıtları'])
                @if(!empty($seoContent))
                    <section class="ib-box" style="margin-top:12px">
                        <div class="ib-box__head"><h2>{{ $category->name }} hakkında</h2></div>
                        <div class="ib-box__body ib-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="ib-box" style="margin-top:12px">
                        <div class="ib-box__head"><h2>Bu kategoriden yazılar</h2><span class="ib-box__note">{{ $posts->count() }} yazı</span></div>
                        <div class="ib-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="ib-links__txt">{{ $post->title }}</span><span class="ib-links__count">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
        </div>
    </div>
</div>
