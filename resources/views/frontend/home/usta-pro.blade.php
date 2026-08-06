@extends('layouts.app')

@section('title', $settings->homepage_title ?? $settings->site_name ?? 'Firma Rehberi')
@section('meta_description', $directory->meta_description ?? $settings->meta_description ?? '')

@section('content')
{{-- ═══ USTA PRO: demo11 tarzı hizmet/usta rehberi düzeni ═══ --}}
@php
    $statsCompanies  = \App\Models\Company::active()->count();
    $statsCategories = \App\Models\Category::active()->count();
    $statsCities     = \App\Models\City::whereHas('companies')->count();
    $statsReviews    = \App\Models\CompanyReview::where('status', 'approved')->count();
@endphp

{{-- ─── HERO: başlık + şehir seçici arama + popüler çipler ─── --}}
<section class="border-b" style="border-color:var(--border);background:linear-gradient(180deg,var(--hero_gradient_from),var(--hero_gradient_to));">
    <div class="mx-auto px-4 py-14 sm:px-6 sm:py-20 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="grid items-center gap-10 lg:grid-cols-[1.15fr_0.85fr]">
            <div>
                <span class="inline-flex items-center gap-2 rounded-full px-3 py-1.5 text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">
                    <span class="h-2 w-2 rounded-full" style="background:var(--accent);"></span>
                    Onaylı firmalar, aracısız iletişim
                </span>
                <h1 class="mt-5 text-4xl font-black leading-tight tracking-tight sm:text-5xl" style="color:var(--text);">{{ $settings->homepage_title ?? 'Her işin uzmanı burada' }}</h1>
                <p class="mt-4 max-w-xl text-base leading-7" style="color:var(--text_muted);">{{ $settings->homepage_subtitle ?? 'Binlerce firma arasından size en uygununu bulun, aracısız iletişime geçin, işinizi güvenle yaptırın.' }}</p>

                {{-- Arama: şehir seç + kelime + buton (mobilde alt alta) --}}
                <form action="{{ route('companies.index') }}" method="GET" class="mt-8 rounded-2xl border p-2 shadow-lg" style="background:var(--bg_card);border-color:var(--border);">
                    <div class="flex flex-col gap-2 md:flex-row">
                        <select name="city" aria-label="Şehir seç" class="rounded-xl border px-4 py-3 text-sm font-semibold outline-none md:w-44" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                            <option value="">Tüm Türkiye</option>
                            @foreach($cities as $city)
                                <option value="{{ $city->slug }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        <input type="text" name="q" placeholder="Ne arıyorsunuz? Örn: tesisat, nakliyat, mobilya..." class="min-w-0 flex-1 rounded-xl border px-4 py-3 text-sm outline-none" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                        <button type="submit" class="rounded-xl px-7 py-3 text-sm font-black transition hover:opacity-90" style="background:var(--primary);color:var(--btn_text,#fff);">Firma Bul</button>
                    </div>
                </form>

                @if($categories->isNotEmpty())
                <div class="mt-5 flex flex-wrap items-center gap-2 text-xs">
                    <span class="font-bold" style="color:var(--text_muted);">Popüler:</span>
                    @foreach($categories->take(5) as $cat)
                        <a href="{{ route('categories.show', $cat->slug) }}" class="rounded-full border px-3 py-1.5 font-semibold transition hover:opacity-75" style="border-color:var(--border);background:var(--bg_card);color:var(--text);">{{ $cat->name }}</a>
                    @endforeach
                </div>
                @endif
            </div>

            {{-- Sağ ray: kategori karoları (sadece geniş ekran) --}}
            @if($categories->isNotEmpty())
            <div class="hidden grid-cols-2 gap-3 lg:grid">
                @foreach($categories->take(6) as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" class="rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md" style="background:var(--bg_card);border-color:var(--border);">
                        <span class="flex h-10 w-10 items-center justify-center rounded-xl text-lg" style="background:var(--primary_light);">{{ $cat->icon ?? '◆' }}</span>
                        <div class="mt-3 truncate text-sm font-bold" style="color:var(--text);">{{ $cat->name }}</div>
                        <div class="mt-0.5 text-xs" style="color:var(--text_muted);">{{ $cat->companies_count }} firma</div>
                    </a>
                @endforeach
            </div>
            @endif
        </div>
    </div>
</section>

{{-- ─── POPÜLER KATEGORİLER ─── --}}
@if($categories->isNotEmpty())
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
            <div>
                <div class="text-xs font-black uppercase tracking-[0.18em]" style="color:var(--accent);">Kategoriler</div>
                <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Popüler Kategoriler</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">En çok tercih edilen hizmet kategorileri</p>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tümünü Gör →</a>
        </div>
        <div class="grid grid-cols-2 gap-3 sm:grid-cols-3 lg:grid-cols-4">
            @foreach($categories as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}" class="rounded-2xl border p-4 transition hover:-translate-y-0.5 hover:shadow-md sm:p-5" style="background:var(--bg_card);border-color:var(--border);">
                    <span class="flex h-11 w-11 items-center justify-center rounded-xl text-xl" style="background:var(--primary_light);">{{ $cat->icon ?? '◆' }}</span>
                    <div class="mt-3 truncate font-bold" style="color:var(--text);">{{ $cat->name }}</div>
                    <div class="mt-0.5 text-xs" style="color:var(--text_muted);">{{ $cat->companies_count }} firma</div>
                </a>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── VİTRİNDEKİ FİRMALAR (premium) ─── --}}
