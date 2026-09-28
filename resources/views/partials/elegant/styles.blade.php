{{-- Elegant Premium · lüks editöryal tasarım sistemi (.el) --}}
<style>
    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .el {
        color: var(--text);
        background: var(--bg);
        font-family: var(--font_body, Jost, sans-serif);
        font-size: 15px;
        line-height: 1.65;
        font-weight: 300;
        letter-spacing: .01em;
    }
    .el * { box-sizing: border-box; }
    .el a { color: inherit; }
    .el :is(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-weight: 600; letter-spacing: 0; line-height: 1.12; color: var(--primary); }
    .el p { margin: 0; }
    .el-wrap { width: min(100% - 40px, var(--page_width, 1120px)); margin-inline: auto; }
    .el-page { padding-block: 40px 72px; }
    .el-eyebrow { display: inline-flex; align-items: center; gap: 10px; font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .34em; text-transform: uppercase; color: var(--accent); }
    .el-eyebrow::before { content: ""; width: 28px; height: 1px; background: var(--accent); display: inline-block; }
    .el-hair { height: 1px; background: var(--border); border: 0; margin: 0; }

    /* ── Düğmeler ────────────────────────────────────────────────────── */
    .el-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 26px; border: 1px solid var(--primary); border-radius: 1px; background: var(--primary); color: var(--btn_text, #fff); font-family: var(--font_body, Jost, sans-serif); font-weight: 500; font-size: 12.5px; letter-spacing: .16em; text-transform: uppercase; text-decoration: none; cursor: pointer; transition: background .2s ease, color .2s ease, border-color .2s ease; }
    .el-btn:hover { background: var(--primary_hover); border-color: var(--primary_hover); }
    .el-btn--gold { background: transparent; border-color: var(--accent); color: var(--accent); }
    .el-btn--gold:hover { background: var(--accent); color: #fff; }
    .el-btn--ghost { background: transparent; border-color: var(--border); color: var(--text); }
    .el-btn--ghost:hover { border-color: var(--primary); color: var(--primary); background: transparent; }

    /* ── Editöryal hero band (iç sayfalar) ───────────────────────────── */
    .el-band { position: relative; background: var(--bg_card); border-bottom: 1px solid var(--border); padding-block: 52px 46px; overflow: hidden; }
    .el-band::after { content: ""; position: absolute; left: 0; bottom: 0; width: 100%; height: 3px; background: linear-gradient(90deg, var(--accent), transparent 60%); }
    .el-band h1 { font-size: clamp(30px, 5vw, 52px); font-weight: 500; }
    .el-band h1 small { display: block; margin-top: 14px; font-family: var(--font_body, Jost, sans-serif); font-size: 12px; font-weight: 400; letter-spacing: .28em; text-transform: uppercase; color: var(--text_muted); }
    .el-band .el-eyebrow { margin-bottom: 18px; }
    .el-band__desc { margin-top: 18px; max-width: 62ch; font-size: 16px; color: var(--text_muted); font-weight: 300; line-height: 1.7; }
    .el-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; margin-bottom: 20px; font-size: 11.5px; letter-spacing: .14em; text-transform: uppercase; color: var(--text_muted); }
    .el-crumb a { text-decoration: none; color: var(--text_muted); }
    .el-crumb a:hover { color: var(--accent); }
    .el-crumb .el-crumb__sep { opacity: .5; }
    .el-crumb span:last-child { color: var(--primary); }

    /* ── Arama satırı (band içi) ─────────────────────────────────────── */
    .el-find { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 26px; }
    .el-find :is(input,select) { min-width: 0; flex: 1 1 200px; padding: 13px 16px; border: 1px solid var(--border); border-radius: 1px; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .el-find :is(input,select):focus { outline: none; border-color: var(--accent); }

    /* ── İstatistik şeridi ───────────────────────────────────────────── */
    .el-stats { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 1px; background: var(--border); border: 1px solid var(--border); }
    @media (max-width: 720px) { .el-stats { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    .el-stats > div { background: var(--bg_card); padding: 22px 18px; text-align: center; }
    .el-stats b { display: block; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 34px; font-weight: 600; color: var(--primary); line-height: 1; }
    .el-stats span { display: block; margin-top: 8px; font-size: 11px; letter-spacing: .2em; text-transform: uppercase; color: var(--text_muted); }

    /* ── Filtre şeridi ───────────────────────────────────────────────── */
    .el-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 14px; padding: 20px 22px; margin-bottom: 28px; border: 1px solid var(--border); border-radius: 2px; background: var(--bg_card); }
    .el-filters label { display: flex; flex-direction: column; gap: 6px; font-size: 10.5px; font-weight: 500; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .el-filters :is(input,select) { min-width: 190px; padding: 11px 13px; border: 1px solid var(--border); border-radius: 1px; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .el-filters :is(input,select):focus { outline: none; border-color: var(--accent); }
    .el-filters a { font-size: 12.5px; letter-spacing: .06em; color: var(--primary); text-decoration: none; padding-bottom: 12px; border-bottom: 1px solid transparent; }
    .el-filters a:hover { border-color: var(--accent); }

    /* ── Sütunlar ────────────────────────────────────────────────────── */
    .el-cols { display: grid; grid-template-columns: 260px minmax(0,1fr); gap: 40px; align-items: start; }
    @media (max-width: 900px) { .el-cols { grid-template-columns: 1fr; gap: 28px; } }
    .el-side { display: grid; gap: 32px; }
    @media (min-width: 901px) { .el-side { position: sticky; top: 24px; } }

    /* ── Panel kutusu ────────────────────────────────────────────────── */
    .el-box { border-top: 1px solid var(--border); padding-top: 20px; }
    .el-box + .el-box { margin-top: 32px; }
    .el-box__head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; margin-bottom: 16px; }
    .el-box__head h2 { font-size: 22px; font-weight: 500; }
    .el-box__head a, .el-box__head .el-box__note { font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; color: var(--text_muted); text-decoration: none; }
    .el-box__head a:hover { color: var(--accent); }
    .el-box__body { font-size: 15px; }

    /* ── Numaralı kategori dizini ────────────────────────────────────── */
    .el-rail { display: flex; flex-direction: column; }
    .el-rail > a { display: flex; align-items: baseline; gap: 14px; padding: 11px 0; border-bottom: 1px solid var(--border); text-decoration: none; font-size: 15px; color: var(--text); transition: color .16s ease, padding-left .16s ease; }
    .el-rail > a:last-child { border-bottom: 0; }
    .el-rail > a:hover { color: var(--primary); padding-left: 6px; }
    .el-idx { flex: 0 0 auto; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 13px; font-style: italic; color: var(--accent); font-variant-numeric: tabular-nums; }
    .el-rail > a .el-rail__txt { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .el-rail > a .el-rail__count { flex: 0 0 auto; font-size: 12px; color: var(--text_muted); font-variant-numeric: tabular-nums; }

    /* ── Bağlantı rayı ───────────────────────────────────────────────── */
    .el-links { display: flex; flex-direction: column; }
    .el-links > a { display: flex; align-items: baseline; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); text-decoration: none; font-size: 14.5px; transition: color .16s ease; }
    .el-links > a:last-child { border-bottom: 0; }
    .el-links > a:hover { color: var(--primary); }
    .el-links .el-links__txt { flex: 1 1 auto; min-width: 0; }
    .el-links .el-links__count { flex: 0 0 auto; font-size: 12px; color: var(--accent); }

    /* ── Vitrin kart ızgarası ────────────────────────────────────────── */
    .el-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 28px; }
    @media (max-width: 1000px) { .el-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 600px) { .el-grid { grid-template-columns: 1fr; } }
    .el-card { position: relative; display: flex; flex-direction: column; gap: 10px; padding: 26px 22px 22px; border: 1px solid var(--border); border-top: 2px solid var(--accent); background: var(--bg_card); text-decoration: none; transition: box-shadow .22s ease, transform .22s ease; }
    .el-card:hover { box-shadow: var(--card_shadow, 0 2px 18px rgba(31,58,86,.07)); transform: translateY(-2px); }
    .el-card__logo { width: 56px; height: 56px; display: grid; place-items: center; border: 1px solid var(--border); border-radius: 50%; background: var(--bg); color: var(--primary); font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 24px; overflow: hidden; }
    .el-card__logo img { width: 100%; height: 100%; object-fit: cover; }
    .el-card__cat { font-family: var(--font_body, Jost, sans-serif); font-size: 10.5px; font-weight: 500; letter-spacing: .2em; text-transform: uppercase; color: var(--accent); }
    .el-card h3 { font-size: 24px; font-weight: 500; }
    .el-card__meta { font-size: 13px; color: var(--text_muted); }
    .el-card__text { font-size: 14px; color: var(--text_muted); line-height: 1.6; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
    .el-card__foot { margin-top: auto; padding-top: 14px; border-top: 1px solid var(--border); display: flex; align-items: center; justify-content: space-between; font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; color: var(--primary); }
    .el-tag { position: absolute; top: 14px; right: 14px; font-family: var(--font_body, Jost, sans-serif); font-size: 9.5px; font-weight: 500; letter-spacing: .18em; text-transform: uppercase; color: #fff; background: var(--accent); padding: 3px 9px; }

    /* ── Liste satırı (firmalar / blog / iş) ─────────────────────────── */
    .el-items { display: flex; flex-direction: column; }
    .el-item { display: flex; align-items: stretch; gap: 22px; padding: 22px 0; border-top: 1px solid var(--border); text-decoration: none; transition: background .16s ease; }
    .el-item:last-child { border-bottom: 1px solid var(--border); }
    .el-item:hover { background: rgba(184,145,47,.04); }
    .el-item__thumb { flex: 0 0 92px; display: grid; place-items: center; border: 1px solid var(--border); background: var(--bg_card); color: var(--primary); font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 30px; overflow: hidden; min-height: 76px; }
    .el-item__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .el-item__body { flex: 1 1 auto; min-width: 0; display: flex; flex-direction: column; gap: 5px; }
    .el-item__body h3 { font-size: 22px; font-weight: 500; }
    .el-item:hover .el-item__body h3 { color: var(--primary_hover); }
    .el-item__meta { font-family: var(--font_body, Jost, sans-serif); font-size: 10.5px; font-weight: 500; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .el-item__meta b { color: var(--accent); font-weight: 500; }
    .el-item__text { font-size: 14px; color: var(--text_muted); overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
    .el-item__side { flex: 0 0 130px; display: flex; flex-direction: column; align-items: flex-end; justify-content: center; gap: 8px; text-align: right; }
    .el-item__date { font-size: 12px; color: var(--text_muted); }
    .el-item__go { font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; color: var(--primary); }
    @media (max-width: 620px) {
        .el-item { flex-wrap: wrap; gap: 14px; }
        .el-item__side { flex-direction: row; justify-content: space-between; width: 100%; flex-basis: 100%; }
    }

    /* ── Vurgu bloğu ─────────────────────────────────────────────────── */
    .el-promo { border: 1px solid var(--border); border-top: 2px solid var(--accent); background: var(--bg_card); padding: 26px 22px; text-align: center; }
    .el-promo h2 { font-size: 24px; font-weight: 500; }
    .el-promo p { margin-top: 8px; font-size: 14px; color: var(--text_muted); }
    .el-promo .el-btn { margin-top: 18px; }

    /* ── Prosa ───────────────────────────────────────────────────────── */
    .el-prose { color: var(--text); font-size: 16px; line-height: 1.8; font-weight: 300; }
    .el-prose > * + * { margin-top: 16px; }
    .el-prose :is(h2,h3) { margin-top: 30px; font-weight: 500; }
    .el-prose a { color: var(--primary); font-weight: 500; text-decoration: none; border-bottom: 1px solid var(--accent); }
    .el-prose ul { padding-left: 24px; list-style: disc; }
    .el-prose ul li::marker { color: var(--accent); }
    .el-prose ol { padding-left: 24px; list-style: decimal; }
    .el-prose details { border-bottom: 1px solid var(--border); padding: 4px 0; }
    .el-prose summary { padding: 10px 0; font-weight: 500; cursor: pointer; color: var(--primary); font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 19px; list-style: none; }
    .el-prose summary::-webkit-details-marker { display: none; }
    .el-prose details p { padding: 0 0 12px; color: var(--text_muted); }

    /* ── Paket planları ──────────────────────────────────────────────── */
    .el-plans { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 24px; align-items: stretch; }
    @media (max-width: 900px) { .el-plans { grid-template-columns: 1fr; max-width: 420px; margin-inline: auto; } }
    .el-plan { display: flex; flex-direction: column; gap: 16px; padding: 34px 26px; border: 1px solid var(--border); background: var(--bg_card); }
    .el-plan--featured { border-color: var(--accent); border-width: 1px; box-shadow: 0 0 0 1px var(--accent); }
    .el-plan__badge { align-self: center; font-family: var(--font_body, Jost, sans-serif); font-size: 10px; font-weight: 500; letter-spacing: .2em; text-transform: uppercase; color: #fff; background: var(--accent); padding: 4px 12px; }
    .el-plan__cat { font-family: var(--font_body, Jost, sans-serif); font-size: 10.5px; font-weight: 500; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); text-align: center; }
    .el-plan h2 { font-size: 26px; font-weight: 500; text-align: center; }
    .el-plan__price { font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 46px; font-weight: 600; color: var(--primary); text-align: center; line-height: 1; }
    .el-plan__price small { display: block; margin-top: 8px; font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 400; letter-spacing: .14em; text-transform: uppercase; color: var(--text_muted); }
    .el-plan ul { margin: 4px 0; padding: 0; list-style: none; display: grid; gap: 12px; }
    .el-plan li { font-size: 14.5px; color: var(--text); padding-left: 22px; position: relative; }
    .el-plan li::before { content: "—"; position: absolute; left: 0; top: 0; color: var(--accent); }
    .el-plan .el-btn { margin-top: auto; }

    /* ── Form ────────────────────────────────────────────────────────── */
    .el-form { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 560px) { .el-form { grid-template-columns: 1fr; } }
    .el-form label { display: flex; flex-direction: column; gap: 7px; font-family: var(--font_body, Jost, sans-serif); font-size: 10.5px; font-weight: 500; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .el-form .el-wide { grid-column: 1 / -1; }
    .el-form :is(input,select,textarea) { padding: 12px 14px; border: 1px solid var(--border); border-radius: 1px; background: var(--bg); color: var(--text); font-family: var(--font_body, Jost, sans-serif); font-weight: 300; font-size: 15px; letter-spacing: 0; text-transform: none; }
    .el-form :is(input,select,textarea):focus { outline: none; border-color: var(--accent); }
    .el-note { padding: 14px 18px; border-left: 2px solid var(--accent); background: var(--bg_card); font-size: 14px; }
    .el-empty { padding: 46px 20px; text-align: center; color: var(--text_muted); font-size: 15px; }
    .el-empty a { color: var(--primary); border-bottom: 1px solid var(--accent); text-decoration: none; }

    /* ── Sayfalama ───────────────────────────────────────────────────── */
    .el-pag { padding-top: 26px; }
    .el-pag nav { display: flex; flex-wrap: wrap; justify-content: center; gap: 4px; }
    .el-pag :is(a,span) { display: inline-flex; align-items: center; min-width: 40px; justify-content: center; padding: 9px 12px; border-bottom: 1px solid transparent; font-family: var(--font_body, Jost, sans-serif); font-size: 13px; font-weight: 400; color: var(--text); background: transparent; text-decoration: none; }
    .el-pag a:hover { border-color: var(--accent); color: var(--primary); }
    .el-pag [aria-current="page"], .el-pag .current { color: var(--primary); border-color: var(--accent); font-weight: 500; }
    .el-pag svg { width: 13px; height: 13px; }

    /* ── CTA ─────────────────────────────────────────────────────────── */
    .el-cta { background: var(--primary); color: #f3ede0; padding: 64px 0; text-align: center; }
    .el-cta h2 { color: #fff; font-size: clamp(28px,4vw,42px); font-weight: 500; }
    .el-cta p { margin-top: 14px; color: rgba(243,237,224,.8); font-weight: 300; }
    .el-cta .el-btn { margin-top: 26px; }

    /* ── Detay (firma profili) ───────────────────────────────────────── */
    .el-detail__head { margin-bottom: 34px; }
    .el-detail__head h1 { font-size: clamp(30px,4.6vw,48px); font-weight: 500; margin-top: 14px; }
    .el-kicker { display: inline-block; margin-top: 16px; font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .2em; text-transform: uppercase; color: var(--accent); }
    .el-detail__actions { display: flex; flex-wrap: wrap; gap: 12px; margin-top: 26px; }
    .el-meta { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 1px; background: var(--border); border: 1px solid var(--border); margin-bottom: 40px; }
    @media (max-width: 760px) { .el-meta { grid-template-columns: 1fr 1fr; } }
    .el-meta > div { background: var(--bg_card); padding: 18px 16px; }
    .el-meta dt { font-family: var(--font_body, Jost, sans-serif); font-size: 10px; font-weight: 500; letter-spacing: .18em; text-transform: uppercase; color: var(--text_muted); }
    .el-meta dd { margin: 8px 0 0; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 20px; font-weight: 600; color: var(--primary); }
    .el-meta dd a { color: var(--primary); text-decoration: none; }
    .el-meta dd a:hover { color: var(--accent); }
    .el-detail__body { display: grid; grid-template-columns: minmax(0,1fr) 320px; gap: 44px; align-items: start; }
    @media (max-width: 980px) { .el-detail__body { grid-template-columns: 1fr; gap: 32px; } }
    .el-detail__main { display: grid; gap: 40px; }
    .el-detail__side { display: grid; gap: 28px; }
    @media (min-width: 981px) { .el-detail__side { position: sticky; top: 24px; } }

    /* ── td-* (ortak section/sidebar) — elegant override ─────────────── */
    .el-detail .td-section { border-top: 1px solid var(--border); padding-top: 20px; }
    .el-detail .td-side-card { border: 1px solid var(--border); border-top: 2px solid var(--accent); background: var(--bg_card); padding: 22px; }
    .el-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 12px; margin-bottom: 16px; }
    .el-detail .td-section-head h2, .el-detail .td-side-card h2 { font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 26px; font-weight: 500; color: var(--primary); }
    .el-detail .td-section-head > a, .el-detail .td-count { margin-left: auto; font-family: var(--font_body, Jost, sans-serif); color: var(--accent); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; }
    .el-detail .td-index { display: none; }
    .el-detail .td-prose { color: var(--text); line-height: 1.8; font-size: 15.5px; font-weight: 300; }
    .el-detail .td-prose p + p { margin-top: 14px; }
    .el-detail .td-prose :is(h2,h3) { margin: 22px 0 10px; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 22px; font-weight: 500; color: var(--primary); }
    .el-detail .td-prose ul { padding-left: 24px; list-style: disc; }
    .el-detail .td-prose ul li::marker { color: var(--accent); }
    .el-detail .td-share { margin-top: 18px; padding-top: 16px; border-top: 1px solid var(--border); }
    .el-detail .td-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 16px; }
    @media (max-width: 620px) { .el-detail .td-card-grid { grid-template-columns: 1fr; } }
    .el-detail .td-card { display: flex; flex-direction: column; gap: 5px; padding: 16px; border: 1px solid var(--border); background: var(--bg); text-decoration: none; transition: border-color .16s ease; }
    .el-detail .td-card:hover { border-color: var(--accent); }
    .el-detail .td-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; margin-bottom: 8px; }
    .el-detail .td-card small { font-family: var(--font_body, Jost, sans-serif); font-size: 10px; font-weight: 500; letter-spacing: .16em; text-transform: uppercase; color: var(--accent); }
    .el-detail .td-card h3 { font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 20px; font-weight: 500; color: var(--primary); }
    .el-detail .td-card p { font-size: 13.5px; color: var(--text_muted); }
    .el-detail .td-price { font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 20px; color: var(--primary); font-weight: 600; }
    .el-detail .td-linebreak { white-space: pre-line; }
    .el-detail .td-gallery { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 8px; }
    @media (max-width: 620px) { .el-detail .td-gallery { grid-template-columns: repeat(2, 1fr); } }
    .el-detail .td-gallery img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border: 1px solid var(--border); }
    .el-detail .td-map { overflow: hidden; border: 1px solid var(--border); }
    .el-detail .td-map iframe { width: 100%; height: 320px; border: 0; display: block; }
    .el-detail .td-reviews { display: grid; gap: 14px; margin-bottom: 18px; }
    .el-detail .td-review { padding: 16px 18px; border: 1px solid var(--border); background: var(--bg); }
    .el-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 12px; font-size: 13px; }
    .el-detail .td-review span { color: var(--text_muted); }
    .el-detail .td-stars { color: var(--accent); }
    .el-detail .td-empty { color: var(--text_muted); font-size: 14px; }
    .el-detail .td-form-title { margin: 6px 0 14px; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 22px; color: var(--primary); }
    .el-detail .td-review-form { display: grid; gap: 12px; }
    .el-detail .td-review-form :is(input,select,textarea) { padding: 11px 13px; border: 1px solid var(--border); border-radius: 1px; background: var(--bg); color: var(--text); font: inherit; font-size: 14.5px; }
    .el-detail .td-review-form button { justify-self: start; }
    .el-detail .td-message { padding: 14px 16px; border-left: 2px solid var(--accent); background: var(--bg_card); font-size: 14px; }
    .el-detail .td-faq { border-bottom: 1px solid var(--border); padding: 2px 0; margin-bottom: 2px; }
    .el-detail .td-faq summary { padding: 12px 0; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 19px; font-weight: 500; cursor: pointer; list-style: none; color: var(--primary); }
    .el-detail .td-faq summary::-webkit-details-marker { display: none; }
    .el-detail .td-faq p { padding: 0 0 12px; color: var(--text_muted); }
    .el-detail .td-side-label { font-family: var(--font_body, Jost, sans-serif); font-size: 10px; font-weight: 500; letter-spacing: .18em; text-transform: uppercase; color: var(--accent); }
    .el-detail .td-contact dl { margin: 14px 0 0; display: grid; gap: 0; }
    .el-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px solid var(--border); font-size: 14px; }
    .el-detail .td-contact dt { color: var(--text_muted); }
    .el-detail .td-contact dd { margin: 0; text-align: right; font-weight: 500; }
    .el-detail .td-contact dd a { color: var(--primary); text-decoration: none; }
    .el-detail .td-actions { display: grid; gap: 10px; margin-top: 16px; }
    .el-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 14px; border: 1px solid var(--border); background: var(--bg); font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; }
    .el-detail .td-actions a:hover { border-color: var(--accent); color: var(--primary); }
    .el-detail .td-claim > a { display: inline-flex; align-items: center; gap: 8px; margin-top: 14px; padding: 12px 22px; background: var(--primary); color: var(--btn_text, #fff); font-family: var(--font_body, Jost, sans-serif); font-size: 11px; font-weight: 500; letter-spacing: .14em; text-transform: uppercase; text-decoration: none; }
    .el-detail .td-claim small { display: block; margin-top: 10px; color: var(--text_muted); font-size: 12px; }
    .el-detail .td-related a { display: flex; flex-direction: column; gap: 3px; padding: 12px 0; border-bottom: 1px solid var(--border); text-decoration: none; font-size: 15px; }
    .el-detail .td-related a:hover { color: var(--primary); }
    .el-detail .td-related span { font-size: 12px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .el * { transition: none !important; } }

    /* ── Home: numaralı kategori dizini ──────────────────────────────── */
    .el-catdir { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 0 44px; }
    @media (max-width: 820px) { .el-catdir { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 520px) { .el-catdir { grid-template-columns: 1fr; } }
    .el-catdir__item { display: flex; align-items: baseline; gap: 16px; padding: 14px 0; border-bottom: 1px solid var(--border); text-decoration: none; color: var(--text); transition: color .16s ease, padding-left .16s ease; }
    .el-catdir__item:hover { color: var(--primary); padding-left: 6px; }
    .el-catdir__name { flex: 1 1 auto; min-width: 0; font-family: var(--font_heading, 'Cormorant Garamond', serif); font-size: 22px; font-weight: 500; }
    .el-catdir__count { font-size: 13px; color: var(--text_muted); font-variant-numeric: tabular-nums; }

    /* ── Home: şehir ızgarası ────────────────────────────────────────── */
    .el-citygrid { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 1px; background: var(--border); border: 1px solid var(--border); }
    @media (max-width: 820px) { .el-citygrid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 460px) { .el-citygrid { grid-template-columns: 1fr; } }
    .el-citygrid__item { background: var(--bg_card); padding: 22px 18px; text-decoration: none; display: flex; flex-direction: column; gap: 6px; transition: background .16s ease; }
    .el-citygrid__item:hover { background: var(--primary_light); }
</style>
