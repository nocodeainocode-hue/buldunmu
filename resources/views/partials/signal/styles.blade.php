{{-- SİNYAL İSTASYONU · iç sayfa tasarım sistemi (Firma Feneri) --}}
<style>
    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .sig {
        color: var(--text);
        background: var(--bg);
        font-family: var(--font_body, Inter, system-ui, sans-serif);
        font-size: 15.5px;
        line-height: 1.65;
    }
    .sig * { box-sizing: border-box; }
    .sig a { color: inherit; }
    .sig :is(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, "Space Grotesk", sans-serif); font-weight: 700; letter-spacing: -.01em; line-height: 1.1; }
    .sig p { margin: 0; }
    .sig-wrap { width: min(100% - 32px, var(--page_width, 1320px)); margin-inline: auto; }
    .sig-page { padding-block: 30px 60px; }

    /* ── Üst bant (hero) ─────────────────────────────────────────────── */
    .sig-band { position: relative; overflow: hidden; background: var(--primary); color: #eaf1f2; padding-block: clamp(34px,5vw,64px); }
    .sig-band::before {
        content: ""; position: absolute; inset: 0; pointer-events: none;
        background-image: linear-gradient(rgba(255,255,255,.06) 1px,transparent 1px),linear-gradient(90deg,rgba(255,255,255,.06) 1px,transparent 1px);
        background-size: 26px 26px;
    }
    .sig-band::after { content: ""; position: absolute; right: -80px; top: -120px; width: 320px; height: 320px; border-radius: 50%; background: radial-gradient(circle, color-mix(in srgb, var(--accent) 40%, transparent), transparent 68%); pointer-events: none; }
    .sig-band > .sig-wrap { position: relative; z-index: 1; }
    .sig-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: 8px; margin-bottom: 16px; font-size: 12.5px; font-weight: 600; letter-spacing: .02em; color: rgba(234,241,242,.72); }
    .sig-crumb a { text-decoration: none; color: rgba(234,241,242,.82); }
    .sig-crumb a:hover { color: var(--accent); }
    .sig-crumb .sig-crumb__sep { opacity: .5; }
    .sig-kicker { display: inline-flex; align-items: center; gap: 9px; font-size: 11px; font-weight: 800; letter-spacing: .2em; text-transform: uppercase; color: var(--secondary); }
    .sig-kicker::before { content: ""; width: 8px; height: 8px; border-radius: 50%; background: currentColor; box-shadow: 0 0 0 4px color-mix(in srgb, currentColor 24%, transparent); }
    /* Koy zeminlerde (üst bant / vurgu bloğu) kicker amber okunur */
    :is(.sig-band, .sig-promo) .sig-kicker { color: var(--accent); }
    .sig-display { margin-top: 14px; font-size: clamp(30px,5.2vw,54px); font-weight: 800; text-transform: none; max-width: 20ch; }
    .sig-band p.sig-lede { margin-top: 12px; max-width: 62ch; font-size: 16px; color: rgba(234,241,242,.82); }

    /* ── Arama / filtre ──────────────────────────────────────────────── */
    .sig-search { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 22px; max-width: 640px; }
    .sig-search input { flex: 1 1 220px; min-width: 0; border: 1px solid rgba(255,255,255,.28); background: rgba(255,255,255,.1); color: #fff; padding: 12px 15px; border-radius: .125rem; font: inherit; }
    .sig-search input::placeholder { color: rgba(234,241,242,.6); }
    .sig-search input:focus { outline: none; border-color: var(--accent); background: rgba(255,255,255,.16); }
    .sig-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; padding: 12px 20px; border: 1px solid var(--accent); border-radius: .125rem; background: var(--accent); color: #102b3c; font-weight: 800; font-size: 13.5px; letter-spacing: .01em; text-decoration: none; cursor: pointer; transition: transform .15s ease, filter .15s ease, background .15s ease; }
    .sig-btn:hover { transform: translateY(-1px); filter: brightness(1.05); }
    .sig-btn--ghost { background: transparent; border-color: var(--border); color: var(--text); }
    .sig-btn--ghost:hover { background: var(--primary_light); border-color: var(--primary); }
    .sig-search .sig-btn--ghost, .sig-band .sig-btn--ghost { border-color: rgba(255,255,255,.3); color: #fff; }
    .sig-search .sig-btn--ghost:hover, .sig-band .sig-btn--ghost:hover { background: rgba(255,255,255,.12); }

    .sig-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 12px; padding: 16px 18px; margin-bottom: 22px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg_card); box-shadow: var(--card_shadow); }
    .sig-filters label { display: flex; flex-direction: column; gap: 6px; font-size: 11px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: var(--text_muted); }
    .sig-filters :is(input,select) { min-width: 180px; padding: 10px 12px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .sig-filters :is(input,select):focus { outline: none; border-color: var(--primary); }
    .sig-filters a { font-size: 13px; font-weight: 700; color: var(--secondary); text-decoration: none; padding-bottom: 10px; }
    .sig-filters a:hover { text-decoration: underline; }

    /* ── Sütunlar ────────────────────────────────────────────────────── */
    .sig-cols { display: grid; grid-template-columns: minmax(0,1fr) 320px; gap: 26px; align-items: start; }
    @media (max-width: 940px) { .sig-cols { grid-template-columns: 1fr; } }
    .sig-side { display: grid; gap: 18px; }
    @media (min-width: 941px) { .sig-side { position: sticky; top: 22px; } }

    /* ── Panel ───────────────────────────────────────────────────────── */
    .sig-panel { border: 1px solid var(--border); border-radius: .125rem; background: var(--bg_card); box-shadow: var(--card_shadow); overflow: hidden; }
    .sig-panel + .sig-panel { margin-top: 18px; }
    .sig-panel__head { display: flex; align-items: baseline; justify-content: space-between; gap: 12px; padding: 15px 18px; border-bottom: 1px solid var(--border); background: color-mix(in srgb, var(--primary) 4%, var(--bg_card)); }
    .sig-panel__head h2 { font-size: 16px; font-weight: 700; text-transform: uppercase; letter-spacing: .02em; }
    .sig-panel__head .sig-code { font-size: 10.5px; font-weight: 800; letter-spacing: .16em; text-transform: uppercase; color: var(--text_muted); }
    .sig-panel__body { padding: 16px 18px; }

    /* ── Sinyal bağlantıları (numaralı satır listesi) ────────────────── */
    .sig-links { display: flex; flex-direction: column; }
    .sig-links > a { display: flex; align-items: center; gap: 12px; padding: 12px 18px; border-top: 1px solid var(--border); text-decoration: none; font-size: 14px; font-weight: 600; transition: background .16s ease, padding-left .16s ease; }
    .sig-links > a:first-child { border-top: 0; }
    .sig-links > a:hover { background: var(--primary_light); padding-left: 22px; }
    .sig-links .sig-links__no { flex: 0 0 auto; font-size: 11px; font-weight: 800; color: var(--secondary); letter-spacing: .08em; font-variant-numeric: tabular-nums; }
    .sig-links .sig-links__txt { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .sig-links .sig-links__go { flex: 0 0 auto; color: var(--text_muted); font-weight: 800; }
    .sig-links .sig-links__count { flex: 0 0 auto; font-size: 12px; font-weight: 800; color: var(--primary); background: var(--primary_light); padding: 2px 9px; border-radius: .125rem; }

    /* ── Firma kartları ──────────────────────────────────────────────── */
    .sig-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 14px; }
    @media (max-width: 560px) { .sig-grid { grid-template-columns: 1fr; } }
    .sig-card { display: flex; flex-direction: column; gap: 10px; padding: 16px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg_card); text-decoration: none; transition: border-color .16s ease, transform .16s ease, box-shadow .16s ease; }
    .sig-card:hover { border-color: var(--primary); transform: translateY(-2px); box-shadow: 0 12px 26px rgba(16,43,60,.1); }
    .sig-card__top { display: flex; align-items: center; gap: 12px; }
    .sig-card__logo { flex: 0 0 auto; width: 52px; height: 52px; display: grid; place-items: center; border-radius: .125rem; background: var(--primary); color: #fff; font-size: 22px; font-weight: 800; overflow: hidden; }
    .sig-card__logo img { width: 100%; height: 100%; object-fit: cover; }
    .sig-card__cat { font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--secondary); }
    .sig-card h3 { font-size: 17px; font-weight: 700; margin-top: 2px; }
    .sig-card__meta { font-size: 12.5px; color: var(--text_muted); }
    .sig-card__text { font-size: 13.5px; color: var(--text_muted); line-height: 1.55; }
    .sig-card__go { margin-top: auto; font-size: 12.5px; font-weight: 800; color: var(--primary); letter-spacing: .04em; text-transform: uppercase; }
    .sig-card__vip { align-self: flex-start; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #102b3c; background: var(--accent); padding: 3px 8px; border-radius: .125rem; }

    /* ── Satır listesi (firma / yazı / ilan) ─────────────────────────── */
    .sig-rows { display: flex; flex-direction: column; }
    .sig-row { display: flex; align-items: center; gap: 16px; padding: 16px 18px; border-top: 1px solid var(--border); text-decoration: none; transition: background .16s ease; }
    .sig-row:first-child { border-top: 0; }
    .sig-row:hover { background: var(--primary_light); }
    .sig-row__mark { flex: 0 0 auto; width: 58px; height: 58px; display: grid; place-items: center; border-radius: .125rem; border: 1px solid var(--border); background: var(--primary_light); color: var(--primary); font-size: 22px; font-weight: 800; overflow: hidden; }
    .sig-row__mark img { width: 100%; height: 100%; object-fit: cover; }
    .sig-row__txt { flex: 1 1 auto; min-width: 0; }
    .sig-row__txt h3 { font-size: 16.5px; font-weight: 700; margin-top: 3px; }
    .sig-row__cat { font-size: 11px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--secondary); }
    .sig-row__text { font-size: 13px; color: var(--text_muted); overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
    .sig-row__go { flex: 0 0 auto; font-size: 12px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: var(--primary); white-space: nowrap; }

    /* ── Vurgu / bilgi bloğu ─────────────────────────────────────────── */
    .sig-promo { border: 1px solid var(--primary); border-radius: .125rem; background: var(--primary); color: #eaf1f2; padding: 20px; }
    .sig-promo h2 { color: #fff; font-size: 19px; }
    .sig-promo p { margin-top: 8px; font-size: 13.5px; color: rgba(234,241,242,.82); }
    .sig-promo .sig-btn { margin-top: 14px; }
    .sig-detail .sig-card__vip { align-self: center; }
    .sig-lead { display: grid; grid-template-columns: minmax(0,1.4fr) minmax(0,1fr); gap: 0; }
    @media (max-width: 640px) { .sig-lead { grid-template-columns: 1fr; } }
    .sig-lead__body { padding: 22px; }
    .sig-lead__body h2 { font-size: clamp(20px,2.6vw,28px); margin: 12px 0; }
    .sig-lead__body p { color: var(--text_muted); }
    .sig-lead__media { min-height: 200px; }
    .sig-lead__media img { width: 100%; height: 100%; object-fit: cover; }

    /* ── Eyalet / prosa ──────────────────────────────────────────────── */
    .sig-prose { color: var(--text); font-size: 15.5px; line-height: 1.75; }
    .sig-prose > * + * { margin-top: 14px; }
    .sig-prose :is(h2,h3) { font-family: var(--font_heading, "Space Grotesk", sans-serif); margin-top: 24px; }
    .sig-prose a { color: var(--primary); font-weight: 700; }
    .sig-prose ul { padding-left: 22px; list-style: disc; }
    .sig-prose ol { padding-left: 22px; list-style: decimal; }
    .sig-prose details { border-bottom: 1px dashed var(--border); }
    .sig-prose summary { padding: 12px 0; font-weight: 700; cursor: pointer; }
    .sig-prose details p { padding: 0 0 12px; color: var(--text_muted); }

    /* ── Paket planları ──────────────────────────────────────────────── */
    .sig-plans { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 18px; }
    @media (max-width: 900px) { .sig-plans { grid-template-columns: 1fr; max-width: 460px; margin-inline: auto; } }
    .sig-plan { display: flex; flex-direction: column; gap: 14px; padding: 24px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg_card); box-shadow: var(--card_shadow); }
    .sig-plan--featured { border-color: var(--primary); box-shadow: 0 0 0 2px var(--primary); }
    .sig-plan__badge { align-self: flex-start; font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: #fff; background: var(--secondary); padding: 3px 9px; border-radius: .125rem; }
    .sig-plan__cat { font-size: 11px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; color: var(--text_muted); }
    .sig-plan h2 { font-size: 22px; }
    .sig-plan__price { font-family: var(--font_heading, "Space Grotesk", sans-serif); font-size: 34px; font-weight: 800; color: var(--primary); }
    .sig-plan__price small { display: block; font-size: 12px; font-weight: 600; color: var(--text_muted); }
    .sig-plan ul { margin: 0; padding-left: 0; list-style: none; display: grid; gap: 10px; }
    .sig-plan li { font-size: 13.5px; color: var(--text); padding-left: 22px; position: relative; }
    .sig-plan li::before { content: "›"; position: absolute; left: 4px; top: 0; color: var(--secondary); font-weight: 800; }
    .sig-plan .sig-btn { margin-top: auto; }

    /* ── Form (iletişim) ─────────────────────────────────────────────── */
    .sig-form { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    @media (max-width: 560px) { .sig-form { grid-template-columns: 1fr; } }
    .sig-form label { display: flex; flex-direction: column; gap: 7px; font-size: 12px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--text_muted); }
    .sig-form .sig-wide { grid-column: 1 / -1; }
    .sig-form :is(input,select,textarea) { padding: 12px 14px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); color: var(--text); font: inherit; font-size: 14.5px; text-transform: none; letter-spacing: 0; }
    .sig-form :is(input,select,textarea):focus { outline: none; border-color: var(--primary); }
    .sig-note { padding: 12px 15px; border-left: 4px solid var(--primary); background: var(--primary_light); border-radius: .125rem; font-size: 14px; }

    .sig-empty { padding: 40px 24px; text-align: center; color: var(--text_muted); }
    .sig-empty a { color: var(--primary); font-weight: 800; }

    /* ── Sayfalama ───────────────────────────────────────────────────── */
    .sig-pag { padding: 16px 18px; border-top: 1px solid var(--border); }
    .sig-pag nav { display: flex; flex-wrap: wrap; justify-content: center; }
    .sig-pag :is(a,span) { display: inline-flex; align-items: center; min-width: 38px; justify-content: center; margin: 2px; padding: 9px 12px; border: 1px solid var(--border); border-radius: .125rem; font-size: 13px; font-weight: 700; color: var(--text); text-decoration: none; }
    .sig-pag a:hover { background: var(--primary_light); border-color: var(--primary); }
    .sig-pag [aria-current="page"], .sig-pag .current { background: var(--primary); border-color: var(--primary); color: #fff; }
    .sig-pag svg { width: 14px; height: 14px; }

    /* ── Yorum / puan rozetleri ──────────────────────────────────────── */
    .sig-rate { display: inline-flex; align-items: center; gap: 6px; font-size: 12.5px; font-weight: 800; color: var(--text); }
    .sig-rate .stars { color: var(--accent); letter-spacing: .04em; }

    /* ── Detay (firma profili) ───────────────────────────────────────── */
    .sig-detail .sig-band { padding-block: clamp(34px,5vw,58px); }
    .sig-detail__actions { display: flex; flex-wrap: wrap; gap: 10px; margin-top: 22px; }
    .sig-detail .sig-meta { margin-bottom: 34px; }
    .sig-jobbar { margin-top: 0; margin-bottom: 24px; }
    @media (max-width: 760px) { .sig-jobbar { margin-top: 0; } }
    .sig-meta { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 0; margin-top: -28px; margin-bottom: 30px; position: relative; z-index: 3; }
    @media (max-width: 760px) { .sig-meta { grid-template-columns: 1fr 1fr; margin-top: 18px; } }
    .sig-meta > div { padding: 16px 18px; border: 1px solid var(--border); border-left: 0; background: var(--bg_card); }
    .sig-meta > div:first-child { border-left: 1px solid var(--border); }
    @media (max-width: 760px) { .sig-meta > div:nth-child(3) { border-left: 1px solid var(--border); } }
    .sig-meta dt { font-size: 10.5px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: var(--text_muted); }
    .sig-meta dd { margin: 6px 0 0; font-size: 15px; font-weight: 700; color: var(--text); }
    .sig-meta dd a { color: var(--primary); text-decoration: none; }
    .sig-meta dd a:hover { text-decoration: underline; }
    .sig-detail__body { display: grid; grid-template-columns: minmax(0,1fr) 340px; gap: 26px; align-items: start; padding-block: 34px 20px; }
    @media (max-width: 980px) { .sig-detail__body { grid-template-columns: 1fr; } }
    .sig-detail__main { display: grid; gap: 18px; }
    .sig-detail__side { display: grid; gap: 18px; }
    @media (min-width: 981px) { .sig-detail__side { position: sticky; top: 22px; } }

    /* td-* (ortak section/sidebar parçaları) — sinyal temasına bağlanır */
    .sig-detail .td-section, .sig-detail .td-side-card { border: 1px solid var(--border); border-radius: .125rem; background: var(--bg_card); padding: 20px; box-shadow: var(--card_shadow); }
    .sig-detail .td-section { border-top: 3px solid var(--primary); }
    .sig-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 10px; margin-bottom: 14px; padding-bottom: 12px; border-bottom: 1px dashed var(--border); }
    .sig-detail .td-index { color: var(--secondary); font-size: 11px; font-weight: 800; letter-spacing: .16em; }
    .sig-detail .td-section-head h2, .sig-detail .td-side-card h2 { font-size: 19px; font-weight: 700; text-transform: uppercase; }
    .sig-detail .td-side-card h2 { margin-top: 8px; }
    .sig-detail .td-section-head > a, .sig-detail .td-count { margin-left: auto; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; text-decoration: none; }
    .sig-detail .td-prose { color: var(--text); line-height: 1.75; }
    .sig-detail .td-prose p + p { margin-top: 12px; }
    .sig-detail .td-prose :is(h2,h3) { margin: 18px 0 8px; font-size: 18px; text-transform: uppercase; }
    .sig-detail .td-prose ul { padding-left: 22px; list-style: disc; }
    .sig-detail .td-share { margin-top: 18px; padding-top: 14px; border-top: 1px dashed var(--border); }
    .sig-detail .td-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 12px; }
    @media (max-width: 620px) { .sig-detail .td-card-grid { grid-template-columns: 1fr; } }
    .sig-detail .td-card { display: flex; flex-direction: column; gap: 5px; padding: 14px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); text-decoration: none; transition: border-color .16s ease; }
    .sig-detail .td-card:hover { border-color: var(--primary); }
    .sig-detail .td-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: .125rem; margin-bottom: 6px; }
    .sig-detail .td-card small { font-size: 10.5px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--secondary); }
    .sig-detail .td-card h3 { font-size: 15.5px; }
    .sig-detail .td-card p { font-size: 13px; color: var(--text_muted); }
    .sig-detail .td-price { font-size: 15px; color: var(--primary); }
    .sig-detail .td-linebreak { white-space: pre-line; }
    .sig-detail .td-gallery { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 8px; }
    @media (max-width: 620px) { .sig-detail .td-gallery { grid-template-columns: repeat(2, 1fr); } }
    .sig-detail .td-gallery img { width: 100%; aspect-ratio: 1; object-fit: cover; border-radius: .125rem; }
    .sig-detail .td-map { overflow: hidden; border-radius: .125rem; border: 1px solid var(--border); }
    .sig-detail .td-map iframe { width: 100%; height: 320px; border: 0; display: block; }
    .sig-detail .td-reviews { display: grid; gap: 12px; margin-bottom: 18px; }
    .sig-detail .td-review { padding: 14px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); }
    .sig-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 10px; font-size: 13px; }
    .sig-detail .td-review span { color: var(--text_muted); }
    .sig-detail .td-stars { color: var(--accent); }
    .sig-detail .td-empty { color: var(--text_muted); font-size: 13.5px; }
    .sig-detail .td-form-title { margin: 6px 0 12px; font-size: 16px; text-transform: uppercase; }
    .sig-detail .td-review-form { display: grid; gap: 10px; }
    .sig-detail .td-review-form :is(input,select,textarea) { padding: 11px 13px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .sig-detail .td-review-form button { justify-self: start; display: inline-flex; align-items: center; gap: 8px; padding: 12px 20px; border: 1px solid var(--accent); border-radius: .125rem; background: var(--accent); color: #102b3c; font-weight: 800; font-size: 13.5px; cursor: pointer; }
    .sig-detail .td-message { padding: 12px 14px; border-left: 4px solid var(--secondary); background: var(--primary_light); border-radius: .125rem; font-size: 13.5px; }
    .sig-detail .td-faq { border-bottom: 1px dashed var(--border); }
    .sig-detail .td-faq summary { padding: 12px 0; font-weight: 700; cursor: pointer; }
    .sig-detail .td-faq p { padding: 0 0 12px; color: var(--text_muted); }
    .sig-detail .td-side-label { font-size: 10.5px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; color: var(--secondary); }
    .sig-detail .td-contact dl { margin: 14px 0 0; display: grid; gap: 0; }
    .sig-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px dashed var(--border); font-size: 14px; }
    .sig-detail .td-contact dt { color: var(--text_muted); font-weight: 600; }
    .sig-detail .td-contact dd { margin: 0; text-align: right; }
    .sig-detail .td-contact dd a { color: var(--primary); text-decoration: none; }
    .sig-detail .td-actions { display: grid; gap: 8px; margin-top: 16px; }
    .sig-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 8px; padding: 11px 14px; border: 1px solid var(--border); border-radius: .125rem; background: var(--bg); font-size: 13.5px; font-weight: 700; text-decoration: none; }
    .sig-detail .td-actions a:hover { border-color: var(--primary); background: var(--primary_light); }
    .sig-detail .td-claim > a { display: inline-flex; align-items: center; gap: 8px; margin-top: 12px; padding: 11px 16px; border-radius: .125rem; background: var(--primary); color: #fff; font-size: 13px; font-weight: 800; text-decoration: none; }
    .sig-detail .td-claim small { display: block; margin-top: 10px; color: var(--text_muted); font-size: 11.5px; }
    .sig-detail .td-related a { display: flex; flex-direction: column; gap: 3px; padding: 10px 0; border-bottom: 1px dashed var(--border); text-decoration: none; }
    .sig-detail .td-related span { font-size: 12px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .sig * { transition: none !important; } }
</style>
