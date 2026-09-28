{{-- Mobil Uygulama · özel "uygulama" kabuk tasarım sistemi (.ap) --}}
<style>
    /* ── Tema chrome gizleme: tüm sayfa tek uygulama ekranı olur ─────── */
    html.theme-mobile-app body > header,
    html.theme-mobile-app body > footer { display: none !important; }
    html.theme-mobile-app body { background: #dfe3ef !important; }
    @media (max-width: 520px) { html.theme-mobile-app body { background: var(--bg) !important; } }
    html.theme-mobile-app body > main { padding: 0; }

    /* ── Kabuk ───────────────────────────────────────────────────────── */
    .ap {
        color: var(--text);
        font-family: var(--font_body, Inter, system-ui, sans-serif);
        font-size: 14px;
        line-height: 1.5;
        --ap-device-w: 430px;
        --ap-device-h: 880px;
    }
    .ap * { box-sizing: border-box; }
    .ap a { color: inherit; }
    .ap :is(h1,h2,h3,h4) { margin: 0; font-family: var(--font_heading, Inter, sans-serif); font-weight: 800; line-height: 1.2; letter-spacing: -.01em; }
    .ap p { margin: 0; }

    .ap-wrap { display: flex; justify-content: center; padding: 24px 16px 40px; background: #dfe3ef; min-height: 100vh; }
    @media (max-width: 520px) { .ap-wrap { padding: 0; background: var(--bg); min-height: 0; } }

    .ap-device {
        width: var(--ap-device-w);
        max-width: 100%;
        height: var(--ap-device-h);
        max-height: calc(100vh - 64px);
        display: flex;
        flex-direction: column;
        background: var(--bg);
        border: 10px solid #10131a;
        border-radius: 42px;
        overflow: hidden;
        box-shadow: 0 30px 70px rgba(16,19,26,.35);
        position: relative;
    }
    @media (max-width: 520px) {
        .ap-device { width: 100%; max-width: 100%; height: auto; min-height: 100vh; max-height: none; border: 0; border-radius: 0; box-shadow: none; }
    }

    /* ── Durum çubuğu ────────────────────────────────────────────────── */
    .ap-status { display: flex; align-items: center; justify-content: space-between; padding: 8px 20px 4px; font-size: 12px; font-weight: 700; color: var(--text); background: var(--bg_card); }
    .ap-status__sig { display: inline-flex; align-items: center; gap: 6px; letter-spacing: .04em; color: var(--text_muted); }
    .ap-status__bat { display: inline-block; width: 22px; height: 11px; border: 1.5px solid var(--text_muted); border-radius: 3px; position: relative; }
    .ap-status__bat::after { content: ""; position: absolute; inset: 2px; right: 60%; background: var(--secondary); border-radius: 1px; }

    /* ── Uygulama başlığı ────────────────────────────────────────────── */
    .ap-appbar { display: flex; align-items: center; gap: 10px; padding: 8px 16px 12px; background: var(--bg_card); border-bottom: 1px solid var(--border); }
    .ap-appbar__back { flex: 0 0 auto; display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: var(--bg); font-size: 20px; text-decoration: none; color: var(--text); }
    .ap-appbar__back:hover { background: var(--primary_light); color: var(--primary); }
    .ap-appbar__brand { display: flex; align-items: center; gap: 9px; min-width: 0; text-decoration: none; }
    .ap-appbar__mark { flex: 0 0 auto; width: 34px; height: 34px; display: grid; place-items: center; border-radius: 11px; background: linear-gradient(135deg, var(--primary), var(--hero_gradient_to, var(--secondary))); color: var(--btn_text, #fff); font-weight: 800; font-size: 15px; }
    .ap-appbar__name { font-weight: 800; font-size: 15px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ap-appbar__spacer { flex: 1 1 auto; }
    .ap-appbar__btn { flex: 0 0 auto; display: inline-flex; align-items: center; height: 34px; padding: 0 14px; border-radius: 999px; background: var(--primary); color: var(--btn_text, #fff); font-size: 12.5px; font-weight: 700; text-decoration: none; }
    .ap-appbar__btn:hover { background: var(--primary_hover); }

    /* ── Arama hapı ──────────────────────────────────────────────────── */
    .ap-search { margin: 14px 16px 6px; }
    .ap-search form { display: flex; align-items: center; gap: 8px; padding: 4px 4px 4px 14px; border-radius: 999px; background: var(--bg_card); border: 1px solid var(--border); }
    .ap-search input { flex: 1 1 auto; min-width: 0; border: 0; background: transparent; font: inherit; font-size: 14px; color: var(--text); outline: none; }
    .ap-search__go { flex: 0 0 auto; display: grid; place-items: center; width: 34px; height: 34px; border: 0; cursor: pointer; border-radius: 50%; background: var(--primary); color: var(--btn_text, #fff); }

    /* ── Ekran / kaydırma ────────────────────────────────────────────── */
    .ap-scroll { flex: 1 1 auto; overflow-y: auto; overflow-x: hidden; -webkit-overflow-scrolling: touch; padding-bottom: 8px; }
    .ap-scroll::-webkit-scrollbar { width: 5px; }
    .ap-scroll::-webkit-scrollbar-thumb { background: var(--border); border-radius: 999px; }

    /* ── Bölüm başlığı ───────────────────────────────────────────────── */
    .ap-sec { padding: 16px 16px 6px; }
    .ap-sec__head { display: flex; align-items: baseline; justify-content: space-between; gap: 10px; margin-bottom: 10px; }
    .ap-sec__head h2 { font-size: 16px; font-weight: 800; }
    .ap-sec__head a { font-size: 12px; font-weight: 700; color: var(--primary); text-decoration: none; }

    /* ── Kayan kategori chip'leri ────────────────────────────────────── */
    .ap-chips { display: flex; gap: 8px; overflow-x: auto; padding: 8px 16px; scroll-snap-type: x proximity; }
    .ap-chips::-webkit-scrollbar { display: none; }
    .ap-chip { flex: 0 0 auto; display: inline-flex; align-items: center; gap: 6px; padding: 8px 14px; border-radius: 999px; background: var(--bg_card); border: 1px solid var(--border); font-size: 13px; font-weight: 600; text-decoration: none; white-space: nowrap; transition: background .14s ease, color .14s ease, border-color .14s ease; }
    .ap-chip:hover, .ap-chip.is-active { background: var(--primary); border-color: var(--primary); color: var(--btn_text, #fff); }
    .ap-chip__n { font-size: 11px; opacity: .7; }

    /* ── Feed kartı ──────────────────────────────────────────────────── */
    .ap-feed { display: flex; flex-direction: column; gap: 12px; padding: 6px 16px 16px; }
    .ap-post { display: block; border: 1px solid var(--border); border-radius: 18px; background: var(--bg_card); overflow: hidden; text-decoration: none; box-shadow: var(--card_shadow, 0 2px 8px rgba(0,0,0,.04)); transition: transform .12s ease, box-shadow .12s ease; }
    .ap-post:active { transform: scale(.99); }
    .ap-post__cover { display: block; aspect-ratio: 16/9; background: var(--primary_light); overflow: hidden; }
    .ap-post__cover img { width: 100%; height: 100%; object-fit: cover; }
    .ap-post__cover--ph { display: grid; place-items: center; font-size: 40px; font-weight: 800; color: var(--primary); }
    .ap-post__body { padding: 12px 14px 14px; }
    .ap-post__cat { font-size: 11px; font-weight: 700; letter-spacing: .04em; text-transform: uppercase; color: var(--secondary); }
    .ap-post__body h3 { font-size: 15.5px; font-weight: 800; margin-top: 4px; }
    .ap-post__text { margin-top: 6px; font-size: 13px; color: var(--text_muted); line-height: 1.5; display: -webkit-box; -webkit-line-clamp: 2; line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; }
    .ap-post__row { display: flex; align-items: center; gap: 6px; margin-top: 10px; font-size: 12px; color: var(--text_muted); }
    .ap-post__dot { opacity: .5; }
    .ap-post__actions { display: flex; gap: 8px; margin-top: 12px; }

    /* ── Liste satırı ────────────────────────────────────────────────── */
    .ap-list { display: flex; flex-direction: column; padding: 6px 0 8px; }
    .ap-row { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-bottom: 1px solid var(--border); text-decoration: none; }
    .ap-row:active { background: var(--primary_light); }
    .ap-row__av { flex: 0 0 auto; width: 46px; height: 46px; display: grid; place-items: center; border-radius: 14px; background: var(--primary_light); color: var(--primary); font-weight: 800; font-size: 18px; overflow: hidden; }
    .ap-row__av img { width: 100%; height: 100%; object-fit: cover; }
    .ap-row__main { flex: 1 1 auto; min-width: 0; }
    .ap-row__main h3 { font-size: 14.5px; font-weight: 700; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ap-row__meta { font-size: 12px; color: var(--text_muted); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .ap-row__chev { flex: 0 0 auto; color: var(--text_muted); font-size: 18px; }

    /* ── Düğmeler ────────────────────────────────────────────────────── */
    .ap-btn { display: inline-flex; align-items: center; justify-content: center; gap: 6px; padding: 10px 16px; border: 1px solid var(--primary); border-radius: 12px; background: var(--primary); color: var(--btn_text, #fff); font: inherit; font-weight: 700; font-size: 13px; text-decoration: none; cursor: pointer; }
    .ap-btn:hover { background: var(--primary_hover); }
    .ap-btn--ghost { background: var(--bg_card); border-color: var(--border); color: var(--text); }
    .ap-btn--ghost:hover { border-color: var(--primary); color: var(--primary); }
    .ap-btn--block { width: 100%; }

    /* ── Kart (vitrin / kategori) ────────────────────────────────────── */
    .ap-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; padding: 6px 16px 16px; }
    .ap-tile { display: flex; flex-direction: column; gap: 4px; padding: 14px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); text-decoration: none; }
    .ap-tile b { font-size: 14px; font-weight: 700; }
    .ap-tile small { font-size: 11.5px; color: var(--text_muted); }

    /* ── Filtre ──────────────────────────────────────────────────────── */
    .ap-filter { margin: 8px 16px; padding: 12px 14px; border: 1px solid var(--border); border-radius: 16px; background: var(--bg_card); display: flex; flex-direction: column; gap: 10px; }
    .ap-filter label { display: flex; flex-direction: column; gap: 4px; font-size: 11px; font-weight: 700; color: var(--text_muted); }
    .ap-filter :is(input,select) { padding: 9px 11px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font: inherit; font-size: 13.5px; }
    .ap-filter :is(input,select):focus { outline: none; border-color: var(--primary); }

    /* ── Bilgi / prose ───────────────────────────────────────────────── */
    .ap-prose { padding: 6px 16px 16px; font-size: 14px; line-height: 1.65; }
    .ap-prose > * + * { margin-top: 12px; }
    .ap-prose :is(h2,h3) { font-size: 16px; font-weight: 800; }
    .ap-prose a { color: var(--primary); font-weight: 700; }
    .ap-prose ul { padding-left: 20px; list-style: disc; }
    .ap-prose details { border: 1px solid var(--border); border-radius: 12px; background: var(--bg_card); padding: 0 12px; margin-bottom: 8px; }
    .ap-prose summary { padding: 10px 0; font-weight: 700; cursor: pointer; }
    .ap-prose details p { padding: 0 0 10px; color: var(--text_muted); }

    /* ── Paket planları ──────────────────────────────────────────────── */
    .ap-plans { display: grid; gap: 12px; padding: 10px 16px 16px; }
    .ap-plan { display: flex; flex-direction: column; gap: 10px; padding: 18px; border: 1px solid var(--border); border-radius: 18px; background: var(--bg_card); }
    .ap-plan--featured { border-color: var(--primary); box-shadow: 0 0 0 1px var(--primary); }
    .ap-plan__badge { align-self: flex-start; font-size: 10.5px; font-weight: 800; text-transform: uppercase; letter-spacing: .04em; color: #fff; background: var(--secondary); padding: 3px 10px; border-radius: 999px; }
    .ap-plan h2 { font-size: 18px; font-weight: 800; }
    .ap-plan__price { font-size: 30px; font-weight: 800; color: var(--primary); }
    .ap-plan__price small { display: block; font-size: 11.5px; font-weight: 600; color: var(--text_muted); }
    .ap-plan ul { margin: 0; padding: 0; list-style: none; display: grid; gap: 8px; }
    .ap-plan li { font-size: 13px; padding-left: 20px; position: relative; }
    .ap-plan li::before { content: "✓"; position: absolute; left: 0; color: var(--secondary); font-weight: 800; }

    /* ── Form ────────────────────────────────────────────────────────── */
    .ap-form { display: flex; flex-direction: column; gap: 12px; padding: 12px 16px 16px; }
    .ap-form label { display: flex; flex-direction: column; gap: 5px; font-size: 12px; font-weight: 700; color: var(--text_muted); }
    .ap-form :is(input,select,textarea) { padding: 11px 13px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .ap-form :is(input,select,textarea):focus { outline: none; border-color: var(--primary); }

    /* ── İstatistik ──────────────────────────────────────────────────── */
    .ap-stats { display: grid; grid-template-columns: repeat(3, 1fr); gap: 8px; padding: 10px 16px; }
    .ap-stats > div { text-align: center; padding: 14px 8px; border-radius: 16px; background: var(--bg_card); border: 1px solid var(--border); }
    .ap-stats b { display: block; font-size: 22px; font-weight: 800; color: var(--primary); }
    .ap-stats span { font-size: 11px; color: var(--text_muted); font-weight: 600; }

    .ap-empty { padding: 40px 24px; text-align: center; color: var(--text_muted); }
    .ap-empty a { color: var(--primary); font-weight: 700; }
    .ap-note { margin: 10px 16px; padding: 12px 14px; border-left: 3px solid var(--secondary); background: var(--primary_light); border-radius: 10px; font-size: 13px; }

    /* ── Sayfalama ───────────────────────────────────────────────────── */
    .ap-pag { padding: 12px 16px 20px; }
    .ap-pag nav { display: flex; flex-wrap: wrap; justify-content: center; gap: 3px; }
    .ap-pag :is(a,span) { display: inline-flex; align-items: center; justify-content: center; min-width: 38px; padding: 8px 10px; border: 1px solid var(--border); border-radius: 10px; font-size: 13px; font-weight: 700; color: var(--primary); background: var(--bg_card); text-decoration: none; }
    .ap-pag [aria-current="page"], .ap-pag .current { background: var(--primary); border-color: var(--primary); color: #fff; }
    .ap-pag svg { width: 13px; height: 13px; }

    /* ── Alt sekme çubuğu ────────────────────────────────────────────── */
    .ap-tabbar { flex: 0 0 auto; display: grid; grid-template-columns: repeat(5, 1fr); background: var(--bg_card); border-top: 1px solid var(--border); padding: 6px 4px calc(6px + env(safe-area-inset-bottom, 0px)); }
    .ap-tab { display: flex; flex-direction: column; align-items: center; gap: 3px; padding: 6px 2px; border-radius: 12px; font-size: 10px; font-weight: 600; color: var(--text_muted); text-decoration: none; }
    .ap-tab__ico { font-size: 19px; line-height: 1; }
    .ap-tab.is-active { color: var(--primary); }
    .ap-tab--cta { color: var(--primary); }
    .ap-tab--cta .ap-tab__ico { display: grid; place-items: center; width: 38px; height: 38px; margin-top: -14px; border-radius: 50%; background: var(--primary); color: var(--btn_text, #fff); font-size: 22px; box-shadow: 0 6px 16px rgba(67,56,202,.4); }

    /* ── Detay ───────────────────────────────────────────────────────── */
    .ap-detail__cover { position: relative; aspect-ratio: 4/3; background: var(--primary_light); overflow: hidden; }
    .ap-detail__cover img { width: 100%; height: 100%; object-fit: cover; }
    .ap-detail__cover--ph { display: grid; place-items: center; font-size: 60px; font-weight: 800; color: var(--primary); }
    .ap-detail__sheet { margin-top: -22px; background: var(--bg); border-radius: 24px 24px 0 0; padding: 18px 16px 8px; position: relative; }
    .ap-detail__sheet h1 { font-size: 22px; font-weight: 800; }
    .ap-kicker { font-size: 12px; font-weight: 700; color: var(--secondary); }
    .ap-actions { display: flex; gap: 8px; margin: 14px 0 4px; }
    .ap-actions .ap-btn { flex: 1 1 0; }
    .ap-facts { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; padding: 8px 16px 4px; }
    .ap-facts > div { padding: 12px; border-radius: 14px; background: var(--bg_card); border: 1px solid var(--border); }
    .ap-facts dt { font-size: 11px; font-weight: 700; color: var(--text_muted); }
    .ap-facts dd { margin: 4px 0 0; font-size: 14px; font-weight: 700; }
    .ap-facts dd a { color: var(--primary); text-decoration: none; }

    /* td-* ortak section/sidebar — mobil uygulamaya bağlanır */
    .ap-detail .td-section, .ap-detail .td-side-card { border: 1px solid var(--border); border-radius: 18px; background: var(--bg_card); margin: 10px 16px; padding: 14px 16px 16px; overflow: hidden; }
    .ap-detail .td-section > *, .ap-detail .td-side-card > * { padding-inline: 0; }
    .ap-detail .td-section-head { display: flex; align-items: baseline; gap: 10px; margin-bottom: 10px; padding-bottom: 8px; border-bottom: 1px solid var(--border); }
    .ap-detail .td-section-head h2, .ap-detail .td-side-card h2 { font-size: 16px; font-weight: 800; }
    .ap-detail .td-section-head > a, .ap-detail .td-count { margin-left: auto; color: var(--primary); font-size: 12px; font-weight: 700; text-decoration: none; }
    .ap-detail .td-index { display: none; }
    .ap-detail .td-prose { font-size: 14px; line-height: 1.65; }
    .ap-detail .td-prose p + p { margin-top: 10px; }
    .ap-detail .td-prose :is(h2,h3) { margin: 14px 0 8px; font-size: 16px; font-weight: 800; }
    .ap-detail .td-prose ul { padding-left: 22px; list-style: disc; }
    .ap-detail .td-share { margin-top: 12px; padding-top: 12px; border-top: 1px solid var(--border); }
    .ap-detail .td-card-grid { display: grid; grid-template-columns: 1fr; gap: 10px; }
    .ap-detail .td-card { display: flex; flex-direction: column; gap: 4px; padding: 12px; border: 1px solid var(--border); border-radius: 14px; background: var(--bg); text-decoration: none; }
    .ap-detail .td-card:hover { border-color: var(--primary); }
    .ap-detail .td-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: 12px; margin-bottom: 6px; }
    .ap-detail .td-card small { font-size: 11px; font-weight: 700; color: var(--secondary); }
    .ap-detail .td-card h3 { font-size: 14.5px; font-weight: 800; }
    .ap-detail .td-card p { font-size: 12.5px; color: var(--text_muted); }
    .ap-detail .td-price { font-size: 14px; color: var(--primary); font-weight: 800; }
    .ap-detail .td-linebreak { white-space: pre-line; }
    .ap-detail .td-gallery { display: grid; grid-template-columns: repeat(2, 1fr); gap: 6px; }
    .ap-detail .td-gallery img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border: 1px solid var(--border); border-radius: 10px; }
    .ap-detail .td-map { overflow: hidden; border: 1px solid var(--border); border-radius: 14px; }
    .ap-detail .td-map iframe { width: 100%; height: 240px; border: 0; display: block; }
    .ap-detail .td-reviews { display: grid; gap: 10px; margin-bottom: 12px; }
    .ap-detail .td-review { padding: 12px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); }
    .ap-detail .td-review > div:first-child { display: flex; justify-content: space-between; gap: 10px; font-size: 12.5px; }
    .ap-detail .td-review span { color: var(--text_muted); }
    .ap-detail .td-stars { color: var(--accent); }
    .ap-detail .td-empty { color: var(--text_muted); font-size: 13px; }
    .ap-detail .td-form-title { margin: 4px 0 10px; font-size: 14.5px; font-weight: 800; }
    .ap-detail .td-review-form { display: grid; gap: 8px; }
    .ap-detail .td-review-form :is(input,select,textarea) { padding: 10px 12px; border: 1px solid var(--border); border-radius: 10px; background: var(--bg); color: var(--text); font: inherit; font-size: 14px; }
    .ap-detail .td-review-form button { justify-self: start; }
    .ap-detail .td-message { padding: 10px 12px; border-left: 3px solid var(--secondary); background: var(--primary_light); border-radius: 10px; font-size: 13px; }
    .ap-detail .td-faq { border: 1px solid var(--border); border-radius: 12px; background: var(--bg); padding: 0 12px; margin-bottom: 6px; }
    .ap-detail .td-faq summary { padding: 10px 0; font-weight: 700; cursor: pointer; }
    .ap-detail .td-faq p { padding: 0 0 10px; color: var(--text_muted); }
    .ap-detail .td-side-label { font-size: 11px; font-weight: 700; color: var(--secondary); text-transform: uppercase; letter-spacing: .03em; }
    .ap-detail .td-contact dl { margin: 10px 0 0; display: grid; }
    .ap-detail .td-contact dl > div { display: flex; justify-content: space-between; gap: 10px; padding: 9px 0; border-bottom: 1px solid var(--border); font-size: 13px; }
    .ap-detail .td-contact dt { color: var(--text_muted); }
    .ap-detail .td-contact dd { margin: 0; text-align: right; font-weight: 700; }
    .ap-detail .td-contact dd a { color: var(--primary); text-decoration: none; }
    .ap-detail .td-actions { display: grid; gap: 7px; margin-top: 10px; }
    .ap-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 6px; padding: 11px 12px; border: 1px solid var(--border); border-radius: 12px; background: var(--bg); font-size: 13px; font-weight: 700; text-decoration: none; }
    .ap-detail .td-actions a:hover { border-color: var(--primary); color: var(--primary); }
    .ap-detail .td-claim > a { display: inline-flex; align-items: center; gap: 6px; margin-top: 10px; padding: 10px 16px; border-radius: 12px; background: var(--primary); color: var(--btn_text, #fff); font-size: 12.5px; font-weight: 700; text-decoration: none; }
    .ap-detail .td-claim small { display: block; margin-top: 8px; color: var(--text_muted); font-size: 11px; }
    .ap-detail .td-related a { display: flex; flex-direction: column; gap: 2px; padding: 9px 0; border-bottom: 1px solid var(--border); text-decoration: none; font-size: 13px; }
    .ap-detail .td-related a:hover { color: var(--primary); }
    .ap-detail .td-related span { font-size: 11.5px; color: var(--text_muted); }

    @media (prefers-reduced-motion: reduce) { .ap * { transition: none !important; } }
</style>
