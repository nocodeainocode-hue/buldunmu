{{-- MOBİL UYGULAMA · alt sekme çubuğu (5 sekme + merkez CTA) --}}
@php
    $apTabs = [
        ['route' => 'home', 'label' => 'Ana Sayfa', 'icon' => '⌂', 'match' => ['home']],
        ['route' => 'companies.index', 'label' => 'Firmalar', 'icon' => '▦', 'match' => ['companies.index', 'companies.show', 'search', 'cities.show', 'categories.show']],
        ['route' => 'jobs.index', 'label' => 'İş', 'icon' => '◷', 'match' => ['jobs.*']],
        ['route' => 'blog.index', 'label' => 'Rehber', 'icon' => '✎', 'match' => ['blog.*', 'pages.*', 'packages.index']],
    ];
@endphp
<nav class="ap-tabbar" aria-label="Alt menü">
    @foreach($apTabs as $i => $apTab)
        @if($i === 2)
            <a class="ap-tab ap-tab--cta {{ request()->routeIs('listing.create') ? 'is-active' : '' }}" href="{{ route('listing.create') }}">
                <span class="ap-tab__ico" aria-hidden="true">+</span>
                <span>Ekle</span>
            </a>
        @endif
        <a class="ap-tab {{ request()->routeIs($apTab['match']) ? 'is-active' : '' }}" href="{{ route($apTab['route']) }}">
            <span class="ap-tab__ico" aria-hidden="true">{{ $apTab['icon'] }}</span>
            <span>{{ $apTab['label'] }}</span>
        </a>
    @endforeach
</nav>
