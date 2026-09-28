{{-- İLAN BORSASI · iç sayfa tasarım sistemi (sahibinden tarzı yoğun ilan panosu) --}}
<style>
    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .ib {
        color: var(--text);
        background: var(--bg);
        font-family: var(--font_body, Roboto, system-ui, sans-serif);
        font-size: 14px;
        line-height: 1.55;
    }
    .ib * { box-sizing: border-box; }
    .ib a { color: inherit; }
    .ib :is(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, Roboto, sans-serif); font-weight: 700; letter-spacing: 0; line-height: 1.25; }
    .ib p { margin: 0; }
    .ib-wrap { width: min(100% - 24px, var(--page_width, 1200px)); margin-inline: auto; }
    .ib-page { padding-block: 14px 44px; }
    .ib-price { font-weight: 700; color: var(--primary_hover); font-variant-numeric: tabular-nums; white-space: nowrap; }

    /* ── Üst bant (arama bandı) ──────────────────────────────────────── */
    .ib-band { background: var(--primary); color: #eef4f9; padding-block: 18px; border-bottom: 3px solid var(--accent); }
    .ib-band h1 { font-size: clamp(20px,3vw,28px); font-weight: 700; }
    .ib-band h1 small { display: block; margin-top: 3px; font-size: 12.5px; font-weight: 400; color: rgba(238,244,249,.75); letter-spacing: .02em; }
    .ib-crumb { display: flex; flex-wrap: wrap; align-items: center; gap: 6px; margin-bottom: 10px; font-size: 12px; color: rgba(238,244,249,.7); }
    .ib-crumb a { text-decoration: none; color: rgba(238,244,249,.85); }
    .ib-crumb a:hover { color: #fff; text-decoration: underline; }
    .ib-crumb .ib-crumb__sep { opacity: .5; }
    .ib-find { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
    .ib-find :is(input,select) { min-width: 0; flex: 1 1 180px; padding: 10px 12px; border: 1px solid rgba(255,255,255,.35); border-radius: var(--border_radius, .25rem); background: #fff; color: var(--text); font: inherit; font-size: 13.5px; }
    .ib-find input:focus, .ib-find select:focus { outline: 2px solid var(--accent); outline-offset: -1px; border-color: #fff; }
    .ib-btn { display: inline-flex; align-items: center; justify-content: center; gap: 7px; padding: 10px 18px; border: 1px solid var(--accent); border-radius: var(--border_radius, .25rem); background: var(--accent); color: #27180a; font-weight: 700; font-size: 13.5px; text-decoration: none; cursor: pointer; transition: filter .15s ease; }
    .ib-btn:hover { filter: brightness(.94); }
    .ib-btn--primary { background: var(--primary); border-color: var(--primary); color: var(--btn_text, #fff); }
    .ib-btn--primary:hover { background: var(--primary_hover); filter: none; }
    .ib-btn--ghost { background: #fff; border-color: var(--border); color: var(--text); }
    .ib-btn--ghost:hover { border-color: var(--primary); color: var(--primary); filter: none; }
    .ib-find .ib-btn { flex: 0 0 auto; }

    /* ── Filtre şeridi ───────────────────────────────────────────────── */
    .ib-filters { display: flex; flex-wrap: wrap; align-items: end; gap: 10px; padding: 12px 14px; margin-bottom: 14px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); }
    .ib-filters label { display: flex; flex-direction: column; gap: 4px; font-size: 11px; font-weight: 700; color: var(--text_muted); }
    .ib-filters :is(input,select) { min-width: 170px; padding: 8px 10px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); color: var(--text); font: inherit; font-size: 13.5px; }
    .ib-filters :is(input,select):focus { outline: none; border-color: var(--primary); }
    .ib-filters a { font-size: 12.5px; font-weight: 700; color: var(--primary); text-decoration: none; padding-bottom: 8px; }
    .ib-filters a:hover { text-decoration: underline; }

    /* ── Sütunlar: sol kategori rayı + içerik ────────────────────────── */
    .ib-cols { display: grid; grid-template-columns: 230px minmax(0,1fr); gap: 14px; align-items: start; }
    @media (max-width: 900px) { .ib-cols { grid-template-columns: 1fr; } }
    .ib-side { display: grid; gap: 12px; }
    @media (min-width: 901px) { .ib-side { position: sticky; top: 12px; } }
    @media (max-width: 900px) { .ib-side { grid-template-columns: repeat(2, minmax(0,1fr)); } }

    /* ── Kutu (panel) ────────────────────────────────────────────────── */
    .ib-box { border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); overflow: hidden; }
    .ib-box + .ib-box { margin-top: 12px; }
    .ib-box__head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 9px 12px; background: var(--primary); color: #fff; }
    .ib-box__head h2 { font-size: 13.5px; font-weight: 700; letter-spacing: .02em; }
    .ib-box__head a, .ib-box__head .ib-box__note { font-size: 11.5px; font-weight: 400; color: rgba(255,255,255,.8); text-decoration: none; }
    .ib-box__head a:hover { color: #fff; text-decoration: underline; }
    .ib-box__body { padding: 12px; }

    /* ── Kategori rayı ───────────────────────────────────────────────── */
    .ib-catnav { display: flex; flex-direction: column; }
    .ib-catnav > a { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-top: 1px solid var(--bg); text-decoration: none; font-size: 13px; font-weight: 400; color: var(--text); transition: background .12s ease; }
    .ib-catnav > a:first-child { border-top: 0; }
    .ib-catnav > a:hover { background: var(--primary_light); color: var(--primary_hover); }
    .ib-catnav .ib-catnav__count { margin-left: auto; flex: 0 0 auto; font-size: 11px; font-weight: 700; color: var(--text_muted); background: var(--bg); border: 1px solid var(--border); padding: 1px 7px; border-radius: 999px; font-variant-numeric: tabular-nums; }

    /* ── Bağlantı rayı (ilçe / şehir / arşiv) ────────────────────────── */
    .ib-links { display: flex; flex-direction: column; }
    .ib-links > a { display: flex; align-items: center; gap: 8px; padding: 8px 12px; border-top: 1px solid var(--bg); text-decoration: none; font-size: 13px; transition: background .12s ease; }
    .ib-links > a:first-child { border-top: 0; }
    .ib-links > a:hover { background: var(--primary_light); }
    .ib-links .ib-links__txt { flex: 1 1 auto; min-width: 0; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ib-links .ib-links__count { flex: 0 0 auto; font-size: 11.5px; font-weight: 700; color: var(--primary); font-variant-numeric: tabular-nums; }

    /* ── İlan satırı (küçük resim + başlık + açıklama + fiyat) ───────── */
    .ib-items { display: flex; flex-direction: column; }
    .ib-item { display: flex; align-items: stretch; gap: 12px; padding: 12px; border-top: 1px solid var(--border); text-decoration: none; transition: background .12s ease; }
    .ib-item:first-child { border-top: 0; }
    .ib-item:hover { background: var(--primary_light); }
    .ib-item__thumb { flex: 0 0 96px; display: grid; place-items: center; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--primary_light); color: var(--primary); font-size: 26px; font-weight: 700; overflow: hidden; min-height: 72px; }
    .ib-item__thumb img { width: 100%; height: 100%; object-fit: cover; }
    .ib-item__body { flex: 1 1 auto; min-width: 0; display: flex; flex-direction: column; gap: 3px; }
    .ib-item__body h3 { font-size: 14.5px; font-weight: 700; color: var(--primary_hover); }
    .ib-item:hover .ib-item__body h3 { text-decoration: underline; }
    .ib-item__meta { font-size: 11.5px; color: var(--text_muted); }
    .ib-item__meta b { color: var(--secondary); font-weight: 700; }
    .ib-item__text { font-size: 12.5px; color: var(--text_muted); overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
    .ib-item__side { flex: 0 0 140px; display: flex; flex-direction: column; align-items: flex-end; justify-content: space-between; gap: 6px; text-align: right; }
    .ib-item__price { font-size: 15px; font-weight: 700; color: var(--primary_hover); font-variant-numeric: tabular-nums; }
    .ib-item__date { font-size: 11px; color: var(--text_muted); }
    .ib-item__go { font-size: 11.5px; font-weight: 700; color: var(--primary); }
    @media (max-width: 620px) {
        .ib-item { flex-wrap: wrap; }
        .ib-item__side { flex-direction: row; align-items: center; justify-content: space-between; width: 100%; flex-basis: 100%; }
    }
    .ib-tag { display: inline-block; font-size: 10px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: #27180a; background: var(--accent); padding: 1px 6px; border-radius: 2px; vertical-align: 1px; }
    .ib-tag--spot { background: var(--secondary); color: #fff; }

    /* ── Kart ızgarası (vitrin) ──────────────────────────────────────── */
    .ib-grid { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 12px; }
    @media (max-width: 1020px) { .ib-grid { grid-template-columns: repeat(2, minmax(0,1fr)); } }
    @media (max-width: 620px) { .ib-grid { grid-template-columns: 1fr; } }
    .ib-card { display: flex; flex-direction: column; gap: 8px; padding: 12px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); text-decoration: none; transition: border-color .12s ease, box-shadow .12s ease; }
    .ib-card:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(28,96,150,.12); }
    .ib-card__top { display: flex; align-items: center; gap: 10px; }
    .ib-card__logo { flex: 0 0 auto; width: 44px; height: 44px; display: grid; place-items: center; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--primary_light); color: var(--primary); font-size: 19px; font-weight: 700; overflow: hidden; }
    .ib-card__logo img { width: 100%; height: 100%; object-fit: cover; }
    .ib-card__cat { font-size: 11px; font-weight: 700; color: var(--secondary); }
    .ib-card h3 { font-size: 14.5px; font-weight: 700; margin-top: 1px; }
    .ib-card__meta { font-size: 12px; color: var(--text_muted); }
    .ib-card__text { font-size: 12.5px; color: var(--text_muted); line-height: 1.5; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; }
    .ib-card__foot { margin-top: auto; display: flex; align-items: center; justify-content: space-between; gap: 8px; font-size: 12px; font-weight: 700; color: var(--primary); }
    .ib-card__vip { align-self: flex-start; font-size: 9.5px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #27180a; background: var(--accent); padding: 1px 6px; border-radius: 2px; }

    /* ── Vurgu bloğu (spot reklam) ───────────────────────────────────── */
    .ib-promo { border: 1px solid var(--primary); border-left: 4px solid var(--accent); border-radius: var(--border_radius, .25rem); background: var(--primary_light); padding: 14px; }
    .ib-promo h2 { font-size: 15px; color: var(--primary_hover); }
    .ib-promo p { margin-top: 6px; font-size: 12.5px; color: var(--text_muted); }
    .ib-promo .ib-btn { margin-top: 10px; padding: 8px 14px; font-size: 12.5px; }

    /* ── Prosa (blog / bilgilendirme) ────────────────────────────────── */
    .ib-prose { color: var(--text); font-size: 14.5px; line-height: 1.7; }
    .ib-prose > * + * { margin-top: 12px; }
    .ib-prose :is(h2,h3) { margin-top: 22px; color: var(--primary_hover); }
    .ib-prose a { color: var(--primary); font-weight: 700; }
    .ib-prose ul { padding-left: 22px; list-style: disc; }
    .ib-prose ol { padding-left: 22px; list-style: decimal; }
    .ib-prose details { border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); padding: 0 12px; margin-bottom: 6px; }
    .ib-prose summary { padding: 10px 0; font-weight: 700; cursor: pointer; }
    .ib-prose details p { padding: 0 0 10px; color: var(--text_muted); }

    /* ── Paket planları ──────────────────────────────────────────────── */
    .ib-plans { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 14px; }
    @media (max-width: 900px) { .ib-plans { grid-template-columns: 1fr; max-width: 440px; margin-inline: auto; } }
    .ib-plan { display: flex; flex-direction: column; gap: 12px; padding: 18px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); }
    .ib-plan--featured { border-color: var(--primary); border-width: 2px; }
    .ib-plan__badge { align-self: flex-start; font-size: 10px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: #fff; background: var(--secondary); padding: 2px 8px; border-radius: 2px; }
    .ib-plan__cat { font-size: 11px; font-weight: 700; color: var(--text_muted); }
    .ib-plan h2 { font-size: 18px; }
    .ib-plan__price { font-size: 28px; font-weight: 700; color: var(--primary_hover); font-variant-numeric: tabular-nums; }
    .ib-plan__price small { display: block; font-size: 11.5px; font-weight: 400; color: var(--text_muted); }
    .ib-plan ul { margin: 0; padding-left: 0; list-style: none; display: grid; gap: 8px; }
    .ib-plan li { font-size: 13px; color: var(--text); padding-left: 18px; position: relative; }
    .ib-plan li::before { content: "✓"; position: absolute; left: 0; top: 0; color: var(--secondary); font-weight: 700; }
    .ib-plan .ib-btn { margin-top: auto; }

    /* ── Form (iletişim) ─────────────────────────────────────────────── */
    .ib-form { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
    @media (max-width: 560px) { .ib-form { grid-template-columns: 1fr; } }
    .ib-form label { display: flex; flex-direction: column; gap: 5px; font-size: 12px; font-weight: 700; color: var(--text_muted); }
    .ib-form .ib-wide { grid-column: 1 / -1; }
    .ib-form :is(input,select,textarea) { padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); color: var(--text); font: inherit; font-size: 13.5px; }
    .ib-form :is(input,select,textarea):focus { outline: none; border-color: var(--primary); }
    .ib-note { padding: 10px 13px; border: 1px solid var(--border); border-left: 4px solid var(--primary); background: var(--primary_light); border-radius: var(--border_radius, .25rem); font-size: 13px; }
    .ib-empty { padding: 34px 20px; text-align: center; color: var(--text_muted); }
    .ib-empty a { color: var(--primary); font-weight: 700; }

    /* ── Sayfalama ───────────────────────────────────────────────────── */
    .ib-pag { padding: 10px 12px; border-top: 1px solid var(--border); background: var(--bg_card); }
    .ib-pag nav { display: flex; flex-wrap: wrap; justify-content: center; }
    .ib-pag :is(a,span) { display: inline-flex; align-items: center; min-width: 32px; justify-content: center; margin: 2px; padding: 6px 10px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); font-size: 12.5px; font-weight: 700; color: var(--primary); background: var(--bg_card); text-decoration: none; }
    .ib-pag a:hover { border-color: var(--primary); background: var(--primary_light); }
    .ib-pag [aria-current="page"], .ib-pag .current { background: var(--primary); border-color: var(--primary); color: #fff; }
    .ib-pag svg { width: 13px; height: 13px; }

    /* ── Puan rozeti ─────────────────────────────────────────────────── */
    .ib-rate { display: inline-flex; align-items: center; gap: 5px; font-size: 12px; font-weight: 700; color: var(--text); }
    .ib-rate .stars { color: var(--accent); letter-spacing: .02em; }

    /* ── Detay (ilan/firma profili) ──────────────────────────────────── */
    .ib-detail__head { background: var(--bg_card); border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); padding: 18px; margin-bottom: 12px; }
    .ib-detail__head h1 { font-size: clamp(20px,3vw,26px); color: var(--primary_hover); margin-top: 8px; }
    .ib-kicker { display: inline-block; font-size: 11.5px; font-weight: 700; color: var(--text_muted); }
    .ib-detail__actions { display: flex; flex-wrap: wrap; gap: 8px; margin-top: 14px; }
    .ib-meta { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 8px; margin-bottom: 12px; }
    @media (max-width: 760px) { .ib-meta { grid-template-columns: 1fr 1fr; } }
    .ib-meta > div { padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); }
    .ib-meta dt { font-size: 10.5px; font-weight: 700; color: var(--text_muted); }
    .ib-meta dd { margin: 3px 0 0; font-size: 13.5px; font-weight: 700; color: var(--text); }
    .ib-meta dd a { color: var(--primary); text-decoration: none; }
    .ib-meta dd a:hover { text-decoration: underline; }
    .ib-jobbar { display: flex; flex-wrap: wrap; align-items: center; gap: 10px; padding: 12px 14px; border: 1px solid var(--border); border-left: 4px solid var(--secondary); border-radius: var(--border_radius, .25rem); background: var(--bg_card); margin-bottom: 12px; }
    .ib-jobbar h1 { font-size: 19px; color: var(--primary_hover); }
    .ib-detail__body { display: grid; grid-template-columns: minmax(0,1fr) 300px; gap: 14px; align-items: start; }
    @media (max-width: 980px) { .ib-detail__body { grid-template-columns: 1fr; } }
    .ib-detail__main { display: grid; gap: 12px; }
    .ib-detail__side { display: grid; gap: 12px; }
    @media (min-width: 981px) { .ib-detail__side { position: sticky; top: 12px; } }

    /* td-* (ortak section/sidebar parçaları) — ilan temasına bağlanır */
    .ib-detail .td-section, .ib-detail .td-side-card { border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg_card); overflow: hidden; }
    .ib-detail .td-section > *, .ib-detail .td-side-card > * { padding-inline: 14px; }
    .ib-detail .td-section > :is(:first-child, h2) , .ib-detail .td-side-card > :first-child { margin: 0; }
    .ib-detail .td-section { padding-bottom: 14px; }
    .ib-detail .td-side-card { padding-bottom: 14px; }
    .ib-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 10px; padding: 0 14px; margin: 0 -1px 12px; padding-top: 0; }
    .ib-detail .td-section-head { border-bottom: 1px solid var(--border); padding-bottom: 9px; background: transparent; }
    .ib-detail .td-section-head h2, .ib-detail .td-side-card h2 { font-size: 15px; font-weight: 700; padding-top: 10px; color: var(--primary_hover); }
    .ib-detail .td-section-head { padding-top: 10px; }
    .ib-detail .td-side-card h2 { margin: 10px 0 8px; }
    .ib-detail .td-section-head > a, .ib-detail .td-count { margin-left: auto; color: var(--primary); font-size: 11.5px; font-weight: 700; text-decoration: none; }
    .ib-detail .td-index { display: none; }
    .ib-detail .td-prose { color: var(--text); line-height: 1.7; font-size: 14px; }
    .ib-detail .td-prose p + p { margin-top: 10px; }
    .ib-detail .td-prose :is(h2,h3) { margin: 16px 0 8px; font-size: 16px; color: var(--primary_hover); }
    .ib-detail .td-prose ul { padding-left: 22px; list-style: disc; }
    .ib-detail .td-share { margin-top: 14px; padding: 12px 14px 0; border-top: 1px solid var(--border); }
    .ib-detail .td-card-grid { display: grid; grid-template-columns: repeat(2, minmax(0,1fr)); gap: 10px; }
    @media (max-width: 620px) { .ib-detail .td-card-grid { grid-template-columns: 1fr; } }
    .ib-detail .td-card { display: flex; flex-direction: column; gap: 4px; padding: 10px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); text-decoration: none; transition: border-color .12s ease; }
    .ib-detail .td-card:hover { border-color: var(--primary); }
    .ib-detail .td-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--border_radius, .25rem); margin-bottom: 5px; }
    .ib-detail .td-card small { font-size: 10.5px; font-weight: 700; color: var(--secondary); }
    .ib-detail .td-card h3 { font-size: 14px; color: var(--primary_hover); }
    .ib-detail .td-card p { font-size: 12.5px; color: var(--text_muted); }
    .ib-detail .td-price { font-size: 14px; color: var(--primary_hover); font-weight: 700; }
    .ib-detail .td-linebreak { white-space: pre-line; }
    .ib-detail .td-gallery { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 6px; }
    @media (max-width: 620px) { .ib-detail .td-gallery { grid-template-columns: repeat(2, 1fr); } }
    .ib-detail .td-gallery img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); }
    .ib-detail .td-map { margin: 0 14px; overflow: hidden; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); }
    .ib-detail .td-map iframe { width: 100%; height: 300px; border: 0; display: block; }
    .ib-detail .td-reviews { display: grid; gap: 10px; margin-bottom: 14px; }
    .ib-detail .td-review { padding: 10px 12px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); }
    .ib-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 10px; font-size: 12.5px; }
    .ib-detail .td-review span { color: var(--text_muted); }
    .ib-detail .td-stars { color: var(--accent); }
    .ib-detail .td-empty { color: var(--text_muted); font-size: 13px; }
    .ib-detail .td-form-title { margin: 4px 0 10px; font-size: 14.5px; color: var(--primary_hover); }
    .ib-detail .td-review-form { display: grid; gap: 8px; }
    .ib-detail .td-review-form :is(input,select,textarea) { padding: 9px 11px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); color: var(--text); font: inherit; font-size: 13.5px; }
    .ib-detail .td-review-form button { justify-self: start; display: inline-flex; align-items: center; gap: 7px; padding: 9px 16px; border: 1px solid var(--accent); border-radius: var(--border_radius, .25rem); background: var(--accent); color: #27180a; font-weight: 700; font-size: 13px; cursor: pointer; }
    .ib-detail .td-message { padding: 10px 12px; border: 1px solid var(--border); border-left: 4px solid var(--secondary); background: var(--primary_light); border-radius: var(--border_radius, .25rem); font-size: 13px; }
    .ib-detail .td-faq { border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); padding: 0 12px; margin-bottom: 6px; }
    .ib-detail .td-faq summary { padding: 9px 0; font-weight: 700; cursor: pointer; }
    .ib-detail .td-faq p { padding: 0 0 9px; color: var(--text_muted); }
    .ib-detail .td-side-label { font-size: 10.5px; font-weight: 700; color: var(--secondary); }
    .ib-detail .td-contact dl { margin: 12px 0 0; display: grid; gap: 0; }
    .ib-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 10px; padding: 8px 0; border-bottom: 1px solid var(--border); font-size: 13px; }
    .ib-detail .td-contact dt { color: var(--text_muted); }
    .ib-detail .td-contact dd { margin: 0; text-align: right; font-weight: 700; }
    .ib-detail .td-contact dd a { color: var(--primary); text-decoration: none; }
    .ib-detail .td-actions { display: grid; gap: 7px; margin-top: 12px; }
    .ib-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 7px; padding: 9px 12px; border: 1px solid var(--border); border-radius: var(--border_radius, .25rem); background: var(--bg); font-size: 13px; font-weight: 700; text-decoration: none; }
    .ib-detail .td-actions a:hover { border-color: var(--primary); background: var(--primary_light); color: var(--primary_hover); }
    .ib-detail .td-claim > a { display: inline-flex; align-items: center; gap: 7px; margin-top: 10px; padding: 9px 14px; border-radius: var(--border_radius, .25rem); background: var(--primary); color: var(--btn_text, #fff); font-size: 12.5px; font-weight: 700; text-decoration: none; }
    .ib-detail .td-claim small { display: block; margin-top: 8px; color: var(--text_muted); font-size: 11px; }
    .ib-detail .td-related a { display: flex; flex-direction: column; gap: 2px; padding: 8px 0; border-bottom: 1px solid var(--border); text-decoration: none; font-size: 13px; }
    .ib-detail .td-related a:hover { color: var(--primary); }
    .ib-detail .td-related span { font-size: 11.5px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .ib * { transition: none !important; } }
</style>
