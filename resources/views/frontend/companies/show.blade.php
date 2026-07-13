@extends('layouts.app')

@php
    $cityName = $company->city->name ?? 'Türkiye';
    $districtName = $company->district->name ?? null;
    $categoryName = $company->category->name ?? 'Firma';
    $ratingAvg = $company->reviews_avg_rating ? number_format((float) $company->reviews_avg_rating, 1) : null;
    $approvedReviews = $company->approvedReviews ?? collect();
    $reviewCount = $approvedReviews->count();
    $phoneClean = $company->phone ? preg_replace('/[^0-9]/', '', $company->phone) : null;
    $whatsappClean = $company->whatsapp ? preg_replace('/[^0-9]/', '', $company->whatsapp) : null;
    $isSeoStory = ($detailVariant ?? 'compact-local') === 'seo-story';
    $hasRichContent = !empty($company->description) && strlen(strip_tags($company->description)) > 200;
    $googleMapsEmbedSrc = $company->googleMapsEmbedSrc();
@endphp

@section('title', $company->meta_title ?: $company->name . ' - ' . $cityName . ' - Firma Rehberi')
@section('meta_description', $company->meta_description ?: ($company->short_description ?: $company->name . ' | Telefon, adres, web sitesi ve kullanıcı yorumlarıyla ' . $cityName . ' ' . $categoryName . ' firması.'))
@section('canonical', route('companies.show', $company->slug))

@push('head')
{{-- Open Graph --}}
<meta property="og:title" content="{{ $company->name }} - {{ $cityName }} {{ $categoryName }}">
<meta property="og:description" content="{{ $company->short_description ?: $company->name . ' iletişim bilgileri, adresi ve kullanıcı yorumları.' }}">
<meta property="og:url" content="{{ route('companies.show', $company->slug) }}">
<meta property="og:type" content="website">
@if($company->logo)
<meta property="og:image" content="{{ asset('storage/' . $company->logo) }}">
@endif

@include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::company($company)])
@endpush

