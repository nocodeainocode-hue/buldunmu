<div class="ap-search">
    <form action="{{ url()->current() }}" method="GET">
        <input type="search" name="q" value="{{ request('q') }}" placeholder="Firma veya hizmet ara">
        <button class="ap-search__go" type="submit" aria-label="Ara">⌕</button>
    </form>
</div>
@if($categories->isNotEmpty())
    <nav class="ap-chips" aria-label="Kategoriler">
        <a class="ap-chip {{ !request('category') ? 'is-active' : '' }}" href="{{ url()->current() }}">Tümü</a>
        @foreach($categories->take(16) as $cat)
            <a class="ap-chip {{ request('category') == $cat->slug ? 'is-active' : '' }}" href="{{ url()->current() }}?category={{ $cat->slug }}{{ request('q') ? '&q='.request('q') : '' }}">{{ $cat->name }}</a>
        @endforeach
    </nav>
@endif
<form class="ap-filter" action="{{ url()->current() }}" method="GET">
    @if(request('q'))<input type="hidden" name="q" value="{{ request('q') }}">@endif
    <label>Şehir
        <select name="city"><option value="">Tüm şehirler</option>@foreach($cities as $ct)<option value="{{ $ct->slug }}" @selected(request('city') == $ct->slug)>{{ $ct->name }}</option>@endforeach</select>
    </label>
    @if(request('category'))<input type="hidden" name="category" value="{{ request('category') }}">@endif
    <button class="ap-btn ap-btn--block" type="submit">Filtrele</button>
</form>
@include('frontend.mobileapp.company-list', ['listTitle' => request()->routeIs('search') ? 'Arama sonuçları' : 'Firmalar'])
@if($cities->isNotEmpty())
    <div class="ap-sec"><div class="ap-sec__head"><h2>Şehirler</h2></div></div>
    <div class="ap-grid">
        @foreach($cities->take(8) as $ct)
            <a class="ap-tile" href="{{ route('cities.show', $ct->slug) }}"><b>{{ $ct->name }}</b><small>Firmaları görüntüle ›</small></a>
        @endforeach
    </div>
@endif
