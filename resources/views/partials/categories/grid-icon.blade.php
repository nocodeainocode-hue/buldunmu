{{-- Grid Icon — Varsayılan, Cesur, Elegant --}}
<div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 gap-4">
    @foreach($categories as $category)
        <a href="{{ route('categories.show', $category->slug) }}" class="group p-6 rounded-2xl hover:shadow-md transition-all duration-200 text-center" style="background-color: var(--bg); border: 1px solid var(--border); border-radius: var(--border_radius);">
            <div class="w-14 h-14 rounded-xl flex items-center justify-center mx-auto mb-3" style="background-color: var(--primary_light);">
                <span class="text-2xl">{{ $category->icon ?? '📁' }}</span>
            </div>
            <h3 class="font-semibold group-hover:underline transition" style="color: var(--text);">{{ $category->name }}</h3>
            <p class="text-sm mt-1" style="color: var(--text_muted);">{{ $category->companies_count }} firma</p>
        </a>
    @endforeach
</div>
