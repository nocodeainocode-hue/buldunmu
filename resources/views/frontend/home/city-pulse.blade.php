@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Şehir Nabzı Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki seçili firmaları, hizmetleri ve konumu belli işletmeleri tek bir canlı rehberde keşfedin.')

@section('content')
@php
    $pulseHero = $directory?->hero_image
        ? asset('storage/'.$directory->hero_image)
        : asset('images/themes/city-pulse-hero.png');
    $pulseFeatured = $premiumCompanies->first() ?? $latestCompanies->first();
    $pulseCompanies = $premiumCompanies->merge($latestCompanies)->unique('id')->take(6);
    $pulseMapCompanies = $mapCompanies->isNotEmpty() ? $mapCompanies : $latestCompanies->filter(fn ($company) => $company->latitude && $company->longitude);
@endphp

<main style="background:var(--bg);">
    <section class="relative isolate min-h-[650px] overflow-hidden" style="background:var(--text);">
        <img src="{{ $pulseHero }}" alt="{{ $settings->site_name ?? 'Şehirdeki yerel işletmeler' }}" class="absolute inset-0 -z-20 h-full w-full object-cover" width="1920" height="1080" fetchpriority="high">
        <div class="absolute inset-0 -z-10" style="background:linear-gradient(90deg,rgba(16,28,34,.95) 0%,rgba(16,28,34,.78) 41%,rgba(16,28,34,.34) 68%,rgba(16,28,34,.10) 100%);"></div>

        <div class="mx-auto flex min-h-[650px] flex-col justify-between px-4 py-12 sm:px-6 lg:px-8 lg:py-16" style="max-width:var(--page_width,1320px);">
            <div class="max-w-3xl pt-5 sm:pt-12">
                <div class="inline-flex items-center gap-3 border-l-2 pl-3 text-xs font-black uppercase tracking-[0.22em]" style="border-color:var(--accent);color:rgba(255,255,255,.78);">Canlı yerel keşif</div>
                <h1 class="mt-6 max-w-3xl text-5xl font-black leading-[.96] sm:text-6xl lg:text-7xl" style="color:#fff;font-family:var(--font_heading);">{{ $settings->homepage_title ?? 'Şehrin iyi işletmeleri, tek bir nabızda.' }}</h1>
                <p class="mt-6 max-w-2xl text-base leading-8 sm:text-lg" style="color:rgba(255,255,255,.80);">{{ $settings->homepage_subtitle ?? 'Yerel markaları, iyi hizmet veren işletmeleri ve şehirde olup biteni tek bir güçlü rehberden takip edin.' }}</p>

                <form action="{{ route('search') }}" method="GET" class="mt-9 max-w-2xl border bg-white p-3 shadow-2xl" style="border-color:rgba(255,255,255,.35);border-radius:var(--border_radius);">
                    <label class="sr-only" for="city-pulse-search">Firma, kategori veya şehir ara</label>
                    <div class="flex flex-col gap-2 sm:flex-row">
                        <input id="city-pulse-search" name="q" class="min-h-14 min-w-0 flex-1 border px-4 text-sm outline-none" style="border-color:var(--border);border-radius:calc(var(--border_radius) - 2px);color:var(--text);" placeholder="Ne arıyorsunuz? Örn. avukat, su arıtma, restoran">
                        <button class="min-h-14 px-7 text-sm font-black" style="background:var(--primary);border-radius:calc(var(--border_radius) - 2px);color:#fff;">Şehirde ara</button>
                    </div>
                    <div class="mt-3 flex flex-wrap gap-2 px-1">
                        @foreach($categories->take(5) as $category)
                            <a href="{{ route('categories.show', $category->slug) }}" class="border px-3 py-2 text-xs font-bold" style="border-color:var(--border);border-radius:999px;color:var(--text_muted);">{{ $category->name }}</a>
                        @endforeach
                    </div>
                </form>
            </div>

            <div class="grid max-w-3xl grid-cols-3 border border-white/20 bg-white/10 backdrop-blur-md" style="border-radius:var(--border_radius);">
                <div class="border-r border-white/20 p-4 sm:p-5"><strong class="block text-2xl sm:text-3xl" style="color:#fff;">{{ $latestCompanies->count() }}</strong><span class="mt-1 block text-xs" style="color:rgba(255,255,255,.72);">Yeni keşif</span></div>
                <div class="border-r border-white/20 p-4 sm:p-5"><strong class="block text-2xl sm:text-3xl" style="color:#fff;">{{ $categories->count() }}</strong><span class="mt-1 block text-xs" style="color:rgba(255,255,255,.72);">Hizmet alanı</span></div>
                <div class="p-4 sm:p-5"><strong class="block text-2xl sm:text-3xl" style="color:#fff;">{{ $mapCompanies->count() }}</strong><span class="mt-1 block text-xs" style="color:rgba(255,255,255,.72);">Konumu belli</span></div>
            </div>
        </div>
    </section>

    <section class="relative z-10 -mt-8 pb-8 sm:-mt-10 sm:pb-12">
        <div class="mx-auto grid gap-4 px-4 sm:px-6 lg:grid-cols-[1.15fr_.85fr] lg:px-8" style="max-width:var(--page_width,1320px);">
            @if($pulseFeatured)
                <article class="grid overflow-hidden border sm:grid-cols-[180px_1fr]" style="border-color:var(--border);background:var(--bg_card);border-radius:var(--border_radius);box-shadow:var(--card_shadow);">
                    <a href="{{ route('companies.show', $pulseFeatured->slug) }}" class="flex min-h-48 items-center justify-center p-7" style="background:var(--secondary);">
                        @if($pulseFeatured->logo)
                            <img src="{{ asset('storage/'.$pulseFeatured->logo) }}" alt="{{ $pulseFeatured->name }}" class="max-h-24 max-w-full object-contain" style="background:#fff;border-radius:var(--border_radius);padding:.75rem;">
                        @else
                            <span class="text-6xl font-black" style="color:#fff;font-family:var(--font_heading);">{{ mb_substr($pulseFeatured->name, 0, 1) }}</span>
                        @endif
                    </a>
                    <div class="p-6"><div class="flex flex-wrap items-center gap-2"><span class="px-2 py-1 text-[10px] font-black uppercase tracking-wider" style="background:var(--primary_light);border-radius:999px;color:var(--primary);">Editörün seçimi</span>@if($pulseFeatured->is_verified)<span class="text-[10px] font-black uppercase tracking-wider" style="color:var(--secondary);">Doğrulandı</span>@endif</div><h2 class="mt-4 text-3xl font-black leading-tight" style="color:var(--text);font-family:var(--font_heading);">{{ $pulseFeatured->name }}</h2><p class="mt-2 text-sm" style="color:var(--text_muted);">{{ $pulseFeatured->category->name ?? 'Firma' }} · {{ $pulseFeatured->city->name ?? 'Türkiye' }}</p><p class="mt-4 line-clamp-2 text-sm leading-6" style="color:var(--text_muted);">{{ $pulseFeatured->short_description ?: 'Şehrinizde hizmet veren bu işletmenin iletişim, konum ve profil ayrıntılarını inceleyin.' }}</p><a href="{{ route('companies.show', $pulseFeatured->slug) }}" class="mt-5 inline-block border-b-2 pb-1 text-sm font-black" style="border-color:var(--accent);color:var(--text);">Profili keşfet</a></div>
                </article>
            @else
                <article class="border p-7" style="border-color:var(--border);background:var(--bg_card);border-radius:var(--border_radius);"><div class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--primary);">Şehir seçkisi</div><h2 class="mt-3 text-3xl font-black" style="color:var(--text);font-family:var(--font_heading);">İyi işletmeler burada görünür.</h2><p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">İlk firmalar eklendikçe bu alan seçili bir işletme vitrini olur.</p></article>
            @endif

            <section class="border p-5 sm:p-6" style="border-color:var(--border);background:var(--bg_card);border-radius:var(--border_radius);box-shadow:var(--card_shadow);"><div class="flex items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--primary);">Şimdi keşfet</div><h2 class="mt-2 text-2xl font-black" style="color:var(--text);font-family:var(--font_heading);">Hizmete göre başla</h2></div><a href="{{ route('companies.index') }}" class="text-xs font-black" style="color:var(--secondary);">Tümü</a></div><div class="mt-5 grid grid-cols-2 gap-2">@foreach($categories->take(6) as $category)<a href="{{ route('categories.show', $category->slug) }}" class="border p-3 transition hover:-translate-y-0.5" style="border-color:var(--border);border-radius:calc(var(--border_radius) - 2px);background:var(--bg);"><span class="block text-sm font-black" style="color:var(--text);">{{ $category->name }}</span><span class="mt-1 block text-xs" style="color:var(--text_muted);">{{ $category->companies_count }} firma</span></a>@endforeach</div></section>
        </div>
    </section>

    <section class="border-y py-12 sm:py-16" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[.7fr_1.3fr] lg:px-8" style="max-width:var(--page_width,1320px);">
            <div class="flex flex-col justify-between"><div><div class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--primary);">Şehir haritası</div><h2 class="mt-4 text-4xl font-black leading-tight" style="color:var(--text);font-family:var(--font_heading);">Sadece listeleme değil, yerini de görün.</h2><p class="mt-5 text-sm leading-7" style="color:var(--text_muted);">Konum bilgisi bulunan işletmeleri haritada inceleyin; ardından profilinden doğrudan iletişime geçin.</p></div><div class="mt-7 border-t pt-5" style="border-color:var(--border);"><div class="grid grid-cols-2 gap-4"><div><strong class="text-2xl" style="color:var(--secondary);">{{ $mapCompanies->count() }}</strong><span class="mt-1 block text-xs" style="color:var(--text_muted);">Haritalı firma</span></div><div><strong class="text-2xl" style="color:var(--secondary);">{{ $cities->count() }}</strong><span class="mt-1 block text-xs" style="color:var(--text_muted);">Aktif şehir</span></div></div><a href="{{ route('companies.index') }}" class="mt-6 inline-block px-5 py-3 text-sm font-black" style="background:var(--secondary);border-radius:var(--border_radius);color:#fff;">Haritada keşfet</a></div></div>
            <div class="overflow-hidden border" style="border-color:var(--border);border-radius:var(--border_radius);">@include('partials.maps.company-map', ['companies' => $pulseMapCompanies, 'mapId' => 'city-pulse-map', 'height' => '520px'])</div>
        </div>
    </section>

    <section class="py-12 sm:py-16">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1320px);"><div class="mb-8 flex flex-wrap items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--primary);">Yeni nabızlar</div><h2 class="mt-3 text-4xl font-black" style="color:var(--text);font-family:var(--font_heading);">Şehre yeni eklenenler</h2></div><a href="{{ route('companies.index') }}" class="border-b-2 pb-1 text-sm font-black" style="border-color:var(--accent);color:var(--text);">Tüm firmalar</a></div><div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">@forelse($pulseCompanies as $company)<article class="border p-5 transition hover:-translate-y-1" style="border-color:var(--border);background:var(--bg_card);border-radius:var(--border_radius);box-shadow:var(--card_shadow);"><div class="flex items-start gap-4"><a href="{{ route('companies.show', $company->slug) }}" class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden" style="background:var(--primary_light);border-radius:var(--border_radius);color:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain p-1">@else<span class="text-xl font-black">{{ mb_substr($company->name, 0, 1) }}</span>@endif</a><div class="min-w-0 flex-1"><div class="flex items-center justify-between gap-2"><span class="truncate text-xs font-bold" style="color:var(--primary);">{{ $company->category->name ?? 'Firma' }}</span>@if($company->is_premium)<span class="text-[10px] font-black" style="color:var(--accent);">SEÇİLİ</span>@endif</div><a href="{{ route('companies.show', $company->slug) }}" class="mt-1 block truncate text-lg font-black" style="color:var(--text);">{{ $company->name }}</a><p class="mt-1 text-xs" style="color:var(--text_muted);">{{ $company->city->name ?? 'Türkiye' }}{{ $company->district ? ' · '.$company->district->name : '' }}</p></div></div><div class="mt-5 flex items-center justify-between border-t pt-4 text-xs" style="border-color:var(--border);"><span style="color:var(--text_muted);">{{ $company->phone ? 'Telefon bilgisi var' : 'Profil bilgileri' }}</span><a href="{{ route('companies.show', $company->slug) }}" class="font-black" style="color:var(--secondary);">İncele</a></div></article>@empty<div class="border p-10 text-center text-sm" style="border-color:var(--border);background:var(--bg_card);color:var(--text_muted);border-radius:var(--border_radius);">Firma eklenmeye başladığında şehir akışı burada görünür.</div>@endforelse</div></div>
    </section>

    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
