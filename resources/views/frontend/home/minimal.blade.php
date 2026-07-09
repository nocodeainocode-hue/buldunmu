@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('content')
{{-- ═══ MINIMAL: Sade, bol beyaz ═══ --}}
@include('partials.heroes.minimal', ['title' => $settings->homepage_title ?? 'Firma Rehberi', 'subtitle' => $settings->homepage_subtitle ?? 'Sade ve hizli firma arama'])

@if($premiumCompanies->isNotEmpty())
<section class="py-10" style="background:var(--bg_card);">
    <div class="mx-auto px-4" style="max-width: 900px;">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-light uppercase tracking-wide" style="color:var(--text_muted);">⭐ One Cikanlar</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($premiumCompanies as $c)
                @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c,'premium'=>true])
            @endforeach
        </div>
    </div>
</section>
@endif

<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4" style="max-width: 900px;">
        <h2 class="text-lg font-light text-center mb-8 uppercase tracking-wide" style="color:var(--text_muted);">Son Eklenen Isletmeler</h2>
        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
            @foreach($latestCompanies->take(8) as $c)
                @include('partials.cards.'.\App\View\Helpers\ThemeHelper::cardPartial($directory??null),['company'=>$c])
            @endforeach
        </div>
        <div class="text-center mt-10">
            <a href="{{ route('companies.index') }}" class="inline-block px-8 py-2.5 rounded-full border text-sm font-medium" style="border-color:var(--primary);color:var(--primary);">Tum Firmalar →</a>
        </div>
    </div>
</section>

@if($categories->isNotEmpty())
<section class="py-8 border-t" style="border-color:var(--border);background:var(--bg_card);">
    <div class="mx-auto px-4 text-center" style="max-width:900px;">
        <h3 class="text-xs font-medium mb-3 uppercase tracking-widest" style="color:var(--text_muted);">Kategoriler</h3>
        <div class="flex flex-wrap gap-1.5 justify-center">
            @foreach($categories as $cat)
            <a href="{{ route('categories.show',$cat->slug) }}" class="px-3 py-1 text-xs rounded" style="color:var(--text_muted);">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.blog-section')
@endsection
