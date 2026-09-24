@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki işletmeleri ve hizmetleri keşfedin.')

@push('head')
<style>
    .paper-trail { background-image: repeating-linear-gradient(0deg,transparent,transparent 5px,rgba(67,52,31,.018) 6px); }
    .paper-trail .paper-rule { border-color:var(--text); }
    .paper-trail .paper-entry:hover { background:var(--primary_light); }
</style>
@endpush

@section('content')
<main class="paper-trail py-6 sm:py-12" style="background-color:var(--bg)">
    <div class="mx-auto px-4 sm:px-6" style="max-width:var(--page_width)">
        <section class="paper-rule border-y-4 py-5 text-center">
            <div class="flex flex-wrap justify-between gap-2 text-xs font-bold uppercase tracking-[.22em]" style="color:var(--text_muted)"><span>Yerel işletme kayıtları</span><span>{{ now()->translatedFormat('d F Y') }}</span></div>
            <h1 class="mx-auto mt-6 max-w-4xl text-4xl font-bold leading-tight sm:text-6xl" style="font-family:var(--font_heading)">{{ $settings->homepage_title ?? 'Şehrin iş ve hizmet rehberi' }}</h1>
            <p class="mx-auto mt-4 max-w-2xl text-base leading-7" style="color:var(--text_muted)">{{ $settings->homepage_subtitle ?? 'İşletmeleri, hizmetleri ve şehirleri kendi ritminizde inceleyin.' }}</p>
            <form action="{{ route('search') }}" method="GET" role="search" class="mx-auto mt-6 flex max-w-xl border-b-2 pb-2" style="border-color:var(--text)"><label for="paper-search" class="sr-only">Rehberde ara</label><input id="paper-search" name="q" class="min-w-0 flex-1 bg-transparent px-2 py-2 text-base outline-none" placeholder="Rehberde ara: firma, hizmet, şehir"><button class="px-4 text-sm font-bold uppercase tracking-wider" style="color:var(--primary)">Bul →</button></form>
        </section>

        <div class="grid gap-8 py-8 lg:grid-cols-[minmax(0,1.6fr)_minmax(260px,.7fr)]">
            <section aria-labelledby="paper-firms">
                <div class="flex items-end justify-between border-b-2 pb-2" style="border-color:var(--text)"><div><p class="text-xs font-bold uppercase tracking-widest" style="color:var(--primary)">Rehber / 01</p><h2 id="paper-firms" class="text-3xl font-bold">İşletme kayıtları</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-bold underline">Tüm kayıtlar</a></div>
                <div class="divide-y" style="border-color:var(--border)">
                    @forelse($latestCompanies->take(8) as $index => $company)
                        <article class="paper-entry grid gap-3 py-5 sm:grid-cols-[42px_minmax(0,1fr)_auto] sm:items-start">
                            <span class="text-lg font-bold" style="color:var(--primary)">{{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                            <div><p class="text-xs font-bold uppercase tracking-widest" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }} · {{ $company->city->name ?? 'Türkiye' }}</p><h3 class="mt-1 text-2xl font-bold"><a href="{{ route('companies.show', $company->slug) }}">{{ $company->name }}</a></h3>@if($company->short_description)<p class="mt-2 line-clamp-2 text-sm leading-6" style="color:var(--text_muted)">{{ $company->short_description }}</p>@endif</div>
                            <a href="{{ route('companies.show', $company->slug) }}" class="self-end whitespace-nowrap text-sm font-bold underline">İncele ↗</a>
                        </article>
                    @empty
                        <p class="py-8 text-sm" style="color:var(--text_muted)">Henüz işletme kaydı yok.</p>
                    @endforelse
                </div>
            </section>
            <aside class="space-y-8">
                <section class="border p-5" style="background:var(--bg_card);border-color:var(--border)"><p class="text-xs font-bold uppercase tracking-widest" style="color:var(--primary)">Rehber / 02</p><h2 class="mt-1 border-b-2 pb-3 text-2xl font-bold" style="border-color:var(--text)">Hizmet dizini</h2><div class="mt-2 divide-y" style="border-color:var(--border)">@foreach($categories->take(9) as $category)<a class="flex justify-between gap-3 py-3 text-sm" href="{{ route('categories.show', $category->slug) }}"><strong>{{ $category->name }}</strong><span style="color:var(--text_muted)">{{ $category->companies_count }}</span></a>@endforeach</div></section>
                <section class="border p-5" style="background:var(--bg_card);border-color:var(--border)"><p class="text-xs font-bold uppercase tracking-widest" style="color:var(--primary)">Rehber / 03</p><h2 class="mt-1 border-b-2 pb-3 text-2xl font-bold" style="border-color:var(--text)">Şehirler</h2><div class="mt-3 flex flex-wrap gap-2">@foreach($cities->take(10) as $city)<a href="{{ route('cities.show', $city->slug) }}" class="border px-3 py-2 text-sm font-bold" style="border-color:var(--border)">{{ $city->name }}</a>@endforeach</div></section>
                <a href="{{ route('owner.register') }}" class="block p-6" style="background:var(--secondary);color:white"><span class="text-xs font-bold uppercase tracking-widest">İşletme sahipleri</span><strong class="mt-2 block text-2xl">Rehberde yerinizi alın</strong><span class="mt-4 inline-block border-b border-white pb-1 text-sm font-bold">Firma ekle →</span></a>
            </aside>
        </div>
    </div>
    @include('partials.blog-section')
</main>
@endsection
