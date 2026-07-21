{{-- Editorial Hero — Serif, dergi kapağı hissi --}}
<section class="relative overflow-hidden py-24 sm:py-32" style="background:linear-gradient(160deg,var(--hero_from) 0%,var(--hero_to) 100%);">
    {{-- Dekoratif çizgiler --}}
    <div class="absolute inset-0 opacity-10">
        <div class="absolute left-[15%] top-0 h-full w-px bg-white"></div>
        <div class="absolute right-[15%] top-0 h-full w-px bg-white"></div>
    </div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mx-auto max-w-3xl text-center">
            <div class="mb-6 text-xs font-black uppercase tracking-[0.4em]" style="color:var(--accent);">
                Est. {{ date('Y') }} — Şehir Rehberi
            </div>
            <h1 class="text-4xl font-black italic leading-tight sm:text-5xl lg:text-6xl" style="color:#fff;font-family:var(--font_heading);">
                {{ $title }}
            </h1>
            <div class="mx-auto mt-6 flex items-center justify-center gap-4">
                <span class="h-px w-16" style="background:var(--accent);"></span>
                <span class="text-2xl" style="color:var(--accent);">❦</span>
                <span class="h-px w-16" style="background:var(--accent);"></span>
            </div>
            <p class="mx-auto mt-6 max-w-xl text-lg italic leading-8" style="color:rgba(255,255,255,0.85);font-family:var(--font_heading);">
                {{ $subtitle }}
            </p>

            <form action="{{ route('search') }}" method="GET" class="mx-auto mt-10 max-w-xl">
                <div class="flex overflow-hidden rounded-none border-b-2" style="border-color:var(--accent);">
                    <input type="text" name="q" placeholder="Bir işletme, semt veya lezzet arayın..."
                        class="min-w-0 flex-1 bg-transparent px-2 py-4 text-center text-base italic outline-none placeholder:text-white/50" style="color:#fff;font-family:var(--font_heading);">
                    <button type="submit" class="px-6 text-sm font-black uppercase tracking-widest transition hover:opacity-80" style="color:var(--accent);">
                        Ara
                    </button>
                </div>
            </form>
        </div>
    </div>
</section>
