@php
    // Çini desenleri CSS arka planı olarak satır içi SVG (data URI) ile çizilir; görsel dosyası yüklenmez.
    // Gül: dört uzun saz yaprağı + dört lale goncası + ortada küçük çiçek (header'daki #cn-rozet ile aynı çizim).
    $cnRoz = fn (string $a, string $b, string $c, string $in = '#fffdf7') =>
        '<g fill="'.$a.'"><path id="p" d="M0-13C10-22 10-37 0-49C-10-37-10-22 0-13Z"/><use href="#p" transform="rotate(90)"/><use href="#p" transform="rotate(180)"/><use href="#p" transform="rotate(270)"/></g>'
        .'<g fill="'.$in.'"><path id="q" d="M0-21C3.6-27 3.6-35 0-41C-3.6-35-3.6-27 0-21Z"/><use href="#q" transform="rotate(90)"/><use href="#q" transform="rotate(180)"/><use href="#q" transform="rotate(270)"/></g>'
        .'<g fill="'.$b.'"><path id="b" transform="rotate(45)" d="M0-15C7-18 9-26 6.5-34L2.5-29.5 0-36-2.5-29.5-6.5-34C-9-26-7-18 0-15Z"/><use href="#b" transform="rotate(90)"/><use href="#b" transform="rotate(180)"/><use href="#b" transform="rotate(270)"/></g>'
        .'<circle r="12.5" fill="'.$a.'"/><circle r="5" fill="'.$in.'"/><circle r="2.6" fill="'.$c.'"/>';
    // Lale (taban 20,56): yan taç yapraklar $a, orta yaprak $c, sap ve yapraklar $l.
    $cnLale = fn (string $a, string $c, string $l) =>
        '<path fill="'.$l.'" d="M20 52C12 48 6 40 4.5 31C11 35 17 41 20 52ZM20 47C28 43 34 36 35.5 28C29 31.5 23 37 20 47ZM19.1 27h1.8v27h-1.8z"/>'
        .'<path fill="'.$a.'" d="M20 29C10.5 28 6.5 19 8.5 8.5C12 15 16 21 20 29ZM20 29C29.5 28 33.5 19 31.5 8.5C28 15 24 21 20 29Z"/>'
        .'<path fill="'.$c.'" d="M20 3C25.5 9.5 26.5 20 20 29C13.5 20 14.5 9.5 20 3Z"/>';
    // Karo: köşelerde gül çeyrekleri (komşu karolarla tam gül olur), kenar ortalarında sırt sırta laleler.
    $cnTile = function (int $s, float $k, float $tk, array $col, string $grout, float $op = 1, bool $side = true) use ($cnRoz, $cnLale) {
        [$a, $b, $c, $in] = $col;
        $h = $s / 2;
        $lale = '<g id="l">'.$cnLale($a, $c, $b).'</g>';
        $t = fn ($x, $y, $rot) => '<use href="#l" transform="translate('.$x.' '.$y.') rotate('.$rot.') scale('.$tk.') translate(-20 -56)"/>';
        $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="'.$s.'" height="'.$s.'" viewBox="0 0 '.$s.' '.$s.'">'
            .'<defs><g id="r" transform="scale('.$k.')">'.$cnRoz($a, $b, $c, $in).'</g>'.$lale.'</defs>'
            .'<g opacity="'.$op.'">'
            .($grout !== '' ? '<path d="M0 0H'.$s.'M0 0V'.$s.'" stroke="'.$grout.'" stroke-width="2.5" fill="none"/>' : '')
            .'<use href="#r"/><use href="#r" x="'.$s.'"/><use href="#r" y="'.$s.'"/><use href="#r" x="'.$s.'" y="'.$s.'"/>'
            .$t($h, 0, 180).$t($h, $s, 0).($side ? $t(0, $h, 90).$t($s, $h, -90) : '')
            .'</g></svg>';

        return 'url("data:image/svg+xml,'.rawurlencode($svg).'")';
    };
    $cnBlue = '#1d3a7a'; $cnTq = '#1f8a8c'; $cnCoral = '#c8402e'; $cnGreen = '#3f7d4e'; $cnGlaze = '#fffdf7';
    $cnWall = $cnTile(150, .86, .6, [$cnBlue, $cnTq, $cnCoral, $cnGlaze], '#e3d7bd', .9, false);
    $cnWallWhite = $cnTile(150, .86, .6, ['#ffffff', '#ffffff', '#ffffff', 'transparent'], 'rgba(255,255,255,.6)', .1, false);
    $cnSir = [
        1 => $cnTile(64, .5, .36, [$cnBlue, $cnTq, $cnCoral, $cnGlaze], '#e6dcc4'),
        2 => $cnTile(64, .5, .36, [$cnTq, $cnBlue, $cnCoral, $cnGlaze], '#e6dcc4'),
        3 => $cnTile(64, .5, .36, [$cnBlue, $cnGreen, $cnCoral, $cnGlaze], '#e6dcc4'),
        4 => $cnTile(64, .5, .36, [$cnGreen, $cnTq, $cnBlue, $cnGlaze], '#e6dcc4'),
    ];
    $cnUri = fn (string $svg) => 'url("data:image/svg+xml,'.rawurlencode($svg).'")';
    // Su yolu şeridi: krem üstünde turkuaz baklavalar.
    $cnStrip = $cnUri('<svg xmlns="http://www.w3.org/2000/svg" width="16" height="10" viewBox="0 0 16 10"><path fill="#1f8a8c" d="M8 1.5L13 5 8 8.5 3 5Z"/></svg>');
    // Pano çerçevesi: lacivert bant, turkuaz baklava, içinde krem nokta.
    $cnFrame = $cnUri('<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 22 22"><rect width="22" height="22" fill="#1d3a7a"/><path fill="#1f8a8c" d="M11 3.5L18.5 11 11 18.5 3.5 11Z"/><path fill="#fffdf7" d="M11 8.5L13.5 11 11 13.5 8.5 11Z"/></svg>');
