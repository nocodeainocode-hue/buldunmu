<div class="bd bd-page">
    <div class="bd-wrap">
        @include('partials.board.band', [
            'crumb' => $category->name,
            'tag' => ($category->icon ?? '◆').' Kategori',
            'title' => $category->name,
            'description' => $category->description ?: $category->name.' alanındaki işletmeleri tek panoda incele.',
        ])
        <form class="bd-filters" action="{{ url()->current() }}" method="GET">
            <label>Şehir<select name="city"><option value="">Tüm şehirler</option>@foreach($popularCities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }} ({{ $ct->companies_count }})</option>@endforeach</select></label>
            <button type="submit" class="bd-btn">Bu şehirde göster</button>
            @if(request()->filled('city'))<a href="{{ url()->current() }}">Filtreyi temizle</a>@endif
        </form>
        <div class="bd-cols">
            <div class="bd-stack">
                @include('frontend.board.company-list', ['listTitle' => $category->name.' ilanları'])
                @if(!empty($seoContent))
                    <section class="bd-panel"><h2>{{ $category->name }} hakkında</h2><div class="bd-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div></section>
                @endif
                @if($posts->isNotEmpty())
                    <section class="bd-box"><div class="bd-box__head"><h2>İlgili yazılar</h2></div><div class="bd-links">@foreach($posts as $post)<a href="{{ route('blog.show', $post->slug) }}"><span class="bd-links__txt">{{ $post->title }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                @endif
            </div>
            <aside class="bd-side">
                <section class="bd-box"><div class="bd-box__head"><h2>Şehirler</h2><span>{{ $totalInCategory }} ilan</span></div><div class="bd-links">@foreach($popularCities as $ct)<a href="{{ route('cities.show', $ct->slug) }}"><span class="bd-links__txt">📍 {{ $ct->name }}</span><span class="bd-links__n">{{ $ct->companies_count }}</span></a>@endforeach</div></section>
                <section class="bd-box"><div class="bd-box__head"><h2>Diğer kategoriler</h2></div><div class="bd-links">@foreach($relatedCategories as $related)<a href="{{ route('categories.show', $related->slug) }}"><span class="bd-links__txt">{{ $related->icon ?? '◆' }} {{ $related->name }}</span><span class="bd-links__n">→</span></a>@endforeach</div></section>
                <div class="bd-cta"><h2>{{ $category->name }} mı yapıyorsun?</h2><p>İlanını ver, bu kategoride görün.</p><a class="bd-btn" href="{{ route('owner.register') }}">+ Ücretsiz ekle</a></div>
            </aside>
        </div>
    </div>
</div>
