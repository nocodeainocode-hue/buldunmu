<div class="dep dep-page">
    @include('partials.departures.page-hero', [
        'crumb' => $category->name,
        'eyebrow' => 'Hat / Kategori',
        'title' => $category->name.' hattı',
        'description' => $category->description ?: $category->name.' alanındaki işletmeleri ve hizmetleri tek panoda görün.',
    ])
    <div class="dep-wrap dep-page__body">
        <form class="dep-filter" action="{{ url()->current() }}" method="GET">
            <label>Şehir seçin<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="dep-columns">
            <div class="dep-main">
                @include('frontend.departures.company-list', ['listTitle' => $category->name.' seferleri'])
                @if(!empty($seoContent))
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>{{ $category->name }} hakkında</h2><span class="dep-code">Rehber</span></div>
                        <div class="dep-copy">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="dep-panel">
                        <div class="dep-panel__head"><h2>Bu hattan yazılar</h2><span class="dep-code">{{ $posts->count() }} kayıt</span></div>
                        <div class="dep-link-list">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span>{{ $post->title }}</span><b>→</b></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="dep-side">
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Şehirler</h2><span class="dep-code">{{ $totalInCategory }} firma</span></div>
                    <div class="dep-link-list">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span>{{ $ct->name }}</span><b>{{ $ct->companies_count }}</b></a>@endforeach</div>
                </section>
                <section class="dep-panel">
                    <div class="dep-panel__head"><h2>Diğer hatlar</h2><span class="dep-code">Kategori</span></div>
                    <div class="dep-link-list">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span>{{ $related->name }}</span><b>→</b></a>@endforeach</div>
                </section>
                <div class="dep-promo">
                    <span class="dep-kicker dep-kicker--light">Bu hatta mısınız?</span>
                    <h2>Panoya adınızı yazın</h2>
                    <p>{{ $category->name }} kategorisinde firmalarınızı görünür kılın.</p>
                    <a class="dep-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
