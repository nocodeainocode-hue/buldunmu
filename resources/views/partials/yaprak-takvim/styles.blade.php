<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400&family=Source+Serif+4:ital,wght@1,400;1,600&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Oswald:wght@400;500;600;700&family=Source+Sans+3:ital,wght@0,400;0,600;0,700;1,400&family=Source+Serif+4:ital,wght@1,400;1,600&display=swap"></noscript>
<style>
    /* Yaprak Takvim · Günün Firması: duvar takvimi. Gazete kâğıdı beyazı, takvim kırmızısı, mürekkep siyahı, ince yeşil çizgi. */
    .theme-yaprak-takvim {
        --yt-wall: #efe9dc; --yt-wall-2: #e7e0d0; --yt-paper: #fffdf6; --yt-news: #f7f3ea; --yt-old: #f5efdf; --yt-back: #f2ede2;
        --yt-ink: #1b1a17; --yt-muted: #59534a; --yt-line: #ddd4c2; --yt-line-2: #c6bba5;
        --yt-red: #c8102e; --yt-red-2: #a00d24; --yt-red-soft: #fbeaea;
        --yt-green: #2f6b4f; --yt-green-soft: #e6efe9; --yt-wa: #1b7f45;
        --yt-num: 'Oswald', 'Arial Narrow', sans-serif; --yt-serif: 'Source Serif 4', Georgia, serif;
        scroll-padding-top: 84px;
    }
    .theme-yaprak-takvim body { background: var(--yt-wall); color: var(--yt-ink); font-family: var(--font_body); font-size: 16px; line-height: 1.6; }
    .theme-yaprak-takvim :where(h1,h2,h3) { font-family: var(--font_heading); font-weight: 600; }
    .theme-yaprak-takvim :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--yt-ink); outline-offset: 3px; }
    .theme-yaprak-takvim #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .yt-wrap { width: 100%; max-width: var(--page_width, 1240px); margin-inline: auto; padding-inline: 32px; }
    .yt-home { overflow-x: clip; }
    .yt-home ul, .yt-home ol { margin: 0; padding: 0; list-style: none; }
    .yt-home p { margin: 0; }

    /* Düğmeler ve bağlantılar */
    .yt-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 20px; border: 1px solid transparent; border-radius: 3px; font: 500 16px/1.1 var(--yt-num); letter-spacing: .06em; text-transform: uppercase; text-align: center; text-decoration: none; cursor: pointer; transition: background-color .15s, color .15s, border-color .15s; }
    .yt-btn--sm { min-height: 42px; padding: 0 16px; font-size: 15px; white-space: nowrap; }
    .yt-btn--lg { min-height: 54px; padding: 0 26px; font-size: 18px; }
    .yt-btn--red { background: var(--yt-red); color: #fff; }
    .yt-btn--red:hover { background: var(--yt-red-2); }
    .yt-btn--line { background: var(--yt-paper); border-color: var(--yt-line-2); color: var(--yt-ink); }
    .yt-btn--line:hover { border-color: var(--yt-ink); }
    .yt-link { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; color: var(--yt-red-2); font-weight: 700; text-decoration: none; }
    .yt-link svg { transition: transform .15s; }
    .yt-link:hover { text-decoration: underline; text-underline-offset: 4px; }
    .yt-link:hover svg { transform: translateX(3px); }

    /* Üst şerit: gazete kâğıdı, kırmızı üst çizgi, ince yeşil alt çizgi */
    .yt-header { position: sticky; top: 0; z-index: 50; background: rgba(247,243,234,.96); backdrop-filter: blur(8px); border-top: 4px solid var(--yt-red); border-bottom: 1px solid var(--yt-line-2); box-shadow: 0 2px 0 -1px var(--yt-green); }
    .yt-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 68px; }
    .yt-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--yt-ink); text-decoration: none; }
    .yt-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .yt-brand__mark { position: relative; display: grid; flex: none; width: 38px; height: 42px; padding-top: 12px; border: 1px solid var(--yt-line-2); border-radius: 3px; background: linear-gradient(var(--yt-red) 0 11px, var(--yt-paper) 11px); box-shadow: 0 2px 0 -1px #e9e1cf, 0 3px 0 -1px var(--yt-line-2); }
    .yt-brand__ring { position: absolute; top: 4px; left: 9px; right: 9px; height: 4px; background: radial-gradient(circle, #fff 1.6px, transparent 2px) 0 0/10px 4px repeat-x; }
    .yt-brand__day { display: grid; place-items: center; color: var(--yt-red); font: 700 19px/1 var(--yt-num); }
    .yt-brand__name { min-width: 0; font: 600 22px/1.15 var(--yt-num); letter-spacing: .01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .yt-nav { display: flex; flex: none; align-items: center; gap: 26px; }
    .yt-nav a { position: relative; padding: 10px 0; color: var(--yt-ink); font: 500 15px/1.2 var(--yt-num); letter-spacing: .08em; text-transform: uppercase; text-decoration: none; white-space: nowrap; }
    .yt-nav a::after { content: ""; position: absolute; left: 0; right: 0; bottom: 3px; height: 2px; background: var(--yt-red); transform: scaleX(0); transform-origin: left; transition: transform .16s; }
    .yt-nav a:hover::after, .yt-nav a[aria-current="page"]::after { transform: scaleX(1); }
    .yt-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .yt-cta-short { display: none; }
    .yt-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1px solid var(--yt-line-2); border-radius: 3px; background: var(--yt-paper); color: var(--yt-ink); cursor: pointer; }

    /* Ortak etiketler */
    .yt-kicker { display: flex; align-items: center; gap: 12px; margin: 0 0 10px !important; color: var(--yt-red-2); font: 500 14px/1.3 var(--yt-num); letter-spacing: .2em; text-transform: uppercase; }
    .yt-kicker::before { content: ""; flex: none; width: 28px; height: 2px; background: var(--yt-red); }
    .yt-chip { display: inline-flex; align-items: center; padding: 3px 9px; border: 1px solid var(--yt-line-2); border-radius: 2px; font: 600 12.5px/1.3 var(--font_body); letter-spacing: .02em; }
    .yt-chip--red { border-color: var(--yt-red); color: var(--yt-red-2); }
    .yt-chip--ok { border-color: #a9c8b6; color: var(--yt-green); }
    .yt-chip--open { border-color: #a9c8b6; background: var(--yt-green-soft); color: var(--yt-green); }

    /* Duvar */
    .yt-hero { position: relative; padding: 44px 0 72px; background: repeating-linear-gradient(90deg, rgba(27,26,23,.022) 0 1px, transparent 1px 26px), radial-gradient(80% 70% at 30% 20%, #f5f0e5, transparent 70%), var(--yt-wall); border-bottom: 1px solid var(--yt-line-2); }
    .yt-hero__head { max-width: 860px; }
    .yt-h1 { margin: 0 0 12px; font: 600 clamp(34px, 4.2vw, 54px)/1.08 var(--yt-num); letter-spacing: -.005em; color: var(--yt-ink); text-wrap: balance; overflow-wrap: break-word; }
    .yt-lede { max-width: 60ch; color: var(--yt-muted); font-size: 18px; }
    .yt-hero__grid { display: grid; grid-template-columns: minmax(0,1fr); gap: 40px; margin-top: 34px; }

    /* Takvim bloğu: karton arka, koparılmış yaprak artığı, bugünün yaprağı */
    .yt-pad { position: relative; filter: drop-shadow(0 22px 22px rgba(70,52,22,.16)) drop-shadow(0 2px 2px rgba(70,52,22,.12)); }
    .yt-pad__board { position: relative; display: flex; align-items: center; justify-content: space-between; gap: 16px; height: 60px; padding: 0 24px; border-radius: 6px 6px 0 0; background: linear-gradient(180deg, #d01734, var(--yt-red) 60%, #b60e29); color: #fff; font: 600 15px/1 var(--yt-num); letter-spacing: .16em; text-transform: uppercase; box-shadow: inset 0 -4px 0 rgba(0,0,0,.16); }
    .yt-pad__nail { position: absolute; top: 10px; left: 50%; width: 14px; height: 14px; margin-left: -7px; border-radius: 50%; background: radial-gradient(circle at 35% 35%, #8f8a80, #34312c 70%); box-shadow: 0 0 0 3px #a50c25, 0 2px 3px rgba(0,0,0,.35); }
    .yt-pad__brand { min-width: 0; max-width: 40%; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; }
    .yt-pad__year { font-size: 18px; letter-spacing: .12em; }
    .yt-pad__stub { height: 12px; margin: 0 12px; background: #f1ebdc; clip-path: polygon(0 0,100% 0,100% 40%,98% 90%,96% 45%,93% 80%,90% 35%,87% 95%,84% 50%,81% 75%,78% 30%,75% 85%,72% 45%,69% 100%,66% 40%,63% 70%,60% 30%,57% 90%,54% 50%,51% 80%,48% 35%,45% 95%,42% 45%,39% 70%,36% 30%,33% 85%,30% 50%,27% 100%,24% 40%,21% 75%,18% 35%,15% 90%,12% 50%,9% 80%,6% 35%,3% 95%,0 45%); }
    .yt-today { position: relative; margin: 0 12px; padding-top: 12px; background: var(--yt-paper); box-shadow: 0 3px 0 -1px #f3eee2, 0 4px 0 -1px #d8cfbb, 0 7px 0 -3px #efe9db, 0 8px 0 -3px #d2c7b1; }
    .yt-today::before { content: ""; position: absolute; left: 0; right: 0; top: 2px; height: 8px; background: radial-gradient(circle, #cdc2ac 1.7px, transparent 2.1px) 3px 50%/11px 8px repeat-x; }
    .yt-today__top { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; margin: 0 22px; padding: 10px 0 8px; border-bottom: 3px double var(--yt-ink); font: 500 15px/1.2 var(--yt-num); letter-spacing: .14em; text-transform: uppercase; }
    .yt-today__month { color: var(--yt-red); font-size: 26px; font-weight: 600; letter-spacing: .24em; }
    .yt-today__main { display: grid; grid-template-columns: minmax(0,1fr); margin: 0 22px; }
    .yt-today__date { display: flex; flex-direction: column; align-items: center; padding: 14px 0 18px; text-align: center; }
    .yt-today__num { display: block; color: var(--yt-red); font: 700 clamp(132px, 13vw, 184px)/.86 var(--yt-num); letter-spacing: -.03em; font-variant-numeric: lining-nums; }
    .yt-today__wd { margin-top: 10px; font: 600 25px/1.1 var(--yt-num); letter-spacing: .26em; text-transform: uppercase; padding-left: .26em; }
    .yt-today__rows { width: 100%; max-width: 250px; margin: 16px 0 0; border-top: 1px solid var(--yt-ink); }
    .yt-today__rows div { display: flex; justify-content: space-between; gap: 10px; padding: 6px 2px; border-bottom: 1px solid var(--yt-line); font-size: 14.5px; }
    .yt-today__rows dt { color: var(--yt-muted); font-weight: 600; }
    .yt-today__rows dd { margin: 0; font-weight: 700; font-variant-numeric: tabular-nums; }
    .yt-today__firm { display: flex; flex-direction: column; gap: 10px; min-width: 0; padding: 18px 0 22px; border-top: 1px dashed var(--yt-line-2); }
    .yt-today__label { display: flex; align-items: center; gap: 12px; color: var(--yt-green); font: 600 14px/1 var(--yt-num); letter-spacing: .26em; text-transform: uppercase; }
    .yt-today__label::before, .yt-today__label::after { content: ""; flex: 1; height: 1px; background: var(--yt-green); }
    .yt-today__name { margin: 4px 0 0; font: 600 clamp(28px, 2.6vw, 36px)/1.1 var(--yt-num); overflow-wrap: break-word; }
    .yt-today__name a { color: var(--yt-ink); text-decoration: none; }
    .yt-today__name a:hover { color: var(--yt-red-2); text-decoration: underline; text-decoration-thickness: 2px; text-underline-offset: 5px; }
    .yt-today__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 14px; color: var(--yt-muted); font-size: 15.5px; }
    .yt-today__cat { color: var(--yt-red-2); font: 500 15px/1.2 var(--yt-num); letter-spacing: .1em; text-transform: uppercase; }
    .yt-today__place { display: inline-flex; align-items: center; gap: 5px; }
    .yt-today__quote { position: relative; padding-left: 18px; border-left: 3px solid var(--yt-green); color: #3c3830; font: italic 400 18px/1.5 var(--yt-serif); display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .yt-today__chips { display: flex; flex-wrap: wrap; gap: 6px; }
    .yt-today__acts { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 14px; margin-top: 6px; }

    /* Arama düğmeleri */
    .yt-call { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-width: 0; min-height: 48px; padding: 0 16px; border: 1px solid var(--yt-red); border-radius: 3px; background: var(--yt-red); color: #fff; font: 600 18px/1 var(--yt-num); letter-spacing: .03em; font-variant-numeric: tabular-nums; text-decoration: none; transition: background-color .15s; }
    .yt-call span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .yt-call:hover { background: var(--yt-red-2); }
    .yt-call--big { flex: 1 1 230px; min-height: 58px; font-size: 23px; }
    .yt-call--wa { border-color: var(--yt-wa); background: var(--yt-paper); color: var(--yt-wa); font-size: 17px; text-transform: uppercase; letter-spacing: .08em; }
    .yt-call--wa:hover { background: var(--yt-wa); color: #fff; }
    .yt-call--wa.yt-call--big { background: var(--yt-wa); color: #fff; }
    .yt-today__acts .yt-call--wa:not(.yt-call--big) { min-height: 58px; padding: 0 20px; }
    .yt-call--line { border-color: var(--yt-line-2); background: var(--yt-paper); color: var(--yt-ink); }
    .yt-call--line:hover { background: var(--yt-news); }
    .yt-wa { display: grid; flex: none; place-items: center; width: 48px; height: 48px; border: 1px solid #a9c8b6; border-radius: 3px; background: var(--yt-paper); color: var(--yt-wa); }
    .yt-wa:hover { background: var(--yt-wa); border-color: var(--yt-wa); color: #fff; }
    .yt-more { display: grid; flex: none; place-items: center; width: 44px; height: 48px; border: 1px solid var(--yt-line-2); border-radius: 3px; background: var(--yt-paper); color: var(--yt-ink); }
    .yt-more:hover { border-color: var(--yt-ink); }

    /* Yaprağın altındaki arama */
    .yt-today__search { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; margin: 0 22px; padding: 16px 0 22px; border-top: 3px double var(--yt-ink); }
    .yt-field { display: flex; flex-direction: column; gap: 5px; min-width: 0; }
    .yt-field label { color: var(--yt-red-2); font: 500 14px/1.2 var(--yt-num); letter-spacing: .14em; text-transform: uppercase; }
    .yt-field :where(input,select) { width: 100%; min-width: 0; height: 52px; padding: 0 14px; border: 1px solid var(--yt-line-2); border-radius: 3px; background: #fff; color: var(--yt-ink); font: 500 17px/1.2 var(--font_body); }
    .yt-field input::placeholder { color: #8d867a; }
    .yt-field :where(input,select):focus { outline: none; border-color: var(--yt-ink); box-shadow: 0 0 0 3px rgba(27,26,23,.12); }
    .yt-search__btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; align-self: end; min-height: 52px; padding: 0 24px; border: 0; border-radius: 3px; background: var(--yt-ink); color: #fff; font: 600 18px/1 var(--yt-num); letter-spacing: .08em; text-transform: uppercase; cursor: pointer; transition: background-color .15s; }
    .yt-search__btn:hover { background: #000; }

    /* Koparılan yapraklar */
    .yt-torn { display: flex; flex-direction: column; gap: 14px; min-width: 0; }
    .yt-torn__title { display: flex; align-items: center; gap: 12px; color: var(--yt-muted); font: 500 14px/1.2 var(--yt-num); letter-spacing: .22em; text-transform: uppercase; }
    .yt-torn__title::after { content: ""; flex: 1; height: 1px; background: var(--yt-line-2); }
    .yt-torn__list { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .yt-torn__item { display: flex; filter: drop-shadow(0 12px 12px rgba(70,52,22,.14)) drop-shadow(0 1px 1px rgba(70,52,22,.18)); transition: transform .2s; }
    .yt-torn__item--1 { transform: rotate(-2deg); }
    .yt-torn__item--2 { transform: rotate(1.6deg) translateX(8px); }
    .yt-torn__item--3 { transform: rotate(-1deg) translateX(-4px); }
    .yt-torn__item:hover, .yt-torn__item:focus-within { transform: rotate(0) translateY(-3px); }
    .yt-torn__leaf { display: grid; grid-template-columns: auto minmax(0,1fr); align-items: center; gap: 18px; width: 100%; padding: 26px 22px 18px; background: linear-gradient(180deg, #f8f3e6, var(--yt-old)); clip-path: polygon(0 9px,4% 3px,8% 8px,12% 1px,17% 7px,21% 2px,26% 9px,31% 3px,35% 7px,40% 0,45% 8px,49% 3px,54% 9px,58% 2px,63% 7px,68% 1px,72% 8px,77% 3px,81% 9px,86% 2px,90% 7px,95% 1px,100% 8px,100% 100%,0 100%); }
    .yt-torn__date { display: flex; flex-direction: column; align-items: center; min-width: 74px; padding-right: 18px; border-right: 1px solid var(--yt-line-2); text-align: center; }
    .yt-torn__num { color: var(--yt-red); font: 700 58px/.9 var(--yt-num); letter-spacing: -.02em; }
    .yt-torn__md { margin-top: 6px; color: var(--yt-ink); font: 500 12.5px/1.35 var(--yt-num); letter-spacing: .12em; text-transform: uppercase; }
    .yt-torn__info { display: flex; flex-direction: column; gap: 3px; min-width: 0; }
    .yt-torn__label { color: var(--yt-green); font: 600 12.5px/1.2 var(--yt-num); letter-spacing: .18em; text-transform: uppercase; }
    .yt-torn__name { font: 600 22px/1.15 var(--yt-num); overflow-wrap: break-word; }
    .yt-torn__name a { color: var(--yt-ink); text-decoration: none; }
    .yt-torn__name a:hover { color: var(--yt-red-2); text-decoration: underline; text-underline-offset: 4px; }
    .yt-torn__meta { color: var(--yt-muted); font-size: 14.5px; }
    .yt-torn__tel { display: inline-flex; align-items: center; gap: 6px; width: fit-content; min-height: 40px; color: var(--yt-red-2); font: 600 17px/1 var(--yt-num); letter-spacing: .03em; font-variant-numeric: tabular-nums; text-decoration: none; }
    .yt-torn__tel:hover { text-decoration: underline; text-underline-offset: 4px; }
    .yt-torn__item--cta { filter: none; transform: rotate(1deg); }
    .yt-torn__leaf--blank { display: flex; flex-direction: column; align-items: flex-start; justify-content: center; gap: 4px; padding: 22px; border: 2px dashed var(--yt-line-2); background: rgba(255,253,246,.55); clip-path: none; color: var(--yt-ink); text-decoration: none; }
    .yt-torn__leaf--blank .yt-torn__meta { color: var(--yt-red-2); font-weight: 700; }
    .yt-torn__leaf--blank:hover { border-color: var(--yt-red); }

    /* Bölümler */
    .yt-section { padding: 72px 0; }
    .yt-section--wall { background: var(--yt-news); border-top: 1px solid var(--yt-line); border-bottom: 1px solid var(--yt-line); }
    .yt-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 30px; padding-bottom: 14px; border-bottom: 3px double var(--yt-ink); }
    .yt-h2 { margin: 0; font: 600 clamp(30px, 3.2vw, 42px)/1.1 var(--yt-num); letter-spacing: .01em; }
    .yt-sub { margin-top: 8px !important; color: var(--yt-muted); font-size: 16.5px; }
    .yt-after { margin-top: 22px !important; text-align: center; }
    .yt-empty-line { color: var(--yt-muted); font-size: 17px; text-align: center; }

    /* Haftanın yaprakları */
    .yt-leaves { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .yt-leaves > li { display: flex; }
    .yt-leaf { display: flex; flex-direction: column; width: 100%; border: 1px solid var(--yt-line); border-radius: 3px; background: var(--yt-paper); box-shadow: 0 2px 0 -1px #f1ebdd, 0 3px 0 -1px var(--yt-line), 0 18px 30px -24px rgba(70,52,22,.45); transition: transform .18s, box-shadow .18s; }
    .yt-leaf:hover, .yt-leaf:focus-within { transform: translateY(-3px); box-shadow: 0 2px 0 -1px #f1ebdd, 0 3px 0 -1px var(--yt-line), 0 26px 34px -24px rgba(70,52,22,.55); }
    .yt-leaf__band { display: flex; align-items: center; justify-content: space-between; gap: 10px; min-height: 46px; padding: 8px 18px; border-radius: 3px 3px 0 0; background: var(--yt-red); color: #fff; }
    .yt-leaf__cat { min-width: 0; overflow: hidden; white-space: nowrap; text-overflow: ellipsis; font: 500 15px/1.2 var(--yt-num); letter-spacing: .12em; text-transform: uppercase; }
    .yt-leaf__seal { flex: none; padding: 3px 8px; border-radius: 2px; background: #fff; color: var(--yt-red-2); font: 600 12px/1.2 var(--yt-num); letter-spacing: .1em; text-transform: uppercase; }
    .yt-leaf--premium .yt-leaf__band { background: var(--yt-ink); }
    .yt-leaf--premium .yt-leaf__seal { background: var(--yt-red); color: #fff; }
    .yt-leaf__body { display: flex; flex: 1; flex-direction: column; gap: 7px; padding: 22px 20px 18px; background: radial-gradient(circle, #d3c9b4 1.6px, transparent 2px) 4px 7px/11px 10px repeat-x, var(--yt-paper); }
    .yt-leaf__name { margin: 0; font: 600 25px/1.15 var(--yt-num); overflow-wrap: break-word; }
    .yt-leaf__name a { color: var(--yt-ink); text-decoration: none; }
    .yt-leaf__name a:hover { color: var(--yt-red-2); text-decoration: underline; text-underline-offset: 4px; }
    .yt-leaf__place { display: inline-flex; align-items: center; gap: 5px; color: var(--yt-muted); font-size: 15px; }
    .yt-leaf__desc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: #3c3830; font: italic 400 16px/1.5 var(--yt-serif); }
    .yt-leaf__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 10px; font-size: 14px; }
    .yt-leaf__rating { color: var(--yt-ink); font-weight: 700; }
    .yt-leaf__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 14px; border-top: 1px dashed var(--yt-line-2); }
    .yt-leaf__acts .yt-call { flex: 1; }

    /* Boş yaprak */
    .yt-blank { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 30px 24px; border: 2px dashed var(--yt-red); border-radius: 3px; background: var(--yt-red-soft); text-align: center; }
    .yt-blank__tag { padding: 3px 10px; border: 1px solid var(--yt-red); border-radius: 2px; color: var(--yt-red-2); font: 600 13px/1.2 var(--yt-num); letter-spacing: .16em; text-transform: uppercase; }
    .yt-blank h3 { margin: 4px 0 0; font: 600 26px/1.15 var(--yt-num); }
    .yt-blank p { max-width: 42ch; color: var(--yt-muted); }
    .yt-blank .yt-btn { margin-top: 6px; }
    .yt-blank--empty { padding: 48px 24px; }

    /* Hizmet takvimi: küçük yapraklar */
    .yt-months { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 16px; }
    .yt-months > li { display: flex; }
    .yt-month { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: flex-start; gap: 2px; width: 100%; min-height: 150px; padding: 30px 12px 16px; border: 1px solid var(--yt-line); border-radius: 3px; background: var(--yt-paper); color: var(--yt-ink); text-align: center; text-decoration: none; box-shadow: 0 2px 0 -1px #f1ebdd, 0 3px 0 -1px var(--yt-line); transition: transform .16s, border-color .16s; }
    .yt-month:hover { transform: translateY(-3px); border-color: var(--yt-red); }
    .yt-month__band { position: absolute; top: 0; left: 0; right: 0; height: 14px; border-radius: 3px 3px 0 0; background: radial-gradient(circle, var(--yt-paper) 2px, transparent 2.4px) 50% 50%/22px 14px repeat-x, var(--yt-red); }
    .yt-month__num { color: var(--yt-red); font: 700 50px/1 var(--yt-num); letter-spacing: -.02em; font-variant-numeric: tabular-nums; }
    .yt-month__unit { color: var(--yt-muted); font: 500 12px/1 var(--yt-num); letter-spacing: .2em; text-transform: uppercase; }
    .yt-month__name { margin-top: auto; padding-top: 10px; font: 500 17px/1.2 var(--yt-num); letter-spacing: .04em; overflow-wrap: break-word; }

    /* Şehir almanağı */
    .yt-almanac { padding: 6px 26px 18px; border: 1px solid var(--yt-line); background: var(--yt-paper); }
    .yt-almanac__cap { display: none; }
    .yt-almanac__list { display: grid; grid-template-columns: minmax(0,1fr); gap: 0 44px; }
    .yt-almanac__row { display: flex; align-items: baseline; gap: 10px; min-height: 52px; padding: 14px 2px 8px; border-bottom: 1px solid var(--yt-line); color: var(--yt-ink); text-decoration: none; }
    .yt-almanac__row:hover .yt-almanac__name { color: var(--yt-red-2); text-decoration: underline; text-underline-offset: 4px; }
    .yt-almanac__name { flex: none; max-width: 72%; font: 500 19px/1.2 var(--yt-num); letter-spacing: .03em; overflow-wrap: break-word; }
    .yt-almanac__dots { flex: 1; min-width: 16px; border-bottom: 2px dotted var(--yt-line-2); transform: translateY(-5px); }
    .yt-almanac__n { flex: none; color: var(--yt-red); font: 600 20px/1 var(--yt-num); font-variant-numeric: tabular-nums; }

    /* Takvim arkası yazıları */
    .yt-backs { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .yt-backs > li { display: flex; }
    .yt-back { position: relative; display: flex; flex-direction: column; gap: 10px; width: 100%; padding: 24px 24px 14px; overflow: hidden; border: 1px solid var(--yt-line); border-radius: 3px; background: var(--yt-back); }
    .yt-back__date { position: relative; display: flex; align-items: baseline; gap: 10px; }
    .yt-back__num { position: absolute; top: -34px; right: -34px; color: var(--yt-red); font: 700 150px/1 var(--yt-num); opacity: .08; transform: scaleX(-1); pointer-events: none; }
    .yt-back__mon { color: var(--yt-green); font: 500 13.5px/1.2 var(--yt-num); letter-spacing: .18em; text-transform: uppercase; }
    .yt-back h3 { position: relative; margin: 0; font: 600 23px/1.2 var(--yt-num); }
    .yt-back h3 a { color: var(--yt-ink); text-decoration: none; }
    .yt-back h3 a:hover { color: var(--yt-red-2); text-decoration: underline; text-underline-offset: 4px; }
    .yt-back p { position: relative; color: #3c3830; font: 400 16.5px/1.55 var(--yt-serif); }
    .yt-back .yt-link { position: relative; margin-top: auto; }

    /* İş ilanları */
    .yt-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; }
    .yt-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border: 1px solid var(--yt-line); border-top: 4px solid var(--yt-red); background: var(--yt-paper); color: var(--yt-ink); text-decoration: none; }
    .yt-job:hover .yt-job__title { text-decoration: underline; }
    .yt-job__type { color: var(--yt-red-2); font: 500 13px/1.3 var(--yt-num); letter-spacing: .14em; text-transform: uppercase; }
    .yt-job__title { font: 600 21px/1.2 var(--yt-num); }
    .yt-job__meta { color: var(--yt-muted); font-size: 14.5px; }

    /* Takvimde yerinizi alın */
    .yt-cta-sec { padding-top: 24px; }
    .yt-cta { display: grid; grid-template-columns: minmax(0,1fr); gap: 30px; align-items: center; padding: 32px 24px; border: 1px solid var(--yt-line); border-top: 4px solid var(--yt-red); background: var(--yt-paper); }
    .yt-cta__leaf { position: relative; display: flex; flex-direction: column; align-items: center; gap: 12px; width: 100%; max-width: 300px; margin: 0 auto; padding: 34px 26px 28px; border: 1px solid var(--yt-line-2); background: var(--yt-news); box-shadow: 0 3px 0 -1px #efe9db, 0 4px 0 -1px var(--yt-line-2), 0 7px 0 -3px #ebe4d4, 0 8px 0 -3px var(--yt-line-2); transform: rotate(-1.5deg); }
    .yt-cta__band { position: absolute; top: 0; left: 0; right: 0; height: 16px; background: radial-gradient(circle, var(--yt-news) 2.2px, transparent 2.6px) 50% 50%/24px 16px repeat-x, var(--yt-red); }
    .yt-cta__num { color: transparent; font: 700 110px/.9 var(--yt-num); -webkit-text-stroke: 2px var(--yt-red); }
    .yt-cta__line { width: 80%; height: 0; border-bottom: 2px dashed var(--yt-line-2); }
    .yt-cta__line--short { width: 50%; }
    .yt-cta__word { color: var(--yt-ink); font: 600 30px/1 var(--yt-num); letter-spacing: .12em; text-transform: uppercase; }
    .yt-cta__txt h2 { margin-bottom: 10px; }
    .yt-cta__txt > p { max-width: 54ch; margin-bottom: 20px; color: var(--yt-muted); font-size: 17px; }
    .yt-cta__form label { display: block; margin-bottom: 6px; font: 500 14px/1.2 var(--yt-num); letter-spacing: .14em; text-transform: uppercase; color: var(--yt-red-2); }
    .yt-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .yt-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 16px; border: 1px solid var(--yt-line-2); border-radius: 3px; background: #fff; color: var(--yt-ink); font: 500 17px/1.2 var(--font_body); }
    .yt-cta__row input::placeholder { color: #8d867a; }
    .yt-cta__row input:focus { outline: none; border-color: var(--yt-ink); box-shadow: 0 0 0 3px rgba(27,26,23,.12); }
    .yt-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin-top: 12px !important; }

    /* Mobil sabit çubuk */
    .yt-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-yaprak-takvim body > footer { background: var(--yt-ink) !important; color: #d9d3c6 !important; border-top: 4px solid var(--yt-red); }
    .theme-yaprak-takvim body > footer :where(p,ul,button) { color: #c2bbad !important; }
    .theme-yaprak-takvim body > footer :where(h3,span) { color: #f7f3ea !important; }
    .theme-yaprak-takvim body > footer h3 { font-family: var(--yt-num); font-weight: 500; font-size: 17px; text-transform: uppercase !important; letter-spacing: .14em !important; }
    .theme-yaprak-takvim body > footer a:hover { color: #ff9aa9 !important; }
    .theme-yaprak-takvim body > footer :focus-visible { outline-color: #f7f3ea; }

    /* İç sayfalar: birincil renkli başlık bantları mürekkep siyahı, altında kırmızı çizgi. */
    .theme-yaprak-takvim main section[style*="background:var(--primary)"] { background: var(--yt-ink) !important; box-shadow: inset 0 -4px 0 var(--yt-red); }
    .theme-yaprak-takvim main section[style*="background:var(--primary)"] :focus-visible { outline-color: #f7f3ea; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-yaprak-takvim #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-yaprak-takvim #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .yt-today__main { grid-template-columns: minmax(0,.8fr) minmax(0,1.2fr); gap: 0 26px; }
        .yt-today__date { padding: 18px 26px 22px 0; border-right: 1px dashed var(--yt-line-2); }
        .yt-today__firm { justify-content: center; padding: 22px 0 24px; border-top: 0; }
        .yt-today__search { grid-template-columns: minmax(0,1.4fr) minmax(0,1fr) auto; align-items: end; }
        .yt-leaves { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .yt-leaves > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .yt-months { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .yt-almanac__list { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .yt-torn__list { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .yt-cta { padding: 36px; }
    }
    @media (min-width: 768px) {
        .yt-backs, .yt-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (min-width: 900px) {
        .yt-months { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .yt-torn__list { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .yt-torn__leaf { grid-template-columns: minmax(0,1fr); }
        .yt-torn__date { flex-direction: row; gap: 12px; justify-content: flex-start; padding: 0 0 10px; border-right: 0; border-bottom: 1px solid var(--yt-line-2); text-align: left; }
        .yt-torn__leaf--blank { grid-column: auto; }
    }
    @media (min-width: 1024px) {
        .yt-hero__grid { grid-template-columns: minmax(0,7fr) minmax(0,4.4fr); gap: 46px; align-items: stretch; }
        .yt-hero__grid--solo { grid-template-columns: minmax(0,1fr); max-width: 820px; }
        .yt-torn { padding-top: 6px; }
        .yt-torn__list { flex: 1; grid-template-columns: minmax(0,1fr); grid-auto-rows: minmax(0,1fr); gap: 26px; }
        .yt-torn__leaf { grid-template-columns: auto minmax(0,1fr); }
        .yt-torn__date { flex-direction: column; gap: 0; justify-content: center; padding: 0 18px 0 0; border-bottom: 0; border-right: 1px solid var(--yt-line-2); text-align: center; }
        .yt-leaves { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 26px; }
        .yt-leaves > li:last-child:nth-child(odd) { grid-column: auto; }
        .yt-leaves > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .yt-leaves > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .yt-almanac__list { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .yt-cta { grid-template-columns: minmax(0,4fr) minmax(0,8fr); gap: 56px; padding: 48px 56px; }
    }
    @media (min-width: 1200px) {
        .yt-months { grid-template-columns: repeat(var(--yt-cols, 6),minmax(0,1fr)); }
    }
    @media (min-width: 640px) {
        .yt-almanac--c1 .yt-almanac__list { grid-template-columns: minmax(0,1fr); }
        .yt-almanac--c2 .yt-almanac__list { grid-template-columns: repeat(2,minmax(0,1fr)); }
    }
    @media (min-width: 1024px) and (max-width: 1199.98px) {
        .yt-nav { gap: 18px; }
        .yt-today__main { grid-template-columns: minmax(0,.75fr) minmax(0,1.25fr); gap: 0 20px; }
        .yt-today__date { padding-right: 20px; }
        .yt-today__search { grid-template-columns: minmax(0,1fr) minmax(0,1fr); }
        .yt-search__btn { grid-column: 1 / -1; }
        .yt-torn__leaf { gap: 14px; padding: 24px 16px 16px; }
        .yt-torn__date { min-width: 62px; padding-right: 14px; }
        .yt-torn__num { font-size: 48px; }
        .yt-torn__name { font-size: 20px; }
        .yt-leaf__acts { gap: 6px; }
        .yt-leaf__acts .yt-call { gap: 6px; padding: 0 8px; font-size: 16.5px; }
        .yt-leaf__acts .yt-call svg { width: 15px; height: 15px; }
        .yt-leaf__acts .yt-wa { width: 44px; }
        .yt-leaf__acts .yt-more { width: 40px; }
    }
    @media (max-width: 1023.98px) {
        .yt-nav { display: none; }
        .yt-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-yaprak-takvim #mobile-menu { display: block; z-index: 60; }
        .theme-yaprak-takvim #mobile-backdrop { z-index: 55; }
        .theme-yaprak-takvim #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 760px) {
        html.theme-yaprak-takvim { scroll-padding-bottom: 84px; }
        .yt-home { padding-bottom: 76px; }
        .yt-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .yt-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(247,243,234,.97); border-top: 1px solid var(--yt-line-2); box-shadow: 0 -12px 28px -18px rgba(27,26,23,.45); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .yt-dock.is-on .yt-dock__bar { transform: none; visibility: visible; }
        .yt-dock__bar .yt-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 639.98px) {
        .yt-wrap { padding-inline: 16px; }
        .yt-header__row { height: 60px; gap: 10px; }
        .yt-brand { gap: 10px; }
        .yt-brand__name { max-width: 46vw; font-size: 19px; }
        .yt-btn--sm { min-height: 44px; padding: 0 12px; }
        .yt-hero { padding: 30px 0 52px; }
        .yt-h1 { font-size: clamp(30px, 8.6vw, 38px); }
        .yt-lede { font-size: 16.5px; }
        .yt-hero__grid { margin-top: 24px; gap: 34px; }
        .yt-pad__board { height: 52px; padding: 0 16px; font-size: 13px; letter-spacing: .12em; }
        .yt-pad__board { justify-content: flex-end; }
        .yt-pad__brand { display: none; }
        .yt-pad__year { font-size: 16px; }
        .yt-pad__stub, .yt-today { margin-inline: 6px; }
        .yt-today__top { margin: 0 14px; font-size: 13px; letter-spacing: .1em; }
        .yt-today__month { font-size: 22px; letter-spacing: .18em; }
        .yt-today__main, .yt-today__search { margin: 0 14px; }
        .yt-today__num { font-size: 128px; }
        .yt-today__wd { font-size: 22px; }
        .yt-today__name { font-size: 28px; }
        .yt-call--big { flex-basis: 100%; font-size: 22px; }
        .yt-today__acts .yt-call--wa:not(.yt-call--big) { flex: 1 1 auto; }
        .yt-torn__item--2 { transform: rotate(1.2deg) translateX(4px); }
        .yt-torn__leaf { padding: 24px 16px 16px; gap: 14px; }
        .yt-torn__date { min-width: 62px; padding-right: 14px; }
        .yt-torn__num { font-size: 48px; }
        .yt-section { padding: 50px 0; }
        .yt-head { flex-direction: column; align-items: flex-start; gap: 6px; margin-bottom: 22px; }
        .yt-head .yt-link { min-height: 36px; }
        .yt-leaf__body { padding: 20px 16px 16px; }
        .yt-leaf__name { font-size: 23px; }
        .yt-month { min-height: 132px; padding: 28px 10px 14px; }
        .yt-month__num { font-size: 42px; }
        .yt-month__name { font-size: 16px; }
        .yt-almanac { padding: 4px 16px 14px; }
        .yt-cta { padding: 26px 18px; }
        .yt-cta__leaf { max-width: 240px; padding: 28px 20px 22px; }
        .yt-cta__num { font-size: 84px; }
        .yt-cta__row .yt-btn { width: 100%; }
        .yt-cta-sec { padding-top: 8px; }
    }
    @media (max-width: 419.98px) {
        .yt-cta-long { display: none; }
        .yt-cta-short { display: inline; }
        .yt-call { font-size: 17px; }
        .yt-call--big { font-size: 21px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-yaprak-takvim { scroll-behavior: auto; }
        .theme-yaprak-takvim #mobile-menu { transition: none; }
        .yt-leaf, .yt-month, .yt-torn__item, .yt-nav a::after, .yt-btn, .yt-call, .yt-link svg, .yt-dock__bar { transition: none !important; }
        .yt-leaf:hover, .yt-leaf:focus-within, .yt-month:hover, .yt-link:hover svg { transform: none; }
    }
</style>
