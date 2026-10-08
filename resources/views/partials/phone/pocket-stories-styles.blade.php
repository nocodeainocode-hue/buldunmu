<style>
    /* Cepte Hikâyeler · sade ve editoryal görünüm */
    html.theme-pocket-stories {
        --ph-wall: #f5f4ef; --ph-wall2: #f5f4ef; --ph-bezel: #f5f4ef;
        --ph-bg: #f5f4ef; --ph-screen: #f5f4ef; --ph-card: #fff; --ph-card2: #edf1ec;
        --ph-ink: #202d28; --ph-muted: #65716a; --ph-line: #dce1da;
        --ph-primary: #29483d; --ph-primary2: #29483d; --ph-accent: #a5794e;
        --ph-nav: #fff; --ph-tab: #fff; --ph-radius: 14px; --ph-radius-sm: 10px;
        --ph-pill: 8px; --ph-shadow: 0 3px 12px rgba(32,45,40,.04);
    }
    html.theme-pocket-stories body { background: #f5f4ef !important; }
    html.theme-pocket-stories .ph-device { background: #f5f4ef; border: 0; border-radius: 0; box-shadow: none; }
    html.theme-pocket-stories .ph-device::after,
    html.theme-pocket-stories .ph-hero::before { display: none; }
    html.theme-pocket-stories .ph-appbar { background: #fff; border-bottom: 1px solid var(--ph-line); backdrop-filter: none; }
    html.theme-pocket-stories .ph-appbar__mark { background: var(--ph-primary); border-radius: 7px; }
    html.theme-pocket-stories .ph-appbar__btn--go,
    html.theme-pocket-stories .ph-tab--cta .ph-tab__ico,
    html.theme-pocket-stories .ph-btn,
    html.theme-pocket-stories .ph-search button,
    html.theme-pocket-stories .ph-chip.is-on,
    html.theme-pocket-stories .ph-pagination [aria-current="page"],
    html.theme-pocket-stories .ph-pagination .current,
    html.theme-pocket-stories .ph-quick a:first-child,
    html.theme-pocket-stories .ph-detail .td-review-form button,
    html.theme-pocket-stories .ph-detail .td-claim > a { background: var(--ph-primary); box-shadow: none; }
    html.theme-pocket-stories .ph-tabbar { background: #fff; backdrop-filter: none; }
    html.theme-pocket-stories .ph-tab.is-active { background: var(--ph-card2); color: var(--ph-primary); transform: none; }
    html.theme-pocket-stories .ph-tab.is-active .ph-tab__ico { transform: none; }
    html.theme-pocket-stories .ph-eyebrow { color: var(--ph-primary); letter-spacing: .11em; }
    html.theme-pocket-stories .ph-eyebrow::before { width: 18px; height: 1px; border-radius: 0; background: var(--ph-accent); }
    html.theme-pocket-stories .ph-hero { margin: 16px 16px 0; padding: 25px 20px 10px; background: #fff; border: 1px solid var(--ph-line); border-radius: 12px; }
    html.theme-pocket-stories .ph-h1 { margin-top: 11px; font-size: clamp(29px, 6vw, 39px); line-height: 1.12; letter-spacing: -.045em; }
    html.theme-pocket-stories .ph-lead { line-height: 1.6; }
    html.theme-pocket-stories .ph-hero .ph-search { margin: 19px 0 8px; border-radius: 8px; box-shadow: none; }
    html.theme-pocket-stories .ph-section { padding-top: 26px; }
    html.theme-pocket-stories .ph-section__head { align-items: end; }
    html.theme-pocket-stories .ph-h2 { font-size: 21px; letter-spacing: -.03em; }
    html.theme-pocket-stories .ph-ring { width: 76px; gap: 8px; }
    html.theme-pocket-stories .ph-ring__av { width: 58px; height: 58px; padding: 0; border: 1px solid var(--ph-line); border-radius: 10px; background: #fff; }
    html.theme-pocket-stories .ph-ring__av::after { display: none; }
    html.theme-pocket-stories .ph-ring__in { border-radius: 9px; background: #fff; color: var(--ph-primary); font-size: 20px; }
    html.theme-pocket-stories .ph-ring__label { max-width: 76px; color: var(--ph-ink); }
    html.theme-pocket-stories .ph-stats { gap: 0; margin: 10px 16px 0; padding: 0; border: 1px solid var(--ph-line); border-radius: 10px; overflow: hidden; background: #fff; }
    html.theme-pocket-stories .ph-stats > div { border: 0; border-radius: 0; border-right: 1px solid var(--ph-line); border-bottom: 1px solid var(--ph-line); }
    html.theme-pocket-stories .ph-stats b { background: none; color: var(--ph-ink); font-size: 26px; }
    html.theme-pocket-stories .ph-card,
    html.theme-pocket-stories .ph-row,
    html.theme-pocket-stories .ph-sheet { box-shadow: var(--ph-shadow); }
    html.theme-pocket-stories .ph-card__media,
    html.theme-pocket-stories .ph-detail__cover { background: #dfe7dd; }
    html.theme-pocket-stories .ph-card__initial { color: var(--ph-primary); font-size: 42px; }
    html.theme-pocket-stories .ph-card__badge { background: #fff; color: var(--ph-primary); border: 1px solid var(--ph-line); border-radius: 5px; }
    html.theme-pocket-stories .ph-card__foot { border-top-style: solid; }
    html.theme-pocket-stories .ph-row__av { background: var(--ph-card2); color: var(--ph-primary); border-radius: 8px; }
    html.theme-pocket-stories .ph-promo { padding: 20px; border: 1px solid var(--ph-line); border-left: 3px solid var(--ph-primary); border-radius: 10px; background: #fff; }
    html.theme-pocket-stories .ph-plan--featured { background: var(--ph-card2); }
    html.theme-pocket-stories .ph-detail__cover::after { display: none; }
    @media (min-width: 720px) {
        html.theme-pocket-stories .ph-wrap { display: block; min-height: 100dvh; padding: 0; background: #f5f4ef; }
        html.theme-pocket-stories .ph-device { width: min(100%, 680px); height: auto; min-height: 100dvh; margin: 0 auto; }
        html.theme-pocket-stories .ph-screen { overflow: visible; border-radius: 0; }
        html.theme-pocket-stories .ph-notch { display: none; }
        html.theme-pocket-stories .ph-tabbar { position: sticky; bottom: 0; }
    }
</style>
