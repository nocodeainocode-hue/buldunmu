{{-- Editorial Card — Dergi vitrin tarzı, serif --}}
<article class="group flex h-full flex-col border p-6 transition hover:shadow-xl" style="background:var(--bg_card);border-color:var(--border);box-shadow:var(--card_shadow);">
    {{-- Üst: kategori + premium --}}
    <div class="mb-4 flex items-center justify-between">
        @if($company->category)
            <span class="text-xs font-black uppercase tracking-widest" style="color:var(--accent);">{{ $company->category->name }}</span>
        @else
            <span></span>
        @endif
        @if($premium ?? false)
            <span class="text-xs font-black uppercase tracking-widest" style="color:var(--primary);">★ Seçki</span>
        @endif
    </div>

    {{-- Logo --}}
    <div class="mb-5 flex justify-center">
        @if($company->logo)
            <img src="{{ asset('storage/' . $company->logo) }}" alt="{{ $company->name }}" class="h-20 w-20 rounded-full object-contain p-2" style="background:var(--bg);border:1px solid var(--border);">
        @else
            <div class="flex h-20 w-20 items-center justify-center rounded-full text-3xl font-black" style="background:var(--bg);color:var(--primary);border:1px solid var(--border);font-family:var(--font_heading);">
                {{ mb_substr($company->name, 0, 1) }}
            </div>
        @endif
    </div>

    {{-- Başlık --}}
    <h3 class="text-center text-xl font-black leading-snug" style="color:var(--text);font-family:var(--font_heading);">
        <a href="{{ route('companies.show', $company->slug) }}" class="transition group-hover:underline">{{ $company->name }}</a>
    </h3>

    {{-- Konum --}}
    @if($company->city)
    <div class="mt-2 text-center text-xs uppercase tracking-widest" style="color:var(--text_muted);">
        {{ $company->city->name }}{{ $company->district ? ' — ' . $company->district->name : '' }}
    </div>
    @endif

    {{-- Açıklama --}}
    @if($company->short_description)
    <p class="mt-4 line-clamp-3 flex-1 text-center text-sm italic leading-7" style="color:var(--text_muted);font-family:var(--font_heading);">
        "{{ $company->short_description }}"
    </p>
    @else
    <div class="flex-1"></div>
    @endif

    {{-- Alt çizgi + aksiyonlar --}}
    <div class="mt-6 border-t pt-5" style="border-color:var(--border);">
        <div class="flex items-center justify-center gap-3">
            <a href="{{ route('companies.show', $company->slug) }}" class="border-b-2 pb-0.5 text-xs font-black uppercase tracking-widest transition hover:opacity-70" style="color:var(--primary);border-color:var(--primary);">
                İncele
            </a>
            @if($company->phone)
                <a href="tel:{{ $company->phone }}" class="text-lg transition hover:opacity-70" title="Ara" style="color:var(--accent);">✆</a>
            @endif
            @if($company->whatsapp)
                <a href="https://wa.me/{{ preg_replace('/[^0-9]/', '', $company->whatsapp) }}" target="_blank" class="text-lg transition hover:opacity-70" title="WhatsApp" style="color:var(--accent);">✉</a>
            @endif
        </div>
    </div>
</article>
