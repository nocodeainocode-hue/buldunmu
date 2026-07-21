@extends('layouts.app')
@section('title', 'Çevrimdışı - ' . ($directory->name ?? 'Firma Rehberi'))
@section('content')
<div class="flex min-h-[70vh] flex-col items-center justify-center px-4 text-center">
    <div class="mb-6 flex h-20 w-20 items-center justify-center rounded-3xl" style="background:var(--primary_light);">
        <svg class="h-10 w-10" style="color:var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636a9 9 0 010 12.728M5.636 5.636a9 9 0 000 12.728M12 8v4l2 2"/>
        </svg>
    </div>
    <h1 class="mb-3 text-3xl font-black tracking-tight" style="color:var(--text);">İnternet bağlantısı yok</h1>
    <p class="mb-8 max-w-md text-sm leading-relaxed" style="color:var(--text_muted);">
        Şu anda çevrimdışısınız. İnternet bağlantınızı kontrol edin ve tekrar deneyin.
        Daha önce ziyaret ettiğiniz sayfalar çevrimdışıyken de görüntülenebilir.
    </p>
    <a href="{{ route('home') }}" class="inline-flex rounded-xl px-6 py-3 text-sm font-black text-white shadow-lg transition hover:opacity-90" style="background:var(--primary);">
        Ana Sayfaya Dön
    </a>
</div>
@endsection
