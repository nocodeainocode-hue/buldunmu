{{-- Verified Card — Doğrulanmış rozeti önde --}}
<div class="group relative overflow-hidden rounded-xl border transition-all hover:shadow-lg" style="background:var(--bg_card);border-color:var(--border);box-shadow:var(--card_shadow);">
    {{-- Doğrulanmış rozeti --}}
    @if($company->is_verified ?? false)
    <div class="absolute right-3 top-3 z-10 flex items-center gap-1 rounded-full px-2.5 py-1 text-[10px] font-black uppercase tracking-wider text-white" style="background:var(--primary);">
        <svg class="h-3 w-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
        Doğrulanmış
    </div>
    @endif

    @if($premium ?? false)
    <div class="px-4 py-1.5 text-center text-xs font-bold uppercase tracking-wider" style="background:var(--accent);color:#fff;">⭐ Premium</div>
    @endif

    <div class="p-5">
        <div class="flex items-start gap-4">
            <div class="shrink-0">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-16 w-16 rounded-xl object-contain" style="background:var(--bg);">
                @else
                    <div class="flex h-16 w-16 items-center justify-center rounded-xl text-2xl font-black text-white" style="background:var(--primary);">
                        {{ mb_substr($company->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <a href="{{ route('companies.show', $company->slug) }}" class="block truncate text-lg font-black transition hover:underline" style="color:var(--text);">
                    {{ $company->name }}
                </a>
                <div class="mt-1.5 flex flex-wrap items-center gap-2 text-sm" style="color:var(--text_muted);">
                    @if($company->category)
                        <span class="rounded-full px-2 py-0.5 text-xs font-bold" style="background:var(--primary_light);color:var(--primary);">{{ $company->category->name }}</span>
                    @endif
                    @if($company->city)
                        <span>📍 {{ $company->city->name }}{{ $company->district ? ' / ' . $company->district->name : '' }}</span>
                    @endif
                </div>
                @if($company->short_description)
                    <p class="mt-2 line-clamp-2 text-sm" style="color:var(--text_muted);">{{ $company->short_description }}</p>
                @endif
            </div>
        </div>

        {{-- Güven göstergeleri --}}
        <div class="mt-4 flex items-center gap-4 border-t pt-4" style="border-color:var(--border);">
            <div class="flex items-center gap-1.5 text-xs" style="color:var(--text_muted);">
                <svg class="h-4 w-4" style="color:var(--primary);" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M6.267 3.455a3.066 3.066 0 001.745-.723 3.066 3.066 0 013.976 0 3.066 3.066 0 001.745.723 3.066 3.066 0 012.812 2.812c.051.643.304 1.254.723 1.745a3.066 3.066 0 010 3.976 3.066 3.066 0 00-.723 1.745 3.066 3.066 0 01-2.812 2.812 3.066 3.066 0 00-1.745.723 3.066 3.066 0 01-3.976 0 3.066 3.066 0 00-1.745-.723 3.066 3.066 0 01-2.812-2.812 3.066 3.066 0 00-.723-1.745 3.066 3.066 0 010-3.976 3.066 3.066 0 00.723-1.745 3.066 3.066 0 012.812-2.812zm7.44 5.252a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                <span>Kimlik ✓</span>
            </div>
            <div class="flex items-center gap-1.5 text-xs" style="color:var(--text_muted);">
                <svg class="h-4 w-4" style="color:var(--primary);" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                <span>Telefon ✓</span>
            </div>
            @if($company->reviews_count ?? 0)
            <div class="flex items-center gap-1.5 text-xs" style="color:var(--text_muted);">
                <svg class="h-4 w-4" style="color:var(--accent);" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/></svg>
                <span>{{ $company->reviews_count }} yorum</span>
            </div>
            @endif
        </div>

        <div class="mt-4 flex items-center gap-2">
            <a href="{{ route('companies.show', $company->slug) }}" class="flex-1 rounded-lg py-2.5 text-center text-sm font-black text-white transition hover:opacity-90" style="background:var(--primary);">
                Detaylı İncele
            </a>
            @if($company->phone)
                <a href="tel:{{ $company->phone }}" class="flex h-10 w-10 items-center justify-center rounded-lg transition hover:opacity-80" style="background:var(--primary_light);color:var(--primary);" title="Ara">📞</a>
            @endif
            @if($company->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="flex h-10 w-10 items-center justify-center rounded-lg transition hover:opacity-80" style="background:var(--primary_light);color:var(--primary);" title="WhatsApp">💬</a>
            @endif
        </div>
    </div>
</div>
