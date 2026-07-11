@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Mahalle Radarı')
@section('meta_description', $settings->meta_description ?? 'Yakınınızdaki firmaları haritada görün; şehir, kategori ve konuma göre yerel işletmeleri keşfedin.')

@section('content')
@php
    $radarCompanies = $mapCompanies->isNotEmpty() ? $mapCompanies->take(10) : $latestCompanies;
@endphp

<main style="background:var(--bg);">
    <section class="border-b" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto px-4 py-7 sm:px-6 lg:px-8" style="max-width:var(--page_width,1440px);">
            <div class="flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <div class="mb-2 text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Konuma göre keşfet</div>
                    <h1 class="max-w-3xl text-3xl font-black sm:text-5xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Mahallendeki doğru firmayı haritada bul' }}</h1>
                    <p class="mt-3 max-w-2xl text-sm leading-7 sm:text-base" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Yakın işletmeleri, hizmet noktalarını ve yerel markaları tek ekranda karşılaştırın.' }}</p>
                </div>
                <div class="flex gap-6 border-l pl-6" style="border-color:var(--border);">
                    <div><strong class="block text-2xl" style="color:var(--text);">{{ $mapCompanies->count() }}</strong><span class="text-xs" style="color:var(--text_muted);">Harita pini</span></div>
                    <div><strong class="block text-2xl" style="color:var(--text);">{{ $cities->count() }}</strong><span class="text-xs" style="color:var(--text_muted);">Şehir</span></div>
                    <div><strong class="block text-2xl" style="color:var(--text);">{{ $categories->count() }}</strong><span class="text-xs" style="color:var(--text_muted);">Kategori</span></div>
                </div>
            </div>
        </div>
    </section>

    <section class="mx-auto grid min-h-[680px] lg:grid-cols-[390px_1fr]" style="max-width:var(--page_width,1440px);">
        <aside class="order-2 border-r p-4 sm:p-6 lg:order-1" style="border-color:var(--border);background:var(--bg_card);">
            <form action="{{ route('search') }}" method="GET" class="mb-6">
                <label for="radar-search" class="mb-2 block text-xs font-black uppercase tracking-wider" style="color:var(--text_muted);">Firma veya hizmet ara</label>
                <div class="flex overflow-hidden rounded-lg border" style="border-color:var(--border);">
                    <input id="radar-search" name="q" class="min-w-0 flex-1 border-0 px-4 py-3 text-sm outline-none" placeholder="Diş kliniği, restoran, oto servis..." style="background:var(--bg);color:var(--text);">
                    <button class="px-5 text-sm font-black text-white" style="background:var(--primary);">Ara</button>
                </div>
            </form>

            <div class="mb-6">
                <div class="mb-3 flex items-center justify-between"><h2 class="font-black" style="color:var(--text);">Hızlı kategoriler</h2><a href="{{ route('companies.index') }}" class="text-xs font-bold" style="color:var(--primary);">Tümü</a></div>
                <div class="flex flex-wrap gap-2">
                    @foreach($categories->take(8) as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="rounded-full border px-3 py-2 text-xs font-bold" style="border-color:var(--border);background:var(--primary_light);color:var(--primary);">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>

            <div class="mb-4 flex items-end justify-between">
                <div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--primary);">Radar sonuçları</div><h2 class="mt-1 text-xl font-black" style="color:var(--text);">Yakındaki firmalar</h2></div>
                <a href="{{ route('companies.index') }}" class="text-xs font-bold" style="color:var(--primary);">Liste</a>
            </div>
            <div class="space-y-2 lg:max-h-[415px] lg:overflow-y-auto lg:pr-2">
                @forelse($radarCompanies as $company)
                    <a href="{{ route('companies.show', $company->slug) }}" class="group flex gap-3 rounded-lg border p-3 transition hover:shadow-md" style="border-color:var(--border);background:var(--bg_card);">
                        <div class="flex h-11 w-11 shrink-0 items-center justify-center overflow-hidden rounded-md font-black text-white" style="background:var(--primary);">
                            @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full bg-white object-contain p-1">@else{{ mb_substr($company->name, 0, 1) }}@endif
                        </div>
                        <div class="min-w-0"><h3 class="truncate text-sm font-black" style="color:var(--text);">{{ $company->name }}</h3><p class="mt-1 truncate text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}{{ $company->district ? ' / '.$company->district->name : '' }}</p></div>
                    </a>
                @empty
                    <div class="rounded-lg border p-5 text-sm" style="border-color:var(--border);color:var(--text_muted);">Henüz gösterilecek firma bulunmuyor.</div>
                @endforelse
            </div>
        </aside>

        <div class="order-1 p-4 sm:p-6 lg:order-2 lg:p-8">
            <div class="mb-4 flex flex-wrap items-center justify-between gap-3">
                <div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--primary);">Canlı rehber görünümü</div><h2 class="mt-1 text-xl font-black" style="color:var(--text);">Firmaların haritadaki yerleri</h2></div>
                <div class="flex flex-wrap gap-2">@foreach($cities->take(5) as $city)<a href="{{ route('cities.show', $city->slug) }}" class="rounded-md border px-3 py-2 text-xs font-bold" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">{{ $city->name }}</a>@endforeach</div>
            </div>
            @include('partials.maps.company-map', ['companies' => $mapCompanies, 'mapId' => 'neighborhood-radar-map', 'height' => '590px'])
        </div>
    </section>

    <section class="border-y py-12" style="border-color:var(--border);background:var(--primary);">
        <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_2fr] lg:px-8" style="max-width:var(--page_width,1440px);">
            <div><div class="text-xs font-black uppercase tracking-[0.2em] text-white/70">Bölge seç</div><h2 class="mt-2 text-3xl font-black text-white">Şehir şehir yerel işletmeler</h2><p class="mt-3 text-sm leading-6 text-white/75">Konumunu seç, o bölgedeki firmaları ve popüler hizmetleri doğrudan incele.</p></div>
            <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">@foreach($cities as $city)<a href="{{ route('cities.show', $city->slug) }}" class="rounded-md bg-white/10 px-4 py-3 text-sm font-bold text-white transition hover:bg-white/20">{{ $city->name }} <span class="text-white/60">{{ $city->companies_count }}</span></a>@endforeach</div>
        </div>
    </section>

    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
