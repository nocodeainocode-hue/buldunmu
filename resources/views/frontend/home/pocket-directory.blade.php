@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Cep Rehberi')
@section('meta_description', $settings->meta_description ?? 'Firmaları mobil uygulama rahatlığında arayın, filtreleyin ve iletişime geçin.')

@push('head')
<style>
    html.theme-pocket-directory body > header,
    html.theme-pocket-directory body > footer { display:none; }
    html.theme-pocket-directory body { background:#eaf2f4 !important; }
    html.theme-pocket-directory body > main { padding:18px 12px; }
    @media (max-width:520px) {
        html.theme-pocket-directory body > main { padding:0; }
        .pocket-shell { min-height:100vh !important; border:0 !important; border-radius:0 !important; }
    }
</style>
@endpush

@section('content')
<div class="pocket-shell relative mx-auto min-h-[calc(100vh-36px)] overflow-hidden border bg-white shadow-2xl" style="max-width:460px;border-color:var(--border);border-radius:18px;">
    <div class="flex items-center justify-between border-b px-4 py-2 text-[11px]" style="border-color:var(--border);color:var(--text_muted);">
        <span>{{ now()->format('d.m.Y, H:i') }}</span>
        <span class="rounded-full px-2 py-1 font-bold" style="background:var(--primary_light);color:var(--primary);">Türkiye</span>
    </div>

    <div class="flex items-center justify-between border-b px-4 py-4" style="border-color:var(--border);">
        <a href="{{ route('home') }}" class="flex min-w-0 items-center gap-2">
            <span class="flex h-9 w-9 items-center justify-center rounded-lg text-sm font-black text-white" style="background:var(--primary);">{{ mb_substr($directory->name ?? $settings->site_name ?? 'R', 0, 1) }}</span>
            <strong class="truncate text-base" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}</strong>
        </a>
        <a href="{{ route('listing.create') }}" class="shrink-0 rounded-lg border px-3 py-2 text-xs font-bold" style="border-color:var(--border);color:var(--primary);">+ Firma ekle</a>
    </div>

    <div class="px-4 pb-24 pt-5">
        <div class="flex items-start justify-between gap-4">
            <div><h1 class="text-2xl font-black" style="color:var(--text);">Rehber</h1><p class="mt-1 text-sm" style="color:var(--text_muted);">İşletmeleri hızlıca bulun ve ulaşın.</p></div>
            <span class="rounded-lg px-3 py-2 text-center text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">{{ \App\Models\Company::active()->count() }}<br>işletme</span>
        </div>

        <form action="{{ route('search') }}" method="GET" class="mt-5 flex overflow-hidden rounded-lg border" style="border-color:var(--border);">
            <input name="q" class="min-w-0 flex-1 px-4 py-3 text-sm outline-none" placeholder="İşletme, kategori veya konum ara...">
            <button class="px-4 text-sm font-black text-white" style="background:var(--primary);">Ara</button>
        </form>

        <div class="mt-3 flex gap-2 overflow-x-auto pb-1">
            @foreach($categories->take(5) as $category)
                <a href="{{ route('categories.show',$category->slug) }}" class="shrink-0 rounded-full border px-3 py-1.5 text-xs font-bold" style="border-color:var(--border);color:var(--text);">{{ $category->name }}</a>
            @endforeach
        </div>

        <div class="mt-6 space-y-3">
            @forelse($latestCompanies->take(8) as $company)
                <article class="rounded-lg border p-3" style="border-color:{{ $company->is_premium ? 'var(--accent)' : 'var(--border)' }};box-shadow:var(--card_shadow);">
                    <div class="flex gap-3">
                        <div class="flex h-14 w-14 shrink-0 items-center justify-center overflow-hidden rounded-lg text-lg font-black" style="background:var(--primary_light);color:var(--primary);">
                            @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain">@else{{ mb_substr($company->name,0,1) }}@endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <a href="{{ route('companies.show',$company->slug) }}" class="block truncate text-sm font-black" style="color:var(--text);">{{ $company->name }}</a>
                            <div class="mt-1 flex flex-wrap gap-1 text-[10px]">
                                @if($company->is_verified)<span class="rounded px-1.5 py-1" style="background:#e9f8ef;color:#16794a;">Doğrulanmış</span>@endif
                                <span class="rounded px-1.5 py-1" style="background:var(--primary_light);color:var(--primary);">{{ $company->category->name ?? 'Firma' }}</span>
                            </div>
                            <p class="mt-2 line-clamp-2 text-xs leading-5" style="color:var(--text_muted);">{{ $company->city->name ?? '' }}{{ $company->address ? ' · '.$company->address : '' }}</p>
                        </div>
                    </div>
                    <div class="mt-3 grid grid-cols-3 gap-2 border-t pt-3 text-center text-xs font-bold" style="border-color:var(--border);">
                        <a href="{{ route('companies.show',$company->slug) }}" style="color:var(--primary);">Detay</a>
                        <a href="{{ $company->phone ? 'tel:'.preg_replace('/\D+/','',$company->phone) : route('companies.show',$company->slug) }}" style="color:#16794a;">Telefon</a>
                        <a href="{{ $company->whatsapp ? 'https://wa.me/'.preg_replace('/\D+/','',$company->whatsapp) : route('companies.show',$company->slug) }}" style="color:#16794a;">WhatsApp</a>
                    </div>
                </article>
            @empty
                <div class="rounded-lg border p-8 text-center text-sm" style="border-color:var(--border);color:var(--text_muted);">Henüz firma eklenmedi.</div>
            @endforelse
        </div>
    </div>

    <nav class="absolute inset-x-0 bottom-0 grid grid-cols-4 border-t bg-white px-2 py-2 text-center text-[11px] font-bold" style="border-color:var(--border);">
        <a href="{{ route('home') }}" class="rounded-lg py-2" style="background:var(--primary_light);color:var(--primary);">Rehber</a>
        <a href="{{ route('companies.index') }}" class="py-2" style="color:var(--text_muted);">Firmalar</a>
        <a href="{{ route('blog.index') }}" class="py-2" style="color:var(--text_muted);">Keşfet</a>
        <a href="{{ route('listing.create') }}" class="py-2" style="color:var(--text_muted);">Firma Ekle</a>
    </nav>
</div>
@endsection
