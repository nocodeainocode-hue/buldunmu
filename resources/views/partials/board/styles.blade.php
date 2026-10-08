{{-- MAHALLE PANOSU v2 · ortak tasarım sistemi (önek: bd-) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,400..800&family=DM+Sans:ital,opsz,wght@0,9..40,400..700;1,9..40,400&display=swap" rel="stylesheet">
<style>
    html.theme-board-v2 body > header,
    html.theme-board-v2 body > footer { display: none !important; }
    html.theme-board-v2 body > main { padding: 0; }

    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .bd { color: var(--text); background: var(--bg); font-family: var(--font_body, "DM Sans", system-ui, sans-serif); font-size: 15px; line-height: 1.55; -webkit-font-smoothing: antialiased; }
    .bd *, .bd *::before, .bd *::after { box-sizing: border-box; }
    .bd :where(a) { color: inherit; text-decoration: none; }
    .bd :where(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, "Bricolage Grotesque", sans-serif); font-weight: 700; letter-spacing: -.025em; line-height: 1.08; }
    .bd :where(p) { margin: 0; }
    .bd :where(ul) { margin: 0; padding: 0; list-style: none; }
    .bd-wrap { width: min(100% - 32px, var(--page_width, 1380px)); margin-inline: auto; }
    .bd-page { padding-bottom: 64px; }
    .bd-muted { color: var(--text_muted); }
    .bd-sr { position: absolute; left: -9999px; }

    /* ── Düğmeler / rozetler ─────────────────────────────────────────── */
    .bd-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 20px; border: 2px solid var(--primary); border-radius: 999px; background: var(--primary); color: #fff !important; font: inherit; font-size: 14px; font-weight: 700; cursor: pointer; transition: transform .15s ease, background .15s ease, box-shadow .15s ease; }
    .bd-btn:hover { transform: translateY(-2px); box-shadow: 0 6px 0 rgba(20,33,61,.18); }
    .bd-btn--hot { background: var(--secondary); border-color: var(--secondary); }
    .bd-btn--ghost { background: transparent; color: var(--primary) !important; }
    .bd-btn--ghost:hover { background: var(--primary); color: #fff !important; }
    .bd-btn--sm { padding: 7px 14px; font-size: 13px; }
    .bd-badge { display: inline-flex; align-items: center; gap: 4px; padding: 2px 9px; border-radius: 999px; background: var(--primary_light); color: var(--primary); font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .bd-badge--pin { background: var(--accent); color: #3b2a00; }
    .bd-badge--ok { background: #dcf5e4; color: #146a35; }
    .bd-badge--new { background: var(--secondary); color: #fff; }

    /* ── Üst alan ────────────────────────────────────────────────────── */
    .bd-header { position: sticky; top: 0; z-index: 40; background: var(--bg_card); border-bottom: 1px solid var(--border); }
    .bd-header__row { display: grid; grid-template-columns: auto minmax(0, 1fr) auto; align-items: center; gap: 22px; padding-block: 12px; }
    .bd-brand { display: inline-flex; align-items: center; gap: 10px; font-family: var(--font_heading, "Bricolage Grotesque", sans-serif); font-size: 24px; font-weight: 800; letter-spacing: -.04em; line-height: 1; }
    .bd-brand img { max-height: 40px; max-width: 180px; object-fit: contain; }
    .bd-brand__pin { width: 28px; height: 28px; display: grid; place-items: center; border-radius: 50% 50% 50% 4px; transform: rotate(-45deg); background: var(--secondary); }
    .bd-brand__pin::after { content: ""; width: 10px; height: 10px; border-radius: 50%; background: #fff; }
    .bd-find { display: flex; align-items: stretch; max-width: 720px; width: 100%; margin-inline: auto; border: 2px solid var(--primary); border-radius: 999px; background: #fff; overflow: hidden; }
    .bd-find input { flex: 1 1 auto; min-width: 0; padding: 11px 20px; border: 0; background: transparent; color: var(--text); font: inherit; font-size: 15px; outline: none; }
    .bd-find button { flex: 0 0 auto; padding: 0 22px; border: 0; background: var(--primary); color: #fff; font: inherit; font-weight: 700; cursor: pointer; transition: background .15s ease; }
    .bd-find button:hover { background: var(--secondary); }
    .bd-header__act { display: flex; align-items: center; gap: 10px; }
    .bd-header__link { font-size: 14px; font-weight: 600; padding: 8px 10px; border-radius: 8px; }
    .bd-header__link:hover { background: var(--primary_light); }
    .bd-chips { border-top: 1px solid var(--border); background: var(--bg); }
    .bd-chips__row { display: flex; gap: 8px; overflow-x: auto; padding-block: 10px; scrollbar-width: none; }
    .bd-chips__row::-webkit-scrollbar { display: none; }
    .bd-chip { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border: 1px solid var(--border); border-radius: 999px; background: var(--bg_card); font-size: 13.5px; font-weight: 600; white-space: nowrap; transition: background .15s ease, border-color .15s ease, transform .15s ease; }
    .bd-chip:hover { background: var(--primary); border-color: var(--primary); color: #fff; transform: translateY(-1px); }
    .bd-chip--all { background: var(--primary); border-color: var(--primary); color: #fff; }
    .bd-menu { display: none; position: relative; }
    .bd-menu summary { list-style: none; cursor: pointer; width: 42px; height: 42px; display: grid; place-items: center; border: 2px solid var(--primary); border-radius: 12px; font-size: 18px; }
    .bd-menu summary::-webkit-details-marker { display: none; }
    .bd-menu__panel { position: absolute; right: 0; top: 52px; width: min(86vw, 300px); padding: 10px; border: 2px solid var(--primary); border-radius: 16px; background: #fff; box-shadow: 6px 6px 0 var(--primary); display: grid; }
    .bd-menu__panel a { padding: 11px 12px; border-radius: 8px; font-weight: 600; }
    .bd-menu__panel a:hover { background: var(--primary_light); }
    @media (max-width: 900px) {
        .bd-header__row { grid-template-columns: minmax(0, 1fr) auto; gap: 12px; }
        .bd-find { grid-column: 1 / -1; grid-row: 2; max-width: none; }
        .bd-header__link, .bd-header__act .bd-btn { display: none; }
        .bd-menu { display: block; }
    }

    /* ── Ana sayfa ───────────────────────────────────────────────────── */
    .bd-hero { padding-block: clamp(26px, 4vw, 48px) 8px; }
    .bd-hero__row { display: flex; align-items: end; justify-content: space-between; flex-wrap: wrap; gap: 18px 30px; }
    .bd-hero h1 { font-size: clamp(34px, 5.4vw, 66px); font-weight: 800; letter-spacing: -.045em; max-width: 16ch; }
    .bd-hero h1 mark { background: linear-gradient(transparent 62%, var(--accent) 62%); color: inherit; padding: 0 .08em; }
    .bd-hero__sub { margin-top: 12px; max-width: 56ch; color: var(--text_muted); font-size: 17px; }
    .bd-pulse { display: flex; gap: 10px; flex-wrap: wrap; }
    .bd-pulse > div { padding: 12px 18px; border: 1px solid var(--border); border-radius: 14px; background: var(--bg_card); min-width: 118px; }
    .bd-pulse strong { display: block; font-family: var(--font_heading, sans-serif); font-size: 30px; line-height: 1; letter-spacing: -.04em; }
    .bd-pulse span { font-size: 12px; color: var(--text_muted); font-weight: 600; }
    .bd-pulse i { display: inline-block; width: 8px; height: 8px; margin-right: 6px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 0 rgba(34,197,94,.6); animation: bdPulse 1.8s infinite; }
    @keyframes bdPulse { 70% { box-shadow: 0 0 0 8px rgba(34,197,94,0); } 100% { box-shadow: 0 0 0 0 rgba(34,197,94,0); } }

    /* Pano: iğnelenmiş kartlar */
    .bd-pinboard { position: relative; margin-top: 26px; padding: clamp(20px, 3vw, 34px); border-radius: 22px; background-color: #1d2c4d; background-image: radial-gradient(rgba(255,255,255,.12) 1.4px, transparent 1.4px); background-size: 22px 22px; box-shadow: inset 0 0 0 6px #e0b987, inset 0 0 0 8px #b88b52, 0 14px 30px rgba(20,33,61,.18); color: #fff; }
    .bd-pinboard__head { display: flex; align-items: baseline; justify-content: space-between; gap: 14px; margin-bottom: 22px; }
    .bd-pinboard__head h2 { font-size: clamp(24px, 3vw, 36px); color: #fff; }
    .bd-pinboard__head span { font-size: 13px; color: rgba(255,255,255,.65); }
    .bd-pinboard__grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 26px 22px; }
    @media (max-width: 1080px) { .bd-pinboard__grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 560px) { .bd-pinboard__grid { grid-template-columns: 1fr; } }
    .bd-note { position: relative; display: flex; flex-direction: column; gap: 8px; padding: 22px 18px 16px; border-radius: 4px; background: #fffdf4; color: var(--text); box-shadow: 0 10px 18px rgba(0,0,0,.28); transform: rotate(var(--tilt, -1deg)); transition: transform .2s ease, box-shadow .2s ease; }
    .bd-note:nth-child(4n+1) { --tilt: -1.6deg; }
    .bd-note:nth-child(4n+2) { --tilt: 1.2deg; background: #fff3c4; }
    .bd-note:nth-child(4n+3) { --tilt: -.6deg; background: #e4f1ff; }
    .bd-note:nth-child(4n+4) { --tilt: 1.8deg; background: #ffe3d6; }
    .bd-note:hover { transform: rotate(0) translateY(-6px) scale(1.02); box-shadow: 0 18px 28px rgba(0,0,0,.34); z-index: 2; }
    .bd-note::before { content: ""; position: absolute; left: 50%; top: -9px; width: 18px; height: 18px; margin-left: -9px; border-radius: 50%; background: radial-gradient(circle at 35% 30%, #ff9a73, var(--secondary) 60%, #9b2b00); box-shadow: 0 4px 6px rgba(0,0,0,.35); }
    .bd-note__cat { font-size: 11.5px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--secondary); }
    .bd-note h3 { font-size: 21px; }
    .bd-note p { font-size: 13.5px; color: var(--text_muted); display: -webkit-box; -webkit-line-clamp: 3; line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
    .bd-note__foot { margin-top: auto; padding-top: 10px; display: flex; justify-content: space-between; align-items: center; gap: 8px; border-top: 2px dashed rgba(20,33,61,.18); font-size: 12.5px; font-weight: 600; }
    .bd-note__foot b { color: var(--primary); }

    /* Üç sütun düzeni */
    .bd-layout { display: grid; grid-template-columns: 250px minmax(0, 1fr) 300px; gap: 26px; align-items: start; padding-top: 30px; }
    @media (max-width: 1180px) { .bd-layout { grid-template-columns: 230px minmax(0, 1fr); } .bd-layout > .bd-rail--right { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 18px; } }
    @media (max-width: 820px) { .bd-layout { grid-template-columns: 1fr; } .bd-layout > .bd-rail--right { grid-template-columns: 1fr; } }
    .bd-rail { display: grid; gap: 18px; }
    @media (min-width: 821px) { .bd-rail--left { position: sticky; top: 132px; } }
    .bd-box { border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); box-shadow: var(--card_shadow); overflow: hidden; }
    .bd-box__head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; padding: 14px 16px 10px; }
    .bd-box__head h2 { font-size: 18px; }
    .bd-box__head span, .bd-box__head a { font-size: 12.5px; font-weight: 600; color: var(--text_muted); }
    .bd-box__head a:hover { color: var(--secondary); }
    .bd-list { padding: 0 8px 10px; }
    .bd-list a { display: flex; align-items: center; gap: 10px; padding: 8px 10px; border-radius: 10px; font-size: 14.5px; font-weight: 500; transition: background .12s ease; }
    .bd-list a:hover { background: var(--primary_light); }
    .bd-list__ico { flex: 0 0 auto; width: 28px; height: 28px; display: grid; place-items: center; border-radius: 8px; background: var(--primary_light); font-size: 14px; }
    .bd-list__txt { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .bd-list__n { flex: 0 0 auto; font-size: 12px; font-weight: 700; color: var(--text_muted); }
    .bd-sec-title { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 14px; }
    .bd-sec-title h2 { font-size: clamp(22px, 2.6vw, 30px); }
    .bd-sec-title a { font-size: 14px; font-weight: 700; color: var(--secondary); }
    .bd-sec-title a:hover { text-decoration: underline; }
    .bd-stack { display: grid; gap: 34px; min-width: 0; }

    /* ── İlan listesi (liste / galeri) ───────────────────────────────── */
    .bd-view { position: relative; }
    .bd-view > input { position: absolute; opacity: 0; pointer-events: none; }
    .bd-view__bar { display: flex; align-items: center; justify-content: space-between; gap: 12px; margin-bottom: 12px; }
    .bd-view__count { font-size: 14px; color: var(--text_muted); font-weight: 600; }
    .bd-view__toggle { display: inline-flex; padding: 3px; border: 1px solid var(--border); border-radius: 999px; background: var(--bg_card); }
    .bd-view__toggle label { display: inline-flex; align-items: center; gap: 6px; padding: 6px 14px; border-radius: 999px; font-size: 13px; font-weight: 700; cursor: pointer; color: var(--text_muted); }
    .bd-view > input:focus-visible ~ .bd-view__bar .bd-view__toggle { outline: 2px solid var(--secondary); }
    .bd-view > #bdv-list:checked ~ .bd-view__bar label[for="bdv-list"],
    .bd-view > #bdv-grid:checked ~ .bd-view__bar label[for="bdv-grid"] { background: var(--primary); color: #fff; }
    .bd-items { display: grid; gap: 12px; }
    .bd-item { position: relative; display: grid; grid-template-columns: 124px minmax(0, 1fr) auto; gap: 16px; align-items: center; padding: 12px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); transition: transform .15s ease, box-shadow .15s ease, border-color .15s ease; }
    .bd-item:hover { transform: translateY(-2px); border-color: var(--primary); box-shadow: 0 10px 24px rgba(20,33,61,.1); }
    .bd-item--pin { background: linear-gradient(90deg, #fff7d6, var(--bg_card) 40%); border-color: #f0d889; }
    .bd-item__thumb { width: 124px; height: 96px; border-radius: 12px; overflow: hidden; background: var(--primary); display: grid; place-items: center; color: #fff; font-family: var(--font_heading, sans-serif); font-size: 40px; font-weight: 800; }
    .bd-item:nth-child(3n+2) .bd-item__thumb:not(:has(img)) { background: var(--secondary); }
    .bd-item:nth-child(3n+3) .bd-item__thumb:not(:has(img)) { background: #0f766e; }
    .bd-item__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .bd-item__body { min-width: 0; display: grid; gap: 4px; }
    .bd-item__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 4px 8px; font-size: 12.5px; color: var(--text_muted); font-weight: 500; }
    .bd-item__meta b { color: var(--secondary); font-weight: 700; }
    .bd-item h3 { font-size: 19px; letter-spacing: -.02em; }
    .bd-item h3 a::after { content: ""; position: absolute; inset: 0; border-radius: 16px; }
    .bd-item__text { font-size: 14px; color: var(--text_muted); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .bd-item__badges { display: flex; flex-wrap: wrap; gap: 6px; margin-top: 2px; }
    .bd-item__act { position: relative; z-index: 2; display: grid; gap: 8px; justify-items: end; }
    .bd-view > #bdv-grid:checked ~ .bd-items { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    .bd-view > #bdv-grid:checked ~ .bd-items .bd-item { grid-template-columns: 1fr; align-items: stretch; align-content: start; }
    .bd-view > #bdv-grid:checked ~ .bd-items .bd-item__thumb { width: 100%; height: auto; aspect-ratio: 4/3; font-size: 56px; }
    .bd-view > #bdv-grid:checked ~ .bd-items .bd-item__act { justify-items: stretch; grid-auto-flow: column; }
    .bd-view > #bdv-grid:checked ~ .bd-items .bd-item__act .bd-btn { width: 100%; }
    @media (max-width: 1100px) { .bd-view > #bdv-grid:checked ~ .bd-items { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 680px) {
        .bd-view > #bdv-grid:checked ~ .bd-items { grid-template-columns: 1fr; }
        .bd-item { grid-template-columns: 92px minmax(0, 1fr); gap: 12px; }
        .bd-item__thumb { width: 92px; height: 82px; font-size: 30px; }
        .bd-item__act { grid-column: 1 / -1; justify-items: stretch; grid-auto-flow: column; }
        .bd-item__act .bd-btn { width: 100%; }
    }
    .bd-empty { padding: 44px 20px; text-align: center; border: 2px dashed var(--border); border-radius: 16px; color: var(--text_muted); }
    .bd-empty a { color: var(--secondary); font-weight: 700; }

    /* ── Kategori ızgarası ───────────────────────────────────────────── */
    .bd-cats { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 12px; }
    @media (max-width: 1000px) { .bd-cats { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 640px) { .bd-cats { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    .bd-cat { display: flex; flex-direction: column; gap: 10px; padding: 16px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); transition: transform .15s ease, background .15s ease, color .15s ease; }
    .bd-cat:hover { transform: translateY(-3px) rotate(-.6deg); background: var(--primary); color: #fff; }
    .bd-cat__ico { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 12px; background: var(--accent); color: #3b2a00; font-size: 20px; }
    .bd-cat strong { font-family: var(--font_heading, sans-serif); font-size: 17px; letter-spacing: -.02em; line-height: 1.15; }
    .bd-cat span { font-size: 12.5px; opacity: .7; font-weight: 600; }

    /* ── Yan kutular ─────────────────────────────────────────────────── */
    .bd-cta { position: relative; padding: 20px; border-radius: 18px; background: var(--secondary); color: #fff; overflow: hidden; }
    .bd-cta::after { content: ""; position: absolute; right: -30px; bottom: -30px; width: 120px; height: 120px; border-radius: 50%; background: rgba(255,255,255,.16); }
    .bd-cta h2 { font-size: 24px; }
    .bd-cta p { margin-top: 8px; font-size: 14px; opacity: .92; }
    .bd-cta .bd-btn { position: relative; z-index: 1; margin-top: 14px; background: #fff; border-color: #fff; color: var(--secondary) !important; }
    .bd-mini { display: block; padding: 11px 16px; border-top: 1px solid var(--border); }
    .bd-mini:hover { background: var(--primary_light); }
    .bd-mini strong { display: block; font-size: 14.5px; line-height: 1.3; }
    .bd-mini span { font-size: 12.5px; color: var(--text_muted); }

    /* ── İç sayfa başlığı ────────────────────────────────────────────── */
    .bd-band { padding-block: 26px 22px; }
    .bd-crumb { display: flex; flex-wrap: wrap; gap: 6px 10px; margin-bottom: 14px; font-size: 13px; color: var(--text_muted); font-weight: 500; }
    .bd-crumb a:hover { color: var(--secondary); }
    .bd-band h1 { font-size: clamp(32px, 4.6vw, 56px); font-weight: 800; letter-spacing: -.045em; }
    .bd-band__lede { margin-top: 10px; max-width: 64ch; color: var(--text_muted); font-size: 16.5px; }
    .bd-band__tag { display: inline-flex; margin-bottom: 12px; }

    /* ── Filtre çubuğu ───────────────────────────────────────────────── */
    .bd-filters { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 12px; padding: 14px 16px; margin-bottom: 20px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); }
    .bd-filters label { display: grid; gap: 4px; flex: 1 1 180px; font-size: 12px; font-weight: 700; color: var(--text_muted); }
    .bd-filters :is(input, select) { width: 100%; padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font: inherit; font-size: 14.5px; }
    .bd-filters :is(input, select):focus { outline: 2px solid var(--secondary); outline-offset: 1px; }
    .bd-filters a:not(.bd-btn) { align-self: center; font-size: 13px; font-weight: 700; color: var(--secondary); }

    .bd-cols { display: grid; grid-template-columns: minmax(0, 1fr) 320px; gap: 26px; align-items: start; }
    @media (max-width: 980px) { .bd-cols { grid-template-columns: 1fr; } }
    .bd-side { display: grid; gap: 18px; }
    @media (min-width: 981px) { .bd-side { position: sticky; top: 132px; } }
    .bd-links a { display: flex; align-items: center; gap: 10px; padding: 10px 16px; border-top: 1px solid var(--border); font-size: 14.5px; font-weight: 500; }
    .bd-links a:hover { background: var(--primary_light); }
    .bd-links__txt { flex: 1 1 auto; min-width: 0; }
    .bd-links__n { font-size: 12px; font-weight: 700; color: var(--text_muted); }
    .bd-panel { padding: 18px 20px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); }
    .bd-panel + .bd-panel, .bd-box + .bd-panel, .bd-panel + .bd-box { margin-top: 18px; }
    .bd-panel > h2 { font-size: 22px; margin-bottom: 10px; }

    /* ── Prosa ───────────────────────────────────────────────────────── */
    .bd-prose { font-size: 16.5px; line-height: 1.75; }
    .bd .bd-prose > * + * { margin-top: 14px; }
    .bd-prose :is(h2, h3) { margin-top: 28px; font-size: 26px; }
    .bd-prose a { color: var(--secondary); text-decoration: underline; text-underline-offset: 3px; }
    .bd-prose ul { padding-left: 22px; list-style: disc; }
    .bd-prose ol { padding-left: 22px; list-style: decimal; }
    .bd-prose img { max-width: 100%; height: auto; border-radius: 12px; }
    .bd-prose details { border-bottom: 1px solid var(--border); }
    .bd-prose summary { padding: 12px 0; font-weight: 700; cursor: pointer; }
    .bd-prose details p { padding: 0 0 12px; color: var(--text_muted); }

    /* ── Paketler / form / not ───────────────────────────────────────── */
    .bd-plans { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 20px; }
    @media (max-width: 900px) { .bd-plans { grid-template-columns: 1fr; max-width: 460px; margin-inline: auto; } }
    .bd-plan { position: relative; display: flex; flex-direction: column; gap: 14px; padding: 26px; border: 1px solid var(--border); border-radius: 22px; background: var(--bg_card); }
    .bd-plan--hot { background: var(--primary); color: #fff; border-color: var(--primary); transform: rotate(-.8deg) translateY(-8px); box-shadow: 8px 10px 0 var(--accent); }
    .bd-plan--hot .bd-muted { color: rgba(255,255,255,.7); }
    .bd-plan h2 { font-size: 26px; }
    .bd-plan__price { font-family: var(--font_heading, sans-serif); font-size: 46px; font-weight: 800; letter-spacing: -.05em; line-height: 1; }
    .bd-plan__price small { display: block; margin-top: 4px; font-family: var(--font_body); font-size: 13px; font-weight: 500; letter-spacing: 0; opacity: .7; }
    .bd-plan ul { display: grid; gap: 9px; }
    .bd-plan li { position: relative; padding-left: 26px; font-size: 14.5px; }
    .bd-plan li::before { content: "✓"; position: absolute; left: 0; top: 0; width: 18px; height: 18px; display: grid; place-items: center; border-radius: 50%; background: var(--accent); color: #3b2a00; font-size: 11px; font-weight: 800; line-height: 18px; text-align: center; }
    .bd-plan .bd-btn { margin-top: auto; }
    .bd-plan--hot .bd-btn { background: var(--accent); border-color: var(--accent); color: #3b2a00 !important; }
    .bd-plan__flag { position: absolute; right: 18px; top: -12px; padding: 4px 12px; border-radius: 999px; background: var(--secondary); color: #fff; font-size: 12px; font-weight: 700; transform: rotate(3deg); }
    .bd-form { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 600px) { .bd-form { grid-template-columns: 1fr; } }
    .bd-form label { display: grid; gap: 6px; font-size: 13px; font-weight: 700; color: var(--text_muted); }
    .bd-form .bd-wide { grid-column: 1 / -1; }
    .bd-form :is(input, select, textarea) { padding: 12px 14px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font: inherit; font-size: 15px; }
    .bd-form :is(input, select, textarea):focus { outline: 2px solid var(--secondary); outline-offset: 1px; }
    .bd-note-box { padding: 12px 16px; border-radius: 12px; border-left: 5px solid var(--secondary); background: #fff3ec; font-size: 14.5px; }
    .bd-pag { padding-top: 22px; }
    .bd-pag nav > div:first-child { display: none; }
    .bd-pag nav { display: flex; flex-wrap: wrap; justify-content: center; }
    .bd-pag :is(a, span[aria-current], span[aria-disabled]) { display: inline-flex; align-items: center; justify-content: center; min-width: 40px; height: 40px; margin: 3px; padding: 0 12px; border: 1px solid var(--border); border-radius: 999px; background: var(--bg_card); font-size: 14px; font-weight: 600; }
    .bd-pag a:hover { border-color: var(--primary); }
    .bd-pag [aria-current="page"] > span, .bd-pag span[aria-current="page"] { background: var(--primary); color: #fff; border-color: var(--primary); }
    .bd-pag svg { width: 14px; height: 14px; }

    /* ── Alt bilgi ───────────────────────────────────────────────────── */
    .bd-footer { margin-top: 60px; background: var(--primary); color: #cdd5e6; padding-top: 44px; }
    .bd-footer__grid { display: grid; grid-template-columns: 1.5fr 1fr 1fr 1fr; gap: 34px; padding-bottom: 34px; }
    @media (max-width: 860px) { .bd-footer__grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 520px) { .bd-footer__grid { grid-template-columns: 1fr; } }
    .bd-footer h3 { margin-bottom: 12px; font-size: 14px; color: var(--accent); letter-spacing: .06em; text-transform: uppercase; }
    .bd-footer li + li { margin-top: 8px; }
    .bd-footer a:hover { color: #fff; text-decoration: underline; }
    .bd-footer .bd-brand { color: #fff; }
    .bd-footer__about { margin-top: 12px; max-width: 36ch; font-size: 14px; opacity: .8; }
    .bd-footer__legal { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding-block: 16px; border-top: 1px solid rgba(255,255,255,.14); font-size: 13px; opacity: .75; }

    /* ── Firma detayı ────────────────────────────────────────────────── */
    .bd-detail__cover { height: clamp(160px, 24vw, 300px); border-radius: 22px; overflow: hidden; background: var(--primary); }
    .bd-detail__cover img { width: 100%; height: 100%; object-fit: cover; }
    .bd-detail__head { display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 22px; align-items: end; margin-top: -46px; padding-inline: clamp(14px, 3vw, 30px); position: relative; }
    .bd-detail__logo { width: 112px; height: 112px; display: grid; place-items: center; border-radius: 24px; border: 5px solid var(--bg); background: var(--secondary); color: #fff; font-family: var(--font_heading, sans-serif); font-size: 48px; font-weight: 800; overflow: hidden; }
    .bd-detail__logo img { width: 100%; height: 100%; object-fit: cover; }
    .bd-detail__title h1 { font-size: clamp(28px, 4.2vw, 48px); font-weight: 800; letter-spacing: -.04em; }
    .bd-detail__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; margin-top: 8px; font-size: 14px; color: var(--text_muted); font-weight: 500; }
    .bd-detail__lede { margin-top: 18px; max-width: 70ch; font-size: 17px; color: var(--text_muted); }
    .bd-detail__body { display: grid; grid-template-columns: minmax(0, 1fr) 340px; gap: 28px; align-items: start; padding-top: 28px; }
    @media (max-width: 980px) { .bd-detail__body { grid-template-columns: 1fr; } .bd-detail__head { margin-top: -34px; } }
    @media (max-width: 560px) { .bd-detail__head { grid-template-columns: 1fr; } .bd-detail__logo { width: 84px; height: 84px; font-size: 36px; } }
    .bd-detail__main, .bd-detail__side { display: grid; gap: 18px; }
    @media (min-width: 981px) { .bd-detail__side { position: sticky; top: 132px; } }
    .bd-contact { padding: 20px; border: 2px solid var(--primary); border-radius: 20px; background: var(--bg_card); box-shadow: 6px 6px 0 var(--primary); }
    .bd-contact h2 { font-size: 20px; }
    .bd-contact__btns { display: grid; gap: 10px; margin-top: 14px; }
    .bd-contact .bd-btn { width: 100%; }
    .bd-contact dl { margin: 14px 0 0; }
    .bd-contact dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 8px 0; border-top: 1px dashed var(--border); font-size: 14px; }
    .bd-contact dt { color: var(--text_muted); }
    .bd-contact dd { margin: 0; text-align: right; font-weight: 600; }
    .bd-facts { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 18px; }
    .bd-facts > div { padding: 10px 16px; border: 1px solid var(--border); border-radius: 14px; background: var(--bg_card); }
    .bd-facts dt { font-size: 11.5px; font-weight: 700; color: var(--text_muted); text-transform: uppercase; letter-spacing: .06em; }
    .bd-facts dd { margin: 2px 0 0; font-weight: 700; }
    .bd-facts a { text-decoration: underline; text-underline-offset: 3px; }

    .bd-detail .td-section, .bd-detail .td-side-card { padding: 20px 22px; border: 1px solid var(--border); border-radius: 18px; background: var(--bg_card); box-shadow: var(--card_shadow); }
    .bd-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; }
    .bd-detail .td-index { display: inline-grid; place-items: center; width: 28px; height: 28px; border-radius: 8px; background: var(--accent); color: #3b2a00; font-size: 12px; font-weight: 800; }
    .bd-detail .td-section-head h2, .bd-detail .td-side-card h2 { font-size: 22px; }
    .bd-detail .td-side-card h2 { margin-top: 6px; }
    .bd-detail .td-section-head > a, .bd-detail .td-count { margin-left: auto; font-size: 13px; font-weight: 700; color: var(--secondary); }
    .bd-detail .td-prose { font-size: 16px; line-height: 1.75; }
    .bd-detail .td-prose p + p { margin-top: 12px; }
    .bd-detail .td-prose :is(h2, h3) { margin: 18px 0 8px; font-size: 20px; }
    .bd-detail .td-prose ul { padding-left: 22px; list-style: disc; }
    .bd-detail .td-share { margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--border); }
    .bd-detail .td-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 14px; }
    @media (max-width: 620px) { .bd-detail .td-card-grid { grid-template-columns: 1fr; } }
    .bd-detail .td-card { display: flex; flex-direction: column; gap: 5px; padding: 12px; border: 1px solid var(--border); border-radius: 14px; background: var(--bg); transition: transform .15s ease, border-color .15s ease; }
    .bd-detail .td-card:hover { transform: translateY(-2px); border-color: var(--primary); }
    .bd-detail .td-card-image { width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: 10px; margin-bottom: 4px; }
    .bd-detail .td-card small { font-size: 11.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--secondary); }
    .bd-detail .td-card h3 { font-size: 17px; }
    .bd-detail .td-card p { font-size: 13.5px; color: var(--text_muted); }
    .bd-detail .td-price { font-family: var(--font_heading, sans-serif); font-size: 18px; font-weight: 800; color: var(--primary); }
    .bd-detail .td-linebreak { white-space: pre-line; }
    .bd-detail .td-gallery { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
    @media (max-width: 620px) { .bd-detail .td-gallery { grid-template-columns: repeat(2, 1fr); } }
    .bd-detail .td-gallery img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: 12px; }
    .bd-detail .td-map { overflow: hidden; border-radius: 14px; border: 1px solid var(--border); }
    .bd-detail .td-map iframe { display: block; width: 100%; height: 320px; border: 0; }
    .bd-detail .td-reviews { display: grid; gap: 12px; margin-bottom: 18px; }
    .bd-detail .td-review { padding: 14px 16px; border-radius: 14px; background: var(--bg); }
    .bd-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 10px; font-size: 13.5px; }
    .bd-detail .td-review span { color: var(--text_muted); }
    .bd-detail .td-stars { color: #f59e0b; }
    .bd-detail .td-empty { color: var(--text_muted); font-size: 14px; }
    .bd-detail .td-form-title { margin: 6px 0 12px; font-size: 18px; }
    .bd-detail .td-review-form { display: grid; gap: 10px; }
    .bd-detail .td-review-form :is(input, select, textarea) { padding: 11px 14px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font: inherit; font-size: 15px; }
    .bd-detail .td-review-form :is(input, select, textarea):focus { outline: 2px solid var(--secondary); outline-offset: 1px; }
    .bd-detail .td-review-form button { justify-self: start; padding: 11px 22px; border: 2px solid var(--primary); border-radius: 999px; background: var(--primary); color: #fff; font: inherit; font-size: 14px; font-weight: 700; cursor: pointer; }
    .bd-detail .td-review-form button:hover { background: var(--secondary); border-color: var(--secondary); }
    .bd-detail .td-message { padding: 12px 14px; border-left: 5px solid var(--secondary); border-radius: 10px; background: #fff3ec; font-size: 14px; }
    .bd-detail .td-faq { border-bottom: 1px solid var(--border); }
    .bd-detail .td-faq summary { padding: 12px 0; font-weight: 700; cursor: pointer; }
    .bd-detail .td-faq p { padding: 0 0 12px; color: var(--text_muted); }
    .bd-detail .td-side-label { font-size: 11.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--secondary); }
    .bd-detail .td-contact dl { margin: 12px 0 0; }
    .bd-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 9px 0; border-bottom: 1px dashed var(--border); font-size: 14px; }
    .bd-detail .td-contact dt { color: var(--text_muted); }
    .bd-detail .td-contact dd { margin: 0; text-align: right; font-weight: 600; }
    .bd-detail .td-actions { display: grid; gap: 8px; margin-top: 14px; }
    .bd-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 14px; border: 2px solid var(--primary); border-radius: 999px; font-size: 14px; font-weight: 700; }
    .bd-detail .td-actions a:hover { background: var(--primary); color: #fff; }
    .bd-detail .td-claim > a { display: inline-flex; margin-top: 10px; padding: 10px 18px; border-radius: 999px; background: var(--secondary); color: #fff; font-size: 13.5px; font-weight: 700; }
    .bd-detail .td-claim small { display: block; margin-top: 10px; color: var(--text_muted); font-size: 12px; }
    .bd-detail .td-related a { display: flex; flex-direction: column; gap: 2px; padding: 10px 0; border-bottom: 1px dashed var(--border); }
    .bd-detail .td-related a:hover { color: var(--secondary); }
    .bd-detail .td-related span { font-size: 12.5px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .bd *, .bd *::before, .bd *::after { transition: none !important; animation: none !important; } .bd-note, .bd-plan--hot { transform: none; } }
    @media print { .bd-header, .bd-footer, .bd-pinboard { display: none; } }
</style>
