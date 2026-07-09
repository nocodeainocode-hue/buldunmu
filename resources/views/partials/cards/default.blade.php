{{-- Default Card — Varsayılan, Cesur --}}
<div class="rounded-xl border overflow-hidden transition-all duration-200 group" style="background-color: var(--bg_card); border-color: var(--border); box-shadow: var(--card_shadow); border-radius: var(--border_radius);">
    @if($premium ?? false)
        <div class="px-4 py-1.5 text-center text-xs font-bold uppercase tracking-wider" style="background-color: var(--accent); color: #fff;">⭐ Premium</div>
    @endif
    <div class="p-5">
        <div class="flex items-start gap-4">
            <div class="shrink-0">
                @if($company->logo)
                    <img src="{{ asset('storage/' . $company->logo) }}" class="w-16 h-16 rounded-xl object-contain" style="background:var(--bg);">
                @else
                    <div class="w-16 h-16 rounded-xl flex items-center justify-center text-white font-bold text-2xl" style="background: var(--primary); border-radius: var(--border_radius);">
                        {{ mb_substr($company->name, 0, 1) }}
                    </div>
                @endif
            </div>
            <div class="flex-1 min-w-0">
                <a href="{{ route('companies.show', $company->slug) }}" class="font-semibold truncate block hover:underline" style="color: var(--text);">{{ $company->name }}</a>
                <div class="flex flex-wrap items-center gap-2 mt-1.5 text-sm" style="color: var(--text_muted);">
                    @if($company->category)
                        <span class="px-2 py-0.5 rounded-full text-xs font-medium" style="background-color: var(--primary_light); color: var(--primary);">{{ $company->category->name }}</span>
                    @endif
                    @if($company->city)
                        <span>📍 {{ $company->city->name }}{{ $company->district ? ' / ' . $company->district->name : '' }}</span>
                    @endif
                </div>
                @if($company->short_description)
                    <p class="mt-2 text-sm line-clamp-2" style="color: var(--text_muted);">{{ $company->short_description }}</p>
                @endif
            </div>
        </div>
        <div class="flex items-center gap-2 mt-4 pt-4 border-t" style="border-color: var(--border);">
            <a href="{{ route('companies.show', $company->slug) }}" class="flex-1 text-center px-3 py-2 text-sm font-medium rounded-lg transition" style="background-color: var(--primary_light); color: var(--primary);">Detay</a>
            @if($company->phone)
                <a href="tel:{{ $company->phone }}" class="p-2 rounded-lg transition" style="color: var(--text_muted);" title="Ara">📞</a>
            @endif
            @if($company->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="p-2 rounded-lg transition" style="color: var(--text_muted);" title="WhatsApp">💬</a>
            @endif
        </div>
    </div>
</div>
