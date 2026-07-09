<x-filament-panels::page>
    <x-filament-panels::form wire:submit="import">
        {{ $this->form }}

        <div class="flex justify-end gap-3 mt-6">
            <x-filament::button
                type="submit"
                icon="heroicon-o-arrow-up-tray"
                color="success"
                size="lg"
            >
                İçe Aktar
            </x-filament::button>
        </div>
    </x-filament-panels::form>

    @if ($this->hasImported)
        <div class="mt-8 rounded-xl border p-6" style="border-color: #d1fae5; background: #f0fdf4;">
            <h3 class="text-lg font-bold text-green-800 mb-3">✅ İçe Aktarma Sonucu</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div class="rounded-lg bg-white p-4 border border-green-200">
                    <div class="text-2xl font-black text-green-600">{{ $this->importedCount }}</div>
                    <div class="text-green-700 font-medium">Başarıyla eklendi</div>
                </div>
                <div class="rounded-lg bg-white p-4 border border-amber-200">
                    <div class="text-2xl font-black text-amber-600">{{ $this->skippedCount }}</div>
                    <div class="text-amber-700 font-medium">Atlandı</div>
                </div>
            </div>

            @if (!empty($this->errors))
                <div class="mt-4">
                    <h4 class="font-bold text-red-700 mb-2">Hatalar:</h4>
                    <ul class="text-sm text-red-600 space-y-1 max-h-48 overflow-y-auto">
                        @foreach ($this->errors as $error)
                            <li>• {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    @endif

    <div class="mt-8 rounded-xl border bg-gray-50 p-6">
        <h3 class="text-lg font-bold text-gray-800 mb-3">📋 CSV Formatı</h3>
        <p class="text-sm text-gray-600 mb-3">CSV dosyanız aşağıdaki başlıkları içermelidir. İlk satır başlık olarak kullanılır.</p>
        <div class="overflow-x-auto">
            <table class="w-full text-sm border-collapse">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border px-3 py-2 text-left font-bold">Başlık</th>
                        <th class="border px-3 py-2 text-left font-bold">Açıklama</th>
                        <th class="border px-3 py-2 text-left font-bold">Zorunlu</th>
                    </tr>
                </thead>
                <tbody>
                    <tr><td class="border px-3 py-2 font-mono">name</td><td class="border px-3 py-2">Firma adı</td><td class="border px-3 py-2 text-green-600 font-bold">Evet</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">phone</td><td class="border px-3 py-2">Telefon numarası</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">email</td><td class="border px-3 py-2">E-posta adresi</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">website</td><td class="border px-3 py-2">Web sitesi URL'si</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">address</td><td class="border px-3 py-2">Fiziksel adres</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">description</td><td class="border px-3 py-2">Firma açıklaması</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">category</td><td class="border px-3 py-2">Kategori adı (eşleşirse)</td><td class="border px-3 py-2">Hayır</td></tr>
                    <tr><td class="border px-3 py-2 font-mono">city</td><td class="border px-3 py-2">Şehir adı (eşleşirse)</td><td class="border px-3 py-2">Hayır</td></tr>
                </tbody>
            </table>
        </div>
        <div class="mt-4 p-3 bg-gray-100 rounded-lg">
            <p class="text-xs font-mono text-gray-700">
                <strong>Örnek CSV:</strong><br>
                name,phone,email,website,address,description,category,city<br>
                Örnek Firma A,0212 555 0101,info@ornek.com,https://ornek.com,İstanbul/Kadıköy,"Açıklama metni",Restoran,İstanbul<br>
                Örnek Firma B,0312 555 0202,,https://ornekb.com,Ankara/Çankaya,,Avukat,Ankara
            </p>
        </div>
    </div>
</x-filament-panels::page>
