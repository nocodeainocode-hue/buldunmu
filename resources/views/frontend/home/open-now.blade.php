@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Gece Açık Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şu an açık restoranları, eczaneleri, çekicileri ve acil hizmet veren yerel firmaları bulun.')

@section('content')
@php
    $displayCompanies = $openCompanies->isNotEmpty() ? $openCompanies : $latestCompanies->take(8);
    $openNowHero = $directory?->hero_image
        ? asset('storage/'.$directory->hero_image)
        : asset('images/themes/open-now-hero.png');
@endphp

<main style="background:var(--bg);">
    <section class="relative isolate overflow-hidden border-b" style="border-color:var(--border);background:var(--bg_card);">
        <img src="{{ $openNowHero }}" alt="{{ $settings->site_name ?? 'Şu an açık yerel firmalar' }}" class="absolute inset-0 -z-20 h-full w-full object-cover object-right" width="1920" height="1080" fetchpriority="high">
        <div class="absolute inset-0 -z-10" style="background:linear-gradient(90deg,rgba(12,30,48,.94) 0%,rgba(12,30,48,.80) 43%,rgba(12,30,48,.42) 69%,rgba(12,30,48,.08) 100%);"></div>
        <div class="mx-auto grid min-h-[520px] items-center gap-9 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_0.75fr] lg:px-8 lg:py-16" style="max-width:var(--page_width,1280px);">
            <div>
                <div class="mb-4 inline-flex items-center gap-2 rounded-full border border-white/25 bg-white/10 px-3 py-2 text-xs font-black" style="color:#fff;"><span class="h-2 w-2 rounded-full bg-green-400"></span>{{ now()->format('H:i') }} · Canlı çalışma saati</div>
                <h1 class="max-w-3xl text-4xl font-black leading-tight sm:text-6xl" style="color:#fff;">{{ $settings->homepage_title ?? 'Şu an açık olan firmayı hemen bul' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8" style="color:rgba(255,255,255,.82);">{{ $settings->homepage_subtitle ?? 'Gece geç saatte, hafta sonunda veya acil bir anda hizmet veren işletmelere hızlıca ulaşın.' }}</p>
                <form action="{{ route('search') }}" method="GET" class="mt-7 flex max-w-2xl flex-col gap-2 rounded-2xl bg-white p-3 shadow-2xl sm:flex-row"><label class="sr-only" for="open-now-search">Açık firma ara</label><input id="open-now-search" name="q" class="min-h-14 min-w-0 flex-1 rounded-xl border px-5 text-sm outline-none" placeholder="Nöbetçi eczane, çekici, restoran..." style="border-color:var(--border);color:var(--text);"><button class="min-h-14 rounded-xl px-7 font-black" style="background:var(--primary);color:#fff;">Bul</button></form>
            </div>
            <div class="grid grid-cols-2 gap-3 self-center">
                @foreach($categories->take(6) as $category)<a href="{{ route('categories.show',$category->slug) }}" class="border-l-4 bg-white/90 p-4 shadow-xl backdrop-blur-sm transition hover:-translate-y-0.5" style="border-color:var(--primary);"><div class="text-sm font-black" style="color:var(--text);">{{ $category->name }}</div><div class="mt-1 text-xs" style="color:var(--text_muted);">{{ $category->companies_count }} firma</div></a>@endforeach
            </div>
        </div>
    </section>

    <section class="py-12">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="mb-7 flex flex-wrap items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[0.18em]" style="color:var(--accent);">{{ $openCompanies->isNotEmpty() ? 'Şu an hizmet veriyor' : 'Çalışma saati bilgisi bekleniyor' }}</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">{{ $openCompanies->isNotEmpty() ? 'Açık işletmeler' : 'Son eklenen işletmeler' }}</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Tüm firmalar</a></div>
            <div class="grid gap-4 md:grid-cols-2">
                @foreach($displayCompanies as $company)
                    <article class="flex gap-4 rounded-lg border p-4 shadow-sm" style="border-color:var(--border);background:var(--bg_card);">
                        <a href="{{ route('companies.show',$company->slug) }}" class="flex h-16 w-16 shrink-0 items-center justify-center overflow-hidden rounded-md text-2xl font-black text-white" style="background:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full bg-white object-contain p-1">@else{{ mb_substr($company->name,0,1) }}@endif</a>
                        <div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2">@if($company->isOpenNow() === true)<span class="rounded-full bg-green-50 px-2 py-1 text-[10px] font-black text-green-700">ŞU AN AÇIK</span>@elseif($company->opening_hours)<span class="rounded-full px-2 py-1 text-[10px] font-black" style="background:var(--primary_light);color:var(--primary);">SAATLERİ GÖR</span>@endif</div><a href="{{ route('companies.show',$company->slug) }}" class="mt-2 block truncate font-black" style="color:var(--text);">{{ $company->name }}</a><p class="mt-1 text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p><div class="mt-3 flex gap-2">@if($company->phone)<a href="tel:{{ $company->phone }}" class="rounded-md px-3 py-2 text-xs font-black text-white" style="background:var(--primary);">Hemen ara</a>@endif<a href="{{ route('companies.show',$company->slug) }}" class="rounded-md border px-3 py-2 text-xs font-black" style="border-color:var(--border);color:var(--text);">Detay</a></div></div>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y py-10" style="border-color:var(--border);background:var(--text);"><div class="mx-auto grid gap-6 px-4 text-white sm:px-6 md:grid-cols-3 lg:px-8" style="max-width:var(--page_width,1280px);"><div><strong class="text-2xl">Acil hizmet</strong><p class="mt-2 text-sm text-white/65">Telefon bilgisi bulunan firmalara tek dokunuşla ulaşın.</p></div><div><strong class="text-2xl">Şehir bazlı</strong><p class="mt-2 text-sm text-white/65">Bulunduğunuz bölgedeki açık işletmeleri keşfedin.</p></div><div><strong class="text-2xl">Güncel saatler</strong><p class="mt-2 text-sm text-white/65">Çalışma saatleri firma profilinden otomatik hesaplanır.</p></div></div></section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
