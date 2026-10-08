<div class="kat kat-page">
    @include('partials.catalog.band', [
        'crumb' => $category->name,
        'eyebrow' => 'Hizmet dizini / Kategori',
        'title' => $category->name,
        'description' => $category->description ?: $category->name.' alanındaki işletmeleri, şehirleri ve yorumları tek sayfada inceleyin.',
        'folio' => str_pad((string) $totalInCategory, 2, '0', STR_PAD_LEFT),
    ])
    <div class="kat-wrap">
        <form class="kat-filters" action="{{ url()->current() }}" method="GET">
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit" class="kat-btn">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="kat-cols">
            <div>
                @include('frontend.catalog.company-list', ['listTitle' => $category->name.' firmaları'])
                @if(!empty($seoContent))
                    <section class="kat-panel" style="margin-top:44px">
                        <div class="kat-panel__head"><h2>{{ $category->name }} hakkında</h2><span>Rehber</span></div>
                        <div class="kat-panel__body kat-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="kat-panel">
                        <div class="kat-panel__head"><h2>İlgili yazılar</h2><span>{{ $posts->count() }} yazı</span></div>
                        <div class="kat-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="kat-links__txt">{{ $post->title }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="kat-side">
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Şehirler</h2><span>{{ $totalInCategory }} firma</span></div>
                    <div class="kat-links">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="kat-links__txt">{{ $ct->name }}</span><span class="kat-links__count">{{ $ct->companies_count }}</span></a>@endforeach</div>
                </section>
                <section class="kat-panel">
                    <div class="kat-panel__head"><h2>Diğer kategoriler</h2><span>Dizin</span></div>
                    <div class="kat-links">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span class="kat-links__txt">{{ $related->name }}</span><span class="kat-links__go">→</span></a>@endforeach</div>
                </section>
                <div class="kat-promo">
                    <span class="kat-kicker">Bu alandasınız?</span>
                    <h2>Sayfanızı <em>açın</em></h2>
                    <p>{{ $category->name }} kategorisinde firmanızı görünür kılın.</p>
                    <a class="kat-btn kat-btn--accent" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
