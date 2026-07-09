@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('content')
@include('partials.heroes.gradient', ['title' => $settings->homepage_title ?? 'Premium Firma Rehberi', 'subtitle' => $settings->homepage_subtitle ?? ''])

{{-- Feature stats --}}
<section class="py-8" style="background:var(--bg_card);">
    <div class="mx-auto px-4 flex justify-center gap-12 text-center" style="max-width:var(--page_width,1280px);">
        <div><div class="text-3xl font-bold" style="color:var(--primary);">{{ \App\Models\Directory::count() }}</div><div class="text-xs mt-1" style="color:var(--text_muted);">Rehber</div></div>
        <div><div class="text-3xl font-bold" style="color:var(--primary);">{{ \App\Models\Company::count() }}</div><div class="text-xs mt-1" style="color:var(--text_muted);">Firma</div></div>
        <div><div class="text-3xl font-bold" style="color:var(--primary);">{{ \App\Models\Category::count() }}</div><div class="text-xs mt-1" style="color:var(--text_muted);">Kategori</div></div>
    </div>
</section>

@if($premiumCompanies->isNotEmpty())
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <div class="flex justify-between items-end mb-8">
            <div><div class="text-xs uppercase tracking-widest mb-1" style="color:var(--accent);">Premium</div><h2 class="text-2xl font-serif font-bold" style="color:var(--text);">One Cikan Isletmeler</h2></div>
            <a href="{{ route('companies.index') }}" class="text-sm font-serif italic" style="color:var(--text_muted);">Tumunu gor →</a>
        </div>
        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory??null) }} gap-8">
            @foreach($premiumCompanies as $c) @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c,'premium'=>true]) @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12" style="background:var(--bg_card);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <h2 class="text-2xl font-serif font-bold text-center mb-8" style="color:var(--text);">Rehbere Yeni Eklenenler</h2>
        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory??null) }} gap-8">
            @foreach($latestCompanies as $c) @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c]) @endforeach
        </div>
    </div>
</section>

@if($categories->isNotEmpty())
<section class="py-10 border-t" style="border-color:var(--border);background:var(--bg);">
    <div class="mx-auto px-4" style="max-width:var(--page_width,1280px);">
        <h3 class="text-sm font-serif text-center mb-6 uppercase tracking-widest" style="color:var(--text_muted);">Kategorilerimiz</h3>
        <div class="flex flex-wrap gap-3 justify-center">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show',$cat->slug) }}" class="px-5 py-2.5 rounded border text-sm font-serif transition hover:shadow" style="border-color:var(--border);color:var(--text);background:var(--bg_card);">
                {{ $cat->icon ?? '' }} {{ $cat->name }}
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.blog-section')

@include('partials.cta')
@endsection
