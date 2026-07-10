<!DOCTYPE html>
<html lang="tr" class="scroll-smooth {{ \App\View\Helpers\ThemeHelper::templateClass($directory ?? null) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings->site_name ?? 'Firma Rehberi')</title>
    <meta name="description" content="@yield('meta_description', $settings->meta_description ?? '')">

    @hasSection('canonical')
        <link rel="canonical" href="@yield('canonical')">
    @endif

    @php $dirFavicon = ($directory->favicon ?? null) ?: ($settings->favicon ?? null); @endphp
    @if($dirFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $dirFavicon) }}">
    @endif

    <style>
        {!! \App\View\Helpers\ThemeHelper::cssVariables($directory ?? null) !!}
    </style>

    {{-- Dynamic Google Fonts per template --}}
    @php $fontsUrl = \App\View\Helpers\ThemeHelper::googleFontsUrl($directory ?? null); @endphp
    @if($fontsUrl)
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontsUrl }}" rel="stylesheet">
    @endif

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('head')
</head>
<body class="min-h-screen flex flex-col" style="background-color:var(--bg);color:var(--text);font-family:var(--font_body);">
    <header class="sticky top-0 z-50 border-b backdrop-blur" style="background-color:color-mix(in srgb,var(--bg_card) 92%,transparent);border-color:var(--border);box-shadow:var(--card_shadow);">
        <div class="mx-auto px-4 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="flex h-16 items-center justify-between">
                <a href="{{ route('home') }}" class="flex shrink-0 items-center gap-2">
                    @php $dirLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null); @endphp
                    @if($dirLogo)
                        <img src="{{ asset('storage/' . $dirLogo) }}" alt="{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}" class="h-10 w-auto">
                    @else
                        <div class="flex h-10 w-10 items-center justify-center rounded-xl text-sm font-black text-white" style="background:var(--primary);">
                            {{ mb_substr($directory->name ?? $settings->site_name ?? 'F', 0, 1) }}
                        </div>
                    @endif
                    <span class="hidden text-xl font-black sm:block" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}</span>
                </a>

                <nav class="hidden items-center gap-1 md:flex">
                    <form action="{{ route('search') }}" method="GET" class="relative mr-3">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma, kategori veya şehir ara..."
                            class="w-48 lg:w-64 rounded-xl border px-4 py-2 text-sm focus:outline-none focus:ring-2"
                            style="border-color:var(--border);background:var(--bg);color:var(--text);">
                        <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 p-1" style="color:var(--text_muted);" aria-label="Ara">
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </form>
                    <a href="{{ route('companies.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition hover:opacity-70" style="color:var(--text);">Firmalar</a>
                    <div class="group relative">
                        <button class="flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-bold transition hover:opacity-70" style="color:var(--text);">
                            Kategoriler
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="invisible absolute left-0 top-full z-50 mt-2 w-60 rounded-2xl border p-2 opacity-0 shadow-xl transition-all group-hover:visible group-hover:opacity-100" style="background:var(--bg_card);border-color:var(--border);">
                            @php $headerCategories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(8)->get(); @endphp
                            @foreach($headerCategories as $cat)
                                <a href="{{ route('categories.show', $cat->slug) }}" class="block rounded-xl px-3 py-2 text-sm font-semibold transition hover:opacity-70" style="color:var(--text);">
                                    {{ $cat->name }}
                                    <span class="ml-1 text-xs" style="color:var(--text_muted);">({{ $cat->companies_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <div class="group relative">
                        <button class="flex items-center gap-1 rounded-lg px-4 py-2 text-sm font-bold transition hover:opacity-70" style="color:var(--text);">
                            Şehirler
                            <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div class="invisible absolute left-0 top-full z-50 mt-2 w-60 rounded-2xl border p-2 opacity-0 shadow-xl transition-all group-hover:visible group-hover:opacity-100" style="background:var(--bg_card);border-color:var(--border);">
                            @php $headerCities = \App\Models\City::withCount('companies')->orderByDesc('companies_count')->take(8)->get(); @endphp
                            @foreach($headerCities as $city)
                                <a href="{{ route('cities.show', $city->slug) }}" class="block rounded-xl px-3 py-2 text-sm font-semibold transition hover:opacity-70" style="color:var(--text);">
                                    {{ $city->name }}
                                    <span class="ml-1 text-xs" style="color:var(--text_muted);">({{ $city->companies_count }})</span>
                                </a>
                            @endforeach
                        </div>
                    </div>
                    <a href="{{ route('blog.index') }}" class="rounded-lg px-4 py-2 text-sm font-bold transition hover:opacity-70" style="color:var(--text);">Blog</a>
                    <a href="{{ route('listing.create') }}" class="ml-2 rounded-xl px-4 py-2 text-sm font-black text-white shadow-sm transition hover:opacity-90" style="background:var(--primary);">+ Firma Ekle</a>
                </nav>

                <button id="mobile-menu-btn" class="rounded-lg p-2 md:hidden" style="color:var(--text_muted);" aria-label="Menu">
                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                </button>
            </div>

            <div id="mobile-menu" class="hidden border-t py-3 md:hidden" style="border-color:var(--border);">
                <form action="{{ route('search') }}" method="GET" class="mb-3">
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma, kategori veya şehir ara..."
                            class="w-full rounded-xl border px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                            style="border-color:var(--border);background:var(--bg);color:var(--text);">
                        <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--text_muted);" aria-label="Ara">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </button>
                    </div>
                </form>
                <nav class="flex flex-col gap-1">
                    <a href="{{ route('companies.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold" style="color:var(--text);">Firmalar</a>
                    <a href="{{ route('blog.index') }}" class="rounded-lg px-3 py-2 text-sm font-semibold" style="color:var(--text);">Blog</a>
                    <a href="{{ route('listing.create') }}" class="rounded-lg px-3 py-2 text-sm font-semibold text-white" style="background:var(--primary);">Firma Ekle</a>
                    <a href="{{ route('pages.about') }}" class="rounded-lg px-3 py-2 text-sm font-semibold" style="color:var(--text);">Hakkımızda</a>
                    <a href="{{ route('pages.contact') }}" class="rounded-lg px-3 py-2 text-sm font-semibold" style="color:var(--text);">İletişim</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="flex-1">
        @yield('content')
    </main>

    <footer style="background-color:#0f172a;color:#cbd5e1;">
        <div class="mx-auto px-4 py-12 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div>
                    <span class="text-xl font-black" style="color:white;">{{ $settings->site_name ?? 'Firma Rehberi' }}</span>
                    <p class="mt-3 max-w-xs text-sm leading-6" style="color:#94a3b8;">Türkiye genelinde firma, kategori ve şehir araması için sade rehber deneyimi.</p>
                </div>
                <div>
                    <h3 class="mb-4 text-xs font-black uppercase tracking-widest" style="color:white;">Linkler</h3>
                    <ul class="space-y-2 text-sm" style="color:#94a3b8;">
                        <li><a href="{{ route('companies.index') }}" class="transition hover:text-white">Firmalar</a></li>
                        <li><a href="{{ route('blog.index') }}" class="transition hover:text-white">Blog</a></li>
                        <li><a href="{{ route('listing.create') }}" class="transition hover:text-white">Firma Ekle</a></li>
                        <li><a href="{{ route('pages.about') }}" class="transition hover:text-white">Hakkımızda</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="transition hover:text-white">İletişim</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-xs font-black uppercase tracking-widest" style="color:white;">Kategoriler</h3>
                    <ul class="space-y-2 text-sm" style="color:#94a3b8;">
                        @php $footerCategories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(5)->get(); @endphp
                        @foreach($footerCategories as $cat)
                            <li><a href="{{ route('categories.show', $cat->slug) }}" class="transition hover:text-white">{{ $cat->name }}</a></li>
                        @endforeach
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-xs font-black uppercase tracking-widest" style="color:white;">Yasal</h3>
                    <ul class="space-y-2 text-sm" style="color:#94a3b8;">
                        <li><a href="{{ route('pages.privacy') }}" class="transition hover:text-white">Gizlilik Politikası</a></li>
                        <li><a href="{{ route('pages.terms') }}" class="transition hover:text-white">Kullanım Şartları</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <script>
        document.getElementById('mobile-menu-btn')?.addEventListener('click', function() {
            document.getElementById('mobile-menu')?.classList.toggle('hidden');
        });
    </script>
    @stack('scripts')
</body>
</html>
