@php $kvBlankLink = ! ($kvBlankDecor ?? false); @endphp
{{-- Süs kopyası (yelpazenin arkasındaki ikinci/üçüncü boş kart) bağlantı değildir; aynı bağlantı tekrarlanmaz. --}}
@if($kvBlankLink)<a class="kv-card kv-card--blank" href="{{ route('owner.register') }}" aria-label="Kartını bırak: firmanı ücretsiz ekle">@else<span class="kv-card kv-card--blank" aria-hidden="true">@endif
    @unless($kvBlankDecor ?? false)<span class="kv-tag">Senin yerin</span>@endunless
    <span class="kv-card__who" aria-hidden="true"><span class="kv-card__name kv-ghost">{{ $kvBlankTitle ?? 'Firma adın' }}</span></span>
    <span class="kv-card__unvan kv-ghost-line" aria-hidden="true"><span class="kv-card__unvan-rule"></span>Hizmetin · Şehrin</span>
    <span class="kv-card__where kv-ghost-line" aria-hidden="true">Semtin / Şehrin</span>
    <span class="kv-strip kv-strip--blank">
        <span class="kv-ghost-phone" aria-hidden="true">0 5__ ___ __ __</span>
        <span class="kv-blank-cta">Müşterin seni bu kartla arasın · Kartını bırak ›</span>
    </span>
@if($kvBlankLink)</a>@else</span>@endif
