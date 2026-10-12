<style>
    .theme-sun-listing { --sun: #ffd83d; scroll-padding-top: 88px; }
    .theme-sun-listing body { background: var(--bg); color: var(--text); font-size: 16px; line-height: 1.6; }
    .theme-sun-listing :where(a,button,input,select):focus-visible { outline: 3px solid var(--text); outline-offset: 3px; }
    .theme-sun-listing #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }
    .sl-wrap { max-width: var(--page_width); margin-inline: auto; padding-inline: 24px; }

    /* Üst menü */
    .sl-header { position: sticky; top: 0; z-index: 50; background: rgba(255,253,244,.94); backdrop-filter: blur(10px); border-bottom: 1px solid var(--border); }
    .sl-header__row { display: flex; align-items: center; justify-content: space-between; gap: 20px; min-height: 76px; }
    .sl-brand { display: inline-flex; align-items: center; gap: 10px; flex-shrink: 0; font-size: 22px; font-weight: 800; letter-spacing: -.02em; }
    .sl-brand img { width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .sl-brand__mark { display: grid; place-items: center; width: 40px; height: 40px; border-radius: 12px; background: var(--sun); color: var(--text); font-size: 22px; font-weight: 800; box-shadow: 0 6px 16px rgba(255,200,0,.35); }
    .sl-nav { display: flex; align-items: center; gap: 26px; font-size: 16px; font-weight: 600; }
    .sl-nav a:hover { text-decoration: underline; text-decoration-thickness: 3px; text-decoration-color: var(--sun); text-underline-offset: 6px; }
    .sl-header__actions { display: flex; align-items: center; gap: 12px; }
    .sl-pill { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 48px; padding: 12px 24px; border-radius: 999px; background: var(--sun); color: var(--text); font-size: 16px; font-weight: 800; box-shadow: 0 8px 22px rgba(255,200,0,.35); transition: transform .15s ease, box-shadow .15s ease; white-space: nowrap; }
    .sl-pill:hover { transform: translateY(-2px); box-shadow: 0 12px 26px rgba(255,200,0,.45); }
    .sl-pill--dark { background: var(--text); color: #fff; box-shadow: none; }
    .sl-header__cta-short { display: none; }
    .sl-menu { display: none; padding: 8px; }

    /* Açılış */
    .sl-home { overflow: clip; }
    .sl-hero { position: relative; padding: 72px 0 56px; text-align: center; background:
        radial-gradient(circle at 15% 10%, rgba(255,216,61,.35), transparent 38%),
        radial-gradient(circle at 90% 0%, rgba(255,216,61,.22), transparent 34%); }
    .sl-badge { display: inline-flex; align-items: center; gap: 10px; padding: 9px 18px; border: 1px solid var(--border); border-radius: 999px; background: #fff; box-shadow: 0 4px 14px rgba(17,24,39,.06); font-size: 15px; font-weight: 700; }
    .sl-badge::before { content: ''; width: 9px; height: 9px; border-radius: 50%; background: #22c55e; box-shadow: 0 0 0 4px rgba(34,197,94,.18); }
    .sl-hero h1 { max-width: 980px; margin: 26px auto 0; font-size: clamp(36px,6.4vw,76px); line-height: 1.08; font-weight: 800; letter-spacing: -.035em; text-wrap: balance; }
    .sl-hero h1 mark { display: inline-block; margin-top: 10px; padding: 2px 22px 6px; border-radius: 22px; background: var(--sun); color: var(--text); box-shadow: 0 14px 34px rgba(255,200,0,.35); transform: rotate(-1.2deg); }
    .sl-hero__lede { max-width: 640px; margin: 26px auto 0; color: var(--text_muted); font-size: 19px; line-height: 1.7; }
    .sl-search { display: grid; grid-template-columns: minmax(0,1.5fr) minmax(0,1fr) auto; gap: 8px; max-width: 880px; margin: 34px auto 0; padding: 10px; border: 1px solid var(--border); border-radius: 999px; background: #fff; box-shadow: 0 18px 44px rgba(17,24,39,.1); text-align: left; }
    .sl-search__field { display: flex; flex-direction: column; justify-content: center; min-width: 0; padding: 6px 22px; border-radius: 999px; }
    .sl-search__field + .sl-search__field { border-left: 1px solid var(--border); border-radius: 0; }
    .sl-search label { font-size: 12px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; color: var(--text_muted); }
    .sl-search :where(input,select) { width: 100%; min-width: 0; border: 0; background: transparent; color: var(--text); font-size: 17px; font-weight: 600; padding: 2px 0; }
    .sl-search :where(input,select):focus { outline: none; }
    .sl-search .sl-pill { min-height: 56px; padding-inline: 30px; }
    .sl-stats { display: flex; justify-content: center; flex-wrap: wrap; gap: 14px 46px; margin-top: 34px; }
    .sl-stat strong { display: block; font-size: 30px; font-weight: 800; letter-spacing: -.02em; }
    .sl-stat span { font-size: 13px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--text_muted); }

    /* Bölümler */
    .sl-section { padding: 72px 0; }
    .sl-section--white { background: #fff; border-block: 1px solid var(--border); }
    .sl-head { display: flex; align-items: end; justify-content: space-between; gap: 20px; margin-bottom: 32px; }
    .sl-head h2 { font-size: clamp(28px,3.6vw,40px); line-height: 1.15; font-weight: 800; letter-spacing: -.03em; }
    .sl-head p { margin-top: 8px; color: var(--text_muted); font-size: 17px; }
    .sl-link { flex-shrink: 0; font-size: 16px; font-weight: 800; text-decoration: underline; text-decoration-thickness: 3px; text-decoration-color: var(--sun); text-underline-offset: 6px; }

    /* Öne çıkan firma kartları */
    .sl-cards { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 24px; }
    .sl-card { position: relative; display: flex; flex-direction: column; overflow: hidden; border: 1px solid var(--border); border-radius: 26px; background: #fff; box-shadow: 0 10px 28px rgba(17,24,39,.06); transition: transform .18s ease, box-shadow .18s ease; }
    .sl-card:hover { transform: translateY(-4px); box-shadow: 0 18px 40px rgba(17,24,39,.12); }
    .sl-card__media { position: relative; height: 170px; background: linear-gradient(135deg, #ffe58a, #ffd83d); }
    .sl-card__media img { width: 100%; height: 100%; object-fit: cover; }
    .sl-card__initial { display: grid; place-items: center; height: 100%; font-size: 64px; font-weight: 800; color: rgba(17,24,39,.55); }
    .sl-card__chips { position: absolute; top: 14px; left: 14px; right: 14px; display: flex; flex-wrap: wrap; gap: 8px; }
    .sl-chip { display: inline-flex; align-items: center; gap: 6px; padding: 5px 12px; border-radius: 999px; background: #fff; color: var(--text); font-size: 13px; font-weight: 800; box-shadow: 0 4px 12px rgba(17,24,39,.12); }
    .sl-chip--open { color: #15803d; }
    .sl-chip--closed { color: #b91c1c; }
    .sl-chip--star { background: var(--text); color: #fff; }
    .sl-card__body { display: flex; flex: 1; flex-direction: column; padding: 22px; }
    .sl-card__cat { font-size: 13px; font-weight: 800; letter-spacing: .06em; text-transform: uppercase; color: var(--text_muted); }
    .sl-card h3 { margin-top: 6px; font-size: 22px; line-height: 1.25; font-weight: 800; letter-spacing: -.02em; overflow-wrap: anywhere; }
    .sl-card__tag { margin-top: 10px; color: var(--text_muted); font-size: 16px; line-height: 1.6; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; overflow: hidden; }
    .sl-card__place { margin-top: 12px; font-size: 15px; font-weight: 600; color: var(--text); }
    .sl-card__actions { display: flex; gap: 10px; margin-top: auto; padding-top: 20px; }
    .sl-card__actions .sl-pill { flex: 1; min-height: 46px; padding: 10px 16px; font-size: 15px; }
    .sl-card__phone { display: inline-flex; align-items: center; justify-content: center; min-height: 46px; padding: 10px 16px; border: 2px solid var(--text); border-radius: 999px; font-size: 15px; font-weight: 800; }

    /* Adımlar, hızlı keşif, avantajlar */
    .sl-steps { display: grid; grid-template-columns: repeat(4, minmax(0,1fr)); gap: 20px; }
    .sl-step { padding: 26px; border: 1px solid var(--border); border-radius: 24px; background: #fff; }
    .sl-step__no { display: grid; place-items: center; width: 46px; height: 46px; border-radius: 14px; background: var(--sun); font-size: 20px; font-weight: 800; }
    .sl-step h3 { margin-top: 16px; font-size: 20px; font-weight: 800; }
    .sl-step p { margin-top: 8px; color: var(--text_muted); font-size: 16px; line-height: 1.65; }
    .sl-quick { display: flex; flex-wrap: wrap; gap: 12px; }
    .sl-quick a { display: inline-flex; align-items: center; gap: 10px; padding: 12px 20px; border: 2px solid var(--text); border-radius: 999px; background: #fff; font-size: 16px; font-weight: 700; transition: background .15s ease, transform .15s ease; }
    .sl-quick a:hover { background: var(--sun); transform: translateY(-2px); }
    .sl-quick small { padding: 2px 9px; border-radius: 999px; background: var(--primary_light); font-size: 13px; font-weight: 800; }
    .sl-why { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 20px; }
    .sl-why > div { padding: 28px; border-radius: 26px; background: var(--text); color: #fff; }
    .sl-why h3 { font-size: 21px; font-weight: 800; color: var(--sun); }
    .sl-why p { margin-top: 10px; color: #d6dae3; font-size: 16px; line-height: 1.7; }
    .sl-posts { display: grid; grid-template-columns: repeat(3, minmax(0,1fr)); gap: 24px; }
    .sl-post { display: flex; flex-direction: column; gap: 10px; padding: 26px; border: 1px solid var(--border); border-radius: 24px; background: #fff; }
    .sl-post:hover { border-color: var(--text); }
    .sl-post h3 { font-size: 20px; line-height: 1.35; font-weight: 800; overflow-wrap: anywhere; }
    .sl-post p { color: var(--text_muted); font-size: 16px; display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 3; overflow: hidden; }
    .sl-cloud { display: flex; flex-wrap: wrap; gap: 10px; }
    .sl-cloud a { padding: 9px 16px; border-radius: 999px; background: var(--primary_light); font-size: 15px; font-weight: 700; }
    .sl-cloud a:hover { background: var(--sun); }
    .sl-banner { display: flex; align-items: center; justify-content: space-between; gap: 30px; padding: 44px; border-radius: 34px; background: var(--sun); box-shadow: 0 22px 50px rgba(255,200,0,.35); }
    .sl-banner h2 { font-size: clamp(26px,3.4vw,38px); line-height: 1.15; font-weight: 800; letter-spacing: -.03em; }
    .sl-banner p { margin-top: 10px; max-width: 520px; font-size: 17px; }
    .sl-empty { grid-column: 1 / -1; padding: 28px; border: 2px dashed var(--border); border-radius: 22px; color: var(--text_muted); text-align: center; }
    .sl-float { display: none; }

    @media (max-width: 980px) {
        .sl-nav { display: none; }
        .sl-menu { display: inline-flex; }
        .sl-cards, .sl-posts { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .sl-steps { grid-template-columns: repeat(2, minmax(0,1fr)); }
        .sl-why { grid-template-columns: minmax(0,1fr); }
    }
    @media (max-width: 720px) {
        .sl-wrap { padding-inline: 18px; }
        .sl-header__row { min-height: 68px; gap: 10px; }
        .sl-brand { min-width: 0; flex-shrink: 1; font-size: 19px; gap: 8px; }
        .sl-brand > span:last-child { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
        .sl-header__actions { flex-shrink: 0; gap: 6px; }
        .sl-header__actions .sl-pill { min-height: 42px; padding: 8px 14px; font-size: 15px; }
        .sl-header__actions .sl-pill > span[aria-hidden="true"]:first-child { display: none; }
        .sl-header__cta-long { display: none; }
        .sl-header__cta-short { display: inline; }
        .sl-hero { padding: 48px 0 40px; }
        .sl-search { grid-template-columns: minmax(0,1fr); border-radius: 28px; }
        .sl-search__field + .sl-search__field { border-left: 0; border-top: 1px solid var(--border); border-radius: 0; }
        .sl-search .sl-pill { width: 100%; }
        .sl-cards, .sl-posts, .sl-steps { grid-template-columns: minmax(0,1fr); }
        .sl-section { padding: 52px 0; }
        .sl-head { flex-direction: column; align-items: flex-start; }
        .sl-banner { flex-direction: column; align-items: flex-start; padding: 30px; border-radius: 28px; }
        .sl-float { position: fixed; z-index: 40; right: 16px; bottom: 16px; display: inline-flex; min-height: 54px; padding: 14px 26px; font-size: 17px; box-shadow: 0 14px 34px rgba(17,24,39,.28); }
    }
    @media (prefers-reduced-motion: reduce) { .sl-card, .sl-pill, .sl-quick a { transition: none; } .sl-card:hover, .sl-pill:hover, .sl-quick a:hover { transform: none; } .sl-hero h1 mark { transform: none; } }
</style>
