{{-- Metro Card — Yatay, koyu tema --}}
<div class="group flex items-center gap-4 rounded-xl border p-4 transition hover:shadow-lg" style="background:var(--bg_card);border-color:var(--border);box-shadow:var(--card_shadow);">
    {{-- Sol: Logo --}}
    <div class="shrink-0">
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-14 w-14 rounded-lg object-contain" style="background:var(--bg);">
        @else
            <div class="flex h-14 w-14 items-center justify-center rounded-lg text-xl font-black" style="background:var(--secondary);color:#0f172a;">
                {{ mb_substr($company->name, 0, 1) }}
            </div>
        @endif
    </div>

    {{-- Orta: Bilgiler --}}
    <div class="min-w-0 flex-1">
        <div class="flex items-center gap-2">
            <a href="{{ route('companies.show', $company->slug) }}" class="truncate text-base font-black transition hover:underline" style="color:var(--text);">
                {{ $company->name }}
            </a>
            @if($premium ?? false)
                <span class="shrink-0 rounded px-1.5 py-0.5 text-[10px] font-black uppercase" style="background:var(--secondary);color:#0f172a;">PRO</span>
            @endif
        </div>
        <div class="mt-1 flex flex-wrap items-center gap-2 text-sm" style="color:var(--text_muted);">
            @if($company->category)
                <span style="color:var(--accent);">{{ $company->category->name }}</span>
                <span>·</span>
            @endif
            @if($company->city)
                <span>{{ $company->city->name }}{{ $company->district ? ' / ' . $company->district->name : '' }}</span>
            @endif
        </div>
        @if($company->short_description)
            <p class="mt-2 line-clamp-1 text-sm" style="color:var(--text_muted);">{{ $company->short_description }}</p>
        @endif
    </div>

    {{-- Sağ: Aksiyonlar --}}
    <div class="flex shrink-0 items-center gap-2">
        @if($company->phone)
            <a href="tel:{{ $company->phone }}" class="flex h-10 w-10 items-center justify-center rounded-lg transition hover:opacity-80" style="background:var(--bg);color:var(--text_muted);" title="Ara">📞</a>
        @endif
        @if($company->whatsapp)
            <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="flex h-10 w-10 items-center justify-center rounded-lg transition hover:opacity-80" style="background:var(--bg);color:var(--text_muted);" title="WhatsApp">💬</a>
        @endif
        <a href="{{ route('companies.show', $company->slug) }}" class="rounded-lg px-4 py-2 text-sm font-black transition hover:opacity-90" style="background:var(--accent);color:#0f172a;">
            Detay
        </a>
    </div>
</div>
