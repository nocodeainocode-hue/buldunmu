<x-filament-widgets::widget>
    <div style="display: flex; flex-wrap: wrap; align-items: center; gap: 12px;">
        @foreach ($actions as $action)
            <x-filament::button tag="a" :href="$action['url']" :icon="$action['icon']"
                :color="$loop->first ? 'primary' : 'gray'" :title="$action['description']">
                {{ $action['label'] }}
            </x-filament::button>
        @endforeach
        @if ($directory?->domain)
            <x-filament::button tag="a" :href="'https://'.$directory->domain"
                icon="heroicon-o-arrow-top-right-on-square" color="gray"
                target="_blank" rel="noopener noreferrer" :title="$directory->domain">
                Siteyi Aç
            </x-filament::button>
        @endif
    </div>
</x-filament-widgets::widget>
