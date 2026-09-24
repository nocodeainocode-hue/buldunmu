@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Hizmetleri, şehirleri ve firmaları tek ekranda keşfedin.')

@push('head')
<style>
    .signal-station .signal-grid { background-image: linear-gradient(rgba(255,255,255,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.06) 1px,transparent 1px); background-size: 26px 26px; }
    .signal-station .signal-link:hover { background: var(--primary_light); }
</style>
@endpush

@section('content')
@php $signalCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(4) : $latestCompanies->take(4); @endphp
<main class="signal-station" style="background:var(--bg)">
    <section class="signal-grid" style="background-color:var(--primary);color:white">
        <div class="mx-auto grid gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:items-end lg:py-20" style="max-width:var(--page_width)">
            <div>
                <p class="mb-5 inline-flex items-center gap-3 border border-white/30 px-3 py-2 text-xs font-bold uppercase tracking-[.2em]"><span class="h-2 w-2 rounded-full" style="background:var(--accent)"></span>Hizmet ağı / keşif noktası</p>
                <h1 class="max-w-3xl text-4xl font-black leading-tight sm:text-6xl" style="font-family:var(--font_heading)">{{ $settings->homepage_title ?? 'Aradığınız işletmeye giden kısa yol' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-7 text-white/80">{{ $settings->homepage_subtitle ?? 'Hizmeti seçin, bölgenizdeki firmaları inceleyin ve doğrudan iletişime geçin.' }}</p>
                <form action="{{ route('search') }}" method="GET" role="search" class="mt-8 flex max-w-2xl flex-col gap-2 sm:flex-row">
                    <label for="signal-search" class="sr-only">Firma veya hizmet ara</label>
                    <input id="signal-search" name="q" class="min-w-0 flex-1 px-5 py-4 text-sm text-slate-900 outline-none" placeholder="Firma, hizmet veya şehir yazın">
                    <button class="px-7 py-4 text-sm font-black" style="background:var(--accent);color:var(--text)">Ara →</button>
                </form>
            </div>
            <div class="border border-white/30 bg-white/10 p-6 backdrop-blur-sm">
                <div class="mb-4 flex items-center justify-between border-b border-white/25 pb-3 text-xs font-bold uppercase tracking-widest"><span>Hızlı bağlantı</span><span>01 / 03</span></div>
                <div class="space-y-1">
                    <a href="{{ route('companies.index') }}" class="flex items-center justify-between py-3 text-xl font-bold">Firmalar <span aria-hidden="true">↗</span></a>
                    <a href="#signal-categories" class="flex items-center justify-between border-t border-white/20 py-3 text-xl font-bold">Kategoriler <span aria-hidden="true">↗</span></a>
                    <a href="#signal-cities" class="flex items-center justify-between border-t border-white/20 py-3 text-xl font-bold">Şehirler <span aria-hidden="true">↗</span></a>
                </div>
            </div>
        </div>
    </section>

    <div class="mx-auto grid gap-6 px-4 py-10 sm:px-6 lg:grid-cols-[230px_minmax(0,1fr)] lg:px-8" style="max-width:var(--page_width)">
        <aside id="signal-categories" class="self-start border p-5 lg:sticky lg:top-20" style="background:var(--bg_card);border-color:var(--border)">
            <h2 class="mb-3 text-xs font-black uppercase tracking-widest" style="color:var(--secondary)">Hizmet hatları</h2>
            <nav aria-label="Kategoriler" class="divide-y" style="border-color:var(--border)">
                @foreach($categories->take(10) as $index => $category)
                    <a class="signal-link flex items-center gap-3 py-3 text-sm font-bold" href="{{ route('categories.show', $category->slug) }}"><span class="text-xs" style="color:var(--secondary)">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span><span class="min-w-0 flex-1 truncate">{{ $category->name }}</span><span aria-hidden="true">›</span></a>
                @endforeach
            </nav>
        </aside>
        <div class="min-w-0 space-y-10">
            <section aria-labelledby="signal-featured">
                <div class="mb-5 flex items-end justify-between gap-3"><div><p class="text-xs font-black uppercase tracking-widest" style="color:var(--secondary)">Öne çıkan bağlantılar</p><h2 id="signal-featured" class="mt-1 text-3xl font-black" style="font-family:var(--font_heading)">Firmalara doğrudan ulaşın</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-bold underline">Tüm firmalar</a></div>
                <div class="grid gap-3 sm:grid-cols-2">
                    @forelse($signalCompanies as $company)
                        <article class="flex min-h-44 flex-col justify-between border p-5" style="background:var(--bg_card);border-color:var(--border);box-shadow:var(--card_shadow)">
                            <div class="flex items-start justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-wider" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }}</p><h3 class="mt-2 text-xl font-black">{{ $company->name }}</h3></div><span class="flex h-11 w-11 shrink-0 items-center justify-center text-lg font-black" style="background:var(--primary_light);color:var(--primary)">{{ mb_substr($company->name, 0, 1) }}</span></div>
                            <div class="mt-5 flex items-center justify-between border-t pt-3 text-sm" style="border-color:var(--border)"><span style="color:var(--text_muted)">{{ $company->city->name ?? 'Türkiye' }}</span><a class="font-black" style="color:var(--primary)" href="{{ route('companies.show', $company->slug) }}">Profili aç ↗</a></div>
                        </article>
                    @empty
                        <p class="border p-8 text-sm sm:col-span-2" style="border-color:var(--border)">Henüz firma kaydı bulunmuyor.</p>
                    @endforelse
                </div>
            </section>
            <section id="signal-cities" aria-labelledby="signal-city-heading"><h2 id="signal-city-heading" class="mb-4 text-2xl font-black" style="font-family:var(--font_heading)">Şehir bağlantıları</h2><div class="grid gap-px border sm:grid-cols-2 md:grid-cols-3" style="background:var(--border);border-color:var(--border)">@foreach($cities->take(9) as $city)<a href="{{ route('cities.show', $city->slug) }}" class="signal-link flex items-center justify-between p-4 text-sm font-bold" style="background:var(--bg_card)"><span>{{ $city->name }}</span><span style="color:var(--secondary)">{{ $city->companies_count }}</span></a>@endforeach</div></section>
        </div>
    </div>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
