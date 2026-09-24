@extends('layouts.app')

@section('title', $settings->homepage_title ?? $directory->name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Şehrinizdeki işletmeleri ve hizmetleri keşfedin.')

@push('head')
<style>
    .sky-world { overflow: hidden; background: var(--bg); color: var(--text); }
    .sky-world__hero { position: relative; isolation: isolate; background: linear-gradient(145deg,#82d0ea 0%,#b8e8eb 46%,#f7f5db 100%); }
    .sky-world__hero::before { content: ''; position: absolute; inset: 0; z-index: -1; opacity: .46; background: radial-gradient(ellipse 180px 65px at 12% 22%,#fff 30%,transparent 70%),radial-gradient(ellipse 220px 75px at 79% 18%,#fff 28%,transparent 72%),radial-gradient(ellipse 290px 75px at 56% 87%,#fff 27%,transparent 73%); }
    .sky-world__sun { position: absolute; width: 240px; height: 240px; right: 8%; top: 5%; border-radius: 50%; background: #ffebae; box-shadow: 0 0 90px 40px rgba(255,234,177,.48); opacity: .9; }
    .sky-world__island { position: relative; display: flex; min-height: 175px; flex-direction: column; justify-content: center; align-items: center; padding: 24px; border-radius: 48% 52% 40% 44% / 48% 51% 42% 44%; background: linear-gradient(135deg,#fffef2 0%,#e1f4dc 76%); box-shadow: 0 25px 0 -3px #85b9ab,0 38px 0 -8px #548e87,0 48px 0 -14px #2e6c70,0 70px 48px rgba(19,87,97,.25); transform: rotate(-4deg); transition: transform .25s ease, box-shadow .25s ease; }
    .sky-world__island:nth-child(2) { transform: translateY(24px) rotate(5deg); background: linear-gradient(135deg,#fffaf0,#e7f4f7); }
    .sky-world__island:nth-child(3) { transform: translateY(-8px) rotate(-3deg); background: linear-gradient(135deg,#fff9ea,#f7dfce); }
    .sky-world__island:hover,.sky-world__island:focus-visible { transform: translateY(-7px) rotate(0); box-shadow: 0 25px 0 -3px #85b9ab,0 38px 0 -8px #548e87,0 48px 0 -14px #2e6c70,0 82px 54px rgba(19,87,97,.28); }
    .sky-world__route { border: 1px dashed var(--border); border-radius: 28px; background: rgba(255,255,255,.75); }
    .sky-world__firm { background: #fff; border: 1px solid var(--border); border-radius: 24px; transition: transform .2s ease,box-shadow .2s ease; }
    .sky-world__firm:hover { transform: translateY(-5px); box-shadow: var(--card_shadow); }
    @media (max-width: 767px) { .sky-world__sun { width: 140px; height: 140px; top: 3%; right: -10%; } .sky-world__island,.sky-world__island:nth-child(2),.sky-world__island:nth-child(3) { transform: none; min-height: 130px; border-radius: 40px; } .sky-world__island:hover { transform: translateY(-4px); } }
    @media (prefers-reduced-motion: reduce) { .sky-world__island,.sky-world__firm { transition: none; } }
</style>
@endpush

@section('content')
<main class="sky-world">
    <section class="sky-world__hero pb-20 pt-16 sm:pb-28 sm:pt-24">
        <div class="sky-world__sun" aria-hidden="true"></div>
        <div class="relative mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
            <div class="max-w-3xl">
                <p class="text-xs font-black uppercase tracking-[.28em]" style="color:var(--primary)">Yeni bir keşif dünyası</p>
                <h1 class="mt-5 text-5xl font-black leading-[1.05] sm:text-7xl" style="font-family:var(--font_heading)">{{ $settings->homepage_title ?? 'İyi işletmelerin yolu burada kesişir.' }}</h1>
                <p class="mt-6 max-w-xl text-base leading-7 sm:text-lg" style="color:var(--text_muted)">{{ $settings->homepage_subtitle ?? 'Hizmetleri, şehirleri ve işletmeleri kendi rotanızda keşfedin.' }}</p>
                <form action="{{ route('search') }}" method="GET" role="search" class="mt-9 flex max-w-2xl flex-col gap-2 rounded-[26px] border bg-white p-2 shadow-xl sm:flex-row" style="border-color:var(--border)">
                    <label for="sky-search" class="sr-only">Firma veya hizmet ara</label>
                    <input id="sky-search" name="q" class="min-w-0 flex-1 rounded-2xl px-5 py-3 text-sm outline-none" placeholder="Hangi işletmeyi arıyorsunuz?">
                    <button type="submit" class="rounded-2xl px-7 py-3 text-sm font-black text-white" style="background:var(--primary)">Rotayı çiz →</button>
                </form>
            </div>
            <div class="relative mt-16 grid gap-12 px-1 sm:grid-cols-3 sm:gap-5 lg:gap-12" aria-label="Keşif yolları">
                @foreach($categories->take(3) as $category)
                    <a href="{{ route('categories.show', $category->slug) }}" class="sky-world__island text-center">
                        <span class="text-xs font-black uppercase tracking-[.2em]" style="color:var(--secondary)">Keşif adası {{ str_pad($loop->iteration,2,'0',STR_PAD_LEFT) }}</span>
                        <strong class="mt-3 text-xl sm:text-2xl" style="font-family:var(--font_heading)">{{ $category->name }}</strong>
                        <span class="mt-2 text-xs font-bold" style="color:var(--text_muted)">{{ $category->companies_count }} işletme · İncele ↗</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="relative mx-auto px-4 py-16 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
        <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-black uppercase tracking-[.22em]" style="color:var(--secondary)">Keşif haritası / 01</p><h2 class="mt-2 text-3xl font-black sm:text-4xl" style="font-family:var(--font_heading)">Bir yol seçin</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black underline underline-offset-4">Tüm işletmeler →</a></div>
        <div class="mt-7 grid gap-3 md:grid-cols-2 lg:grid-cols-3">
            @foreach($categories->slice(3,9) as $category)
                <a href="{{ route('categories.show', $category->slug) }}" class="sky-world__route group flex min-h-24 items-center justify-between gap-4 px-6 py-5 transition hover:bg-white"><span class="text-lg font-bold">{{ $category->name }}</span><span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full text-xl transition group-hover:translate-x-1" style="background:var(--primary_light);color:var(--primary)">↗</span></a>
            @endforeach
        </div>
    </section>

    <section class="py-16" style="background:#d9eeed">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
            <p class="text-xs font-black uppercase tracking-[.22em]" style="color:var(--secondary)">Keşif haritası / 02</p><h2 class="mt-2 text-3xl font-black sm:text-4xl" style="font-family:var(--font_heading)">Şehirler arasında dolaşın</h2>
            <div class="mt-8 flex flex-wrap gap-3">@forelse($cities->take(10) as $city)<a href="{{ route('cities.show',$city->slug) }}" class="rounded-full border bg-white px-5 py-3 text-sm font-bold transition hover:-translate-y-1" style="border-color:var(--border)">{{ $city->name }} <span class="ml-2 text-xs" style="color:var(--text_muted)">{{ $city->companies_count }}</span></a>@empty<p style="color:var(--text_muted)">Şehirler yakında burada olacak.</p>@endforelse</div>
        </div>
    </section>

    <section class="mx-auto px-4 py-16 sm:px-6 lg:px-8" style="max-width:var(--page_width)">
        <div class="flex flex-wrap items-end justify-between gap-4"><div><p class="text-xs font-black uppercase tracking-[.22em]" style="color:var(--secondary)">Keşif haritası / 03</p><h2 class="mt-2 text-3xl font-black sm:text-4xl" style="font-family:var(--font_heading)">Yeni karşılaşmalar</h2></div><a href="{{ route('owner.register') }}" class="rounded-full px-5 py-3 text-sm font-black text-white" style="background:var(--primary)">İşletmeni ekle →</a></div>
        <div class="mt-8 grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($premiumCompanies->concat($latestCompanies)->unique('id')->take(6) as $company)
                <article class="sky-world__firm flex flex-col p-6"><div class="flex items-center justify-between gap-4"><span class="flex h-14 w-14 items-center justify-center rounded-2xl text-2xl font-black" style="background:var(--primary_light);color:var(--primary)">{{ mb_substr($company->name,0,1) }}</span><span class="text-xs font-bold" style="color:var(--text_muted)">{{ $company->city->name ?? 'Türkiye' }}</span></div><p class="mt-6 text-xs font-black uppercase tracking-[.16em]" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }}</p><h3 class="mt-2 text-xl font-black">{{ $company->name }}</h3><p class="mt-3 line-clamp-2 flex-1 text-sm leading-6" style="color:var(--text_muted)">{{ $company->short_description ?: 'İletişim ve hizmet bilgilerini profilde inceleyin.' }}</p><a href="{{ route('companies.show',$company->slug) }}" class="mt-5 border-t pt-4 text-sm font-black" style="border-color:var(--border);color:var(--primary)">Profili keşfet ↗</a></article>
            @empty<p style="color:var(--text_muted)">İlk işletmeler burada yerini alacak.</p>@endforelse
        </div>
    </section>
    @include('partials.blog-section')
</main>
@endsection
