{{-- Split Hero — Modern tema: solda yazı, sağda istatistik --}}
<section class="py-16 sm:py-24" style="background-color: var(--primary); color: white;">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 items-center">
            <div>
                <h1 class="text-3xl sm:text-5xl font-bold tracking-tight mb-6">{{ $title }}</h1>
                <p class="text-lg opacity-80 mb-8">{{ $subtitle }}</p>
                <form action="{{ route('companies.index') }}" method="GET" class="flex gap-2 max-w-md">
                    <input type="text" name="q" placeholder="Firma ara..." class="flex-1 px-5 py-3 rounded-lg text-gray-900 text-sm">
                    <button type="submit" class="px-6 py-3 bg-white font-semibold rounded-lg text-sm" style="color: var(--primary);">Ara</button>
                </form>
            </div>
            <div class="grid grid-cols-2 gap-4">
                @php $totalCompanies = \App\Models\Company::active()->count(); @endphp
                @php $totalCategories = \App\Models\Category::active()->count(); @endphp
                @php $totalCities = \App\Models\City::count(); @endphp
                <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold">{{ $totalCompanies }}</div>
                    <div class="text-sm opacity-70 mt-1">Firma</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold">{{ $totalCategories }}</div>
                    <div class="text-sm opacity-70 mt-1">Kategori</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-center">
                    <div class="text-3xl font-bold">{{ $totalCities }}</div>
                    <div class="text-sm opacity-70 mt-1">Şehir</div>
                </div>
                <div class="bg-white/10 backdrop-blur rounded-xl p-6 text-center flex items-center justify-center">
                    <a href="{{ route('listing.create') }}" class="text-sm font-semibold underline">+ Firma Ekle</a>
                </div>
            </div>
        </div>
    </div>
</section>
