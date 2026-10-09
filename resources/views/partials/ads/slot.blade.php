{{-- Reklam konumu: $placement = top | bottom. Sayfa bağlamından (şehir/kategori) hedeflenir. --}}
@php
    $adServer = app(\App\Services\AdServer::class);
    $adContext = $adServer->contextFromRequest(request());
    $adItem = $adServer->pick($placement, $directory ?? null, $adContext['city'], $adContext['category']);
@endphp
@if($adItem)
    @once
        <style>
            .adx { font-family: inherit; line-height: 1.4; }
            .adx a { color: inherit; text-decoration: none; }
            .adx__tag { flex: 0 0 auto; padding: 2px 8px; border: 1px solid currentColor; border-radius: 4px; font-size: 10.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; opacity: .75; }
            .adx__cta { flex: 0 0 auto; padding: 8px 18px; border-radius: 999px; font-size: 13.5px; font-weight: 700; white-space: nowrap; transition: transform .15s ease, filter .15s ease; }
            .adx__link:hover .adx__cta { transform: translateY(-1px); filter: brightness(1.06); }
            .adx--top { border-bottom: 1px solid rgba(0,0,0,.08); }
            .adx--top .adx__link { display: flex; align-items: center; justify-content: center; gap: 14px; flex-wrap: wrap; padding: 9px 16px; }
            .adx--top .adx__head { font-weight: 700; font-size: 14.5px; }
            .adx--top .adx__body { font-size: 13.5px; opacity: .85; }
            .adx--top img { display: block; width: 100%; max-height: 90px; object-fit: cover; }
            .adx--bottom { padding: 28px 16px 8px; }
            .adx--bottom .adx__wrap { max-width: var(--page_width, 1100px); margin-inline: auto; }
            .adx--bottom .adx__link { display: flex; align-items: center; justify-content: space-between; gap: 22px; flex-wrap: wrap; padding: 26px 30px; border-radius: 18px; }
            .adx--bottom .adx__text { display: grid; gap: 6px; max-width: 64ch; }
            .adx--bottom .adx__head { font-size: clamp(20px, 2.6vw, 28px); font-weight: 800; letter-spacing: -.02em; }
            .adx--bottom .adx__body { font-size: 15px; opacity: .85; }
            .adx--bottom .adx__cta { padding: 12px 24px; font-size: 15px; }
            .adx--bottom img { display: block; width: 100%; max-height: 250px; object-fit: cover; border-radius: 18px; }
            .adx__imgwrap { position: relative; display: block; }
            .adx__imgwrap .adx__tag { position: absolute; right: 10px; top: 10px; background: rgba(0,0,0,.55); color: #fff; border-color: rgba(255,255,255,.5); }
            @media (max-width: 640px) { .adx--top .adx__body { display: none; } .adx--bottom .adx__link { padding: 20px; } }
        </style>
        <script>
            // Gösterim: reklam ekranda en az %50 göründüğünde bir kez sayılır (bot/önizleme sayılmaz).
            document.addEventListener('DOMContentLoaded', function () {
                var ads = document.querySelectorAll('[data-ad-impression]');
                var fire = function (el) { new Image().src = el.getAttribute('data-ad-impression') + '?t=' + Date.now(); };
                if (!('IntersectionObserver' in window)) { ads.forEach(fire); return; }
                var io = new IntersectionObserver(function (entries) {
                    entries.forEach(function (e) { if (e.isIntersecting) { fire(e.target); io.unobserve(e.target); } });
                }, { threshold: 0.5 });
                ads.forEach(function (el) { io.observe(el); });
            });
        </script>
    @endonce

    <aside class="adx adx--{{ $placement }}" data-ad-impression="{{ route('ad.impression', $adItem) }}" aria-label="Reklam"
           @if(!$adItem->image_path && $placement === 'top') style="background:{{ e($adItem->bg_color) }};color:{{ e($adItem->text_color) }}" @endif>
        <div class="adx__wrap">
            <a class="adx__link" href="{{ route('ad.click', $adItem) }}" target="_blank" rel="sponsored nofollow noopener"
               @if(!$adItem->image_path && $placement === 'bottom') style="background:{{ e($adItem->bg_color) }};color:{{ e($adItem->text_color) }}" @endif>
                @if($adItem->image_path)
                    <span class="adx__imgwrap">
                        <img src="{{ asset('storage/'.$adItem->image_path) }}" alt="{{ $adItem->headline }}" loading="lazy">
                        <span class="adx__tag">Reklam</span>
                    </span>
                @elseif($placement === 'bottom')
                    <span class="adx__text">
                        <span class="adx__tag" style="justify-self:start">Reklam</span>
                        <span class="adx__head">{{ $adItem->headline }}</span>
                        @if($adItem->body)<span class="adx__body">{{ $adItem->body }}</span>@endif
                    </span>
                    <span class="adx__cta" style="background:{{ e($adItem->accent_color) }};color:#111">{{ $adItem->cta_label }} →</span>
                @else
                    <span class="adx__tag">Reklam</span>
                    <span class="adx__head">{{ $adItem->headline }}</span>
                    @if($adItem->body)<span class="adx__body">{{ $adItem->body }}</span>@endif
                    <span class="adx__cta" style="background:{{ e($adItem->accent_color) }};color:#111">{{ $adItem->cta_label }} →</span>
                @endif
            </a>
        </div>
    </aside>
@endif
