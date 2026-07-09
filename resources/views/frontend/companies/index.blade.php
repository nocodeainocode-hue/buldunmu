@extends('layouts.app')

@php
    $template = $directory->template ?? 'default';
    $gridCols = \App\View\Helpers\ThemeHelper::gridCols($directory ?? null);
    $cardPartial = \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null);
@endphp

@section('title', $metaTitle ?? 'Firmalar')
@section('meta_description', 'Tüm firmaları listeleyin, kategorilere ve şehirlere göre filtreleyin.')

@section('content')
<div style="background:var(--bg);">
    {{-- Hero --}}
    <section class="py-12" style="background:var(--primary);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-sm" style="color:rgba(255,255,255,0.7);">
                <a href="{{ route('home') }}" class="hover:underline" style="color:rgba(255,255,255,0.7);">Ana Sayfa</a>
                <span class="mx-1">/</span>
                <span style="color:white;">{{ $metaTitle ?? 'Tüm Firmalar' }}</span>
            </nav>
            <h1 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:white;">{{ $metaTitle ?? 'Tüm Firmalar' }}</h1>
            <p class="mt-2 text-sm" style="color:rgba(255,255,255,0.8);">{{ $companies->total() }} firma bulundu</p>
        </div>
    </section>

    {{-- Filter Bar --}}
    <section class="border-b py-4" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <form action="{{ url()->current() }}" method="GET" class="flex flex-wrap gap-3 items-center">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma, kategori veya şehir ara..."
                    class="rounded-xl border px-4 py-2 text-sm w-full sm:w-64" style="border-color:var(--border);background:var(--bg);color:var(--text);">

                <select name="category" class="rounded-xl border px-4 py-2 text-sm" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <option value="">Tüm Kategoriler</option>
                    @foreach($categories as $cat)
                        <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>{{ $cat->name }}</option>
                    @endforeach
                </select>

                <select name="city" class="rounded-xl border px-4 py-2 text-sm" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <option value="">Tüm Şehirler</option>
                    @foreach($cities as $ct)
                        <option value="{{ $ct->slug }}" {{ request('city') == $ct->slug ? 'selected' : '' }}>{{ $ct->name }}</option>
                    @endforeach
                </select>

                <button type="submit" class="rounded-xl px-5 py-2 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Filtrele</button>
                @if(request()->anyFilled(['q', 'category', 'city', 'district']))
                    <a href="{{ route('companies.index') }}" class="text-sm hover:underline" style="color:var(--text_muted);">Temizle</a>
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
                    <div class="text-5xl mb-4">🔍</div>
                    <h3 class="text-lg font-bold" style="color:var(--text);">Firma Bulunamadı</h3>
                    <p class="mt-2 text-sm" style="color:var(--text_muted);">Aramanızla eşleşen firma bulunamadı. Farklı kriterlerle tekrar deneyin.</p>
                </div>
            @endif
        </div>
    </section>

    @include('partials.cta')
</div>
@endsection
