<!DOCTYPE html>
<html lang="tr" class="scroll-smooth {{ \App\View\Helpers\ThemeHelper::templateClass($directory ?? null) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', $settings->site_name ?? 'Firma Rehberi')</title>
    <meta name="description" content="@yield('meta_description', $settings->meta_description ?? '')">
    <meta name="robots" content="@yield('robots', 'index,follow,max-image-preview:large,max-snippet:-1,max-video-preview:-1')">
    @php
        $seoName = $directory->name ?? $settings->site_name ?? 'Firma Rehberi';
        $seoTitle = trim($__env->yieldContent('title') ?: $seoName);
        $seoDescription = trim($__env->yieldContent('meta_description') ?: ($directory->meta_description ?? $settings->meta_description ?? ''));
        $seoCanonical = trim($__env->yieldContent('canonical') ?: url()->current());
    @endphp
    <link rel="canonical" href="{{ $seoCanonical }}">
    <meta property="og:site_name" content="{{ $seoName }}">
    <meta property="og:title" content="{{ $seoTitle }}">
    <meta property="og:description" content="{{ $seoDescription }}">
    <meta property="og:url" content="{{ $seoCanonical }}">
    <meta property="og:type" content="website">
    <meta name="twitter:card" content="summary">
    <meta name="twitter:title" content="{{ $seoTitle }}">
    <meta name="twitter:description" content="{{ $seoDescription }}">

    @if($__env->hasSection('canonical'))
        {{-- Page-specific canonical overrides the tenant-aware default above. --}}
    @endif

    @php $dirFavicon = ($directory->favicon ?? null) ?: ($settings->favicon ?? null); @endphp
    @if($dirFavicon)
        <link rel="icon" type="image/x-icon" href="{{ asset('storage/' . $dirFavicon) }}">
    @endif

    {{-- Apple touch icon (iOS home screen) --}}
    @php $dirLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null); @endphp
    @if($dirLogo)
        <link rel="apple-touch-icon" href="{{ asset('storage/' . $dirLogo) }}">
        <link rel="apple-touch-startup-image" href="{{ asset('storage/' . $dirLogo) }}">
    @endif

    {{-- PWA manifest — directory-aware, served via route --}}
    <link rel="manifest" href="{{ route('pwa.manifest') }}">
    <meta name="theme-color" content="{{ \App\View\Helpers\ThemeHelper::get('primary', $directory ?? null, '#4f46e5') }}">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}">

    <style>
        {!! \App\View\Helpers\ThemeHelper::cssVariables($directory ?? null) !!}
        @php $mobileShellTemplates = ['pocket-directory', 'social-feed', 'chat-directory']; @endphp
        @if(in_array(($directory->template ?? 'default'), $mobileShellTemplates))
        html.theme-{{ $directory->template }} body > header,
        html.theme-{{ $directory->template }} body > footer { display:none; }
        html.theme-{{ $directory->template }} body { background:var(--bg) !important; }
        html.theme-{{ $directory->template }} body > main { padding:4px 0; }
        @media (max-width:520px) {
            html.theme-{{ $directory->template }} body > main { padding:0; }
        }
        html.theme-{{ $directory->template }} .mobile-shell { border:0; border-radius:0; }
        @endif
    </style>

    {{-- Dynamic Google Fonts per template --}}
    @php $fontsUrl = \App\View\Helpers\ThemeHelper::googleFontsUrl($directory ?? null); @endphp
    @if($fontsUrl)
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ $fontsUrl }}" rel="stylesheet">
    @endif

    @if(request()->routeIs('home'))
        @include('partials.seo.json-ld', ['schema' => \App\Support\SeoSchema::home($settings ?? null, $directory ?? null)])
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
                        <img src="{{ asset('storage/' . $dirLogo) }}" alt="{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}" width="40" height="40" class="h-10 w-auto">
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
                        <div class="invisible absolute left-1/2 top-full z-50 mt-2 w-[min(92vw,54rem)] -translate-x-1/2 rounded-2xl border p-4 opacity-0 shadow-xl transition-all group-hover:visible group-hover:opacity-100" style="background:var(--bg_card);border-color:var(--border);">
                            @php $headerCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->withCount('companies')->orderBy('name')->take(35)->get(); @endphp
                            <div class="mb-3 flex items-center justify-between border-b pb-3" style="border-color:var(--border);">
                                <span class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">35 ortak kategori</span>
                                <a href="{{ route('companies.index') }}" class="text-xs font-bold" style="color:var(--secondary);">Tüm firmalar →</a>
                            </div>
                            <div class="grid grid-cols-2 gap-1 sm:grid-cols-3 lg:grid-cols-4">
                                @foreach($headerCategories as $cat)
                                    <a href="{{ route('categories.show', $cat->slug) }}" class="flex min-w-0 items-center gap-2 rounded-lg px-2 py-2 text-xs font-semibold transition hover:bg-black/5 dark:hover:bg-white/5" style="color:var(--text);">
                                        <span class="shrink-0 text-base">{{ $cat->icon ?? '◆' }}</span>
                                        <span class="min-w-0 truncate">{{ $cat->name }}</span>
                                    </a>
                                @endforeach
                            </div>
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

            {{-- Mobile menu backdrop --}}
            <div id="mobile-backdrop" class="fixed inset-0 z-40 hidden bg-black/40 backdrop-blur-sm md:hidden" aria-hidden="true"></div>

            {{-- Mobile menu panel — slides in from left, full height, scrollable --}}
            <div id="mobile-menu" class="fixed inset-y-0 left-0 z-50 w-[min(88vw,26rem)] translate-x-[-105%] overflow-hidden border-r shadow-2xl transition-transform duration-300 ease-in-out md:hidden" style="background:var(--bg_card);border-color:var(--border);">
                <div class="flex h-full flex-col">
                    {{-- Header row: logo + close --}}
                    <div class="flex shrink-0 items-center justify-between border-b px-4 py-3" style="border-color:var(--border);">
                        <a href="{{ route('home') }}" class="flex items-center gap-2" onclick="closeMobileMenu()">
                            @php $dirLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null); @endphp
                            @if($dirLogo)
                                <img src="{{ asset('storage/' . $dirLogo) }}" alt="{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}" width="32" height="32" class="h-8 w-auto">
                            @else
                                <div class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-black text-white" style="background:var(--primary);">
                                    {{ mb_substr($directory->name ?? $settings->site_name ?? 'F', 0, 1) }}
                                </div>
                            @endif
                            <span class="text-lg font-black" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Firma Rehberi' }}</span>
                        </a>
                        <button id="mobile-menu-close" class="rounded-lg p-2 transition hover:bg-black/5 dark:hover:bg-white/5" style="color:var(--text_muted);" aria-label="Menüyü kapat">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                        </button>
                    </div>

                    {{-- Scrollable body --}}
                    <div class="flex-1 overflow-y-auto overscroll-contain px-4 py-3">
                        {{-- Search --}}
                        <form action="{{ route('search') }}" method="GET" class="mb-4">
                            <div class="relative">
                                <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma, kategori veya şehir ara..."
                                    class="w-full rounded-xl border px-4 py-2.5 text-sm focus:outline-none focus:ring-2"
                                    style="border-color:var(--border);background:var(--bg);color:var(--text);">
                                <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--text_muted);" aria-label="Ara">
                                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </button>
                            </div>
                        </form>

                        {{-- Quick actions --}}
                        <div class="mb-4 flex gap-2">
                            <a href="{{ route('companies.index') }}" onclick="closeMobileMenu()" class="flex-1 rounded-xl px-4 py-2.5 text-center text-sm font-bold transition hover:opacity-80" style="background:var(--primary_light);color:var(--primary);">Firmalar</a>
                            <a href="{{ route('listing.create') }}" onclick="closeMobileMenu()" class="flex-1 rounded-xl px-4 py-2.5 text-center text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">+ Firma Ekle</a>
                        </div>

                        {{-- Categories — scrollable grid --}}
                        <div class="mb-4">
                            <div class="mb-2 flex items-center justify-between">
                                <span class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Kategoriler</span>
                                <span class="text-xs" style="color:var(--text_muted);">35</span>
                            </div>
                            @php $mobileCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->orderBy('name')->take(35)->get(); @endphp
                            <div class="grid grid-cols-2 gap-1">
                                @foreach($mobileCategories as $cat)
                                    <a href="{{ route('categories.show', $cat->slug) }}" onclick="closeMobileMenu()" class="flex items-center gap-2 rounded-lg px-2 py-2 text-xs font-semibold transition hover:bg-black/5 dark:hover:bg-white/5" style="color:var(--text);">
                                        <span class="shrink-0 text-base">{{ $cat->icon ?? '◆' }}</span>
                                        <span class="min-w-0 truncate">{{ $cat->name }}</span>
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    </div>

                    {{-- Fixed bottom links --}}
                    <div class="shrink-0 border-t px-4 py-3" style="border-color:var(--border);">
                        <nav class="flex flex-wrap gap-x-4 gap-y-2 text-xs font-semibold" style="color:var(--text_muted);">
                            <a href="{{ route('blog.index') }}" onclick="closeMobileMenu()" class="transition hover:opacity-70">Blog</a>
                            <a href="{{ route('pages.about') }}" onclick="closeMobileMenu()" class="transition hover:opacity-70">Hakkımızda</a>
                            <a href="{{ route('pages.contact') }}" onclick="closeMobileMenu()" class="transition hover:opacity-70">İletişim</a>
                            <a href="{{ route('pages.privacy') }}" onclick="closeMobileMenu()" class="transition hover:opacity-70">Gizlilik</a>
                            <a href="{{ route('pages.terms') }}" onclick="closeMobileMenu()" class="transition hover:opacity-70">Kullanım</a>
                        </nav>
                    </div>
                </div>
            </div>
        </div>
    </header>

    <main class="flex-1">
        @if(!request()->routeIs('home') && in_array(($directory->template ?? 'default'), $mobileShellTemplates))
            @include('partials.mobile-shell')
        @else
            @yield('content')
        @endif
    </main>

    <footer style="background-color:#0f172a;color:#cbd5e1;">
        <div class="mx-auto px-4 py-12 sm:px-6 lg:px-8" style="max-width:var(--page_width,1280px);">
            <div class="grid grid-cols-1 gap-8 md:grid-cols-4">
                <div>
                    @php
                        $footerName = $directory->name ?? $settings->site_name ?? 'Firma Rehberi';
                        $footerLogo = ($directory->logo ?? null) ?: ($settings->logo ?? null);
                    @endphp
                    <a href="{{ route('home') }}" class="inline-flex items-center gap-3" aria-label="{{ $footerName }} ana sayfa">
                        @if($footerLogo)
                            <img src="{{ asset('storage/' . $footerLogo) }}" alt="{{ $footerName }}" width="40" height="40" class="h-10 w-auto rounded-lg object-contain">
                        @else
                            <span class="flex h-10 w-10 items-center justify-center rounded-lg text-sm font-black" style="background:var(--primary);color:white;">{{ mb_substr($footerName, 0, 1) }}</span>
                        @endif
                        <span class="text-xl font-black" style="color:white;">{{ $footerName }}</span>
                    </a>
                    <p class="mt-3 max-w-xs text-sm leading-6" style="color:#94a3b8;">{{ $directory->meta_description ?? $settings->meta_description ?? 'Türkiye genelinde firma, kategori ve şehir araması için sade rehber deneyimi.' }}</p>
                </div>
                <div>
                    <h3 class="mb-4 text-xs font-black uppercase tracking-widest" style="color:white;">Linkler</h3>
                    <ul class="space-y-2 text-sm" style="color:#94a3b8;">
                        <li><a href="{{ route('companies.index') }}" class="transition hover:text-white">Firmalar</a></li>
                        <li><a href="{{ route('blog.index') }}" class="transition hover:text-white">Blog</a></li>
                        <li><a href="{{ route('listing.create') }}" class="transition hover:text-white">Firma Ekle</a></li>
                        <li><a href="{{ route('packages.index') }}" class="transition hover:text-white">Üyelik Paketleri</a></li>
                        <li><a href="{{ route('pages.about') }}" class="transition hover:text-white">Hakkımızda</a></li>
                        <li><a href="{{ route('pages.contact') }}" class="transition hover:text-white">İletişim</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="mb-4 text-xs font-black uppercase tracking-widest" style="color:white;">Kategoriler</h3>
                    <ul class="space-y-2 text-sm" style="color:#94a3b8;">
                        @php $footerCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->withCount('companies')->orderByDesc('companies_count')->take(5)->get(); @endphp
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
        document.addEventListener('DOMContentLoaded', function () {
            const menu = document.getElementById('mobile-menu');
            const backdrop = document.getElementById('mobile-backdrop');
            const openBtn = document.getElementById('mobile-menu-btn');
            const closeBtn = document.getElementById('mobile-menu-close');

            function openMobileMenu() {
                menu?.classList.remove('translate-x-[-105%]');
                backdrop?.classList.remove('hidden');
                document.body.style.overflow = 'hidden';
            }

            window.closeMobileMenu = function() {
                menu?.classList.add('translate-x-[-105%]');
                backdrop?.classList.add('hidden');
                document.body.style.overflow = '';
            };

            openBtn?.addEventListener('click', openMobileMenu);
            closeBtn?.addEventListener('click', closeMobileMenu);
            backdrop?.addEventListener('click', closeMobileMenu);

            // Close on Escape
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && !menu?.classList.contains('translate-x-[-105%]')) {
                    closeMobileMenu();
                }
            });
        });

        // PWA Service Worker registration
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js', { scope: '/' })
                    .then((reg) => console.log('[PWA] SW registered:', reg.scope))
                    .catch((err) => console.warn('[PWA] SW registration failed:', err));
            });
        }

        // PWA Install Prompt — exposes window._installPwa() for buttons to call
        (function () {
            let deferredPrompt = null;

            window._pwaInstallReady = false;

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;

                // Don't show if already in standalone mode
                if (window.matchMedia('(display-mode: standalone)').matches) return;
                if (navigator.standalone) return;

                window._pwaInstallReady = true;
                document.body.classList.add('pwa-installable');

                // Show install buttons
                document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.remove('hidden'));
            });

            window._installPwa = async function() {
                if (!deferredPrompt) return;
                deferredPrompt.prompt();
                const { outcome } = await deferredPrompt.userChoice;
                console.log('[PWA] Install outcome:', outcome);
                deferredPrompt = null;
                window._pwaInstallReady = false;
                document.body.classList.remove('pwa-installable');
                document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.add('hidden'));
            };

            window.addEventListener('appinstalled', () => {
                deferredPrompt = null;
                window._pwaInstallReady = false;
                document.body.classList.remove('pwa-installable');
                document.querySelectorAll('.pwa-install-btn').forEach(el => el.classList.add('hidden'));
            });
        })();
    </script>
    @stack('scripts')
</body>
</html>
