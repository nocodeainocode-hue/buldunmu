@extends('layouts.app')

@section('title', $company->name.' - Ürün ve Hizmet Vitrini')
@section('robots', 'noindex,nofollow')

@section('content')
<div class="mx-auto max-w-6xl px-4 py-10 sm:px-6">
    <a href="{{ route('owner.dashboard') }}" class="text-sm font-bold" style="color:var(--primary);">← Firma paneline dön</a>
    <div class="mt-5 flex flex-wrap items-end justify-between gap-4">
        <div>
            <p class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">{{ $company->name }}</p>
            <h1 class="mt-2 text-3xl font-black" style="color:var(--text);">Ürün ve hizmet vitrini</h1>
            <p class="mt-2 text-sm" style="color:var(--text_muted);">Ürünlerinizi ve hizmetlerinizi firma profilinizde ayrı kartlarla gösterin.</p>
        </div>
        <a href="{{ route('companies.show', $company->slug) }}" class="text-sm font-bold" style="color:var(--primary);">Firma sayfasını gör →</a>
    </div>

    @if(session('success'))<p class="mt-6 rounded-xl p-4 text-sm font-bold" style="background:#dcfce7;color:#166534;">{{ session('success') }}</p>@endif

    @if(! $company->hasActivePremium())
        <div class="mt-8 rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);">
            <h2 class="text-xl font-black" style="color:var(--text);">Premium vitrin</h2>
            <p class="mt-2 text-sm" style="color:var(--text_muted);">Ürün ve hizmet eklemek ve yayımlamak için firmanızda aktif premium üyelik gerekir. Mevcut kayıtlarınız korunur.</p>
            <a href="{{ route('packages.index') }}" class="mt-4 inline-block rounded-xl px-5 py-3 text-sm font-black text-white" style="background:var(--primary);">Paketleri incele</a>
        </div>
    @else
        <section class="mt-8 rounded-3xl border p-6" style="border-color:var(--border);background:var(--bg_card);">
            <h2 class="text-xl font-black" style="color:var(--text);">{{ $editing ? 'Vitrin öğesini düzenle' : 'Yeni ürün veya hizmet ekle' }}</h2>
            <form action="{{ $editing ? route('owner.offerings.update', [$company, $editing]) : route('owner.offerings.store', $company) }}" method="POST" enctype="multipart/form-data" class="mt-5 grid gap-4 sm:grid-cols-2">
                @csrf
                @if($editing) @method('PUT') @endif
                <label class="text-sm font-bold" style="color:var(--text);">Tür
                    <select name="type" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        <option value="service" @selected(old('type', $editing?->type ?? 'service') === 'service')>Hizmet</option>
                        <option value="product" @selected(old('type', $editing?->type) === 'product')>Ürün</option>
                    </select>
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Ad
                    <input name="name" required maxlength="180" value="{{ old('name', $editing?->name) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold sm:col-span-2" style="color:var(--text);">Açıklama
                    <textarea name="description" rows="4" maxlength="3000" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">{{ old('description', $editing?->description) }}</textarea>
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Fiyat (isteğe bağlı, TL)
                    <input name="price" type="number" min="0" step="0.01" value="{{ old('price', $editing?->price) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Görsel (JPG, PNG veya WebP)
                    <input name="image" type="file" accept="image/jpeg,image/png,image/webp" class="mt-1.5 w-full rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);background:var(--bg);">
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Yayın durumu
                    <select name="status" required class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                        <option value="active" @selected(old('status', $editing?->status ?? 'active') === 'active')>Yayında</option>
                        <option value="draft" @selected(old('status', $editing?->status) === 'draft')>Taslak</option>
                    </select>
                </label>
                <label class="text-sm font-bold" style="color:var(--text);">Sıralama
                    <input name="sort_order" type="number" min="0" value="{{ old('sort_order', $editing?->sort_order ?? 0) }}" class="mt-1.5 w-full rounded-xl border px-4 py-3" style="border-color:var(--border);background:var(--bg);">
                </label>
                @if($errors->any())<div class="text-sm text-red-600 sm:col-span-2">@foreach($errors->all() as $error)<p>{{ $error }}</p>@endforeach</div>@endif
                <div class="flex items-center gap-4 sm:col-span-2">
                    <button class="rounded-xl px-6 py-3 text-sm font-black text-white" style="background:var(--primary);">{{ $editing ? 'Değişiklikleri kaydet' : 'Vitrine ekle' }}</button>
                    @if($editing)<a href="{{ route('owner.offerings.index', $company) }}" class="text-sm font-bold" style="color:var(--text_muted);">Vazgeç</a>@endif
                </div>
            </form>
        </section>
    @endif

    <section class="mt-8">
        <h2 class="text-xl font-black" style="color:var(--text);">Kayıtlı ürün ve hizmetler</h2>
        <div class="mt-4 grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse($offerings as $offering)
                <article class="overflow-hidden rounded-2xl border" style="border-color:var(--border);background:var(--bg_card);">
                    @if($offering->image_path)<img src="{{ asset('storage/'.$offering->image_path) }}" alt="{{ $offering->name }}" class="h-40 w-full object-cover">@endif
                    <div class="p-5">
                        <p class="text-xs font-black uppercase" style="color:var(--primary);">{{ $offering->type === 'product' ? 'Ürün' : 'Hizmet' }} · {{ $offering->status === 'draft' ? 'Taslak' : ($company->hasActivePremium() ? 'Yayında' : 'Gizli') }}</p>
                        <h3 class="mt-2 text-lg font-black" style="color:var(--text);">{{ $offering->name }}</h3>
                        @if($offering->price !== null)<p class="mt-1 font-bold" style="color:var(--primary);">{{ number_format((float) $offering->price, 2, ',', '.') }} TL</p>@endif
                        @if($company->hasActivePremium())
                            <div class="mt-4 flex gap-4 text-sm font-bold">
                                <a href="{{ route('owner.offerings.edit', [$company, $offering]) }}" style="color:var(--primary);">Düzenle</a>
                                <form action="{{ route('owner.offerings.destroy', [$company, $offering]) }}" method="POST" onsubmit="return confirm('Bu öğe silinsin mi?')">@csrf @method('DELETE')<button class="text-red-600">Sil</button></form>
                            </div>
                        @endif
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border p-5 text-sm sm:col-span-2 lg:col-span-3" style="border-color:var(--border);color:var(--text_muted);">Henüz ürün veya hizmet eklenmedi.</p>
            @endforelse
        </div>
    </section>
</div>
@endsection
