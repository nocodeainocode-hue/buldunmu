@extends('layouts.app')

@section('title', $company->meta_title ?: $company->name . ' - ' . ($company->city->name ?? 'Firma') . ' Firma Rehberi')
@section('meta_description', $company->meta_description ?: ($company->short_description ?: $company->name . ' iletişim bilgileri, adresi, hizmetleri ve kullanıcı yorumları.'))
@section('canonical', route('companies.show', $company->slug))

@php
    $cityName = $company->city->name ?? 'Türkiye';
    $districtName = $company->district->name ?? null;
    $categoryName = $company->category->name ?? 'Firma';
    $ratingAvg = $company->reviews_avg_rating ? number_format((float) $company->reviews_avg_rating, 1) : null;
    $approvedReviews = $company->approvedReviews ?? collect();
    $serviceItems = [
        $categoryName . ' hizmeti',
        'Telefon ile iletişim',
        'WhatsApp destek',
        'Yerel hizmet',
        'Adres ve yön bilgisi',
        'Güncel firma bilgileri',
    ];
@endphp

@push('head')
<script type="application/ld+json">
{
    "@@context": "https://schema.org",
    "@@type": "LocalBusiness",
    "name": @json($company->name),
    "description": @json($company->short_description ?? strip_tags($company->description ?? $company->name)),
    "telephone": @json($company->phone),
    "email": @json($company->email),
    "url": @json($company->website),
    "address": {
        "@@type": "PostalAddress",
        "streetAddress": @json($company->address),
        "addressLocality": @json($cityName),
        "addressCountry": "TR"
    }
    @if($ratingAvg)
    ,"aggregateRating": {
        "@@type": "AggregateRating",
        "ratingValue": "{{ $ratingAvg }}",
        "reviewCount": "{{ $approvedReviews->count() }}"
    }
    @endif
}
</script>
@endpush

