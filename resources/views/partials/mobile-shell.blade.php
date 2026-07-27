{{-- mobile-shell.blade.php — generic phone shell for detail pages under mobile-first themes --}}
<div class="mobile-shell relative mx-auto min-h-screen overflow-hidden border bg-white shadow-2xl" style="max-width:480px;border-color:var(--border);border-radius:22px;background:var(--bg);">

    {{-- Backdrop --}}
    <div id="ms-backdrop" class="absolute inset-0 z-40 hidden bg-black/40 backdrop-blur-sm" aria-hidden="true"></div>

    {{-- Slide-out menu panel --}}
    <div id="ms-menu" class="absolute inset-y-0 left-0 z-50 w-[min(88vw,20rem)] translate-x-[-105%] overflow-y-auto overscroll-contain border-r shadow-2xl transition-transform duration-300 ease-in-out" style="background:var(--bg_card);border-color:var(--border);">
        {{-- Menu header --}}
        <div class="flex shrink-0 items-center justify-between border-b px-4 py-3" style="border-color:var(--border);">
            <a href="{{ route('home') }}" class="flex items-center gap-2" onclick="closeMsMenu()">
                <span class="flex h-8 w-8 items-center justify-center rounded-lg text-xs font-black text-white" style="background:var(--primary);">{{ mb_substr($directory->name ?? $settings->site_name ?? 'F', 0, 1) }}</span>
                <span class="text-lg font-black" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Rehber' }}</span>
            </a>
            <button id="ms-close" class="rounded-lg p-2 transition hover:bg-black/5" style="color:var(--text_muted);" aria-label="Menüyü kapat">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        {{-- Menu body --}}
        <div class="px-4 py-3">
            <a href="{{ route('home') }}" onclick="closeMsMenu()" class="mb-3 flex items-center gap-2 rounded-xl px-3 py-2.5 text-sm font-bold" style="background:var(--primary_light);color:var(--primary);">← Ana Sayfa</a>

            <div class="mb-4 flex gap-2">
                <a href="{{ route('companies.index') }}" onclick="closeMsMenu()" class="flex-1 rounded-xl px-3 py-2 text-center text-sm font-bold" style="background:var(--bg);color:var(--text);">Firmalar</a>
                <a href="{{ route('listing.create') }}" onclick="closeMsMenu()" class="flex-1 rounded-xl px-3 py-2 text-center text-sm font-bold text-white" style="background:var(--primary);">+ Firma Ekle</a>
            </div>

            <form action="{{ route('search') }}" method="GET" class="mb-4">
                <div class="relative">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Firma, kategori veya şehir ara..." class="w-full rounded-xl border px-3 py-2.5 text-sm focus:outline-none focus:ring-2" style="border-color:var(--border);background:var(--bg);color:var(--text);">
                    <button type="submit" class="absolute right-3 top-1/2 -translate-y-1/2" style="color:var(--text_muted);" aria-label="Ara">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                </div>
            </form>

            <div class="mb-2 text-xs font-black uppercase tracking-widest" style="color:var(--primary);">Kategoriler</div>
            @php $msCategories = \App\Models\Category::active()->visibleForDirectory($directory ?? null)->orderBy('name')->take(35)->get(); @endphp
            <div class="grid grid-cols-2 gap-1">
                @foreach($msCategories as $cat)
                    <a href="{{ route('categories.show', $cat->slug) }}" onclick="closeMsMenu()" class="flex items-center gap-2 rounded-lg px-2 py-2 text-xs font-semibold transition hover:bg-black/5" style="color:var(--text);">
                        <span class="shrink-0 text-base">{{ $cat->icon ?? '◆' }}</span>
                        <span class="min-w-0 truncate">{{ $cat->name }}</span>
                    </a>
                @endforeach
            </div>

            <div class="mt-4 border-t pt-4" style="border-color:var(--border);">
                <nav class="flex flex-wrap gap-x-4 gap-y-2 text-xs font-semibold" style="color:var(--text_muted);">
                    <a href="{{ route('blog.index') }}" onclick="closeMsMenu()" class="transition hover:opacity-70">Blog</a>
                    <a href="{{ route('pages.about') }}" onclick="closeMsMenu()" class="transition hover:opacity-70">Hakkımızda</a>
                    <a href="{{ route('pages.contact') }}" onclick="closeMsMenu()" class="transition hover:opacity-70">İletişim</a>
                    <a href="{{ route('pages.privacy') }}" onclick="closeMsMenu()" class="transition hover:opacity-70">Gizlilik</a>
                </nav>
            </div>
        </div>
    </div>

    {{-- Top bar: hamburger + title + search --}}
    <header class="sticky top-0 z-30 flex items-center justify-between border-b px-4 py-3" style="background:var(--bg_card);border-color:var(--border);">
        <button id="ms-hamburger" class="rounded-lg p-1.5 transition hover:bg-black/5" style="color:var(--text);" aria-label="Menü">
            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        </button>
        <h1 class="truncate px-2 text-base font-black" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Rehber' }}</h1>
        <a href="{{ route('search') }}" class="flex h-9 w-9 items-center justify-center rounded-full" style="background:var(--primary_light);color:var(--primary);" aria-label="Ara">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35M17 10a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
        </a>
    </header>

    {{-- Page content with safe area for bottom nav --}}
    <div class="pb-24">
        @yield('content')
    </div>

    {{-- Bottom nav — shared across all mobile-first detail pages --}}
    <nav class="absolute inset-x-0 bottom-0 grid grid-cols-4 border-t bg-white px-1 py-1.5 text-center text-[11px] font-bold" style="border-color:var(--border);background:var(--bg_card);">
        <a href="{{ route('home') }}" class="rounded-xl py-1.5" style="color:var(--text_muted);">
            <span class="block text-base">🏠</span>Ana Sayfa
        </a>
        <a href="{{ route('companies.index') }}" class="py-1.5" style="color:var(--text_muted);">
            <span class="block text-base">🏢</span>Firmalar
        </a>
        <a href="{{ route('blog.index') }}" class="py-1.5" style="color:var(--text_muted);">
            <span class="block text-base">✨</span>Keşfet
        </a>
        <button onclick="window._installPwa ? window._installPwa() : null" class="pwa-install-btn hidden py-1.5" style="color:var(--primary);" aria-label="Uygulamayı yükle">
            <span class="block text-base">📲</span>Uygulama
        </button>
    </nav>
</div>

{{-- Mobile shell hamburger menu JS — scoped to this shell instance --}}
<script>
    (function () {
        const menu = document.getElementById('ms-menu');
        const backdrop = document.getElementById('ms-backdrop');
        const openBtn = document.getElementById('ms-hamburger');
        const closeBtn = document.getElementById('ms-close');

        if (!menu || !backdrop) return;

        function openMsMenu() {
            menu.classList.remove('translate-x-[-105%]');
            backdrop.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        window.closeMsMenu = function () {
            menu.classList.add('translate-x-[-105%]');
            backdrop.classList.add('hidden');
            document.body.style.overflow = '';
        };

        openBtn?.addEventListener('click', openMsMenu);
        closeBtn?.addEventListener('click', closeMsMenu);
        backdrop?.addEventListener('click', closeMsMenu);

        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape' && !menu.classList.contains('translate-x-[-105%]')) {
                closeMsMenu();
            }
        });
    })();
</script>
