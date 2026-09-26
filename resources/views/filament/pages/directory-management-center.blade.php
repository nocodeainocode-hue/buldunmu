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

        <x-filament::section heading="Firma vitrini ve iş ilanları" description="Seçili rehberin firmaları, ürünleri, hizmetleri ve ilanları buradan yönetilir.">
            <div class="flex flex-wrap gap-3">
                <x-filament::button tag="a" :href="\App\Filament\Resources\Companies\CompanyResource::getUrl('index')" color="gray">Firmalar ve premium durumu</x-filament::button>
                <x-filament::button tag="a" :href="\App\Filament\Resources\CompanyOfferings\CompanyOfferingResource::getUrl('index')">Ürün ve hizmetler</x-filament::button>
                <x-filament::button tag="a" :href="\App\Filament\Resources\JobPostings\JobPostingResource::getUrl('index')">İş ilanları</x-filament::button>
            </div>
        </x-filament::section>
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
