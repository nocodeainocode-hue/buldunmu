@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Şehir Rehberi Dergisi')
@section('meta_description', $settings->meta_description ?? 'Şehrin en iyi işletmeleri, hikayeleri ve yerel rehber içerikleri.')
@section('content')

{{-- ═══ EDITORIAL: Serif tipografi, editöryal içerik önde, dergi hissi ═══ --}}

{{-- Hero --}}
@include('partials.heroes.editorial', [
    'title' => $settings->homepage_title ?? 'Şehrin Hikayelerini Keşfedin',
    'subtitle' => $settings->homepage_subtitle ?? 'Yerel işletmeler, sahiplerinin hikayeleri ve şehrin saklı güzellikleri.'
])

{{-- Öne Çıkan Blog Yazıları (Editöryal giriş) --}}
@php
    $featuredPosts = \App\Models\Post::published()->latest('published_at')->take(3)->get();
@endphp
@if($featuredPosts->isNotEmpty())
<section class="border-b py-14" style="background:var(--bg_card);border-color:var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mb-10">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.3em]" style="color:var(--accent);">Dergiden</div>
            <h2 class="text-3xl font-black italic sm:text-4xl" style="color:var(--text);font-family:var(--font_heading);">Son Hikayeler</h2>
            <div class="mt-3 h-px w-24" style="background:var(--accent);"></div>
        </div>

        <div class="grid gap-10 md:grid-cols-3">
            @foreach($featuredPosts as $post)
            <article class="group">
                <a href="{{ route('blog.show', $post->slug) }}">
                    <div class="mb-4 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">
                        {{ $post->published_at?->format('d F Y') }}
                    </div>
                    <h3 class="text-xl font-black leading-snug transition group-hover:underline" style="color:var(--text);font-family:var(--font_heading);">
                        {{ $post->title }}
                    </h3>
                    <p class="mt-3 line-clamp-3 text-sm leading-7" style="color:var(--text_muted);">
                        {{ $post->excerpt ?? Str::limit(strip_tags($post->content ?? ''), 140) }}
                    </p>
                    <div class="mt-4 text-xs font-black uppercase tracking-widest" style="color:var(--primary);">
                        Okumaya devam et →
                    </div>
                </a>
            </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Premium Firmalar (Dergi vitrin tarzı) --}}
@if($premiumCompanies->isNotEmpty())
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mb-10 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.3em]" style="color:var(--accent);">Seçtiklerimiz</div>
            <h2 class="text-3xl font-black italic sm:text-4xl" style="color:var(--text);font-family:var(--font_heading);">Öne Çıkan İşletmeler</h2>
            <div class="mx-auto mt-3 h-px w-24" style="background:var(--accent);"></div>
            <p class="mt-4 text-sm italic" style="color:var(--text_muted);">Editörlerimizin özenle seçtiği, şehrin en beğenilen mekanları.</p>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($premiumCompanies->take(6) as $c)
                @include('partials.cards.editorial', ['company' => $c, 'premium' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Kategoriler (Dergi indeksi tarzı) --}}
@if($categories->isNotEmpty())
<section class="border-y py-14" style="background:var(--bg_card);border-color:var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mb-8">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.3em]" style="color:var(--accent);">Rehber İndeksi</div>
            <h2 class="text-2xl font-black italic sm:text-3xl" style="color:var(--text);font-family:var(--font_heading);">Kategorilere Göz Atın</h2>
            <div class="mt-3 h-px w-24" style="background:var(--accent);"></div>
        </div>

        <div class="grid grid-cols-2 gap-px sm:grid-cols-3 lg:grid-cols-4" style="background:var(--border);">
            @foreach($categories->take(8) as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="group flex items-center justify-between p-5 transition hover:opacity-90" style="background:var(--bg_card);">
                <div>
                    <div class="font-black" style="color:var(--text);font-family:var(--font_heading);">{{ $cat->name }}</div>
                    <div class="mt-1 text-xs" style="color:var(--text_muted);">{{ $cat->companies_count }} işletme</div>
                </div>
                <span class="text-xl transition group-hover:translate-x-1" style="color:var(--accent);">→</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Son Eklenenler --}}
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mb-10 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.3em]" style="color:var(--accent);">Yeni Eklenenler</div>
            <h2 class="text-2xl font-black italic sm:text-3xl" style="color:var(--text);font-family:var(--font_heading);">Rehbere Katılan Son İşletmeler</h2>
            <div class="mx-auto mt-3 h-px w-24" style="background:var(--accent);"></div>
        </div>

        <div class="grid gap-8 md:grid-cols-2 lg:grid-cols-3">
            @foreach($latestCompanies->take(6) as $c)
                @include('partials.cards.editorial', ['company' => $c])
            @endforeach
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('companies.index') }}" class="inline-block border-b-2 pb-1 text-sm font-black uppercase tracking-widest transition hover:opacity-70" style="color:var(--primary);border-color:var(--primary);">
                Tüm Rehberi Keşfet
            </a>
        </div>
    </div>
</section>

{{-- Şehirler (Dergi kapanışı) --}}
@if($cities->isNotEmpty())
<section class="py-14" style="background:var(--primary);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1120px);">
        <div class="mb-8 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-[0.3em] text-white/70">Şehir Şehir</div>
            <h2 class="text-2xl font-black italic text-white sm:text-3xl" style="font-family:var(--font_heading);">Türkiye'nin Her Köşesinden</h2>
        </div>
        <div class="flex flex-wrap justify-center gap-x-6 gap-y-3">
            @foreach($cities->take(15) as $city)
            <a href="{{ route('cities.show', $city->slug) }}" class="text-sm font-bold text-white/90 transition hover:text-white hover:underline">
                {{ $city->name }} <span class="text-white/50">({{ $city->companies_count }})</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

@include('partials.cta')

@endsection
