{{-- Metro Hero — Koyu arka plan, neon vurgular --}}
<section class="relative overflow-hidden py-20 sm:py-28" style="background:linear-gradient(135deg,var(--hero_from) 0%,var(--hero_to) 100%);">
    {{-- Dekoratif metro çizgileri --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute left-[10%] top-0 h-full w-px bg-white"></div>
        <div class="absolute left-[30%] top-0 h-full w-px bg-white"></div>
        <div class="absolute left-[50%] top-0 h-full w-px bg-white"></div>
        <div class="absolute left-[70%] top-0 h-full w-px bg-white"></div>
        <div class="absolute left-[90%] top-0 h-full w-px bg-white"></div>
        <div class="absolute left-0 top-[20%] h-px w-full bg-white"></div>
        <div class="absolute left-0 top-[40%] h-px w-full bg-white"></div>
        <div class="absolute left-0 top-[60%] h-px w-full bg-white"></div>
        <div class="absolute left-0 top-[80%] h-px w-full bg-white"></div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="max-w-3xl">
            <div class="mb-4 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-black uppercase tracking-widest" style="background:var(--accent);color:#0f172a;">
                <span class="h-2 w-2 rounded-full bg-current"></span>
                Canlı Rehber
            </div>
            <h1 class="text-4xl font-black leading-tight sm:text-5xl lg:text-6xl" style="color:#f1f5f9;">{{ $title }}</h1>
            <p class="mt-5 text-lg leading-8" style="color:#94a3b8;">{{ $subtitle }}</p>

            <form action="{{ route('search') }}" method="GET" class="mt-8 flex max-w-xl overflow-hidden rounded-2xl border-2 shadow-2xl" style="border-color:var(--secondary);background:var(--bg_card);">
                <label for="metro-search" class="sr-only">Firma, kategori veya şehir ara</label>
                <input id="metro-search" type="search" name="q" autocomplete="off" placeholder="Firma, kategori veya şehir ara..."
                    class="min-w-0 flex-1 px-5 py-4 text-base outline-none" style="background:transparent;color:#f1f5f9;">
                <button type="submit" class="px-6 text-sm font-black uppercase tracking-wider transition hover:opacity-90" style="background:var(--secondary);color:#0f172a;">
                    Ara
                </button>
            </form>

            <div class="mt-6 flex flex-wrap gap-4 text-sm" style="color:#64748b;">
                <span>🔍 Popüler:</span>
                @php $popularCats = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(4)->get(); @endphp
                @foreach($popularCats as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="font-bold transition hover:opacity-80" style="color:var(--accent);">{{ $cat->name }}</a>
                @endforeach
            </div>
        </div>
    </div>
</section>
