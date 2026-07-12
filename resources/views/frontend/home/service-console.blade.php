@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Hizmet Konsolu')
@section('meta_description', $settings->meta_description ?? 'Kategori, şehir ve firma bilgilerini yoğun ve hızlı bir hizmet konsolunda inceleyin.')

@section('content')
<div class="min-h-screen" style="background:var(--bg);">
    <div class="border-b" style="background:var(--bg_card);border-color:var(--border);">
        <div class="mx-auto flex flex-col gap-5 px-4 py-8 lg:flex-row lg:items-end lg:justify-between lg:px-8" style="max-width:var(--page_width);">
            <div><div class="text-xs font-black uppercase tracking-[0.18em]" style="color:var(--primary);">Hizmet ağı</div><h1 class="mt-2 text-3xl font-black" style="color:var(--text);">{{ $settings->homepage_title ?? 'İhtiyacınız olan firmaya doğrudan ulaşın' }}</h1><p class="mt-2 text-sm" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Kategorileri tarayın, şehir seçin ve güncel işletme kayıtlarını inceleyin.' }}</p></div>
            <form action="{{ route('search') }}" class="flex w-full overflow-hidden rounded-md border lg:max-w-xl" style="border-color:var(--border);"><input name="q" class="min-w-0 flex-1 px-4 py-3 text-sm outline-none" placeholder="Firma, hizmet veya şehir"><button class="px-6 text-sm font-black text-white" style="background:var(--primary);">Bul</button></form>
        </div>
    </div>

    <div class="mx-auto grid gap-5 px-4 py-6 lg:grid-cols-[240px_minmax(0,1fr)_300px] lg:px-8" style="max-width:var(--page_width);">
        <aside class="h-fit rounded-md border bg-white p-3 lg:sticky lg:top-20" style="border-color:var(--border);">
            <div class="px-2 pb-3 text-xs font-black uppercase tracking-wider" style="color:var(--text_muted);">Kategoriler</div>
            @foreach($categories as $category)<a href="{{ route('categories.show',$category->slug) }}" class="flex items-center justify-between rounded-md px-3 py-2.5 text-sm font-bold hover:bg-emerald-50" style="color:var(--text);"><span class="truncate">{{ $category->name }}</span><span class="text-xs" style="color:var(--text_muted);">{{ $category->companies_count }}</span></a>@endforeach
            <a href="{{ route('companies.index') }}" class="mt-2 block border-t px-3 pt-3 text-sm font-black" style="border-color:var(--border);color:var(--primary);">Tüm firmalar</a>
        </aside>

        <section class="min-w-0">
            <div class="mb-3 flex items-center justify-between"><h2 class="text-lg font-black" style="color:var(--text);">Güncel firma akışı</h2><span class="text-xs" style="color:var(--text_muted);">{{ \App\Models\Company::active()->count() }} kayıt</span></div>
            <div class="overflow-hidden rounded-md border bg-white" style="border-color:var(--border);">
                @forelse($latestCompanies as $company)
                    <article class="grid gap-3 border-b p-4 sm:grid-cols-[52px_minmax(0,1fr)_auto] sm:items-center" style="border-color:var(--border);">
                        <div class="flex h-12 w-12 items-center justify-center overflow-hidden rounded-md font-black" style="background:var(--primary_light);color:var(--primary);">@if($company->logo)<img src="{{ asset('storage/'.$company->logo) }}" alt="{{ $company->name }}" class="h-full w-full object-contain">@else{{ mb_substr($company->name,0,1) }}@endif</div>
                        <div class="min-w-0"><a href="{{ route('companies.show',$company->slug) }}" class="block truncate text-sm font-black" style="color:var(--text);">{{ $company->name }}</a><p class="mt-1 truncate text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? '' }} · {{ $company->city->name ?? '' }}{{ $company->district ? ' / '.$company->district->name : '' }}</p></div>
                        <div class="flex gap-2"><a href="{{ route('companies.show',$company->slug) }}" class="rounded-md border px-3 py-2 text-xs font-black" style="border-color:var(--border);color:var(--primary);">İncele</a>@if($company->phone)<a href="tel:{{ preg_replace('/\D+/','',$company->phone) }}" class="rounded-md px-3 py-2 text-xs font-black text-white" style="background:var(--primary);">Ara</a>@endif</div>
                    </article>
                @empty<div class="p-10 text-center text-sm" style="color:var(--text_muted);">Henüz firma kaydı yok.</div>@endforelse
            </div>
        </section>

        <aside class="space-y-4">
            <div class="rounded-md border bg-white p-4" style="border-color:var(--border);"><div class="text-xs font-black uppercase tracking-wider" style="color:var(--text_muted);">Hızlı durum</div><div class="mt-4 grid grid-cols-2 gap-2"><div class="rounded-md p-3" style="background:var(--primary_light);"><strong class="text-2xl" style="color:var(--primary);">{{ $categories->count() }}</strong><span class="block text-xs" style="color:var(--text_muted);">Kategori</span></div><div class="rounded-md p-3" style="background:#eef6fa;"><strong class="text-2xl" style="color:var(--secondary);">{{ $cities->count() }}</strong><span class="block text-xs" style="color:var(--text_muted);">Aktif şehir</span></div></div></div>
            <div class="rounded-md border bg-white p-4" style="border-color:var(--border);"><h2 class="text-sm font-black" style="color:var(--text);">Öne çıkanlar</h2><div class="mt-3 space-y-3">@foreach($premiumCompanies->take(4) as $company)<a href="{{ route('companies.show',$company->slug) }}" class="block border-b pb-3 text-sm font-bold last:border-0 last:pb-0" style="border-color:var(--border);color:var(--text);">{{ $company->name }}<span class="mt-1 block text-xs font-normal" style="color:var(--text_muted);">{{ $company->city->name ?? '' }}</span></a>@endforeach</div></div>
            <a href="{{ route('listing.create') }}" class="block rounded-md p-5 text-center text-sm font-black text-white" style="background:var(--secondary);">Firmanızı rehbere ekleyin</a>
        </aside>
    </div>
</div>
@endsection
