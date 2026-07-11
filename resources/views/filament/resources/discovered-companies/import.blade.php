<x-filament-panels::page>
    {{ $this->form }}

    <div class="flex flex-wrap justify-end gap-3">
        <x-filament::button wire:click="preview" color="gray" icon="heroicon-o-eye">Ön İzleme</x-filament::button>
        <x-filament::button wire:click="startImport" color="success" icon="heroicon-o-play">İçe Aktarmayı Başlat</x-filament::button>
    </div>

    @if($previewRows)
        <x-filament::section heading="İlk 15 Satır Ön İzleme" description="Başlatmadan önce hedef rehber ve eşleşmeleri kontrol edin.">
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead><tr class="border-b text-left"><th class="p-2">Satır</th><th class="p-2">Firma</th><th class="p-2">Kategori</th><th class="p-2">Şehir</th><th class="p-2">Hedefler</th><th class="p-2">Durum</th></tr></thead>
                    <tbody>
                    @foreach($previewRows as $row)
                        <tr class="border-b"><td class="p-2">{{ $row['row'] }}</td><td class="p-2 font-semibold">{{ $row['name'] }}</td><td class="p-2">{{ $row['category'] }}</td><td class="p-2">{{ $row['city'] }}</td><td class="p-2">{{ $row['directories'] }}</td><td class="p-2 {{ $row['valid'] ? 'text-green-600' : 'text-red-600' }}">{{ $row['status'] }}</td></tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </x-filament::section>
    @endif

    <x-filament::section heading="Yükleme Geçmişi" description="Kuyruk çalışırken sayfayı yenileyerek durumu takip edebilirsiniz.">
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead><tr class="border-b text-left"><th class="p-2">Batch</th><th class="p-2">Dosya</th><th class="p-2">Durum</th><th class="p-2">Sonuç</th><th class="p-2">Tarih</th><th class="p-2"></th></tr></thead>
                <tbody>
                @forelse($batches as $batch)
                    @php($stats = $batch->stats ?? [])
                    <tr class="border-b"><td class="p-2 font-mono">#{{ $batch->id }}</td><td class="p-2">{{ $batch->filename }}</td><td class="p-2 font-semibold">{{ $batch->status }}</td><td class="p-2">Eklenen: {{ $stats['created'] ?? 0 }} · Güncellenen: {{ $stats['updated'] ?? 0 }} · Atlanan: {{ $stats['skipped'] ?? 0 }} · Hata: {{ $stats['failed'] ?? 0 }}</td><td class="p-2">{{ $batch->created_at->format('d.m.Y H:i') }}</td><td class="p-2 text-right"><div class="flex justify-end gap-2">@if($batch->errors)<x-filament::button size="xs" color="gray" wire:click="downloadErrors({{ $batch->id }})">Hata CSV</x-filament::button>@endif @if($batch->status === 'completed')<x-filament::button size="xs" color="danger" wire:click="rollbackBatch({{ $batch->id }})" wire:confirm="Bu batch tarafından oluşturulan firmalar silinecek, güncellenen kayıtlar eski haline dönecek. Devam edilsin mi?">Geri Al</x-filament::button>@endif</div></td></tr>
                    @if($batch->errors)<tr><td colspan="6" class="px-2 pb-3 text-xs text-red-600">{{ implode(' | ', array_slice($batch->errors, 0, 5)) }}</td></tr>@endif
                @empty
                    <tr><td colspan="6" class="p-6 text-center text-gray-500">Henüz toplu yükleme yapılmadı.</td></tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </x-filament::section>

    <x-filament::section heading="Dosya Sütunları" collapsed collapsible>
        <p class="text-sm text-gray-600">external_id, directory, name, category, city, district, phone, whatsapp, email, website, address, google_maps, opening_hours, short_description, description, logo_url, status</p>
        <p class="mt-2 text-xs text-gray-500">directory alanında virgülle birden fazla domain kullanılabilir. Boşsa üstte seçilen rehberler kullanılır. ALL değeri seçilen tüm rehberlere dağıtır.</p>
    </x-filament::section>
</x-filament-panels::page>
