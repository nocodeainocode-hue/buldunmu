<x-filament-panels::page>
    @php $rows = $this->getRows(); @endphp

    <div class="flex flex-wrap items-end gap-4">
        <label class="text-sm font-medium">
            <span class="mb-1 block text-gray-600 dark:text-gray-300">Dönem</span>
            <select wire:model.live="days" class="rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-white/5">
                <option value="7">Son 7 gün</option>
                <option value="30">Son 30 gün</option>
                <option value="90">Son 90 gün</option>
                <option value="0">Tümü</option>
            </select>
        </label>
        <label class="text-sm font-medium">
            <span class="mb-1 block text-gray-600 dark:text-gray-300">Kayıt olunan rehber</span>
            <select wire:model.live="directoryId" class="rounded-lg border-gray-300 text-sm dark:border-white/10 dark:bg-white/5">
                <option value="">Tüm rehberler</option>
                @foreach($this->getDirectoryOptions() as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
        </label>
    </div>

    <x-filament::section heading="Reklamdan kayda, kayıttan kampanyaya" description="Yarım kalan: 1. adımı geçip (telefonu alınmış) 2. adımı tamamlamayan başvuru. Kayıt: formu tamamlayan kullanıcı. Sonraki sütunlar kayıtların kaçının ilgili adıma ulaştığını gösterir.">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b text-left text-xs uppercase tracking-wide text-gray-500 dark:border-white/10">
                        <th class="p-2">Kaynak</th>
                        <th class="p-2">Ortam</th>
                        <th class="p-2">Reklam / kampanya</th>
                        <th class="p-2 text-right">Yarım kalan</th>
                        <th class="p-2 text-right">Kayıt</th>
                        <th class="p-2 text-right">Popup gördü</th>
                        <th class="p-2 text-right">Kampanya sayfası</th>
                        <th class="p-2 text-right">WhatsApp tıkladı</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr class="border-b dark:border-white/10">
                            <td class="p-2 font-semibold">{{ $row['source'] }}</td>
                            <td class="p-2">{{ $row['medium'] }}</td>
                            <td class="p-2">{{ $row['campaign'] }}</td>
                            <td class="p-2 text-right text-amber-600">{{ $row['partial'] }}</td>
                            <td class="p-2 text-right font-bold">{{ $row['signups'] }}</td>
                            <td class="p-2 text-right">{{ $row['popup'] }}</td>
                            <td class="p-2 text-right">{{ $row['page_view'] }} <span class="text-xs text-gray-500">(%{{ $row['page_rate'] }})</span></td>
                            <td class="p-2 text-right">{{ $row['whatsapp'] }} <span class="text-xs text-gray-500">(%{{ $row['whatsapp_rate'] }})</span></td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="p-6 text-center text-gray-500">Bu dönemde firma kaydı yok.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section heading="Reklam adreslerini nasıl etiketlersiniz?" collapsed collapsible>
        <p class="text-sm text-gray-600 dark:text-gray-300">Reklamın hedef adresine ekleyin, örnek:</p>
        <pre class="mt-2 overflow-x-auto rounded-lg bg-gray-100 p-3 text-xs dark:bg-white/5">https://ALANADI.com.tr/firma-kayit?utm_source=google&amp;utm_medium=cpc&amp;utm_campaign=firma-kayit-ekim</pre>
        <p class="mt-2 text-xs text-gray-500">Google ve Meta'nın tıklama kimlikleri (gclid, fbclid) de otomatik kaydedilir. Kaynak bilgisi ziyaretçide 30 gün saklanır; ilk kayıt anındaki hâli kullanıcıya yazılır.</p>
    </x-filament::section>
</x-filament-panels::page>