@section('content')
<div style="background:var(--bg);">

    {{-- ═══ COVER IMAGE HERO ═══ --}}
    @if($company->cover_image)
    <section class="relative h-48 sm:h-64 lg:h-80 overflow-hidden">
        <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }} kapak görseli" class="h-full w-full object-cover" loading="eager">
        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-black/30 to-transparent"></div>
        <div class="absolute bottom-0 left-0 right-0 p-6">
            <div class="mx-auto" style="max-width:var(--page_width,1280px);">
                <h1 class="text-3xl font-black text-white sm:text-4xl">{{ $company->name }}</h1>
                @if($company->short_description)
                <p class="mt-2 text-sm text-white/80 max-w-2xl">{{ $company->short_description }}</p>
                @endif
            </div>
        </div>
    </section>
    @endif

    {{-- ═══ MAIN HEADER SECTION ═══ --}}
    <section class="border-b" style="background:var(--primary_light);border-color:var(--border);">
        <div class="mx-auto px-4 py-6 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">

            {{-- Breadcrumb --}}
            <nav class="mb-4 flex flex-wrap items-center gap-1.5 text-sm" style="color:var(--text_muted);">
                <a href="{{ route('home') }}" class="hover:underline">Ana Sayfa</a>
                <span class="mx-1">/</span>
                @if($company->category)
                    <a href="{{ route('categories.show', $company->category->slug) }}" class="hover:underline">{{ $categoryName }}</a>
                    <span class="mx-1">/</span>
                @endif
                @if($company->city)
                    <a href="{{ route('cities.show', $company->city->slug) }}" class="hover:underline">{{ $cityName }}</a>
                    <span class="mx-1">/</span>
                @endif
                <span style="color:var(--text);">{{ $company->name }}</span>
            </nav>

            {{-- Header Card --}}
            <div class="rounded-3xl border bg-white p-5 sm:p-6 shadow-lg" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start">
                    {{-- Logo / Initial --}}
                    <div class="shrink-0">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }} logosu" class="h-24 w-24 rounded-2xl object-contain bg-white border p-2" style="border-color:var(--border);" loading="eager">
                        @else
                            <div class="flex h-24 w-24 items-center justify-center rounded-2xl text-4xl font-black text-white shadow-md" style="background:var(--primary);">{{ mb_substr($company->name, 0, 1) }}</div>
                        @endif
                    </div>

                    <div class="min-w-0 flex-1">
                        {{-- Badges --}}
                        <div class="mb-2 flex flex-wrap items-center gap-2">
                            @if($company->is_premium)
                                <span class="rounded-full px-3 py-1 text-xs font-black text-white shadow" style="background:var(--accent);">Premium</span>
                            @endif
                            <span class="rounded-full px-3 py-1 text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">{{ $categoryName }}</span>
                            @if($ratingAvg)
                                <span class="rounded-full px-3 py-1 text-xs font-bold" style="background:#fff7ed;color:#c2410c;">⭐ {{ $ratingAvg }} / 5 ({{ $reviewCount }})</span>
                            @endif
                        </div>

                        @if(!$company->cover_image)
                            <h1 class="text-3xl font-black tracking-tight sm:text-4xl" style="color:var(--text);">{{ $company->name }}</h1>
                        @endif

                        <p class="mt-3 max-w-3xl text-base leading-7" style="color:var(--text_muted);">
                            {{ $company->short_description ?: $company->name . ', ' . $cityName . ($districtName ? ' / ' . $districtName : '') . ' bölgesinde hizmet veren bir ' . $categoryName . ' firmasıdır. Telefon, WhatsApp, adres ve web sitesi bilgileriyle rehberimizde yer almaktadır.' }}
                        </p>

                        <div class="mt-4 flex flex-wrap gap-x-5 gap-y-1 text-sm" style="color:var(--text_muted);">
                            <span class="flex items-center gap-1">📍 {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }}</span>
                            @if($company->address)
                                <span class="flex items-center gap-1">🏠 {{ \Illuminate\Support\Str::limit($company->address, 60) }}</span>
                            @endif
                            @if($company->view_count > 0)
                                <span class="flex items-center gap-1">👁 {{ number_format($company->view_count, 0, ',', '.') }} görüntülenme</span>
                            @endif
                        </div>

                        {{-- Quick action buttons (desktop) --}}
                        <div class="mt-4 hidden flex-wrap gap-2 sm:flex">
                            @if($company->phone)
                                <a href="tel:{{ $phoneClean }}" class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-bold text-white transition hover:opacity-90 active:scale-95" style="background:#16a34a;">📞 Ara</a>
                            @endif
                            @if($company->whatsapp)
                                <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-bold text-white transition hover:opacity-90 active:scale-95" style="background:#22c55e;">💬 WhatsApp</a>
                            @endif
                            @if($company->website)
                                <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 rounded-xl px-4 py-2 text-sm font-bold transition hover:opacity-90 active:scale-95" style="background:var(--primary_light);color:var(--primary);">🌐 Web Sitesi</a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ═══ CONTENT + SIDEBAR GRID ═══ --}}
    <div class="mx-auto grid gap-8 px-4 py-10 sm:px-6 lg:grid-cols-[1fr_340px] lg:px-8" style="max-width:var(--page_width,1280px);">

        {{-- ═══ MAIN CONTENT ═══ --}}
        <main class="space-y-8">

            {{-- SEO Description Block --}}
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-4 text-2xl font-black" style="color:var(--text);">{{ $company->name }} — {{ $cityName }} {{ $categoryName }}</h2>
                <div class="prose max-w-none leading-7" style="color:var(--text_muted);">
                    @if($company->description)
                        {!! $company->description !!}
                    @else
                        <p><strong>{{ $company->name }}</strong>, {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }} bölgesinde hizmet veren bir <strong>{{ $categoryName }}</strong> firmasıdır.</p>

                        <p>{{ $cityName }} ve çevre ilçelerde {{ $categoryName }} hizmeti arayanlar için {{ $company->name }}; telefon numarası, WhatsApp hattı, web sitesi bağlantısı, e-posta adresi ve fiziksel konum bilgisini bir arada sunmaktadır.</p>

                        @if($districtName)
                        <p><strong>{{ $districtName }}</strong> bölgesindeki {{ $categoryName }} ihtiyacınız için {{ $company->name }} ile hemen iletişime geçebilir; telefonla arayabilir, WhatsApp üzerinden mesaj gönderebilir veya web sitesini ziyaret ederek detaylı bilgi alabilirsiniz.</p>
                        @endif

                        @if($isSeoStory)
                        <h3>{{ $cityName }}'de {{ $categoryName }} Hizmeti</h3>
                        <p>{{ $cityName }} ilinde {{ $categoryName }} sektöründe faaliyet gösteren {{ $company->name }}, müşterilerine kaliteli hizmet sunmayı amaçlamaktadır. Firmanın iletişim bilgileri ve adresi düzenli olarak güncellenmekte olup; telefon, WhatsApp, web sitesi ve e-posta kanallarından firmaya ulaşabilirsiniz.</p>
                        @endif

                        <p>{{ $company->name }} hakkında daha fazla bilgi almak, randevu oluşturmak veya fiyat teklifi istemek için yukarıdaki iletişim kanallarını kullanabilirsiniz. Firma bilgileri site yönetimi tarafından düzenli olarak kontrol edilmekte ve güncellenmektedir.</p>
                    @endif
                </div>

                {{-- Share buttons --}}
                <div class="mt-5 border-t pt-4" style="border-color:var(--border);">
                    @include('partials.share-buttons', ['url' => route('companies.show', $company->slug), 'title' => $company->name])
                </div>
            </section>

            {{-- Services Grid (admin-managed) --}}
            @if(!empty($company->services))
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">🔧 Hizmetler ve İletişim Kanalları</h2>
                <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-3">
                    @foreach($company->services as $service)
                        <div class="rounded-2xl border p-4 transition hover:shadow-sm" style="border-color:var(--border);background:var(--bg);">
                            <div class="font-bold" style="color:var(--text);">{{ $service['title'] }}</div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Why Us (admin-managed) --}}
            @if(!empty($company->why_us_items))
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">⭐ Neden {{ $company->name }}?</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach($company->why_us_items as $item)
                        <div class="rounded-2xl p-5 text-center transition hover:-translate-y-0.5" style="background:var(--bg);">
                            <div class="font-bold" style="color:var(--text);">{{ $item['title'] }}</div>
                            <p class="mt-2 text-sm" style="color:var(--text_muted);">{{ $item['description'] }}</p>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- SEO Story: Extended content (only for seo-story variant) --}}
            @if($isSeoStory && !$hasRichContent)
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-4 text-xl font-black" style="color:var(--text);">{{ $cityName }} {{ $categoryName }} Sektöründe {{ $company->name }}</h2>
                <div class="prose max-w-none leading-7 text-sm" style="color:var(--text_muted);">
                    <p>{{ $cityName }} ilinde {{ $categoryName }} sektörü, yerel ekonomi için önemli bir yer tutmaktadır. {{ $company->name }}, bu sektörde {{ $cityName }} halkına hizmet sunan firmalar arasında yer almaktadır.</p>
                    <p>{{ $cityName }}{{ $districtName ? ' ' . $districtName : '' }} bölgesinde {{ $categoryName }} ihtiyacınız olduğunda {{ $company->name }} ile iletişime geçerek; fiyat teklifi alabilir, randevu oluşturabilir ve hizmet detaylarını öğrenebilirsiniz.</p>
                    @if($districtName)
                    <p>{{ $districtName }} ve çevre semtlerde hizmet veren {{ $company->name }}; {{ $cityName }} genelinde {{ $categoryName }} arayan kullanıcılar için güvenilir bir seçenek sunar. İletişim bilgileri doğrulanmış olup, firma profili site yönetimi tarafından denetlenmektedir.</p>
                    @endif
                </div>
            </section>
            @endif

            {{-- Photo Gallery --}}
            @if($company->images && $company->images->isNotEmpty())
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">🖼️ Fotoğraf Galerisi</h2>
                <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
                    @foreach($company->images as $index => $image)
                        <div class="gallery-item aspect-square overflow-hidden rounded-2xl cursor-pointer relative group border" style="border-color:var(--border);" data-index="{{ $index }}" data-src="{{ asset('storage/' . $image->image_path) }}">
                            <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->alt_text ?: $company->name . ' - Fotoğraf ' . ($index + 1) }}" class="h-full w-full object-cover transition duration-300 group-hover:scale-110" loading="lazy">
                            <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 transition-colors duration-300 flex items-center justify-center">
                                <svg class="w-8 h-8 text-white opacity-0 group-hover:opacity-100 transition-opacity duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"/></svg>
                            </div>
                        </div>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Google Maps Embed --}}
            @if($googleMapsEmbedSrc)
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">🗺️ Konum</h2>
                <div class="relative w-full overflow-hidden rounded-2xl" style="padding-top:56.25%;">
                    <iframe
                        src="{{ $googleMapsEmbedSrc }}"
                        class="absolute inset-0 h-full w-full border-0"
                        allowfullscreen=""
                        loading="lazy"
                        referrerpolicy="no-referrer-when-downgrade"
                        title="{{ $company->name }} - Google Maps Konumu">
                    </iframe>
                </div>
            </section>
            @endif

            {{-- Opening Hours --}}
            @if($company->opening_hours)
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">🕐 Çalışma Saatleri</h2>
                <div class="prose max-w-none leading-7" style="color:var(--text_muted);">
                    {!! nl2br(e($company->opening_hours)) !!}
                </div>
            </section>
            @endif

            {{-- FAQ Section --}}
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">❓ Sık Sorulan Sorular</h2>
                <div class="space-y-3">
                    <details class="rounded-2xl border p-4 group" style="border-color:var(--border);">
                        <summary class="cursor-pointer font-bold text-sm" style="color:var(--text);">{{ $company->name }} ile nasıl iletişime geçebilirim?</summary>
                        <p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">
                            @if($company->phone)Telefon: {{ $company->phone }} - @endif
                            @if($company->whatsapp)WhatsApp üzerinden - @endif
                            @if($company->website)Web sitesi: {{ $company->website }} - @endif
                            @if($company->email)E-posta: {{ $company->email }} - @endif
                            Tüm iletişim kanalları sayfanın üst kısmındaki bilgi kartında listelenmiştir.
                        </p>
                    </details>
                    <details class="rounded-2xl border p-4 group" style="border-color:var(--border);">
                        <summary class="cursor-pointer font-bold text-sm" style="color:var(--text);">Firma hangi bölgede hizmet veriyor?</summary>
                        <p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">{{ $company->name }}, {{ $cityName }}{{ $districtName ? ' / ' . $districtName : '' }} bölgesinde hizmet vermektedir{{ $company->address ? '. Adres: ' . $company->address : '' }}.</p>
                    </details>
                    <details class="rounded-2xl border p-4 group" style="border-color:var(--border);">
                        <summary class="cursor-pointer font-bold text-sm" style="color:var(--text);">Firma bilgileri güncel mi?</summary>
                        <p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">Evet, firma bilgileri site yönetimi tarafından düzenli olarak kontrol edilmekte ve güncellenmektedir. Herhangi bir değişiklik durumunda firma yetkilileri bilgilerini güncelleyebilir.</p>
                    </details>
                    @if($isSeoStory)
                    <details class="rounded-2xl border p-4 group" style="border-color:var(--border);">
                        <summary class="cursor-pointer font-bold text-sm" style="color:var(--text);">{{ $cityName }}'de {{ $categoryName }} firması nasıl seçilir?</summary>
                        <p class="mt-3 text-sm leading-6" style="color:var(--text_muted);">{{ $cityName }} bölgesinde {{ $categoryName }} firması seçerken; müşteri yorumları, puanlamalar, iletişim kolaylığı ve konum gibi kriterleri değerlendirmenizi öneririz. {{ $company->name }} sayfasında bu bilgilerin tamamını bulabilirsiniz.</p>
                    </details>
                    @endif
                </div>
            </section>

            {{-- External Links (admin-managed) --}}
            @if(!empty($company->external_links))
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">🔗 Dış Bağlantılar</h2>
                <div class="grid gap-3 sm:grid-cols-2">
                    @foreach($company->external_links as $link)
                        <a href="{{ $link['url'] }}" target="_blank" rel="noopener noreferrer" class="block rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);background:var(--bg);">
                            <div class="font-bold" style="color:var(--text);">{{ $link['label'] }}</div>
                            @if(!empty($link['description']))
                                <p class="mt-1 text-xs" style="color:var(--text_muted);">{{ $link['description'] }}</p>
                            @endif
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

            {{-- Reviews --}}
            <section class="rounded-3xl border bg-white p-6" id="yorumlar" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">
                    💬 Değerlendirmeler
                    @if($reviewCount > 0)
                        <span class="text-lg font-normal" style="color:var(--text_muted);">({{ $reviewCount }})</span>
                    @endif
                </h2>

                @if(session('review_success'))
                    <div class="mb-4 rounded-xl p-4 text-sm font-bold" style="background:#dcfce7;color:#166534;">✅ {{ session('review_success') }}</div>
                @endif

                @if($approvedReviews->isNotEmpty())
                    <div class="mb-6 space-y-4">
                        @foreach($approvedReviews->take(5) as $review)
                            <div class="rounded-2xl border p-4" style="border-color:var(--border);">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <strong style="color:var(--text);">{{ $review->name }}</strong>
                                        <span class="ml-2 text-xs" style="color:var(--text_muted);">{{ $review->created_at->format('d.m.Y') }}</span>
                                    </div>
                                    <div class="flex items-center gap-0.5 text-sm" style="color:#f59e0b;">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="h-4 w-4 fill-current" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @else
                                                <svg class="h-4 w-4 fill-current opacity-25" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                                <p class="mt-2 text-sm leading-6" style="color:var(--text_muted);">{{ $review->comment }}</p>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="mb-6 rounded-2xl border p-6 text-center" style="border-color:var(--border);background:var(--bg);">
                        <div class="text-3xl mb-2">📝</div>
                        <p class="text-sm font-bold" style="color:var(--text);">Henüz değerlendirme yok</p>
                        <p class="mt-1 text-xs" style="color:var(--text_muted);">Bu firma için ilk değerlendirmeyi siz yapabilirsiniz.</p>
                    </div>
                @endif

                {{-- Review Form --}}
                <form action="{{ route('companies.reviews.store', $company->slug) }}" method="POST" class="grid gap-4 sm:grid-cols-2">
                    @csrf
                    <input name="name" value="{{ old('name') }}" placeholder="Adınız Soyadınız" required class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);background:var(--bg);">
                    <input name="email" value="{{ old('email') }}" placeholder="E-posta (isteğe bağlı)" type="email" class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);background:var(--bg);">
                    <select name="rating" required class="rounded-xl border px-4 py-3 text-sm" style="border-color:var(--border);background:var(--bg);">
                        <option value="">Puanınız</option>
                        @for($i=5;$i>=1;$i--) <option value="{{ $i }}" {{ old('rating') == $i ? 'selected' : '' }}>⭐ {{ $i }} yıldız</option> @endfor
                    </select>
                    <div></div>
                    <textarea name="comment" rows="3" placeholder="Yorumunuzu yazın..." required class="rounded-xl border px-4 py-3 text-sm sm:col-span-2" style="border-color:var(--border);background:var(--bg);">{{ old('comment') }}</textarea>
                    <button type="submit" class="w-fit rounded-xl px-6 py-3 text-sm font-black text-white transition hover:opacity-90 active:scale-95" style="background:var(--primary);">📤 Yorum Gönder</button>
                </form>
            </section>

            {{-- Related Posts --}}
            @if($relatedPosts->isNotEmpty())
            <section class="rounded-3xl border bg-white p-6" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h2 class="mb-5 text-2xl font-black" style="color:var(--text);">📰 İlgili İçerikler</h2>
                <div class="grid gap-4 sm:grid-cols-3">
                    @foreach($relatedPosts as $post)
                        <a href="{{ route('blog.show', $post->slug) }}" class="block rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);">
                            <div class="text-xs mb-1" style="color:var(--text_muted);">{{ $post->published_at->format('d.m.Y') }}</div>
                            <div class="font-bold text-sm" style="color:var(--text);">{{ $post->title }}</div>
                            <p class="mt-1 text-xs line-clamp-2" style="color:var(--text_muted);">{{ $post->excerpt }}</p>
                        </a>
                    @endforeach
                </div>
            </section>
            @endif

        </main>

        {{-- ═══ SIDEBAR ═══ --}}
        <aside class="space-y-6">
            {{-- Contact Card --}}
            <div class="sticky top-24 rounded-3xl border bg-white p-5 shadow-lg" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                <h3 class="mb-4 text-lg font-black" style="color:var(--text);">📋 Firma Bilgileri</h3>
                <dl class="space-y-4 text-sm">
                    <div>
                        <dt style="color:var(--text_muted);">Kategori</dt>
                        <dd class="font-bold mt-0.5" style="color:var(--text);">@if($company->category)<a href="{{ route('categories.show', $company->category->slug) }}" class="hover:underline">{{ $categoryName }}</a>@else{{ $categoryName }}@endif</dd>
                    </div>
                    <div>
                        <dt style="color:var(--text_muted);">Şehir</dt>
                        <dd class="font-bold mt-0.5" style="color:var(--text);">@if($company->city)<a href="{{ route('cities.show', $company->city->slug) }}" class="hover:underline">{{ $cityName }}</a>@else{{ $cityName }}@endif</dd>
                    </div>
                    @if($districtName)
                    <div>
                        <dt style="color:var(--text_muted);">İlçe</dt>
                        <dd class="font-bold mt-0.5" style="color:var(--text);">{{ $districtName }}</dd>
                    </div>
                    @endif
                    @if($company->address)
                    <div>
                        <dt style="color:var(--text_muted);">Adres</dt>
                        <dd class="font-bold mt-0.5" style="color:var(--text);">{{ $company->address }}</dd>
                    </div>
                    @endif
                    @if($company->email)
                    <div>
                        <dt style="color:var(--text_muted);">E-posta</dt>
                        <dd class="font-bold mt-0.5" style="color:var(--text);"><a href="mailto:{{ $company->email }}" class="hover:underline" style="color:var(--primary);">{{ $company->email }}</a></dd>
                    </div>
                    @endif
                </dl>

                {{-- Quick Contact Buttons (sidebar) --}}
                <div class="mt-5 space-y-2 border-t pt-4" style="border-color:var(--border);">
                    @if($company->phone)
                        <a href="tel:{{ $phoneClean }}" class="block rounded-xl px-4 py-2.5 text-center text-sm font-bold text-white transition hover:opacity-90 active:scale-95" style="background:#16a34a;">📞 {{ $company->phone }}</a>
                    @endif
                    @if($company->whatsapp)
                        <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer" class="block rounded-xl px-4 py-2.5 text-center text-sm font-bold text-white transition hover:opacity-90 active:scale-95" style="background:#22c55e;">💬 WhatsApp ile Mesaj</a>
                    @endif
                    @if($company->website)
                        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="block rounded-xl px-4 py-2.5 text-center text-sm font-bold transition hover:opacity-90 active:scale-95" style="background:var(--primary_light);color:var(--primary);">🌐 Web Sitesini Ziyaret Et</a>
                    @endif
                    @if($company->email)
                        <a href="mailto:{{ $company->email }}" class="block rounded-xl px-4 py-2.5 text-center text-sm font-bold transition hover:opacity-90 active:scale-95" style="background:var(--bg);color:var(--text);border:1px solid var(--border);">✉️ E-posta Gönder</a>
                    @endif
                </div>
            </div>

            {{-- Similar Companies --}}
            @if($similarCompanies->isNotEmpty())
                <div class="rounded-3xl border bg-white p-5" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <h3 class="mb-4 text-lg font-black" style="color:var(--text);">🔄 Benzer Firmalar</h3>
                    <div class="space-y-3">
                        @foreach($similarCompanies as $similar)
                            <a href="{{ route('companies.show', $similar->slug) }}" class="flex items-center gap-3 rounded-2xl border p-3 transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-black text-white" style="background:var(--primary);">{{ mb_substr($similar->name, 0, 1) }}</div>
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-bold" style="color:var(--text);">{{ $similar->name }}</div>
                                    <div class="text-xs" style="color:var(--text_muted);">{{ $similar->city->name ?? '' }} · {{ $similar->category->name ?? '' }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Nearby (District) --}}
            @if($nearbyCompanies->isNotEmpty())
                <div class="rounded-3xl border bg-white p-5" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <h3 class="mb-4 text-lg font-black" style="color:var(--text);">📍 Yakındaki Firmalar</h3>
                    <div class="space-y-3">
                        @foreach($nearbyCompanies as $nearby)
                            <a href="{{ route('companies.show', $nearby->slug) }}" class="flex items-center gap-3 rounded-2xl border p-3 transition hover:-translate-y-0.5 hover:shadow-sm" style="border-color:var(--border);">
                                <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-black text-white" style="background:var(--accent);">{{ mb_substr($nearby->name, 0, 1) }}</div>
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-bold" style="color:var(--text);">{{ $nearby->name }}</div>
                                    <div class="text-xs" style="color:var(--text_muted);">{{ $nearby->category->name ?? '' }}</div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Same Category --}}
            @if($sameCategoryCompanies->isNotEmpty())
                <div class="rounded-3xl border bg-white p-5" style="border-color:var(--border);box-shadow:var(--card_shadow);">
                    <h3 class="mb-4 text-lg font-black" style="color:var(--text);">🏷️ Aynı Kategoridekiler</h3>
                    <div class="space-y-2">
                        @foreach($sameCategoryCompanies as $sameCat)
                            <a href="{{ route('companies.show', $sameCat->slug) }}" class="block truncate rounded-lg px-3 py-2 text-sm font-medium transition hover:opacity-70" style="color:var(--text);">
                                {{ $sameCat->name }}
                                <span class="text-xs ml-1" style="color:var(--text_muted);">({{ $sameCat->city->name ?? '' }})</span>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif
        </aside>
    </div>

    {{-- ═══ BOTTOM CTA ═══ --}}
    <section>
        @include('partials.cta')
    </section>
