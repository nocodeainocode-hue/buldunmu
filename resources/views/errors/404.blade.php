@extends('layouts.app')
@section('title', 'Sayfa Bulunamadı — 404')

@php
    $_categories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(8)->get();
    $_latestCompanies = \App\Models\Company::active()->with(['category','city'])->latest()->take(6)->get();
@endphp

@section('content')
<div class="min-h-[60vh] flex items-center justify-center px-4 py-16" style="background:var(--bg);">
    <div class="max-w-2xl w-full text-center">
        <div class="text-8xl font-black mb-4" style="color:var(--primary);">404</div>
        <h1 class="text-2xl font-bold mb-3" style="color:var(--text);">Aradığınız sayfa bulunamadı</h1>
        <p class="text-sm mb-8" style="color:var(--text_muted);">Sayfa kaldırılmış, adresi değişmiş veya hiç var olmamış olabilir.</p>

        <div class="flex flex-wrap gap-3 justify-center mb-10">
            <a href="{{ route('home') }}" class="rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Ana Sayfaya Dön</a>
            <a href="{{ route('companies.index') }}" class="rounded-xl px-6 py-3 text-sm font-bold transition hover:opacity-90" style="background:var(--primary_light);color:var(--primary);">Firmaları İncele</a>
        </div>

        @if($_categories->isNotEmpty())
        <div class="mb-8">
            <h2 class="text-lg font-bold mb-4" style="color:var(--text);">Popüler Kategoriler</h2>
            <div class="flex flex-wrap gap-2 justify-center">
                @foreach($_categories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="rounded-full px-4 py-2 text-sm font-medium border transition hover:shadow-sm" style="border-color:var(--border);color:var(--text);background:var(--bg_card);">
                        {{ $cat->icon ?? '' }} {{ $cat->name }}
                    </a>
                @endforeach
            </div>
        </div>
        @endif

        @if($_latestCompanies->isNotEmpty())
        <div>
            <h2 class="text-lg font-bold mb-4" style="color:var(--text);">Son Eklenen Firmalar</h2>
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3 text-left">
                @foreach($_latestCompanies as $c)
                    <a href="{{ route('companies.show', $c->slug) }}" class="rounded-xl border p-3 transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);background:var(--bg_card);">
                        <div class="text-sm font-bold truncate" style="color:var(--text);">{{ $c->name }}</div>
                        <div class="text-xs mt-1" style="color:var(--text_muted);">{{ $c->category->name ?? '' }} · {{ $c->city->name ?? '' }}</div>
                    </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
