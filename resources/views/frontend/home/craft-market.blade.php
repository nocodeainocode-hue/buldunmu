@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Usta Pazarı')
@section('meta_description', $settings->meta_description ?? 'Usta, servis ve yerel hizmet firmalarını kategori ve şehir bazında keşfedin.')

@section('content')
@php $craftCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies : $latestCompanies->take(6); @endphp
<main style="background:var(--bg);">
    <section class="border-b bg-white" style="border-color:var(--border);">
        <div class="mx-auto grid gap-8 px-4 py-12 sm:px-6 lg:grid-cols-[1.05fr_0.95fr] lg:px-8" style="max-width:var(--page_width);">
            <div class="flex flex-col justify-center">
                <div class="text-xs font-black uppercase tracking-[0.22em]" style="color:var(--primary);">Yerel emek · gerçek işletmeler</div>
                <h1 class="mt-4 font-serif text-5xl font-black leading-[1.05] sm:text-6xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'İşinin ustasını doğrudan bulun' }}</h1>
                <p class="mt-5 max-w-xl text-base leading-7" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Tamirden danışmanlığa, sağlıktan bakıma kadar şehrinizde hizmet veren firmalara ulaşın.' }}</p>
                <form action="{{ route('search') }}" class="mt-7 grid max-w-2xl gap-2 sm:grid-cols-[1fr_auto]">
                    <input name="q" class="rounded-sm border bg-white px-5 py-4 text-sm outline-none" style="border-color:var(--border);" placeholder="Hangi ustayı veya hizmeti arıyorsunuz?">
                    <button class="px-7 py-4 text-sm font-black text-white" style="background:var(--primary);">Usta bul</button>
                </form>
            </div>
            <div class="grid min-h-[380px] grid-cols-2 gap-3">
                @foreach($categories->take(6) as $index => $category)
                    <a href="{{ route('categories.show',$category->slug) }}" class="group flex flex-col justify-between border p-5 transition hover:-translate-y-1 {{ $index === 0 ? 'col-span-2' : '' }}" style="border-color:var(--border);background:{{ $index === 0 ? 'var(--primary)' : 'var(--bg_card)' }};color:{{ $index === 0 ? '#fff' : 'var(--text)' }};">
                        <span class="text-xs font-black uppercase tracking-wider" style="{{ $index === 0 ? 'color:rgba(255,255,255,.7)' : 'color:var(--text_muted)' }}">Hizmet {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}</span>
                        <span><strong class="font-serif text-2xl">{{ $category->name }}</strong><small class="mt-1 block" style="{{ $index === 0 ? 'color:rgba(255,255,255,.75)' : 'color:var(--text_muted)' }}">{{ $category->companies_count }} firma</small></span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <section class="mx-auto px-4 py-14 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
        <div class="mb-7 flex flex-wrap items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--secondary);">Öne çıkan işçilik</div><h2 class="mt-2 font-serif text-4xl font-black" style="color:var(--text);">Tavsiye edilen firmalar</h2></div><a href="{{ route('companies.index') }}" class="border-b-2 pb-1 text-sm font-black" style="border-color:var(--primary);color:var(--primary);">Bütün firmalar</a></div>
        <div class="grid gap-5 md:grid-cols-2 lg:grid-cols-3">
            @forelse($craftCompanies as $company)
                <article class="overflow-hidden border bg-white" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <div class="flex h-28 items-center justify-center border-b p-5" style="border-color:var(--border);background:var(--primary_light);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full max-w-full object-contain">@else<span class="font-serif text-5xl font-black" style="color:var(--primary);">{{ mb_substr($company->name,0,1) }}</span>@endif</div>
                    <div class="p-5"><div class="text-xs font-black uppercase tracking-wider" style="color:var(--secondary);">{{ $company->category->name ?? 'Hizmet' }}</div><h3 class="mt-2 font-serif text-2xl font-black" style="color:var(--text);">{{ $company->name }}</h3><p class="mt-2 line-clamp-2 text-sm leading-6" style="color:var(--text_muted);">{{ $company->short_description ?: ($company->city->name ?? 'Türkiye').' bölgesinde hizmet veren işletme.' }}</p><div class="mt-5 flex items-center justify-between border-t pt-4" style="border-color:var(--border);"><span class="text-xs font-bold" style="color:var(--text_muted);">{{ $company->city->name ?? '' }}</span><a href="{{ route('companies.show',$company->slug) }}" class="text-sm font-black" style="color:var(--primary);">Profili aç</a></div></div>
                </article>
            @empty
                <div class="col-span-full border p-12 text-center text-sm" style="border-color:var(--border);color:var(--text_muted);">Henüz firma eklenmedi.</div>
            @endforelse
        </div>
    </section>

    <section class="border-y bg-white py-12" style="border-color:var(--border);">
        <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:px-8" style="max-width:var(--page_width);">
            <div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--primary);">Bölgenizde ara</div><h2 class="mt-2 font-serif text-4xl font-black" style="color:var(--text);">Şehir şehir hizmet ağı</h2><p class="mt-4 text-sm leading-7" style="color:var(--text_muted);">Yakınınızdaki işletmeleri şehir sayfalarından inceleyin.</p></div>
            <div class="grid gap-px border sm:grid-cols-2 lg:grid-cols-3" style="border-color:var(--border);background:var(--border);">@foreach($cities->take(9) as $city)<a href="{{ route('cities.show',$city->slug) }}" class="bg-white p-4"><strong class="font-serif text-lg" style="color:var(--text);">{{ $city->name }}</strong><small class="mt-1 block" style="color:var(--text_muted);">{{ $city->companies_count }} işletme</small></a>@endforeach</div>
        </div>
    </section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