</div>

{{-- ═══ MOBILE FLOATING CTA ═══ --}}
<div class="fixed bottom-0 left-0 right-0 z-50 border-t bg-white/95 backdrop-blur-sm p-3 flex gap-2 lg:hidden shadow-2xl" style="border-color:var(--border);">
    @if($company->phone)
        <a href="tel:{{ $phoneClean }}" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl py-2.5 text-sm font-black text-white transition active:scale-95" style="background:#16a34a;">
            📞 Ara
        </a>
    @endif
    @if($company->whatsapp)
        <a href="https://wa.me/{{ $whatsappClean }}" target="_blank" rel="noopener noreferrer" class="flex-1 flex items-center justify-center gap-1.5 rounded-xl py-2.5 text-sm font-black text-white transition active:scale-95" style="background:#22c55e;">
            💬 WhatsApp
        </a>
    @endif
    @if($company->website)
        <a href="{{ $company->website }}" target="_blank" rel="noopener noreferrer" class="flex items-center justify-center gap-1.5 rounded-xl px-4 py-2.5 text-sm font-black transition active:scale-95" style="background:var(--primary_light);color:var(--primary);">
            🌐 Web
        </a>
    @endif
</div>

{{-- Spacer for mobile CTA --}}
<div class="h-16 lg:hidden"></div>

