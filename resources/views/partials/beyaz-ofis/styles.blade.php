<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Manrope:wght@400;500;600;700;800&display=swap"></noscript>
<style>
    /* Beyaz Ofis · Kurumsal Vitrin: bembeyaz zemin, lacivert, ince çizgiler ve bol boşluk. */
    .theme-beyaz-ofis {
        --bo-navy: #0f2a4a; --bo-navy-2: #0a1d34; --bo-navy-soft: #e8eef6;
        --bo-ink: #16202c; --bo-muted: #5b6878; --bo-line: #e3e8ee; --bo-line-2: #c9d2dd;
        --bo-bg: #ffffff; --bo-tint: #f6f8fa; --bo-ok: #13795b; --bo-wa: #1e8a4c;
        scroll-padding-top: 88px;
    }
    .theme-beyaz-ofis body { background: var(--bo-bg); color: var(--bo-ink); font-family: var(--font_body); font-size: 16px; line-height: 1.6; }
    .theme-beyaz-ofis :where(h1,h2,h3) { font-family: var(--font_heading); letter-spacing: -.01em; }
    .theme-beyaz-ofis :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--bo-navy); outline-offset: 3px; }
    .theme-beyaz-ofis #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .bo-wrap { width: 100%; max-width: var(--page_width, 1200px); margin-inline: auto; padding-inline: 32px; }
    .bo-home { overflow-x: clip; }
    .bo-home ul, .bo-home ol { margin: 0; padding: 0; list-style: none; }

    /* Düğmeler ve bağlantılar */
    .bo-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 22px; border-radius: 8px; border: 1.5px solid transparent; font: 700 15.5px/1.2 var(--font_body); text-align: center; text-decoration: none; cursor: pointer; transition: background-color .16s, color .16s, border-color .16s; }
    .bo-btn--sm { min-height: 42px; padding: 0 16px; white-space: nowrap; }
    .bo-btn--lg { min-height: 54px; padding: 0 26px; font-size: 16.5px; }
    .bo-btn--navy { background: var(--bo-navy); color: #fff; }
    .bo-btn--navy:hover { background: var(--bo-navy-2); }
    .bo-btn--line { background: #fff; border-color: var(--bo-line-2); color: var(--bo-navy); }
    .bo-btn--line:hover { border-color: var(--bo-navy); }
    .bo-btn--white { background: #fff; color: var(--bo-navy); }
    .bo-btn--white:hover { background: var(--bo-navy-soft); }
    .bo-link { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; color: var(--bo-navy); font-weight: 700; text-decoration: none; }
    .bo-link svg { transition: transform .16s; }
    .bo-link:hover { text-decoration: underline; text-underline-offset: 4px; }
    .bo-link:hover svg { transform: translateX(3px); }

    /* Üst şerit */
    .bo-header { position: sticky; top: 0; z-index: 50; background: rgba(255,255,255,.94); backdrop-filter: blur(10px); border-bottom: 1px solid var(--bo-line); }
    .bo-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 70px; }
    .bo-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--bo-ink); text-decoration: none; }
    .bo-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .bo-brand__mark { display: block; flex: none; }
    .bo-brand__mark svg { display: block; }
    .bo-brand__name { min-width: 0; font: 800 20px/1.2 var(--font_heading); letter-spacing: -.01em; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .bo-nav { display: flex; flex: none; align-items: center; gap: 28px; }
    .bo-nav a { position: relative; padding: 10px 0; color: var(--bo-ink); font: 600 15px/1.2 var(--font_body); text-decoration: none; white-space: nowrap; }
    .bo-nav a::after { content: ""; position: absolute; left: 0; right: 0; bottom: 2px; height: 2px; background: var(--bo-navy); transform: scaleX(0); transform-origin: left; transition: transform .18s; }
    .bo-nav a:hover::after, .bo-nav a[aria-current="page"]::after { transform: scaleX(1); }
    .bo-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .bo-cta-short { display: none; }
    .bo-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1.5px solid var(--bo-line-2); border-radius: 8px; background: #fff; color: var(--bo-navy); cursor: pointer; }

    /* Üst bölüm */
    .bo-hero { position: relative; padding: 72px 0 64px; background: #fff; background-image: linear-gradient(var(--bo-line) 1px, transparent 1px), linear-gradient(90deg, var(--bo-line) 1px, transparent 1px); background-size: 48px 48px; background-position: center top; }
    .bo-hero::before { content: ""; position: absolute; inset: 0; background: radial-gradient(80% 70% at 50% 40%, rgba(255,255,255,.2), #fff 90%); pointer-events: none; }
    .bo-hero__grid { position: relative; display: grid; grid-template-columns: minmax(0,1fr); gap: 36px; align-items: start; }
    .bo-eyebrow { display: inline-flex; align-items: center; gap: 10px; margin: 0 0 18px; color: var(--bo-navy); font: 700 13px/1.3 var(--font_body); letter-spacing: .14em; text-transform: uppercase; }
    .bo-eyebrow::before { content: ""; width: 28px; height: 2px; background: var(--bo-navy); }
    .bo-h1 { max-width: 16ch; margin: 0 0 16px; font: 800 clamp(36px, 4.6vw, 60px)/1.06 var(--font_heading); letter-spacing: -.025em; color: var(--bo-ink); text-wrap: balance; overflow-wrap: break-word; }
    .bo-lede { max-width: 50ch; margin: 0; color: var(--bo-muted); font-size: 18px; line-height: 1.55; }

    /* Arama */
    .bo-search { display: grid; grid-template-columns: minmax(0,1.35fr) minmax(0,1fr) auto; gap: 0; max-width: 720px; margin: 30px 0 0; border: 1.5px solid var(--bo-line-2); border-radius: 12px; background: #fff; box-shadow: 0 18px 40px -28px rgba(15,42,74,.35); overflow: hidden; }
    .bo-field { display: flex; flex-direction: column; justify-content: center; min-width: 0; min-height: 64px; padding: 8px 16px; }
    .bo-field--city { border-left: 1px solid var(--bo-line); }
    .bo-field:focus-within { box-shadow: inset 0 -3px 0 var(--bo-navy); }
    .bo-field label { color: var(--bo-muted); font: 700 12px/1.2 var(--font_body); letter-spacing: .06em; text-transform: uppercase; }
    .bo-field :where(input,select) { width: 100%; min-width: 0; height: 32px; padding: 0; border: 0; background: transparent; color: var(--bo-ink); font: 600 17px/1.2 var(--font_body); }
    .bo-field :where(input,select):focus { outline: none; }
    .bo-field input::placeholder { color: #8e99a8; }
    .bo-search__btn { min-width: 128px; padding: 0 26px; border: 0; background: var(--bo-navy); color: #fff; font: 700 16px/1 var(--font_body); cursor: pointer; transition: background-color .16s; }
    .bo-search__btn:hover { background: var(--bo-navy-2); }
    .bo-often { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 14px; margin: 18px 0 0; color: var(--bo-muted); font-size: 14.5px; }
    .bo-often__k { font-weight: 700; }
    .bo-often a { color: var(--bo-navy); font-weight: 600; text-decoration: underline; text-decoration-color: var(--bo-line-2); text-underline-offset: 4px; }
    .bo-often a:hover { text-decoration-color: var(--bo-navy); }

    /* Hizmet dizini */
    .bo-index { padding: 26px 28px 24px; border: 1px solid var(--bo-line); border-radius: 14px; background: #fff; box-shadow: 0 24px 50px -36px rgba(15,42,74,.4); }
    .bo-index__title { margin: 0 0 14px; padding-bottom: 12px; border-bottom: 1.5px solid var(--bo-navy); color: var(--bo-navy); font: 700 13px/1.3 var(--font_body); letter-spacing: .14em; text-transform: uppercase; }
    .bo-index__list li + li { border-top: 1px solid var(--bo-line); }
    .bo-index__list a { display: flex; align-items: center; gap: 14px; min-height: 48px; color: var(--bo-ink); text-decoration: none; }
    .bo-index__list a:hover .bo-index__name { color: var(--bo-navy); text-decoration: underline; text-underline-offset: 4px; }
    .bo-index__no { flex: none; width: 28px; color: var(--bo-muted); font: 700 13px/1 var(--font_body); letter-spacing: .04em; }
    .bo-index__name { flex: 1; min-width: 0; font-weight: 600; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .bo-index__n { flex: none; color: var(--bo-muted); font: 600 13.5px/1 var(--font_body); font-variant-numeric: tabular-nums; }
    .bo-index__all { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; margin-top: 8px; color: var(--bo-navy); font-weight: 700; text-decoration: none; }
    .bo-index__all:hover { text-decoration: underline; text-underline-offset: 4px; }
    .bo-index__empty { margin: 0; color: var(--bo-muted); font-size: 15px; }
    .bo-index__total { margin: 14px 0 0; padding-top: 14px; border-top: 1px solid var(--bo-line); color: var(--bo-muted); font-size: 14.5px; }
    .bo-index__total strong { color: var(--bo-ink); font-size: 20px; font-weight: 800; font-variant-numeric: tabular-nums; }

    /* Bölümler */
    .bo-section { padding: 72px 0; }
    .bo-section--tint { background: var(--bo-tint); border-top: 1px solid var(--bo-line); border-bottom: 1px solid var(--bo-line); }
    .bo-section--rule { border-bottom: 1px solid var(--bo-line); }
    .bo-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 30px; }
    .bo-kicker { margin: 0 0 8px; color: var(--bo-navy); font: 700 12.5px/1.3 var(--font_body); letter-spacing: .14em; text-transform: uppercase; }
    .bo-kicker--light { color: #b9c8dc; }
    .bo-h2 { margin: 0; font: 800 clamp(26px, 3vw, 34px)/1.15 var(--font_heading); letter-spacing: -.02em; }
    .bo-empty-line { margin: 0; color: var(--bo-muted); font-size: 17px; }

    /* Firma kartı */
    .bo-cards { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; }
    .bo-card { display: flex; flex-direction: column; gap: 10px; height: 100%; padding: 22px 22px 20px; border: 1px solid var(--bo-line); border-radius: 14px; background: #fff; transition: border-color .18s, box-shadow .18s, transform .18s; }
    .bo-card:hover, .bo-card:focus-within { border-color: var(--bo-line-2); box-shadow: 0 22px 44px -30px rgba(15,42,74,.45); transform: translateY(-3px); }
    .bo-card--premium { border-color: var(--bo-navy); box-shadow: inset 0 3px 0 var(--bo-navy); }
    .bo-card__top { display: flex; align-items: flex-start; gap: 14px; }
    .bo-card__avatar { display: grid; flex: none; place-items: center; width: 56px; height: 56px; border: 1px solid var(--bo-line); border-radius: 12px; background: var(--bo-navy-soft); color: var(--bo-navy); font: 800 22px/1 var(--font_heading); text-decoration: none; overflow: hidden; }
    .bo-card__avatar img { width: 100%; height: 100%; object-fit: contain; padding: 6px; background: #fff; }
    .bo-card__head { flex: 1; min-width: 0; }
    .bo-card__cat { display: block; color: var(--bo-muted); font: 700 12.5px/1.3 var(--font_body); letter-spacing: .06em; text-transform: uppercase; }
    .bo-card__name { margin: 2px 0 0; font: 800 19px/1.25 var(--font_heading); letter-spacing: -.01em; overflow-wrap: break-word; }
    .bo-card__name a { color: var(--bo-ink); text-decoration: none; }
    .bo-card__name a:hover { color: var(--bo-navy); text-decoration: underline; text-underline-offset: 4px; }
    .bo-card__seal { flex: none; padding: 4px 9px; border-radius: 6px; background: var(--bo-navy); color: #fff; font: 700 11.5px/1.2 var(--font_body); letter-spacing: .06em; text-transform: uppercase; }
    .bo-card__desc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0; color: var(--bo-muted); font-size: 15px; }
    .bo-card__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; margin: 0; color: var(--bo-muted); font-size: 13.5px; }
    .bo-chip { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border: 1px solid var(--bo-line-2); border-radius: 999px; font-weight: 700; }
    .bo-chip--ok { border-color: #bfe3d4; color: var(--bo-ok); }
    .bo-chip--open { border-color: #bfe3d4; background: #edf8f3; color: var(--bo-ok); }
    .bo-card__rating { color: var(--bo-ink); font-weight: 700; }
    .bo-card__place { display: inline-flex; align-items: center; gap: 4px; }
    .bo-card__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 10px; border-top: 1px solid var(--bo-line); }
    .bo-call { display: inline-flex; flex: 1; align-items: center; justify-content: center; gap: 8px; min-width: 0; min-height: 48px; padding: 0 14px; border: 1.5px solid var(--bo-navy); border-radius: 8px; background: var(--bo-navy); color: #fff; font: 700 16.5px/1 var(--font_body); font-variant-numeric: tabular-nums; text-decoration: none; transition: background-color .16s; }
    .bo-call span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .bo-call:hover { background: var(--bo-navy-2); }
    .bo-call--wa { border-color: var(--bo-wa); background: var(--bo-wa); }
    .bo-call--wa:hover { background: #176f3c; }
    .bo-call--line { background: #fff; color: var(--bo-navy); }
    .bo-call--line:hover { background: var(--bo-navy-soft); }
    .bo-wa { display: grid; flex: none; place-items: center; width: 48px; height: 48px; border: 1.5px solid #bfe3d4; border-radius: 8px; background: #fff; color: var(--bo-wa); }
    .bo-wa:hover { background: var(--bo-wa); border-color: var(--bo-wa); color: #fff; }
    .bo-more { display: grid; flex: none; place-items: center; width: 44px; height: 48px; border: 1.5px solid var(--bo-line-2); border-radius: 8px; background: #fff; color: var(--bo-navy); }
    .bo-more:hover { border-color: var(--bo-navy); }

    /* Boş yer */
    .bo-slot, .bo-empty { display: flex; flex-direction: column; align-items: flex-start; gap: 10px; height: 100%; padding: 26px 24px; border: 1.5px dashed var(--bo-line-2); border-radius: 14px; background: var(--bo-tint); }
    .bo-slot__tag { padding: 4px 10px; border-radius: 6px; background: var(--bo-navy-soft); color: var(--bo-navy); font: 700 12px/1.2 var(--font_body); letter-spacing: .08em; text-transform: uppercase; }
    .bo-slot h3, .bo-empty h3 { margin: 4px 0 0; font: 800 22px/1.2 var(--font_heading); letter-spacing: -.01em; }
    .bo-slot p, .bo-empty p { max-width: 44ch; margin: 0; color: var(--bo-muted); }
    .bo-slot__acts { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 18px; margin-top: 6px; }

    /* Hizmet alanları */
    .bo-services { display: grid; grid-template-columns: minmax(0,1fr); border-top: 1px solid var(--bo-line-2); }
    .bo-services li { border-bottom: 1px solid var(--bo-line-2); }
    .bo-service { display: flex; align-items: center; gap: 16px; min-height: 64px; padding: 10px 6px; color: var(--bo-ink); text-decoration: none; transition: background-color .16s, padding-left .16s; }
    .bo-service:hover { background: #fff; padding-left: 12px; }
    .bo-service:hover .bo-service__name { color: var(--bo-navy); }
    .bo-service:hover .bo-service__arrow { transform: translateX(4px); opacity: 1; }
    .bo-service__no { flex: none; width: 30px; color: var(--bo-muted); font: 700 13px/1 var(--font_body); letter-spacing: .04em; }
    .bo-service__name { flex: 1; min-width: 0; font: 700 19px/1.25 var(--font_heading); letter-spacing: -.01em; overflow-wrap: break-word; }
    .bo-service__n { flex: none; color: var(--bo-muted); font: 600 14px/1 var(--font_body); font-variant-numeric: tabular-nums; }
    .bo-service__arrow { flex: none; color: var(--bo-navy); opacity: .45; transition: transform .16s, opacity .16s; }

    /* Şehirler */
    .bo-cities { display: flex; flex-wrap: wrap; gap: 10px; }
    .bo-city { display: inline-flex; align-items: baseline; gap: 8px; min-height: 46px; padding: 10px 18px; border: 1px solid var(--bo-line-2); border-radius: 999px; background: #fff; color: var(--bo-ink); font: 700 16px/1.2 var(--font_body); text-decoration: none; transition: border-color .16s, background-color .16s; }
    .bo-city:hover { border-color: var(--bo-navy); background: var(--bo-navy-soft); }
    .bo-city small { color: var(--bo-muted); font-size: 13px; font-weight: 600; }

    /* Neden */
    .bo-why { display: grid; grid-template-columns: minmax(0,1fr); gap: 24px; }
    .bo-why__item { padding-top: 20px; border-top: 2px solid var(--bo-navy); }
    .bo-why__ico { display: grid; place-items: center; width: 44px; height: 44px; margin-bottom: 14px; border-radius: 10px; background: var(--bo-navy-soft); color: var(--bo-navy); }
    .bo-why__item h3 { margin: 0 0 6px; font: 800 20px/1.25 var(--font_heading); letter-spacing: -.01em; }
    .bo-why__item p { margin: 0; color: var(--bo-muted); font-size: 15.5px; }

    /* Blog */
    .bo-posts { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; }
    .bo-post { display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 24px 22px 18px; border: 1px solid var(--bo-line); border-radius: 14px; background: #fff; }
    .bo-post time { color: var(--bo-muted); font-size: 13.5px; font-weight: 700; }
    .bo-post h3 { margin: 0; font: 800 20px/1.3 var(--font_heading); letter-spacing: -.01em; }
    .bo-post h3 a { color: var(--bo-ink); text-decoration: none; }
    .bo-post h3 a:hover { color: var(--bo-navy); text-decoration: underline; text-underline-offset: 4px; }
    .bo-post p { margin: 0; color: var(--bo-muted); font-size: 15.5px; }
    .bo-post .bo-link { margin-top: auto; }

    /* İş ilanları */
    .bo-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; }
    .bo-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border: 1px solid var(--bo-line); border-left: 4px solid var(--bo-navy); border-radius: 10px; background: #fff; color: var(--bo-ink); text-decoration: none; }
    .bo-job:hover .bo-job__title { text-decoration: underline; }
    .bo-job__type { color: var(--bo-navy); font: 700 12.5px/1.3 var(--font_body); letter-spacing: .06em; text-transform: uppercase; }
    .bo-job__title { font: 800 19px/1.25 var(--font_heading); }
    .bo-job__meta { color: var(--bo-muted); font-size: 14.5px; }

    /* Kayıt çağrısı */
    .bo-cta-sec { padding-top: 24px; }
    .bo-cta { display: grid; grid-template-columns: minmax(0,1fr); gap: 28px; align-items: center; padding: 40px 32px; border-radius: 18px; background: var(--bo-navy); color: #fff; }
    .bo-cta__txt h2 { margin: 0 0 10px; font: 800 clamp(28px, 3.2vw, 38px)/1.12 var(--font_heading); letter-spacing: -.02em; color: #fff; }
    .bo-cta__txt > p { max-width: 50ch; margin: 0; color: #c9d6e6; font-size: 17px; }
    .bo-cta__form label { display: block; margin-bottom: 6px; color: #fff; font: 700 14px/1.2 var(--font_body); }
    .bo-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .bo-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 16px; border: 1.5px solid rgba(255,255,255,.25); border-radius: 8px; background: rgba(255,255,255,.08); color: #fff; font: 600 17px/1.2 var(--font_body); }
    .bo-cta__row input::placeholder { color: #a9bad0; }
    .bo-cta__row input:focus { outline: none; border-color: #fff; background: rgba(255,255,255,.12); }
    .bo-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin: 14px 0 0; }
    .bo-cta__links a { display: inline-flex; align-items: center; min-height: 40px; color: #c9d6e6; font-weight: 700; text-decoration: underline; text-underline-offset: 4px; }
    .bo-cta__links a:hover { color: #fff; }

    /* Mobil sabit çubuk (arama ekrandan çıkınca görünür) */
    .bo-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-beyaz-ofis body > footer { background: #0a1d34 !important; color: #c9d6e6 !important; }
    .theme-beyaz-ofis body > footer :where(p,ul,button) { color: #b4c3d6 !important; }
    .theme-beyaz-ofis body > footer :where(h3,span) { color: #fff !important; }
    .theme-beyaz-ofis body > footer h3 { font-family: var(--font_heading); font-size: 16px; text-transform: none !important; letter-spacing: 0 !important; }
    .theme-beyaz-ofis body > footer a:hover { color: #fff !important; }
    .theme-beyaz-ofis body > footer :focus-visible { outline-color: #fff; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-beyaz-ofis #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-beyaz-ofis #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .bo-cards { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .bo-cards > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .bo-services { grid-template-columns: repeat(2,minmax(0,1fr)); column-gap: 40px; }
    }
    @media (min-width: 768px) {
        .bo-why, .bo-posts, .bo-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (min-width: 1024px) {
        .bo-hero__grid { grid-template-columns: minmax(0,7fr) minmax(0,4fr); gap: 56px; align-items: center; }
        .bo-cards { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 24px; }
        .bo-cards > li:last-child:nth-child(odd) { grid-column: auto; }
        .bo-cards > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .bo-cards > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .bo-services { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .bo-cta { grid-template-columns: minmax(0,5fr) minmax(0,6fr); gap: 56px; padding: 52px 56px; }
    }
    @media (min-width: 1024px) and (max-width: 1179.98px) {
        .bo-nav { gap: 18px; }
    }
    @media (max-width: 1023.98px) {
        .bo-nav { display: none; }
        .bo-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-beyaz-ofis #mobile-menu { display: block; z-index: 60; }
        .theme-beyaz-ofis #mobile-backdrop { z-index: 55; }
        .theme-beyaz-ofis #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 760px) {
        html.theme-beyaz-ofis { scroll-padding-bottom: 84px; }
        .bo-home { padding-bottom: 76px; }
        .bo-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .bo-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(255,255,255,.97); border-top: 1px solid var(--bo-line); box-shadow: 0 -12px 28px -18px rgba(15,42,74,.4); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .bo-dock.is-on .bo-dock__bar { transform: none; visibility: visible; }
        .bo-dock__bar .bo-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 639.98px) {
        .bo-wrap { padding-inline: 16px; }
        .bo-header__row { height: 62px; gap: 10px; }
        .bo-brand { gap: 10px; }
        .bo-brand__name { max-width: 44vw; font-size: 18px; }
        .bo-brand__mark svg { width: 32px; height: 32px; }
        .bo-btn--sm { min-height: 44px; padding: 0 14px; }
        .bo-hero { padding: 44px 0 48px; background-size: 36px 36px; }
        .bo-h1 { font-size: clamp(30px, 8.4vw, 38px); }
        .bo-lede { font-size: 16.5px; }
        .bo-search { grid-template-columns: minmax(0,1fr); margin-top: 24px; }
        .bo-field { min-height: 60px; }
        .bo-field--city { border-left: 0; border-top: 1px solid var(--bo-line); }
        .bo-search__btn { min-height: 52px; }
        .bo-index { padding: 20px 18px 18px; }
        .bo-section { padding: 52px 0; }
        .bo-head { flex-direction: column; align-items: flex-start; gap: 6px; margin-bottom: 24px; }
        .bo-head .bo-link { min-height: 36px; }
        .bo-card { padding: 18px 16px 16px; }
        .bo-service__name { font-size: 17px; }
        .bo-city { min-height: 44px; padding: 8px 14px; font-size: 15px; }
        .bo-cta { padding: 30px 20px; }
        .bo-cta__row .bo-btn { width: 100%; }
        .bo-cta-sec { padding-top: 8px; }
    }
    @media (max-width: 419.98px) {
        .bo-cta-long { display: none; }
        .bo-cta-short { display: inline; }
        .bo-call { font-size: 15.5px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-beyaz-ofis { scroll-behavior: auto; }
        .theme-beyaz-ofis #mobile-menu { transition: none; }
        .bo-card, .bo-service, .bo-service__arrow, .bo-city, .bo-nav a::after, .bo-btn, .bo-call, .bo-link svg, .bo-dock__bar { transition: none !important; }
        .bo-card:hover, .bo-card:focus-within, .bo-link:hover svg, .bo-service:hover .bo-service__arrow { transform: none; }
    }
</style>
