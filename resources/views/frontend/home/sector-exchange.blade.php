@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Sektör Borsası')
@section('meta_description', $settings->meta_description ?? 'Sektörlerdeki firma yoğunluğunu, güvenilir işletmeleri ve yeni kayıtları veri odaklı görünümde inceleyin.')

@section('content')
@php
    $exchangeCompanies = $trustedCompanies->isNotEmpty() ? $trustedCompanies->take(7) : $latestCompanies->take(7);
    $totalCompanies = max(1, \App\Models\Company::active()->count());
@endphp
<main class="min-h-screen" style="background:var(--bg);">
    <section class="border-b bg-white" style="border-color:var(--border);">
        <div class="mx-auto px-4 py-8 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
            <div class="flex flex-wrap items-center justify-between gap-3 border-b pb-4 text-xs font-black uppercase tracking-wider" style="border-color:var(--border);color:var(--text_muted);">
                <span>Türkiye işletme göstergeleri</span><span>{{ now()->translatedFormat('d F Y') }} · Güncel veri</span>
            </div>
            <div class="grid gap-8 py-9 lg:grid-cols-[1fr_440px] lg:items-end">
                <div><div class="text-xs font-black uppercase tracking-[0.2em]" style="color:var(--primary);">Sektörleri takip edin</div><h1 class="mt-3 max-w-4xl text-4xl font-black leading-tight sm:text-5xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Firma hareketliliğini tek ekranda görün' }}</h1><p class="mt-4 max-w-2xl text-base leading-7" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Yoğun kategorileri, aktif şehirleri ve doğrulanmış işletmeleri karşılaştırın.' }}</p></div>
                <form action="{{ route('search') }}" class="flex overflow-hidden border bg-white" style="border-color:var(--border);"><input name="q" class="min-w-0 flex-1 px-4 py-4 text-sm outline-none" placeholder="Sektör, firma veya şehir"><button class="px-6 text-sm font-black text-white" style="background:var(--primary);">Veride ara</button></form>
            </div>
        </div>
    </section>

    <section class="mx-auto grid gap-5 px-4 py-6 sm:px-6 lg:grid-cols-[minmax(0,1fr)_340px] lg:px-8" style="max-width:var(--page_width);">
        <div class="min-w-0">
            <div class="mb-3 flex items-center justify-between"><h2 class="text-lg font-black" style="color:var(--text);">Sektör göstergeleri</h2><span class="text-xs font-bold" style="color:var(--primary);">Firma yoğunluğuna göre</span></div>
            <div class="overflow-hidden border bg-white" style="border-color:var(--border);">
                <div class="grid grid-cols-[minmax(0,1fr)_90px_110px] border-b px-4 py-3 text-xs font-black uppercase tracking-wider" style="border-color:var(--border);color:var(--text_muted);"><span>Sektör</span><span>Kayıt</span><span>Yoğunluk</span></div>
                @foreach($categories->take(10) as $index => $category)
                    @php $share = min(100, max(5, (int) round(($category->companies_count / $totalCompanies) * 100))); @endphp
                    <a href="{{ route('categories.show',$category->slug) }}" class="grid grid-cols-[minmax(0,1fr)_90px_110px] items-center border-b px-4 py-3 last:border-0" style="border-color:var(--border);">
                        <span class="min-w-0"><strong class="block truncate text-sm" style="color:var(--text);">{{ $category->name }}</strong><small style="color:var(--text_muted);">Sıra {{ str_pad($index + 1,2,'0',STR_PAD_LEFT) }}</small></span>
                        <span class="text-sm font-black" style="color:var(--secondary);">{{ $category->companies_count }}</span>
                        <span class="h-2 overflow-hidden" style="background:var(--border);"><span class="block h-full" style="width:{{ $share }}%;background:{{ $index < 3 ? 'var(--primary)' : 'var(--secondary)' }};"></span></span>
                    </a>
                @endforeach
            </div>
        </div>

        <aside class="space-y-4">
            <div class="border bg-white p-5" style="border-color:var(--border);">
                <div class="text-xs font-black uppercase tracking-wider" style="color:var(--text_muted);">Piyasa özeti</div>
                <div class="mt-5 grid grid-cols-2 gap-px border" style="border-color:var(--border);background:var(--border);">
                    <div class="bg-white p-4"><strong class="text-2xl" style="color:var(--primary);">{{ $totalCompanies }}</strong><small class="block" style="color:var(--text_muted);">Firma</small></div>
                    <div class="bg-white p-4"><strong class="text-2xl" style="color:var(--secondary);">{{ $categories->count() }}</strong><small class="block" style="color:var(--text_muted);">Sektör</small></div>
                    <div class="bg-white p-4"><strong class="text-2xl" style="color:var(--accent);">{{ $trustedCompanies->count() }}</strong><small class="block" style="color:var(--text_muted);">Doğrulanmış</small></div>
                    <div class="bg-white p-4"><strong class="text-2xl" style="color:var(--text);">{{ $cities->count() }}</strong><small class="block" style="color:var(--text_muted);">Şehir</small></div>
                </div>
            </div>
            <a href="{{ route('listing.create') }}" class="block p-5 text-white" style="background:var(--secondary);"><span class="text-xs font-black uppercase tracking-wider text-white/70">Firma sahipleri</span><strong class="mt-2 block text-xl">Sektör tablosuna katılın</strong><small class="mt-2 block leading-5 text-white/75">İşletme profilinizi oluşturun ve görünürlüğünüzü artırın.</small></a>
        </aside>
    </section>

    <section class="border-y bg-white py-10" style="border-color:var(--border);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width);">
            <div class="mb-5 flex items-end justify-between"><div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--primary);">Güven ekranı</div><h2 class="mt-1 text-2xl font-black" style="color:var(--text);">İncelenebilecek firmalar</h2></div><a href="{{ route('companies.index') }}" class="text-sm font-black" style="color:var(--secondary);">Tüm kayıtlar</a></div>
            <div class="overflow-x-auto border" style="border-color:var(--border);"><table class="w-full min-w-[780px] text-left"><thead><tr class="border-b text-xs font-black uppercase tracking-wider" style="border-color:var(--border);color:var(--text_muted);"><th class="p-4">Firma</th><th class="p-4">Sektör</th><th class="p-4">Şehir</th><th class="p-4">Durum</th><th class="p-4">Profil</th></tr></thead><tbody>@forelse($exchangeCompanies as $company)<tr class="border-b last:border-0" style="border-color:var(--border);"><td class="p-4 text-sm font-black" style="color:var(--text);">{{ $company->name }}</td><td class="p-4 text-sm" style="color:var(--text_muted);">{{ $company->category->name ?? '-' }}</td><td class="p-4 text-sm" style="color:var(--text_muted);">{{ $company->city->name ?? '-' }}</td><td class="p-4"><span class="px-2 py-1 text-xs font-black" style="background:{{ $company->is_verified ? 'var(--primary_light)' : 'var(--bg)' }};color:{{ $company->is_verified ? 'var(--primary)' : 'var(--text_muted)' }};">{{ $company->is_verified ? 'Doğrulanmış' : 'Standart' }}</span></td><td class="p-4"><a href="{{ route('companies.show',$company->slug) }}" class="text-xs font-black" style="color:var(--secondary);">Aç</a></td></tr>@empty<tr><td colspan="5" class="p-10 text-center text-sm" style="color:var(--text_muted);">Firma kaydı bulunamadı.</td></tr>@endforelse</tbody></table></div>
        </div>
    </section>

    <section class="mx-auto grid gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[0.7fr_1.3fr] lg:px-8" style="max-width:var(--page_width);">
        <div><div class="text-xs font-black uppercase tracking-wider" style="color:var(--accent);">Şehir dağılımı</div><h2 class="mt-2 text-3xl font-black" style="color:var(--text);">Aktif pazarlar</h2></div>
        <div class="grid gap-2 sm:grid-cols-2 lg:grid-cols-3">@foreach($cities->take(9) as $city)<a href="{{ route('cities.show',$city->slug) }}" class="flex items-center justify-between border bg-white p-4" style="border-color:var(--border);"><strong class="text-sm" style="color:var(--text);">{{ $city->name }}</strong><span class="text-xs font-black" style="color:var(--primary);">{{ $city->companies_count }}</span></a>@endforeach</div>
    </section>
    @include('partials.blog-section')
</main>
@endsection
