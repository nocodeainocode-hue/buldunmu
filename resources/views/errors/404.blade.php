<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Sayfa Bulunamadı — 404</title>
    @php
        $dir = \App\Models\Directory::where('domain', request()->getHost())->first();
        $siteName = $dir->name ?? 'Firma Rehberi';
    @endphp
    <style>
        {!! \App\View\Helpers\ThemeHelper::cssVariables($dir) !!}
    </style>
    @vite(['resources/css/app.css'])
</head>
<body class="min-h-screen flex flex-col" style="background:var(--bg);font-family:var(--font_body);">
    <main class="flex-1 flex items-center justify-center px-4 py-16">
        <div class="max-w-2xl w-full text-center">
            <div class="text-8xl font-black mb-4" style="color:var(--primary);">404</div>
            <h1 class="text-2xl font-bold mb-3" style="color:var(--text);">Aradığınız sayfa bulunamadı</h1>
            <p class="text-sm mb-8" style="color:var(--text_muted);">{{ $siteName }} üzerinde bu sayfa mevcut değil.</p>

            <div class="flex flex-wrap gap-3 justify-center mb-10">
                <a href="/" class="rounded-xl px-6 py-3 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Ana Sayfaya Dön</a>
                <a href="/firmalar" class="rounded-xl px-6 py-3 text-sm font-bold transition hover:opacity-90" style="background:var(--primary_light);color:var(--primary);">Firmaları İncele</a>
            </div>

            @php
                $_categories = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(8)->get();
            @endphp
            @if($_categories->isNotEmpty())
            <div>
                <h2 class="text-lg font-bold mb-4" style="color:var(--text);">Popüler Kategoriler</h2>
                <div class="flex flex-wrap gap-2 justify-center">
                    @foreach($_categories as $cat)
                        <a href="/kategori/{{ $cat->slug }}" class="rounded-full px-4 py-2 text-sm font-medium border transition hover:shadow-sm" style="border-color:var(--border);color:var(--text);background:var(--bg_card);">
                            {{ $cat->icon ?? '' }} {{ $cat->name }}
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
        </div>
    </main>
</body>
</html>
