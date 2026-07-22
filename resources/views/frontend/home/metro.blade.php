@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Metro Rehber')
@section('meta_description', $settings->meta_description ?? 'Şehir şehir firmaları keşfedin; kategori ve konuma göre yerel işletmeleri bulun.')
@section('content')

{{-- ═══ METRO: Koyu arka plan, neon vurgular, şehir blokları önde ═══ --}}

{{-- Hero --}}
@include('partials.heroes.metro', [
    'title' => $settings->homepage_title ?? 'Şehirde Ne Varsa Burada',
    'subtitle' => $settings->homepage_subtitle ?? 'Yerel işletmeleri, hizmetleri ve markaları şehir şehir keşfedin.'
])

{{-- Şehir Grid (Metro haritası estetiği) --}}
@if($cities->isNotEmpty())
<section aria-labelledby="metro-cities-heading" class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-[0.25em]" style="color:var(--accent);">Şehirler</div>
                <h2 id="metro-cities-heading" class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Hangi şehirde arıyorsun?</h2>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--secondary);">Tümünü listele →</a>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($cities as $city)
            <a href="{{ route('cities.show', $city->slug) }}" class="group relative overflow-hidden rounded-xl p-5 transition hover:-translate-y-1" style="background:var(--bg_card);border:1px solid var(--border);box-shadow:var(--card_shadow);">
                {{-- Metro çizgisi dekorasyonu --}}
                <div class="absolute left-0 top-0 h-full w-1" style="background:var(--secondary);"></div>
                <div class="absolute right-3 top-3 flex h-8 w-8 items-center justify-center rounded-full text-xs font-black" style="background:var(--accent);color:#0f172a;">{{ $city->companies_count }}</div>
                <div class="mt-6">
                    <div class="text-lg font-black" style="color:var(--text);">{{ $city->name }}</div>
                    <div class="mt-1 text-xs font-medium" style="color:var(--text_muted);">{{ $city->companies_count }} firma kayıtlı</div>
                </div>
                <div class="mt-4 flex items-center gap-1 text-xs font-bold" style="color:var(--secondary);">
                    <span>Keşfet</span>
                    <svg class="h-3 w-3 transition group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                </div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Kategoriler (Pill tarzı, yoğun) --}}
@if($categories->isNotEmpty())
<section class="py-10" style="background:var(--bg_card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-6 flex items-end justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-[0.25em]" style="color:var(--accent);">Kategoriler</div>
                <h2 class="text-xl font-black sm:text-2xl" style="color:var(--text);">Ne arıyorsun?</h2>
            </div>
        </div>
        <div class="flex flex-wrap gap-2">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="group flex items-center gap-2 rounded-full border px-4 py-2.5 text-sm font-bold transition hover:shadow-lg" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                <span class="text-lg">{{ $cat->icon ?? '◆' }}</span>
                <span>{{ $cat->name }}</span>
                <span class="text-xs font-medium" style="color:var(--text_muted);">({{ $cat->companies_count }})</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Premium Firmalar (Yatay liste, metro kartları) --}}
@if($premiumCompanies->isNotEmpty())
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-[0.25em]" style="color:var(--accent);">Öne Çıkanlar</div>
                <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Premium İşletmeler</h2>
            </div>
            <span class="rounded-full px-4 py-1.5 text-xs font-black uppercase tracking-wider" style="background:var(--secondary);color:#0f172a;">Sponsorlu</span>
        </div>

        <div class="space-y-3">
            @foreach($premiumCompanies->take(6) as $c)
                @include('partials.cards.metro', ['company' => $c, 'premium' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Son Eklenenler --}}
<section class="py-12" style="background:var(--bg_card);border-top:1px solid var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.25em]" style="color:var(--accent);">Yeni Kayıtlar</div>
            <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Son Eklenen Firmalar</h2>
        </div>

        <div class="space-y-3">
            @foreach($latestCompanies->take(8) as $c)
                @include('partials.cards.metro', ['company' => $c])
            @endforeach
        </div>

        <div class="mt-8 text-center">
            <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-black transition hover:opacity-90" style="background:var(--secondary);color:#0f172a;">
                Tüm Firmaları Gör
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@include('partials.blog-section')
@include('partials.cta')

@endsection
