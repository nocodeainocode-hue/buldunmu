{{-- Minimal Hero — Minimal tema --}}
<section class="py-20 sm:py-28" style="background-color: var(--bg);">
    <div class="max-w-3xl mx-auto px-4 text-center">
        <h1 class="text-3xl sm:text-5xl font-light tracking-tight mb-4" style="color: var(--text);">{{ $title }}</h1>
        <p class="text-lg mb-10 max-w-xl mx-auto" style="color: var(--text_muted);">{{ $subtitle }}</p>
        <form action="{{ route('companies.index') }}" method="GET" class="max-w-xl mx-auto">
            <div class="flex gap-2">
                <input type="text" name="q" placeholder="Arama yap..."
                    class="flex-1 px-5 py-3 border rounded-lg text-sm focus:ring-2 focus:outline-none" style="border-color: var(--border); background: var(--bg_card); color: var(--text);">
                <button type="submit" class="px-6 py-3 text-white text-sm font-medium rounded-lg transition" style="background-color: var(--primary);">Ara</button>
            </div>
        </form>
        <div class="flex justify-center gap-6 mt-10 text-sm" style="color: var(--text_muted);">
            @php $cats = \App\Models\Category::active()->withCount('companies')->orderByDesc('companies_count')->take(6)->get(); @endphp
            @foreach($cats as $cat)
                <a href="{{ route('categories.show', $cat->slug) }}" class="hover:underline">{{ $cat->name }}</a>
            @endforeach
        </div>
    </div>
</section>
