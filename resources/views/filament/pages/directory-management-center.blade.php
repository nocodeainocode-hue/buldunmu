<x-filament-panels::page>
    @if ($directoryId)
        <form wire:submit="save" class="space-y-6">
            {{ $this->form }}

            <div class="flex justify-end">
                <x-filament::button type="submit" icon="heroicon-o-check">
                    Tüm Değişiklikleri Kaydet
                </x-filament::button>
            </div>
        </form>
    @else
        <x-filament::section>
            <div class="py-8 text-center">
                <x-filament::icon
                    icon="heroicon-o-globe-alt"
                    class="mx-auto mb-4 h-10 w-10 text-gray-400"
                />
                <h2 class="text-lg font-semibold text-gray-950 dark:text-white">Önce bir rehber seçin</h2>
                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
                    Üst menüdeki rehber seçiciden düzenlemek istediğiniz siteyi seçin.
                </p>
            </div>
        </x-filament::section>
    @endif
</x-filament-panels::page>
