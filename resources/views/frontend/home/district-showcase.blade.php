@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Semt Vitrini')
@section('meta_description', $settings->meta_description ?? 'Şehrin semtlerini, popüler işletmelerini ve yerel keşiflerini görsel bir vitrinde inceleyin.')

@section('content')
@php
    $showcase = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies->take(6);
    $lead = $showcase->first();
@endphp
<main style="background:var(--bg);">
    <section class="mx-auto px-4 py-8 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
        <div class="grid min-h-[520px] gap-4 lg:grid-cols-[1.4fr_0.6fr]">
            <div class="relative overflow-hidden rounded-2xl" style="background:var(--primary);">
                @if($lead && ($lead->cover_image || $lead->logo))<img src="{{ asset('storage/'.($lead->cover_image ?: $lead->logo)) }}" alt="{{ $lead->name }}" class="absolute inset-0 h-full w-full object-cover opacity-55">@endif
                <div class="absolute inset-0 bg-gradient-to-t from-black/85 via-black/20 to-transparent"></div>
                <div class="absolute inset-x-0 bottom-0 p-7 sm:p-10">
                    <span class="rounded-full px-3 py-2 text-xs font-black uppercase tracking-wider text-white" style="background:var(--secondary);">Semtin vitrini</span>
                    <h1 class="mt-5 max-w-3xl text-4xl font-black leading-tight text-white sm:text-6xl">{{ $settings->homepage_title ?? ($lead?->name ?: 'Şehrin sevilen adreslerini keşfedin') }}</h1>
                    <p class="mt-4 max-w-2xl text-sm leading-7 text-white/80">{{ $settings->homepage_subtitle ?? 'İyi mekanlar, güvenilir hizmetler ve yeni açılan işletmeler bir arada.' }}</p>
                </div>
            </div>
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-1">
                <form action="{{ route('search') }}" class="flex flex-col justify-between rounded-2xl p-6 text-white" style="background:var(--secondary);">
                    <div><div class="text-xs font-black uppercase tracking-wider text-white/70">Keşfe başla</div><h2 class="mt-2 text-3xl font-black">Bugün ne arıyorsun?</h2></div>
                    <div class="mt-6 flex overflow-hidden rounded-xl bg-white"><input name="q" class="min-w-0 flex-1 px-4 py-3 text-sm text-slate-900 outline-none" placeholder="Kafe, klinik, usta..."><button class="px-4 text-xs font-black" style="color:var(--secondary);">Ara</button></div>
                </form>
                <a href="{{ route('listing.create') }}" class="flex flex-col justify-between rounded-2xl border bg-white p-6" style="border-color:var(--border);">
                    <span class="text-xs font-black uppercase tracking-wider" style="color:var(--accent);">İşletme sahipleri</span><span><strong class="block text-2xl font-black" style="color:var(--text);">Vitrindeki yerinizi alın</strong><small class="mt-2 block leading-6" style="color:var(--text_muted);">Firmanızı ekleyin, profilinizi müşterilerle buluşturun.</small></span>
                </a>
            </div>
        </div>
    </section>

    <section class="mx-auto px-4 py-10 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
        <div class="mb-6 flex items-end justify-between"><div><span class="text-xs font-black uppercase tracking-wider" style="color:var(--secondary);">Şehir seçkisi</span><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Bölge bölge keşfet</h2></div></div>
        <div class="flex gap-3 overflow-x-auto pb-3">@foreach($cities->take(12) as $index => $city)<a href="{{ route('cities.show',$city->slug) }}" class="min-w-[180px] rounded-2xl border bg-white p-5" style="border-color:var(--border);"><span class="text-xs font-black" style="color:{{ $index % 2 ? 'var(--secondary)' : 'var(--primary)' }};">{{ str_pad($index + 1,2,'0',STR_PAD_LEFT) }}</span><strong class="mt-7 block text-xl font-black" style="color:var(--text);">{{ $city->name }}</strong><small class="mt-1 block" style="color:var(--text_muted);">{{ $city->companies_count }} işletme</small></a>@endforeach</div>
    </section>

    <section class="border-y bg-white py-12" style="border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
            <div class="mb-7 flex items-end justify-between"><div><span class="text-xs font-black uppercase tracking-wider" style="color:var(--primary);">Yerel yıldızlar</span><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Öne çıkan işletmeler</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--secondary);">Tümünü gör</a></div>
            <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">@forelse($showcase->take(6) as $company) @include('partials.cards.visual',['company'=>$company,'premium'=>$company->is_premium]) @empty<div class="col-span-full rounded-2xl border p-12 text-center" style="border-color:var(--border);color:var(--text_muted);">Henüz firma yok.</div>@endforelse</div>
        </div>
    </section>

    <section class="mx-auto grid gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[0.75fr_1.25fr] lg:px-8" style="max-width:var(--page_width);">
        <div><span class="text-xs font-black uppercase tracking-wider" style="color:var(--accent);">Kategori rotaları</span><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Şehirde ne yapmak istersiniz?</h2></div>
        <div class="grid gap-3 sm:grid-cols-2">@foreach($categories->take(8) as $category)<a href="{{ route('categories.show',$category->slug) }}" class="flex items-center justify-between rounded-xl border bg-white p-4" style="border-color:var(--border);"><strong class="text-sm" style="color:var(--text);">{{ $category->name }}</strong><span class="rounded-full px-2 py-1 text-xs font-black" style="background:var(--primary_light);color:var(--primary);">{{ $category->companies_count }}</span></a>@endforeach</div>
    </section>
    @include('partials.blog-section')
</main>
@endsection
