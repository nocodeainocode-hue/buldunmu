@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $settings->meta_description ?? 'Kategori ve şehirlerde binlerce firma kaydı, güncel iş ilanları.')

@push('head')
<style>
    .ilan-board .ilan-tile:hover { background: var(--primary_light); }
    .ilan-board .ilan-band { border-bottom: 3px solid var(--accent); }
</style>
@endpush

@section('content')
@php $ilanBoardCompanies = $premiumCompanies->isNotEmpty() ? $premiumCompanies->take(6) : $latestCompanies->take(6); @endphp
<main class="ilan-board" style="background:var(--bg)">
    <section class="ilan-band" style="background:var(--primary);color:#eef4f9">
        <div class="mx-auto px-4 py-8 sm:px-6 lg:py-10" style="max-width:var(--page_width)">
            <div class="flex flex-wrap items-end justify-between gap-4">
                <div style="max-width:640px">
                    <p class="text-xs font-bold uppercase tracking-widest" style="color:rgba(238,244,249,.75)">İlan borsası / Firma rehberi</p>
                    <h1 class="mt-2 text-3xl font-bold leading-tight sm:text-4xl" style="font-family:var(--font_heading)">{{ $settings->homepage_title ?? 'Aradığınız firmayı borsada bulun' }}</h1>
                    <p class="mt-3 text-sm leading-6" style="color:rgba(238,244,249,.8)">{{ $settings->homepage_subtitle ?? 'Kategori ve şehirlerde binlerce firma kaydı; güncel iş ilanları ve fiyat listeleri tek sayfada.' }}</p>
                </div>
                <a href="{{ route('owner.register') }}" class="px-5 py-3 text-sm font-bold" style="background:var(--accent);color:#27180a;border-radius:var(--border_radius)">Ücretsiz firma ekle</a>
            </div>
            <form action="{{ route('search') }}" method="GET" role="search" class="mt-6 flex max-w-3xl flex-col gap-2 sm:flex-row">
                <label for="ilan-search" class="sr-only">Firma veya hizmet ara</label>
                <input id="ilan-search" name="q" class="min-w-0 flex-1 px-4 py-3 text-sm text-slate-900 outline-none" style="border-radius:var(--border_radius)" placeholder="Firma, hizmet veya şehir yazın">
                <button class="px-6 py-3 text-sm font-bold" style="background:var(--accent);color:#27180a;border-radius:var(--border_radius)">Listele</button>
            </form>
        </div>
    </section>

    <div class="mx-auto grid gap-4 px-4 py-6 sm:px-6 lg:grid-cols-[230px_minmax(0,1fr)] lg:px-8" style="max-width:var(--page_width)">
        <aside id="ilan-categories" class="self-start border lg:sticky lg:top-4" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius)">
            <h2 class="px-4 py-3 text-sm font-bold text-white" style="background:var(--primary);border-radius:calc(var(--border_radius) - 1px) calc(var(--border_radius) - 1px) 0 0">Kategoriler</h2>
            <nav aria-label="Kategoriler" class="divide-y" style="border-color:var(--border)">
                @foreach($categories->take(12) as $category)
                    <a class="ilan-tile flex items-center justify-between gap-3 px-4 py-2.5 text-[13px]" href="{{ route('categories.show', $category->slug) }}"><span class="min-w-0 flex-1 truncate">{{ $category->name }}</span><span class="text-xs font-bold" style="color:var(--text_muted)">{{ $category->companies_count }}</span></a>
                @endforeach
            </nav>
        </aside>
        <div class="min-w-0 space-y-6">
            <section aria-labelledby="ilan-featured">
                <div class="mb-3 flex items-end justify-between gap-3 border-b pb-2" style="border-color:var(--border)"><h2 id="ilan-featured" class="text-lg font-bold" style="color:var(--primary_hover)">Spot firmalar</h2><a href="{{ route('companies.index') }}" class="text-[13px] font-bold" style="color:var(--primary)">Tüm firmalar ›</a></div>
                <div class="grid gap-3 sm:grid-cols-2 xl:grid-cols-3">
                    @forelse($ilanBoardCompanies as $company)
                        <a href="{{ route('companies.show', $company->slug) }}" class="ilan-tile flex flex-col gap-2 border p-4 no-underline" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius)">
                            <div class="flex items-center justify-between gap-2">
                                <p class="text-[11px] font-bold uppercase tracking-wide" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }}</p>
                                @if($company->is_premium)<span class="px-1.5 py-0.5 text-[9.5px] font-bold uppercase" style="background:var(--accent);color:#27180a;border-radius:2px">Spot</span>@endif
                            </div>
                            <h3 class="text-[15px] font-bold" style="color:var(--primary_hover)">{{ $company->name }}</h3>
                            <p class="line-clamp-2 text-xs leading-5" style="color:var(--text_muted)">{{ $company->short_description ?: 'Firma profilini, iletişim ve konum bilgilerini inceleyin.' }}</p>
                            <div class="mt-auto flex items-center justify-between border-t pt-2 text-xs font-bold" style="border-color:var(--border)"><span style="color:var(--text_muted)">{{ $company->city->name ?? 'Türkiye' }}</span><span style="color:var(--primary)">Profili aç ›</span></div>
                        </a>
                    @empty
                        <p class="border p-6 text-sm sm:col-span-3" style="border-color:var(--border);background:var(--bg_card)">Henüz firma kaydı bulunmuyor.</p>
                    @endforelse
                </div>
            </section>

            <section id="ilan-cities" aria-labelledby="ilan-city-heading">
                <div class="mb-3 flex items-end justify-between gap-3 border-b pb-2" style="border-color:var(--border)"><h2 id="ilan-city-heading" class="text-lg font-bold" style="color:var(--primary_hover)">Şehirler</h2><a href="{{ route('companies.index') }}" class="text-[13px] font-bold" style="color:var(--primary)">Tüm listeler ›</a></div>
                <div class="grid grid-cols-2 gap-px border sm:grid-cols-3 md:grid-cols-4" style="background:var(--border);border-color:var(--border);border-radius:var(--border_radius);overflow:hidden">
                    @foreach($cities->take(12) as $city)
                        <a href="{{ route('cities.show', $city->slug) }}" class="ilan-tile flex items-center justify-between p-3 text-[13px] font-bold no-underline" style="background:var(--bg_card);color:var(--text)"><span>{{ $city->name }}</span><span class="text-xs" style="color:var(--secondary)">{{ $city->companies_count }}</span></a>
                    @endforeach
                </div>
            </section>

            <section aria-labelledby="ilan-latest-heading">
                <div class="mb-3 flex items-end justify-between gap-3 border-b pb-2" style="border-color:var(--border)"><h2 id="ilan-latest-heading" class="text-lg font-bold" style="color:var(--primary_hover)">Son eklenen firmalar</h2></div>
                <div class="border" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius)">
                    @foreach($latestCompanies->take(6) as $company)
                        <a href="{{ route('companies.show', $company->slug) }}" class="ilan-tile flex flex-wrap items-center gap-x-3 gap-y-1 border-b px-4 py-3 text-sm no-underline last:border-b-0" style="border-color:var(--border)">
                            <span class="text-xs font-bold" style="color:var(--secondary)">{{ $company->category->name ?? 'İşletme' }}</span>
                            <span class="min-w-0 flex-1 truncate font-bold" style="color:var(--primary_hover)">{{ $company->name }}</span>
                            <span class="text-xs" style="color:var(--text_muted)">{{ $company->city->name ?? 'Türkiye' }}</span>
                            <span class="text-xs" style="color:var(--text_muted)">{{ $company->created_at?->format('d.m.Y') }}</span>
                        </a>
                    @endforeach
                </div>
            </section>

            @if($featuredOfferings->isNotEmpty())
                <section aria-labelledby="ilan-offerings-heading">
                    <div class="mb-3 flex items-end justify-between gap-3 border-b pb-2" style="border-color:var(--border)"><h2 id="ilan-offerings-heading" class="text-lg font-bold" style="color:var(--primary_hover)">Güncel ilanlar</h2></div>
                    <div class="grid gap-3 sm:grid-cols-2">
                        @foreach($featuredOfferings as $offering)
                            <a href="{{ route('companies.show', $offering->company?->slug ?? 'ilan') }}#urunler-hizmetler" class="ilan-tile border p-4 no-underline" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius)">
                                <p class="text-xs font-bold" style="color:var(--secondary)">{{ $offering->type === 'product' ? 'Ürün' : 'Hizmet' }} · {{ $offering->company?->name ?? '' }}</p>
                                <p class="mt-1 text-[15px] font-bold" style="color:var(--primary_hover)">{{ $offering->name }}</p>
                                @if($offering->price !== null && (float) $offering->price > 0)<p class="mt-1 text-lg font-bold" style="color:var(--primary_hover)">{{ number_format((float) $offering->price, 0, ',', '.') }} TL</p>@endif
                                <p class="mt-1 text-xs" style="color:var(--text_muted)">İncele ›</p>
                            </a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section aria-labelledby="ilan-jobs-heading">
                <div class="mb-3 flex items-end justify-between gap-3 border-b pb-2" style="border-color:var(--border)"><h2 id="ilan-jobs-heading" class="text-lg font-bold" style="color:var(--primary_hover)">Güncel iş ilanları</h2><a href="{{ route('jobs.index') }}" class="text-[13px] font-bold" style="color:var(--primary)">Tüm ilanlar ›</a></div>
                <div class="border" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius)">
                    @forelse($homeJobs as $job)
                        <a href="{{ route('jobs.show', $job->slug) }}" class="ilan-tile flex flex-wrap items-center gap-x-3 gap-y-1 border-b px-4 py-3 text-sm no-underline last:border-b-0" style="border-color:var(--border)">
                            <span class="min-w-0 flex-1 truncate font-bold" style="color:var(--primary_hover)">{{ $job->title }}</span>
                            <span class="text-xs" style="color:var(--secondary)">{{ $job->company?->name ?? 'Firma' }}</span>
                            <span class="text-xs" style="color:var(--text_muted)">{{ $job->location ?: ($job->company?->city?->name ?? 'Türkiye') }}</span>
                        </a>
                    @empty
                        <p class="px-4 py-5 text-sm" style="color:var(--text_muted)">Şu anda açık iş ilanı yok.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </div>
    @include('partials.blog-section')
    @include('partials.cta')
</main>
@endsection
