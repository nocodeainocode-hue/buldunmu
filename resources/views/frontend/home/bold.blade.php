@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('content')
@include('partials.heroes.gradient', ['title' => $settings->homepage_title ?? 'Aradiginiz Firma Burada!', 'subtitle' => $settings->homepage_subtitle ?? ''])

@if($premiumCompanies->isNotEmpty())
<section class="py-12" style="background:var(--bg);">
    <div style="max-width:1000px;margin:0 auto;padding:0 1rem;">
        <h2 class="text-2xl font-black text-center mb-2 uppercase tracking-tight" style="color:var(--text);">⭐ Öne Çıkanlar</h2>
        <p class="text-center mb-8" style="color:var(--text_muted);">Premium işletmeler</p>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
            @foreach($premiumCompanies->take(6) as $c) @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c,'premium'=>true]) @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12" style="background:var(--bg_card);">
    <div class="mx-auto px-4" style="max-width:100%;padding:0 2rem;">
        <h2 class="text-xl font-bold text-center mb-8" style="color:var(--text);">En Yeni Firmalar</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
            @foreach($latestCompanies as $c) @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c]) @endforeach
        </div>
    </div>
</section>

@if($categories->isNotEmpty())
<section class="py-10" style="background:var(--primary);">
    <div class="text-center text-white">
        <h3 class="text-lg font-bold mb-4">Kategoriler</h3>
        <div class="flex flex-wrap gap-2 justify-center max-w-3xl mx-auto px-4">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show',$cat->slug) }}" class="px-4 py-2 rounded-full bg-white/20 text-sm font-medium hover:bg-white/30 transition">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.blog-section')

@include('partials.cta')
@endsection
