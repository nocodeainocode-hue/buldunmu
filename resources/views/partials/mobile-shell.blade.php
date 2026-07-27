{{-- mobile-shell.blade.php — generic phone shell for detail pages under mobile-first themes --}}
<div class="mobile-shell relative mx-auto min-h-screen overflow-hidden border bg-white shadow-2xl" style="max-width:480px;border-color:var(--border);border-radius:22px;background:var(--bg);">

    {{-- Top bar: back + title + search --}}
    <header class="sticky top-0 z-30 flex items-center justify-between border-b px-4 py-3" style="background:var(--bg_card);border-color:var(--border);">
        <a href="{{ route('home') }}" class="flex items-center gap-1 text-sm font-black" style="color:var(--primary);">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
            <span class="hidden sm:inline">{{ $directory->name ?? $settings->site_name ?? 'Rehber' }}</span>
        </a>
        <h1 class="truncate text-base font-black" style="color:var(--text);">{{ $directory->name ?? $settings->site_name ?? 'Rehber' }}</h1>
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