@if($premiumCompanies->isNotEmpty())
<section class="border-y py-14" style="border-color:var(--border);background:var(--bg_card);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
            <div>
                <div class="text-xs font-black uppercase tracking-[0.18em]" style="color:var(--accent);">Vitrin</div>
                <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Vitrindeki Firmalar</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">Premium üye firmalarımız</p>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tüm firmalar →</a>
        </div>
        <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @foreach($premiumCompanies as $company)
                @include('partials.cards.' . \App\View\Helpers\ThemeHelper::cardPartial($directory ?? null), ['company' => $company, 'premium' => true])
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── ŞU AN AÇIK FİRMALAR ─── --}}
@if($openCompanies->isNotEmpty())
<section class="py-14" style="background:var(--bg);">
    <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div class="mb-8 flex flex-wrap items-end justify-between gap-3">
            <div>
                <div class="text-xs font-black uppercase tracking-[0.18em]" style="color:var(--accent);">Canlı</div>
                <h2 class="mt-2 text-2xl font-black tracking-tight sm:text-3xl" style="color:var(--text);">Şu An Açık Firmalar</h2>
                <p class="mt-2 text-sm" style="color:var(--text_muted);">Hemen ulaşabileceğiniz, şu an hizmet veren firmalar</p>
            </div>
            <a href="{{ route('companies.index') }}" class="text-sm font-bold" style="color:var(--primary);">Tümünü gör →</a>
        </div>
        <div class="grid gap-4 md:grid-cols-2">
            @foreach($openCompanies as $company)
                <article class="flex gap-4 rounded-2xl border p-4 transition hover:shadow-md sm:p-5" style="background:var(--bg_card);border-color:var(--border);">
                    <a href="{{ route('companies.show', $company->slug) }}" class="flex h-20 w-20 shrink-0 items-center justify-center overflow-hidden rounded-xl text-2xl font-black" style="background:var(--primary);color:var(--btn_text,#fff);">
                        @if($company->logo)
                            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-full w-full bg-white object-contain p-1.5">
                        @else
                            {{ mb_substr($company->name, 0, 1) }}
                        @endif
                    </a>
                    <div class="min-w-0 flex-1">
                        <div class="flex flex-wrap items-center gap-2">
                            <span class="inline-flex items-center gap-1.5 rounded-full px-2.5 py-1 text-[10px] font-black" style="background:var(--primary_light);color:var(--primary);">
                                <span class="h-1.5 w-1.5 animate-pulse rounded-full bg-green-500"></span>ŞU AN AÇIK
                            </span>
                            @if($company->reviews_avg_rating)
                                <span class="text-xs font-bold" style="color:var(--accent);">★ {{ number_format($company->reviews_avg_rating, 1) }} <span class="font-medium" style="color:var(--text_muted);">({{ $company->approved_reviews_count }} yorum)</span></span>
                            @endif
                        </div>
                        <a href="{{ route('companies.show', $company->slug) }}" class="mt-1.5 block truncate text-lg font-black" style="color:var(--text);">{{ $company->name }}</a>
                        <p class="mt-0.5 truncate text-xs" style="color:var(--text_muted);">{{ $company->category->name ?? 'Firma' }} · {{ $company->district ? $company->district->name . ' / ' : '' }}{{ $company->city->name ?? 'Türkiye' }}</p>
                        @if($company->short_description)
                            <p class="mt-1.5 line-clamp-2 text-xs leading-5" style="color:var(--text_muted);">{{ $company->short_description }}</p>
                        @endif
                        <div class="mt-3 flex flex-wrap gap-2">
                            @if($company->phone)
                                <a href="tel:{{ $company->phone }}" class="rounded-xl px-4 py-2 text-xs font-black transition hover:opacity-90" style="background:var(--primary);color:var(--btn_text,#fff);">Numarayı Göster</a>
                            @endif
                            <a href="{{ route('companies.show', $company->slug) }}" class="rounded-xl border px-4 py-2 text-xs font-black transition hover:opacity-75" style="border-color:var(--border);color:var(--text);">Detay</a>
                        </div>
                    </div>
                </article>
            @endforeach
        </div>
    </div>