@section('content')
<div style="background:var(--bg);">
    <section class="border-b" style="background:linear-gradient(135deg,var(--primary_light),var(--bg_card));border-color:var(--border);">
        <div class="mx-auto px-4 py-8 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <nav class="mb-6 flex flex-wrap gap-2 text-sm" style="color:var(--text_muted);">
                <a href="{{ route('home') }}" class="hover:underline">Ana Sayfa</a>
                <span>/</span>
                @if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}" class="hover:underline">{{ $categoryName }}</a><span>/</span>@endif
                <span style="color:var(--text);">{{ $company->name }}</span>
            </nav>

            <div class="grid gap-8 lg:grid-cols-[1fr_360px]">
                <div class="rounded-3xl border bg-white p-6 shadow-xl" style="border-color:var(--border);">
                    <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                        <div class="shrink-0">
                            @if($company->logo)
                                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-24 w-24 rounded-2xl object-contain bg-white p-2">
                            @else
                                <div class="flex h-24 w-24 items-center justify-center rounded-2xl text-4xl font-black text-white" style="background:linear-gradient(135deg,var(--primary),var(--secondary));">{{ mb_substr($company->name, 0, 1) }}</div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <div class="mb-3 flex flex-wrap items-center gap-2">
                                @if($company->is_premium)<span class="rounded-full px-3 py-1 text-xs font-black text-white" style="background:var(--accent);">Premium</span>@endif
                                <span class="rounded-full px-3 py-1 text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">{{ $categoryName }}</span>
                                @if($ratingAvg)<span class="rounded-full px-3 py-1 text-xs font-bold" style="background:#fff7ed;color:#c2410c;">{{ $ratingAvg }} / 5</span>@endif
                            </div>
                            <h1 class="text-3xl font-black tracking-tight sm:text-5xl" style="color:var(--text);">{{ $company->name }}</h1>
                            <p class="mt-4 max-w-3xl text-base leading-7" style="color:var(--text_muted);">
                                {{ $company->short_description ?: $cityName . ' bölgesinde hizmet veren ' . $company->name . ', ' . $categoryName . ' kategorisinde firma bilgileri ve iletişim kanallarıyla rehberimizde yer alır.' }}
                            </p>
                            <div class="mt-5 flex flex-wrap gap-2 text-sm" style="color:var(--text_muted);">
                                <span>{{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
                                @if($company->address)<span>{{ $company->address }}</span>@endif
                            </div>
                        </div>
                    </div>
                </div>

                <aside class="rounded-3xl border bg-white p-5 shadow-xl" style="border-color:var(--border);">
                    <h2 class="mb-4 text-lg font-black" style="color:var(--text);">İletişim Bilgileri</h2>
                    <div class="space-y-3">
                        @if($company->phone)<a href="tel:{{ $company->phone }}" class="block rounded-xl px-4 py-3 text-center text-sm font-black text-white" style="background:#16a34a;">Ara: {{ $company->phone }}</a>@endif
                        @if($company->whatsapp)<a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener" class="block rounded-xl px-4 py-3 text-center text-sm font-black text-white" style="background:#22c55e;">WhatsApp</a>@endif
                        @if($company->website)<a href="{{ $company->website }}" target="_blank" rel="noopener" class="block rounded-xl px-4 py-3 text-center text-sm font-black" style="background:var(--primary_light);color:var(--primary);">Web Sitesi</a>@endif
                        @if($company->email)<a href="mailto:{{ $company->email }}" class="block rounded-xl px-4 py-3 text-center text-sm font-black" style="background:var(--bg);color:var(--text);">E-posta Gönder</a>@endif
                    </div>
                </aside>
            </div>
        </div>
    </section>

    <div class="mx-auto grid gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_340px] lg:px-8" style="max-width:var(--page_width,1280px);">
        <main class="space-y-8">
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                <h2 class="mb-4 text-2xl font-black" style="color:var(--text);">{{ $cityName }} {{ $categoryName }}: {{ $company->name }}</h2>
                <div class="prose max-w-none leading-7" style="color:var(--text_muted);">
                    @if($company->description)
                        {!! $company->description !!}
                    @else
                        <p>{{ $company->name }}, {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }} bölgesinde hizmet veren bir {{ $categoryName }} firmasıdır. Firma ile ilgili telefon, WhatsApp, web sitesi, adres ve temel hizmet bilgilerine bu sayfadan ulaşabilirsiniz.</p>
                        <p>{{ $cityName }} çevresinde {{ $categoryName }} arayan kullanıcılar için bu sayfa; iletişim kanallarını, firma açıklamasını, konum bilgisini ve kullanıcı yorumlarını tek yerde toplar.</p>
                    @endif
                </div>
            </section>

            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">Hizmetler</h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($serviceItems as $item)
                        <div class="rounded-2xl border p-4" style="border-color:var(--border);background:var(--bg);">
                            <div class="font-bold" style="color:var(--text);">{{ $item }}</div>
                            <div class="mt-1 text-sm" style="color:var(--text_muted);">{{ $cityName }} için güncel bilgi</div>
                        </div>
                    @endforeach
                </div>
            </section>

            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">Neden Bu Firma?</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl p-5 text-center" style="background:var(--bg);"><div class="font-black" style="color:var(--text);">Kolay İletişim</div><p class="mt-2 text-sm" style="color:var(--text_muted);">Telefon ve WhatsApp bilgileriyle hızlı ulaşım.</p></div>
                    <div class="rounded-2xl p-5 text-center" style="background:var(--bg);"><div class="font-black" style="color:var(--text);">Yerel Firma</div><p class="mt-2 text-sm" style="color:var(--text_muted);">{{ $cityName }} bölgesinde hizmet bilgisi.</p></div>
                    <div class="rounded-2xl p-5 text-center" style="background:var(--bg);"><div class="font-black" style="color:var(--text);">Güncel Bilgiler</div><p class="mt-2 text-sm" style="color:var(--text_muted);">Adres, web sitesi ve açıklama alanları admin panelden düzenlenebilir.</p></div>
                </div>
            </section>

            @if($company->images && $company->images->isNotEmpty())
                <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                    <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">Galeri</h2>
                    <div class="grid grid-cols-2 gap-3 sm:grid-cols-3">
                        @foreach($company->images as $image)
                            <a href="{{ asset('storage/' . $image->image_path) }}" target="_blank" class="aspect-square overflow-hidden rounded-2xl"><img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $company->name }}" class="h-full w-full object-cover transition hover:scale-105"></a>
                        @endforeach
                    </div>
                </section>
            @endif

            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">Sık Sorulan Sorular</h2>
                <div class="space-y-3">
                    <details class="rounded-2xl border p-4" style="border-color:var(--border);"><summary class="cursor-pointer font-bold">Nasıl iletişime geçebilirim?</summary><p class="mt-3 text-sm" style="color:var(--text_muted);">Sayfadaki telefon, WhatsApp, web sitesi veya e-posta bağlantılarını kullanabilirsiniz.</p></details>
                    <details class="rounded-2xl border p-4" style="border-color:var(--border);"><summary class="cursor-pointer font-bold">Firma hangi bölgede hizmet veriyor?</summary><p class="mt-3 text-sm" style="color:var(--text_muted);">{{ $company->name }}, {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }} bölgesiyle ilişkilidir.</p></details>
                    <details class="rounded-2xl border p-4" style="border-color:var(--border);"><summary class="cursor-pointer font-bold">Bilgiler değiştirilebilir mi?</summary><p class="mt-3 text-sm" style="color:var(--text_muted);">Firma bilgileri, açıklama, görseller, SEO alanları ve durum bilgisi admin panelden düzenlenebilir.</p></details>
                </div>
            </section>

            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">Değerlendirmeler</h2>
                @if(session('review_success'))<div class="mb-4 rounded-xl p-4 text-sm font-bold" style="background:#dcfce7;color:#166534;">{{ session('review_success') }}</div>@endif
                @if($approvedReviews->isNotEmpty())
                    <div class="mb-6 space-y-4">
                        @foreach($approvedReviews as $review)
                            <div class="rounded-2xl border p-4" style="border-color:var(--border);">
                                <div class="flex items-center justify-between gap-3"><strong>{{ $review->name }}</strong><span style="color:var(--accent);">{{ str_repeat('★', $review->rating) }}</span></div>
                                <p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="mb-6 text-sm" style="color:var(--text_muted);">Bu firma için henüz onaylanmış yorum bulunmuyor. İlk değerlendirmeyi siz yapabilirsiniz.</p>
                @endif

                <form action="{{ route('companies.reviews.store', $company->slug) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <input name="name" value="{{ old('name') }}" placeholder="Ad Soyad" required class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);">
                    <input name="email" value="{{ old('email') }}" placeholder="E-posta (isteğe bağlı)" class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);">
                    <select name="rating" required class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);">
                        @for($i=5;$i>=1;$i--)<option value="{{ $i }}">{{ $i }} yıldız</option>@endfor
                    </select>
                    <div></div>
                    <textarea name="comment" rows="4" placeholder="Yorumunuz" required class="rounded-xl border px-4 py-3 text-sm sm:col-span-2" style="border-color:var(--border);">{{ old('comment') }}</textarea>
                    <button class="w-fit rounded-xl px-6 py-3 text-sm font-black text-white" style="background:var(--primary);">Yorum Gönder</button>
                </form>
            </section>
        </main>

        <aside class="space-y-6">
            <div class="sticky top-24 rounded-3xl border bg-white p-5" style="border-color:var(--border);">
                <h3 class="mb-4 text-lg font-black" style="color:var(--text);">Firma Bilgileri</h3>
                <dl class="space-y-4 text-sm">
                    <div><dt style="color:var(--text_muted);">Kategori</dt><dd class="font-bold" style="color:var(--text);">{{ $categoryName }}</dd></div>
                    <div><dt style="color:var(--text_muted);">Şehir</dt><dd class="font-bold" style="color:var(--text);">{{ $cityName }}</dd></div>
                    @if($districtName)<div><dt style="color:var(--text_muted);">İlçe</dt><dd class="font-bold" style="color:var(--text);">{{ $districtName }}</dd></div>@endif
                    @if($company->address)<div><dt style="color:var(--text_muted);">Adres</dt><dd class="font-bold" style="color:var(--text);">{{ $company->address }}</dd></div>@endif
                </dl>
            </div>

            @if($similarCompanies->isNotEmpty())
                <div class="rounded-3xl border bg-white p-5" style="border-color:var(--border);">
                    <h3 class="mb-4 text-lg font-black" style="color:var(--text);">Benzer Firmalar</h3>
                    <div class="space-y-3">
                        @foreach($similarCompanies as $similar)
                            <a href="{{ route('companies.show', $similar->slug) }}" class="flex items-center gap-3 rounded-2xl border p-3 transition hover:-translate-y-0.5" style="border-color:var(--border);">
                                <div class="flex h-11 w-11 shrink-0 items-center justify-center rounded-xl text-sm font-black text-white" style="background:var(--primary);">{{ mb_substr($similar->name, 0, 1) }}</div>
                                <div class="min-w-0"><div class="truncate text-sm font-bold" style="color:var(--text);">{{ $similar->name }}</div><div class="text-xs" style="color:var(--text_muted);">{{ $similar->city->name ?? '' }}</div></div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>
</div>
@endsection
