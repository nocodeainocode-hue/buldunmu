@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Hızlı Teklif')
@section('meta_description', $settings->meta_description ?? 'İhtiyacınızı seçin, bölgenizdeki firmaları inceleyin ve hızlıca iletişime geçin.')

@section('content')
@php
    $quoteCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(5) : $latestCompanies->take(5);
    $heroImage = $directory?->hero_image
        ? asset('storage/'.$directory->hero_image)
        : asset('images/themes/local-service-street-hero.png');
@endphp
<main class="min-h-screen" style="background:var(--bg);">
    <section class="relative isolate overflow-hidden border-b" style="border-color:var(--border);background:var(--text);">
        <img src="{{ $heroImage }}" alt="{{ $settings->site_name ?? 'Yerel hizmetler' }}" class="absolute inset-0 -z-20 h-full w-full object-cover" width="1920" height="1080" fetchpriority="high">
        <div class="absolute inset-0 -z-10" style="background:linear-gradient(90deg,rgba(9,20,30,.92) 0%,rgba(9,20,30,.79) 39%,rgba(9,20,30,.28) 67%,rgba(9,20,30,.08) 100%);"></div>
        <div class="relative mx-auto grid min-h-[590px] gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[minmax(0,660px)_1fr] lg:px-8 lg:py-20" style="max-width:var(--page_width);">
            <div class="flex flex-col justify-center">
                <span class="w-fit rounded-full border border-white/30 bg-white/10 px-3 py-2 text-xs font-black uppercase tracking-wider" style="color:#fff;">İhtiyaçtan firmaya kısa yol</span>
                <h1 class="mt-6 max-w-3xl text-4xl font-black leading-tight sm:text-6xl" style="color:#fff;">{{ $settings->homepage_title ?? 'Hizmeti seçin, uygun firmaya hemen ulaşın' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7" style="color:rgba(255,255,255,.8);">{{ $settings->homepage_subtitle ?? 'Kategori ve konum seçiminizi daraltın, firma profillerini inceleyip doğrudan iletişime geçin.' }}</p>
                <div class="mt-8 grid max-w-2xl grid-cols-3 gap-3 text-center">
                    @foreach([['01','Hizmeti seç'],['02','Firmaları incele'],['03','İletişime geç']] as [$number,$label])
                        <div class="rounded-xl border border-white/20 bg-white/10 p-4 backdrop-blur-sm"><strong class="text-xl" style="color:#fff;">{{ $number }}</strong><span class="mt-1 block text-xs font-black" style="color:rgba(255,255,255,.85);">{{ $label }}</span></div>
                    @endforeach
                </div>
                <form action="{{ route('search') }}" method="GET" class="mt-9 max-w-2xl rounded-2xl border border-white/20 bg-white p-3 shadow-2xl">
                    <label class="sr-only" for="quick-quote-search">Hizmet veya firma</label>
                    <div class="flex flex-col gap-2 sm:flex-row"><input id="quick-quote-search" name="q" class="min-h-14 min-w-0 flex-1 rounded-xl border px-4 text-sm outline-none" style="border-color:var(--border);color:var(--text);" placeholder="Örn. diş kliniği, oto servis"><button class="min-h-14 rounded-xl px-6 text-sm font-black" style="background:var(--primary);color:#fff;">Uygun firmaları göster</button></div>
                    <div class="mt-3 flex flex-wrap gap-2 px-1">@foreach($categories->take(5) as $category)<a href="{{ route('categories.show',$category->slug) }}" class="rounded-full px-3 py-2 text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">{{ $category->name }}</a>@endforeach</div>
                </form>
            </div>
        </div>
    </section>

    <section class="mx-auto px-4 py-12 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
        <div class="mb-6 flex items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--secondary);">Hızlı erişim</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Öne çıkan firmalar</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Tüm firmalar</a></div>
        <div class="grid gap-4 lg:grid-cols-[1.2fr_0.8fr]">
            <div class="space-y-3">
                @forelse($quoteCompanies as $company)
                    <article class="grid gap-4 rounded-xl border bg-white p-4 sm:grid-cols-[54px_minmax(0,1fr)_auto] sm:items-center" style="border-color:var(--border);">
                        <div class="flex h-13 w-13 items-center justify-center overflow-hidden rounded-xl font-black" style="height:52px;width:52px;background:var(--primary_light);color:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain">@else{{ mb_substr($company->name,0,1) }}@endif</div>
                        <div class="min-w-0"><h3 class="truncate text-base font-black" style="color:var(--text);">{{ $company->name }}</h3><p class="mt-1 truncate text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p></div>
                        <div class="flex gap-2"><a href="{{ route('companies.show',$company->slug) }}" class="rounded-lg border px-3 py-2 text-xs font-black" style="border-color:var(--border);color:var(--secondary);">Profil</a>@if($company->phone)<a href="tel:{{ preg_replace('/\D+/','',$company->phone) }}" class="rounded-lg px-3 py-2 text-xs font-black text-white" style="background:var(--primary);">Ara</a>@endif</div>
                    </article>
                @empty<div class="rounded-xl border bg-white p-10 text-center text-sm" style="border-color:var(--border);color:var(--text_muted);">Henüz firma yok.</div>@endforelse
            </div>
            <aside class="rounded-xl p-6 text-white" style="background:var(--secondary);">
                <div class="text-xs font-black uppercase tracking-wider text-white/70">Bölge seçimi</div><h2 class="mt-2 text-3xl font-black">Yakınınızdaki hizmetler</h2><div class="mt-6 grid grid-cols-2 gap-2">@foreach($cities->take(8) as $city)<a href="{{ route('cities.show',$city->slug) }}" class="rounded-lg bg-white/10 p-3 text-sm font-black">{{ $city->name }}<small class="mt-1 block font-normal text-white/70">{{ $city->companies_count }} firma</small></a>@endforeach</div>
            </aside>
        </div>
    </section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
