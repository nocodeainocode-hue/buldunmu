{{-- TASARIM KATALOĞU · tüm sayfalar için ortak tasarım sistemi (önek: kat-) --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fraunces:ital,opsz,wght@0,9..144,300..700;1,9..144,300..700&family=Instrument+Sans:wght@400..700&display=swap" rel="stylesheet">
<style>
    html.theme-design-catalog body > header,
    html.theme-design-catalog body > footer { display: none !important; }
    html.theme-design-catalog body > main { padding: 0; }

    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .kat { color: var(--text); background: var(--bg); font-family: var(--font_body, "Instrument Sans", system-ui, sans-serif); font-size: 16px; line-height: 1.65; -webkit-font-smoothing: antialiased; }
    .kat *, .kat *::before, .kat *::after { box-sizing: border-box; }
    .kat a { color: inherit; }
    .kat :where(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, Fraunces, Georgia, serif); font-weight: 400; letter-spacing: -.02em; line-height: 1.02; font-variation-settings: "opsz" 96; }
    .kat :is(h1,h2,h3,h4) em { font-style: italic; color: var(--secondary); }
    .kat :where(p) { margin: 0; }
    .kat :where(ul) { margin: 0; padding: 0; list-style: none; }
    .kat-wrap { width: min(100% - 40px, var(--page_width, 1280px)); margin-inline: auto; }
    .kat-page { padding-bottom: 70px; }
    .kat-caps { font-size: 11px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; }
    .kat-muted { color: var(--text_muted); }
    .kat-serif { font-family: var(--font_heading, Fraunces, Georgia, serif); }

    /* ── Düğmeler ────────────────────────────────────────────────────── */
    .kat-btn { display: inline-flex; align-items: center; justify-content: center; gap: 10px; padding: 14px 24px; border: 1px solid var(--text); background: var(--text); color: var(--bg) !important; font: inherit; font-size: 12px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; cursor: pointer; transition: background .2s ease, color .2s ease, border-color .2s ease, transform .2s ease; }
    .kat-btn:hover { background: var(--secondary); border-color: var(--secondary); transform: translateY(-1px); }
    .kat-btn--ghost { background: transparent; color: var(--text) !important; }
    .kat-btn--ghost:hover { background: var(--text); border-color: var(--text); color: var(--bg) !important; }
    .kat-btn--accent { background: var(--accent); border-color: var(--accent); color: #1b1813 !important; }
    .kat-btn--accent:hover { background: var(--bg); border-color: var(--bg); color: #1b1813 !important; }
    .kat-link { font-size: 12px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; border-bottom: 1px solid currentColor; padding-bottom: 2px; }
    .kat-link:hover { color: var(--secondary); }

    /* ── Künye / Başlık şeridi ───────────────────────────────────────── */
    .kat-header { background: var(--bg); border-bottom: 3px double var(--text); position: relative; z-index: 30; }
    .kat-header__strip { border-bottom: 1px solid var(--border); font-size: 11px; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .kat-header__strip .kat-wrap { display: flex; justify-content: space-between; gap: 16px; padding-block: 8px; }
    .kat-header__strip a { text-decoration: none; }
    .kat-header__strip a:hover { color: var(--secondary); }
    .kat-header__main { display: grid; grid-template-columns: 1fr auto 1fr; align-items: center; gap: 20px; padding-block: 22px; }
    .kat-brand { display: inline-flex; align-items: center; gap: 12px; font-family: var(--font_heading, Fraunces, serif); font-size: clamp(24px, 3vw, 34px); letter-spacing: -.03em; text-decoration: none; line-height: 1; }
    .kat-brand img { max-height: 44px; max-width: 200px; object-fit: contain; }
    .kat-brand i { color: var(--secondary); font-style: normal; }
    .kat-nav { display: flex; align-items: center; justify-content: center; gap: 30px; }
    .kat-nav a { font-size: 12px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; padding-block: 6px; border-bottom: 1px solid transparent; }
    .kat-nav a:hover { border-bottom-color: var(--secondary); color: var(--secondary); }
    .kat-header__cta { justify-self: end; display: flex; align-items: center; gap: 18px; }
    .kat-menu { display: none; }
    @media (max-width: 980px) {
        .kat-header__main { grid-template-columns: 1fr auto; }
        .kat-nav, .kat-header__cta .kat-link { display: none; }
        .kat-menu { display: block; position: relative; }
        .kat-menu summary { list-style: none; cursor: pointer; width: 44px; height: 44px; display: grid; place-items: center; border: 1px solid var(--text); font-size: 20px; }
        .kat-menu summary::-webkit-details-marker { display: none; }
        .kat-menu[open] summary { background: var(--text); color: var(--bg); }
        .kat-menu__panel { position: absolute; right: 0; top: 54px; width: min(86vw, 340px); background: var(--bg_card); border: 1px solid var(--text); padding: 20px; display: grid; gap: 4px; box-shadow: 8px 8px 0 var(--text); }
        .kat-menu__panel a { display: flex; justify-content: space-between; padding: 12px 0; border-bottom: 1px solid var(--border); font-family: var(--font_heading, Fraunces, serif); font-size: 22px; text-decoration: none; }
        .kat-menu__panel form { margin-bottom: 10px; }
        .kat-menu__panel input { width: 100%; padding: 12px; border: 1px solid var(--border); background: var(--bg); font: inherit; }
    }
    @media (max-width: 700px) { .kat-header__cta .kat-btn { display: none; } .kat-header__main { padding-block: 16px; } }
    @media (max-width: 560px) { .kat-header__strip span:nth-child(2) { display: none; } }

    /* ── Arama ───────────────────────────────────────────────────────── */
    .kat-search { display: flex; align-items: stretch; border-bottom: 2px solid var(--text); max-width: 640px; }
    .kat-search input { flex: 1 1 auto; min-width: 0; padding: 18px 4px; border: 0; background: transparent; color: var(--text); font-family: var(--font_heading, Fraunces, serif); font-style: italic; font-size: clamp(20px, 2.4vw, 28px); outline: none; }
    .kat-search input::placeholder { color: var(--text_muted); opacity: .8; }
    .kat-search button { flex: 0 0 auto; width: 64px; border: 0; background: var(--secondary); color: #fff; font-size: 22px; cursor: pointer; transition: background .2s ease; }
    .kat-search button:hover { background: var(--text); }

    /* ── Ana sayfa · kapak ───────────────────────────────────────────── */
    .kat-hero { padding-block: clamp(34px, 6vw, 84px) clamp(28px, 4vw, 52px); }
    .kat-hero__grid { display: grid; grid-template-columns: minmax(0, 7fr) minmax(0, 4fr); gap: clamp(28px, 5vw, 72px); align-items: end; }
    @media (max-width: 980px) { .kat-hero__grid { grid-template-columns: 1fr; } }
    .kat-kicker { display: inline-flex; align-items: center; gap: 12px; color: var(--secondary); font-size: 11px; font-weight: 600; letter-spacing: .22em; text-transform: uppercase; }
    .kat-kicker::before { content: ""; width: 34px; height: 1px; background: currentColor; }
    .kat-hero h1 { margin-top: 22px; font-size: clamp(44px, 7.4vw, 104px); line-height: .96; letter-spacing: -.045em; }
    .kat-hero__lede { margin-top: 34px; max-width: 54ch; font-size: 18px; color: var(--text_muted); }
    .kat-hero .kat-search { margin-top: 34px; }
    .kat-hero__tags { display: flex; flex-wrap: wrap; gap: 8px 18px; margin-top: 22px; font-size: 13px; color: var(--text_muted); }
    .kat-hero__tags a { text-decoration: none; border-bottom: 1px dotted var(--text_muted); }
    .kat-hero__tags a:hover { color: var(--secondary); border-color: var(--secondary); }

    .kat-toc { border: 1px solid var(--text); background: var(--bg_card); padding: 24px 26px 18px; position: relative; }
    .kat-toc::after { content: ""; position: absolute; inset: 6px -6px -6px 6px; border: 1px solid var(--text); z-index: -1; }
    .kat-toc__head { display: flex; justify-content: space-between; align-items: baseline; padding-bottom: 14px; border-bottom: 1px solid var(--text); }
    .kat-toc__head h2 { font-size: 28px; font-style: italic; }
    .kat-toc a { display: flex; align-items: baseline; gap: 10px; padding: 10px 0; text-decoration: none; font-size: 15px; }
    .kat-toc a + a { border-top: 1px solid var(--border); }
    .kat-toc__no { flex: 0 0 26px; font-size: 11px; font-weight: 600; letter-spacing: .1em; color: var(--secondary); font-variant-numeric: tabular-nums; }
    .kat-toc__name { flex: 0 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-family: var(--font_heading, Fraunces, serif); font-size: 19px; }
    .kat-toc__dots { flex: 1 1 20px; border-bottom: 2px dotted var(--border); transform: translateY(-4px); }
    .kat-toc__n { flex: 0 0 auto; font-size: 12px; color: var(--text_muted); font-variant-numeric: tabular-nums; }
    .kat-toc a:hover .kat-toc__name { color: var(--secondary); font-style: italic; }
    .kat-toc__foot { display: block; margin-top: 6px; padding-top: 14px; border-top: 1px solid var(--text); }

    .kat-figures { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); border-block: 1px solid var(--text); }
    .kat-figures > div { padding: 22px 20px; border-left: 1px solid var(--border); }
    .kat-figures > div:first-child { border-left: 0; padding-left: 0; }
    .kat-figures strong { display: block; font-family: var(--font_heading, Fraunces, serif); font-size: clamp(34px, 4.4vw, 56px); font-weight: 300; line-height: 1; letter-spacing: -.04em; }
    .kat-figures span { display: block; margin-top: 8px; font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    @media (max-width: 760px) { .kat-figures { grid-template-columns: 1fr 1fr; } .kat-figures > div:nth-child(3) { border-left: 0; padding-left: 0; } .kat-figures > div:nth-child(n+3) { border-top: 1px solid var(--border); } }

    /* ── Bölüm başlığı ───────────────────────────────────────────────── */
    .kat-sec { padding-block: clamp(44px, 6vw, 84px) 0; }
    .kat-sec__head { display: flex; align-items: flex-end; justify-content: space-between; gap: 20px; padding-bottom: 18px; margin-bottom: 30px; border-bottom: 1px solid var(--text); }
    .kat-sec__title { display: flex; align-items: flex-end; gap: 18px; }
    .kat-sec__no { font-family: var(--font_heading, Fraunces, serif); font-size: clamp(54px, 7vw, 92px); font-weight: 300; line-height: .8; color: transparent; -webkit-text-stroke: 1px var(--secondary); letter-spacing: -.04em; }
    .kat-sec__head h2 { font-size: clamp(30px, 4vw, 52px); }
    .kat-sec__head p { margin-top: 8px; max-width: 54ch; color: var(--text_muted); font-size: 15px; }
    @media (max-width: 640px) { .kat-sec__head { flex-direction: column; align-items: flex-start; } }

    /* ── Levha (firma kartı) ─────────────────────────────────────────── */
    .kat-plates { display: grid; grid-template-columns: repeat(12, minmax(0, 1fr)); gap: 28px 24px; }
    .kat-plate { grid-column: span 4; display: flex; flex-direction: column; text-decoration: none; background: var(--bg_card); border: 1px solid var(--border); transition: transform .25s ease, border-color .25s ease, box-shadow .25s ease; position: relative; }
    .kat-plate:hover { transform: translate(-3px, -3px); border-color: var(--text); box-shadow: 6px 6px 0 var(--text); }
    .kat-plate--lead { grid-column: span 7; grid-row: span 2; }
    .kat-plate--lead + .kat-plate, .kat-plate--lead + .kat-plate + .kat-plate { grid-column: span 5; }
    .kat-plate__fig { position: relative; aspect-ratio: 4/3; overflow: hidden; background: var(--primary); display: grid; place-items: center; }
    .kat-plate--lead .kat-plate__fig { aspect-ratio: auto; flex: 1 1 auto; min-height: 340px; }
    .kat-plate--lead ~ .kat-plate:nth-child(n+4) { grid-column: span 6; }
    .kat-plate--lead ~ .kat-plate:nth-child(n+4) .kat-plate__fig { aspect-ratio: 16/9; }
    .kat-plate__fig img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
    .kat-plate:hover .kat-plate__fig img { transform: scale(1.04); }
    .kat-plate__init { font-family: var(--font_heading, Fraunces, serif); font-style: italic; font-weight: 300; font-size: clamp(90px, 12vw, 170px); line-height: 1; color: rgba(255,255,255,.88); }
    .kat-plate--lead .kat-plate__init { font-size: clamp(120px, 16vw, 240px); }
    .kat-plate:nth-child(4n+2) .kat-plate__fig:not(:has(img)) { background: var(--secondary); }
    .kat-plate:nth-child(4n+3) .kat-plate__fig:not(:has(img)) { background: color-mix(in srgb, var(--accent) 88%, #000); }
    .kat-plate:nth-child(4n+3) .kat-plate__init { color: rgba(27,24,19,.8); }
    .kat-plate__fig:not(:has(img))::before { content: ""; position: absolute; inset: 14px; border: 1px solid rgba(255,255,255,.35); }
    .kat-plate__folio { position: absolute; left: 0; top: 0; z-index: 2; padding: 7px 12px; background: var(--bg); font-size: 11px; font-weight: 600; letter-spacing: .16em; }
    .kat-plate__flag { position: absolute; right: 0; top: 16px; z-index: 2; padding: 6px 12px 6px 14px; background: var(--accent); color: #1b1813; font-size: 10px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
    .kat-plate__body { display: flex; flex-direction: column; gap: 8px; flex: 1; padding: 20px 22px 22px; }
    .kat-plate__cat { font-size: 11px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--secondary); }
    .kat-plate h3 { font-size: 26px; line-height: 1.08; }
    .kat-plate--lead h3 { font-size: clamp(30px, 3.4vw, 44px); }
    .kat-plate__text { font-size: 14.5px; color: var(--text_muted); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .kat-plate__foot { margin-top: auto; padding-top: 14px; display: flex; justify-content: space-between; align-items: center; gap: 12px; border-top: 1px solid var(--border); font-size: 12.5px; color: var(--text_muted); }
    .kat-plate__foot b { font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--text); }
    @media (max-width: 980px) { .kat-plate, .kat-plate--lead, .kat-plate--lead + .kat-plate, .kat-plate--lead + .kat-plate + .kat-plate, .kat-plate--lead ~ .kat-plate:nth-child(n+4) { grid-column: span 6; grid-row: auto; } .kat-plate--lead .kat-plate__fig { aspect-ratio: 4/3; min-height: 0; } }
    @media (max-width: 640px) { .kat-plate, .kat-plate--lead, .kat-plate--lead + .kat-plate, .kat-plate--lead + .kat-plate + .kat-plate, .kat-plate--lead ~ .kat-plate:nth-child(n+4) { grid-column: 1 / -1; } }

    /* ── Dizin (kategori) ────────────────────────────────────────────── */
    .kat-index { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); border-top: 1px solid var(--text); border-left: 1px solid var(--text); }
    .kat-index a { position: relative; min-height: 168px; padding: 20px 20px 18px; display: flex; flex-direction: column; justify-content: space-between; text-decoration: none; border-right: 1px solid var(--text); border-bottom: 1px solid var(--text); background: var(--bg_card); transition: background .22s ease, color .22s ease; }
    .kat-index a:hover { background: var(--primary); color: var(--bg); }
    .kat-index__no { font-size: 11px; font-weight: 600; letter-spacing: .16em; color: var(--secondary); }
    .kat-index a:hover .kat-index__no { color: var(--accent); }
    .kat-index__name { font-family: var(--font_heading, Fraunces, serif); font-size: 26px; line-height: 1.08; letter-spacing: -.02em; }
    .kat-index__meta { display: flex; justify-content: space-between; font-size: 12px; letter-spacing: .1em; text-transform: uppercase; color: var(--text_muted); }
    .kat-index a:hover .kat-index__meta { color: rgba(255,255,255,.7); }
    @media (max-width: 1080px) { .kat-index { grid-template-columns: repeat(3, minmax(0, 1fr)); } }
    @media (max-width: 760px) { .kat-index { grid-template-columns: repeat(2, minmax(0, 1fr)); } .kat-index a { min-height: 140px; } .kat-index__name { font-size: 21px; } }

    /* ── Şehirler ────────────────────────────────────────────────────── */
    .kat-cities { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); column-gap: 40px; }
    .kat-cities a { display: flex; align-items: baseline; gap: 14px; padding: 16px 0; border-bottom: 1px solid var(--border); text-decoration: none; }
    .kat-cities__name { font-family: var(--font_heading, Fraunces, serif); font-size: clamp(26px, 3vw, 40px); letter-spacing: -.03em; line-height: 1; transition: color .2s ease, padding-left .2s ease; }
    .kat-cities a:hover .kat-cities__name { color: var(--secondary); padding-left: 8px; font-style: italic; }
    .kat-cities__n { margin-left: auto; font-size: 12px; font-weight: 600; letter-spacing: .1em; color: var(--text_muted); font-variant-numeric: tabular-nums; }
    @media (max-width: 900px) { .kat-cities { grid-template-columns: repeat(2, minmax(0, 1fr)); column-gap: 24px; } }
    @media (max-width: 560px) { .kat-cities { grid-template-columns: 1fr; } }

    /* ── Yazılar ─────────────────────────────────────────────────────── */
    .kat-journal { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 34px; }
    .kat-article { display: flex; flex-direction: column; gap: 12px; text-decoration: none; }
    .kat-article__fig { aspect-ratio: 3/2; overflow: hidden; background: var(--primary_light); border: 1px solid var(--border); display: grid; place-items: center; font-family: var(--font_heading, Fraunces, serif); font-size: 64px; font-style: italic; color: var(--primary); }
    .kat-article__fig img { width: 100%; height: 100%; object-fit: cover; transition: transform .6s ease; }
    .kat-article:hover .kat-article__fig img { transform: scale(1.04); }
    .kat-article h3 { font-size: 26px; line-height: 1.08; }
    .kat-article:hover h3 { color: var(--secondary); }
    .kat-article p { font-size: 14.5px; color: var(--text_muted); }
    @media (max-width: 900px) { .kat-journal { grid-template-columns: 1fr; } }

    /* ── Satır listesi (firma / yazı / ilan) ─────────────────────────── */
    .kat-entries { border-top: 1px solid var(--text); }
    .kat-entry { display: grid; grid-template-columns: 54px 84px minmax(0, 1fr) auto; align-items: center; gap: 20px; padding: 22px 6px; border-bottom: 1px solid var(--border); transition: background .2s ease, padding .2s ease; }
    .kat-entry:hover { background: var(--bg_card); padding-inline: 16px; }
    .kat-entry__no { font-family: var(--font_heading, Fraunces, serif); font-size: 30px; font-weight: 300; color: var(--secondary); line-height: 1; font-variant-numeric: tabular-nums; }
    .kat .kat-entry__mark { width: 84px; height: 84px; display: grid; place-items: center; background: var(--primary); color: #fff; font-family: var(--font_heading, Fraunces, serif); font-size: 38px; font-style: italic; overflow: hidden; text-decoration: none; }
    .kat-entry__mark img { width: 100%; height: 100%; object-fit: cover; }
    .kat-entry__cat { font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--secondary); }
    .kat-entry h3 { margin-top: 4px; font-size: clamp(22px, 2.4vw, 30px); line-height: 1.08; }
    .kat-entry h3 a { text-decoration: none; }
    .kat-entry:hover h3 a { color: var(--secondary); font-style: italic; }
    .kat-entry__text { margin-top: 6px; max-width: 70ch; font-size: 14.5px; color: var(--text_muted); display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .kat-entry__go { font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; white-space: nowrap; border-bottom: 1px solid currentColor; padding-bottom: 2px; }
    .kat-entry__go:hover { color: var(--secondary); }
    @media (max-width: 760px) {
        .kat-entry { grid-template-columns: 64px minmax(0, 1fr); gap: 14px; }
        .kat-entry__no { display: none; }
        .kat-entry__mark { width: 64px; height: 64px; font-size: 28px; }
        .kat-entry__go { grid-column: 2; justify-self: start; }
    }
    .kat-entry--job { grid-template-columns: 54px minmax(0, 1fr) auto; }
    @media (max-width: 760px) { .kat-entry--job { grid-template-columns: minmax(0, 1fr); } }
    .kat-empty { padding: 56px 20px; text-align: center; font-family: var(--font_heading, Fraunces, serif); font-style: italic; font-size: 24px; color: var(--text_muted); }
    .kat-empty a { color: var(--secondary); }

    /* ── Sayfa başlığı (iç sayfalar) ─────────────────────────────────── */
    .kat-band { padding-block: clamp(28px, 4vw, 54px) clamp(26px, 4vw, 46px); border-bottom: 1px solid var(--text); }
    .kat-crumb { display: flex; flex-wrap: wrap; gap: 8px 12px; margin-bottom: 26px; font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .kat-crumb a { text-decoration: none; }
    .kat-crumb a:hover { color: var(--secondary); }
    .kat-band__grid { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 30px; align-items: end; }
    .kat-band h1 { margin-top: 18px; font-size: clamp(40px, 6.6vw, 92px); line-height: .96; letter-spacing: -.04em; max-width: 18ch; }
    .kat-band__lede { margin-top: 20px; max-width: 60ch; font-size: 17.5px; color: var(--text_muted); }
    .kat-band__folio { font-family: var(--font_heading, Fraunces, serif); font-size: clamp(80px, 12vw, 180px); font-weight: 300; line-height: .8; color: transparent; -webkit-text-stroke: 1px var(--border); letter-spacing: -.05em; user-select: none; }
    @media (max-width: 760px) { .kat-band__grid { grid-template-columns: 1fr; } .kat-band__folio { display: none; } }

    /* ── Filtre ──────────────────────────────────────────────────────── */
    .kat-filters { display: flex; flex-wrap: wrap; align-items: flex-end; gap: 14px 22px; padding-block: 22px; margin-bottom: 8px; border-bottom: 1px solid var(--border); }
    .kat-filters label { display: flex; flex-direction: column; gap: 6px; font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .kat-filters :is(input, select) { min-width: 200px; padding: 8px 2px; border: 0; border-bottom: 1px solid var(--text); border-radius: 0; background: transparent; color: var(--text); font-family: var(--font_heading, Fraunces, serif); font-size: 19px; outline: none; }
    .kat-filters :is(input, select):focus { border-bottom-color: var(--secondary); }
    .kat-filters a:not(.kat-btn) { font-size: 12px; letter-spacing: .12em; text-transform: uppercase; color: var(--secondary); padding-bottom: 10px; }

    /* ── İki sütun & paneller ────────────────────────────────────────── */
    .kat-cols { display: grid; grid-template-columns: minmax(0, 1fr) 330px; gap: clamp(26px, 4vw, 56px); align-items: start; padding-top: 18px; }
    @media (max-width: 980px) { .kat-cols { grid-template-columns: 1fr; } }
    .kat-side { display: grid; gap: 26px; }
    @media (min-width: 981px) { .kat-side { position: sticky; top: 22px; } }
    .kat-panel { border-top: 2px solid var(--text); background: transparent; }
    .kat-panel + .kat-panel { margin-top: 34px; }
    .kat-panel__head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; padding: 14px 0 12px; border-bottom: 1px solid var(--border); }
    .kat-panel__head h2 { font-size: 26px; font-style: italic; }
    .kat-panel__head span { font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .kat-panel__body { padding-block: 18px; }
    .kat-panel--card { background: var(--bg_card); border: 1px solid var(--border); border-top: 2px solid var(--text); padding-inline: 22px; }
    .kat-links > a { display: flex; align-items: baseline; gap: 12px; padding: 12px 0; border-bottom: 1px solid var(--border); text-decoration: none; transition: padding-left .2s ease, color .2s ease; }
    .kat-links > a:hover { padding-left: 8px; color: var(--secondary); }
    .kat-links__no { flex: 0 0 auto; font-size: 11px; font-weight: 600; letter-spacing: .1em; color: var(--secondary); font-variant-numeric: tabular-nums; }
    .kat-links__txt { flex: 1 1 auto; min-width: 0; font-family: var(--font_heading, Fraunces, serif); font-size: 18px; line-height: 1.2; }
    .kat-links__count { flex: 0 0 auto; font-size: 12px; color: var(--text_muted); font-variant-numeric: tabular-nums; }
    .kat-links__go { flex: 0 0 auto; color: var(--text_muted); }
    .kat-promo { padding: 28px 26px; background: var(--primary); color: #eef2ec; position: relative; }
    .kat-promo::after { content: ""; position: absolute; inset: 7px; border: 1px solid rgba(255,255,255,.25); pointer-events: none; }
    .kat-promo .kat-kicker { color: var(--accent); }
    .kat-promo h2 { margin-top: 14px; font-size: 32px; color: #fff; }
    .kat-promo h2 em { color: var(--accent); }
    .kat-promo p { margin-top: 12px; font-size: 14.5px; color: rgba(238,242,236,.8); }
    .kat-promo .kat-btn { position: relative; z-index: 1; margin-top: 20px; }

    /* ── Prosa ───────────────────────────────────────────────────────── */
    .kat-prose { font-size: 17px; line-height: 1.8; }
    .kat .kat-prose > * + * { margin-top: 16px; }
    .kat-prose :is(h2, h3) { margin-top: 36px; font-size: 32px; }
    .kat-prose a { color: var(--secondary); text-underline-offset: 3px; }
    .kat-prose ul { padding-left: 22px; list-style: disc; }
    .kat-prose ol { padding-left: 22px; list-style: decimal; }
    .kat-prose blockquote { margin-inline: 0; padding: 4px 0 4px 22px; border-left: 3px solid var(--secondary); font-family: var(--font_heading, Fraunces, serif); font-style: italic; font-size: 24px; line-height: 1.35; }
    .kat-prose img { max-width: 100%; height: auto; }
    .kat-prose--drop > p:first-of-type::first-letter { float: left; margin: 6px 12px 0 0; font-family: var(--font_heading, Fraunces, serif); font-size: 88px; line-height: .78; color: var(--secondary); }
    .kat-prose details { border-bottom: 1px solid var(--border); }
    .kat-prose summary { padding: 14px 0; font-family: var(--font_heading, Fraunces, serif); font-size: 21px; cursor: pointer; }
    .kat-prose details p { padding: 0 0 14px; color: var(--text_muted); }
    .kat-lead { display: grid; grid-template-columns: minmax(0, 1fr) minmax(0, 1.1fr); gap: 36px; align-items: center; padding-block: 30px; border-bottom: 1px solid var(--text); }
    .kat-lead h2 { margin: 16px 0 14px; font-size: clamp(32px, 4.4vw, 58px); }
    .kat-lead p { color: var(--text_muted); }
    .kat-lead__fig { aspect-ratio: 4/3; overflow: hidden; background: var(--primary); }
    .kat-lead__fig img { width: 100%; height: 100%; object-fit: cover; }
    @media (max-width: 800px) { .kat-lead { grid-template-columns: 1fr; } .kat-lead__fig { order: -1; } }

    /* ── Paketler ────────────────────────────────────────────────────── */
    .kat-plans { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 24px; padding-top: 30px; }
    @media (max-width: 900px) { .kat-plans { grid-template-columns: 1fr; max-width: 480px; margin-inline: auto; } }
    .kat-plan { position: relative; display: flex; flex-direction: column; gap: 16px; padding: 30px 28px; background: var(--bg_card); border: 1px solid var(--border); }
    .kat-plan--featured { background: var(--primary); color: #eef2ec; border-color: var(--primary); transform: translateY(-14px); }
    .kat-plan--featured .kat-plan__cat, .kat-plan--featured small { color: rgba(238,242,236,.7); }
    .kat-plan--featured h2, .kat-plan--featured .kat-plan__price { color: #fff; }
    .kat-plan--featured li::before { color: var(--accent); }
    .kat-plan__cat { font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .kat-plan__badge { position: absolute; right: 0; top: 22px; padding: 6px 14px; background: var(--accent); color: #1b1813; font-size: 10px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
    .kat-plan h2 { font-size: 34px; }
    .kat-plan__price { font-family: var(--font_heading, Fraunces, serif); font-size: 52px; font-weight: 300; line-height: 1; letter-spacing: -.04em; }
    .kat-plan__price small { display: block; margin-top: 6px; font-family: var(--font_body); font-size: 12px; letter-spacing: .1em; text-transform: uppercase; color: var(--text_muted); }
    .kat-plan ul { display: grid; gap: 10px; padding-top: 16px; border-top: 1px solid currentColor; }
    .kat-plan li { position: relative; padding-left: 24px; font-size: 14.5px; }
    .kat-plan li::before { content: "✦"; position: absolute; left: 0; top: 0; font-size: 11px; line-height: 2; color: var(--secondary); }
    .kat-plan .kat-btn { margin-top: auto; }
    .kat-plan--featured .kat-btn { background: var(--accent); border-color: var(--accent); color: #1b1813 !important; }

    /* ── Form / not ──────────────────────────────────────────────────── */
    .kat-form { display: grid; grid-template-columns: 1fr 1fr; gap: 26px 28px; }
    @media (max-width: 600px) { .kat-form { grid-template-columns: 1fr; } }
    .kat-form label { display: flex; flex-direction: column; gap: 6px; font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .kat-form .kat-wide { grid-column: 1 / -1; }
    .kat-form :is(input, select, textarea) { padding: 8px 2px; border: 0; border-bottom: 1px solid var(--text); border-radius: 0; background: transparent; color: var(--text); font-family: var(--font_heading, Fraunces, serif); font-size: 20px; letter-spacing: 0; text-transform: none; outline: none; }
    .kat-form textarea { border: 1px solid var(--text); padding: 12px; font-family: var(--font_body); font-size: 16px; }
    .kat-form :is(input, select, textarea):focus { border-color: var(--secondary); }
    .kat-note { padding: 14px 18px; border-left: 3px solid var(--secondary); background: var(--bg_card); font-size: 15px; }

    /* ── Sayfalama ───────────────────────────────────────────────────── */
    .kat-pag { padding-top: 26px; }
    .kat-pag nav > div:first-child { display: none; }
    .kat-pag nav { display: flex; flex-wrap: wrap; justify-content: center; }
    .kat-pag :is(a, span[aria-current], span[aria-disabled]) { display: inline-flex; align-items: center; justify-content: center; min-width: 42px; height: 42px; margin: 3px; padding: 0 12px; border: 1px solid var(--border); background: var(--bg_card); font-size: 14px; text-decoration: none; color: var(--text); }
    .kat-pag a:hover { border-color: var(--text); }
    .kat-pag [aria-current="page"] > span, .kat-pag span[aria-current="page"] { background: var(--text); color: var(--bg); border-color: var(--text); }
    .kat-pag svg { width: 14px; height: 14px; }

    /* ── Alt bilgi ───────────────────────────────────────────────────── */
    .kat-cta { margin-top: clamp(54px, 7vw, 96px); background: var(--primary); color: #eef2ec; padding-block: clamp(50px, 7vw, 96px); position: relative; overflow: hidden; }
    .kat-cta__grid { display: grid; grid-template-columns: minmax(0, 1fr) auto; gap: 40px; align-items: end; }
    .kat-cta h2 { font-size: clamp(40px, 6.4vw, 96px); line-height: .96; letter-spacing: -.045em; color: #fff; max-width: 14ch; }
    .kat-cta h2 em { color: var(--accent); }
    .kat-cta p { margin-top: 20px; max-width: 48ch; color: rgba(238,242,236,.78); font-size: 17px; }
    .kat-cta__actions { display: flex; flex-wrap: wrap; gap: 12px; }
    .kat-cta .kat-btn--ghost { color: #fff !important; border-color: rgba(255,255,255,.5); }
    .kat-cta .kat-btn--ghost:hover { background: #fff; color: var(--primary) !important; }
    @media (max-width: 800px) { .kat-cta__grid { grid-template-columns: 1fr; } }
    .kat-footer { background: #15130f; color: #cfc6b4; padding-top: 60px; }
    .kat-footer a { text-decoration: none; }
    .kat-footer a:hover { color: var(--accent); }
    .kat-footer__grid { display: grid; grid-template-columns: 1.6fr 1fr 1fr 1fr; gap: 40px; padding-bottom: 46px; }
    .kat-footer h3 { margin-bottom: 16px; font-family: var(--font_body); font-size: 11px; font-weight: 600; letter-spacing: .2em; text-transform: uppercase; color: var(--accent); }
    .kat-footer li + li { margin-top: 9px; }
    .kat-footer__brand { font-family: var(--font_heading, Fraunces, serif); font-size: 34px; color: #fff; letter-spacing: -.03em; line-height: 1; }
    .kat-footer__brand i { color: var(--secondary); font-style: normal; }
    .kat-footer__about { margin-top: 16px; max-width: 38ch; font-size: 14.5px; color: #9d9482; }
    .kat-footer__word { overflow: hidden; border-top: 1px solid #2c2820; padding-block: 18px 0; font-family: var(--font_heading, Fraunces, serif); font-size: clamp(60px, 15vw, 220px); font-weight: 300; line-height: .85; letter-spacing: -.06em; white-space: nowrap; color: #24211a; user-select: none; }
    .kat-footer__legal { display: flex; justify-content: space-between; flex-wrap: wrap; gap: 10px; padding-block: 20px; border-top: 1px solid #2c2820; font-size: 12px; letter-spacing: .08em; color: #8a8271; }
    @media (max-width: 860px) { .kat-footer__grid { grid-template-columns: 1fr 1fr; } }
    @media (max-width: 520px) { .kat-footer__grid { grid-template-columns: 1fr; } }

    /* ── Firma profili ───────────────────────────────────────────────── */
    .kat-detail__cover { position: relative; aspect-ratio: 21/7; max-height: 420px; width: 100%; overflow: hidden; background: var(--primary); border-bottom: 1px solid var(--text); }
    .kat-detail__cover img { width: 100%; height: 100%; object-fit: cover; }
    .kat-detail__title { padding-block: 40px 30px; display: grid; grid-template-columns: auto minmax(0, 1fr); gap: 34px; align-items: end; border-bottom: 1px solid var(--text); }
    .kat-detail__logo { width: 132px; height: 132px; display: grid; place-items: center; background: var(--primary); color: #fff; font-family: var(--font_heading, Fraunces, serif); font-style: italic; font-size: 64px; border: 1px solid var(--text); overflow: hidden; }
    .kat-detail__logo img { width: 100%; height: 100%; object-fit: cover; }
    .kat-detail__title h1 { margin-top: 14px; font-size: clamp(40px, 6vw, 84px); line-height: .96; letter-spacing: -.04em; }
    .kat-detail__title .kat-band__lede { margin-top: 16px; }
    .kat-detail__actions { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-top: 24px; }
    .kat-detail__flag { padding: 8px 14px; background: var(--accent); color: #1b1813; font-size: 10.5px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; }
    @media (max-width: 700px) { .kat-detail__title { grid-template-columns: 1fr; gap: 20px; } .kat-detail__logo { width: 96px; height: 96px; font-size: 46px; } }
    .kat-colophon { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); border-bottom: 1px solid var(--text); margin: 0; }
    .kat-colophon > div { padding: 18px 20px; border-left: 1px solid var(--border); }
    .kat-colophon > div:first-child { border-left: 0; padding-left: 0; }
    .kat-colophon dt { font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .kat-colophon dd { margin: 6px 0 0; font-family: var(--font_heading, Fraunces, serif); font-size: 20px; line-height: 1.2; }
    .kat-colophon dd a { text-decoration: none; border-bottom: 1px solid var(--border); }
    .kat-colophon dd a:hover { color: var(--secondary); border-color: var(--secondary); }
    @media (max-width: 800px) { .kat-colophon { grid-template-columns: 1fr 1fr; } .kat-colophon > div:nth-child(3) { border-left: 0; padding-left: 0; } .kat-colophon > div:nth-child(n+3) { border-top: 1px solid var(--border); } }
    .kat-detail__body { display: grid; grid-template-columns: minmax(0, 1fr) 350px; gap: clamp(26px, 4vw, 56px); align-items: start; padding-block: 40px 20px; }
    @media (max-width: 980px) { .kat-detail__body { grid-template-columns: 1fr; } }
    .kat-detail__main, .kat-detail__side { display: grid; gap: 30px; }
    @media (min-width: 981px) { .kat-detail__side { position: sticky; top: 22px; } }

    /* td-* (firma detayında paylaşılan sections/sidebar parçaları) */
    .kat-detail .td-section { border-top: 2px solid var(--text); padding-top: 4px; }
    .kat-detail .td-side-card { border: 1px solid var(--border); border-top: 2px solid var(--text); background: var(--bg_card); padding: 22px; }
    .kat-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 12px; padding: 12px 0 14px; margin-bottom: 14px; border-bottom: 1px solid var(--border); }
    .kat-detail .td-index { font-size: 11px; font-weight: 600; letter-spacing: .16em; color: var(--secondary); }
    .kat-detail .td-section-head h2, .kat-detail .td-side-card h2 { font-size: 28px; font-style: italic; }
    .kat-detail .td-side-card h2 { margin-top: 8px; font-size: 24px; }
    .kat-detail .td-section-head > a, .kat-detail .td-count { margin-left: auto; font-size: 11px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; color: var(--text_muted); }
    .kat-detail .td-prose { font-size: 16.5px; line-height: 1.8; }
    .kat-detail .td-prose p + p { margin-top: 14px; }
    .kat-detail .td-prose :is(h2, h3) { margin: 22px 0 8px; font-size: 24px; }
    .kat-detail .td-prose ul { padding-left: 22px; list-style: disc; }
    .kat-detail .td-share { margin-top: 20px; padding-top: 14px; border-top: 1px solid var(--border); }
    .kat-detail .td-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 16px; }
    @media (max-width: 620px) { .kat-detail .td-card-grid { grid-template-columns: 1fr; } }
    .kat-detail .td-card { display: flex; flex-direction: column; gap: 6px; padding: 16px; border: 1px solid var(--border); background: var(--bg_card); text-decoration: none; transition: border-color .2s ease, box-shadow .2s ease; }
    .kat-detail .td-card:hover { border-color: var(--text); box-shadow: 4px 4px 0 var(--text); }
    .kat-detail .td-card-image { width: 100%; aspect-ratio: 4/3; object-fit: cover; margin-bottom: 6px; }
    .kat-detail .td-card small { font-size: 10.5px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; color: var(--secondary); }
    .kat-detail .td-card h3 { font-size: 21px; }
    .kat-detail .td-card p { font-size: 14px; color: var(--text_muted); }
    .kat-detail .td-price { font-family: var(--font_heading, Fraunces, serif); font-size: 19px; color: var(--secondary); }
    .kat-detail .td-linebreak { white-space: pre-line; }
    .kat-detail .td-gallery { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 8px; }
    @media (max-width: 620px) { .kat-detail .td-gallery { grid-template-columns: repeat(2, 1fr); } }
    .kat-detail .td-gallery img { width: 100%; aspect-ratio: 1; object-fit: cover; }
    .kat-detail .td-map { overflow: hidden; border: 1px solid var(--text); }
    .kat-detail .td-map iframe { display: block; width: 100%; height: 340px; border: 0; }
    .kat-detail .td-reviews { display: grid; gap: 14px; margin-bottom: 20px; }
    .kat-detail .td-review { padding: 16px 18px; border: 1px solid var(--border); background: var(--bg_card); }
    .kat-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 10px; font-size: 13.5px; }
    .kat-detail .td-review span { color: var(--text_muted); }
    .kat-detail .td-stars { color: var(--accent); }
    .kat-detail .td-empty { color: var(--text_muted); font-size: 14px; }
    .kat-detail .td-form-title { margin: 8px 0 14px; font-size: 22px; font-style: italic; }
    .kat-detail .td-review-form { display: grid; gap: 12px; }
    .kat-detail .td-review-form :is(input, select, textarea) { padding: 12px 14px; border: 1px solid var(--border); border-radius: 0; background: var(--bg_card); color: var(--text); font: inherit; font-size: 15px; }
    .kat-detail .td-review-form :is(input, select, textarea):focus { outline: none; border-color: var(--text); }
    .kat-detail .td-review-form button { justify-self: start; padding: 14px 24px; border: 1px solid var(--text); background: var(--text); color: var(--bg); font: inherit; font-size: 12px; font-weight: 600; letter-spacing: .16em; text-transform: uppercase; cursor: pointer; }
    .kat-detail .td-review-form button:hover { background: var(--secondary); border-color: var(--secondary); }
    .kat-detail .td-message { padding: 12px 16px; border-left: 3px solid var(--secondary); background: var(--bg_card); font-size: 14.5px; }
    .kat-detail .td-faq { border-bottom: 1px solid var(--border); }
    .kat-detail .td-faq summary { padding: 14px 0; font-family: var(--font_heading, Fraunces, serif); font-size: 20px; cursor: pointer; }
    .kat-detail .td-faq p { padding: 0 0 14px; color: var(--text_muted); }
    .kat-detail .td-side-label { font-size: 10.5px; font-weight: 600; letter-spacing: .18em; text-transform: uppercase; color: var(--secondary); }
    .kat-detail .td-contact dl { margin: 14px 0 0; }
    .kat-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 11px 0; border-bottom: 1px solid var(--border); font-size: 14.5px; }
    .kat-detail .td-contact dt { color: var(--text_muted); }
    .kat-detail .td-contact dd { margin: 0; text-align: right; }
    .kat-detail .td-contact dd a { text-decoration: none; border-bottom: 1px solid var(--border); }
    .kat-detail .td-actions { display: grid; gap: 8px; margin-top: 18px; }
    .kat-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 13px 14px; border: 1px solid var(--text); font-size: 12px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; transition: background .2s ease, color .2s ease; }
    .kat-detail .td-actions a:hover { background: var(--text); color: var(--bg); }
    .kat-detail .td-claim > a { display: inline-flex; margin-top: 12px; padding: 12px 18px; background: var(--secondary); color: #fff; font-size: 12px; font-weight: 600; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; }
    .kat-detail .td-claim small { display: block; margin-top: 10px; color: var(--text_muted); font-size: 12px; }
    .kat-detail .td-related a { display: flex; flex-direction: column; gap: 2px; padding: 11px 0; border-bottom: 1px solid var(--border); text-decoration: none; }
    .kat-detail .td-related a:hover { color: var(--secondary); }
    .kat-detail .td-related span { font-size: 12.5px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .kat *, .kat *::before, .kat *::after { transition: none !important; animation: none !important; } }
    @media print { .kat-header, .kat-footer, .kat-cta { display: none; } }
</style>
