<x-filament-widgets::widget>
    <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
        @foreach($actions as $action)
            <a href="{{ $action['url'] }}" class="group flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 transition hover:border-primary-300 hover:shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-primary-50 text-primary-600 dark:bg-primary-950 dark:text-primary-400">
                    <x-filament::icon :icon="$action['icon']" class="h-5 w-5" />
                </div>
                <div class="min-w-0"><div class="text-sm font-semibold text-gray-950 dark:text-white">{{ $action['label'] }}</div><div class="truncate text-xs text-gray-500">{{ $action['description'] }}</div></div>
            </a>
        @endforeach
        @if($directory?->domain)
            <a href="https://{{ $directory->domain }}" target="_blank" rel="noopener" class="group flex items-center gap-3 rounded-lg border border-gray-200 bg-white p-4 transition hover:border-primary-300 hover:shadow-sm dark:border-white/10 dark:bg-gray-900">
                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg bg-gray-100 text-gray-600 dark:bg-white/10 dark:text-gray-300"><x-filament::icon icon="heroicon-o-arrow-top-right-on-square" class="h-5 w-5" /></div>
                <div class="min-w-0"><div class="text-sm font-semibold text-gray-950 dark:text-white">Siteyi Aç</div><div class="truncate text-xs text-gray-500">{{ $directory->domain }}</div></div>
            </a>
        @else
            <div class="flex items-center rounded-lg border border-dashed border-gray-300 p-4 text-xs text-gray-500 dark:border-white/15">Siteyi açmak için üstten bir rehber seçin.</div>
        @endif
    </div>
</x-filament-widgets::widget>
