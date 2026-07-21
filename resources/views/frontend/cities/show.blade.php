@extends('layouts.app')

@php
    $template = $directory->template ?? 'default';
    $gridCols = \App\View\Helpers\ThemeHelper::gridCols($directory ?? null);
    $cardPartial = \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null);
@endphp

@section('title', $city->meta_title ?: $city->name . ' Firmaları - Firma Rehberi')
@section('meta_description', $city->meta_description ?: $city->name . ' ilinde faaliyet gösteren tüm firmalar. Kategorilere göre filtreleyin, iletişim bilgilerini ve kullanıcı yorumlarını inceleyin.')
@section('robots', $totalInCity > 0 ? 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1' : 'noindex,follow,max-image-preview:large')
@section('canonical', ($isOtherCities ?? false) ? route('cities.other') : route('cities.show', $city->slug))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::listing(
    $city->name . ' Firmaları',
    $city->meta_description ?: $city->name . ' ilinde faaliyet gösteren firmalar.',
    ($isOtherCities ?? false) ? route('cities.other') : route('cities.show', $city->slug),
    $companies->getCollection(),
    [['name'=>'Ana Sayfa','url'=>route('home')], ['name'=>$city->name,'url'=>($isOtherCities ?? false) ? route('cities.other') : route('cities.show',$city->slug)]]
)])
@endpush

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

    {{-- Rich SEO Description --}}
    @if(!empty($seoContent))
    <section class="py-12" style="background:var(--bg);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="prose max-w-none text-sm leading-7" style="color:var(--text_muted);">
                @foreach(explode("\n\n", $seoContent) as $paragraph)
                    <p class="mb-4">{{ $paragraph }}</p>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Popular Categories in this City --}}
    @if($popularCategories->isNotEmpty())
    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">{{ $city->name }} Popüler Kategoriler</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($popularCategories as $pcat)
                    <a href="{{ route('categories.show', $pcat->slug) }}"
                       class="flex flex-col items-center gap-2 rounded-xl border p-4 text-center transition hover:-translate-y-1 hover:shadow-md"
                       style="border-color:var(--border);color:var(--text);background:var(--bg);"
                       title="{{ $city->name }} {{ $pcat->name }} Firmaları">
                        <span class="text-2xl">{{ $pcat->icon ?? '🏷️' }}</span>
                        <span class="text-sm font-bold">{{ $pcat->name }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:var(--primary_light);color:var(--primary);">{{ $pcat->companies_count }} firma</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Nearby Cities --}}
    @if(($nearbyCities ?? collect())->isNotEmpty())
    <section class="py-12" style="background:var(--bg);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">Diğer Şehirler</h2>
            <div class="flex flex-wrap gap-3 justify-center">
                @foreach($nearbyCities as $nearby)
                    <a href="{{ route('cities.show', $nearby->slug) }}"
                       class="flex items-center gap-2 rounded-xl border px-5 py-3 text-sm font-medium transition hover:-translate-y-1 hover:shadow-md"
                       style="border-color:var(--border);color:var(--text);background:var(--bg_card);">
                        <span>📍</span>
                        <span>{{ $nearby->name }}</span>
                        @if($nearby->companies_count)
                            <span class="text-xs px-2 py-0.5 rounded-full" style="background:var(--primary_light);color:var(--primary);">{{ $nearby->companies_count }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Blog Posts Section --}}
    @if(($posts ?? collect())->isNotEmpty())
    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">Blog Yazıları</h2>
            <div class="grid gap-6 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($posts as $post)
                    <a href="{{ route('blog.show', $post->slug) }}"
                       class="group flex flex-col overflow-hidden rounded-2xl border transition hover:-translate-y-1 hover:shadow-lg"
                       style="border-color:var(--border);background:var(--bg);">
                        @if($post->image)
                            <div class="aspect-[16/9] overflow-hidden">
                                <img src="{{ asset('storage/' . $post->image) }}"
                                     alt="{{ $post->title }}"
                                     class="h-full w-full object-cover transition group-hover:scale-105"
                                     loading="lazy">
                            </div>
                        @endif
                        <div class="flex flex-1 flex-col gap-2 p-5">
                            <h3 class="text-base font-bold leading-snug group-hover:underline" style="color:var(--text);">
                                {{ $post->title }}
                            </h3>
                            @if($post->excerpt)
                                <p class="text-sm line-clamp-3" style="color:var(--text_muted);">{{ $post->excerpt }}</p>
                            @endif
                            <div class="mt-auto pt-2 flex items-center gap-2 text-xs" style="color:var(--text_muted);">
                                @if($post->published_at)
                                    <span>{{ $post->published_at->translatedFormat('d F Y') }}</span>
                                @endif
                                @if($post->author_name)
                                    <span>·</span>
                                    <span>{{ $post->author_name }}</span>
                                @endif
                            </div>
                        </div>
                    </a>
                @endforeach
            </div>
            <div class="mt-8 text-center">
                <a href="{{ route('blog.index') }}"
                   class="inline-flex items-center gap-2 rounded-xl px-6 py-3 text-sm font-bold transition hover:opacity-90"
                   style="background:var(--primary);color:white;">
                    Tüm Blog Yazıları
                    <span aria-hidden="true">→</span>
                </a>
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta')
</div>
@endsection
