@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('content')

@include('partials.heroes.gradient', [
    'title' => $settings->homepage_title ?? 'İhtiyacınız olan firmayı hızlıca bulun',
    'subtitle' => $settings->homepage_subtitle ?? 'Türkiye genelinde güvenilir işletmeleri kategori, şehir ve firma adına göre arayın.'
])

@if($categories->isNotEmpty())
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Kategoriler</div>
                <h2 class="text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Ne arıyorsunuz?</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">Sektöre göre en uygun firmaları hızlıca listeleyin.</p>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tum firmalar -></a>
        </div>

        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show',$cat->slug) }}" class="group rounded-2xl border bg-white p-4 shadow-sm transition hover:-translate-y-1 hover:shadow-lg" style="border-color:var(--border);">
                <div class="mb-3 flex h-11 w-11 items-center justify-center rounded-xl text-2xl" style="background:var(--primary_light);">{{ $cat->icon ?? '#' }}</div>
                <div class="truncate text-sm font-black" style="color:var(--text);">{{ $cat->name }}</div>
                <div class="mt-1 text-xs" style="color:var(--text_muted);">{{ $cat->companies_count }} firma</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@if($premiumCompanies->isNotEmpty())
<section class="py-16" style="background:var(--bg_card);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Sponsorlu</div>
                <h2 class="text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Öne çıkan firmalar</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">Daha fazla görünürlük alan premium işletmeler.</p>
            </div>
            <span class="w-fit rounded-full px-4 py-2 text-xs font-black uppercase tracking-wide text-white" style="background:var(--accent);">Premium vitrin</span>
        </div>

        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory??null) }} gap-6">
            @foreach($premiumCompanies as $c)
                @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null), ['company'=>$c, 'premium'=>true])
            @endforeach
        </div>
    </div>
</section>
@endif

@if($cities->isNotEmpty())
<section class="py-14" style="background:#111827;color:white;">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Şehir rehberi</div>
                <h2 class="text-2xl font-black tracking-tight sm:text-3xl" style="color:white;">Türkiye'nin şehirlerinde firma bulun</h2>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:white;">Aramaya basla -></a>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-6">
            @foreach($cities as $city)
            <a href="{{ route('cities.show',$city->slug) }}" class="rounded-xl border border-white/10 bg-white/5 px-4 py-3 text-sm font-bold transition hover:-translate-y-0.5 hover:bg-white/10" style="color:white;">
                {{ $city->name }} <span class="font-medium opacity-60">({{ $city->companies_count }})</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-16" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Yeni kayıtlar</div>
            <h2 class="text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Son eklenen firmalar</h2>
            <p class="mt-2 text-sm" style="color:var(--text_muted);">Rehbere yeni katılan işletmeleri inceleyin.</p>
        </div>

        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory??null) }} gap-6">
            @foreach($latestCompanies as $c)
                @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null), ['company'=>$c])
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('companies.index') }}" class="inline-flex rounded-xl px-7 py-3 text-sm font-black text-white shadow-lg transition hover:opacity-90" style="background:var(--primary);">Tum firmalari gor -></a>
        </div>
    </div>
</section>

<section class="py-14" style="background:var(--bg_card);">
    <div class="mx-auto grid gap-8 px-4 sm:px-6 lg:grid-cols-3 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div>
            <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Yerel SEO</div>
            <h2 class="text-2xl font-black tracking-tight" style="color:var(--text);">Guvenilir firma bilgisi, sade rehber deneyimi</h2>
        </div>
        <div class="lg:col-span-2">
            <p class="text-sm leading-7" style="color:var(--text_muted);">
                {{ $settings->site_name ?? 'Firma Rehberi' }}; kategori, şehir ve işletme bilgilerini tek yerde toplayarak kullanıcıların doğru firmaya daha hızlı ulaşmasını sağlar. Telefon, WhatsApp, web sitesi, adres ve firma açıklamalarıyla yerel arama niyetine uygun, okunabilir ve sade bir rehber yapısı sunar.
            </p>
        </div>
    </div>
</section>

@include('partials.blog-section')

@include('partials.cta')

@endsection
