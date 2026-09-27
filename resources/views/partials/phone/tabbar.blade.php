{{-- CEP KABUĞU · alt sekme çubuğu --}}
@php
    $phTabs = [
        ['route' => 'home', 'label' => 'Ana Sayfa', 'icon' => '🏠', 'match' => ['home']],
        ['route' => 'companies.index', 'label' => 'Firmalar', 'icon' => '🏢', 'match' => ['companies.index', 'search', 'cities.show', 'categories.show']],
        ['route' => 'jobs.index', 'label' => 'İlanlar', 'icon' => '💼', 'match' => ['jobs.*']],
        ['route' => 'blog.index', 'label' => 'Rehber', 'icon' => '📖', 'match' => ['blog.*', 'pages.*', 'packages.index']],
    ];
@endphp
<nav class="ph-tabbar" aria-label="Alt menü">
    @foreach($phTabs as $phTab)
        <a class="ph-tab {{ in_array(request()->route()?->getName(), $phTab['match'], true) || request()->routeIs($phTab['match']) ? 'is-active' : '' }}"
           href="{{ route($phTab['route']) }}">
            <span class="ph-tab__ico" aria-hidden="true">{{ $phTab['icon'] }}</span>
            <span>{{ $phTab['label'] }}</span>
        </a>
    @endforeach
    <a class="ph-tab ph-tab--cta {{ request()->routeIs('listing.create') ? 'is-active' : '' }}" href="{{ route('listing.create') }}">
        <span class="ph-tab__ico" aria-hidden="true">+</span>
        <span>Firma Ekle</span>
    </a>
</nav>
