@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Şehir Panosu')
@section('meta_description', $settings->meta_description ?? 'Şehirdeki işletmeleri, güncel kayıtları ve hizmet kategorilerini tek panoda keşfedin.')

@section('content')
@php
    $boardCompanies = $latestCompanies->take(7);
    $featured = $premiumCompanies->first() ?? $latestCompanies->first();
@endphp
<main class="min-h-screen" style="background:var(--bg);">
    <section class="border-b bg-white" style="border-color:var(--border);">
        <div class="mx-auto grid gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_420px] lg:items-center lg:px-8" style="max-width:var(--page_width);">
            <div>
                <div class="inline-flex items-center gap-2 rounded-md px-3 py-2 text-xs font-black uppercase tracking-wider" style="background:var(--primary_light);color:var(--primary);">
                    <span class="h-2 w-2 rounded-full" style="background:var(--accent);"></span>
                    Şehrin güncel işletme panosu
                </div>
                <h1 class="mt-5 max-w-3xl text-4xl font-black leading-tight sm:text-5xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Aradığınız hizmeti şehir gündeminden bulun' }}</h1>
                <p class="mt-4 max-w-2xl text-base leading-7" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Yeni firmalar, yoğun kategoriler ve aktif şehirler tek bakışta önünüzde.' }}</p>
                <form action="{{ route('search') }}" method="GET" class="mt-7 flex max-w-2xl overflow-hidden rounded-md border bg-white" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <input name="q" class="min-w-0 flex-1 px-5 py-4 text-sm outline-none" placeholder="Firma, hizmet veya şehir ara">
                    <button class="px-6 text-sm font-black text-white" style="background:var(--primary);">Panoda ara</button>
                </form>
            </div>
            <div class="grid grid-cols-2 gap-px overflow-hidden rounded-md border" style="border-color:var(--border);background:var(--border);">
                <div class="bg-white p-5"><span class="text-3xl font-black" style="color:var(--primary);">{{ \App\Models\Company::active()->count() }}</span><span class="mt-1 block text-xs font-bold" style="color:var(--text_muted);">Aktif firma</span></div>
                <div class="bg-white p-5"><span class="text-3xl font-black" style="color:var(--secondary);">{{ $categories->count() }}</span><span class="mt-1 block text-xs font-bold" style="color:var(--text_muted);">Öne çıkan kategori</span></div>
                <div class="bg-white p-5"><span class="text-3xl font-black" style="color:var(--accent);">{{ $cities->count() }}</span><span class="mt-1 block text-xs font-bold" style="color:var(--text_muted);">Aktif şehir</span></div>
                <a href="{{ route('listing.create') }}" class="flex items-center justify-center p-5 text-center text-sm font-black text-white" style="background:var(--secondary);">Firmanı ekle</a>
            </div>
        </div>
    </section>

    <section class="mx-auto grid gap-5 px-4 py-7 sm:px-6 lg:grid-cols-[260px_minmax(0,1fr)_320px] lg:px-8" style="max-width:var(--page_width);">
        <aside class="h-fit rounded-md border bg-white p-4" style="border-color:var(--border);">
            <div class="mb-3 text-xs font-black uppercase tracking-wider" style="color:var(--text_muted);">Şehir kanalları</div>
            <div class="space-y-1">
                @foreach($cities->take(10) as $city)
                    <a href="{{ route('cities.show',$city->slug) }}" class="flex items-center justify-between rounded-md px-3 py-2.5 text-sm font-bold hover:opacity-70" style="color:var(--text);">
                        <span>{{ $city->name }}</span><span class="text-xs" style="color:var(--secondary);">{{ $city->companies_count }}</span>
                    </a>
                @endforeach
            </div>
        </aside>

        <div class="min-w-0">
            <div class="mb-4 flex items-center justify-between"><h2 class="text-xl font-black" style="color:var(--text);">Yeni kayıt akışı</h2><a href="{{ route('companies.index') }}" class="text-xs font-black" style="color:var(--primary);">Tümünü aç</a></div>
            <div class="overflow-hidden rounded-md border bg-white" style="border-color:var(--border);">
                @forelse($boardCompanies as $company)
                    <a href="{{ route('companies.show',$company->slug) }}" class="grid gap-3 border-b p-4 last:border-0 sm:grid-cols-[46px_minmax(0,1fr)_auto] sm:items-center" style="border-color:var(--border);">
                        <span class="flex h-11 w-11 items-center justify-center overflow-hidden rounded-md font-black" style="background:var(--primary_light);color:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain">@else{{ mb_substr($company->name,0,1) }}@endif</span>
                        <span class="min-w-0"><strong class="block truncate text-sm" style="color:var(--text);">{{ $company->name }}</strong><small class="mt-1 block truncate" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</small></span>
                        <span class="rounded-md px-3 py-2 text-xs font-black" style="background:var(--primary_light);color:var(--primary);">İncele</span>
                    </a>
                @empty
                    <div class="p-10 text-center text-sm" style="color:var(--text_muted);">Panoda henüz firma yok.</div>
                @endforelse
            </div>
        </div>

        <aside class="space-y-5">
            @if($featured)
                <div class="overflow-hidden rounded-md border bg-white" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <div class="p-5 text-white" style="background:var(--primary);"><div class="text-xs font-black uppercase tracking-wider">Pano seçimi</div><h2 class="mt-2 text-2xl font-black">{{ $featured->name }}</h2></div>
                    <div class="p-5"><p class="text-sm leading-6" style="color:var(--text_muted);">{{ $featured->short_description ?: 'Öne çıkan firma profilini, iletişim bilgilerini ve hizmetlerini inceleyin.' }}</p><a href="{{ route('companies.show',$featured->slug) }}" class="mt-4 inline-block text-sm font-black" style="color:var(--primary);">Firma detayına git</a></div>
                </div>
            @endif
            <div class="rounded-md border bg-white p-5" style="border-color:var(--border);"><h2 class="text-sm font-black" style="color:var(--text);">Hızlı kategoriler</h2><div class="mt-3 flex flex-wrap gap-2">@foreach($categories->take(8) as $category)<a href="{{ route('categories.show',$category->slug) }}" class="rounded-md border px-3 py-2 text-xs font-bold" style="border-color:var(--border);color:var(--text);">{{ $category->name }}</a>@endforeach</div></div>
        </aside>
    </section>

    @include('partials.blog-section')
</main>
@endsection
