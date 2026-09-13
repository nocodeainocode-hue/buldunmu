@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Kategori Atlası')
@section('meta_description', $settings->meta_description ?? 'Türkiye genelindeki firma kategorilerini, şehirleri ve popüler şehir-kategori rehberlerini keşfedin.')

@section('content')
@php
    $atlasHero = $directory?->hero_image
        ? asset('storage/'.$directory->hero_image)
        : asset('images/themes/category-atlas-hero.png');
@endphp
<main style="background:var(--bg);">
    <section class="relative isolate overflow-hidden py-16 sm:py-20" style="background:var(--text);">
        <img src="{{ $atlasHero }}" alt="{{ $settings->site_name ?? 'Türkiye firma kategorileri' }}" class="absolute inset-0 -z-20 h-full w-full object-cover object-right" width="1920" height="1080" fetchpriority="high">
        <div class="absolute inset-0 -z-10" style="background:linear-gradient(90deg,rgba(7,31,45,.94) 0%,rgba(7,31,45,.82) 42%,rgba(7,31,45,.36) 69%,rgba(7,31,45,.08) 100%);"></div>
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);"><div class="max-w-3xl"><div class="inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-[0.22em]" style="color:#fff;"><span class="h-2 w-2 rounded-full" style="background:var(--accent);"></span> Türkiye firma kategorileri</div><h1 class="mt-5 text-4xl font-black leading-tight sm:text-6xl" style="color:#fff;">{{ $settings->homepage_title ?? 'Aradığınız hizmetin haritasını çıkarın' }}</h1><p class="mt-5 max-w-2xl text-base leading-8" style="color:rgba(255,255,255,.8);">{{ $settings->homepage_subtitle ?? 'Kategoriyi seçin, şehrinizi belirleyin ve doğru işletmeye ulaşın.' }}</p><form action="{{ route('search') }}" method="GET" class="mt-8 flex max-w-2xl flex-col gap-2 rounded-2xl bg-white p-3 shadow-2xl sm:flex-row"><label class="sr-only" for="atlas-search">Hizmet, kategori veya firma ara</label><input id="atlas-search" name="q" class="min-h-14 min-w-0 flex-1 rounded-xl border px-4 text-sm outline-none" placeholder="Hizmet, kategori veya firma ara..." style="border-color:var(--border);color:var(--text);"><button class="min-h-14 rounded-xl px-6 text-sm font-black" style="background:var(--accent);color:#fff;">Atlası ara</button></form></div></div>
    </section>

    <section class="py-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="mb-8"><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Kategori dizini</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Sektör sektör firma keşfi</h2></div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @foreach($categories as $index => $category)
                    <a href="{{ route('categories.show',$category->slug) }}" class="group relative min-h-48 overflow-hidden rounded-lg border p-5 transition hover:-translate-y-1 hover:shadow-xl" style="border-color:var(--border);background:var(--bg_card);">
                        <div class="absolute -right-5 -top-8 text-[100px] font-black opacity-[0.04]" style="color:var(--primary);">{{ str_pad($index+1,2,'0',STR_PAD_LEFT) }}</div><div class="flex h-12 w-12 items-center justify-center rounded-md text-xl font-black text-white" style="background:var(--primary);">{{ $category->icon ?: mb_substr($category->name,0,1) }}</div><h3 class="mt-5 text-xl font-black" style="color:var(--text);">{{ $category->name }}</h3><p class="mt-2 line-clamp-2 text-xs leading-5" style="color:var(--text_muted);">{{ $category->description ?: $category->name.' alanındaki firmaları, hizmetleri ve şehir rehberlerini inceleyin.' }}</p><div class="mt-5 text-xs font-black" style="color:var(--primary);">{{ $category->companies_count }} firma →</div>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y py-12" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);"><div class="mb-8"><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--accent);">Popüler kombinasyonlar</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Şehir ve kategori rehberleri</h2><p class="mt-2 text-sm" style="color:var(--text_muted);">Yerel arama niyetine uygun hazır firma listeleri.</p></div><div class="grid gap-px overflow-hidden rounded-lg border sm:grid-cols-2 lg:grid-cols-3" style="border-color:var(--border);background:var(--border);">@foreach($cities->take(6) as $city) @foreach($categories->take(3) as $category)<a href="{{ route('companies.index',['city'=>$city->slug,'category'=>$category->slug]) }}" class="flex items-center justify-between p-4 text-sm font-bold transition hover:bg-sky-50" style="background:var(--bg_card);color:var(--text);"><span>{{ $city->name }} {{ $category->name }}</span><span style="color:var(--primary);">→</span></a>@endforeach @endforeach</div></div>
    </section>

    <section class="py-12"><div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[0.7fr_1.3fr] lg:px-8" style="max-width:var(--page_width,1280px);"><div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Şehir atlası</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Bölgenizi seçin</h2><p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">Her şehir sayfasında yerel kategoriler, işletmeler ve güncel firma kayıtları bulunur.</p></div><div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">@foreach($cities as $city)<a href="{{ route('cities.show',$city->slug) }}" class="rounded-md border p-4 text-sm font-black" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">{{ $city->name }} <span class="block pt-1 text-xs font-medium" style="color:var(--text_muted);">{{ $city->companies_count }} firma</span></a>@endforeach</div></div></section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
