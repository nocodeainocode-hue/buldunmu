@if($popularCities->isNotEmpty())
    <nav class="ap-chips" aria-label="Şehirler">
        <a class="ap-chip {{ !request('city') ? 'is-active' : '' }}" href="{{ url()->current() }}">Tüm şehirler</a>
        @foreach($popularCities->take(14) as $ct)
            <a class="ap-chip {{ request('city') == $ct->slug ? 'is-active' : '' }}" href="{{ url()->current() }}?city={{ $ct->slug }}">{{ $ct->name }}<span class="ap-chip__n">{{ $ct->companies_count }}</span></a>
        @endforeach
    </nav>
@endif
@include('frontend.mobileapp.company-list', ['listTitle' => $category->name])
@if(!empty($seoContent))
    <div class="ap-sec"><div class="ap-sec__head"><h2>{{ $category->name }} hakkında</h2></div></div>
    <div class="ap-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
@endif
@if($relatedCategories->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>Benzer kategoriler</h2></div></div>
    <nav class="ap-chips">
        @foreach($relatedCategories as $related)
            <a class="ap-chip" href="{{ route('categories.show', $related->slug) }}">{{ $related->name }}</a>
        @endforeach
    </nav>
@endif
@if($posts->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>Bu kategoriden yazılar</h2></div></div>
    <div class="ap-list">
        @foreach($posts as $post)
            <a class="ap-row" href="{{ route('blog.show', $post->slug) }}"><span class="ap-row__main"><h3>{{ $post->title }}</h3></span><span class="ap-row__chev">›</span></a>
        @endforeach
    </div>
@endif