</section>
@endif

{{-- ─── İSTATİSTİK BANDI ─── --}}
<section class="border-y py-12" style="border-color:var(--border);background:var(--bg_card);">
    <div class="mx-auto grid grid-cols-2 gap-8 px-4 text-center sm:px-6 md:grid-cols-4 lg:px-8" style="max-width:var(--page_width,1280px);">
        <div>
            <div class="text-3xl font-black sm:text-4xl" style="color:var(--primary);">{{ number_format($statsCompanies) }}</div>
            <div class="mt-1 text-xs font-bold uppercase tracking-widest" style="color:var(--text_muted);">Kayıtlı Firma</div>
        </div>
        <div>
            <div class="text-3xl font-black sm:text-4xl" style="color:var(--primary);">{{ number_format($statsCategories) }}</div>
            <div class="mt-1 text-xs font-bold uppercase tracking-widest" style="color:var(--text_muted);">Hizmet Kategorisi</div>
        </div>
        <div>
            <div class="text-3xl font-black sm:text-4xl" style="color:var(--primary);">{{ number_format($statsCities) }}</div>
            <div class="mt-1 text-xs font-bold uppercase tracking-widest" style="color:var(--text_muted);">İlde Hizmet</div>
        </div>
        <div>
            <div class="text-3xl font-black sm:text-4xl" style="color:var(--primary);">{{ number_format($statsReviews) }}</div>
            <div class="mt-1 text-xs font-bold uppercase tracking-widest" style="color:var(--text_muted);">Gerçek Yorum</div>
        </div>
    </div>
</section>

{{-- ─── CTA: firma ekle ─── --}}
<section class="py-16" style="background:var(--primary);">
    <div class="mx-auto grid gap-10 px-4 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8" style="max-width:var(--page_width,1280px);">
        <div>
            <h2 class="text-3xl font-black leading-tight sm:text-4xl" style="color:var(--btn_text,#fff);">Firmanız mı var? Müşterileriniz sizi arıyor.</h2>
            <p class="mt-4 max-w-xl text-sm leading-7 sm:text-base" style="color:var(--btn_text,#fff);opacity:.82;">Profilinizi oluşturun, bölgenizdeki binlerce müşteriye ulaşın. Komisyon yok, aracı yok; müşteri doğrudan sizi arar.</p>
            <div class="mt-7 flex flex-col gap-3 sm:flex-row">
                <a href="{{ route('listing.create') }}" class="rounded-xl px-7 py-3 text-center text-sm font-black shadow-lg transition hover:opacity-90" style="background:var(--accent);color:#fff;">Ücretsiz Firma Ekle</a>
                <a href="{{ route('packages.index') }}" class="rounded-xl border px-7 py-3 text-center text-sm font-black transition hover:opacity-80" style="border-color:var(--btn_text,#fff);color:var(--btn_text,#fff);">Üyelik Paketleri</a>
            </div>
        </div>
        <div class="grid gap-3 sm:grid-cols-2">
            <div class="rounded-2xl p-4" style="background:color-mix(in srgb, var(--btn_text,#fff) 12%, transparent);">
                <div class="font-black" style="color:var(--btn_text,#fff);">Sınırsız Erişim</div>
                <p class="mt-1 text-xs leading-5" style="color:var(--btn_text,#fff);opacity:.8;">Müşteriler sizi doğrudan arar, görüşme sınırı yoktur.</p>
            </div>
            <div class="rounded-2xl p-4" style="background:color-mix(in srgb, var(--btn_text,#fff) 12%, transparent);">
                <div class="font-black" style="color:var(--btn_text,#fff);">Aracısız Kazanç</div>
                <p class="mt-1 text-xs leading-5" style="color:var(--btn_text,#fff);opacity:.8;">Kazancınızın tamamı sizin; komisyon ödemezsiniz.</p>
            </div>
            <div class="rounded-2xl p-4" style="background:color-mix(in srgb, var(--btn_text,#fff) 12%, transparent);">
                <div class="font-black" style="color:var(--btn_text,#fff);">Öne Çıkma</div>
                <p class="mt-1 text-xs leading-5" style="color:var(--btn_text,#fff);opacity:.8;">Premium üyelikle listelerin üstünde yer alın.</p>
            </div>
            <div class="rounded-2xl p-4" style="background:color-mix(in srgb, var(--btn_text,#fff) 12%, transparent);">
                <div class="font-black" style="color:var(--btn_text,#fff);">Profil Yönetimi</div>
                <p class="mt-1 text-xs leading-5" style="color:var(--btn_text,#fff);opacity:.8;">Bilgilerinizi, fotoğraflarınızı ve saatlerinizi kendiniz yönetin.</p>
            </div>
        </div>
    </div>
</section>

@include('partials.blog-section')
@endsection
