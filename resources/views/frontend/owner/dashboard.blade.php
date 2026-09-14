@extends('layouts.app')

@section('title', 'Firma Panelim')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    @if(session('success'))<div class="mb-6 rounded-xl px-4 py-3 text-sm font-bold" style="background:#dcfce7;color:#166534;">{{ session('success') }}</div>@endif
    <div class="flex flex-col justify-between gap-4 sm:flex-row sm:items-end">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">{{ $directory->name }}</p>
            <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">Firma Panelim</h1>
            <p class="mt-2" style="color:var(--text_muted);">Hoş geldiniz, {{ auth()->user()->name }}. Bu rehberde sahip olduğunuz profiller burada.</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('owner.campaigns') }}" class="rounded-xl px-4 py-2 text-sm font-black text-white" style="background:var(--primary);">Kampanyalar</a>
            <form method="POST" action="{{ route('owner.logout') }}">@csrf<button class="rounded-xl border px-4 py-2 text-sm font-bold" style="border-color:var(--border);color:var(--text);">Çıkış yap</button></form>
        </div>
    </div>

    <div class="mt-8 grid gap-5 md:grid-cols-2">
        @forelse($companies as $company)
            <section class="rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <div class="text-xs font-black" style="color:{{ $company->status === 'active' ? '#15803d' : '#b45309' }};">{{ $company->status === 'active' ? 'YAYINDA' : 'İNCELEMEDE' }}</div>
                        <h2 class="mt-2 text-xl font-black" style="color:var(--text);">{{ $company->name }}</h2>
                        <p class="mt-1 text-sm" style="color:var(--text_muted);">{{ $company->category?->name }} · {{ $company->city?->name }}</p>
                    </div>
                    @if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" class="h-12 w-12 rounded-xl object-contain" alt="">@endif
                </div>
                <div class="mt-5 flex items-center justify-between border-t pt-4 text-sm" style="border-color:var(--border);">
                    <span style="color:var(--text_muted);">Profil kapsamı <strong style="color:var(--text);">%{{ $company->profileCompletionScore() }}</strong></span>
                    <a href="{{ route('owner.company.edit', $company->slug) }}" class="font-black" style="color:var(--primary);">Profili düzenle →</a>
                </div>
            </section>
        @empty
            <section class="rounded-3xl border p-8 md:col-span-2" style="border-color:var(--border);background:var(--bg_card);">
                <h2 class="text-xl font-black" style="color:var(--text);">Bu rehberde henüz firmanız yok.</h2>
                <p class="mt-2" style="color:var(--text_muted);">Firmanızı eklemek için bu rehberdeki ücretsiz kayıt sayfasını kullanın.</p>
            </section>
        @endforelse
    </div>
</div>
@endsection
