@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory->name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki işletmeleri ve hizmetleri keşfedin.')

@push('head')
<style>
    .light-garden { overflow: hidden; background: var(--bg); color: var(--text); }
    .light-garden__hero { position: relative; isolation: isolate; background: radial-gradient(circle at 75% 20%,rgba(123,212,147,.13),transparent 31%),linear-gradient(140deg,#0d302c,#071d1b 68%); }
    .light-garden__hero::before { content: ''; position: absolute; inset: 0; z-index: -1; background-image: radial-gradient(circle,rgba(185,246,163,.24) 1px,transparent 1px); background-size: 38px 38px; mask-image: linear-gradient(90deg,transparent,#000); }
    .light-garden__dome { position: relative; width: min(100%,430px); aspect-ratio: 1; margin: auto; overflow: hidden; border: 1px solid rgba(191,241,184,.28); border-radius: 50% 50% 9% 9%; background: radial-gradient(circle at 50% 58%,rgba(129,233,152,.23),transparent 49%),linear-gradient(180deg,rgba(145,216,181,.07),rgba(213,253,147,.13)); box-shadow: inset 0 0 70px rgba(175,245,174,.08),0 22px 90px rgba(0,0,0,.22); }
    .light-garden__dome::before { content: ''; position: absolute; inset: 0; opacity: .38; background: repeating-linear-gradient(90deg,transparent 0,transparent 55px,rgba(197,241,185,.33) 56px,transparent 57px); }
    .light-garden__dome::after { content: ''; position: absolute; left: 8%; right: 8%; bottom: 14%; height: 2px; background: #bff3aa; box-shadow: 0 0 18px #c3f7a2,0 18px 0 #477966; }
    .light-garden__stem { position: absolute; bottom: 14%; left: 50%; width: 4px; height: 58%; border-radius: 6px; background: linear-gradient(#d5ff9a,#62ac8a); transform-origin: bottom center; box-shadow: 0 0 18px #97e9a0; }
    .light-garden__stem:nth-child(1) { transform: rotate(-38deg); height: 48%; }
    .light-garden__stem:nth-child(2) { transform: rotate(-17deg); height: 65%; }
    .light-garden__stem:nth-child(3) { transform: rotate(8deg); height: 70%; }
    .light-garden__stem:nth-child(4) { transform: rotate(33deg); height: 52%; }
    .light-garden__stem::after { content: ''; position: absolute; top: -26px; left: -25px; width: 54px; height: 54px; border-radius: 5% 90% 5% 90%; background: linear-gradient(145deg,#ecffad,#7ddab0); transform: rotate(45deg); box-shadow: 0 0 34px rgba(178,248,150,.65); }
    .light-garden__path { position: relative; padding-left: 34px; border-left: 1px solid var(--border); }
    .light-garden__path::before { content: ''; position: absolute; top: 25px; left: -6px; width: 11px; height: 11px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 18px var(--accent); }
    .light-garden__bed { border: 1px solid var(--border); border-radius: 24px 24px 9px 9px; background: linear-gradient(145deg,#164039,#0d2926); transition: transform .2s ease,border-color .2s ease; }
    .light-garden__bed:hover { transform: translateY(-4px); border-color: var(--primary); }
    .light-garden__firm { position: relative; border: 1px solid var(--border); border-radius: 24px; background: var(--bg_card); transition: border-color .2s ease,transform .2s ease; }
    .light-garden__firm:hover { border-color: var(--accent); transform: translateY(-4px); }
    .light-garden__firm::before { content: ''; position: absolute; top: 25px; right: 26px; width: 8px; height: 8px; border-radius: 50%; background: var(--accent); box-shadow: 0 0 18px var(--accent); }
    @media (prefers-reduced-motion: reduce) { .light-garden__bed,.light-garden__firm { transition: none; } }
</style>
@endpush

@section('content')
<main class="light-garden">
    <section class="light-garden__hero border-b" style="border-color:var(--border)">
        <div class="mx-auto grid min-h-[590px] items-center gap-12 px-4 py-16 sm:px-6 lg:grid-cols-[minmax(0,1fr)_minmax(310px,.85fr)] lg:px-8" style="max-width:var(--page_width)">
            <div>
                <p class="text-xs font-black uppercase tracking-[.3em]" style="color:var(--accent)">Yaşayan bir kent rehberi</p>
                <h1 class="mt-6 max-w-3xl text-5xl leading-[1.1] sm:text-7xl" style="font-family:var(--font_heading)">{{ $settings->homepage_title ?? 'İyi işletmeler burada büyür.' }}</h1>
                <p class="mt-7 max-w-xl text-base leading-8" style="color:var(--text_muted)">{{ $settings->homepage_subtitle ?? 'Şehrin üretken insanlarını, hizmetlerini ve işletmelerini keşfedin.' }}</p>
                <form action="{{ route('search') }}" method="GET" role="search" class="mt-9 flex max-w-xl flex-col gap-3 sm:flex-row">
                    <label for="garden-search" class="sr-only">Firma veya hizmet ara</label>
                    <input id="garden-search" name="q" class="min-w-0 flex-1 rounded-2xl border px-5 py-4 text-sm outline-none focus:ring-2" style="background:var(--bg_card);border-color:var(--border);color:var(--text)" placeholder="Bir işletme veya hizmet ara">
                    <button type="submit" class="rounded-2xl px-7 py-4 text-sm font-black" style="background:var(--accent);color:#092720">Keşfet ↗</button>
                </form>
                <a href="{{ route('owner.register') }}" class="mt-7 inline-block border-b pb-1 text-sm font-bold" style="color:var(--accent);border-color:var(--accent)">İşletmeni bu bahçeye ekle →</a>
            </div>
            <div class="light-garden__dome" aria-hidden="true"><span class="light-garden__stem"></span><span class="light-garden__stem"></span><span class="light-garden__stem"></span><span class="light-garden__stem"></span></div>
        </div>
    </section>

    <section class="mx-auto px-4 py-16 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
        <div class="light-garden__path"><p class="text-xs font-black uppercase tracking-[.24em]" style="color:var(--accent)">01 / İlgi alanları</p><h2 class="mt-2 text-4xl sm:text-5xl" style="font-family:var(--font_heading)">Hangi alanda büyüyelim?</h2><p class="mt-3 max-w-xl text-sm leading-7" style="color:var(--text_muted)">Bir hizmet seçin ve o alandaki işletmeleri keşfedin.</p></div>
        <div class="mt-9 grid gap-4 sm:grid-cols-2 lg:grid-cols-4">
            @forelse($categories->take(12) as $category)
                <a href="{{ route('categories.show',$category->slug) }}" class="light-garden__bed flex min-h-40 flex-col justify-between p-6"><span class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--secondary)">Alan {{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span><span><strong class="block text-lg leading-snug">{{ $category->name }}</strong><small class="mt-2 block" style="color:var(--text_muted)">{{ $category->companies_count }} işletme <span style="color:var(--accent)">↗</span></small></span></a>
            @empty<p style="color:var(--text_muted)">Kategoriler yakında burada olacak.</p>@endforelse
        </div>
    </section>

    <section class="border-y py-16" style="border-color:var(--border);background:#0b2825">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
            <div class="light-garden__path"><p class="text-xs font-black uppercase tracking-[.24em]" style="color:var(--accent)">02 / Yeni filizler</p><h2 class="mt-2 text-4xl sm:text-5xl" style="font-family:var(--font_heading)">Yeni işletmeler</h2></div>
            <div class="mt-9 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
                @forelse($premiumCompanies->concat($latestCompanies)->unique('id')->take(6) as $company)
                    <article class="light-garden__firm flex min-h-60 flex-col p-7"><p class="pr-8 text-xs font-black uppercase tracking-[.18em]" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }} · {{ $company->city->name ?? 'Türkiye' }}</p><h3 class="mt-7 text-2xl leading-snug" style="font-family:var(--font_heading)">{{ $company->name }}</h3><p class="mt-3 line-clamp-2 flex-1 text-sm leading-6" style="color:var(--text_muted)">{{ $company->short_description ?: 'İşletmenin hizmet ve iletişim bilgilerini keşfedin.' }}</p><a href="{{ route('companies.show',$company->slug) }}" class="mt-6 self-start border-b pb-1 text-sm font-black" style="border-color:var(--accent);color:var(--accent)">Profili gör ↗</a></article>
                @empty<p style="color:var(--text_muted)">İlk işletmeler burada filizlenecek.</p>@endforelse
            </div>
        </div>
    </section>

    <section class="mx-auto px-4 py-16 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
        <div class="light-garden__path"><p class="text-xs font-black uppercase tracking-[.24em]" style="color:var(--accent)">03 / Yaşayan şehirler</p><h2 class="mt-2 text-4xl sm:text-5xl" style="font-family:var(--font_heading)">Yakınınızdaki hayat</h2></div>
        <div class="mt-8 flex flex-wrap gap-3">@forelse($cities->take(12) as $city)<a href="{{ route('cities.show',$city->slug) }}" class="rounded-full border px-5 py-3 text-sm font-bold transition hover:border-lime-200" style="background:var(--bg_card);border-color:var(--border)">{{ $city->name }} <span class="ml-2 text-xs" style="color:var(--accent)">{{ $city->companies_count }} ↗</span></a>@empty<p style="color:var(--text_muted)">Şehirler yakında burada olacak.</p>@endforelse</div>
        <div class="mt-14 flex flex-col items-start justify-between gap-5 rounded-3xl border p-8 sm:flex-row sm:items-center" style="background:var(--bg_card);border-color:var(--border)"><div><p class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--secondary)">Bu şehrin bir parçası olun</p><h2 class="mt-2 text-2xl" style="font-family:var(--font_heading)">İşletmeniz de burada büyüsün.</h2></div><a href="{{ route('owner.register') }}" class="rounded-full px-6 py-3 text-sm font-black" style="background:var(--accent);color:#092720">Ücretsiz firma kaydı →</a></div>
    </section>
    @include('partials.blog-section')
</main>
@endsection
