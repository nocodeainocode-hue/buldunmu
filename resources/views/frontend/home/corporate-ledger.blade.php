@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Kurumsal Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehir ve sektöre göre doğrulanabilir işletme bilgilerini inceleyin.')

@section('content')
@php
    $featuredCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(4) : $latestCompanies->take(4);
@endphp
<main style="background:var(--bg);">
    <section class="relative isolate overflow-hidden" style="min-height:540px;background:#142842;">
        <img src="{{ asset('images/themes/city-pulse-hero.png') }}" alt="Şehirdeki işletme bölgeleri" class="absolute inset-0 h-full w-full object-cover" width="2048" height="1152" loading="eager">
        <div class="absolute inset-0" style="background:linear-gradient(90deg,rgba(15,31,52,.94) 0%,rgba(15,31,52,.76) 45%,rgba(15,31,52,.24) 100%);"></div>
        <div class="relative mx-auto grid min-h-[540px] items-end gap-10 px-4 py-12 sm:px-6 lg:grid-cols-[1fr_360px] lg:px-8" style="max-width:var(--page_width);">
            <div class="pb-3 lg:pb-8">
                <div class="inline-flex border border-white/30 px-3 py-1.5 text-xs font-black uppercase tracking-[.18em] text-white">Kurumsal işletme dizini</div>
                <h1 class="mt-6 max-w-3xl text-4xl font-black leading-tight text-white sm:text-6xl">{{ $settings->homepage_title ?? 'İşletme kararları için sade ve güvenilir kaynak' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8 text-white/80">{{ $settings->homepage_subtitle ?? 'Sektör, şehir ve doğrulanabilir iletişim bilgileri üzerinden işletmeleri karşılaştırın.' }}</p>
                <form action="{{ route('search') }}" method="GET" class="mt-8 flex max-w-2xl flex-col gap-2 bg-white p-2 shadow-2xl sm:flex-row">
                    <input name="q" class="min-h-12 min-w-0 flex-1 border-0 px-4 text-sm outline-none" placeholder="Firma, sektör veya şehir ara" style="color:var(--text);">
                    <button class="px-7 py-3 text-sm font-black text-white" style="background:var(--accent);">Kayıtlarda ara</button>
                </form>
            </div>
            <aside class="border bg-white p-5 shadow-2xl" style="border-color:rgba(255,255,255,.22);">
                <div class="border-b pb-3 text-xs font-black uppercase tracking-widest" style="border-color:var(--border);color:var(--primary);">Öne çıkan kayıtlar</div>
                <div class="divide-y" style="border-color:var(--border);">
                    @forelse($featuredCompanies as $company)
                        <a href="{{ route('companies.show', $company->slug) }}" class="flex items-center justify-between gap-3 py-4">
                            <span class="min-w-0"><strong class="block truncate text-sm" style="color:var(--text);">{{ $company->name }}</strong><small class="block truncate" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</small></span>
                            <span class="shrink-0 text-xs font-black" style="color:var(--accent);">İNCELE</span>
                        </a>
                    @empty
                        <p class="py-6 text-sm" style="color:var(--text_muted);">Yeni işletme kayıtları burada yer alacak.</p>
                    @endforelse
                </div>
            </aside>
        </div>
    </section>

    <section class="border-b py-8" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto grid grid-cols-2 gap-5 px-4 text-center sm:grid-cols-4 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
            <div><strong class="block text-3xl" style="color:var(--primary);">{{ $categories->count() }}</strong><span class="text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Sektör</span></div>
            <div><strong class="block text-3xl" style="color:var(--primary);">{{ $cities->count() }}</strong><span class="text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Şehir</span></div>
            <div><strong class="block text-3xl" style="color:var(--primary);">{{ $premiumCompanies->count() }}</strong><span class="text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Öne çıkan</span></div>
            <div><a href="{{ route('owner.register') }}" class="inline-flex border px-4 py-2 text-sm font-black" style="border-color:var(--primary);color:var(--primary);">Firma ekle</a><span class="mt-1 block text-xs" style="color:var(--text_muted);">Ücretsiz başvuru</span></div>
        </div>
    </section>

    <section class="py-14">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
            <div class="mb-7 flex items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Sektör seçimi</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">İş alanına göre firmalar</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Tüm kayıtlar</a></div>
            <div class="grid border-t border-l sm:grid-cols-2 lg:grid-cols-4" style="border-color:var(--border);">
                @foreach($categories as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="group border-b border-r p-5 transition hover:bg-white" style="border-color:var(--border);"><span class="text-xs font-black" style="color:var(--accent);">{{ str_pad((string) ($loop->index + 1), 2, '0', STR_PAD_LEFT) }}</span><strong class="mt-7 block text-lg" style="color:var(--text);">{{ $category->name }}</strong><small class="mt-2 block" style="color:var(--text_muted);">{{ $category->companies_count }} kayıt</small></a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="border-y py-14" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width);"><div class="mb-7 flex items-end justify-between"><div><div class="text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Yeni dosyalar</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Rehbere yeni katılan işletmeler</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Listeyi aç</a></div><div class="grid gap-4 md:grid-cols-2">@foreach($latestCompanies->take(6) as $company)<a href="{{ route('companies.show',$company->slug) }}" class="flex gap-4 border bg-white p-5 transition hover:shadow-lg" style="border-color:var(--border);"><div class="flex h-12 w-12 shrink-0 items-center justify-center font-black text-white" style="background:var(--primary);">{{ mb_substr($company->name,0,1) }}</div><div class="min-w-0"><h3 class="truncate font-black" style="color:var(--text);">{{ $company->name }}</h3><p class="mt-1 text-sm" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p></div></a>@endforeach</div></div>
    </section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
