@extends('layouts.app')

@section('title', $company->name . ' - Firma Paneli')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-3xl px-4 py-10 sm:px-6">
    <a href="{{ route('owner.dashboard') }}" class="text-sm font-bold" style="color:var(--primary);">← Firma paneline dön</a>
    <div class="mt-5 rounded-3xl border p-6 sm:p-8" style="border-color:var(--border);background:var(--bg_card);box-shadow:var(--card_shadow);">
        <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Firma profili</p>
        <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">{{ $company->name }}</h1>
        <p class="mt-2 text-sm" style="color:var(--text_muted);">Değişiklikleriniz kaydedilir. Firma yayında değilse yönetim incelemesinden sonra görünür.</p>

        <form action="{{ route('owner.company.update', $company->slug) }}" method="POST" class="mt-7 grid gap-4 sm:grid-cols-2">
            @csrf @method('PUT')
            <label class="block text-sm font-bold" style="color:var(--text);">Telefon<input name="phone" value="{{ old('phone', $company->phone) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
            <label class="block text-sm font-bold" style="color:var(--text);">WhatsApp<input name="whatsapp" value="{{ old('whatsapp', $company->whatsapp) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
            <label class="block text-sm font-bold" style="color:var(--text);">E-posta<input name="email" type="email" value="{{ old('email', $company->email) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
            <label class="block text-sm font-bold" style="color:var(--text);">Web sitesi<input name="website" type="url" value="{{ old('website', $company->website) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);"></label>
            <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Adres<textarea name="address" rows="2" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('address', $company->address) }}</textarea></label>
            <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Kısa açıklama<textarea name="short_description" rows="3" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('short_description', $company->short_description) }}</textarea></label>
            <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Firma açıklaması<textarea name="description" rows="8" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('description', $company->description) }}</textarea></label>
            <label class="block text-sm font-bold sm:col-span-2" style="color:var(--text);">Çalışma saatleri<textarea name="opening_hours" rows="5" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('opening_hours', $company->opening_hours) }}</textarea></label>
            @if($errors->any())<p class="text-sm font-bold text-red-600 sm:col-span-2">Lütfen girdiğiniz bilgileri kontrol edin.</p>@endif
            <button class="rounded-xl px-5 py-3 font-black text-white sm:col-span-2" style="background:var(--primary);">Değişiklikleri Kaydet</button>
        </form>
    </div>
</div>
@endsection
