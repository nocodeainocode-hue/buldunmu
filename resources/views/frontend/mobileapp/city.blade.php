@if($districts->isNotEmpty())
    <nav class="ap-chips" aria-label="İlçeler">
        <a class="ap-chip {{ !request('district') ? 'is-active' : '' }}" href="{{ url()->current() }}">Tüm ilçeler</a>
        @foreach($districts as $district)
            <a class="ap-chip {{ request('district') == $district->slug ? 'is-active' : '' }}" href="{{ url()->current() }}?district={{ $district->slug }}">{{ $district->name }}</a>
        @endforeach
    </nav>
@endif
@if($popularCategories->isNotEmpty())
    <nav class="ap-chips" aria-label="Kategoriler">
        @foreach($popularCategories->take(12) as $cat)
            <a class="ap-chip" href="{{ route('categories.show', $cat->slug) }}">{{ $cat->name }}@if($cat->companies_count)<span class="ap-chip__n">{{ $cat->companies_count }}</span>@endif</a>
        @endforeach
    </nav>
@endif
@include('frontend.mobileapp.company-list', ['listTitle' => $city->name.' firmaları'])
@if(($nearbyCities ?? collect())->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>Yakın şehirler</h2></div></div>
    <div class="ap-list">
        @foreach($nearbyCities as $nearby)
            <a class="ap-row" href="{{ route('cities.show', $nearby->slug) }}"><span class="ap-row__main"><h3>{{ $nearby->name }}</h3></span><span class="ap-row__chev">›</span></a>
        @endforeach
    </div>
@endif
@if(!empty($seoContent))
    <div class="ap-sec"><div class="ap-sec__head"><h2>{{ $city->name }} hakkında</h2></div></div>
    <div class="ap-prose">@foreach(explode("\n\n", $seoContent) as $paragraph)<p>{{ $paragraph }}</p>@endforeach</div>
@endif
@if($posts->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>Şehirden yazılar</h2></div></div>
    <div class="ap-list">
        @foreach($posts as $post)
            <a class="ap-row" href="{{ route('blog.show', $post->slug) }}"><span class="ap-row__main"><h3>{{ $post->title }}</h3></span><span class="ap-row__chev">›</span></a>
        @endforeach
    </div>
@endif
