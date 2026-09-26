@extends('layouts.app')

@section('title', $company->name.' - İş İlanları')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <a href="{{ route('owner.dashboard') }}" class="text-sm font-bold" style="color:var(--primary);">← Firma paneline dön</a>
    <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">{{ $company->name }}</p>
            <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">İş ilanları</h1>
            <p class="mt-2 text-sm" style="color:var(--text_muted);">Yayındaki ilanlar hem firma sayfanızda hem rehberin iş ilanları bölümünde görünür.</p>
        </div>
        <a href="{{ route('jobs.index') }}" class="text-sm font-bold" style="color:var(--primary);">İş ilanları sayfası →</a>
    </div>

    @if(session('success'))<p class="mt-6 rounded-xl p-4 text-sm font-bold" style="background:#dcfce7;color:#166534;">{{ session('success') }}</p>@endif

    @if(! $company->hasActivePremium())
        <div class="mt-8 rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);">
            <h2 class="text-xl font-black" style="color:var(--text);">Premium iş ilanları</h2>
            <p class="mt-2 text-sm" style="color:var(--text_muted);">Firma sahibinin ilan ekleyip yayımlaması için aktif premium üyelik gerekir. Mevcut kayıtlarınız korunur.</p>
            <a href="{{ route('packages.index') }}" class="mt-4 inline-block rounded-xl px-5 py-3 text-sm font-black text-white" style="background:var(--primary);">Paketleri incele</a>
        </div>
    @else
        <section class="mt-8 rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);">
            <h2 class="text-xl font-black" style="color:var(--text);">{{ $editing ? 'İlanı düzenle' : 'Yeni iş ilanı ekle' }}</h2>
            <form action="{{ $editing ? route('owner.jobs.update', [$company, $editing]) : route('owner.jobs.store', $company) }}" method="POST" class="mt-5 grid gap-4 sm:grid-cols-2">
                @csrf
                @if($editing) @method('PUT') @endif
                <label class="text-sm font-bold sm:col-span-2" style="color:var(--text);">Pozisyon adı
                    <input name="title" required maxlength="180" value="{{ old('title', $editing?->title) }}" placeholder="Örn. Satış danışmanı" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold sm:col-span-2" style="color:var(--text);">İş tanımı ve aranan nitelikler
                    <textarea name="description" required rows="9" maxlength="20000" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('description', $editing?->description) }}</textarea>
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Çalışma şekli
                    <select name="employment_type" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        @foreach(['full_time' => 'Tam zamanlı', 'part_time' => 'Yarı zamanlı', 'contract' => 'Sözleşmeli', 'internship' => 'Staj'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('employment_type', $editing?->employment_type ?? 'full_time') === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Çalışma konumu
                    <input name="location" maxlength="180" value="{{ old('location', $editing?->location ?: $company->city?->name) }}" placeholder="Örn. Tekirdağ / Uzaktan" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Başvuru e-postası
                    <input name="apply_email" type="email" value="{{ old('apply_email', $editing?->apply_email ?: $company->email) }}" placeholder="ik@firma.com" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Başvuru bağlantısı
                    <input name="apply_url" type="url" value="{{ old('apply_url', $editing?->apply_url) }}" placeholder="https://..." class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <p class="text-xs sm:col-span-2" style="color:var(--text_muted);">Adayların başvurabilmesi için e-posta veya bağlantıdan en az birini doldurun.</p>
                <label class="text-sm font-bold" style="color:var(--text);">Son başvuru tarihi
                    <input name="expires_at" type="date" value="{{ old('expires_at', $editing?->expires_at?->format('Y-m-d') ?? now()->addDays(30)->format('Y-m-d')) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Yayın durumu
                    <select name="status" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        <option value="published" @selected(old('status', $editing?->status ?? 'published') === 'published')>Yayında</option>
                        <option value="draft" @selected(old('status', $editing?->status) === 'draft')>Taslak</option>
                    </select>
                </label>
                @if($errors->any())<div class="text-sm text-red-600 sm:col-span-2">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
                <div class="flex items-center gap-4 sm:col-span-2">
                    <button class="rounded-xl px-6 py-3 text-sm font-black text-white" style="background:var(--primary);">{{ $editing ? 'İlanı güncelle' : 'İlanı kaydet' }}</button>
                    @if($editing)<a href="{{ route('owner.jobs.index', $company) }}" class="text-sm font-bold" style="color:var(--text_muted);">Vazgeç</a>@endif
                </div>
            </form>
        </section>
    @endif

    <section class="mt-8">
        <h2 class="text-xl font-black" style="color:var(--text);">Firmanızın ilanları</h2>
        <div class="mt-4 grid gap-4 md:grid-cols-2">
            @forelse($jobs as $job)
                <article class="rounded-2xl border p-5" style="border-color:var(--border);background:var(--bg_card);">
                    <p class="text-xs font-black uppercase" style="color:var(--primary);">{{ $job->isPubliclyVisible() ? 'Yayında' : ($job->status === 'draft' ? 'Taslak' : 'Yayında değil') }} · {{ $job->expires_at?->format('d.m.Y') ?? 'Süresiz' }}</p>
                    <h3 class="mt-2 text-lg font-black" style="color:var(--text);">{{ $job->title }}</h3>
                    @if($company->hasActivePremium())
                        <div class="mt-4 flex gap-4 text-sm font-bold">
                            <a href="{{ route('owner.jobs.edit', [$company, $job]) }}" style="color:var(--primary);">Düzenle</a>
                            <form action="{{ route('owner.jobs.destroy', [$company, $job]) }}" method="POST" onsubmit="return confirm('Bu ilan silinsin mi?')">@csrf @method('DELETE')<button class="text-red-600">Sil</button></form>
                        </div>
                    @endif
                </article>
            @empty
                <p class="rounded-2xl border p-5 text-sm md:col-span-2" style="border-color:var(--border);color:var(--text_muted);">Henüz iş ilanı eklenmedi.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
