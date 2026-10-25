<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Bitter:wght@500;600;700;800&family=Figtree:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Bitter:wght@500;600;700;800&family=Figtree:wght@400;500;600;700&family=Caveat:wght@600;700&display=swap"></noscript>
<style>
    /* Posta Pulu · Mektup Kutusu: zarf kreması, uçak postası kırmızı/mavi şeritleri, damga mürekkebi, pul yeşili ve aşı sarısı. */
    .theme-posta-pulu {
        --pp-bg: #ebe5d8; --pp-bg-2: #f3eee3; --pp-env: #f7efdf; --pp-env-2: #fbf6ea; --pp-paper: #fffcf4;
        --pp-ink: #24212e; --pp-muted: #575166; --pp-line: #dcd2bd; --pp-line-2: #c2b69e;
        --pp-red: #c8202f; --pp-red-2: #a51826; --pp-blue: #1f4e8c; --pp-blue-2: #173c6d; --pp-pm: #3b3550;
        --pp-green: #2e6a4a; --pp-ochre: #8f5a0f; --pp-wa: #1b7f45;
        --pp-slab: 'Bitter', Georgia, serif; --pp-sans: 'Figtree', system-ui, sans-serif; --pp-script: 'Caveat', 'Segoe Print', cursive;
        --pp-stripes: repeating-linear-gradient(-45deg, var(--pp-red) 0 14px, transparent 14px 21px, var(--pp-blue) 21px 35px, transparent 35px 42px);
        scroll-padding-top: 88px;
    }
    .theme-posta-pulu body { background: var(--pp-bg); color: var(--pp-ink); font-family: var(--pp-sans); font-size: 16px; line-height: 1.6; }
    .theme-posta-pulu :where(h1,h2,h3) { font-family: var(--pp-slab); font-weight: 700; }
    .theme-posta-pulu :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--pp-blue); outline-offset: 3px; }
    .theme-posta-pulu #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .pp-wrap { width: 100%; max-width: var(--page_width, 1240px); margin-inline: auto; padding-inline: 32px; }
    .pp-home { overflow-x: clip; }
    .pp-home ul, .pp-home ol { margin: 0; padding: 0; list-style: none; }
    .pp-home p { margin: 0; }
    .pp-hand { font-family: var(--pp-script); font-weight: 700; font-size: 1.5em; line-height: 1; color: var(--pp-blue-2); letter-spacing: 0; }

    /* Pul tonları */
    .pp-tone--green { --tone: var(--pp-green); --tone-soft: #e4efe7; }
    .pp-tone--ochre { --tone: var(--pp-ochre); --tone-soft: #f6ead2; }
    .pp-tone--blue { --tone: var(--pp-blue); --tone-soft: #e3eaf4; }
    .pp-tone--red { --tone: var(--pp-red-2); --tone-soft: #f8e3e3; }

    /* Tırtıklı kenar: kenarlarda yarım daire delikler, içerik alanı tam opak kalır (yazı keskin). */
    .pp-perf {
        --r: 4.5px; --g: 15px;
        padding: 8px; background: var(--pp-paper);
        -webkit-mask: radial-gradient(circle var(--r) at 50% 50%, #0000 96%, #000) calc(var(--g) / -2) calc(var(--g) / -2) / var(--g) var(--g) round, linear-gradient(#000 0 0) content-box;
        mask: radial-gradient(circle var(--r) at 50% 50%, #0000 96%, #000) calc(var(--g) / -2) calc(var(--g) / -2) / var(--g) var(--g) round, linear-gradient(#000 0 0) content-box;
    }
    .pp-shadow { filter: drop-shadow(0 12px 14px rgba(59,53,80,.18)) drop-shadow(0 1px 1px rgba(59,53,80,.22)); }

    /* Düğmeler ve bağlantılar */
    .pp-btn { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-height: 46px; padding: 0 20px; border: 1.5px solid transparent; border-radius: 4px; font: 700 16px/1.1 var(--pp-sans); letter-spacing: .01em; text-align: center; text-decoration: none; cursor: pointer; transition: background-color .15s, color .15s, border-color .15s; }
    .pp-btn--sm { min-height: 42px; padding: 0 16px; font-size: 15px; white-space: nowrap; }
    .pp-btn--lg { min-height: 54px; padding: 0 26px; font-size: 17px; }
    .pp-btn--red { background: var(--pp-red); color: #fff; }
    .pp-btn--red:hover { background: var(--pp-red-2); }
    .pp-btn--line { background: var(--pp-paper); border-color: var(--pp-line-2); color: var(--pp-ink); }
    .pp-btn--line:hover { border-color: var(--pp-ink); }
    .pp-link { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; color: var(--pp-blue); font-weight: 700; text-decoration: none; }
    .pp-link svg { transition: transform .15s; }
    .pp-link:hover { text-decoration: underline; text-underline-offset: 4px; }
    .pp-link:hover svg { transform: translateX(3px); }

    /* Üst şerit: uçak postası zarfı kenarı */
    .pp-header { position: sticky; top: 0; z-index: 50; background: rgba(250,245,233,.97); backdrop-filter: blur(8px); border-bottom: 1px solid var(--pp-line-2); }
    .pp-header__stripe { height: 7px; background: var(--pp-stripes), #fbf6ea; }
    .pp-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 68px; }
    .pp-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--pp-ink); text-decoration: none; }
    .pp-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .pp-brand__mark { --r: 2.2px; --g: 8px; display: flex; flex: none; width: 38px; height: 44px; padding: 4px; background: var(--pp-paper); filter: drop-shadow(0 1px 1px rgba(59,53,80,.35));
        -webkit-mask: radial-gradient(circle var(--r) at 50% 50%, #0000 96%, #000) calc(var(--g) / -2) calc(var(--g) / -2) / var(--g) var(--g) round, linear-gradient(#000 0 0) content-box;
        mask: radial-gradient(circle var(--r) at 50% 50%, #0000 96%, #000) calc(var(--g) / -2) calc(var(--g) / -2) / var(--g) var(--g) round, linear-gradient(#000 0 0) content-box; }
    .pp-brand__mark span { display: grid; place-items: center; width: 100%; background: var(--pp-red); color: #fff7e8; font: 800 20px/1 var(--pp-slab); box-shadow: inset 0 0 0 2px var(--pp-red), inset 0 0 0 3px rgba(255,247,232,.6); }
    .pp-brand__name { min-width: 0; font: 700 21px/1.15 var(--pp-slab); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .pp-nav { display: flex; flex: none; align-items: center; gap: 26px; }
    .pp-nav a { position: relative; padding: 10px 0; color: var(--pp-ink); font: 600 15.5px/1.2 var(--pp-sans); text-decoration: none; white-space: nowrap; }
    .pp-nav a::after { content: ""; position: absolute; left: 0; right: 0; bottom: 3px; height: 2px; background: var(--pp-red); transform: scaleX(0); transform-origin: left; transition: transform .16s; }
    .pp-nav a:hover::after, .pp-nav a[aria-current="page"]::after { transform: scaleX(1); }
    .pp-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .pp-cta-short { display: none; }
    .pp-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1px solid var(--pp-line-2); border-radius: 4px; background: var(--pp-paper); color: var(--pp-ink); cursor: pointer; }

    /* Ortak etiketler */
    .pp-kicker { display: flex; align-items: center; gap: 10px; margin: 0 0 10px !important; color: var(--pp-red-2); font: 700 13.5px/1.3 var(--pp-sans); letter-spacing: .16em; text-transform: uppercase; }
    .pp-kicker::before { content: ""; flex: none; width: 30px; height: 6px; background: var(--pp-stripes); background-size: 30px 6px; }
    .pp-chip { display: inline-flex; align-items: center; padding: 3px 9px; border-radius: 3px; font: 700 12.5px/1.3 var(--pp-sans); }
    .pp-chip--open { background: #e2efe6; color: var(--pp-green); }
    .pp-rubber { display: inline-flex; align-items: center; padding: 2px 8px; border: 2px solid currentColor; border-radius: 5px; box-shadow: inset 0 0 0 1px rgba(255,255,255,.6); font: 800 11.5px/1.25 var(--pp-slab); letter-spacing: .14em; text-transform: uppercase; transform: rotate(-5deg); white-space: nowrap; }
    .pp-rubber--red { color: #b3192a; }
    .pp-rubber--green { color: var(--pp-green); transform: rotate(3deg); }

    /* Hero */
    .pp-hero { position: relative; padding: 40px 0 70px; background: radial-gradient(70% 60% at 85% 10%, #f1ece1, transparent 70%), var(--pp-bg); border-bottom: 1px solid var(--pp-line-2); }
    .pp-hero__head { max-width: 880px; }
    .pp-h1 { margin: 0 0 12px; font: 800 clamp(34px, 4vw, 52px)/1.1 var(--pp-slab); letter-spacing: -.015em; color: var(--pp-ink); text-wrap: balance; overflow-wrap: break-word; }
    .pp-lede { max-width: 62ch; color: var(--pp-muted); font-size: 18px; }
    .pp-lede__n { color: var(--pp-ink); font-weight: 700; }
    .pp-hero__grid { display: grid; grid-template-columns: minmax(0,1fr); gap: 44px; margin-top: 30px; }

    /* Zarf: uçak postası kenarlı krem zarf */
    .pp-airmail { border: 11px solid transparent; border-radius: 6px; background: linear-gradient(var(--pp-env), var(--pp-env)) padding-box, var(--pp-stripes) border-box, #fbf6ea border-box; box-shadow: 0 34px 50px -34px rgba(59,53,80,.5), 0 2px 5px rgba(59,53,80,.14); }
    .pp-env { position: relative; min-width: 0; }
    .pp-env__in { position: relative; display: grid; grid-template-columns: minmax(0,1fr) minmax(0,8.6rem); grid-template-areas: "from corner" "addr addr" "feat feat" "empty empty"; gap: 18px 16px; padding: 18px 16px 20px; background: radial-gradient(110% 80% at 15% 0, var(--pp-env-2), transparent 60%); }
    .pp-env__from { grid-area: from; display: flex; flex-direction: column; align-items: flex-start; gap: 12px; min-width: 0; }
    .pp-airlabel { display: inline-flex; align-items: center; padding: 5px 12px; border: 2px solid var(--pp-blue); border-radius: 3px; color: var(--pp-blue); font: 800 13px/1.1 var(--pp-slab); letter-spacing: .2em; text-transform: uppercase; box-shadow: inset 0 0 0 2px var(--pp-env), inset 0 0 0 3px var(--pp-blue); }
    .pp-env__sender { color: var(--pp-muted); font-size: 15px; overflow-wrap: anywhere; }
    .pp-env__sender .pp-hand { font-size: 1.45em; margin-right: 2px; }
    .pp-env__corner { grid-area: corner; position: relative; justify-self: end; width: 100%; }
    .pp-env__stamp { position: relative; z-index: 1; transform: rotate(2deg); }
    .pp-cancel { position: absolute; z-index: 2; right: calc(100% - 18px); bottom: 2px; display: flex; flex-direction: row-reverse; align-items: center; color: var(--pp-pm); opacity: .72; mix-blend-mode: multiply; pointer-events: none; }
    .pp-cancel .pp-pm { flex: none; width: 92px; height: 92px; transform: rotate(-12deg); }
    .pp-waves { flex: none; color: var(--pp-pm); }
    .pp-cancel .pp-waves { width: 120px; height: 40px; margin-right: -14px; }
    .pp-env__feat { grid-area: feat; display: flex; flex-direction: column; gap: 12px; min-width: 0; }
    .pp-feat__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 12px; color: var(--pp-muted); font-size: 15px; }
    .pp-feat__place { display: inline-flex; align-items: center; gap: 5px; }
    .pp-feat__acts { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 14px; }
    .pp-env__empty { grid-area: empty; color: var(--pp-muted); }

    /* Adres bloğu: arama formu */
    .pp-address { grid-area: addr; display: grid; grid-template-columns: minmax(0,1fr); gap: 6px; min-width: 0; }
    .pp-address__line { display: flex; flex-direction: column; gap: 2px; padding-top: 6px; border-bottom: 2px solid rgba(59,53,80,.55); transition: border-color .15s; }
    .pp-address__line:focus-within { border-color: var(--pp-blue); }
    .pp-address__line label { display: flex; align-items: baseline; gap: 8px; color: var(--pp-muted); font-size: 14.5px; cursor: pointer; }
    .pp-address__line label .pp-hand { font-size: 30px; }
    .pp-address__hint { font-weight: 600; }
    .pp-address__line :where(input,select) { width: 100%; min-width: 0; height: 48px; padding: 0 2px; border: 0; border-radius: 0; background: transparent; color: var(--pp-ink); font: 600 19px/1.2 var(--pp-sans); box-shadow: none; outline: none; }
    .pp-address__line input::placeholder { color: #8a8494; font-weight: 500; }
    .pp-address__line select { -webkit-appearance: none; appearance: none; padding-right: 30px; background: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='14' height='14' viewBox='0 0 24 24'%3E%3Cpath fill='none' stroke='%233b3550' stroke-width='2.6' stroke-linecap='round' stroke-linejoin='round' d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 6px center; cursor: pointer; }
    .pp-address__line :where(input,select):focus-visible { outline: none; }
    .pp-address__send { padding-top: 14px; }
    .pp-address__send .pp-btn { width: 100%; }

    /* Pul */
    .pp-stamp { display: flex; width: 100%; }
    .pp-stamp__in { display: flex; flex-direction: column; align-items: center; gap: 6px; width: 100%; min-height: 100%; padding: 10px 10px 12px; background: repeating-radial-gradient(circle at 50% 44%, rgba(255,248,232,.075) 0 1px, transparent 1px 6px), var(--tone); color: #fff8e8; text-align: center; box-shadow: inset 0 0 0 3px var(--tone), inset 0 0 0 4px rgba(255,248,232,.55); }
    .pp-stamp__row { display: flex; align-items: flex-start; justify-content: space-between; gap: 6px; width: 100%; }
    .pp-stamp__row:has(> :only-child) { justify-content: center; }
    .pp-stamp__den { display: flex; flex-direction: column; align-items: flex-start; font: 800 26px/.95 var(--pp-slab); letter-spacing: -.02em; }
    .pp-stamp__den small { font: 700 10px/1.2 var(--pp-sans); letter-spacing: .14em; }
    .pp-stamp__tag { min-width: 0; padding-top: 2px; font: 700 10.5px/1.25 var(--pp-sans); letter-spacing: .14em; text-transform: uppercase; overflow-wrap: anywhere; }
    .pp-stamp__mono { display: grid; place-items: center; width: 62px; aspect-ratio: 1; margin: 4px 0 2px; border: 1.5px solid rgba(255,248,232,.6); border-radius: 50%; box-shadow: 0 0 0 4px var(--tone), 0 0 0 5px rgba(255,248,232,.3); font: 800 25px/1 var(--pp-slab); letter-spacing: .02em; }
    .pp-stamp__name { margin: 0; font: 700 16px/1.2 var(--pp-slab); overflow-wrap: break-word; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .pp-stamp__name a { color: inherit; text-decoration: none; }
    .pp-stamp__name a:hover { text-decoration: underline; text-underline-offset: 3px; }
    .pp-stamp__cat { font: 600 11.5px/1.3 var(--pp-sans); letter-spacing: .12em; text-transform: uppercase; opacity: .9; }
    .pp-stamp__tel { display: inline-flex; align-items: center; gap: 6px; min-height: 36px; margin-top: auto; padding: 0 10px; border-radius: 3px; background: rgba(255,248,232,.16); color: #fff8e8; font: 700 14.5px/1 var(--pp-sans); font-variant-numeric: tabular-nums; text-decoration: none; white-space: nowrap; }
    .pp-stamp__tel:hover { background: rgba(255,248,232,.3); }
    .pp-stamp--lg .pp-stamp__in { padding: 12px 12px 16px; gap: 8px; }
    .pp-stamp--lg .pp-stamp__mono { width: 70px; font-size: 28px; }

    /* Masadaki pullar */
    .pp-pile { display: flex; flex-direction: column; gap: 16px; min-width: 0; }
    .pp-pile__title { display: flex; align-items: center; gap: 12px; color: var(--pp-muted); font: 700 13px/1.2 var(--pp-sans); letter-spacing: .18em; text-transform: uppercase; }
    .pp-pile__title::after { content: ""; flex: 1; height: 1px; background: var(--pp-line-2); }
    .pp-pile__list { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 22px 18px; align-items: start; }
    .pp-pile__item { position: relative; transition: transform .2s; }
    .pp-pile__item > .pp-shadow, .pp-pile__item > .pp-slot { max-width: 230px; margin-inline: auto; }
    .pp-pile__item:nth-child(1) { transform: rotate(-3deg); }
    .pp-pile__item:nth-child(2) { transform: rotate(2.5deg); }
    .pp-pile__item:nth-child(3) { display: none; }
    .pp-pile__item:hover, .pp-pile__item:focus-within { transform: rotate(0) translateY(-3px); z-index: 5; }
    .pp-pile .pp-stamp__in { min-height: 220px; }

    /* Boş pul yeri */
    .pp-slot { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; width: 100%; min-height: 200px; padding: 18px 12px; border: 2px dashed rgba(59,53,80,.45); border-radius: 3px; background: rgba(255,252,244,.6); color: var(--pp-ink); text-align: center; text-decoration: none; }
    .pp-slot__t { font: 800 13px/1.2 var(--pp-slab); letter-spacing: .2em; text-transform: uppercase; color: var(--pp-muted); }
    .pp-slot .pp-hand { font-size: 26px; }
    .pp-slot__go { margin-top: 6px; color: var(--pp-red-2); font-weight: 700; font-size: 14.5px; }
    a.pp-slot:hover { border-color: var(--pp-red); background: var(--pp-paper); }
    .pp-slot--env { min-height: 170px; }

    /* Arama düğmeleri */
    .pp-call { display: inline-flex; align-items: center; justify-content: center; gap: 9px; min-width: 0; min-height: 48px; padding: 0 16px; border: 1.5px solid var(--pp-blue); border-radius: 4px; background: var(--pp-blue); color: #fff; font: 700 17px/1 var(--pp-sans); font-variant-numeric: tabular-nums; text-decoration: none; transition: background-color .15s, color .15s; }
    .pp-call span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .pp-call:hover { background: var(--pp-blue-2); border-color: var(--pp-blue-2); }
    .pp-call--big { flex: 1 1 100%; min-height: 56px; font-size: 21px; }
    .pp-call--wa { border-color: var(--pp-wa); background: var(--pp-paper); color: var(--pp-wa); }
    .pp-call--wa:hover { background: var(--pp-wa); border-color: var(--pp-wa); color: #fff; }
    .pp-call--wa.pp-call--big { background: var(--pp-wa); color: #fff; }
    .pp-call--line { border-color: var(--pp-line-2); background: var(--pp-paper); color: var(--pp-ink); }
    .pp-call--line:hover { background: var(--pp-bg-2); border-color: var(--pp-ink); }
    .pp-feat__acts .pp-call--wa:not(.pp-call--big) { min-height: 50px; }
    .pp-sq { display: grid; flex: none; place-items: center; width: 46px; height: 48px; border: 1.5px solid var(--pp-line-2); border-radius: 4px; background: var(--pp-paper); color: var(--pp-ink); }
    .pp-sq:hover { border-color: var(--pp-ink); }
    .pp-sq--wa { border-color: #a9c8b6; color: var(--pp-wa); }
    .pp-sq--wa:hover { background: var(--pp-wa); border-color: var(--pp-wa); color: #fff; }

    /* Bölümler */
    .pp-section { padding: 72px 0; }
    .pp-section--alt { background: var(--pp-bg-2); border-top: 1px solid var(--pp-line); border-bottom: 1px solid var(--pp-line); }
    .pp-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 30px; }
    .pp-h2 { margin: 0; font: 800 clamp(29px, 3vw, 40px)/1.12 var(--pp-slab); letter-spacing: -.01em; }
    .pp-sub { margin-top: 8px !important; color: var(--pp-muted); font-size: 16.5px; }
    .pp-after { margin-top: 22px !important; text-align: center; }
    .pp-empty-line { padding: 26px 20px; border: 2px dashed var(--pp-line-2); border-radius: 4px; color: var(--pp-muted); font-size: 17px; text-align: center; }

    /* Yeni gelen mektuplar: tırtıklı pul kartlar */
    .pp-letters { display: grid; grid-template-columns: minmax(0,1fr); gap: 26px; }
    .pp-letters > li { display: flex; }
    .pp-letter { display: flex; width: 100%; filter: drop-shadow(0 14px 14px rgba(59,53,80,.13)) drop-shadow(0 1px 1px rgba(59,53,80,.2)); transition: transform .18s; }
    .pp-letter:hover, .pp-letter:focus-within { transform: translateY(-3px); }
    .pp-letter__card { --g: 16px; display: flex; width: 100%; }
    .pp-letter__in { display: flex; flex-direction: column; gap: 8px; width: 100%; padding: 16px 18px 16px; border: 1.5px solid var(--tone); background: linear-gradient(var(--tone-soft), var(--tone-soft)) 0 0 / 100% 5px no-repeat, var(--pp-paper); box-shadow: inset 0 0 0 4px var(--pp-paper), inset 0 0 0 5px color-mix(in srgb, var(--tone) 30%, transparent); }
    .pp-letter__top { display: flex; align-items: flex-start; justify-content: space-between; gap: 10px; min-height: 26px; padding-top: 4px; }
    .pp-letter__cat { min-width: 0; color: var(--tone); font: 700 12.5px/1.3 var(--pp-sans); letter-spacing: .14em; text-transform: uppercase; overflow-wrap: anywhere; }
    .pp-letter__marks { display: flex; flex: none; flex-wrap: wrap; justify-content: flex-end; gap: 6px; }
    .pp-letter__name { margin: 0; font: 700 23px/1.18 var(--pp-slab); overflow-wrap: break-word; }
    .pp-letter__name a { color: var(--pp-ink); text-decoration: none; }
    .pp-letter__name a:hover { color: var(--pp-blue); text-decoration: underline; text-underline-offset: 4px; }
    .pp-letter__place { display: inline-flex; align-items: center; gap: 5px; color: var(--pp-muted); font-size: 15px; }
    .pp-letter__desc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; color: #3f3a4a; font-size: 15.5px; }
    .pp-letter__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 10px; font-size: 14px; }
    .pp-letter__rating { font-weight: 700; }
    .pp-letter__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 14px; border-top: 1.5px dashed var(--pp-line-2); }
    .pp-letter__acts .pp-call { flex: 1; }

    /* Boş zarf kartı */
    .pp-blank { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; width: 100%; padding: 30px 24px; border: 2px dashed rgba(200,32,47,.55); border-radius: 4px; background: var(--pp-env); text-align: center; }
    .pp-blank__slot { display: grid; place-items: center; width: 56px; height: 66px; border: 2px dashed rgba(59,53,80,.45); color: var(--pp-muted); font: 800 12px/1 var(--pp-slab); letter-spacing: .2em; text-transform: uppercase; transform: rotate(3deg); }
    .pp-blank h3 { margin: 4px 0 0; font: 800 23px/1.2 var(--pp-slab); }
    .pp-blank p { max-width: 42ch; color: var(--pp-muted); }
    .pp-blank .pp-btn { margin-top: 6px; }
    .pp-blank--empty { padding: 48px 24px; }

    /* Pul tabakası */
    .pp-sheet { padding: 18px; border: 1px solid var(--pp-line-2); border-radius: 3px; background: repeating-linear-gradient(0deg, rgba(59,53,80,.035) 0 1px, transparent 1px 6px), #e4dac5; box-shadow: 0 20px 34px -28px rgba(59,53,80,.45); }
    .pp-sheet__grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 8px; }
    .pp-sheet__grid > li { display: flex; }
    .pp-mini { --r: 3.5px; --g: 12px; display: flex; width: 100%; padding: 6px; color: var(--pp-ink); text-decoration: none; transition: transform .16s; filter: none; }
    .pp-mini__in { display: flex; flex-direction: column; justify-content: space-between; gap: 10px; width: 100%; min-height: 128px; padding: 12px 12px 12px; border: 1.5px solid var(--tone); background: var(--tone-soft); }
    .pp-mini__den { display: flex; align-items: baseline; gap: 5px; color: var(--tone); font: 600 12px/1 var(--pp-sans); letter-spacing: .1em; text-transform: uppercase; }
    .pp-mini__den b { font: 800 34px/1 var(--pp-slab); letter-spacing: -.02em; }
    .pp-mini__den--go { font: 700 26px/1 var(--pp-slab); }
    .pp-mini__name { font: 700 16.5px/1.2 var(--pp-slab); overflow-wrap: break-word; }
    .pp-mini:hover { transform: translateY(-3px) rotate(-1deg); }
    .pp-mini:hover .pp-mini__name { text-decoration: underline; text-underline-offset: 3px; }

    /* Şehir damgaları */
    .pp-marks { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 18px 12px; }
    .pp-marks > li { display: flex; justify-content: center; }
    .pp-mark { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 8px; color: var(--pp-pm); text-decoration: none; }
    .pp-mark .pp-pm { width: 128px; height: 128px; opacity: .82; transform: rotate(var(--rot, -6deg)); transition: transform .2s, opacity .2s; }
    .pp-marks > li:nth-child(3n+2) .pp-mark { --rot: 5deg; }
    .pp-marks > li:nth-child(3n) .pp-mark { --rot: -2deg; }
    .pp-mark--red { color: var(--pp-red-2); }
    .pp-mark--blue { color: var(--pp-blue); }
    .pp-mark__name { color: var(--pp-ink); font: 700 17px/1.2 var(--pp-slab); text-align: center; overflow-wrap: anywhere; }
    .pp-mark:hover .pp-pm { opacity: 1; transform: rotate(0); }
    .pp-mark:hover .pp-mark__name { text-decoration: underline; text-underline-offset: 4px; }
    .pp-marks--few { display: flex; flex-wrap: wrap; justify-content: center; gap: 18px 30px; }
    .pp-cityrow { display: grid; grid-template-columns: minmax(0,1fr); gap: 10px; }
    .pp-cityrow .pp-head { margin-bottom: 0; }
    .pp-pm text { fill: currentColor; font-family: var(--pp-slab); font-weight: 800; letter-spacing: .1em; }
    .pp-pm .pp-pm__mid { letter-spacing: .04em; }

    /* Kartpostallar: arka yüz */
    .pp-cards { display: grid; grid-template-columns: minmax(0,1fr); gap: 24px; }
    .pp-cards > li { display: flex; }
    .pp-card { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,6.5rem); gap: 0 16px; width: 100%; min-height: 220px; padding: 20px 18px; border: 1px solid var(--pp-line); border-radius: 3px; background: var(--pp-paper); box-shadow: 0 18px 30px -24px rgba(59,53,80,.5); }
    .pp-card__msg { display: flex; flex-direction: column; gap: 8px; min-width: 0; }
    .pp-card__msg h3 { margin: 0; font: 700 19px/1.25 var(--pp-slab); overflow-wrap: break-word; }
    .pp-card__msg h3 a { color: var(--pp-ink); text-decoration: none; }
    .pp-card__msg h3 a:hover { color: var(--pp-blue); text-decoration: underline; text-underline-offset: 4px; }
    .pp-card__msg p { color: #3f3a4a; font-size: 15px; }
    .pp-card__msg .pp-link { margin-top: auto; }
    .pp-card__addr { display: flex; flex-direction: column; min-width: 0; padding-left: 14px; border-left: 1.5px solid var(--pp-line-2); }
    .pp-card__slot { align-self: flex-end; display: grid; place-items: center; width: 46px; height: 54px; margin-bottom: 14px; border: 1.5px dashed var(--pp-line-2); color: #8a8494; font: 800 10px/1 var(--pp-slab); letter-spacing: .18em; text-transform: uppercase; }
    .pp-card__line { display: flex; align-items: flex-end; min-height: 36px; border-bottom: 1.5px solid var(--pp-line-2); overflow: hidden; white-space: nowrap; }
    .pp-card__line .pp-hand { font-size: 21px; padding-bottom: 2px; }

    /* İş ilanları */
    .pp-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; }
    .pp-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border: 1px solid var(--pp-line); box-shadow: inset 0 5px 0 var(--pp-blue); background: var(--pp-paper); color: var(--pp-ink); text-decoration: none; }
    .pp-job:hover .pp-job__title { text-decoration: underline; }
    .pp-job__type { color: var(--pp-blue); font: 700 12.5px/1.3 var(--pp-sans); letter-spacing: .12em; text-transform: uppercase; }
    .pp-job__title { font: 700 20px/1.2 var(--pp-slab); }
    .pp-job__meta { color: var(--pp-muted); font-size: 14.5px; }

    /* Sahip çağrısı: boş pul yerli zarf */
    .pp-cta-sec { padding-top: 24px; }
    .pp-cta__in { display: grid; grid-template-columns: minmax(0,1fr); gap: 26px; padding: 26px 20px; background: radial-gradient(110% 80% at 15% 0, var(--pp-env-2), transparent 60%); }
    .pp-cta__txt > p { max-width: 54ch; margin: 10px 0 20px !important; color: var(--pp-muted); font-size: 17px; }
    .pp-cta__form label { display: flex; align-items: baseline; gap: 8px; margin-bottom: 4px; color: var(--pp-muted); font-weight: 600; font-size: 15px; }
    .pp-cta__form label .pp-hand { font-size: 28px; }
    .pp-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .pp-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 14px; border: 0; border-bottom: 2px solid rgba(59,53,80,.55); border-radius: 0; background: rgba(255,252,244,.7); color: var(--pp-ink); font: 600 18px/1.2 var(--pp-sans); }
    .pp-cta__row input::placeholder { color: #8a8494; font-weight: 500; }
    .pp-cta__row input:focus { outline: none; border-color: var(--pp-blue); background: #fff; }
    .pp-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin-top: 12px !important; }
    .pp-cta__corner { position: relative; display: flex; justify-content: center; order: -1; }
    .pp-slot--cta { width: 150px; min-height: 176px; transform: rotate(2deg); background: rgba(255,252,244,.8); }
    .pp-slot--cta .pp-hand { font-size: 23px; }
    .pp-waves--cta { position: absolute; left: calc(50% - 190px); top: 50%; width: 130px; height: 40px; opacity: .45; transform: translateY(-50%); }

    /* Mobil sabit çubuk */
    .pp-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-posta-pulu body > footer { background: #2a2636 !important; color: #dcd6e4 !important; border-top: 7px solid transparent; border-image: repeating-linear-gradient(-45deg, var(--pp-red) 0 14px, #fbf6ea 14px 21px, var(--pp-blue) 21px 35px, #fbf6ea 35px 42px) 7; }
    .theme-posta-pulu body > footer :where(p,ul,button) { color: #c9c2d4 !important; }
    .theme-posta-pulu body > footer :where(h3,span) { color: #fbf6ea !important; }
    .theme-posta-pulu body > footer h3 { font-family: var(--pp-slab); font-weight: 700; font-size: 17px; }
    .theme-posta-pulu body > footer a:hover { color: #ffb3bb !important; }
    .theme-posta-pulu body > footer :focus-visible { outline-color: #fbf6ea; }

    /* İç sayfalar: birincil renkli başlık bantları damga mürekkebi, altında uçak postası şeridi. */
    .theme-posta-pulu main section[style*="background:var(--primary)"] { background: #2a2636 !important; border-bottom: 7px solid transparent; border-image: repeating-linear-gradient(-45deg, var(--pp-red) 0 14px, #fbf6ea 14px 21px, var(--pp-blue) 21px 35px, #fbf6ea 35px 42px) 7; }
    .theme-posta-pulu main section[style*="background:var(--primary)"] :focus-visible { outline-color: #fbf6ea; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-posta-pulu #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-posta-pulu #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .pp-env__in { grid-template-columns: minmax(0,1fr) minmax(0,12.5rem); gap: 22px 26px; padding: 24px 26px 28px; }
        .pp-cancel .pp-pm { width: 112px; height: 112px; }
        .pp-cancel .pp-waves { width: 170px; height: 50px; }
        .pp-cancel { right: calc(100% - 22px); bottom: 30%; }
        .pp-stamp--lg .pp-stamp__in { min-height: 262px; }
        .pp-stamp--lg .pp-stamp__name { font-size: 20px; }
        .pp-stamp--lg .pp-stamp__mono { width: 84px; font-size: 33px; }
        .pp-stamp--lg .pp-stamp__den { font-size: 32px; }
        .pp-address__line { flex-direction: row; align-items: center; gap: 14px; padding-top: 4px; }
        .pp-address__line label { flex: none; flex-direction: column; align-items: flex-start; gap: 0; width: 9.5rem; font-size: 13.5px; line-height: 1.25; }
        .pp-address__line :where(input,select) { height: 64px; font-size: 20px; }
        .pp-address__send .pp-btn { width: auto; }
        .pp-call--big { flex: 1 1 240px; }
        .pp-pile__list { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 26px; }
        .pp-pile__item:nth-child(3) { display: block; transform: rotate(-1.5deg); }
        .pp-letters { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .pp-letters > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .pp-sheet { padding: 22px; }
        .pp-sheet__grid { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 10px; }
        .pp-marks { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .pp-card { grid-template-columns: minmax(0,1fr) minmax(0,9rem); padding: 22px; gap: 0 20px; }
        .pp-cta__in { grid-template-columns: minmax(0,1fr) minmax(0,13rem); align-items: center; padding: 36px; }
        .pp-cta__corner { order: 0; }
        .pp-slot--cta { width: 170px; min-height: 200px; }
        .pp-waves--cta { left: -110px; }
    }
    @media (min-width: 768px) {
        .pp-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .pp-cards { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .pp-cards > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .pp-env__in { grid-template-areas: "from corner" "addr corner" "addr feat" "empty empty"; grid-template-rows: auto minmax(0,1fr) auto; }
        .pp-address { align-self: end; }
        .pp-env__feat { align-self: end; }
        .pp-call--big { flex-basis: 100%; }
    }
    @media (min-width: 900px) {
        .pp-cityrow { grid-template-columns: minmax(0,1fr) auto; align-items: center; gap: 40px; padding: 30px 40px; border: 1px solid var(--pp-line); border-radius: 4px; background: var(--pp-paper); box-shadow: 0 20px 34px -28px rgba(59,53,80,.45); }
        .pp-cityrow .pp-marks--few { justify-content: flex-end; }
        .pp-sheet__grid { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .pp-marks { grid-template-columns: repeat(4,minmax(0,1fr)); }
    }
    @media (min-width: 1024px) {
        .pp-env__in { grid-template-columns: minmax(0,1fr) minmax(0,15rem); gap: 22px 40px; padding: 30px 36px 34px; }
        .pp-letters { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 28px; }
        .pp-letters > li:last-child:nth-child(odd) { grid-column: auto; }
        .pp-letters > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .pp-letters > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .pp-cta__in { grid-template-columns: minmax(0,1fr) minmax(0,15rem); gap: 56px; padding: 44px 56px; }
    }
    @media (min-width: 1100px) {
        .pp-cards--3 { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .pp-cards--3 > li:last-child:nth-child(odd) { grid-column: auto; }
    }
    @media (min-width: 1200px) {
        .pp-hero__grid { grid-template-columns: minmax(0,8.2fr) minmax(0,3.8fr); gap: 44px; align-items: stretch; }
        .pp-hero__grid--solo { grid-template-columns: minmax(0,1fr); max-width: 900px; }
        .pp-pile { padding-top: 4px; }
        .pp-pile__list { grid-template-columns: repeat(2,minmax(0,1fr)); gap: 0 14px; }
        .pp-pile__item:nth-child(1) { transform: rotate(-5deg) translate(6px, 6px); z-index: 1; }
        .pp-pile__item:nth-child(2) { grid-row: span 2; margin-top: 64px; transform: rotate(4deg) translateX(-10px); z-index: 2; }
        .pp-pile__item:nth-child(3) { margin-top: 14px; transform: rotate(-1.5deg) translateX(18px); z-index: 3; }
        .pp-pile .pp-stamp__in { min-height: 232px; }
        .pp-sheet__grid { grid-template-columns: repeat(var(--pp-cols, 6),minmax(0,1fr)); }
        .pp-marks { grid-template-columns: repeat(var(--pp-cols, 6),minmax(0,1fr)); }
    }
    @media (min-width: 1024px) and (max-width: 1199.98px) {
        .pp-nav { gap: 18px; }
        .pp-letter__acts .pp-call { gap: 6px; padding: 0 8px; font-size: 16px; }
        .pp-letter__acts .pp-sq { width: 42px; }
        .pp-letter__acts .pp-sq--wa + .pp-sq { display: none; }
    }
    @media (max-width: 1023.98px) {
        .pp-nav { display: none; }
        .pp-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-posta-pulu #mobile-menu { display: block; z-index: 60; }
        .theme-posta-pulu #mobile-backdrop { z-index: 55; }
        .theme-posta-pulu #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 760px) {
        html.theme-posta-pulu { scroll-padding-bottom: 84px; }
        .pp-home { padding-bottom: 76px; }
        .pp-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .pp-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(250,245,233,.97); border-top: 1px solid var(--pp-line-2); box-shadow: 0 -12px 28px -18px rgba(36,33,46,.45); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .pp-dock__bar::before { content: ""; position: absolute; left: 0; right: 0; top: -5px; height: 5px; background: var(--pp-stripes), #fbf6ea; }
        .pp-dock.is-on .pp-dock__bar { transform: none; visibility: visible; }
        .pp-dock__bar .pp-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 639.98px) {
        .pp-wrap { padding-inline: 16px; }
        .pp-header__row { height: 60px; gap: 10px; }
        .pp-brand { gap: 10px; }
        .pp-brand__name { max-width: 46vw; font-size: 18px; }
        .pp-btn--sm { min-height: 44px; padding: 0 12px; }
        .pp-hero { padding: 26px 0 50px; }
        .pp-h1 { font-size: clamp(29px, 8.4vw, 36px); }
        .pp-lede { font-size: 16.5px; }
        .pp-hero__grid { margin-top: 22px; gap: 34px; }
        .pp-airmail { border-width: 9px; }
        .pp-airlabel { font-size: 11.5px; padding: 4px 9px; letter-spacing: .16em; }
        .pp-env__sender { font-size: 14px; }
        .pp-section { padding: 50px 0; }
        .pp-head { flex-direction: column; align-items: flex-start; gap: 6px; margin-bottom: 22px; }
        .pp-head .pp-link { min-height: 36px; }
        .pp-letter__name { font-size: 22px; }
        .pp-letter__acts .pp-sq--wa + .pp-sq { display: none; }
        .pp-sheet { padding: 12px; }
        .pp-mini__in { min-height: 116px; padding: 10px; }
        .pp-mini__den b { font-size: 30px; }
        .pp-mini__name { font-size: 15.5px; }
        .pp-mark .pp-pm { width: 112px; height: 112px; }
        .pp-mark__name { font-size: 15.5px; }
        .pp-cta__row .pp-btn { width: 100%; }
        .pp-cta-sec { padding-top: 8px; }
        .pp-waves--cta { display: none; }
    }
    @media (max-width: 419.98px) {
        .pp-cta-long { display: none; }
        .pp-cta-short { display: inline; }
        .pp-call { font-size: 16px; }
        .pp-call--big { font-size: 20px; }
        .pp-card { grid-template-columns: minmax(0,1fr) minmax(0,5.5rem); gap: 0 12px; padding: 18px 14px; }
        .pp-card__addr { padding-left: 10px; }
        .pp-card__line .pp-hand { font-size: 18px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-posta-pulu { scroll-behavior: auto; }
        .theme-posta-pulu #mobile-menu { transition: none; }
        .pp-letter, .pp-mini, .pp-pile__item, .pp-mark .pp-pm, .pp-nav a::after, .pp-btn, .pp-call, .pp-link svg, .pp-dock__bar { transition: none !important; }
        .pp-letter:hover, .pp-letter:focus-within, .pp-mini:hover, .pp-link:hover svg { transform: none; }
    }
</style>
