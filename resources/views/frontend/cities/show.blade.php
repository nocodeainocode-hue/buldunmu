@extends('layouts.app')

@php
    $template = $directory->template ?? 'default';
    $gridCols = \App\View\Helpers\ThemeHelper::gridCols($directory ?? null);
    $cardPartial = \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null);
@endphp

@section('title', $city->meta_title ?: $city->name . ' Firmaları - Firma Rehberi')
@section('meta_description', $city->meta_description ?: $city->name . ' ilinde faaliyet gösteren tüm firmalar. Kategorilere göre filtreleyin, iletişim bilgilerini ve kullanıcı yorumlarını inceleyin.')

@section('content')
<div style="background:var(--bg);">
    {{-- Hero Banner --}}
    <section class="py-12" style="background:var(--primary);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-sm" style="color:rgba(255,255,255,0.7);">
                <a href="{{ route('home') }}" class="hover:underline" style="color:rgba(255,255,255,0.7);">Ana Sayfa</a>
                <span class="mx-1">/</span>
                <span style="color:white;">{{ $city->name }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-3xl" style="background:rgba(255,255,255,0.15);">📍</div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:white;">{{ $city->name }} Firmaları</h1>
                    <p class="mt-2 max-w-2xl text-sm" style="color:rgba(255,255,255,0.8);">
                        {{ $city->name }} ilinde faaliyet gösteren firmaların güncel iletişim bilgileri, adresleri ve kullanıcı yorumları.
                    </p>
                    <p class="mt-2 text-sm font-bold" style="color:rgba(255,255,255,0.6);">{{ number_format($totalInCity, 0, ',', '.') }} firma bulundu</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter Bar --}}
    <section class="border-b py-4" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap gap-3 items-center">
                @if($districts->isNotEmpty())
                <select name="district" class="rounded-xl border px-4 py-2 text-sm" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <option value="">Tüm İlçeler</option>
                    @foreach($districts as $dist)
                        <option value="{{ $dist->slug }}" {{ request('district') == $dist->slug ? 'selected' : '' }}>{{ $dist->name }}</option>
                    @endforeach
                </select>
                @endif
                <select name="category" class="rounded-xl border px-4 py-2 text-sm" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($popularCategories ?? \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(20)->get() as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }} ({{ $cat->companies_count }})</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl px-5 py-2 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Filtrele</button>
                @if(request()->anyFilled(['district', 'category']))
                    <a href="{{ url()->current() }}" class="text-sm hover:underline" style="color:var(--text_muted);">Temizle</a>
                @endif
            </form>
        </div>
    </section>

    {{-- District Quick Links --}}
    @if($districts->isNotEmpty() && !request()->anyFilled(['district', 'category']))
    <section class="py-6" style="background:var(--bg);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h3 class="text-sm font-bold mb-3" style="color:var(--text);">{{ $city->name }} İlçeleri</h3>
            <div class="flex flex-wrap gap-2">
                @foreach($districts->take(12) as $dist)
                    <a href="?district={{ $dist->slug }}" class="px-3 py-1.5 rounded-full border text-xs font-medium transition hover:-translate-y-0.5" style="border-color:var(--border);color:var(--text);">
                        📍 {{ $dist->name }}
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Companies Grid --}}
    <section class="py-10">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            @if($companies->isNotEmpty())
                <div class="grid {{ $gridCols }} gap-6">
                    @foreach($companies as $company)
                        @include('partials.cards.' . $cardPartial, ['company' => $company, 'premium' => $company->is_premium])
                    @endforeach
                </div>
                <div class="mt-10">
                    {{ $companies->links() }}
                </div>
            @else
                <div class="rounded-3xl border p-16 text-center" style="border-color:var(--border);background:var(--bg_card);">
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-lg font-bold" style="color:var(--text);">Bu şehirde firma bulunamadı</h3>
                    <p class="mt-2 text-sm" style="color:var(--text_muted);">Farklı filtrelerle tekrar deneyin veya <a href="{{ route('listing.create') }}" class="font-bold hover:underline" style="color:var(--primary);">firma ekleyin</a>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- SEO Content --}}
    @include('partials.local-seo', [
        'type' => 'city',
        'name' => $city->name,
        'companyCount' => $totalInCity,
    ])

    {{-- Popular Categories in this City --}}
    @if($popularCategories->isNotEmpty())
    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">{{ $city->name }} Popüler Kategoriler</h2>
            <div class="flex flex-wrap gap-2 justify-center">
                @foreach($popularCategories as $pcat)
                    <a href="{{ route('categories.show', $pcat->slug) }}" class="px-4 py-2 rounded-full border text-sm font-medium transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);color:var(--text);background:var(--bg);">
                        🏷️ {{ $pcat->name }} <span style="color:var(--text_muted);">({{ $pcat->companies_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta')
</div>
@endsection
