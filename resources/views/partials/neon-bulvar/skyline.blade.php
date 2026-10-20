@php
    // Üç katmanlı şehir silueti: uzak (soluk), orta ve yakın (koyu). Binalar sabit listelerden çizilir; her yüklemede aynı görünür.
    // Her bina: [genişlik, yükseklik, pencere deseni (0 yok, 1 sıcak, 2 soğuk), tepe tipi (0 düz, 1 anten, 2 çatı, 3 kule)].
    $nbLayers = [
        ['cls' => 'nb-sky__far', 'y' => 150, 'b' => [[34,70,0,0],[22,110,0,1],[40,56,0,0],[28,128,0,3],[46,84,0,0],[20,60,0,0],[38,140,0,1],[30,96,0,2],[44,72,0,0],[26,118,0,0],[36,88,0,1],[48,64,0,0],[24,134,0,3],[42,78,0,0],[30,104,0,0],[38,92,0,2],[26,122,0,1],[44,66,0,0],[32,110,0,0],[40,80,0,0],[28,138,0,3],[36,70,0,0],[46,98,0,1],[22,60,0,0],[38,116,0,0],[30,84,0,2],[42,126,0,1],[34,74,0,0]]],
        ['cls' => 'nb-sky__mid', 'y' => 150, 'b' => [[52,96,2,2],[36,132,1,1],[64,78,2,0],[44,150,1,3],[58,104,2,0],[30,70,1,0],[70,124,2,1],[40,88,1,0],[56,144,2,2],[48,110,1,0],[62,82,2,0],[34,136,1,3],[68,98,2,1],[46,120,1,0],[54,76,2,0],[38,152,1,1],[66,106,2,2],[42,90,1,0],[60,130,2,0],[50,114,1,3]]],
        ['cls' => 'nb-sky__near', 'y' => 150, 'b' => [[90,58,1,0],[70,92,2,2],[110,70,1,1],[60,118,2,0],[96,64,1,0],[80,100,2,3],[120,76,1,0],[66,124,2,1],[104,56,1,0],[76,96,2,2],[114,68,1,0],[70,110,2,0],[98,82,1,1]]],
    ];
    $nbPatterns = [1 => 'nb-win-warm', 2 => 'nb-win-cool'];
    $nbTwinkle = 0;
@endphp
<div class="nb-sky" aria-hidden="true">
    <span class="nb-moon"></span>
    @foreach($nbLayers as $nbLayer)
        @php $nbX = 0; $nbTotal = array_sum(array_column($nbLayer['b'], 0)); @endphp
        <svg class="nb-sky__layer {{ $nbLayer['cls'] }}" viewBox="0 0 {{ $nbTotal }} {{ $nbLayer['y'] }}" preserveAspectRatio="none" focusable="false">
            @if($loop->first)
                <defs>
                    <pattern id="nb-win-warm" width="8" height="10" patternUnits="userSpaceOnUse"><rect x="2" y="2" width="3" height="4" fill="#ffd86b" opacity=".55"/></pattern>
                    <pattern id="nb-win-cool" width="8" height="10" patternUnits="userSpaceOnUse"><rect x="2" y="2" width="3" height="4" fill="#7fe8ff" opacity=".45"/></pattern>
                </defs>
            @endif
            @foreach($nbLayer['b'] as [$nbW, $nbH, $nbWin, $nbTop])
                @php $nbY = $nbLayer['y'] - $nbH; @endphp
                <rect class="nb-bld" x="{{ $nbX }}" y="{{ $nbY }}" width="{{ $nbW - 3 }}" height="{{ $nbH }}"/>
                @if($nbWin)
                    <rect x="{{ $nbX + 3 }}" y="{{ $nbY + 6 }}" width="{{ max($nbW - 9, 4) }}" height="{{ max($nbH - 12, 4) }}" fill="url(#{{ $nbPatterns[$nbWin] }})"/>
                    @if($nbTwinkle < 9 && $nbH > 90)
                        @php $nbTwinkle++; @endphp
                        <rect class="nb-win-on nb-win-on--{{ $nbTwinkle % 3 }}" x="{{ $nbX + 10 }}" y="{{ $nbY + 22 + ($nbTwinkle % 4) * 10 }}" width="3" height="4"/>
                    @endif
                @endif
                @if($nbTop === 1)
                    <rect class="nb-bld" x="{{ $nbX + intdiv($nbW, 2) - 2 }}" y="{{ $nbY - 16 }}" width="1.5" height="16"/><circle class="nb-beacon" cx="{{ $nbX + intdiv($nbW, 2) - 1.25 }}" cy="{{ $nbY - 17 }}" r="1.6"/>
                @elseif($nbTop === 2)
                    <path class="nb-bld" d="M{{ $nbX }} {{ $nbY }}L{{ $nbX + ($nbW - 3) / 2 }} {{ $nbY - 10 }}L{{ $nbX + $nbW - 3 }} {{ $nbY }}Z"/>
                @elseif($nbTop === 3)
                    <rect class="nb-bld" x="{{ $nbX + intdiv($nbW, 4) }}" y="{{ $nbY - 12 }}" width="{{ intdiv($nbW, 2) }}" height="12"/>
                @endif
                @php $nbX += $nbW; @endphp
            @endforeach
        </svg>
    @endforeach
    <div class="nb-road"><span class="nb-road__line"></span></div>
</div>