{{-- ═══ GALLERY LIGHTBOX ═══ --}}
@if(str_starts_with((string) $company->external_id, 'osm:'))
    <div class="container mx-auto px-4 pb-4 text-xs" style="color:var(--text_muted);">
        Konum verileri
        <a href="https://www.openstreetmap.org/copyright" target="_blank" rel="nofollow noopener" class="underline">&copy; OpenStreetMap katkıda bulunanlar</a>
        tarafından sağlanmıştır.
    </div>
@endif

@if($company->images && $company->images->isNotEmpty())
<div id="gallery-lightbox" class="fixed inset-0 z-[9999] hidden bg-black/95 flex items-center justify-center" onclick="closeLightbox(event)">
    <button class="absolute top-4 right-4 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition text-2xl leading-none" onclick="closeLightbox()" aria-label="Kapat">&times;</button>
    <button class="absolute left-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition" onclick="navigateLightbox(-1, event)" aria-label="Önceki">&lsaquo;</button>
    <button class="absolute right-4 top-1/2 -translate-y-1/2 z-10 w-10 h-10 flex items-center justify-center rounded-full bg-white/10 text-white hover:bg-white/20 transition" onclick="navigateLightbox(1, event)" aria-label="Sonraki">&rsaquo;</button>
    <img id="lightbox-img" src="" alt="" class="max-h-[90vh] max-w-[90vw] object-contain rounded-lg shadow-2xl" onclick="event.stopPropagation()">
    <div id="lightbox-counter" class="absolute bottom-4 left-1/2 -translate-x-1/2 text-white/70 text-sm font-medium"></div>
