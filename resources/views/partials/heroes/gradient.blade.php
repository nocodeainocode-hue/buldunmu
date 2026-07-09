@php
    $heroCompanies = collect($premiumCompanies ?? [])->merge($latestCompanies ?? [])->unique('id')->take(4);
    $heroCategories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(4)->get();
    $totalCompanies = \App\Models\Company::active()->count();
    $totalCategories = \App\Models\Category::active()->count();
    $totalCities = \App\Models\City::count();
@endphp

<section class="relative overflow-hidden py-14 sm:py-20" style="background:var(--bg_card);">
    <div class="absolute inset-0 opacity-10" style="background:var(--primary_light);"></div>
    <div class="absolute inset-y-0 right-0 hidden w-1/2 lg:block" style="background:var(--primary);clip-path:polygon(18% 0,100% 0,100% 100%,0 100%);opacity:0.08;"></div>

    <div class="relative mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="grid items-center gap-10 lg:grid-cols-[1.05fr_0.95fr]">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border bg-white px-3 py-1 text-xs font-bold uppercase tracking-wide shadow-sm" style="border-color:var(--border);color:var(--primary);">
                    <span class="h-2 w-2 rounded-full" style="background:var(--accent);"></span>
                    Yerel firma rehberi
                </div>
                <h1 class="max-w-3xl text-4xl font-black leading-tight tracking-tight sm:text-6xl" style="color:var(--text);font-family:var(--font_heading);">{{ $title }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 sm:text-lg" style="color:var(--text_muted);">{{ $subtitle }}</p>

                <form action="{{ route('companies.index') }}" method="GET" class="mt-8 max-w-2xl">
                    <div class="flex flex-col gap-3 rounded-2xl border bg-white p-2 shadow-xl sm:flex-row" style="border-color:var(--border);">
                        <input type="text" name="q" placeholder="Firma, kategori veya şehir ara..." class="min-h-12 flex-1 rounded-xl border-0 px-4 text-sm outline-none" style="color:var(--text);background:var(--bg_card);">
                        <button type="submit" class="rounded-xl px-7 py-3 text-sm font-black text-white shadow-lg transition hover:opacity-90" style="background:var(--primary);">Ara</button>
                    </div>
                </form>

                @if($heroCategories->isNotEmpty())
                    <div class="mt-5 flex flex-wrap gap-2">
                        @foreach($heroCategories as $cat)
                            <a href="{{ route('categories.show', $cat->slug) }}" class="rounded-full border bg-white px-3 py-1.5 text-xs font-semibold shadow-sm transition hover:-translate-y-0.5" style="border-color:var(--border);color:var(--text);">
                                {{ $cat->name }} <span style="color:var(--text_muted);">({{ $cat->companies_count }})</span>
                            </a>
                        @endforeach
                    </div>
                @endif

                <div class="mt-8 grid max-w-xl grid-cols-3 gap-4">
                    <div>
                        <div class="text-2xl font-black" style="color:var(--text);">{{ $totalCompanies }}+</div>
                        <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text_muted);">Firma</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black" style="color:var(--text);">{{ $totalCategories }}+</div>
                        <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text_muted);">Kategori</div>
                    </div>
                    <div>
                        <div class="text-2xl font-black" style="color:var(--text);">{{ $totalCities }}+</div>
                        <div class="text-xs font-semibold uppercase tracking-wide" style="color:var(--text_muted);">Şehir</div>
                    </div>
                </div>
            </div>

            <div class="relative">
                @if($heroCompanies->isNotEmpty())
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($heroCompanies as $company)
                            <a href="{{ route('companies.show', $company->slug) }}" class="group min-h-40 overflow-hidden rounded-2xl border bg-white p-4 shadow-xl transition hover:-translate-y-1" style="border-color:rgba(255,255,255,.45);">
                                <div class="mb-4 flex h-20 items-center justify-center rounded-xl" style="background:var(--primary_light);">
                                    @if($company->logo)
                                        <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="max-h-14 max-w-full object-contain">
                                    @else
                                        <span class="flex h-12 w-12 items-center justify-center rounded-xl text-xl font-black text-white" style="background:var(--primary);">{{ mb_substr($company->name, 0, 1) }}</span>
                                    @endif
                                </div>
                                <div class="truncate text-sm font-black" style="color:var(--text);">{{ $company->name }}</div>
                                <div class="mt-1 flex items-center justify-between gap-2 text-xs" style="color:var(--text_muted);">
                                    <span class="truncate">{{ $company->city->name ?? 'Türkiye' }}</span>
                                    <span style="color:var(--accent);">4.9</span>
                                </div>
                            </a>
                        @endforeach
                    </div>
                @else
                    <div class="rounded-3xl bg-white p-8 text-center shadow-xl">
                        <div class="mx-auto mb-4 flex h-20 w-20 items-center justify-center rounded-2xl text-3xl font-black text-white" style="background:var(--primary);">F</div>
                        <h3 class="text-xl font-black" style="color:var(--text);">Firma vitrinin hazır</h3>
                        <p class="mt-2 text-sm" style="color:var(--text_muted);">İlk premium firmalar burada öne çıkar.</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>
