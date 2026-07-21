@extends('layouts.app')

@php
    $template = $directory->template ?? 'default';
    $gridCols = \App\View\Helpers\ThemeHelper::gridCols($directory ?? null);
    $cardPartial = \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null);
@endphp

@section('title', $category->meta_title ?: $category->name . ' Firmaları')
@section('meta_description', $category->meta_description ?: $category->name . ' kategorisindeki en iyi firmalar. Telefon, adres, yorumlar ve iletişim bilgileriyle ' . $category->name . ' firmalarını keşfedin.')
@section('canonical', route('categories.show', $category->slug))

@push('head')
@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::listing(
    $category->name . ' Firmaları',
    $category->meta_description ?: $category->description ?: $category->name . ' kategorisindeki firmalar.',
    route('categories.show', $category->slug),
    $companies->getCollection(),
    [['name'=>'Ana Sayfa','url'=>route('home')], ['name'=>$category->name,'url'=>route('categories.show',$category->slug)]]
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
                <span style="color:white;">{{ $category->name }}</span>
            </nav>

            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                <div class="flex h-16 w-16 shrink-0 items-center justify-center rounded-2xl text-3xl" style="background:rgba(255,255,255,0.15);">
                    {{ $category->icon ?? '📁' }}
                </div>
                <div>
                    <h1 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:white;">{{ $category->name }} Firmaları</h1>
                    @if($category->description)
                        <p class="mt-2 max-w-2xl text-sm" style="color:rgba(255,255,255,0.8);">{{ $category->description }}</p>
                    @endif
                    <p class="mt-2 text-sm font-bold" style="color:rgba(255,255,255,0.6);">{{ number_format($totalInCategory, 0, ',', '.') }} firma bulundu</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Filter Bar --}}
    <section class="border-b py-4" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <select name="city" class="rounded-xl border px-4 py-2 text-sm" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <option value="">Tüm Şehirler</option>
                    @foreach($popularCities ?? \App\Models\City::withCount('companies')->orderByDesc('companies_count')->take(20)->get() as $ct)
                        <option value="{{ $ct->slug }}" {{ request('city') == $ct->slug ? 'selected' : '' }}>{{ $ct->name }} ({{ $ct->companies_count }})</option>
                    @endforeach
                </select>
                <button type="submit" class="rounded-xl px-5 py-2 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Filtrele</button>
                @if(request()->anyFilled(['city']))
                    <a href="{{ url()->current() }}" class="text-sm hover:underline" style="color:var(--text_muted);">Temizle</a>
                @endif
            </form>
        </div>
    </section>

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
                    <div class="text-5xl mb-4">📭</div>
                    <h3 class="text-lg font-bold" style="color:var(--text);">Bu kategoride henüz firma yok</h3>
                    <p class="mt-2 text-sm" style="color:var(--text_muted);">İlk firmayı eklemek için <a href="{{ route('listing.create') }}" class="font-bold hover:underline" style="color:var(--primary);">tıklayın</a>.</p>
                </div>
            @endif
        </div>
    </section>

    {{-- SEO Content --}}
    @include('partials.local-seo', [
        'type' => 'category',
        'name' => $category->name,
        'companyCount' => $totalInCategory,
        'subName' => $popularCities->first()?->name ?? ''
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

    {{-- Featured Cities Grid --}}
    @if($popularCities->isNotEmpty())
    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">Bu Kategoride Öne Çıkan Şehirler</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-3">
                @foreach($popularCities as $pct)
                    <a href="{{ route('cities.show', $pct->slug) }}"
                       class="flex flex-col items-center gap-2 rounded-xl border p-4 text-center transition hover:-translate-y-1 hover:shadow-md"
                       style="border-color:var(--border);color:var(--text);background:var(--bg);"
                       title="{{ $pct->name }}'daki {{ $category->name }} Firmaları">
                        <span class="text-2xl">📍</span>
                        <span class="text-sm font-bold">{{ $pct->name }}'daki<br>{{ $category->name }}</span>
                        <span class="text-xs font-medium px-2 py-0.5 rounded-full" style="background:var(--primary_light);color:var(--primary);">{{ $pct->companies_count }} firma</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Related Categories Sidebar --}}
    @if($relatedCategories->isNotEmpty())
    <section class="py-12" style="background:var(--bg);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">İlgili Kategoriler</h2>
            <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-3">
                @foreach($relatedCategories as $relCat)
                    <a href="{{ route('categories.show', $relCat->slug) }}"
                       class="flex flex-col items-center gap-2 rounded-xl border p-4 text-center transition hover:-translate-y-1 hover:shadow-md"
                       style="border-color:var(--border);color:var(--text);background:var(--bg_card);">
                        <span class="text-2xl">{{ $relCat->icon ?? '📁' }}</span>
                        <span class="text-sm font-medium">{{ $relCat->name }}</span>
                        <span class="text-xs" style="color:var(--text_muted);">{{ $relCat->companies_count }} firma</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    {{-- Blog Posts Section --}}
    @if($posts->isNotEmpty())
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
