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

    {{-- Popular Cities Sidebar (bottom for SEO) --}}
    @if($popularCities->isNotEmpty())
    <section class="py-12" style="background:var(--bg_card);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <h2 class="text-xl font-black mb-6 text-center" style="color:var(--text);">{{ $category->name }} Firmalarının Bulunduğu Şehirler</h2>
            <div class="flex flex-wrap gap-2 justify-center">
                @foreach($popularCities as $pct)
                    <a href="{{ route('cities.show', $pct->slug) }}" class="px-4 py-2 rounded-full border text-sm font-medium transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);color:var(--text);background:var(--bg);">
                        📍 {{ $pct->name }} <span style="color:var(--text_muted);">({{ $pct->companies_count }})</span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    @include('partials.cta')
</div>
@endsection
