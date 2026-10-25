@php
    // Yuvarlak damga: üst yayda yer adı, ortada tarih, alt yayda kısa not. Yazılar Türkçe büyük harfe çevrilir.
    $pmUp = fn ($s) => mb_strtoupper(str_replace(['i', 'ı'], ['İ', 'I'], (string) $s), 'UTF-8');
    $pmTop = $pmUp($top ?? '');
    $pmBottom = $pmUp($bottom ?? '');
    $pmMid = (string) ($mid ?? '');
    $pmLen = mb_strlen($pmTop);
    $pmTopSize = $pmLen > 13 ? 9 : ($pmLen > 10 ? 10.5 : 12);
    $pmBotLen = mb_strlen($pmBottom);
    $pmBotSize = $pmBotLen > 14 ? 8 : 9.5;
@endphp
<svg class="pp-pm{{ isset($class) ? ' '.$class : '' }}" viewBox="0 0 120 120" aria-hidden="true" focusable="false">
    <defs>
        <path id="{{ $id }}-t" d="M 26 60 A 34 34 0 0 1 94 60"/>
        <path id="{{ $id }}-b" d="M 17 60 A 43 43 0 0 0 103 60"/>
    </defs>
    <circle cx="60" cy="60" r="56" fill="none" stroke="currentColor" stroke-width="2.6"/>
    <circle cx="60" cy="60" r="47" fill="none" stroke="currentColor" stroke-width="1.2"/>
    <text class="pp-pm__arc" font-size="{{ $pmTopSize }}"><textPath href="#{{ $id }}-t" startOffset="50%" text-anchor="middle">{{ $pmTop }}</textPath></text>
    <line x1="16" y1="49" x2="104" y2="49" stroke="currentColor" stroke-width="1.2"/>
    <line x1="16" y1="71" x2="104" y2="71" stroke="currentColor" stroke-width="1.2"/>
    <text class="pp-pm__mid" x="60" y="64.5" text-anchor="middle" font-size="{{ mb_strlen($pmMid) > 9 ? 12.5 : 14 }}">{{ $pmMid }}</text>
    @if($pmBottom !== '')
        <text class="pp-pm__arc" font-size="{{ $pmBotSize }}"><textPath href="#{{ $id }}-b" startOffset="50%" text-anchor="middle">{{ $pmBottom }}</textPath></text>
    @else
        <text class="pp-pm__star" x="60" y="94" text-anchor="middle" font-size="12">★</text>
    @endif
</svg>