@endphp
<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,500;0,700;0,800;1,500;1,600&family=Nunito:wght@400;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alegreya:ital,wght@0,500;0,700;0,800;1,500;1,600&family=Nunito:wght@400;600;700;800&display=swap"></noscript>
<style>
    /* Çini Pano · İznik Karosu */
    .theme-cini-pano {
        --cn-blue: #1d3a7a; --cn-blue-2: #152b5c;
        --cn-tq: #1f8a8c; --cn-tq-ink: #17696b; --cn-tq-soft: #dcefec;
        --cn-coral: #c8402e; --cn-coral-2: #a93322;
        --cn-green: #3f7d4e; --cn-wa: #1e8a4c;
        --cn-glaze: #fffdf7; --cn-cream: #f6f1e4; --cn-cream-2: #efe7d3;
        --cn-grout: #dccfb2; --cn-line: #ddd3bd;
        --cn-ink: #1b2442; --cn-muted: #4d5670;
        --cn-kartus: polygon(18px 0, calc(100% - 18px) 0, 100% 50%, calc(100% - 18px) 100%, 18px 100%, 0 50%);
        --cn-wall: {!! $cnWall !!};
        --cn-wall-w: {!! $cnWallWhite !!};
        --cn-strip: {!! $cnStrip !!};
        --cn-frame: {!! $cnFrame !!};
        --cn-plate: inset 0 1px 0 #fff, 0 1px 0 var(--cn-line), 0 16px 30px -20px rgba(27,36,66,.42);
        scroll-padding-top: 90px;
    }
    .theme-cini-pano body { background: var(--cn-cream); color: var(--cn-ink); font-size: 16px; line-height: 1.6; }
    .theme-cini-pano :where(h1,h2,h3) { font-family: var(--font_heading); }
    .theme-cini-pano :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--cn-blue); outline-offset: 3px; }
    .theme-cini-pano #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .cn-wrap { max-width: var(--page_width, 1240px); margin-inline: auto; padding-inline: 32px; }
    .cn-home { overflow-x: clip; }

    /* Düğmeler ve bağlantılar */
    .cn-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 22px; border-radius: 6px; border: 2px solid transparent; font: 800 16px/1.2 var(--font_body); text-align: center; text-decoration: none; cursor: pointer; transition: background-color .16s, color .16s, border-color .16s; }
    .cn-btn--sm { min-height: 42px; padding: 0 16px; white-space: nowrap; }
    .cn-btn--lg { min-height: 54px; padding: 0 26px; font-size: 17px; }
    .cn-btn--coral { background: var(--cn-coral); color: #fff; box-shadow: inset 0 1px 0 rgba(255,255,255,.28), 0 6px 14px -8px rgba(168,51,34,.7); }
    .cn-btn--coral:hover { background: var(--cn-coral-2); }
    .cn-btn--line { background: transparent; color: var(--cn-blue); border-color: var(--cn-blue); }
    .cn-btn--line:hover { background: var(--cn-blue); color: #fff; }
    .cn-link { display: inline-flex; align-items: center; gap: 8px; min-height: 44px; color: var(--cn-blue); font-weight: 800; text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .cn-link:hover { text-decoration-thickness: 2px; }
    .cn-loz { display: inline-block; flex: none; width: 7px; height: 7px; background: var(--cn-tq); transform: rotate(45deg); vertical-align: middle; }
    .cn-link .cn-loz { margin-left: 2px; }
    /* Sekiz köşe yıldız: iki kare, biri 45° döndürülmüş. */
    .cn-star { position: relative; display: inline-block; flex: none; width: 9px; height: 9px; margin: 0 12px; vertical-align: middle; }
    .cn-star::before, .cn-star::after { content: ""; position: absolute; inset: 0; background: var(--cn-tq); }
    .cn-star::after { transform: rotate(45deg); }
    .cn-star--light::before, .cn-star--light::after { background: #9fdfe1; }

    /* Üst şerit */
    .cn-header { position: sticky; top: 0; z-index: 50; background: rgba(252,249,241,.96); backdrop-filter: blur(8px); }
    .cn-header::after { content: ""; position: absolute; left: 0; right: 0; top: 100%; height: 13px; background: var(--cn-strip) repeat-x 0 3px / 16px 10px, linear-gradient(var(--cn-blue), var(--cn-blue)) no-repeat 0 0 / 100% 3px, rgba(252,249,241,.96); pointer-events: none; }
    .cn-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 70px; }
    .cn-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--cn-ink); text-decoration: none; }
    .cn-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .cn-mark { display: block; flex: none; width: 40px; height: 40px; border-radius: 4px; overflow: hidden; background: var(--cn-glaze); box-shadow: inset 0 0 0 1px var(--cn-line), 0 2px 6px -3px rgba(27,36,66,.4); }
    .cn-mark svg { display: block; }
    .cn-brand__name { min-width: 0; font: 800 22px/1.2 var(--font_heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .cn-nav { display: flex; flex: none; align-items: center; gap: 26px; }
    .cn-nav a { position: relative; padding: 10px 0; color: var(--cn-ink); font: 700 16px/1.2 var(--font_body); text-decoration: none; white-space: nowrap; }
    .cn-nav a::after { content: ""; position: absolute; left: 50%; bottom: 0; width: 7px; height: 7px; margin-left: -3.5px; background: var(--cn-tq); transform: rotate(45deg) scale(0); transition: transform .15s; }
    .cn-nav a:hover::after, .cn-nav a[aria-current="page"]::after { transform: rotate(45deg) scale(1); }
    .cn-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .cn-cta-short { display: none; }
    .cn-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1.5px solid var(--cn-blue); border-radius: 6px; background: transparent; color: var(--cn-blue); cursor: pointer; }

    /* Şemse: krem çini duvarda madalyon */
    .cn-hero { position: relative; padding: 76px 0 72px; background-color: var(--cn-cream); background-image: radial-gradient(60% 70% at 50% 45%, rgba(246,241,228,0) 40%, rgba(246,241,228,.55) 100%), var(--cn-wall); background-size: auto, 150px 150px; background-position: center, calc(50% - 75px) 0; }
    .cn-hero::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 13px; background: linear-gradient(var(--cn-blue), var(--cn-blue)) no-repeat 0 100% / 100% 3px, var(--cn-strip) repeat-x 0 0 / 16px 10px, var(--cn-cream); }
    .cn-semse { position: relative; max-width: 940px; margin: 0 auto; padding: 92px 15% 82px; border-radius: 50%; background: radial-gradient(90% 70% at 32% 18%, #fff 0, var(--cn-glaze) 50%, #fbf5e7 100%); box-shadow: 0 34px 60px -34px rgba(27,36,66,.55); text-align: center; }
    .cn-semse__band { position: absolute; inset: 0; width: 100%; height: 100%; overflow: visible; pointer-events: none; }
    .cn-semse__band rect { fill: none; rx: 50%; ry: 50%; }
    /* Sır bandı: lacivert kenarlı turkuaz bant, içinde krem karolar ve her karoda mercan bir nokta. */
    .cn-b1 { stroke: var(--cn-blue); stroke-width: 32px; }
    .cn-b2 { stroke: var(--cn-tq); stroke-width: 24px; }
    .cn-b3 { stroke: var(--cn-glaze); stroke-width: 13px; stroke-dasharray: 13px 7px; }
    .cn-b4 { stroke: var(--cn-coral); stroke-width: 4px; stroke-linecap: round; stroke-dasharray: 0 20px; stroke-dashoffset: -6.5px; }
    .cn-salbek { position: absolute; top: calc(50% - 26px); width: 38px; height: 52px; --cn-a: var(--cn-blue); --cn-c: var(--cn-coral); --cn-l: var(--cn-tq); }
    .cn-salbek--l { left: -66px; transform: rotate(-90deg); }
    .cn-salbek--r { right: -66px; transform: rotate(90deg); }
    .cn-semse__in { position: relative; }
    .cn-eyebrow { display: flex; align-items: center; justify-content: center; margin: 0 0 12px; color: var(--cn-tq-ink); font: italic 600 19px/1.3 var(--font_heading); }
    .cn-h1 { max-width: 17ch; margin: 0 auto 14px; color: var(--cn-ink); font-family: var(--font_heading); font-weight: 800; font-size: clamp(34px, 4.6vw, 58px); line-height: 1.07; text-wrap: balance; overflow-wrap: break-word; }
    .cn-lede { max-width: 50ch; margin: 0 auto; color: var(--cn-muted); font-size: 18px; line-height: 1.55; }

    /* Kartuş arama çubuğu */
    .cn-search-wrap { max-width: 640px; margin: 26px auto 0; filter: drop-shadow(0 10px 16px rgba(27,36,66,.2)); }
    .cn-search { display: grid; grid-template-columns: minmax(0,1.35fr) minmax(0,1fr) auto; padding: 2px; background: var(--cn-blue); clip-path: polygon(22px 0, calc(100% - 22px) 0, 100% 50%, calc(100% - 22px) 100%, 22px 100%, 0 50%); text-align: left; }
    .cn-field { display: flex; flex-direction: column; justify-content: center; min-width: 0; min-height: 62px; padding: 6px 14px; background: var(--cn-glaze); }
    .cn-field--q { padding-left: 32px; clip-path: polygon(21px 0, 100% 0, 100% 100%, 21px 100%, 0 50%); }
    .cn-field--city { border-left: 1px solid var(--cn-line); }
    .cn-field:focus-within { box-shadow: inset 0 -3px 0 var(--cn-tq); }
    .cn-field label { color: var(--cn-muted); font: 700 12.5px/1.2 var(--font_body); }
    .cn-field :where(input,select) { width: 100%; min-width: 0; height: 32px; padding: 0; border: 0; background: transparent; color: var(--cn-ink); font: 600 17px/1.2 var(--font_body); }
    .cn-field :where(input,select):focus { outline: none; }
    .cn-field input::placeholder { color: #6d7590; }
    .cn-search__btn { min-width: 112px; padding: 0 40px 0 26px; border: 0; background: var(--cn-blue); color: #fff; font: 800 19px/1 var(--font_body); cursor: pointer; }
    .cn-search__btn:hover { background: var(--cn-blue-2); }
    .cn-search__btn:focus-visible { outline: 3px solid #9fdfe1; outline-offset: -6px; }
    .cn-often { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 2px 10px; margin: 18px 0 0; color: var(--cn-muted); font-size: 15px; }
    .cn-often__k { font-weight: 700; }
    .cn-often a { display: inline-flex; align-items: center; min-height: 40px; color: var(--cn-blue); font: italic 600 18px/1.2 var(--font_heading); text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .cn-kitabe { display: flex; align-items: center; justify-content: center; width: max-content; max-width: 100%; margin: 40px auto 0; padding: 10px 40px; background: var(--cn-blue); color: #fff; clip-path: var(--cn-kartus); font: italic 600 20px/1.3 var(--font_heading); }
    .cn-kitabe .cn-star::before, .cn-kitabe .cn-star::after { background: #9fdfe1; }

    /* Bölümler ve kartuş başlıklar */
    .cn-section { padding: 76px 0; }
    .cn-section--tint { background: var(--cn-cream-2); }
    .cn-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 12px 24px; margin-bottom: 30px; }
    .cn-head p { max-width: 60ch; margin: 12px 0 0; color: var(--cn-muted); font-size: 17px; }
    .cn-head--center { justify-content: center; text-align: center; }
    .cn-head--center p { margin-inline: auto; }
    .cn-kartus { position: relative; isolation: isolate; display: inline-block; max-width: 100%; margin: 0; padding: 10px 40px 12px; background: var(--cn-blue); clip-path: var(--cn-kartus); color: var(--cn-blue); font: 700 clamp(25px, 3vw, 31px)/1.15 var(--font_heading); }
    .cn-kartus::before { content: ""; position: absolute; inset: 2.5px; z-index: -1; background: var(--cn-glaze); clip-path: polygon(17px 0, calc(100% - 17px) 0, 100% 50%, calc(100% - 17px) 100%, 17px 100%, 0 50%); }
    .cn-kartus--light { background: #9fdfe1; color: #fff; }
    .cn-kartus--light::before { background: var(--cn-blue); }

    /* İşletme kartları */
    .cn-kartlar { display: grid; grid-template-columns: minmax(0,1fr); gap: 24px; margin: 0; padding: 0; list-style: none; }
    .cn-kartlar > li { min-width: 0; }
    .cn-kart { position: relative; display: flex; flex-direction: column; height: 100%; background: var(--cn-glaze); border: 1px solid var(--cn-line); border-radius: 6px; box-shadow: var(--cn-plate); overflow: hidden; transition: transform .18s, box-shadow .18s; }
    .cn-kart:hover, .cn-kart:focus-within { transform: translateY(-2px); box-shadow: inset 0 1px 0 #fff, 0 1px 0 var(--cn-line), 0 22px 36px -22px rgba(27,36,66,.5); }
    .cn-kart__karo { position: relative; display: flex; align-items: center; justify-content: center; height: 104px; overflow: hidden; background-color: var(--cn-glaze); background-size: 64px 64px; background-position: center; border-bottom: 3px solid var(--cn-tq); }
    .cn-kart__karo::after { content: ""; position: absolute; inset: 0; background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,.6) 50%, transparent 60%); transform: translateX(-100%); transition: transform .6s; }
    .cn-kart:hover .cn-kart__karo::after { transform: translateX(100%); }
    .cn-sir--1 { background-image: {!! $cnSir[1] !!}; }
    .cn-sir--2 { background-image: {!! $cnSir[2] !!}; }
    .cn-sir--3 { background-image: {!! $cnSir[3] !!}; }
    .cn-sir--4 { background-image: {!! $cnSir[4] !!}; }
    .cn-kart__cover { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .cn-kart__logo { position: relative; display: grid; place-items: center; height: 68px; padding: 6px 14px; background: #fff; border-radius: 6px; box-shadow: 0 0 0 3px var(--cn-tq), 0 6px 14px -8px rgba(27,36,66,.5); }
    .cn-kart__logo img { display: block; width: auto; max-width: 140px; height: 56px; object-fit: contain; }
    .cn-kart__seal { position: absolute; top: 10px; right: 10px; z-index: 2; padding: 6px 18px 7px; background: var(--cn-blue); color: #fff; clip-path: polygon(10px 0, calc(100% - 10px) 0, 100% 50%, calc(100% - 10px) 100%, 10px 100%, 0 50%); font: 800 12.5px/1 var(--font_body); }
    .cn-kart__cat { position: relative; z-index: 1; align-self: flex-start; max-width: calc(100% - 32px); margin: -17px 0 0 16px; padding: 1.5px; background: var(--cn-tq); clip-path: polygon(12px 0, calc(100% - 12px) 0, 100% 50%, calc(100% - 12px) 100%, 12px 100%, 0 50%); }
    .cn-kart__cat span { display: block; padding: 5px 22px 6px; overflow: hidden; background: var(--cn-glaze); clip-path: polygon(11px 0, calc(100% - 11px) 0, 100% 50%, calc(100% - 11px) 100%, 11px 100%, 0 50%); color: var(--cn-tq-ink); font: italic 600 16px/1.2 var(--font_heading); white-space: nowrap; text-overflow: ellipsis; }
    .cn-kart__body { display: flex; flex: 1; flex-direction: column; gap: 10px; padding: 12px 20px 20px; }
    .cn-kart__name { margin: 0; font: 700 23px/1.2 var(--font_heading); overflow-wrap: anywhere; }
    .cn-kart__name a { color: var(--cn-ink); text-decoration: none; }
    .cn-kart__name a:hover { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .cn-kart__desc { display: -webkit-box; margin: 0; overflow: hidden; color: var(--cn-muted); font-size: 15.5px; line-height: 1.5; -webkit-box-orient: vertical; -webkit-line-clamp: 2; }
    .cn-kart__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; margin: 0; color: var(--cn-muted); font-size: 14.5px; }
    .cn-chip { display: inline-flex; align-items: center; min-height: 24px; padding: 0 8px; border-radius: 3px; background: #e9edf3; color: #3e475f; font: 800 12.5px/1 var(--font_body); box-shadow: inset 0 1px 0 rgba(255,255,255,.6); }
    .cn-chip--open { background: var(--cn-green); color: #fff; }
    .cn-chip--ok { background: var(--cn-tq-soft); color: var(--cn-tq-ink); }
    .cn-kart__rating span { color: #b07a12; }
    .cn-kart__place { display: inline-flex; align-items: center; gap: 7px; }
    .cn-kart__place::before { content: ""; width: 6px; height: 6px; background: var(--cn-tq); transform: rotate(45deg); }
    .cn-kart__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 6px; }
    .cn-call { display: inline-flex; flex: 1; align-items: center; justify-content: center; gap: 9px; min-width: 0; min-height: 50px; padding: 0 14px; border-radius: 6px; background: var(--cn-blue); color: #fff; font: 800 18px/1 var(--font_body); font-variant-numeric: tabular-nums; text-decoration: none; white-space: nowrap; box-shadow: inset 0 1px 0 rgba(255,255,255,.2); }
    .cn-call:hover { background: var(--cn-blue-2); }
    .cn-call span { overflow: hidden; text-overflow: ellipsis; }
    .cn-call--wa { background: var(--cn-wa); }
    .cn-call--line { background: transparent; color: var(--cn-blue); box-shadow: inset 0 0 0 2px var(--cn-blue); }
    .cn-call--line:hover { color: #fff; }
    .cn-wa, .cn-more { display: inline-grid; flex: none; place-items: center; width: 50px; height: 50px; border-radius: 6px; text-decoration: none; }
    .cn-wa { background: var(--cn-wa); color: #fff; box-shadow: inset 0 1px 0 rgba(255,255,255,.2); }
    .cn-wa:hover { background: #17703d; }
    .cn-more { box-shadow: inset 0 0 0 2px var(--cn-tq); color: var(--cn-blue); font: 800 26px/1 var(--font_body); }
    .cn-more:hover { background: var(--cn-tq-soft); }
    /* Dar kartta numara kesilmesin: WhatsApp varken "›" düğmesi düşer (firma adı zaten profile gider). */
    .cn-kart { container-type: inline-size; }
    @container (max-width: 340px) {
        .cn-wa ~ .cn-more { display: none; }
        .cn-call { gap: 7px; padding: 0 10px; font-size: 17px; }
    }

    /* Eksik karo kartı ve boş durum */
    .cn-kartlar__cta { container-type: inline-size; }
    .cn-eksik { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 16px; height: 100%; padding: 28px 24px; border: 2px dashed var(--cn-grout); border-radius: 6px; background: linear-gradient(rgba(246,241,228,.82), rgba(246,241,228,.82)), var(--cn-wall) center / 150px 150px; text-align: center; }
    .cn-eksik h3 { margin: 0 0 6px; color: var(--cn-ink); font: 700 23px/1.2 var(--font_heading); }
    .cn-eksik p { max-width: 42ch; margin: 0 auto; color: var(--cn-muted); font-size: 15.5px; }
    .cn-eksik__acts { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 6px 18px; margin-top: 14px; }
    @container (min-width: 560px) {
        .cn-eksik { flex-direction: row; gap: 28px; padding: 26px 34px; text-align: left; }
        .cn-eksik p { margin: 0; }
        .cn-eksik__acts { justify-content: flex-start; }
    }
    .cn-socket { display: grid; flex: none; place-items: center; width: 92px; height: 92px; border: 2px dashed var(--cn-coral); border-radius: 4px; background: var(--cn-glaze); color: var(--cn-coral); font: 800 36px/1 var(--font_body); }
    .cn-empty { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 24px 32px; padding: 40px 28px; border: 2px dashed var(--cn-grout); border-radius: 8px; background: var(--cn-glaze); }
    .cn-empty h3 { margin: 0 0 6px; font: 700 26px/1.2 var(--font_heading); }
    .cn-empty p { max-width: 46ch; margin: 0 0 16px; color: var(--cn-muted); }
    .cn-empty-line { margin: 0; padding: 22px 24px; border: 2px dashed var(--cn-grout); border-radius: 8px; background: var(--cn-glaze); color: var(--cn-muted); text-align: center; }

    /* Hizmet karoları: derzle ayrılmış karolar; köşe ve kenar motifleri komşu karoyla birleşir. */
    .cn-panel { padding: 12px; background: var(--cn-frame) 0 0 / 22px 22px; border-radius: 4px; box-shadow: 0 24px 44px -30px rgba(27,36,66,.6); }
    .cn-karolar { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 3px; margin: 0; padding: 0; background: var(--cn-grout); box-shadow: 0 0 0 3px var(--cn-tq); list-style: none; }
    .cn-karolar > li { min-width: 0; }
    .cn-dolgu { display: none; }
    .cn-karo { --cn-r: 30px; --cn-t: 16px; --cn-a: var(--cn-blue); --cn-b: var(--cn-tq); --cn-c: var(--cn-coral); --cn-l: var(--cn-tq); position: relative; display: flex; align-items: center; justify-content: center; aspect-ratio: 1 / 1; overflow: hidden; background: radial-gradient(110% 80% at 25% 10%, #fff 0, var(--cn-glaze) 45%, #fbf6ea 100%); color: var(--cn-ink); text-decoration: none; }
    .cn-karo::after { content: ""; position: absolute; inset: 0; background: linear-gradient(115deg, transparent 40%, rgba(255,255,255,.75) 50%, transparent 60%); transform: translateX(-100%); transition: transform .6s; pointer-events: none; }
    a.cn-karo:hover::after, a.cn-karo:focus-visible::after { transform: translateX(100%); }
    a.cn-karo:hover .cn-karo__name { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    a.cn-karo:focus-visible { outline: 3px solid var(--cn-coral); outline-offset: -8px; }
    .cn-motif { position: absolute; inset: 0; pointer-events: none; }
    .cn-motif svg { position: absolute; display: block; overflow: visible; }
    .cn-motif__r { width: calc(2 * var(--cn-r)); height: calc(2 * var(--cn-r)); }
    .cn-motif__r--tl { left: calc(-1 * var(--cn-r) - 1.5px); top: calc(-1 * var(--cn-r) - 1.5px); }
    .cn-motif__r--tr { right: calc(-1 * var(--cn-r) - 1.5px); top: calc(-1 * var(--cn-r) - 1.5px); }
    .cn-motif__r--bl { left: calc(-1 * var(--cn-r) - 1.5px); bottom: calc(-1 * var(--cn-r) - 1.5px); }
    .cn-motif__r--br { right: calc(-1 * var(--cn-r) - 1.5px); bottom: calc(-1 * var(--cn-r) - 1.5px); }
    .cn-motif__t { width: var(--cn-t); height: calc(var(--cn-t) * 1.4); }
    .cn-motif__t--t { top: -1.5px; left: calc(50% - var(--cn-t) / 2); transform: rotate(180deg); }
    .cn-motif__t--b { bottom: -1.5px; left: calc(50% - var(--cn-t) / 2); }
    .cn-motif__t--l { top: calc(50% - var(--cn-t) * .7); left: calc(var(--cn-t) * .2 - 1.5px); transform: rotate(90deg); }
    .cn-motif__t--r { top: calc(50% - var(--cn-t) * .7); right: calc(var(--cn-t) * .2 - 1.5px); transform: rotate(-90deg); }
    .cn-karo__txt { position: relative; z-index: 1; display: flex; flex-direction: column; align-items: center; gap: 4px; max-width: 100%; padding: 0 calc(var(--cn-r) * .9); text-align: center; }
    .cn-karo__name { font: 700 18px/1.2 var(--font_heading); overflow-wrap: anywhere; text-wrap: balance; }
    .cn-karo__n { color: var(--cn-tq-ink); font: 800 13px/1.2 var(--font_body); }
    .cn-karo__center { position: relative; width: 30px; height: 42px; --cn-l: var(--cn-tq); }
    .cn-after { margin: 18px 0 0; text-align: center; }

    /* Şehir kitabesi */
    .cn-band { position: relative; padding: 70px 0 66px; background: var(--cn-wall-w) calc(50% - 75px) 0 / 150px 150px, var(--cn-blue); color: #fff; text-align: center; }
    .cn-band::before, .cn-band::after { content: ""; position: absolute; left: 0; right: 0; height: 12px; background: var(--cn-strip) repeat-x 0 1px / 16px 10px, var(--cn-cream); }
    .cn-band::before { top: 0; }
    .cn-band::after { bottom: 0; }
    .cn-cities { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 0; max-width: 1000px; margin: 26px auto 0; padding: 0; list-style: none; }
    .cn-cities li { display: inline-flex; align-items: center; }
    .cn-city { display: inline-flex; align-items: baseline; min-height: 44px; color: #fff; font: 600 24px/1.6 var(--font_heading); text-decoration: none; }
    .cn-city--big { font-size: 34px; font-style: italic; }
    .cn-city small { margin-left: 6px; color: #9fdfe1; font: 800 13px/1 var(--font_body); font-style: normal; white-space: nowrap; }
    .cn-city:hover { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 6px; }
    .cn-band :focus-visible { outline-color: #9fdfe1; }
    .cn-band__empty { margin: 22px 0 0; font: italic 600 20px/1.5 var(--font_heading); }
    .cn-band__empty a { color: #9fdfe1; }

    /* Kemer nişleri */
    .cn-niches { display: grid; grid-template-columns: minmax(0,1fr); gap: 24px; max-width: 1060px; margin: 0 auto; padding: 0; list-style: none; }
    .cn-niche { --cn-l: var(--cn-tq); --cn-a: var(--cn-blue); --cn-c: var(--cn-coral); --cn-b: var(--cn-tq); padding: 58px 28px 30px; border-radius: 50% 50% 8px 8px / 32% 32% 8px 8px; background: radial-gradient(90% 60% at 50% 0, #fff 0, var(--cn-glaze) 60%); box-shadow: inset 0 0 0 7px var(--cn-glaze), inset 0 0 0 9px var(--cn-tq), var(--cn-plate); text-align: center; }
    .cn-niche__ico { display: block; width: auto; height: 62px; margin: 0 auto 12px; }
    .cn-niche--3 .cn-niche__ico { height: 34px; margin-top: 16px; margin-bottom: 24px; --cn-l: var(--cn-green); }
    .cn-niche h3 { margin: 0 0 8px; color: var(--cn-ink); font: 700 23px/1.2 var(--font_heading); }
    .cn-niche p { max-width: 34ch; margin: 0 auto; color: var(--cn-muted); font-size: 15.5px; }

    /* Okuma köşesi */
    .cn-posts { display: grid; grid-template-columns: minmax(0,1fr); gap: 24px; margin: 0; padding: 0; list-style: none; }
    .cn-post { display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 0 22px 14px; overflow: hidden; background: var(--cn-glaze); border: 1px solid var(--cn-line); border-radius: 6px; box-shadow: var(--cn-plate); }
    .cn-post__band { display: grid; place-items: center; height: 84px; margin: 0 -22px 8px; background: var(--cn-wall-w) center / 66px 66px, var(--cn-blue); border-bottom: 3px solid var(--cn-tq); --cn-a: #fff; --cn-c: #fff; --cn-l: rgba(255,255,255,.75); }
    .cn-post--2 .cn-post__band { background-color: var(--cn-tq); border-bottom-color: var(--cn-blue); }
    .cn-post--3 .cn-post__band { background-color: var(--cn-green); }
    .cn-post__band svg { width: 34px; height: 48px; }
    .cn-post time { color: var(--cn-muted); font-size: 13.5px; font-weight: 700; }
    .cn-post h3 { margin: 0; font: 700 22px/1.25 var(--font_heading); }
    .cn-post h3 a { color: var(--cn-ink); text-decoration: none; }
    .cn-post h3 a:hover { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .cn-post p { margin: 0; color: var(--cn-muted); font-size: 15.5px; }
    .cn-post .cn-link { margin-top: auto; }

    /* İş ilanları */
    .cn-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; margin: 0; padding: 0; list-style: none; }
    .cn-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border-left: 5px solid var(--cn-tq); border-radius: 4px; background: var(--cn-glaze); box-shadow: var(--cn-plate); color: var(--cn-ink); text-decoration: none; }
    .cn-job:hover .cn-job__title { text-decoration: underline; }
    .cn-job__type { color: var(--cn-tq-ink); font: italic 600 15px/1.3 var(--font_heading); }
    .cn-job__title { font: 700 20px/1.25 var(--font_heading); }
    .cn-job__meta { color: var(--cn-muted); font-size: 14.5px; }

    /* Eksik karo bölümü */
    .cn-cta-sec { padding-top: 24px; }
    .cn-cta { display: grid; grid-template-columns: minmax(0,1fr); gap: 32px; align-items: center; padding: 28px 20px; border: 1px solid var(--cn-line); border-radius: 8px; background: var(--cn-glaze); box-shadow: var(--cn-plate); }
    .cn-mosaic { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 3px; width: 100%; max-width: 330px; margin: 12px auto; background: var(--cn-grout); box-shadow: 0 0 0 3px var(--cn-tq), 0 0 0 12px var(--cn-blue); }
    .cn-karo.cn-karo--mini { aspect-ratio: 1 / 1; --cn-r: 26px; --cn-t: 14px; }
    .cn-mosaic__gap { display: grid; place-items: center; aspect-ratio: 1 / 1; background: var(--cn-cream); outline: 2px dashed var(--cn-coral); outline-offset: -7px; color: var(--cn-coral); font: 800 34px/1 var(--font_body); }
    .cn-mosaic__gap span { animation: cn-pulse 2.4s ease-in-out infinite; }
    @keyframes cn-pulse { 50% { opacity: .45; transform: scale(.9); } }
    .cn-cta__txt h2 { margin: 0 0 10px; color: var(--cn-ink); font: 800 clamp(30px, 3.4vw, 40px)/1.12 var(--font_heading); }
    .cn-cta__txt > p { max-width: 52ch; margin: 0 0 20px; color: var(--cn-muted); font-size: 17px; }
    .cn-cta__form label { display: block; margin-bottom: 6px; color: var(--cn-ink); font: 700 14px/1.2 var(--font_body); }
    .cn-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .cn-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 16px; border: 1.5px solid var(--cn-grout); border-radius: 6px; background: #fff; color: var(--cn-ink); font: 600 17px/1.2 var(--font_body); }
    .cn-cta__row input:focus { outline: 3px solid var(--cn-tq); outline-offset: 0; border-color: var(--cn-tq); }
    .cn-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin: 12px 0 0; }

    /* Ortak alt bilgi */
    .theme-cini-pano body > footer { position: relative; background: #13285c !important; color: #d6dff0 !important; }
    .theme-cini-pano body > footer::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 12px; background: var(--cn-strip) repeat-x 0 1px / 16px 10px, var(--cn-cream); }
    .theme-cini-pano body > footer :where(p,ul,button) { color: #c3cee5 !important; }
    .theme-cini-pano body > footer :where(h3,span) { color: #f3efe4 !important; }
    .theme-cini-pano body > footer h3 { text-transform: none !important; letter-spacing: 0 !important; font-family: var(--font_heading); font-size: 17px; }
    .theme-cini-pano body > footer a:hover { color: #9fdfe1 !important; }

    /* İç sayfalar: birincil renkli başlık bantlarına çini deseni ve su yolu şeridi. */
    .theme-cini-pano main section[style*="background:var(--primary)"] { background-image: var(--cn-wall-w) !important; background-size: 150px 150px !important; box-shadow: inset 0 -6px 0 var(--cn-tq); }
    .theme-cini-pano body > footer :focus-visible, .theme-cini-pano main section[style*="background:var(--primary)"] :focus-visible { outline-color: #9fdfe1; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-cini-pano #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-cini-pano #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .cn-karolar { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .cn-karo { aspect-ratio: 4 / 3; --cn-r: 42px; --cn-t: 22px; }
        .cn-karo__name { font-size: 20px; }
        .cn-dolgu--c3 { display: block; }
        .cn-kartlar { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .cn-kartlar > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .cn-cta { padding: 36px; }
    }
    @media (min-width: 768px) {
        .cn-niches { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .cn-posts { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .cn-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (min-width: 960px) {
        .cn-karolar { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .cn-dolgu--c3 { display: none; }
        .cn-dolgu--c4 { display: block; }
        .cn-karo { --cn-r: 52px; --cn-t: 27px; }
        .cn-karo__name { font-size: 22px; }
    }
    @media (min-width: 1024px) {
        .cn-kartlar { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 28px; }
        .cn-kartlar > li:last-child:nth-child(odd) { grid-column: auto; }
        .cn-kartlar > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .cn-kartlar > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .cn-cta { grid-template-columns: minmax(0,5fr) minmax(0,7fr); gap: 56px; padding: 44px 48px; }
        .cn-mosaic { margin: 12px; }
    }
    @media (max-width: 1139.98px) {
        .cn-salbek { display: none; }
    }
    @media (min-width: 1024px) and (max-width: 1179.98px) {
        .cn-nav { gap: 18px; }
    }
    @media (max-width: 1023.98px) {
        .cn-nav { display: none; }
        .cn-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-cini-pano #mobile-menu { display: block; z-index: 60; }
        .theme-cini-pano #mobile-backdrop { z-index: 55; }
        .theme-cini-pano #mobile-backdrop:not(.hidden) { display: block; }
        .cn-semse { padding-inline: 12%; }
    }
    @media (max-width: 639.98px) {
        .cn-wrap { padding-inline: 16px; }
        .cn-header__row { height: 62px; gap: 10px; }
        .cn-brand { gap: 10px; }
        .cn-brand__name { max-width: 44vw; font-size: 19px; }
        .cn-mark, .cn-mark svg { width: 34px; height: 34px; }
        .cn-btn--sm { min-height: 44px; padding: 0 14px; }
        .cn-dolgu--c2 { display: block; }
        .cn-hero { padding: 40px 0 44px; background-size: auto, 120px 120px; background-position: center, calc(50% - 60px) 0; }
        /* Telefonda dikey şemse: üstü ve altı kubbe, yanları neredeyse düz. */
        .cn-semse { margin-inline: 10px; padding: 62px 18px 48px; border-radius: 50% / 11%; }
        .cn-semse__band rect { ry: 11%; }
        .cn-b1 { stroke-width: 22px; }
        .cn-b2 { stroke-width: 16px; }
        .cn-b3 { stroke-width: 9px; stroke-dasharray: 9px 5px; }
        .cn-b4 { stroke-width: 3.5px; stroke-dasharray: 0 14px; stroke-dashoffset: -4.5px; }
        .cn-eyebrow { font-size: 17px; }
        .cn-star { margin: 0 9px; }
        .cn-h1 { font-size: clamp(30px, 8.6vw, 38px); }
        .cn-lede { font-size: 16.5px; }
        .cn-search-wrap { margin-top: 22px; filter: drop-shadow(0 8px 12px rgba(27,36,66,.16)); }
        .cn-search { grid-template-columns: minmax(0,1fr); gap: 2px; clip-path: none; border-radius: 10px; }
        .cn-field, .cn-field--q { min-height: 60px; padding: 6px 14px; clip-path: none; }
        .cn-field--q { border-radius: 8px 8px 0 0; }
        .cn-field--city { border-left: 0; }
        .cn-search__btn { min-height: 52px; padding: 0 20px; border-radius: 0 0 8px 8px; }
        .cn-often { gap: 0 8px; }
        .cn-kitabe { padding: 10px 32px; font-size: 18px; }
        .cn-section { padding: 52px 0; }
        .cn-head { margin-bottom: 24px; }
        .cn-kartus { padding: 9px 30px 10px; }
        .cn-panel { padding: 8px; }
        .cn-karo { aspect-ratio: 5 / 4; }
        .cn-karo__txt { padding: 0 14px; }
        .cn-karo__name { font-size: 17px; }
        .cn-karo__n { font-size: 12.5px; }
        .cn-kart__karo { height: 96px; }
        .cn-band { padding: 56px 0 52px; }
        .cn-city { font-size: 20px; }
        .cn-city--big { font-size: 25px; }
        .cn-star { margin: 0 8px; }
        .cn-niche { border-radius: 50% 50% 8px 8px / 22% 22% 8px 8px; }
        .cn-cta__row .cn-btn { width: 100%; }
        .cn-cta-sec { padding-top: 8px; }
    }
    @media (max-width: 419.98px) {
        .cn-cta-long { display: none; }
        .cn-cta-short { display: inline; }
        .cn-call { font-size: 17px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-cini-pano { scroll-behavior: auto; }
        .theme-cini-pano #mobile-menu { transition: none; }
        .cn-kart, .cn-kart__karo::after, .cn-karo::after, .cn-nav a::after, .cn-btn { transition: none !important; }
        .cn-kart:hover, .cn-kart:focus-within { transform: none; }
        .cn-mosaic__gap span { animation: none; }
    }
</style>
