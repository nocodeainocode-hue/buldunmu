<style>
    .theme-local-ledger { scroll-padding-top: 96px; }
    .theme-local-ledger body { background: var(--bg); }
    .theme-local-ledger :where(a,button,input,select):focus-visible { outline: 3px solid var(--primary); outline-offset: 4px; }
    .yl-wrap { max-width: var(--page_width); margin-inline: auto; padding-inline: 32px; }
    .yl-header { position: sticky; top: 0; z-index: 50; border-bottom: 1px solid var(--border); background: var(--bg_card); }
    .yl-header__row { display: flex; align-items: center; justify-content: space-between; gap: 24px; min-height: 88px; }
    .yl-brand { display: inline-flex; align-items: center; gap: 10px; flex-shrink: 0; font-size: 23px; letter-spacing: -.8px; }
    .yl-brand img { width: auto; max-width: 172px; height: 40px; object-fit: contain; }
    .yl-brand__mark { display: grid; place-items: center; width: 38px; height: 38px; border-radius: 8px; background: var(--primary); color: #fff; font-size: 21px; font-weight: 800; }
    .yl-nav,.yl-header__actions { display: flex; align-items: center; gap: 24px; }
    .yl-nav { font-size: 13px; font-weight: 700; }
    .yl-nav a:hover { color: var(--primary); }
    .yl-button { display: inline-flex; justify-content: center; align-items: center; gap: 14px; min-height: 48px; border-radius: 7px; background: var(--primary); color: #fff; padding: 14px 22px; font-size: 14px; font-weight: 750; text-align: center; }
    .yl-button:hover { background: var(--primary_hover); }
    .yl-button--small { min-height: 42px; padding: 11px 17px; font-size: 12px; white-space: nowrap; }
    .yl-menu { display: none; padding: 8px; }
    .yl-home { overflow: clip; }
    .yl-hero { border-bottom: 1px solid var(--border); }
    .yl-hero__grid { display: grid; grid-template-columns: minmax(0,1.4fr) minmax(0,1fr); gap: 64px; align-items: center; padding-block: 68px; }
    .yl-kicker { display: flex; align-items: center; gap: 10px; color: var(--primary); font-size: 11px; font-weight: 800; letter-spacing: .14em; text-transform: uppercase; }
    .yl-kicker::before { content: ''; width: 7px; height: 7px; border-radius: 50%; background: currentColor; }
    .yl-hero h1 { max-width: 640px; margin-top: 22px; font-size: clamp(38px,4.4vw,60px); line-height: 1.13; font-weight: 800; letter-spacing: -.055em; text-wrap: balance; }
    .yl-hero__lede { max-width: 500px; margin-top: 22px; color: var(--text_muted); font-size: 15px; line-height: 1.85; }
    .yl-search { display: flex; gap: 8px; padding: 8px; margin-top: 30px; border: 1px solid var(--border); border-radius: 10px; background: #fff; box-shadow: 0 4px 14px rgba(34,45,40,.04); }
    .yl-search__field { display: flex; align-items: center; gap: 12px; flex: 1; min-width: 0; padding-left: 10px; }
    .yl-search svg { flex-shrink: 0; color: var(--text_muted); }
    .yl-search input { min-width: 0; width: 100%; border: 0; padding: 12px 0; color: var(--text); background: transparent; font-size: 13px; }
    .yl-search input::placeholder { color: var(--text_muted); }
    .yl-search button { flex-shrink: 0; }
    .yl-popular { display: flex; flex-wrap: wrap; gap: 8px 14px; align-items: center; margin-top: 15px; font-size: 11px; color: var(--text_muted); }
    .yl-popular a { color: var(--primary); border-bottom: 1px solid var(--border); padding-bottom: 2px; }
    .yl-hero__business { display: inline-flex; align-items: center; gap: 7px; margin-top: 28px; color: var(--primary); font-size: 12px; font-weight: 750; }
    .yl-city-panel { border: 1px solid var(--border); border-radius: 14px; padding: 28px; background: #fff; box-shadow: 8px 8px 0 #edf0e8; }
    .yl-city-panel__head { display: flex; align-items: start; justify-content: space-between; gap: 12px; padding-bottom: 20px; border-bottom: 1px solid var(--border); }
    .yl-city-panel__label { color: var(--text_muted); font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .yl-city-panel h2 { margin-top: 6px; font-size: 23px; font-weight: 800; letter-spacing: -.8px; }
    .yl-city-panel__symbol { display: grid; place-items: center; width: 45px; height: 45px; border-radius: 10px; background: var(--primary_light); color: var(--primary); }
    .yl-city-row { display: flex; align-items: center; gap: 13px; padding: 17px 0; border-bottom: 1px solid var(--border); }
    .yl-city-row:last-of-type { border-bottom: 0; }
    .yl-city-row__no { font-size: 10px; font-weight: 700; color: var(--text_muted); }
    .yl-city-row__name { flex: 1; font-size: 13px; font-weight: 750; }
    .yl-city-row__count { color: var(--text_muted); font-size: 10px; }
    .yl-city-row__arrow { color: var(--primary); font-size: 18px; }
    .yl-city-row:hover .yl-city-row__name { color: var(--primary); }
    .yl-city-panel__foot { padding-top: 16px; font-size: 10px; line-height: 1.7; color: var(--text_muted); }
    .yl-section { padding-block: 52px; }
    .yl-section--white { background: #fff; border-block: 1px solid var(--border); }
    .yl-section__head { display: flex; align-items: end; justify-content: space-between; gap: 24px; margin-bottom: 25px; }
    .yl-section__label { color: var(--text_muted); font-size: 10px; font-weight: 800; letter-spacing: .12em; text-transform: uppercase; }
    .yl-section h2 { margin-top: 8px; font-size: clamp(25px,3vw,32px); line-height: 1.2; letter-spacing: -1px; font-weight: 800; }
    .yl-section__intro { margin-top: 10px; font-size: 13px; line-height: 1.8; color: var(--text_muted); }
    .yl-link { display: inline-flex; align-items: center; gap: 10px; flex-shrink: 0; color: var(--primary); font-size: 12px; font-weight: 800; padding-block: 8px; }
    .yl-link:hover { text-decoration: underline; text-underline-offset: 4px; }
    .yl-categories { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 10px; }
    .yl-category { display: flex; gap: 14px; align-items: center; padding: 19px 16px; min-width: 0; border: 1px solid var(--border); border-radius: 8px; background: #fff; }
    .yl-category:hover { border-color: var(--primary); background: var(--primary_light); }
    .yl-category__icon { display: grid; place-items: center; width: 38px; height: 38px; flex-shrink: 0; border: 1px solid var(--border); border-radius: 8px; background: var(--bg); color: var(--primary); font-size: 12px; font-weight: 750; }
    .yl-category__name { display: block; font-size: 12px; line-height: 1.5; font-weight: 750; overflow-wrap: anywhere; }
    .yl-category__count { display: block; margin-top: 3px; color: var(--text_muted); font-size: 10px; }
    .yl-firms { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 18px; }
    .yl-firm { display: flex; flex-direction: column; min-width: 0; padding: 23px; border: 1px solid var(--border); border-radius: 10px; background: #fff; }
    .yl-firm:hover { border-color: #9aac9f; box-shadow: var(--card_shadow); }
    .yl-firm__top { display: flex; justify-content: space-between; align-items: center; gap: 10px; }
    .yl-firm__logo { display: grid; place-items: center; width: 54px; height: 54px; flex-shrink: 0; overflow: hidden; border-radius: 9px; background: var(--primary_light); color: var(--primary); font-size: 23px; font-weight: 800; }
    .yl-firm__logo img { width: 100%; height: 100%; object-fit: contain; padding: 5px; background: #fff; }
    .yl-firm__badge { border-radius: 4px; background: #f4eddc; color: #785724; padding: 5px 8px; font-size: 9px; font-weight: 800; }
    .yl-firm__category { color: var(--text_muted); font-size: 10px; font-weight: 700; margin-top: 21px; }
    .yl-firm h3 { margin-top: 8px; font-size: 16px; line-height: 1.5; letter-spacing: -.4px; font-weight: 800; overflow-wrap: anywhere; }
    .yl-firm h3 a:hover { color: var(--primary); }
    .yl-firm__description { display: -webkit-box; -webkit-box-orient: vertical; -webkit-line-clamp: 2; overflow: hidden; margin-top: 11px; color: var(--text_muted); font-size: 12px; line-height: 1.8; }
    .yl-firm__bottom { display: flex; justify-content: space-between; gap: 12px; align-items: end; padding-top: 19px; margin-top: auto; }
    .yl-firm__place { display: flex; gap: 5px; align-items: center; min-width: 0; font-size: 10px; color: var(--text_muted); }
    .yl-firm__place svg { flex-shrink: 0; }
    .yl-firm__bottom .yl-link { font-size: 10px; }
    .yl-city-grid { display: grid; grid-template-columns: repeat(4,minmax(0,1fr)); gap: 12px; }
    .yl-city-tile { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 18px 20px; border-radius: 8px; border: 1px solid var(--border); background: #fff; }
    .yl-city-tile strong { font-size: 13px; font-weight: 750; }
    .yl-city-tile small { display: block; font-size: 10px; color: var(--text_muted); margin-top: 5px; }
    .yl-city-tile:hover { border-color: var(--primary); }
    .yl-city-tile > span:last-child { color: var(--primary); }
    .yl-banner { display: grid; grid-template-columns: minmax(0,1fr) auto; align-items: center; gap: 32px; padding: 36px; background: var(--primary); border-radius: 12px; color: #fff; }
    .yl-banner__label { font-size: 10px; letter-spacing: .12em; text-transform: uppercase; color: #c8dfd0; font-weight: 800; }
    .yl-banner h2 { margin-top: 10px; max-width: 560px; font-size: 28px; letter-spacing: -.8px; font-weight: 750; line-height: 1.3; }
    .yl-banner p { margin-top: 12px; font-size: 12px; line-height: 1.8; color: #d6e5dc; max-width: 540px; }
    .yl-banner .yl-button { background: #e8f0d9; color: #193c2b; }
    .yl-banner .yl-button:hover { background: #fff; }
    .yl-blog { display: grid; grid-template-columns: repeat(3,minmax(0,1fr)); gap: 26px; }
    .yl-article { display: flex; flex-direction: column; align-items: start; min-width: 0; border-top: 2px solid var(--primary); padding-top: 20px; }
    .yl-article time { font-size: 10px; color: var(--text_muted); }
    .yl-article h3 { margin-top: 13px; font-size: 17px; line-height: 1.5; font-weight: 800; letter-spacing: -.4px; }
    .yl-article h3 a:hover { color: var(--primary); }
    .yl-article p { margin-block: 12px 14px; font-size: 12px; line-height: 1.8; color: var(--text_muted); }
    .yl-article .yl-link { margin-top: auto; }
    .yl-empty { border: 1px dashed var(--border); border-radius: 8px; padding: 24px; color: var(--text_muted); font-size: 13px; line-height: 1.8; grid-column: 1/-1; }
    .yl-empty a { color: var(--primary); text-decoration: underline; }
    .theme-local-ledger body > footer { background: #edf1e9 !important; border-top: 1px solid var(--border); color: var(--text) !important; }
    .theme-local-ledger body > footer :where(p,ul,button) { color: var(--text_muted) !important; }
    .theme-local-ledger body > footer :where(h3,a,span) { color: var(--text) !important; }
    @media (max-width: 1100px) {
        .yl-nav { gap: 16px; }.yl-nav a:last-child { display: none; }
        .yl-hero__grid { gap: 36px; }.yl-categories { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (max-width: 800px) {
        .yl-nav { display: none; }.yl-menu { display: block; }
        .yl-header__actions { gap: 10px; }.yl-header__row { min-height: 72px; }
        .yl-hero__grid { grid-template-columns: minmax(0,1fr); gap: 35px; padding-block: 42px; }
        .yl-hero h1 { max-width: 540px; font-size: 46px; }
        .yl-city-panel { padding: 24px; }.yl-city-panel__cities { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); column-gap: 24px; }
        .yl-categories,.yl-firms,.yl-city-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .yl-blog { grid-template-columns: minmax(0,1fr); gap: 24px; }
        .yl-banner { grid-template-columns: minmax(0,1fr); gap: 22px; }
        .yl-banner .yl-button { justify-self: start; }
    }
    @media (max-width: 480px) {
        .yl-wrap { padding-inline: 20px; }.yl-header__row { gap: 10px; min-height: 66px; }
        .yl-brand { font-size: 20px; }.yl-brand img { max-width: 112px; height: 30px; }
        .yl-button--small { padding: 10px; min-height: 38px; font-size: 10px; gap: 8px; }
        .yl-header__actions { gap: 3px; }.yl-menu { padding: 7px; }
        .yl-hero__grid { padding-block: 34px; }.yl-hero h1 { font-size: 38px; margin-top: 18px; }
        .yl-hero__lede { font-size: 13px; margin-top: 18px; }.yl-kicker { font-size: 9px; letter-spacing: .1em; }
        .yl-search { margin-top: 24px; padding: 6px; gap: 4px; }.yl-search__field { gap: 7px; padding-left: 5px; }
        .yl-search input { font-size: 11px; }.yl-search .yl-button { min-height: 44px; padding: 10px 14px; font-size: 12px; gap: 7px; }
        .yl-popular { font-size: 10px; gap: 8px 10px; }.yl-hero__business { margin-top: 22px; font-size: 11px; }
        .yl-city-panel { padding: 20px; box-shadow: 5px 5px 0 #edf0e8; }.yl-city-panel h2 { font-size: 21px; }
        .yl-city-panel__cities { grid-template-columns: minmax(0,1fr); }.yl-city-row { padding-block: 13px; }
        .yl-city-panel__cities .yl-city-row:nth-child(n+5) { display: none; }
        .yl-section { padding-block: 36px; }.yl-section__head { align-items: start; gap: 14px; flex-direction: column; }
        .yl-section__intro { font-size: 12px; }.yl-category { padding: 15px 12px; gap: 10px; align-items: start; }
        .yl-category__icon { display: none; }.yl-category__name { font-size: 11px; }.yl-category__count { font-size: 10px; margin-top: 5px; }
        .yl-firms { grid-template-columns: minmax(0,1fr); gap: 12px; }.yl-firm { padding: 21px; }
        .yl-city-tile { padding: 16px 14px; }.yl-city-tile strong { font-size: 12px; }
        .yl-banner { padding: 25px; }.yl-banner h2 { font-size: 24px; }.yl-banner .yl-button { width: 100%; }
    }
</style>
