@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Güven Endeksi Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Doğrulanmış firma bilgileri, onaylı kullanıcı yorumları ve profil doluluk puanlarıyla işletmeleri karşılaştırın.')

@section('content')
@php
    $trustList = $trustedCompanies->isNotEmpty() ? $trustedCompanies : $latestCompanies->take(8);
    $trustHero = $directory?->hero_image
        ? asset('storage/'.$directory->hero_image)
        : asset('images/themes/trust-index-hero.png');
@endphp

<main style="background:var(--bg);">
    <section class="relative isolate overflow-hidden border-b" style="border-color:var(--border);background:var(--bg_card);">
        <img src="{{ $trustHero }}" alt="{{ $settings->site_name ?? 'Firma değerlendirmesi' }}" class="absolute inset-0 -z-20 h-full w-full object-cover object-right" width="1920" height="1080" fetchpriority="high">
        <div class="absolute inset-0 -z-10" style="background:linear-gradient(90deg,rgba(255,255,255,.98) 0%,rgba(255,255,255,.92) 46%,rgba(255,255,255,.45) 68%,rgba(255,255,255,.08) 100%);"></div>
        <div class="mx-auto grid min-h-[530px] items-center gap-10 px-4 py-14 sm:px-6 lg:grid-cols-[minmax(0,1fr)_390px] lg:px-8" style="max-width:var(--page_width,1200px);">
            <div>
                <div class="inline-flex items-center gap-2 rounded-full border px-3 py-2 text-xs font-black uppercase tracking-[0.18em]" style="border-color:color-mix(in srgb,var(--primary) 24%,transparent);background:color-mix(in srgb,var(--primary) 8%,white);color:var(--primary);">
                    <span class="h-2 w-2 rounded-full" style="background:var(--primary);"></span> Şeffaf firma karşılaştırması
                </div>
                <h1 class="mt-5 max-w-3xl text-4xl font-black leading-tight sm:text-6xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Güvenebileceğiniz firmayı verilerle bulun' }}</h1>
                <p class="mt-5 max-w-2xl text-base leading-8" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Doğrulanmış bilgiler, gerçek yorumlar ve güncel profil verileriyle daha bilinçli seçim yapın.' }}</p>
                <form action="{{ route('search') }}" method="GET" class="mt-8 flex max-w-2xl flex-col gap-2 rounded-2xl border bg-white p-3 shadow-xl sm:flex-row" style="border-color:var(--border);">
                    <label class="sr-only" for="trust-search">Firma veya hizmet ara</label>
                    <input id="trust-search" name="q" class="min-h-14 min-w-0 flex-1 rounded-xl border px-5 text-sm outline-none" placeholder="Firma veya hizmet ara..." style="border-color:var(--border);color:var(--text);">
                    <button class="min-h-14 rounded-xl px-7 text-sm font-black" style="background:var(--primary);color:#fff;">Karşılaştır</button>
                </form>
            </div>
            <div class="grid grid-cols-2 gap-px overflow-hidden rounded-2xl border bg-white/50 shadow-2xl backdrop-blur-sm" style="border-color:var(--border);">
                <div class="p-5 sm:p-6" style="background:rgba(255,255,255,.91);"><strong class="text-3xl" style="color:var(--primary);">{{ $trustedCompanies->count() }}</strong><span class="mt-1 block text-xs font-semibold" style="color:var(--text_muted);">Doğrulanmış firma</span></div>
                <div class="p-5 sm:p-6" style="background:rgba(255,255,255,.91);"><strong class="text-3xl" style="color:var(--primary);">{{ $trustList->sum('approved_reviews_count') }}</strong><span class="mt-1 block text-xs font-semibold" style="color:var(--text_muted);">Onaylı yorum</span></div>
                <div class="col-span-2 p-5 sm:p-6" style="background:rgba(255,255,255,.94);"><strong class="text-lg" style="color:var(--text);">Puan nasıl oluşur?</strong><p class="mt-2 text-xs leading-6" style="color:var(--text_muted);">Profil doluluğu %45, kullanıcı puanı %35, admin doğrulaması %20 ağırlığındadır.</p></div>
            </div>
        </div>
    </section>

    <section class="py-12"><div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1200px);"><div class="mb-8 flex items-end justify-between gap-4"><div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Güven sıralaması</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Öne çıkan firma profilleri</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--primary);">Tümünü gör</a></div><div class="grid gap-5 md:grid-cols-2">
        @foreach($trustList as $company)
            @php
                $profileScore = $company->profileCompletionScore();
                $ratingScore = $company->reviews_avg_rating ? ((float)$company->reviews_avg_rating / 5) * 100 : 0;
                $trustScore = min(100, (int) round(($profileScore * .45) + ($ratingScore * .35) + ($company->is_verified ? 20 : 0)));
            @endphp
            <article class="rounded-lg border p-5 shadow-sm" style="border-color:var(--border);background:var(--bg_card);"><div class="flex gap-4"><a href="{{ route('companies.show',$company->slug) }}" class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-md text-xl font-black text-white" style="background:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full bg-white object-contain p-1">@else{{ mb_substr($company->name,0,1) }}@endif</a><div class="min-w-0 flex-1"><div class="flex flex-wrap items-center gap-2"><a href="{{ route('companies.show',$company->slug) }}" class="truncate font-black" style="color:var(--text);">{{ $company->name }}</a>@if($company->is_verified)<span class="rounded-full px-2 py-1 text-[10px] font-black text-white" style="background:var(--primary);">DOĞRULANDI</span>@endif</div><p class="mt-1 text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p></div><div class="text-right"><strong class="text-3xl" style="color:var(--primary);">{{ $trustScore }}</strong><span class="block text-[10px] font-black" style="color:var(--text_muted);">GÜVEN</span></div></div><div class="mt-5 h-2 overflow-hidden rounded-full" style="background:var(--primary_light);"><div class="h-full rounded-full" style="width:{{ $trustScore }}%;background:var(--primary);"></div></div><div class="mt-4 grid grid-cols-3 divide-x text-center" style="border-color:var(--border);"><div><strong class="block text-sm" style="color:var(--text);">{{ $company->reviews_avg_rating ? number_format((float)$company->reviews_avg_rating,1) : '–' }}</strong><span class="text-[10px]" style="color:var(--text_muted);">Puan</span></div><div><strong class="block text-sm" style="color:var(--text);">{{ $company->approved_reviews_count }}</strong><span class="text-[10px]" style="color:var(--text_muted);">Yorum</span></div><div><strong class="block text-sm" style="color:var(--text);">%{{ $profileScore }}</strong><span class="text-[10px]" style="color:var(--text_muted);">Profil</span></div></div><div class="mt-4 flex items-center justify-between border-t pt-4 text-xs" style="border-color:var(--border);color:var(--text_muted);"><span>Güncelleme: {{ $company->updated_at->format('d.m.Y') }}</span><a href="{{ route('companies.show',$company->slug) }}" class="font-black" style="color:var(--primary);">Profili incele</a></div></article>
        @endforeach
    </div></div></section>

    <section class="border-y py-12" style="border-color:var(--border);background:var(--bg_card);"><div class="mx-auto grid gap-5 px-4 sm:px-6 md:grid-cols-3 lg:px-8" style="max-width:var(--page_width,1200px);"><div class="border-t-4 p-5" style="border-color:var(--primary);background:var(--bg);"><h3 class="font-black" style="color:var(--text);">Doğrulanmış bilgiler</h3><p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">Admin kontrolünden geçen firma profilleri açıkça işaretlenir.</p></div><div class="border-t-4 p-5" style="border-color:var(--accent);background:var(--bg);"><h3 class="font-black" style="color:var(--text);">Onaylı yorumlar</h3><p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">Yalnızca moderasyondan geçen değerlendirmeler puana katılır.</p></div><div class="border-t-4 p-5" style="border-color:#16a34a;background:var(--bg);"><h3 class="font-black" style="color:var(--text);">Güncel profiller</h3><p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">Profil doluluğu ve son güncelleme tarihi kullanıcıya gösterilir.</p></div></div></section>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
