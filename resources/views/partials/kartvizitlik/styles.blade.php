<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&family=Zilla+Slab:wght@500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Archivo:wght@400;500;600;700&family=Zilla+Slab:wght@500;600;700&display=swap"></noscript>
<style>
    /* Kartvizitlik · Kart Dosyası */
    .theme-kartvizitlik {
        --kv-ink: #23201c; --kv-ink-2: #0f0d0b;
        --kv-red: #c41e3a; --kv-red-2: #a3172f;
        --kv-wa: #1f9d55; --kv-open: #1e7a43; --kv-notch: #2f9e5b;
        --kv-stamp: #3d4aa0;
        --kv-umber: #6f5a3c;
        --kv-kraft: #dfcaa5; --kv-kraft-deep: #cbb187;
        --kv-stock: #fffefb;
        --kv-premium: #211e1b; --kv-premium-text: #f3ead8;
        --kv-rule: #cfd8e3;
        --kv-tab-1: #e8d5a6; --kv-tab-2: #cfdcc4; --kv-tab-3: #ecc9bd; --kv-tab-4: #c9d8e4;
        --kv-edge: inset 0 1px 0 rgba(255,255,255,.7), 0 1px 0 rgba(40,30,15,.08), 0 12px 20px -14px rgba(40,30,15,.45);
        --kv-edge-up: inset 0 1px 0 rgba(255,255,255,.7), 0 2px 0 rgba(40,30,15,.08), 0 18px 26px -16px rgba(40,30,15,.55);
        scroll-padding-top: 80px;
    }
    .theme-kartvizitlik body { background: var(--bg); color: var(--text); font-size: 16px; line-height: 1.55; }
    .theme-kartvizitlik :where(h1,h2,h3) { font-family: var(--font_heading); }
    .theme-kartvizitlik :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--kv-ink); outline-offset: 3px; }
    .theme-kartvizitlik #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .kv-wrap { max-width: var(--page_width, 1200px); margin-inline: auto; padding-inline: 32px; }
    .kv-home { overflow-x: clip; }
    .kv-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }
    .kv-dot { display: inline-block; width: 8px; height: 8px; border-radius: 50%; background: var(--kv-notch); flex: none; }

    /* Düğmeler */
    .kv-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; padding: 0 20px; border-radius: 6px; border: 2px solid transparent; font: 600 16px/1.2 var(--font_body); text-align: center; text-decoration: none; cursor: pointer; transition: background-color .16s, color .16s, border-color .16s; }
    .kv-btn--sm { min-height: 40px; padding: 0 16px; white-space: nowrap; }
    .kv-btn--lg { min-height: 52px; padding: 0 26px; }
    .kv-btn--ink { background: var(--kv-ink); color: #fff; }
    .kv-btn--ink:hover { background: var(--kv-ink-2); }
    .kv-btn--red { background: var(--kv-red); color: #fff; }
    .kv-btn--red:hover { background: var(--kv-red-2); }
    .kv-btn--line { background: transparent; color: var(--kv-ink); border-color: var(--kv-ink); }
    .kv-btn--line:hover { background: var(--kv-ink); color: #fff; }
    .kv-link { display: inline-flex; align-items: center; min-height: 44px; color: var(--kv-ink); font-weight: 600; text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .kv-link:hover { text-decoration-thickness: 2px; }
    .kv-redlink { display: inline-flex; align-items: center; min-height: 44px; color: var(--kv-red); font-weight: 600; text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }

    /* Üst şerit */
    .kv-header { position: sticky; top: 0; z-index: 50; background: var(--kv-stock); border-bottom: 1px solid var(--border); }
    .kv-header::after { content: ""; position: absolute; left: 0; right: 0; top: 100%; height: 3px; background: var(--kv-kraft); pointer-events: none; }
    .kv-header__row { display: flex; align-items: center; justify-content: space-between; gap: 16px; height: 64px; }
    .kv-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--kv-ink); text-decoration: none; }
    .kv-brand img { display: block; width: auto; max-width: 160px; height: 36px; object-fit: contain; }
    .kv-mark { position: relative; width: 30px; height: 24px; flex: none; }
    .kv-mark i { position: absolute; width: 22px; height: 14px; border-radius: 2px; }
    .kv-mark i:first-child { left: 0; top: 2px; background: var(--kv-kraft); box-shadow: 0 0 0 1px rgba(35,32,28,.25); transform: rotate(-6deg); }
    .kv-mark i:last-child { left: 7px; top: 8px; background: var(--kv-ink); transform: rotate(4deg); }
    .kv-brand__name { min-width: 0; font: 600 20px/1.2 var(--font_heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kv-nav { display: flex; flex: none; align-items: center; gap: 26px; }
    .kv-nav a { padding: 8px 0; border-bottom: 2px solid transparent; color: var(--kv-ink); font: 500 16px/1.2 var(--font_body); text-decoration: none; white-space: nowrap; }
    .kv-nav a:hover, .kv-nav a[aria-current="page"] { border-bottom-color: var(--kv-ink); }
    .kv-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .kv-cta-short { display: none; }
    .kv-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1.5px solid var(--kv-ink); border-radius: 6px; background: transparent; color: var(--kv-ink); cursor: pointer; }

    /* Masa (kraft) */
    .kv-desk { background-color: var(--kv-kraft); background-image: radial-gradient(120% 80% at 50% 0,rgba(255,248,232,.45),transparent 60%), repeating-linear-gradient(17deg,rgba(60,38,10,.035) 0 1px,transparent 1px 3px), repeating-linear-gradient(-23deg,rgba(255,255,255,.05) 0 1px,transparent 1px 4px); }
    .kv-hero { padding: 56px 0 72px; }
    .kv-hero__grid { display: grid; grid-template-columns: minmax(0,1fr); gap: 32px; align-items: start; }
    .kv-hero__main { min-width: 0; }
    .kv-label { display: inline-block; max-width: 100%; margin: 0 0 18px; padding: 6px 24px; border: 1px solid var(--kv-ink); border-radius: 4px; background: radial-gradient(circle at 11px 50%, var(--kv-ink) 0 2.5px, transparent 3px), radial-gradient(circle at calc(100% - 11px) 50%, var(--kv-ink) 0 2.5px, transparent 3px), var(--kv-stock); color: var(--kv-ink); font: 600 14px/1.4 var(--font_body); overflow-wrap: anywhere; }
    .kv-h1 { max-width: 16ch; margin: 0 0 16px; color: var(--kv-ink); font-family: var(--font_heading); font-weight: 700; font-size: clamp(34px,5vw,58px); line-height: 1.06; text-wrap: balance; overflow-wrap: break-word; }
    .kv-lede { max-width: 52ch; margin: 0 0 28px; color: #3d372f; font-size: 18px; line-height: 1.55; }

    /* Arama fişi */
    .kv-fis { position: relative; z-index: 2; background: var(--kv-stock); border: 1px solid rgba(60,40,10,.12); border-radius: 6px; box-shadow: var(--kv-edge); }
    .kv-search { max-width: 620px; }
    .kv-fis__head { display: flex; align-items: center; height: 40px; padding: 0 20px; border-bottom: 2px solid var(--kv-ink); color: var(--text_muted); font: 600 13px/1 var(--font_body); text-transform: uppercase; letter-spacing: .08em; }
    .kv-row { display: grid; grid-template-columns: 150px minmax(0,1fr); align-items: center; gap: 8px; min-height: 56px; padding: 0 20px; border-bottom: 1px solid var(--kv-rule); }
    .kv-row:focus-within { background: rgba(35,32,28,.04); }
    .kv-row label { color: var(--text_muted); font: 600 14px/1.2 var(--font_body); }
    .kv-row :where(input,select) { width: 100%; min-width: 0; height: 44px; padding: 0 2px; border: 0; border-radius: 0; background: transparent; color: var(--text); font: 500 17px/1.2 var(--font_body); }
    .kv-row :where(input,select):focus { outline: none; box-shadow: 0 2px 0 var(--kv-ink); }
    .kv-row input::placeholder { color: #6f685d; }
    .kv-fis__foot { display: flex; justify-content: flex-end; padding: 14px 20px 16px; }

    /* Sık açılan çekmeceler */
    .kv-quicktabs { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 12px; max-width: 620px; margin-top: 22px; }
    .kv-quicktabs__k { color: #3d372f; font: 600 14px/1.3 var(--font_body); }
    .kv-quicktabs__row { display: flex; flex-wrap: wrap; gap: 6px; min-width: 0; }
    .kv-qtab { display: inline-flex; align-items: center; min-height: 36px; padding: 0 20px; background: var(--tab); color: var(--kv-ink); clip-path: polygon(8% 0,92% 0,100% 100%,0 100%); font: 600 15px/1.2 var(--font_body); text-decoration: none; white-space: nowrap; }
    .kv-qtab:hover { filter: brightness(.95); }
    .kv-qtab:focus-visible { clip-path: none; }

    /* Yelpaze (yalnızca masaüstü) */
    .kv-fan { display: none; position: relative; z-index: 1; }
    .kv-fan__back { position: absolute; right: 0; bottom: 0; width: min(360px,100%); height: 230px; border-radius: 6px; background: var(--kv-stock); border: 1px solid rgba(60,40,10,.12); box-shadow: var(--kv-edge); transform-origin: 12% 100%; }
    .kv-fan__back--1 { transform: rotate(9deg); }
    .kv-fan__back--2 { transform: rotate(4deg); }
    .kv-fan__top { position: relative; container-type: inline-size; width: min(360px,100%); margin-left: auto; transform: rotate(-2deg); transition: transform .16s; }
    .kv-fan__top:hover, .kv-fan__top:focus-within { transform: none; }

    /* Bölümler */
    .kv-section { padding: 64px 0; content-visibility: auto; contain-intrinsic-size: auto 640px; }
    .kv-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 8px 24px; margin-bottom: 28px; }
    .kv-head > div { flex: 1 1 320px; min-width: 0; }
    .kv-head h2 { margin: 0 0 10px; padding-bottom: 8px; border-bottom: 1px solid var(--kv-ink); color: var(--kv-ink); font-family: var(--font_heading); font-weight: 600; font-size: clamp(26px,3vw,34px); line-height: 1.15; overflow-wrap: break-word; }
    .kv-head p { margin: 0; color: var(--text_muted); font-size: 17px; }
    .kv-head--desk p { color: #3d372f; }
    .kv-more-row { margin-top: 28px; text-align: center; }
    .kv-after { margin: 20px 0 0; }

    /* Kartvizit */
    .kv-rack { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; margin: 0; padding: 0; list-style: none; }
    .kv-rack > li { container-type: inline-size; min-width: 0; list-style: none; }
    .kv-card { position: relative; display: flex; flex-direction: column; gap: 6px; height: 100%; min-width: 0; min-height: 200px; min-height: max(200px, calc(100cqi * 55 / 85)); padding: 18px 20px 0; border: 1px solid rgba(60,40,10,.12); border-radius: 6px; background: radial-gradient(rgba(35,32,28,.03) 1px,transparent 1px) 0 0/3px 3px, var(--kv-stock); box-shadow: var(--kv-edge); color: var(--text); text-decoration: none; transition: transform .16s, box-shadow .16s; }
    .kv-card:not(.kv-card--xl):hover, .kv-card:not(.kv-card--xl):focus-within { transform: translateY(-2px); box-shadow: var(--kv-edge-up); }
    .kv-card--open::before { content: ""; position: absolute; left: -1px; top: 18px; width: 4px; height: 30px; background: var(--kv-notch); border-radius: 0 2px 2px 0; }
    .kv-card__who { display: flex; align-items: center; gap: 12px; min-width: 0; }
    .kv-card--verified .kv-card__who { padding-right: 40px; }
    .kv-card__logo { flex: none; width: 36px; height: 36px; border-radius: 4px; object-fit: contain; background: #fff; }
    .kv-card__name { margin: 0; min-width: 0; color: var(--kv-ink); font: 600 21px/1.15 var(--font_heading); overflow-wrap: anywhere; }
    /* İki satır kırpması bağlantının içindeki span'de: başlık kırpmadığı için odak çerçevesi görünür kalır. */
    .kv-card__name a { display: block; color: inherit; text-decoration: none; }
    .kv-card__name-text { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .kv-card__name a:hover .kv-card__name-text { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 3px; }
    .kv-card__unvan { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 10px; margin: 2px 0 0; color: var(--kv-umber); font: 600 14px/1.3 var(--font_body); }
    .kv-card__unvan-rule { flex: none; width: 18px; height: 1px; background: currentColor; }
    .kv-card__premium { color: #d9b77a; font: 600 13px/1.3 var(--font_body); }
    .kv-card__desc { margin: 0; color: var(--text_muted); font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .kv-card__where { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 12px; margin: 0 0 14px; color: var(--text_muted); font-size: 14px; }
    .kv-card__open { display: inline-flex; align-items: center; gap: 6px; color: var(--kv-open); font-weight: 600; }
    .kv-card__place { display: inline-flex; align-items: center; gap: 4px; min-width: 0; overflow-wrap: anywhere; }
    .kv-card__place svg { flex: none; }
    .kv-star { color: #b7791f; }

    .kv-strip { display: flex; align-items: center; gap: 8px; min-height: 52px; margin: auto -20px 0; padding: 4px 8px 4px 0; border-top: 1px solid rgba(35,32,28,.1); border-radius: 0 0 5px 5px; background: rgba(35,32,28,.03); }
    .kv-call { flex: 1 1 auto; display: flex; align-items: center; gap: 8px; min-width: 0; min-height: 44px; padding-left: 20px; color: var(--kv-ink); text-decoration: none; }
    .kv-call__k { color: var(--text_muted); font: 600 13px/1 var(--font_body); text-transform: uppercase; letter-spacing: .06em; }
    .kv-call__n { min-width: 15ch; font: 600 22px/1 var(--font_heading); font-variant-numeric: tabular-nums lining-nums; white-space: nowrap; }
    .kv-call:hover .kv-call__n { text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .kv-call--wa, .kv-call--wa .kv-call__k { color: var(--kv-open); }
    .kv-wa { flex: none; display: grid; place-items: center; width: 40px; height: 40px; border-radius: 6px; background: var(--kv-wa); color: #fff; }
    .kv-wa:hover { background: var(--kv-open); }
    .kv-more { flex: none; display: grid; place-items: center; width: 40px; height: 40px; border: 1px solid rgba(35,32,28,.2); border-radius: 6px; color: var(--kv-ink); font-size: 22px; line-height: 1; text-decoration: none; }
    .kv-more:hover { background: rgba(35,32,28,.06); }
    .kv-strip__all { flex: 1; display: flex; align-items: center; min-height: 44px; padding-left: 20px; color: var(--kv-ink); font-weight: 600; text-decoration: none; }
    @container (max-width: 359.98px) {
        .kv-strip { gap: 4px; }
        .kv-call { gap: 6px; padding-left: 16px; }
        .kv-call__n { min-width: 0; font-size: 20px; }
    }

    /* Onaylı mührü */
    .kv-seal { position: absolute; top: -12px; right: -8px; z-index: 1; display: grid; place-items: center; width: 60px; height: 60px; border: 2px solid var(--kv-stamp); border-radius: 50%; background: rgba(255,255,255,.6); box-shadow: inset 0 0 0 3px var(--kv-stock), inset 0 0 0 4px var(--kv-stamp); color: var(--kv-stamp); font: 700 11px/1 var(--font_body); text-transform: uppercase; letter-spacing: .06em; transform: rotate(-12deg); opacity: .88; pointer-events: none; }

    /* Premium: siyah kart */
    .kv-card--premium { background: radial-gradient(rgba(255,255,255,.035) 1px,transparent 1px) 0 0/3px 3px, var(--kv-premium); border-color: rgba(0,0,0,.45); color: var(--kv-premium-text); }
    .kv-card--premium .kv-card__name { color: var(--kv-premium-text); }
    @supports ((-webkit-background-clip: text) or (background-clip: text)) {
        .kv-card--premium .kv-card__name-text { background: linear-gradient(100deg,#b8913a,#f1dc9a 45%,#c9a24a); -webkit-background-clip: text; background-clip: text; color: transparent; }
    }
    .kv-card--premium .kv-card__unvan { color: #d9b77a; }
    .kv-card--premium :where(.kv-card__desc,.kv-card__where,.kv-call__k) { color: #bfb39c; }
    .kv-card--premium .kv-card__open { color: #5fd08a; }
    .kv-card--premium .kv-star { color: #e3b45a; }
    .kv-card--premium .kv-strip { background: rgba(255,255,255,.05); border-top-color: rgba(255,255,255,.12); }
    .kv-card--premium .kv-call { color: var(--kv-premium-text); }
    .kv-card--premium .kv-call--wa, .kv-card--premium .kv-call--wa .kv-call__k { color: #5fd08a; }
    .kv-card--premium .kv-more, .kv-card--premium .kv-strip__all { color: var(--kv-premium-text); border-color: rgba(255,255,255,.25); }
    .kv-card--premium .kv-seal { border-color: #b9c0ff; background: rgba(33,30,27,.6); box-shadow: inset 0 0 0 3px var(--kv-premium), inset 0 0 0 4px #b9c0ff; color: #b9c0ff; }
    .kv-card--premium :where(a,button):focus-visible { outline-color: #f1dc9a; }

    /* Boş kart */
    .kv-card--blank { border: 2px dashed #8a7a62; background: rgba(255,255,255,.45); box-shadow: none; }
    .kv-card--blank:not(.kv-card--xl):hover { box-shadow: none; background: rgba(255,255,255,.7); }
    .kv-tag { align-self: flex-end; padding: 5px 8px; border: 1.5px solid var(--kv-red); border-radius: 4px; color: var(--kv-red); font: 600 12px/1 var(--font_body); text-transform: uppercase; letter-spacing: .06em; }
    .kv-ghost { color: var(--text_muted); text-decoration: underline dotted; text-decoration-thickness: 1px; text-underline-offset: 5px; }
    .kv-ghost-line { align-self: flex-start; color: var(--text_muted); text-decoration: underline dotted; text-underline-offset: 4px; }
    .kv-card--blank .kv-card__where { margin-bottom: 14px; }
    .kv-strip--blank { flex-wrap: wrap; gap: 2px 12px; padding: 8px 20px; }
    .kv-ghost-phone { color: #8a8173; font: 600 20px/1.2 var(--font_heading); font-variant-numeric: tabular-nums lining-nums; letter-spacing: .02em; white-space: nowrap; }
    .kv-blank-cta { color: var(--kv-red); font: 600 14px/1.35 var(--font_body); }
    .kv-card--xl { min-height: 300px; max-width: 560px; width: 100%; }
    .kv-empty { display: flex; justify-content: center; }
    .kv-empty__card { justify-content: center; gap: 12px; min-height: 260px; padding-bottom: 22px; }
    .kv-empty__card .kv-tag { position: absolute; top: 16px; right: 16px; }
    .kv-fan__top .kv-card--blank, .kv-fan__top .kv-card--blank:hover { background: var(--kv-stock); }
    .kv-empty__text { margin: 0; color: var(--text_muted); font-size: 17px; }
    .kv-empty-line { display: flex; flex-wrap: wrap; align-items: center; gap: 0 12px; margin: 0; color: var(--text_muted); font-size: 17px; }

    /* Numara listesi */
    .kv-quick { max-width: 820px; margin-inline: auto; }
    .kv-quick__cols { display: flex; justify-content: space-between; padding: 12px 20px 10px; border-bottom: 2px solid var(--kv-ink); color: var(--text_muted); font: 600 12px/1 var(--font_body); text-transform: uppercase; letter-spacing: .08em; }
    .kv-quick__cols span:last-child { padding-right: 56px; }
    .kv-quick__list { margin: 0; padding: 0; list-style: none; }
    .kv-quick__row { display: grid; grid-template-columns: 14px minmax(0,1fr) auto auto; grid-template-areas: "d w t a"; align-items: center; gap: 12px; min-height: 52px; padding: 4px 12px 4px 20px; border-bottom: 1px solid var(--kv-rule); }
    .kv-quick__row:last-child { border-bottom: 0; }
    .kv-quick__dot { grid-area: d; display: flex; align-items: center; }
    .kv-quick__who { grid-area: w; display: flex; flex-wrap: wrap; align-items: baseline; gap: 2px 10px; min-width: 0; }
    .kv-quick__name { display: inline-flex; align-items: center; min-height: 44px; color: var(--kv-ink); font: 600 17px/1.25 var(--font_heading); text-decoration: none; overflow-wrap: anywhere; }
    .kv-quick__name:hover { text-decoration: underline; }
    .kv-quick__cat { color: var(--text_muted); font-size: 13px; }
    .kv-quick__tel { grid-area: t; display: flex; align-items: center; justify-content: flex-end; min-width: 15ch; min-height: 44px; color: var(--kv-ink); font: 600 19px/1 var(--font_heading); font-variant-numeric: tabular-nums lining-nums; text-align: right; text-decoration: none; white-space: nowrap; }
    .kv-quick__tel:hover { text-decoration: underline; text-underline-offset: 4px; }
    .kv-quick__row .kv-wa, .kv-wa-gap { grid-area: a; }
    .kv-wa-gap { display: block; width: 40px; }

    /* Çekmeceler */
    .kv-drawers { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 20px; margin: 0; padding: 0; list-style: none; }
    .kv-drawers > li { display: flex; min-width: 0; }
    .kv-drawer { display: flex; flex-direction: column; width: 100%; color: var(--kv-ink); text-decoration: none; }
    .kv-drawer__tab { align-self: flex-start; max-width: 86%; padding: 7px 18px; background: var(--tab); clip-path: polygon(8% 0,92% 0,100% 100%,0 100%); font: 600 16px/1.25 var(--font_heading); display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; overflow-wrap: anywhere; }
    .kv-drawers > li:nth-child(2n) .kv-drawer__tab { align-self: flex-end; }
    .kv-drawer__body { flex: 1; display: flex; flex-direction: column; justify-content: space-between; gap: 10px; min-height: 112px; padding: 14px 16px; border-top: 4px solid var(--tab); border-radius: 0 0 6px 6px; background: var(--kv-stock); box-shadow: var(--kv-edge); transition: transform .16s, box-shadow .16s; }
    .kv-drawer:hover .kv-drawer__body, .kv-drawer:focus-visible .kv-drawer__body { transform: translateY(-2px); box-shadow: var(--kv-edge-up); }
    .kv-drawer__count { color: var(--text_muted); font-size: 14px; }
    .kv-drawer__count strong { margin-right: 4px; color: var(--kv-ink); font: 600 32px/1 var(--font_heading); font-variant-numeric: lining-nums; }
    .kv-drawer__open { font-weight: 600; font-size: 15px; }

    /* Şehir kutuları */
    .kv-boxes { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 26px 18px; margin: 0; padding: 20px 0 0; list-style: none; }
    .kv-boxes > li { min-width: 0; }
    .kv-box { position: relative; display: flex; flex-direction: column; justify-content: flex-end; gap: 2px; min-height: 96px; height: 100%; padding: 24px 14px 14px; border-radius: 4px 4px 6px 6px; background: #e8dcc4; box-shadow: 0 1px 0 rgba(40,30,15,.1), 0 10px 16px -12px rgba(40,30,15,.45); color: var(--kv-ink); text-decoration: none; }
    .kv-box::after { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 12px; border-radius: 4px 4px 0 0; background: var(--kv-kraft-deep); }
    .kv-box::before { content: ""; position: absolute; top: -10px; left: 14%; right: 14%; height: 10px; background: #fff; box-shadow: 0 -4px 0 #f3efe6, 0 -8px 0 #e4ded0; transition: transform .16s; }
    .kv-box:hover::before, .kv-box:focus-visible::before { transform: translateY(-3px); }
    .kv-box__name { font: 600 18px/1.2 var(--font_heading); overflow-wrap: anywhere; }
    .kv-box__n { color: #4d473f; font-size: 13px; }

    /* Notlar ve ilanlar */
    .kv-notes { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; margin: 0; padding: 0; list-style: none; }
    .kv-notes > li { display: flex; min-width: 0; }
    .kv-note { display: flex; flex-direction: column; gap: 10px; width: 100%; padding: 0 20px 14px; border-top: 2px solid var(--kv-ink); border-radius: 0 0 6px 6px; background: var(--kv-stock); box-shadow: var(--kv-edge); }
    .kv-note__band { display: flex; align-items: center; justify-content: flex-end; height: 40px; margin: 0 -20px; padding: 0 20px; border-bottom: 1px solid var(--kv-rule); color: var(--text_muted); font-size: 13px; }
    .kv-note h3 { margin: 4px 0 0; color: var(--kv-ink); font: 600 20px/1.25 var(--font_heading); overflow-wrap: anywhere; }
    .kv-note h3 a { color: inherit; text-decoration: none; }
    .kv-note h3 a:hover { text-decoration: underline; }
    .kv-note p { flex: 1; margin: 0; color: var(--text_muted); font-size: 16px; overflow-wrap: anywhere; }
    .kv-note .kv-link { align-self: flex-start; }
    .kv-job { display: flex; flex-direction: column; gap: 6px; width: 100%; padding: 18px 20px; border: 1px solid rgba(60,40,10,.12); border-radius: 6px; background: var(--kv-stock); box-shadow: var(--kv-edge); color: var(--text); text-decoration: none; transition: transform .16s, box-shadow .16s; }
    .kv-job:hover { transform: translateY(-2px); box-shadow: var(--kv-edge-up); }
    .kv-job__type { color: var(--kv-umber); font: 600 13px/1.3 var(--font_body); }
    .kv-job__title { color: var(--kv-ink); font: 600 18px/1.25 var(--font_heading); overflow-wrap: anywhere; }
    .kv-job__meta { color: var(--text_muted); font-size: 15px; overflow-wrap: anywhere; }

    /* Boş kart bandı */
    .kv-cta { padding: 64px 0 72px; }
    .kv-cta__grid { display: grid; grid-template-columns: minmax(0,1fr); gap: 28px; align-items: center; }
    .kv-cta__card { margin-inline: auto; padding-bottom: 0; background: rgba(255,255,255,.62); }
    .kv-ghost-label { margin-top: 4px; color: var(--text_muted); font: 600 12px/1.2 var(--font_body); text-transform: uppercase; letter-spacing: .08em; }
    .kv-ghost-field { display: flex; align-items: center; gap: 8px; border-bottom: 1.5px dashed #8a7a62; }
    .kv-ghost-field:focus-within { border-bottom-style: solid; border-bottom-color: var(--kv-ink); }
    .kv-ghost-field input { flex: 1; min-width: 0; height: 52px; padding: 6px 0; border: 0; background: transparent; color: var(--kv-ink); font: 600 26px/1.2 var(--font_heading); }
    .kv-ghost-field input:focus { outline: none; }
    .kv-ghost-field input::placeholder { color: #6b6357; }
    .kv-pen { color: var(--text_muted); font-size: 20px; }
    .kv-cta__card .kv-ghost-line { margin-bottom: 14px; }
    .kv-strip--form { flex-wrap: wrap; justify-content: space-between; gap: 10px; padding: 10px 12px 10px 20px; }
    .kv-facts ul { display: grid; gap: 12px; margin: 0 0 8px; padding: 0; list-style: none; }
    .kv-facts li { display: flex; align-items: center; gap: 10px; color: var(--kv-ink); font-size: 17px; }
    .kv-tick { position: relative; flex: none; width: 18px; height: 18px; border: 1.5px solid var(--kv-ink); border-radius: 3px; }
    .kv-tick::after { content: ""; position: absolute; left: 5px; top: 1px; width: 5px; height: 10px; border: solid var(--kv-ink); border-width: 0 2px 2px 0; transform: rotate(45deg); }

    /* Mobil alt çubuk */
    .kv-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-kartvizitlik body > footer { background: #1f1d1a !important; color: #d9d2c4 !important; border-top: 6px solid var(--kv-kraft); }
    .theme-kartvizitlik body > footer :where(p,ul,button) { color: #bfb6a6 !important; }
    .theme-kartvizitlik body > footer :where(h3,span) { color: #f1e8d6 !important; }
    .theme-kartvizitlik body > footer h3 { text-transform: none !important; letter-spacing: 0 !important; font-family: var(--font_heading); font-size: 16px; }

    /* İç sayfalar */
    .theme-kartvizitlik main section[style*="background:var(--primary);"] { background-image: repeating-linear-gradient(17deg,rgba(255,255,255,.025) 0 1px,transparent 1px 3px) !important; box-shadow: inset 0 -6px 0 var(--kv-kraft); }
    /* Koyu zeminlerde mürekkep rengi odak çerçevesi görünmez; açık krem kullanılır. */
    .theme-kartvizitlik body > footer :focus-visible, .theme-kartvizitlik main section[style*="background:var(--primary);"] :focus-visible { outline-color: #f1e8d6; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-kartvizitlik #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-kartvizitlik #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .kv-rack { grid-template-columns: repeat(2,minmax(0,1fr)); gap: 24px; }
        .kv-drawers { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .kv-drawers > li:nth-child(n) .kv-drawer__tab { align-self: flex-start; }
        .kv-drawers > li:nth-child(3n+2) .kv-drawer__tab { align-self: center; }
        .kv-drawers > li:nth-child(3n) .kv-drawer__tab { align-self: flex-end; }
        .kv-boxes { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .kv-notes { grid-template-columns: repeat(2,minmax(0,1fr)); }
    }
    @media (min-width: 1024px) {
        .kv-hero__grid { grid-template-columns: minmax(0,620px) minmax(0,1fr); }
        .kv-fan { display: block; align-self: end; margin-left: -48px; padding: 24px 12px 0 0; }
        .kv-rack { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 28px; }
        .kv-rack__item--fanned { display: none; }
        .kv-drawers { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .kv-boxes { grid-template-columns: repeat(6,minmax(0,1fr)); }
        .kv-notes { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .kv-cta__grid { grid-template-columns: minmax(0,560px) minmax(0,1fr); gap: 48px; }
        .kv-cta__card { margin-inline: 0; }
    }
    @media (min-width: 1024px) and (max-width: 1179.98px) {
        .kv-nav { gap: 18px; }
    }
    @media (max-width: 1023.98px) {
        .kv-nav { display: none; }
        .kv-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-kartvizitlik #mobile-menu { display: block; z-index: 60; }
        .theme-kartvizitlik #mobile-backdrop { z-index: 55; }
        .theme-kartvizitlik #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 639.98px) {
        /* Alt çubuk (kv-dock) odaklanan öğeyi örtmesin. */
        html.theme-kartvizitlik { scroll-padding-bottom: 84px; }
        .kv-wrap { padding-inline: 16px; }
        .kv-header__row { height: 56px; gap: 10px; }
        .kv-brand { gap: 10px; }
        .kv-brand__name { max-width: 46vw; font-size: 18px; }
        .kv-btn--sm { min-height: 44px; padding: 0 14px; }
        .kv-hero { padding: 36px 0 48px; }
        .kv-lede { font-size: 17px; margin-bottom: 22px; }
        .kv-fis__head { padding: 0 16px; }
        .kv-row { grid-template-columns: minmax(0,1fr); align-content: center; gap: 0; min-height: 64px; padding: 6px 16px; }
        .kv-row label { font-size: 12px; text-transform: uppercase; letter-spacing: .06em; }
        .kv-fis__foot { padding: 12px 16px 16px; }
        .kv-fis__foot .kv-btn { width: 100%; }
        .kv-quicktabs { display: block; }
        .kv-quicktabs__row { flex-wrap: nowrap; margin: 8px -16px 0; padding: 0 16px 4px; overflow-x: auto; scroll-snap-type: x proximity; scroll-padding-inline: 16px; scrollbar-width: thin; }
        .kv-qtab { flex: none; min-height: 44px; scroll-snap-align: start; }
        .kv-section { padding: 48px 0; }
        .kv-strip { min-height: 56px; }
        .kv-wa, .kv-more { width: 44px; height: 44px; }
        .kv-quick__cols span:last-child { padding-right: 0; }
        .kv-quick__row { grid-template-columns: 14px minmax(0,1fr) 44px; grid-template-areas: "d w w" "t t a"; gap: 6px 10px; padding: 10px 16px; }
        .kv-quick__tel { justify-content: flex-start; min-width: 0; min-height: 48px; padding: 0 14px; border-radius: 6px; background: rgba(35,32,28,.05); text-align: left; }
        .kv-wa-gap { width: 44px; }
        .kv-drawers { gap: 16px; }
        .kv-cta { padding: 48px 0 56px; }
        .kv-strip--form { flex-direction: column; align-items: stretch; padding: 12px 16px; }
        .kv-strip--form .kv-btn { width: 100%; }
        .kv-ghost-field input { font-size: 22px; }
        .kv-home { padding-bottom: 76px; }
        .kv-dock { display: block; position: sticky; bottom: 0; height: 0; z-index: 40; }
        .kv-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 8px; padding: 8px 16px max(8px, env(safe-area-inset-bottom)); background: var(--kv-stock); border-top: 1px solid var(--border); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .kv-dock.is-on .kv-dock__bar { transform: none; visibility: visible; }
        .kv-dock__bar .kv-btn { padding: 0 10px; }
    }
    @media (max-width: 419.98px) {
        .kv-cta-long { display: none; }
        .kv-cta-short { display: inline; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-kartvizitlik { scroll-behavior: auto; }
        .theme-kartvizitlik #mobile-menu { transition: none; }
        .kv-card, .kv-job, .kv-drawer__body, .kv-box::before, .kv-fan__top, .kv-dock__bar, .kv-btn { transition: none !important; }
        .kv-card:hover, .kv-card:focus-within, .kv-job:hover, .kv-drawer:hover .kv-drawer__body, .kv-drawer:focus-visible .kv-drawer__body, .kv-box:hover::before, .kv-box:focus-visible::before { transform: none !important; }
        .kv-fan__top:hover, .kv-fan__top:focus-within { transform: rotate(-2deg); }
    }
</style>
