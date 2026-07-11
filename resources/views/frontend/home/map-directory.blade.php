@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Haritalı Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Türkiye şehirlerinde firmaları harita üzerinde bulun, kategori ve şehir filtreleriyle hızlıca işletme keşfedin.')

@section('content')
@php
    $mapListCompanies = $mapCompanies->isNotEmpty() ? $mapCompanies->take(8) : $latestCompanies;
@endphp

<div style="background:var(--bg);">
    <section class="relative overflow-hidden py-16 sm:py-20" style="background:linear-gradient(135deg,var(--hero_gradient_from),var(--hero_gradient_to));">
        <div class="absolute inset-0 opacity-20" style="background-image:radial-gradient(circle at 1px 1px, white 1px, transparent 0);background-size:24px 24px;"></div>
        <div class="relative mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[1fr_1.05fr] lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="flex flex-col justify-center">
                <div class="mb-4 inline-flex w-fit rounded-full bg-white/15 px-4 py-2 text-xs font-black uppercase tracking-widest text-white">Harita odaklı rehber</div>
                <h1 class="text-4xl font-black tracking-tight text-white sm:text-5xl">{{ $settings->homepage_title ?? "Türkiye'nin şehirlerinde firma bulun" }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-white/85">{{ $settings->homepage_subtitle ?? 'Firma, kategori veya şehir arayın; konumu belli işletmeleri harita üzerinde inceleyin.' }}</p>
                <form action="{{ route('search') }}" method="GET" class="mt-8 flex flex-col gap-3 rounded-2xl bg-white p-2 shadow-2xl sm:flex-row">
                    <input type="text" name="q" placeholder="Firma adı, kategori veya şehir ara..." class="min-h-12 flex-1 rounded-xl border-0 px-4 text-sm outline-none" style="background:var(--bg);color:var(--text);">
                    <button class="rounded-xl px-7 py-3 text-sm font-black text-white" style="background:var(--primary);">Ara</button>
                </form>
                <div class="mt-6 flex flex-wrap gap-4 text-sm font-semibold text-white/85">
                    <span>{{ $latestCompanies->count() }} yeni firma</span>
                    <span>{{ $mapCompanies->count() }} haritalı kayıt</span>
                    <span>{{ $categories->count() }} popüler kategori</span>
                </div>
            </div>
            <div class="lg:pl-4">
                @include('partials.maps.company-map', ['companies' => $mapCompanies, 'mapId' => 'home-map-directory', 'height' => '520px'])
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[0.95fr_1.05fr] lg:px-8" style="max-width:var(--page_width,1280px);">
            <div>
                <div class="mb-6 flex items-end justify-between gap-4">
                    <div>
                        <div class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Haritadaki firmalar</div>
                        <h2 class="mt-2 text-2xl font-black" style="color:var(--text);">Konumu belli işletmeler</h2>
                    </div>
                    <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tüm firmalar</a>
                </div>
                <div class="space-y-4">
                    @foreach($mapListCompanies as $company)
                        <a href="{{ route('companies.show', $company->slug) }}" class="block rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-lg" style="background:var(--bg_card);border-color:var(--border);">
                            <div class="flex items-start gap-4">
                                <div class="flex h-12 w-12 shrink-0 items-center justify-center rounded-2xl text-lg font-black text-white" style="background:var(--primary);">{{ mb_substr($company->name, 0, 1) }}</div>
                                <div class="min-w-0 flex-1">
                                    <div class="flex flex-wrap items-center gap-2">
                                        <h3 class="truncate font-black" style="color:var(--text);">{{ $company->name }}</h3>
                                        @if($company->is_premium)
                                            <span class="rounded-full px-2 py-0.5 text-[10px] font-black text-white" style="background:var(--accent);">Premium</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-sm" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p>
                                    <p class="mt-2 line-clamp-2 text-sm" style="color:var(--text_muted);">{{ $company->short_description }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-6 lg:sticky lg:top-24 lg:self-start">
                <div class="rounded-3xl border p-6" style="background:var(--bg_card);border-color:var(--border);">
                    <h3 class="text-lg font-black" style="color:var(--text);">Şehre göre keşfet</h3>
                    <div class="mt-4 grid grid-cols-2 gap-3">
                        @foreach($cities->take(10) as $city)
                            <a href="{{ route('cities.show', $city->slug) }}" class="rounded-xl border px-4 py-3 text-sm font-bold transition hover:shadow" style="border-color:var(--border);color:var(--text);background:var(--bg);">
                                {{ $city->name }} <span style="color:var(--text_muted);">({{ $city->companies_count }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
                <div class="rounded-3xl border p-6" style="background:var(--bg_card);border-color:var(--border);">
                    <h3 class="text-lg font-black" style="color:var(--text);">Popüler kategoriler</h3>
                    <div class="mt-4 flex flex-wrap gap-2">
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" class="rounded-full px-4 py-2 text-sm font-bold" style="background:var(--primary_light);color:var(--primary);">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </div>
            </aside>
        </div>
    </section>

    @include('partials.blog-section')
    @include('partials.cta')
</div>
@endsection
