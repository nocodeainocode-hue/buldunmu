@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Şehir Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrin öne çıkan firmaları, semt rehberleri, hizmet kategorileri ve yerel keşif yazıları.')

@section('content')
@php
    $leadCompany = $premiumCompanies->first() ?? $latestCompanies->first();
    $journalCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies->take(6);
@endphp

<main style="background:var(--bg);">
    <section class="border-b" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto px-4 py-5 sm:px-6 lg:px-8" style="max-width:var(--page_width,1180px);">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b pb-4 text-xs font-bold uppercase tracking-[0.18em]" style="border-color:var(--border);color:var(--text_muted);">
                <span>Yerel işletmeler · Şehir yaşamı · Güncel rehber</span>
                <span>{{ now()->translatedFormat('d F Y') }}</span>
            </div>
            <div class="py-7 text-center"><div class="font-serif text-4xl font-black sm:text-6xl" style="color:var(--text);">{{ $settings->site_name ?? 'Şehir Rehberi' }}</div><p class="mt-2 text-sm" style="color:var(--text_muted);">Şehrin iyi adreslerini keşfetmenin kısa yolu</p></div>
            <nav class="flex gap-6 overflow-x-auto border-t pt-4 text-sm font-black" style="border-color:var(--border);color:var(--text);">
                <a href="{{ route('companies.index') }}">Firmalar</a>@foreach($categories->take(6) as $category)<a href="{{ route('categories.show', $category->slug) }}" class="whitespace-nowrap">{{ $category->name }}</a>@endforeach
            </nav>
        </div>
    </section>

    <section class="mx-auto px-4 py-10 sm:px-6 lg:px-8" style="max-width:var(--page_width,1180px);">
        <div class="grid gap-8 lg:grid-cols-[1.55fr_0.8fr]">
            <div class="relative min-h-[460px] overflow-hidden border" style="border-color:var(--border);background:var(--text);">
                @if($leadCompany && ($leadCompany->cover_image || $leadCompany->logo))
                    <img src="{{ asset('storage/'.($leadCompany->cover_image ?: $leadCompany->logo)) }}" alt="{{ $leadCompany->name }}" class="absolute inset-0 h-full w-full object-cover opacity-70">
                @endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/25 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-6 sm:p-9">
                    <div class="mb-3 inline-block px-3 py-1 text-xs font-black uppercase tracking-wider text-white" style="background:var(--primary);">Haftanın seçimi</div>
                    @if($leadCompany)
                        <h1 class="max-w-3xl font-serif text-4xl font-black leading-tight text-white sm:text-5xl">{{ $leadCompany->name }}</h1>
                        <p class="mt-3 max-w-2xl text-sm leading-7 text-white/80">{{ $leadCompany->short_description ?: ($settings->homepage_subtitle ?? 'Şehrin dikkat çeken işletmelerini, hizmetlerini ve adreslerini keşfedin.') }}</p>
                        <a href="{{ route('companies.show', $leadCompany->slug) }}" class="mt-5 inline-block border-b-2 border-white pb-1 text-sm font-black text-white">Firmayı incele</a>
                    @else
                        <h1 class="font-serif text-4xl font-black text-white">{{ $settings->homepage_title ?? 'Şehrin iyi adresleri burada' }}</h1>
                    @endif
                </div>
            </div>

            <aside class="border-y py-6 lg:border-y-0 lg:border-l lg:py-0 lg:pl-7" style="border-color:var(--border);">
                <div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Hızlı keşif</div><h2 class="mt-2 font-serif text-3xl font-black" style="color:var(--text);">Bugün ne arıyorsunuz?</h2>
                <form action="{{ route('search') }}" method="GET" class="mt-5 border-b-2 pb-2" style="border-color:var(--text);"><div class="flex"><input name="q" class="min-w-0 flex-1 bg-transparent py-3 text-sm outline-none" placeholder="Firma, hizmet veya şehir..." style="color:var(--text);"><button class="px-3 text-sm font-black" style="color:var(--primary);">Ara</button></div></form>
                <div class="mt-7 divide-y" style="border-color:var(--border);">
                    @foreach($categories->take(7) as $category)
                        <a href="{{ route('categories.show', $category->slug) }}" class="flex items-center justify-between py-3 text-sm font-bold" style="border-color:var(--border);color:var(--text);"><span>{{ $category->name }}</span><span style="color:var(--text_muted);">{{ $category->companies_count }}</span></a>
                    @endforeach
                </div>
            </aside>
        </div>
    </section>

    <section class="border-y py-12" style="border-color:var(--border);background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1180px);">
            <div class="mb-7 flex items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Editörün seçtikleri</div><h2 class="mt-2 font-serif text-3xl font-black" style="color:var(--text);">Şehrin öne çıkan işletmeleri</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Tüm firmalar</a></div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">@foreach($journalCompanies as $company) @include('partials.cards.visual', ['company'=>$company, 'premium'=>$company->is_premium]) @endforeach</div>
        </div>
    </section>

    <section class="mx-auto grid gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8" style="max-width:var(--page_width,1180px);">
        <div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Şehir dosyaları</div><h2 class="mt-2 font-serif text-3xl font-black" style="color:var(--text);">Bölge bölge firma keşfi</h2><div class="mt-7 grid gap-px border sm:grid-cols-2" style="border-color:var(--border);background:var(--border);">@foreach($cities as $city)<a href="{{ route('cities.show', $city->slug) }}" class="group p-5" style="background:var(--bg_card);"><div class="flex items-baseline justify-between"><h3 class="font-serif text-xl font-black" style="color:var(--text);">{{ $city->name }}</h3><span class="text-xs font-bold" style="color:var(--primary);">{{ $city->companies_count }} firma</span></div><p class="mt-2 text-xs leading-5" style="color:var(--text_muted);">{{ $city->name }} işletmeleri, hizmet noktaları ve yerel öneriler.</p></a>@endforeach</div></div>
        <aside class="border-l pl-6" style="border-color:var(--border);"><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Yeni eklenenler</div><div class="mt-5 divide-y" style="border-color:var(--border);">@foreach($latestCompanies->take(7) as $company)<a href="{{ route('companies.show', $company->slug) }}" class="block py-4" style="border-color:var(--border);"><div class="text-xs font-bold uppercase" style="color:var(--primary);">{{ $company->category->name ?? 'Firma' }}</div><h3 class="mt-1 font-serif text-lg font-black" style="color:var(--text);">{{ $company->name }}</h3><p class="mt-1 text-xs" style="color:var(--text_muted);">{{ $company->city->name ?? 'Türkiye' }}{{ $company->district ? ' / '.$company->district->name : '' }}</p></a>@endforeach</div></aside>
    </section>

    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
