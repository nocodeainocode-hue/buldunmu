@extends('layouts.app')

@section('title', 'Firma Paneli Girişi')
@section('robots', 'noindex,follow')

@section('content')
<div class="mx-auto max-w-md px-4 py-14 sm:px-6">
    <div class="rounded-3xl border p-7" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
        <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Firma paneli</p>
        <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">Profilinize giriş yapın</h1>
        <p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">Bu rehberdeki firma profilinizi, iletişim bilgilerinizi ve açıklamanızı yönetin.</p>

        <form action="{{ route('owner.login.store') }}" method="POST" class="mt-7 space-y-4">
            @csrf
            <label class="block text-sm font-bold" style="color:var(--text);">E-posta
                <input name="email" type="email" value="{{ old('email') }}" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
            </label>
            <label class="block text-sm font-bold" style="color:var(--text);">Şifre
                <input name="password" type="password" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
            </label>
            @error('email')<p class="text-sm font-semibold text-red-600">{{ $message }}</p>@enderror
            <label class="flex items-center gap-2 text-sm" style="color:var(--text_muted);"><input type="checkbox" name="remember" value="1"> Beni hatırla</label>
            <button class="w-full rounded-xl px-5 py-3 font-black text-white" style="background:var(--primary);">Panele Giriş Yap</button>
        </form>

        <p class="mt-6 text-center text-sm" style="color:var(--text_muted);">Hesabınız yok mu? <a href="{{ route('owner.register') }}" class="font-black" style="color:var(--primary);">Firmanızı ücretsiz ekleyin</a></p>
    </div>
</div>
@endsection
