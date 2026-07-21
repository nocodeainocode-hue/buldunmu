{{-- Verified Hero — Güven odaklı, yeşil gradient --}}
<section class="relative overflow-hidden py-20 sm:py-24" style="background:linear-gradient(135deg,var(--hero_from) 0%,var(--hero_to) 100%);">
    {{-- Dekoratif güven deseni --}}
    <div class="absolute inset-0 opacity-5">
        <div class="absolute left-1/4 top-1/4 h-64 w-64 rounded-full border-[20px] border-white"></div>
        <div class="absolute bottom-1/4 right-1/4 h-48 w-48 rounded-full border-[16px] border-white"></div>
        <div class="absolute left-1/2 top-1/2 h-32 w-32 -translate-x-1/2 -translate-y-1/2 rounded-full border-[12px] border-white"></div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mb-5 inline-flex items-center gap-2 rounded-full px-5 py-2 text-sm font-black uppercase tracking-widest" style="background:rgba(255,255,255,0.15);color:#fff;">
                <svg class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Doğrulanmış Rehber
            </div>
            <h1 class="text-4xl font-black leading-tight sm:text-5xl" style="color:#fff;">{{ $title }}</h1>
            <p class="mt-5 text-lg leading-8" style="color:rgba(255,255,255,0.85);">{{ $subtitle }}</p>

            <form action="{{ route('search') }}" method="GET" class="mx-auto mt-8 flex max-w-xl overflow-hidden rounded-2xl shadow-2xl" style="background:#fff;">
                <input type="text" name="q" placeholder="Firma, kategori veya şehir ara..."
                    class="min-w-0 flex-1 px-5 py-4 text-base outline-none" style="color:#1e293b;">
                <button type="submit" class="px-6 text-sm font-black uppercase tracking-wider text-white transition hover:opacity-90" style="background:var(--primary);">
                    Ara
                </button>
            </form>

            <div class="mt-6 flex flex-wrap items-center justify-center gap-4 text-sm" style="color:rgba(255,255,255,0.8);">
                <span class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Kimlik Doğrulaması
                </span>
                <span class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Telefon Teyidi
                </span>
                <span class="flex items-center gap-1">
                    <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/></svg>
                    Adres Doğrulaması
                </span>
            </div>
        </div>
    </div>
</section>
