@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Sohbet Rehberi')
@section('meta_description', $settings->meta_description ?? 'Firmalarla mesajlaşır gibi iletişime geçin; arayın, WhatsApp açın, yol tarifi alın.')

@push('head')
<style>
    html.theme-chat-directory body > header,
    html.theme-chat-directory body > footer { display:none; }
    html.theme-chat-directory body { background:var(--bg) !important; }
    html.theme-chat-directory body > main { padding:18px 12px; }
    @media (max-width:520px) {
        html.theme-chat-directory body > main { padding:0; }
        .chat-shell { min-height:100vh !important; border:0 !important; border-radius:0 !important; }
    }
    html.theme-chat-directory #pwa-install-banner { bottom:60px !important; }
</style>
@endpush

@section('content')
<div class="chat-shell relative mx-auto min-h-[calc(100vh-36px)] overflow-hidden border shadow-2xl" style="max-width:480px;border-color:var(--border);border-radius:22px;background:var(--bg);">

    {{-- App bar (messenger style) --}}
    <div class="px-4 pb-3 pt-4" style="background:linear-gradient(135deg,var(--hero_gradient_from),var(--hero_gradient_to));">
        <div class="flex items-center justify-between">
            <div class="min-w-0">
                <strong class="block truncate text-lg text-white" style="font-family:var(--font_heading);">{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}</strong>
                <span class="text-[11px] text-white/80">{{ \App\Models\Company::active()->count() }} işletme çevrimiçi gibi listelendi</span>
            </div>
            <a href="{{ route('listing.create') }}" class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/20 text-lg font-black text-white" aria-label="Firma ekle">+</a>
        </div>
        <form action="{{ route('search') }}" method="GET" class="mt-3 flex items-center gap-2 rounded-full bg-white/95 px-4 py-2.5">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="var(--text_muted)" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
            <input name="q" class="min-w-0 flex-1 bg-transparent text-sm outline-none" placeholder="Firma, kategori veya şehir ara...">
        </form>
    </div>

    {{-- Filter chips --}}
    <div class="flex gap-2 overflow-x-auto px-4 py-3">
        <a href="{{ route('home') }}" class="shrink-0 rounded-full px-3.5 py-1.5 text-xs font-black text-white" style="background:var(--primary);">Tümü</a>
        @foreach($categories->take(6) as $category)
            <a href="{{ route('categories.show',$category->slug) }}" class="shrink-0 rounded-full border bg-white px-3.5 py-1.5 text-xs font-bold" style="border-color:var(--border);color:var(--text);">{{ $category->name }}</a>
        @endforeach
    </div>

    {{-- Conversation list --}}
    <div class="pb-28">
        @forelse($latestCompanies->take(9) as $company)
            <a href="{{ route('companies.show',$company->slug) }}" class="flex items-center gap-3 border-b bg-white px-4 py-3.5" style="border-color:var(--border);">
                <span class="relative flex h-12 w-12 shrink-0 items-center justify-center overflow-hidden rounded-full text-lg font-black" style="background:var(--primary_light);color:var(--primary);">
                    @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-cover">@else{{ mb_substr($company->name,0,1) }}@endif
                </span>
                <span class="min-w-0 flex-1">
                    <span class="flex items-center justify-between gap-2">
                        <span class="flex min-w-0 items-center gap-1 text-sm font-black" style="color:var(--text);">
                            <span class="truncate">{{ $company->name }}</span>
                            @if($company->is_verified)
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 shrink-0" viewBox="0 0 20 20" fill="var(--accent)"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.7-9.3a1 1 0 00-1.4-1.4L9 10.6 7.7 9.3a1 1 0 00-1.4 1.4l2 2a1 1 0 001.4 0l4-4z" clip-rule="evenodd"/></svg>
                            @endif
                        </span>
                        <span class="shrink-0 text-[10px]" style="color:var(--text_muted);">{{ $company->reviews_avg_rating ? '★ '.number_format($company->reviews_avg_rating,1) : 'yeni' }}</span>
                    </span>
                    <span class="mt-1 flex items-center justify-between gap-2">
                        <span class="truncate text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->city->name ?? 'Türkiye' }}</span>
                        @if($company->is_premium)
                            <span class="flex h-5 min-w-5 shrink-0 items-center justify-center rounded-full px-1.5 text-[10px] font-black text-white" style="background:var(--accent);">P</span>
                        @endif
                    </span>
                </span>
            </a>
        @empty
            <div class="mx-4 mt-6 rounded-xl border bg-white p-10 text-center text-sm" style="border-color:var(--border);color:var(--text_muted);">Henüz sohbet başlatılacak firma yok.</div>
        @endforelse
    </div>

    {{-- FAB: quick contact to first reachable company / add listing --}}
    <a href="{{ route('listing.create') }}" class="absolute bottom-20 right-4 flex h-14 w-14 items-center justify-center rounded-full text-2xl text-white shadow-xl" style="background:var(--accent);" aria-label="Firma ekle">💬</a>

    {{-- Bottom nav --}}
    <nav class="absolute inset-x-0 bottom-0 grid grid-cols-4 border-t bg-white px-1 py-1.5 text-center text-[11px] font-bold" style="border-color:var(--border);">
        <a href="{{ route('home') }}" class="rounded-xl py-2" style="background:var(--primary_light);color:var(--primary);">💬<br>Firmalar</a>
        <a href="{{ route('companies.index') }}" class="py-2" style="color:var(--text_muted);">📇<br>Rehber</a>
        <a href="{{ route('blog.index') }}" class="py-2" style="color:var(--text_muted);">📰<br>Yazılar</a>
        <a href="{{ route('listing.create') }}" class="py-2" style="color:var(--text_muted);">➕<br>Ekle</a>
    </nav>
</div>
@endsection