</div>
@endif

@push('scripts')
<script>
    // Lightbox functionality
    let lightboxImages = [];
    let lightboxIndex = 0;

    document.querySelectorAll('.gallery-item').forEach((item) => {
        item.addEventListener('click', (e) => {
            e.preventDefault();
            lightboxImages = Array.from(document.querySelectorAll('.gallery-item')).map(el => el.dataset.src);
            lightboxIndex = parseInt(item.dataset.index);
            openLightbox(lightboxIndex);
        });
    });

    function openLightbox(index) {
        lightboxIndex = index;
        const img = document.getElementById('lightbox-img');
        const counter = document.getElementById('lightbox-counter');
        if (!img || !counter) return;
        img.src = lightboxImages[lightboxIndex];
        counter.textContent = (lightboxIndex + 1) + ' / ' + lightboxImages.length;
        document.getElementById('gallery-lightbox')?.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeLightbox(event) {
        if (event && event.target !== document.getElementById('gallery-lightbox')) return;
        document.getElementById('gallery-lightbox')?.classList.add('hidden');
        document.body.style.overflow = '';
        lightboxImages = [];
        lightboxIndex = 0;
    }

    function navigateLightbox(direction, event) {
        if (event) event.stopPropagation();
        lightboxIndex = (lightboxIndex + direction + lightboxImages.length) % lightboxImages.length;
        openLightbox(lightboxIndex);
    }

    // Keyboard navigation
    document.addEventListener('keydown', function(e) {
        const lb = document.getElementById('gallery-lightbox');
        if (!lb || lb.classList.contains('hidden')) return;
        if (e.key === 'Escape') closeLightbox();
        if (e.key === 'ArrowLeft') navigateLightbox(-1);
        if (e.key === 'ArrowRight') navigateLightbox(1);
    });

    // Touch swipe support
    let touchStartX = 0;
    let touchEndX = 0;
    const lightbox = document.getElementById('gallery-lightbox');
    if (lightbox) {
        lightbox.addEventListener('touchstart', function(e) {
            touchStartX = e.changedTouches[0].screenX;
        }, {passive: true});
        lightbox.addEventListener('touchend', function(e) {
            touchEndX = e.changedTouches[0].screenX;
            const diff = touchStartX - touchEndX;
            if (Math.abs(diff) > 50) {
                navigateLightbox(diff > 0 ? 1 : -1);
            }
        }, {passive: true});
    }
</script>
@endpush

@endsection
