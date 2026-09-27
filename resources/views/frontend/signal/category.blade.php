<div class="sig sig-page">
    @include('partials.signal.band', [
        'crumb' => $category->name,
        'eyebrow' => 'Hizmet hattı / Kategori',
        'title' => $category->name.' hattı',
        'description' => $category->description ?: $category->name.' alanındaki işletmeleri ve hizmetleri tek ekranda görün.',
    ])
    <div class="sig-wrap" style="padding-top:26px">
        <form class="sig-filters" action="{{ url()->current() }}" method="GET">
            <label>Şehir seçin<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit" class="sig-btn">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="sig-cols">
            <div>
                @include('frontend.signal.company-list', ['listTitle' => $category->name.' kayıtları'])
                @if(!empty($seoContent))
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>{{ $category->name }} hakkında</h2><span class="sig-code">Rehber</span></div>
                        <div class="sig-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
                    </section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="sig-panel">
                        <div class="sig-panel__head"><h2>Bu hattan yazılar</h2><span class="sig-code">{{ $posts->count() }} kayıt</span></div>
                        <div class="sig-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="sig-links__txt">{{ $post->title }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                    </section>
                @endif
            </div>
            <aside class="sig-side">
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Şehirler</h2><span class="sig-code">{{ $totalInCategory }} firma</span></div>
                    <div class="sig-links">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="sig-links__txt">{{ $ct->name }}</span><span class="sig-links__count">{{ $ct->companies_count }}</span></a>@endforeach</div>
                </section>
                <section class="sig-panel">
                    <div class="sig-panel__head"><h2>Diğer hatlar</h2><span class="sig-code">Kategori</span></div>
                    <div class="sig-links">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span class="sig-links__txt">{{ $related->name }}</span><span class="sig-links__go">›</span></a>@endforeach</div>
                </section>
                <div class="sig-promo">
                    <span class="sig-kicker">Bu hatta mısınız?</span>
                    <h2 style="margin-top:10px">Sinyale adınızı yazın</h2>
                    <p>{{ $category->name }} kategorisinde firmalarınızı görünür kılın.</p>
                    <a class="sig-btn" href="{{ route('owner.register') }}">Firma ekle →</a>
                </div>
            </aside>
        </div>
    </div>
</div>
