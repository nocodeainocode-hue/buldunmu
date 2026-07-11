@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Yerel Firma Haritası')
@section('meta_description', $settings->meta_description ?? 'Yakındaki firmaları, şehirleri ve kategorileri harita destekli yerel firma rehberinde keşfedin.')

@section('content')
@php
    $featuredCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies->take(6);
@endphp

<div style="background:var(--bg);">
    <section class="overflow-hidden border-b" style="border-color:var(--border);background:linear-gradient(180deg,var(--primary_light),var(--bg));">
        <div class="mx-auto grid gap-8 px-4 py-14 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8 lg:py-20" style="max-width:var(--page_width,1280px);">
            <div class="flex flex-col justify-center">
                <div class="mb-4 inline-flex w-fit rounded-full px-4 py-2 text-xs font-black uppercase tracking-widest" style="background:var(--bg_card);color:var(--primary);">Yerel keşif</div>
                <h1 class="text-4xl font-black tracking-tight sm:text-5xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Yakındaki firmaları haritada bulun' }}</h1>
                <p class="mt-5 text-base leading-8" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Şehir, ilçe ve kategori odaklı firma aramaları için hızlı, sade ve SEO uyumlu rehber deneyimi.' }}</p>
                <form action="{{ route('companies.index') }}" method="GET" class="mt-8 grid gap-3 rounded-3xl border p-3 shadow-xl sm:grid-cols-[1fr_auto]" style="background:var(--bg_card);border-color:var(--border);">
                    <input name="q" placeholder="Örn: diş kliniği, oto tamir, otel..." class="min-h-12 rounded-2xl border px-4 text-sm outline-none" style="background:var(--bg);border-color:var(--border);color:var(--text);">
                    <button class="rounded-2xl px-7 py-3 text-sm font-black text-white" style="background:var(--primary);">Firmaları bul</button>
                </form>
                <div class="mt-6 grid grid-cols-3 gap-3">
                    <div class="rounded-2xl border p-4" style="background:var(--bg_card);border-color:var(--border);">
                        <div class="text-2xl font-black" style="color:var(--primary);">{{ $latestCompanies->count() }}</div>
                        <div class="text-xs font-bold" style="color:var(--text_muted);">Yeni firma</div>
                    </div>
                    <div class="rounded-2xl border p-4" style="background:var(--bg_card);border-color:var(--border);">
                        <div class="text-2xl font-black" style="color:var(--primary);">{{ $cities->count() }}</div>
                        <div class="text-xs font-bold" style="color:var(--text_muted);">Şehir</div>
                    </div>
                    <div class="rounded-2xl border p-4" style="background:var(--bg_card);border-color:var(--border);">
                        <div class="text-2xl font-black" style="color:var(--primary);">{{ $mapCompanies->count() }}</div>
                        <div class="text-xs font-bold" style="color:var(--text_muted);">Harita pini</div>
                    </div>
                </div>
            </div>
            <div>
                @include('partials.maps.company-map', ['companies' => $mapCompanies, 'mapId' => 'local-map-landing', 'height' => '610px'])
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="mb-7 flex items-end justify-between gap-4">
                <div>
                    <div class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Öne çıkanlar</div>
                    <h2 class="mt-2 text-2xl font-black" style="color:var(--text);">Güvenilir yerel firmalar</h2>
                </div>
                <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tümünü gör</a>
            </div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @foreach($featuredCompanies as $company)
                    @include('partials.cards.visual', ['company' => $company, 'premium' => $company->is_premium])
                @endforeach
            </div>
        </div>
    </section>

    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-2 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg);">
                <h2 class="text-2xl font-black" style="color:var(--text);">Şehre göre firma bul</h2>
                <p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">Her rehber sitesi farklı şehir ve kategori kombinasyonlarıyla çalışabilir. Bu layout yerel arama niyetini öne çıkarır.</p>
                <div class="mt-5 grid grid-cols-2 gap-3">
                    @foreach($cities as $city)
                        <a href="{{ route('cities.show', $city->slug) }}" class="rounded-2xl px-4 py-3 text-sm font-black" style="background:var(--bg_card);color:var(--text);">{{ $city->name }} <span style="color:var(--text_muted);">({{ $city->companies_count }})</span></a>
                    @endforeach
                </div>
            </div>
            <div class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg);">
                <h2 class="text-2xl font-black" style="color:var(--text);">Kategoriye göre keşfet</h2>
                <p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">Diş kliniği, otel, oto tamir, restoran gibi aramalarda kullanıcıyı hızlıca doğru listeye taşır.</p>
                <div class="mt-5 flex flex-wrap gap-3">
                    @foreach($categories as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="rounded-full border px-4 py-2 text-sm font-bold" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">{{ $category->name }}</a>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    @include('partials.blog-section')
    @include('partials.cta')
</div>
@endsection
