@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('content')
{{-- ═══ MODERN: Is Odakli Split ═══ --}}
@include('partials.heroes.split', ['title' => $settings->homepage_title ?? 'Isletmenizi Buyutun', 'subtitle' => $settings->homepage_subtitle ?? ''])

{{-- Premium yatay liste, kategorisiz, direkt firmalar --}}
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-xl font-bold" style="color:var(--text);">Öne Çıkan İşletmeler</h2>
            <a href="{{ route('companies.index') }}" class="text-sm font-medium" style="color:var(--primary);">Tumunu Gor →</a>
        </div>
        <div class="space-y-3">
            @foreach($premiumCompanies->merge($latestCompanies)->take(8) as $c)
                @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c,'premium'=>$c->is_premium])
            @endforeach
        </div>
    </div>
</section>

{{-- Kategoriler yatay scroll --}}
@if($categories->isNotEmpty())
<section class="py-10" style="background:var(--bg_card);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <h3 class="text-lg font-bold mb-4" style="color:var(--text);">Kategoriler</h3>
        <div class="flex gap-2 overflow-x-auto pb-2">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show',$cat->slug) }}" class="shrink-0 px-4 py-2 rounded-full border text-sm font-medium whitespace-nowrap hover:shadow transition" style="background:var(--bg);border-color:var(--border);color:var(--text);">
                {{ $cat->name }} ({{ $cat->companies_count }})
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Sehirler mini --}}
@if($cities->isNotEmpty())
<section class="py-8" style="background:var(--bg);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <div class="flex flex-wrap gap-2 justify-center">
            @foreach($cities->take(10) as $city)
            <a href="{{ route('cities.show',$city->slug) }}" class="px-3 py-1.5 rounded-lg border text-xs" style="border-color:var(--border);color:var(--text_muted);">📍 {{ $city->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.blog-section')

@include('partials.cta')
@endsection
