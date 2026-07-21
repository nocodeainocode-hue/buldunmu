<article class="group overflow-hidden border bg-white transition-all duration-300 hover:-translate-y-1 hover:shadow-xl" style="background:var(--bg_card);border-color:var(--border);border-radius:var(--border_radius);box-shadow:var(--card_shadow);">
    <a href="{{ route('companies.show', $company->slug) }}" class="block">
        <div class="relative aspect-[4/3] overflow-hidden" style="background:var(--primary_light);">
            @if($company->logo)
                <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" width="400" height="300" class="h-full w-full object-contain p-4">
            @elseif($company->cover_image)
                <img src="{{ asset('storage/' . $company->cover_image) }}" alt="{{ $company->name }}" width="400" height="300" class="h-full w-full object-cover transition duration-500 group-hover:scale-105">
            @else
                <div class="flex h-full items-center justify-center">
                    <div class="flex h-20 w-20 items-center justify-center rounded-2xl text-4xl font-black text-white shadow-lg" style="background:var(--primary);">
                        {{ mb_substr($company->name, 0, 1) }}
                    </div>
                </div>
            @endif

            @if($premium ?? false)
                <span class="absolute left-3 top-3 rounded-full px-3 py-1 text-xs font-bold uppercase tracking-wide text-white" style="background:var(--accent);">Premium</span>
            @endif
            @php $avgRating = $company->reviews_avg_rating ?? ($company->approved_reviews_avg_rating ?? null); @endphp
            @if($avgRating)
                <span class="absolute bottom-3 right-3 rounded-full bg-white/95 px-2.5 py-1 text-xs font-bold shadow flex items-center gap-0.5" style="color:var(--primary);">
                    ⭐ {{ number_format((float)$avgRating, 1) }}
                </span>
            @endif
        </div>
    </a>

    <div class="p-4">
        <div class="mb-2 flex items-start gap-3">
            <div class="mt-0.5 flex h-10 w-10 shrink-0 items-center justify-center rounded-xl text-sm font-bold text-white" style="background:var(--primary);">
                {{ mb_substr($company->name, 0, 1) }}
            </div>
            <div class="min-w-0 flex-1">
                <a href="{{ route('companies.show', $company->slug) }}" class="block truncate font-bold leading-tight" style="color:var(--text);">{{ $company->name }}</a>
                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs" style="color:var(--text_muted);">
                    @if($company->category)
                        <span class="rounded-full px-2 py-0.5 font-medium" style="background:var(--primary_light);color:var(--primary);">{{ $company->category->name }}</span>
                    @endif
                    @if($company->city)
                        <span>{{ $company->city->name }}{{ $company->district ? ' / ' . $company->district->name : '' }}</span>
                    @endif
                </div>
            </div>
        </div>

        @if($company->short_description)
            <p class="mb-4 line-clamp-2 text-sm leading-6" style="color:var(--text_muted);">{{ $company->short_description }}</p>
        @endif

        <div class="flex items-center gap-2 border-t pt-3" style="border-color:var(--border);">
            <a href="{{ route('companies.show', $company->slug) }}" class="flex-1 rounded-lg px-3 py-2 text-center text-sm font-bold transition hover:opacity-90" style="background:var(--primary_light);color:var(--primary);">Detay</a>
            @if($company->phone)
                <a href="tel:{{ $company->phone }}" class="rounded-lg px-3 py-2 text-sm font-bold text-white transition hover:opacity-90" style="background:var(--primary);">Ara</a>
            @endif
            @if($company->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" rel="noopener" class="rounded-lg px-3 py-2 text-sm font-bold transition hover:opacity-90" style="background:var(--accent);color:white;">WA</a>
            @endif
        </div>
    </div>
</article>
