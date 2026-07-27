@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Akış Rehberi')
@section('meta_description', $settings->meta_description ?? 'Firmaları sosyal medya akışı rahatlığında keşfedin; kaydırın, dokunun, ulaşın.')

@push('head')
<style>
    html.theme-social-feed body > header,
    html.theme-social-feed body > footer { display:none; }
    html.theme-social-feed body { background:var(--bg) !important; }
    html.theme-social-feed body > main { padding:18px 12px; }
    @media (max-width:520px) {
        html.theme-social-feed body > main { padding:0; }
        .feed-shell { min-height:100vh !important; border:0 !important; border-radius:0 !important; }
    }
    .story-ring { background:linear-gradient(135deg,var(--hero_gradient_from),var(--hero_gradient_to)); }
</style>
@endpush

@section('content')
<div class="feed-shell relative mx-auto min-h-[calc(100vh-36px)] overflow-hidden border bg-white shadow-2xl" style="max-width:480px;border-color:var(--border);border-radius:22px;">

    {{-- App bar --}}
    <div class="flex items-center justify-between border-b px-4 py-3" style="border-color:var(--border);">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2">
            <span class="story-ring flex h-9 w-9 items-center justify-center rounded-xl text-sm font-black text-white">{{ mb_substr($directory->name ?? $settings->site_name ?? 'R', 0, 1) }}</span>
            <strong class="truncate text-base" style="color:var(--text);font-family:var(--font_heading);">{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}</strong>
        </a>
        <a href="{{ route('search') }}" class="flex h-9 w-9 items-center justify-center rounded-full" style="background:var(--primary_light);color:var(--primary);" aria-label="Ara">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </a>
    </div>

    <div class="px-0 pb-24 pt-3">

        {{-- Story row: categories --}}
        <div class="flex gap-4 overflow-x-auto px-4 pb-3 pt-1">
            @foreach($categories->take(10) as $category)
                <a href="{{ route('categories.show',$category->slug) }}" class="flex w-16 shrink-0 flex-col items-center gap-1.5">
                    <span class="story-ring flex h-16 w-16 items-center justify-center rounded-full p-[3px]">
                        <span class="flex h-full w-full items-center justify-center rounded-full bg-white text-lg font-black" style="color:var(--primary);">{{ mb_substr($category->name,0,1) }}</span>
                    </span>
                    <span class="w-full truncate text-center text-[11px] font-bold" style="color:var(--text);">{{ $category->name }}</span>
                </a>
            @endforeach
        </div>

        {{-- Search --}}
        <div class="px-4">
            <form action="{{ route('search') }}" method="GET" class="mt-1 flex overflow-hidden rounded-full border" style="border-color:var(--border);background:var(--primary_light);">
                <input name="q" class="min-w-0 flex-1 bg-transparent px-4 py-2.5 text-sm outline-none" placeholder="Ne aramıştınız?">
                <button class="px-4 text-sm font-black" style="color:var(--primary);">Ara</button>
            </form>
        </div>

        {{-- Feed --}}
        <div class="mt-4 space-y-4 px-4">
            @forelse($latestCompanies->take(9) as $company)
                <article class="overflow-hidden border bg-white" style="border-color:{{ $company->is_premium ? 'var(--accent)' : 'var(--border)' }};border-radius:var(--border_radius);box-shadow:var(--card_shadow);">
                    {{-- Card header --}}
                    <div class="flex items-center gap-2.5 px-3.5 py-3">
                        <span class="flex h-10 w-10 shrink-0 items-center justify-center overflow-hidden rounded-full text-base font-black" style="background:var(--primary_light);color:var(--primary);">
                            @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-cover">@else{{ mb_substr($company->name,0,1) }}@endif
                        </span>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('companies.show',$company->slug) }}" class="flex items-center gap-1 text-sm font-black" style="color:var(--text);">
                                <span class="truncate">{{ $company->name }}</span>
                                @if($company->is_verified)
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="var(--primary)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd"/></svg>
                                @endif
                            </a>
                            <p class="truncate text-[11px]" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</p>
                        </div>
                        @if($company->reviews_avg_rating)
                            <span class="shrink-0 rounded-full px-2 py-1 text-[11px] font-black" style="background:var(--primary_light);color:var(--primary);">★ {{ number_format($company->reviews_avg_rating,1) }}</span>
                        @endif
                    </div>

                    {{-- Visual area --}}
                    <a href="{{ route('companies.show',$company->slug) }}" class="story-ring relative flex h-44 items-center justify-center">
                        @if($company->logo)
                            <img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="max-h-28 max-w-[70%] rounded-xl object-contain bg-white/90 p-3">
                        @else
                            <span class="text-6xl font-black text-white/90">{{ mb_substr($company->name,0,1) }}</span>
                        @endif
                        @if($company->is_premium)
                            <span class="absolute left-3 top-3 rounded-full px-2.5 py-1 text-[10px] font-black text-white" style="background:var(--accent);">PREMİUM</span>
                        @endif
                    </a>

                    {{-- Action bar --}}
                    <div class="grid grid-cols-3 divide-x border-t text-center text-xs font-black" style="border-color:var(--border);">
                        <a href="{{ $company->phone ? 'tel:'.preg_replace('/\D+/','',$company->phone) : route('companies.show',$company->slug) }}" class="py-3" style="color:var(--primary);">📞 Ara</a>
                        <a href="{{ $company->whatsapp ? 'https://wa.me/'.preg_replace('/\D+/','',$company->whatsapp) : route('companies.show',$company->slug) }}" class="py-3" style="color:#16a34a;">💬 WhatsApp</a>
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode(trim(($company->address ?? '').' '.($company->city->name ?? ''))) }}" class="py-3" style="color:var(--secondary);">📍 Yol Tarifi</a>
                    </div>
                </article>
            @empty
                <div class="border p-10 text-center text-sm" style="border-color:var(--border);color:var(--text_muted);border-radius:var(--border_radius);">Akışta henüz firma yok.</div>
            @endforelse
        </div>
    </div>

    {{-- Bottom nav --}}
    <nav class="absolute inset-x-0 bottom-0 grid grid-cols-4 border-t bg-white px-1 py-1.5 text-center text-[11px] font-bold" style="border-color:var(--border);">
        <a href="{{ route('home') }}" class="rounded-xl py-2" style="background:var(--primary_light);color:var(--primary);">🏠<br>Akış</a>
        <a href="{{ route('companies.index') }}" class="py-2" style="color:var(--text_muted);">🏢<br>Firmalar</a>
        <a href="{{ route('blog.index') }}" class="py-2" style="color:var(--text_muted);">✨<br>Keşfet</a>
        <a href="{{ route('listing.create') }}" class="py-2" style="color:var(--text_muted);">➕<br>Ekle</a>
    </nav>
</div>
@endsection
