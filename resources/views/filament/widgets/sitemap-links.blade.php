<x-filament::section>
    <x-slot name="heading">Sitemap Linkleri</x-slot>
    <x-slot name="description">Her rehberin kendi domain'ine özel sitemap adresi. Sitemap dinamik olarak oluşturulur, güncelleme gerekmez.</x-slot>

    <div class="grid gap-2">
        @foreach($links as $link)
        <div class="flex items-center justify-between rounded-lg border p-3" style="border-color: #e5e7eb;">
            <div>
                <span class="text-sm font-semibold">{{ $link['name'] }}</span>
                <span class="ml-2 text-xs text-gray-400">{{ $link['url'] }}</span>
            </div>
            <button
                onclick="navigator.clipboard.writeText('{{ $link['url'] }}')"
                class="rounded-lg px-3 py-1.5 text-xs font-bold bg-primary-50 text-primary-600 hover:bg-primary-100 transition"
            >
                Kopyala
            </button>
        </div>
        @endforeach
    </div>
</x-filament::section>
