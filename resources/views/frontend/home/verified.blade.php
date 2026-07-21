@extends('layouts.app')
@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Doğrulanmış Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Doğrulanmış ve güvenilir firmaları keşfedin. İşletmelerin güven endeksini görün.')
@section('content')

{{-- ═══ VERIFIED: Güven odaklı, yeşil/altın, doğrulanmış rozeti önde ═══ --}}

{{-- Hero --}}
@include('partials.heroes.verified', [
    'title' => $settings->homepage_title ?? 'Güvenilir İşletmeleri Keşfedin',
    'subtitle' => $settings->homepage_subtitle ?? 'Doğrulanmış firmalar, gerçek müşteri yorumları ve güven endeksiyle doğru seçimi yapın.'
])

{{-- Güven İstatistikleri --}}
<section class="border-b py-8" style="background:var(--bg_card);border-color:var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="grid grid-cols-2 gap-6 sm:grid-cols-4">
            <div class="text-center">
                <div class="text-3xl font-black" style="color:var(--primary);">{{ \App\Models\Company::count() }}</div>
                <div class="mt-1 text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Kayıtlı Firma</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black" style="color:var(--primary);">{{ \App\Models\Category::active()->count() }}</div>
                <div class="mt-1 text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Kategori</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black" style="color:var(--primary);">{{ \App\Models\City::count() }}</div>
                <div class="mt-1 text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Şehir</div>
            </div>
            <div class="text-center">
                <div class="text-3xl font-black" style="color:var(--accent);">✓</div>
                <div class="mt-1 text-xs font-bold uppercase tracking-wider" style="color:var(--text_muted);">Doğrulanmış</div>
            </div>
        </div>
    </div>
</section>

{{-- Doğrulanmış Firmalar (Ana vitrin) --}}
@php
    $trustedCompanies = $premiumCompanies->merge($latestCompanies)->sortByDesc('is_verified')->take(6);
@endphp
@if($trustedCompanies->isNotEmpty())
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 text-center">
            <div class="mb-2 inline-flex items-center gap-2 rounded-full px-4 py-1.5 text-xs font-black uppercase tracking-widest" style="background:var(--primary_light);color:var(--primary);">
                <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                Doğrulanmış İşletmeler
            </div>
            <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Güvenle Tercih Edebileceğiniz Firmalar</h2>
            <p class="mt-3 text-sm" style="color:var(--text_muted);">Kimliği doğrulanmış, iletişim bilgileri teyit edilmiş işletmeler.</p>
        </div>

        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory ?? null) }} gap-6">
            @foreach($trustedCompanies as $c)
                @include('partials.cards.verified', ['company' => $c, 'premium' => $c->is_premium])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Kategoriler --}}
@if($categories->isNotEmpty())
<section class="py-12" style="background:var(--bg_card);border-top:1px solid var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex items-end justify-between">
            <div>
                <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Kategoriler</div>
                <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Sektöre Göre Arayın</h2>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tüm firmalar →</a>
        </div>

        <div class="grid grid-cols-2 gap-4 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($categories->take(8) as $cat)
            <a href="{{ route('categories.show', $cat->slug) }}" class="group rounded-xl border p-5 text-center transition hover:-translate-y-1 hover:shadow-lg" style="border-color:var(--border);background:var(--bg);">
                <div class="mx-auto mb-3 flex h-12 w-12 items-center justify-center rounded-xl text-2xl" style="background:var(--primary_light);">{{ $cat->icon ?? '🏢' }}</div>
                <div class="font-black" style="color:var(--text);">{{ $cat->name }}</div>
                <div class="mt-1 text-xs" style="color:var(--text_muted);">{{ $cat->companies_count }} firma</div>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Şehirler --}}
@if($cities->isNotEmpty())
<section class="py-12" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Şehirler</div>
            <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Türkiye Genelinde Hizmet</h2>
        </div>

        <div class="flex flex-wrap justify-center gap-2">
            @foreach($cities->take(12) as $city)
            <a href="{{ route('cities.show', $city->slug) }}" class="flex items-center gap-2 rounded-full border px-4 py-2 text-sm font-bold transition hover:shadow-md" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">
                <svg class="h-4 w-4" style="color:var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                {{ $city->name }}
                <span class="text-xs" style="color:var(--text_muted);">({{ $city->companies_count }})</span>
            </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- Son Eklenenler --}}
<section class="py-14" style="background:var(--bg_card);border-top:1px solid var(--border);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 text-center">
            <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--accent);">Yeni Kayıtlar</div>
            <h2 class="text-2xl font-black sm:text-3xl" style="color:var(--text);">Rehbere Yeni Katılanlar</h2>
        </div>

        <div class="grid {{ \App\View\Helpers\ThemeHelper::gridCols($directory ?? null) }} gap-6">
            @foreach($latestCompanies->take(6) as $c)
                @include('partials.cards.verified', ['company' => $c])
            @endforeach
        </div>

        <div class="mt-10 text-center">
            <a href="{{ route('companies.index') }}" class="inline-flex items-center gap-2 rounded-xl px-7 py-3 text-sm font-black text-white shadow-lg transition hover:opacity-90" style="background:var(--primary);">
                Tüm Firmaları Gör
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 8l4 4m0 0l-4 4m4-4H3"/></svg>
            </a>
        </div>
    </div>
</section>

@include('partials.local-seo', ['type' => 'home', 'name' => $settings->site_name ?? 'Firma Rehberi', 'companyCount' => \App\Models\Company::count()])
@include('partials.blog-section')
@include('partials.cta')

@endsection
