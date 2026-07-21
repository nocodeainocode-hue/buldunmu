{{-- Horizontal Card — Modern tema: yatay düzen, logo solda --}}
<a href="{{ route('companies.show', $company->slug) }}" class="flex items-center gap-4 p-4 border rounded-lg transition-all duration-200 hover:shadow-md group" style="background-color: var(--bg_card); border-color: var(--border); box-shadow: var(--card_shadow); border-radius: var(--border_radius);">
    <div class="shrink-0">
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" width="64" height="64" class="w-16 h-16 object-contain" style="border-radius: var(--border_radius); background:var(--bg);">
        @else
            <div class="w-16 h-16 flex items-center justify-center text-white font-bold text-2xl" style="background-color: var(--primary); border-radius: var(--border_radius);">{{ mb_substr($company->name, 0, 1) }}</div>
        @endif
    </div>
    <div class="flex-1 min-w-0">
        <div class="flex items-center gap-2">
            <h3 class="font-semibold truncate" style="color: var(--text);">{{ $company->name }}</h3>
            @if($premium ?? false)
                <span class="text-xs px-2 py-0.5 rounded-full text-white font-bold" style="background-color: var(--accent);">⭐</span>
            @endif
        </div>
        <div class="flex items-center gap-2 mt-1 text-xs" style="color: var(--text_muted);">
            @if($company->category)<span>{{ $company->category->name }}</span>@endif
            @if($company->city)<span>· {{ $company->city->name }}</span>@endif
        </div>
        @if($company->short_description)
            <p class="mt-1 text-sm line-clamp-1" style="color: var(--text_muted);">{{ $company->short_description }}</p>
        @endif
    </div>
    <div class="shrink-0 flex items-center gap-1">
        @if($company->phone)
            <span class="px-3 py-1.5 text-xs font-medium rounded-lg" style="background-color: var(--primary_light); color: var(--primary);">📞 Ara</span>
        @endif
    </div>
</a>
