<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow:wght@400;500;600;700&family=Oswald:wght@400;500;600;700&family=IBM+Plex+Mono:wght@400;500;600&display=swap" rel="stylesheet">
<style>
    html.theme-departure-board body > header,
    html.theme-departure-board body > footer { display: none !important; }
    html.theme-departure-board body > main { background: #eef1ef; color: #0e1a1d; }

    /* ── Temel ───────────────────────────────────────────────────────── */
    /* Renk/tipografi etiketleri html taşıyor: .dep-shell-content gibi .dep dışındaki
       seçiciler de bu değişkenleri kullanabilsin (CSS değişkenleri yalnız aşağı iner). */
    html.theme-departure-board {
        --slate: #0c1618; --slate2: #13262b; --slate3: #1b333a;
        --paper: #eef1ef; --slab: #f7f9f8; --white: #ffffff; --stub: #f6f2e8;
        --amber: #f0a12b; --amber-deep: #b96c11; --mint: #35c0a8; --signal: #d7452f;
        --ink: #0e1a1d; --muted: #5b6f73; --line: #d3dcd9;
        --mono: "IBM Plex Mono", ui-monospace, monospace;
        --sign: Oswald, "Arial Narrow", sans-serif;
    }
    .dep {
        color: var(--ink); background: var(--paper);
        font: 15px/1.6 Barlow, "Segoe UI", sans-serif; letter-spacing: .005em;
    }
    .dep * { box-sizing: border-box; }
    /* Varsayılan link rengi: sıfır özgüllük (:where) — böylece tek sınıflı .dep-*
       bileşen kuralları kendi renklerini bozmadan korur (.dep a yerine). */
    :where(.dep) a { color: inherit; }
    .dep :is(h1,h2,h3,h4) { margin: 0; font-family: var(--sign); font-weight: 600; letter-spacing: -.005em; }
    .dep p { margin: 0; }
    .dep-wrap { width: min(100% - 44px, 1300px); margin-inline: auto; }
    .dep-kicker { display: inline-flex; align-items: center; gap: 9px; color: var(--amber-deep); font: 600 10px/1 var(--mono); letter-spacing: .24em; text-transform: uppercase; }
    .dep-kicker::before { content: ""; width: 8px; height: 8px; background: var(--amber); }
    .dep-kicker--light { color: var(--amber); }
    .dep-display { font-family: var(--sign); font-size: clamp(38px,6.6vw,84px); font-weight: 700; line-height: .95; letter-spacing: -.015em; text-transform: uppercase; }
    .dep-h2 { font-size: clamp(25px,3.2vw,40px); font-weight: 600; line-height: 1.03; text-transform: uppercase; }
    .dep-h3 { font-size: 21px; font-weight: 600; line-height: 1.18; }
    .dep-code { font: 500 10px/1.4 var(--mono); letter-spacing: .14em; text-transform: uppercase; color: var(--muted); }
    .dep-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; min-height: 46px; padding: 12px 20px; border: 1px solid var(--amber); background: var(--amber); color: #14201f !important; font: 600 12px/1 var(--sign); letter-spacing: .16em; text-transform: uppercase; text-decoration: none; cursor: pointer; transition: transform .18s ease, box-shadow .18s ease, background .18s ease; }
    .dep-btn:hover { background: #ffbb4d; transform: translate(-2px,-2px); box-shadow: 4px 4px 0 var(--slate); }
    .dep-btn--ghost { background: transparent; border-color: currentColor; color: var(--ink) !important; }
    .dep-btn--ghost:hover { background: var(--ink); border-color: var(--ink); color: #fff !important; box-shadow: 4px 4px 0 rgba(12,22,24,.25); }
    .dep-btn--on-dark { border-color: rgba(255,255,255,.4); color: #fff !important; background: transparent; }
    .dep-btn--on-dark:hover { background: #fff; color: var(--slate) !important; border-color: #fff; box-shadow: 4px 4px 0 rgba(0,0,0,.35); }
    .dep-hazard { height: 8px; background: repeating-linear-gradient(115deg,var(--amber) 0 16px,var(--slate) 16px 32px); }

    /* ── Üst bar + bant ──────────────────────────────────────────────── */
    .dep-header { position: relative; z-index: 5; background: var(--slate); color: #eef1ef; }
    .dep-header__strip { border-bottom: 1px solid rgba(255,255,255,.13); font: 500 10px/1 var(--mono); letter-spacing: .18em; text-transform: uppercase; color: #9fb4b6; }
    .dep-header__strip .dep-wrap { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding-block: 9px; }
    .dep-header__strip b { color: var(--mint); font-weight: 500; }
    .dep-header__main { display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 18px 26px; padding-block: 18px; }
    .dep-brand { display: flex; align-items: center; gap: 13px; text-decoration: none; }
    .dep-brand__mark { display: grid; place-items: center; width: 42px; height: 42px; background: var(--amber); color: var(--slate); font: 700 15px/1 var(--sign); box-shadow: 4px 4px 0 rgba(255,255,255,.14); }
    .dep-brand__text { font: 600 clamp(19px,2.3vw,27px)/1 var(--sign); letter-spacing: .01em; text-transform: uppercase; color: #fff; }
    .dep-brand__text span { color: var(--amber); }
    .dep-brand__sub { display: block; margin-top: 5px; font: 400 9px/1 var(--mono); letter-spacing: .26em; text-transform: uppercase; color: #8ba3a6; }
    .dep-nav { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 26px; font: 500 12px/1 var(--sign); letter-spacing: .15em; text-transform: uppercase; }
    .dep-nav a { position: relative; padding-block: 8px; color: #eef1ef; text-decoration: none; }
    .dep-nav a:not(.dep-nav__cta)::after { content: ""; position: absolute; left: 0; bottom: 0; width: 100%; height: 2px; background: var(--amber); transform: scaleX(0); transform-origin: left; transition: transform .22s ease; }
    .dep-nav a:not(.dep-nav__cta):hover::after { transform: scaleX(1); }
    .dep-nav__cta { border: 1px solid var(--amber); padding: 10px 14px !important; color: var(--amber) !important; }
    .dep-nav__cta:hover { background: var(--amber); color: var(--slate) !important; }
    .dep-ticker { display: flex; align-items: center; overflow: hidden; background: var(--slate2); border-block: 1px solid rgba(255,255,255,.1); color: var(--amber); font: 500 10px/1 var(--mono); letter-spacing: .22em; text-transform: uppercase; }
    .dep-ticker__label { flex: none; z-index: 1; padding: 9px 14px; background: var(--amber); color: var(--slate); font-family: var(--sign); font-size: 11px; letter-spacing: .18em; }
    .dep-ticker__track { display: flex; width: max-content; gap: 34px; padding-left: 34px; animation: dep-roll 38s linear infinite; }
    .dep-ticker__track span { display: flex; gap: 34px; white-space: nowrap; }
    .dep-ticker__track i { color: var(--mint); font-style: normal; }
    @keyframes dep-roll { to { transform: translateX(-50%); } }

    /* ── Hero ────────────────────────────────────────────────────────── */
    .dep-hero { position: relative; isolation: isolate; overflow: hidden; background: var(--slate); color: #fff; }
    .dep-hero::before { content: ""; position: absolute; z-index:-1; inset: 0; background: radial-gradient(560px 320px at 12% -8%, rgba(240,161,43,.3), transparent 68%), radial-gradient(520px 300px at 78% 4%, rgba(53,192,168,.22), transparent 70%), linear-gradient(160deg,#0c1618 0%,#12292f 62%,#0c1618 100%); }
    .dep-hero::after { content: ""; position: absolute; z-index:-1; inset: 0; opacity: .5; background: repeating-linear-gradient(90deg, rgba(255,255,255,.05) 0 1px, transparent 1px 74px), repeating-linear-gradient(0deg, rgba(255,255,255,.04) 0 1px, transparent 1px 74px); mask-image: linear-gradient(#000,transparent 78%); }
    .dep-hero .dep-wrap { position: relative; padding-block: clamp(46px,6vw,74px) 34px; }
    .dep-hero__grid { display: grid; grid-template-columns: minmax(0,1.05fr) minmax(0,.95fr); gap: clamp(26px,4vw,56px); align-items: start; }
    .dep-hero__copy { max-width: 640px; }
    .dep-hero h1 { margin: 20px 0 18px; color: #fff; text-wrap: balance; }
    .dep-hero h1 em { font-style: normal; color: var(--amber); }
    .dep-hero__lede { max-width: 520px; color: #b9cbcd; font-size: 16.5px; }
    .dep-gate { display: flex; margin-top: 30px; border: 2px solid rgba(255,255,255,.22); background: #fff; box-shadow: 0 24px 48px rgba(0,0,0,.32); }
    .dep-gate__icon { display: grid; place-items: center; width: 52px; background: var(--slate2); color: var(--amber); font: 500 15px var(--mono); }
    .dep-gate input { flex: 1; min-width: 0; padding: 17px 18px; border: 0; background: #fff; color: var(--ink); font: 400 15px Barlow,sans-serif; outline: none; }
    .dep-gate input::placeholder { color: #7c8f92; }
    .dep-gate button { flex: none; border: 0; padding: 0 24px; background: var(--amber); color: var(--slate); font: 600 12px var(--sign); letter-spacing: .16em; text-transform: uppercase; cursor: pointer; transition: background .2s ease; }
    .dep-gate button:hover { background: #ffbb4d; }
    .dep-hero__quick { display: flex; flex-wrap: wrap; align-items: center; gap: 10px 18px; margin-top: 22px; color: #9fb4b6; font: 500 10px var(--mono); letter-spacing: .16em; text-transform: uppercase; }
    .dep-hero__quick a { color: #eef1ef; text-decoration: none; border-bottom: 1px solid rgba(255,255,255,.3); padding-bottom: 2px; }
    .dep-hero__quick a:hover { color: var(--amber); border-color: var(--amber); }

    /* ── Kalkış paneli (split-flap) ──────────────────────────────────── */
    .dep-board { position: relative; z-index: 2; margin-top: clamp(38px,5vw,62px); border: 1px solid rgba(255,255,255,.16); background: #050d0e; box-shadow: inset 0 1px 0 rgba(255,255,255,.08), 0 -30px 60px rgba(0,0,0,.35); perspective: 900px; }
    .dep-board__head { display: grid; grid-template-columns: 78px minmax(0,1.5fr) minmax(0,1fr) 118px 116px; gap: 14px; padding: 11px 18px; border-bottom: 1px solid rgba(255,255,255,.14); color: #7d9396; font: 500 9.5px var(--mono); letter-spacing: .22em; text-transform: uppercase; }
    .dep-board__row { display: grid; grid-template-columns: 78px minmax(0,1.5fr) minmax(0,1fr) 118px 116px; gap: 14px; align-items: center; padding: 13px 18px; border-bottom: 1px solid rgba(255,255,255,.07); color: #f5d9a8; text-decoration: none; transition: background .2s ease; animation: dep-flip .5s ease both; animation-delay: calc(var(--i,0) * 90ms); }
    .dep-board__row:last-child { border-bottom: 0; }
    .dep-board__row:hover { background: #0d1e22; }
    .dep-board__row:hover .dep-board__name { color: #fff; }
    .dep-board__cell { position: relative; overflow: hidden; font: 500 13px/1.35 var(--mono); letter-spacing: .04em; white-space: nowrap; text-overflow: ellipsis; text-transform: uppercase; }
    .dep-board__cell::after { content: ""; position: absolute; left: 0; right: 0; top: 50%; height: 1px; background: rgba(0,0,0,.55); }
    .dep-board__plat { color: var(--amber); font-family: var(--sign); font-size: 19px; font-weight: 600; letter-spacing: .04em; }
    .dep-board__name { min-width: 0; overflow: hidden; color: #f5d9a8; font: 600 clamp(15px,1.5vw,21px)/1.2 var(--sign); letter-spacing: .01em; text-overflow: ellipsis; white-space: nowrap; text-transform: uppercase; transition: color .2s ease; }
    .dep-board__tag { color: #9fb4b6; }
    .dep-board__state { display: inline-flex; align-items: center; gap: 7px; color: var(--mint); }
    .dep-board__state::before { content: ""; width: 7px; height: 7px; border-radius: 50%; background: var(--mint); box-shadow: 0 0 0 4px rgba(53,192,168,.16); animation: dep-blink 2.4s ease-in-out infinite; }
    .dep-board__state--new { color: var(--amber); }
    .dep-board__state--new::before { background: var(--amber); box-shadow: 0 0 0 4px rgba(240,161,43,.16); }
    .dep-board__foot { display: flex; flex-wrap: wrap; justify-content: space-between; gap: 10px; padding: 11px 18px; background: #0a1415; color: #63797c; font: 500 9.5px var(--mono); letter-spacing: .2em; text-transform: uppercase; }
    @keyframes dep-flip { from { transform: rotateX(-72deg); opacity: 0; } to { transform: rotateX(0); opacity: 1; } }
    @keyframes dep-blink { 50% { opacity: .35; } }

    /* ── İstatistik bandı ────────────────────────────────────────────── */
    .dep-stats { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); margin-top: 26px; border: 1px solid var(--line); border-left: 0; background: var(--white); }
    .dep-stats > div { padding: 20px 22px; border-left: 1px solid var(--line); }
    .dep-stats strong { display: block; color: var(--amber-deep); font: 600 clamp(27px,3vw,38px)/1 var(--sign); }
    .dep-stats span { display: block; margin-top: 7px; font: 500 9.5px var(--mono); letter-spacing: .18em; text-transform: uppercase; color: var(--muted); }

    /* ── Bölimler ────────────────────────────────────────────────────── */
    .dep-section { padding-block: clamp(48px,6vw,74px); }
    .dep-section--slab { background: var(--slab); border-block: 1px solid var(--line); }
    .dep-section--dark { background: var(--slate); color: #eef1ef; }
    .dep-section__head { display: flex; align-items: flex-end; justify-content: space-between; flex-wrap: wrap; gap: 14px 22px; padding-bottom: 16px; margin-bottom: 26px; border-bottom: 2px solid var(--ink); }
    .dep-section--dark .dep-section__head { border-color: rgba(255,255,255,.3); }
    .dep-section__head p { max-width: 520px; margin-top: 9px; color: var(--muted); font-size: 14px; }
    .dep-section--dark .dep-section__head p { color: #a8bdc0; }
    .dep-section__more { display: inline-flex; align-items: center; gap: 8px; color: var(--amber-deep); font: 600 11px var(--sign); letter-spacing: .18em; text-transform: uppercase; text-decoration: none; white-space: nowrap; }
    .dep-section__more:hover { color: var(--ink); }
    .dep-section--dark .dep-section__more { color: var(--amber); }
    .dep-grid { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 20px; }
    .dep-grid--2 { grid-template-columns: repeat(2,minmax(0,1fr)); }
    .dep-empty { padding: 34px 22px; border: 1px dashed var(--line); background: var(--white); color: var(--muted); font-size: 14px; }
    .dep-empty a { color: var(--amber-deep); font-weight: 600; }

    /* ── Bilet kartı ─────────────────────────────────────────────────── */
    .dep-card { --notch: var(--paper); position: relative; display: grid; grid-template-columns: 74px minmax(0,1fr); min-width: 0; background: var(--white); border: 1px solid var(--line); transition: transform .2s ease, box-shadow .2s ease, border-color .2s ease; }
    .dep-section--slab .dep-card, .dep-slab .dep-card { --notch: var(--slab); }
    .dep-card:hover { transform: translateY(-4px); border-color: var(--amber); box-shadow: 0 16px 32px rgba(14,26,29,.12); }
    .dep-card__stub { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: space-between; gap: 12px; padding: 14px 8px; background: var(--stub); border-right: 2px dashed #cfc7b4; }
    .dep-card__stub::before, .dep-card__stub::after { content: ""; position: absolute; right: -10px; width: 18px; height: 18px; border-radius: 50%; background: var(--notch); }
    .dep-card__stub::before { top: -10px; } .dep-card__stub::after { bottom: -10px; }
    .dep-card__plat { font: 600 17px/1 var(--sign); color: var(--amber-deep); }
    .dep-card__vert { flex: 1; writing-mode: vertical-rl; text-orientation: mixed; font: 500 9px var(--mono); letter-spacing: .26em; text-transform: uppercase; color: #8b8168; }
    .dep-card__barcode { width: 100%; height: 28px; background: repeating-linear-gradient(90deg,#12262b 0 2px,transparent 2px 4px,#12262b 4px 5px,transparent 5px 9px,#12262b 9px 12px,transparent 12px 14px); opacity: .72; }
    .dep-card__media { position: relative; display: flex; align-items: flex-end; height: 124px; overflow: hidden; background: linear-gradient(135deg,var(--slate3),var(--slate)); color: #fff; }
    .dep-card__media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .dep-card__media::after { content: ""; position: absolute; inset: 0; background: linear-gradient(0deg,rgba(5,13,14,.78),transparent 62%); }
    .dep-card__initial { position: relative; z-index: 1; padding: 10px 16px; font: 700 58px/.8 var(--sign); color: rgba(255,255,255,.34); }
    .dep-card__badge { position: absolute; z-index: 2; top: 10px; left: 12px; padding: 4px 8px; background: var(--amber); color: var(--slate); font: 600 9px var(--mono); letter-spacing: .16em; text-transform: uppercase; }
    .dep-card__badge--mint { background: var(--mint); }
    .dep-card__body { padding: 16px 18px 18px; }
    .dep-card__body > .dep-code { color: var(--amber-deep); }
    .dep-card h3 { margin: 8px 0 7px; }
    .dep-card h3 a { text-decoration: none; }
    .dep-card h3 a:hover { color: var(--amber-deep); }
    .dep-card__text { color: var(--muted); font-size: 13.5px; line-height: 1.62; }
    .dep-card__foot { display: flex; align-items: center; justify-content: space-between; gap: 10px; margin-top: 15px; padding-top: 12px; border-top: 1px dashed var(--line); font: 500 10px var(--mono); letter-spacing: .1em; text-transform: uppercase; color: var(--muted); }
    .dep-card__foot a { display: inline-flex; align-items: center; gap: 6px; color: var(--ink); font-family: var(--sign); font-size: 11px; font-weight: 600; letter-spacing: .14em; text-decoration: none; }
    .dep-card__foot a:hover { color: var(--amber-deep); }

    /* ── İstasyon / kategori tahtaları ───────────────────────────────── */
    .dep-dest-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 12px; }
    .dep-dest { position: relative; display: flex; flex-direction: column; justify-content: space-between; min-height: 132px; padding: 16px 17px; overflow: hidden; background: var(--slate2); color: #eef1ef; text-decoration: none; transition: background .22s ease, transform .22s ease; }
    .dep-dest:hover { background: var(--slate3); transform: translateY(-3px); color: #fff; }
    .dep-dest::after { content: ""; position: absolute; left: 0; bottom: 0; width: 100%; height: 3px; background: var(--amber); transform: scaleX(.22); transform-origin: left; transition: transform .26s ease; }
    .dep-dest:hover::after { transform: scaleX(1); }
    .dep-dest__no { font: 500 9.5px var(--mono); letter-spacing: .2em; color: var(--amber); }
    .dep-dest__name { margin-top: 14px; font: 600 20px/1.15 var(--sign); text-transform: uppercase; letter-spacing: 0; }
    .dep-dest__count { margin-top: 8px; font: 400 11px var(--mono); color: #9fb4b6; }

    /* ── Vagon listesi (satır) ───────────────────────────────────────── */
    .dep-panel { border: 1px solid var(--line); background: var(--white); }
    .dep-panel__head { display: flex; align-items: baseline; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding: 16px 19px; border-bottom: 2px solid var(--ink); }
    .dep-panel__head h2 { font-size: 21px; font-weight: 600; text-transform: uppercase; }
    .dep-panel__head .dep-code { color: var(--amber-deep); }
    .dep-row { display: grid; grid-template-columns: 62px minmax(0,1fr) auto; gap: 17px; align-items: center; padding: 16px 18px; border-bottom: 1px solid var(--line); transition: background .18s ease; }
    .dep-row:last-child { border-bottom: 0; }
    .dep-row:hover { background: #fbfaf6; }
    .dep-row__mark { display: grid; place-items: center; width: 62px; height: 62px; overflow: hidden; background: var(--stub); border: 1px solid #e2dbc9; color: var(--amber-deep); font: 600 22px var(--sign); text-decoration: none; }
    .dep-row__mark img { width: 100%; height: 100%; object-fit: cover; }
    .dep-row__go { display: inline-flex; align-items: center; gap: 7px; padding: 9px 12px; border: 1px solid var(--ink); color: var(--ink); font: 600 10px var(--sign); letter-spacing: .16em; text-transform: uppercase; text-decoration: none; white-space: nowrap; }
    .dep-row__go:hover { background: var(--amber); border-color: var(--amber); }
    .dep-link-list a { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 12px 18px; border-bottom: 1px solid var(--line); color: var(--ink); font-size: 13.5px; font-weight: 500; text-decoration: none; }
    .dep-link-list a:last-child { border-bottom: 0; }
    .dep-link-list a:hover { background: #fbfaf6; color: var(--amber-deep); }
    .dep-link-list b { font: 500 11px var(--mono); color: var(--muted); }

    /* ── Şehir tarife tablosu ────────────────────────────────────────── */
    .dep-timetable { border: 1px solid var(--line); background: var(--white); }
    .dep-timetable__row { display: grid; grid-template-columns: 58px minmax(0,1fr) auto auto; gap: 14px; align-items: center; padding: 14px 17px; border-bottom: 1px solid var(--line); text-decoration: none; transition: background .18s ease, box-shadow .18s ease; }
    .dep-timetable__row:last-child { border-bottom: 0; }
    .dep-timetable__row:hover { background: var(--slate); color: #fff; box-shadow: inset 4px 0 0 var(--amber); }
    .dep-timetable__row:hover .dep-code, .dep-timetable__row:hover b { color: var(--amber); }
    .dep-timetable__row strong { font: 600 17px/1 var(--sign); text-transform: uppercase; }
    .dep-timetable__row b { font: 500 12px var(--mono); color: var(--amber-deep); }
    .dep-timetable__row i { font-style: normal; font: 500 11px var(--mono); letter-spacing: .12em; opacity: .8; }

    /* ── CTA / promosyon ─────────────────────────────────────────────── */
    .dep-cta { display: grid; grid-template-columns: minmax(0,1.15fr) minmax(0,.85fr); overflow: hidden; background: var(--slate); color: #fff; }
    .dep-cta__copy { padding: clamp(28px,4.5vw,58px); }
    .dep-cta h2 { margin: 16px 0 14px; font-size: clamp(27px,3.6vw,46px); font-weight: 600; line-height: 1.02; text-transform: uppercase; color: #fff; }
    .dep-cta p { max-width: 460px; color: #a8bdc0; }
    .dep-cta .dep-btn { margin-top: 26px; }
    .dep-cta__art { position: relative; min-height: 240px; background: repeating-linear-gradient(115deg,rgba(255,255,255,.05) 0 2px,transparent 2px 22px), linear-gradient(140deg,var(--slate3),#061012); }
    .dep-cta__art::before { content: ""; position: absolute; inset: 12% 14%; border: 2px dashed rgba(240,161,43,.55); }
    .dep-cta__art::after { content: "PERON"; position: absolute; left: 50%; top: 50%; transform: translate(-50%,-50%); color: rgba(240,161,43,.85); font: 700 clamp(44px,7vw,96px)/1 var(--sign); letter-spacing: .12em; }
    .dep-promo { position: relative; overflow: hidden; padding: 24px; background: var(--slate2); color: #eef1ef; }
    .dep-promo::before { content: ""; position: absolute; inset: 0 0 auto; height: 6px; background: repeating-linear-gradient(115deg,var(--amber) 0 12px,transparent 12px 24px); }
    .dep-promo h2 { margin: 12px 0 10px; font-size: 23px; font-weight: 600; text-transform: uppercase; color: #fff; }
    .dep-promo p { margin-bottom: 18px; color: #a8bdc0; font-size: 13px; }

    /* ── Alt sayfa kabuğu ───────────────────────────────────────────── */
    .dep-page { min-height: 58vh; }
    .dep-page__hero { background: var(--slate); color: #fff; }
    .dep-page__hero::before { content: ""; display: block; height: 6px; background: repeating-linear-gradient(115deg,var(--amber) 0 16px,var(--slate) 16px 32px); }
    .dep-page__hero .dep-wrap { padding-block: 38px 44px; }
    .dep-page__hero h1 { margin: 14px 0 12px; color: #fff; }
    .dep-page__hero p { max-width: 660px; color: #a8bdc0; }
    .dep-crumb { display: flex; flex-wrap: wrap; gap: 9px; margin-bottom: 22px; color: #8ba3a6; font: 500 10px var(--mono); letter-spacing: .16em; text-transform: uppercase; }
    .dep-crumb a { color: var(--amber); text-decoration: none; }
    .dep-crumb a:hover { text-decoration: underline; }
    .dep-page__body { padding-block: 32px 72px; }
    .dep-filter { display: flex; align-items: flex-end; flex-wrap: wrap; gap: 12px; padding: 16px; border: 1px solid var(--line); border-top: 3px solid var(--amber); background: var(--white); }
    .dep-filter label { display: grid; gap: 6px; flex: 1 1 190px; color: var(--muted); font: 500 9.5px var(--mono); letter-spacing: .18em; text-transform: uppercase; }
    .dep-filter :is(input,select) { min-width: 0; width: 100%; padding: 11px 12px; border: 1px solid var(--line); border-radius: 0; background: #fff; color: var(--ink); font: 400 14px Barlow,sans-serif; }
    .dep-filter :is(input,select):focus { outline: 2px solid var(--amber); outline-offset: -1px; }
    .dep-filter button { flex: none; min-height: 44px; padding: 11px 20px; border: 0; background: var(--ink); color: #fff; font: 600 11px var(--sign); letter-spacing: .18em; text-transform: uppercase; cursor: pointer; }
    .dep-filter button:hover { background: var(--amber-deep); }
    .dep-filter > a { align-self: center; color: var(--amber-deep); font: 500 10px var(--mono); letter-spacing: .12em; text-transform: uppercase; }
    .dep-columns { display: grid; grid-template-columns: minmax(0,1fr) 300px; gap: 24px; align-items: start; margin-top: 24px; }
    .dep-main, .dep-side { display: grid; gap: 22px; min-width: 0; align-content: start; }
    .dep-copy { padding: 19px; font-size: 14px; }
    .dep-copy p + p { margin-top: 13px; }
    .dep-pagination { padding: 14px 16px; border-top: 2px solid var(--ink); }
    .dep-pagination nav { display: flex; flex-wrap: wrap; gap: 6px; align-items: center; font: 500 12px var(--mono); }
    .dep-pagination span, .dep-pagination a { padding: 8px 12px; border: 1px solid var(--line); text-decoration: none; }
    .dep-pagination span[aria-current], .dep-pagination [aria-current] span { background: var(--ink); color: #fff; border-color: var(--ink); }
    .dep-pagination svg { width: 14px; height: 14px; }
    .dep-info { max-width: 940px; margin-inline: auto; padding-block: 32px 72px; }
    .dep-info .dep-panel { border-top: 3px solid var(--amber); padding: clamp(22px,4vw,52px); }
    .dep-article { padding: clamp(20px,3.4vw,46px); border-top: 3px solid var(--amber); }
    .dep-lead { display: grid; grid-template-columns: 1fr; }
    .dep-lead--split { grid-template-columns: minmax(0,1fr) 360px; }
    .dep-lead img { width: 100%; height: 100%; min-height: 230px; object-fit: cover; display: block; }
    .dep-article__foot { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 12px; margin-top: 26px; padding-top: 16px; border-top: 1px dashed var(--line); }
    .dep-article__foot > div { flex-wrap: wrap; }
    .dep-faq { padding: 13px 0; border-bottom: 1px dashed var(--line); }
    .dep-faq summary { cursor: pointer; font: 600 16px/1.3 var(--sign); text-transform: uppercase; }
    .dep-faq summary:hover { color: var(--amber-deep); }
    .dep-faq p { margin-top: 9px; color: var(--muted); font-size: 14px; }
    .dep-article h1 { font-size: clamp(30px,4.6vw,58px); font-weight: 700; text-transform: uppercase; line-height: 1; }
    .dep-article__image { width: 100%; max-height: 460px; margin-top: 22px; object-fit: cover; }
    .dep-prose { margin-top: 24px; padding-top: 22px; border-top: 1px solid var(--line); color: #2b3d40; font-size: 15.5px; line-height: 1.85; }
    .dep-prose :is(h2,h3) { margin: 26px 0 11px; font-size: 24px; font-weight: 600; text-transform: uppercase; }
    .dep-prose p + p { margin-top: 15px; }
    .dep-prose :is(ol,ul) { margin: 15px 0; padding-left: 26px; list-style: revert; }
    .dep-prose a { color: var(--amber-deep); text-decoration: underline; }
    .dep-prose img { max-width: 100%; height: auto; }
    .dep-prose table { width: 100%; border-collapse: collapse; }
    .dep-prose :is(th,td) { border: 1px solid var(--line); padding: 9px; }
    .dep-prose th { background: var(--slab); font: 600 12px var(--mono); text-transform: uppercase; }
    .dep-note { margin-top: 20px; padding: 14px 16px; border: 1px solid var(--line); border-left: 4px solid var(--amber); background: var(--slab); color: #2b3d40; font-size: 14px; }
    .dep-note + .dep-note { margin-top: 10px; }
    .dep-contact-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 16px; margin-top: 22px; }
    .dep-contact-form label { display: grid; gap: 7px; color: var(--muted); font: 500 9.5px var(--mono); letter-spacing: .16em; text-transform: uppercase; }
    .dep-contact-form :is(input,select,textarea) { width: 100%; min-width: 0; padding: 12px; border: 1px solid var(--line); border-radius: 0; background: #fff; color: var(--ink); font: 400 14px Barlow,sans-serif; letter-spacing: 0; text-transform: none; }
    .dep-contact-form :is(input,select,textarea):focus { outline: 2px solid var(--amber); outline-offset: -1px; }
    .dep-contact-form .wide { grid-column: 1/-1; }
    .dep-plan { display: flex; flex-direction: column; border: 1px solid var(--line); background: var(--white); }
    .dep-plan--featured { border-color: var(--amber); box-shadow: 0 0 0 4px rgba(240,161,43,.14); }
    .dep-plan__head { padding: 22px; border-bottom: 2px dashed var(--line); background: var(--stub); }
    .dep-plan__head h2 { margin-top: 10px; font-size: 26px; font-weight: 600; text-transform: uppercase; }
    .dep-plan__price { padding: 22px; font: 600 clamp(32px,4vw,46px)/1 var(--sign); color: var(--amber-deep); }
    .dep-plan__price small { display: block; margin-top: 8px; font: 500 10px var(--mono); letter-spacing: .18em; text-transform: uppercase; color: var(--muted); }
    .dep-plan ul { flex: 1; margin: 0; padding: 0 22px 22px 40px; color: var(--muted); font-size: 13.5px; line-height: 1.9; list-style: none; }
    .dep-plan li { position: relative; }
    .dep-plan li::before { content: "▸"; position: absolute; left: -18px; color: var(--amber); }
    .dep-plan li strong { color: var(--ink); }
    .dep-plan .dep-btn { align-self: flex-start; margin: 0 22px 24px; }

    /* ── Firma detayı ───────────────────────────────────────────────── */
    .dep-detail { background: var(--paper); }
    .dep-detail__cover { position: relative; overflow: hidden; background: var(--slate); color: #fff; }
    .dep-detail__cover > img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; opacity: .34; }
    .dep-detail__cover::after { content: ""; position: absolute; inset: 0; background: linear-gradient(94deg,rgba(6,16,18,.96) 8%,rgba(6,16,18,.35) 78%); }
    .dep-detail__cover .dep-wrap { position: relative; z-index: 1; padding-block: 34px 44px; }
    .dep-detail__cover h1 { max-width: 880px; margin: 14px 0 14px; color: #fff; }
    .dep-detail__cover p { max-width: 620px; color: #b9cbcd; }
    .dep-detail__actions { display: flex; flex-wrap: wrap; gap: 11px; margin-top: 26px; }
    .dep-detail__vip { display: inline-flex; align-items: center; gap: 9px; padding: 12px 18px; border: 1px dashed var(--amber); color: var(--amber); font: 600 11px/1 var(--mono); letter-spacing: .18em; text-transform: uppercase; }
    .dep-detail__vip::before { content: "★"; letter-spacing: 0; }
    .dep-ticketbar { position: relative; z-index: 2; display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); border: 1px solid var(--line); border-top: 3px solid var(--amber); background: var(--white); }
    .dep-detail .dep-ticketbar { margin-top: -26px; }
    .dep-ticketbar > div { padding: 16px 18px; border-left: 1px dashed var(--line); }
    .dep-ticketbar > div:first-child { border-left: 0; }
    .dep-ticketbar dt { color: var(--muted); font: 500 9.5px var(--mono); letter-spacing: .18em; text-transform: uppercase; }
    .dep-ticketbar dd { margin: 7px 0 0; font: 600 16px/1.25 var(--sign); text-transform: uppercase; overflow-wrap: anywhere; }
    .dep-ticketbar dd a { text-decoration: none; }
    .dep-ticketbar dd a:hover { color: var(--amber-deep); }
    .dep-detail__body { display: grid; grid-template-columns: minmax(0,1fr) 320px; gap: 24px; align-items: start; padding-block: 32px 68px; }
    .dep-detail__main, .dep-detail__side { display: grid; gap: 22px; min-width: 0; align-content: start; }
    .dep-detail .td-section, .dep-detail .td-side-card { border: 1px solid var(--line); background: var(--white); padding: clamp(18px,2.6vw,30px); }
    .dep-detail .td-section { border-top: 3px solid var(--ink); }
    .dep-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 11px; margin-bottom: 16px; padding-bottom: 14px; border-bottom: 1px dashed var(--line); }
    .dep-detail .td-index { color: var(--amber-deep); font: 600 11px var(--mono); letter-spacing: .2em; }
    .dep-detail .td-section-head h2, .dep-detail .td-side-card h2 { font-size: clamp(20px,2.4vw,27px); font-weight: 600; text-transform: uppercase; }
    .dep-detail .td-side-card h2 { margin-top: 8px; }
    .dep-detail .td-section-head > a, .dep-detail .td-count { margin-left: auto; color: var(--amber-deep); font: 600 10px var(--sign); letter-spacing: .18em; text-transform: uppercase; }
    .dep-detail .td-prose { color: #2b3d40; line-height: 1.8; }
    .dep-detail .td-prose p + p { margin-top: 13px; }
    .dep-detail .td-prose :is(h2,h3) { margin: 20px 0 9px; font-size: 20px; text-transform: uppercase; }
    .dep-detail .td-prose ul { padding-left: 24px; list-style: disc; }
    .dep-detail .td-share { margin-top: 20px; padding-top: 15px; border-top: 1px dashed var(--line); }
    .dep-detail .td-card-grid { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 12px; }
    .dep-detail .td-card { display: block; min-width: 0; overflow: hidden; border: 1px solid var(--line); background: var(--slab); text-decoration: none; transition: border-color .2s ease, transform .2s ease; }
    .dep-detail .td-card:hover { border-color: var(--amber); transform: translateY(-3px); }
    .dep-detail .td-card-image { width: 100%; height: 160px; object-fit: cover; }
    .dep-detail .td-card-body, .dep-detail .td-job { padding: 16px; }
    .dep-detail .td-card small, .dep-detail .td-side-label { color: var(--amber-deep); font: 500 9.5px var(--mono); letter-spacing: .2em; text-transform: uppercase; }
    .dep-detail .td-card h3 { margin: 7px 0 5px; font-size: 18px; font-weight: 600; }
    .dep-detail .td-card p, .dep-detail .td-side-card p { color: var(--muted); font-size: 13px; }
    .dep-detail .td-card strong { display: block; margin-top: 10px; color: var(--amber-deep); font: 600 11px var(--sign); letter-spacing: .16em; text-transform: uppercase; }
    .dep-detail .td-price { display: block; margin-top: 11px; color: var(--ink); font: 600 15px var(--sign); letter-spacing: .04em; }
    .dep-detail .td-gallery { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 8px; }
    .dep-detail .td-gallery img { width: 100%; aspect-ratio: 4/3; object-fit: cover; transition: filter .2s ease; }
    .dep-detail .td-gallery img:hover { filter: saturate(1.2) contrast(1.05); }
    .dep-detail .td-map { aspect-ratio: 16/9; border: 1px solid var(--line); }
    .dep-detail .td-map iframe { width: 100%; height: 100%; border: 0; }
    .dep-detail .td-review { padding: 14px 0; border-bottom: 1px dashed var(--line); }
    .dep-detail .td-review > div { display: flex; justify-content: space-between; gap: 10px; }
    .dep-detail .td-review span, .dep-detail .td-empty { color: var(--muted); font: 500 11px var(--mono); }
    .dep-detail .td-stars { margin: 5px 0; color: var(--amber); letter-spacing: .1em; }
    .dep-detail .td-form-title { margin: 20px 0 11px; font-size: 19px; font-weight: 600; text-transform: uppercase; }
    .dep-detail .td-review-form { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 9px; }
    .dep-detail .td-review-form :is(input,select,textarea) { min-width: 0; padding: 11px; border: 1px solid var(--line); border-radius: 0; background: #fff; font: 400 14px Barlow,sans-serif; }
    .dep-detail .td-review-form textarea { grid-column: 1/-1; }
    .dep-detail .td-review-form button { width: max-content; padding: 11px 18px; border: 0; background: var(--ink); color: #fff; font: 600 11px var(--sign); letter-spacing: .18em; text-transform: uppercase; cursor: pointer; }
    .dep-detail .td-review-form button:hover { background: var(--amber-deep); }
    .dep-detail .td-message { margin-bottom: 13px; padding: 12px; border-left: 3px solid var(--amber); background: var(--stub); font-size: 13.5px; }
    .dep-detail .td-faq { padding: 13px 0; border-bottom: 1px dashed var(--line); }
    .dep-detail .td-faq summary { cursor: pointer; font: 600 15px var(--sign); text-transform: uppercase; }
    .dep-detail .td-faq p { margin-top: 9px; color: var(--muted); font-size: 13.5px; }
    .dep-detail .td-contact dl { margin: 14px 0 0; }
    .dep-detail .td-contact dl div { display: flex; justify-content: space-between; gap: 14px; padding: 10px 0; border-bottom: 1px dashed var(--line); overflow-wrap: anywhere; }
    .dep-detail .td-contact dt { color: var(--muted); font: 500 9.5px var(--mono); letter-spacing: .16em; text-transform: uppercase; }
    .dep-detail .td-contact dd { margin: 0; font-weight: 600; text-align: right; }
    .dep-detail .td-actions { display: grid; gap: 8px; margin-top: 18px; }
    .dep-detail .td-actions a, .dep-detail .td-claim > a { display: block; padding: 11px 13px; background: var(--ink); color: #fff !important; font: 600 11px var(--sign); letter-spacing: .16em; text-transform: uppercase; text-align: center; text-decoration: none; }
    .dep-detail .td-actions a:hover { background: var(--amber-deep); }
    .dep-detail .td-claim > a { margin: 15px 0; background: var(--amber); color: var(--slate) !important; }
    .dep-detail .td-related a { display: block; padding: 11px 0; border-bottom: 1px dashed var(--line); text-decoration: none; }
    .dep-detail .td-related a:hover strong { color: var(--amber-deep); }
    .dep-detail .td-related span { display: block; margin-top: 3px; color: var(--muted); font: 400 12px var(--mono); }
    .dep-detail .td-linebreak { white-space: pre-line; }
    .dep-detail .td-side-card { border-top: 3px solid var(--slate2); }

    /* ── Alt bilgi ──────────────────────────────────────────────────── */
    .dep-footer { background: var(--slate); color: #cfdcdc; }
    .dep-footer__main { display: grid; grid-template-columns: minmax(0,1.6fr) repeat(2,minmax(0,1fr)); gap: 34px; padding-block: 44px; }
    .dep-footer h2 { max-width: 320px; font-size: 27px; font-weight: 600; text-transform: uppercase; color: #fff; }
    .dep-footer p { margin-top: 12px; max-width: 340px; color: #8ba3a6; font-size: 13px; }
    .dep-footer h3 { margin-bottom: 12px; color: var(--amber); font: 500 10px var(--mono); letter-spacing: .22em; text-transform: uppercase; }
    .dep-footer a { display: block; margin: 9px 0; color: #cfdcdc; font-size: 13.5px; text-decoration: none; }
    .dep-footer a:hover { color: var(--amber); }
    .dep-footer__bottom { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding-block: 15px; border-top: 1px solid rgba(255,255,255,.12); color: #7d9396; font: 400 11px var(--mono); letter-spacing: .06em; }

    /* ── Genel Tailwind sayfalarının tema içine alınması ─────────────── */
    .dep-shell-content { min-height: 52vh; background: var(--paper); }
    .dep-shell-content :is(.rounded-full,.rounded-3xl,.rounded-2xl,.rounded-xl,.rounded-lg,.rounded-md) { border-radius: 2px !important; }
    .dep-shell-content :is(.shadow,.shadow-sm,.shadow-md,.shadow-lg,.shadow-xl,.shadow-2xl) { box-shadow: none !important; }
    .dep-shell-content :is(input,select,textarea) { border-radius: 0 !important; font-family: Barlow,sans-serif; }
    .dep-shell-content :is(input,select,textarea):focus { outline: 2px solid var(--amber); outline-offset: -1px; }
    .dep-shell-content :is(button,a).rounded-xl, .dep-shell-content button[type=submit] { font-family: var(--sign); letter-spacing: .14em; text-transform: uppercase; }
    .dep-shell-content h1, .dep-shell-content h2, .dep-shell-content h3 { font-family: var(--sign); text-transform: uppercase; letter-spacing: 0; }
    .dep-shell-content .bg-white { background: var(--white); border: 1px solid var(--line); }
    .dep-shell-content > .max-w-3xl { max-width: 940px; padding-block: 32px 72px; }
    .dep-shell-content > .max-w-3xl > .bg-white { border-top: 3px solid var(--amber); padding: clamp(22px,4vw,48px); }
    .dep-shell-content > .max-w-3xl > .bg-white button[type=submit] { background: var(--ink) !important; color: #fff !important; }
    .dep-shell-content:has(#owner-registration-form) > div > div:first-child { max-width: 880px; padding: 30px 26px; background: var(--slate); color: #eef1ef; }
    .dep-shell-content:has(#owner-registration-form) > div > div:first-child h1 { color: #fff !important; font-size: clamp(30px,4.6vw,52px); font-weight: 700; }
    .dep-shell-content:has(#owner-registration-form) > div > div:first-child p { color: #a8bdc0 !important; }
    .dep-shell-content #owner-registration-form { border-top: 3px solid var(--amber) !important; }
    .dep-shell-content #owner-registration-form :is(button[data-next-step],button[type=submit]) { background: var(--amber) !important; color: var(--slate) !important; font-weight: 600; }

    /* ── Duyarlı ────────────────────────────────────────────────────── */
    @media(max-width:1080px) { .dep-hero__grid { grid-template-columns: 1fr; } .dep-board__head, .dep-board__row { grid-template-columns: 64px minmax(0,1.4fr) minmax(0,1fr) 96px; } .dep-board__state, .dep-board__head span:last-child { display: none; } .dep-dest-grid { grid-template-columns: repeat(3,minmax(0,1fr)); } .dep-columns, .dep-detail__body { grid-template-columns: minmax(0,1fr) 260px; } }
    @media(max-width:880px) { .dep-stats { grid-template-columns: repeat(2,minmax(0,1fr)); } .dep-grid, .dep-grid--2 { grid-template-columns: repeat(2,minmax(0,1fr)); } .dep-cta { grid-template-columns: 1fr; } .dep-cta__art { order: -1; min-height: 190px; } .dep-columns, .dep-detail__body { grid-template-columns: 1fr; } .dep-side { grid-template-columns: repeat(2,minmax(0,1fr)); } .dep-ticketbar { grid-template-columns: repeat(2,minmax(0,1fr)); } .dep-ticketbar > div:nth-child(odd) { border-left: 0; } }
    @media(max-width:620px) { .dep-lead--split { grid-template-columns: 1fr; } .dep-lead img { min-height: 190px; } .dep-wrap { width: min(100% - 26px,1300px); } .dep-header__main { padding-block: 14px; } .dep-nav { gap: 8px 16px; font-size: 11px; } .dep-header__strip .dep-wrap { font-size: 9px; } .dep-hero .dep-wrap { padding-block: 34px 30px; } .dep-gate { flex-wrap: wrap; } .dep-gate__icon { display: none; } .dep-gate input { flex: 1 1 100%; padding: 15px; } .dep-gate button { flex: 1 1 100%; min-height: 46px; padding: 14px; } .dep-stats, .dep-grid, .dep-grid--2, .dep-dest-grid, .dep-side { grid-template-columns: 1fr; } .dep-board__head, .dep-board__row { grid-template-columns: 52px minmax(0,1fr); gap: 8px; } .dep-board__tag, .dep-board__head span:nth-child(3), .dep-board__plat { display: none; } .dep-board__row { padding: 12px 14px; } .dep-card { grid-template-columns: 56px minmax(0,1fr); } .dep-card__barcode { display: none; } .dep-row { grid-template-columns: 52px minmax(0,1fr); } .dep-row__mark { width: 52px; height: 52px; font-size: 18px; } .dep-row__go { grid-column: 1/-1; justify-self: start; } .dep-filter { display: grid; grid-template-columns: 1fr; } .dep-filter label { flex: none; } .dep-filter button { width: 100%; } .dep-timetable__row { grid-template-columns: 46px minmax(0,1fr) auto; } .dep-timetable__row i { display: none; } .dep-detail .td-card-grid { grid-template-columns: 1fr; } .dep-detail .td-gallery { grid-template-columns: repeat(2,minmax(0,1fr)); } .dep-contact-form, .dep-detail .td-review-form { grid-template-columns: 1fr; } .dep-detail .td-review-form button { width: 100%; } .dep-footer__main { grid-template-columns: 1fr; gap: 24px; } }
    @media(prefers-reduced-motion:reduce) { .dep-board__row { animation: none; } .dep-ticker__track { animation: none; } .dep-board__state::before { animation: none; } .dep :is(.dep-card,.dep-btn,.dep-dest,.dep-row) { transition: none; } }
    @media print { .dep-header, .dep-footer, .dep-ticker { display: none; } }
</style>
