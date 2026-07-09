<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex justify-end gap-3 mt-6">
            <x-filament::button
                wire:click="discover"
                icon="heroicon-o-magnifying-glass"
                color="primary"
                size="lg"
            >
                Keşfet
            </x-filament::button>
        </div>

    @if ($this->hasSearched)
        <div class="mt-8">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-lg font-semibold">
                    Keşif Sonuçları
                    <span class="text-sm font-normal text-gray-500 ml-2">
                        ({{ $this->keyword }} — {{ $this->city }})
                    </span>
                </h2>
                <a href="{{ \App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource::getUrl('index') }}"
                   class="text-sm text-primary-600 hover:underline font-medium">
                    Tüm keşfedilenleri görüntüle →
                </a>
            </div>

            {{-- Bulk Approve with Category Wizard --}}
            <div class="mb-6 rounded-xl border border-gray-200 bg-gray-50 p-5 dark:border-gray-700 dark:bg-gray-800">
                <h3 class="text-base font-bold mb-4">⚡ Toplu Onaylama Sihirbazı</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 mb-4">
                    Tüm bekleyen firmaları seçtiğiniz kategori ve şehirle toplu olarak onaylayıp rehbere ekleyin.
                </p>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Kategori *</label>
                        <select wire:model="bulkCategoryId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm py-2">
                            <option value="">-- Kategori Seçin --</option>
                            @foreach(\App\Models\Category::active()->orderBy('name')->get() as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Şehir</label>
                        <select wire:model="bulkCityId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm py-2">
                            <option value="">-- Şehir Seçin --</option>
                            @foreach(\App\Models\City::orderBy('name')->get() as $ct)
                                <option value="{{ $ct->id }}">{{ $ct->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">İlçe</label>
                        <select wire:model="bulkDistrictId" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-primary-500 focus:ring-primary-500 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-200 text-sm py-2">
                            <option value="">-- İlçe Seçin --</option>
                            @if($this->bulkCityId)
                                @foreach(\App\Models\District::where('city_id', $this->bulkCityId)->orderBy('name')->get() as $dist)
                                    <option value="{{ $dist->id }}">{{ $dist->name }}</option>
                                @endforeach
                            @endif
                        </select>
                    </div>
                    <div class="flex items-end gap-2">
                        <x-filament::button
                            wire:click="approveAllWithCategory"
                            icon="heroicon-o-check-badge"
                            color="success"
                            class="w-full"
                        >
                            Kategorili Onayla
                        </x-filament::button>
                    </div>
                </div>

                <div class="mt-3 flex gap-2">
                    <x-filament::button
                        wire:click="approveAll"
                        icon="heroicon-o-check-circle"
                        color="gray"
                        size="sm"
                    >
                        Kategorisiz Toplu Onayla
                    </x-filament::button>
                </div>
            </div>

            {{ $this->table }}
        </div>
    @endif

    @if (!$this->hasSearched)
        <div class="mt-8 rounded-xl border-2 border-dashed border-gray-300 dark:border-gray-600 p-12 text-center">
            <x-filament::icon
                icon="heroicon-o-magnifying-glass"
                class="w-16 h-16 mx-auto text-gray-400 dark:text-gray-500 mb-4"
            />
            <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Firma Keşfine Başlayın
            </h3>
            <p class="mt-2 text-sm text-gray-500 dark:text-gray-400 max-w-md mx-auto">
                Anahtar kelime ve şehir girip "Keşfet" butonuna tıklayarak Firecrawl API ile yeni firmalar keşfedebilirsiniz.
                Keşif işlemi arka planda çalışır, sonuçlar hazır olduğunda bildirim alırsınız.
            </p>
        </div>
    @endif
</x-filament-panels::page>
