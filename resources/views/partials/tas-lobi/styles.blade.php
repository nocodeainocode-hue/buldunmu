<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@400;500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,500;0,600;0,700;1,500&family=Inter:wght@400;500;600;700&display=swap"></noscript>
<style>
    /* Taş Lobi · Mermer Kurumsal: taş rengi zemin, antrasit metin, bronz ince çizgiler, serif başlıklar. */
    .theme-tas-lobi {
        --tl-stone: #f7f4ee; --tl-stone-2: #efeae1; --tl-paper: #fffdf9;
        --tl-ink: #24272b; --tl-muted: #6a6761; --tl-line: #e2dcd0; --tl-line-2: #cdc4b3;
        --tl-bronze: #9a7b4f; --tl-bronze-2: #7d6239; --tl-bronze-soft: #f1e9db;
        --tl-ok: #2f6b4f; --tl-wa: #1e8a4c;
        scroll-padding-top: 88px;
    }
    .theme-tas-lobi body { background: var(--tl-stone); color: var(--tl-ink); font-family: var(--font_body); font-size: 16px; line-height: 1.65; }
    .theme-tas-lobi :where(h1,h2,h3) { font-family: var(--font_heading); font-weight: 600; }
    .theme-tas-lobi :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--tl-bronze); outline-offset: 3px; }
    .theme-tas-lobi #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .tl-wrap { width: 100%; max-width: var(--page_width, 1180px); margin-inline: auto; padding-inline: 32px; }
    .tl-home { overflow-x: clip; }
    .tl-home ul, .tl-home ol { margin: 0; padding: 0; list-style: none; }

    /* Ortak süsler: bronz çizgi, baklava */
    .tl-rule { display: inline-block; flex: none; width: 44px; height: 1px; background: var(--tl-bronze); vertical-align: middle; }
    .tl-dia { display: inline-block; flex: none; width: 6px; height: 6px; margin: 0 12px; background: var(--tl-bronze); transform: rotate(45deg); vertical-align: middle; }

    /* Düğmeler ve bağlantılar */
    .tl-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 22px; border-radius: 3px; border: 1px solid transparent; font: 600 15px/1.2 var(--font_body); letter-spacing: .02em; text-align: center; text-decoration: none; cursor: pointer; transition: background-color .16s, color .16s, border-color .16s; }
    .tl-btn--sm { min-height: 42px; padding: 0 16px; white-space: nowrap; }
    .tl-btn--lg { min-height: 54px; padding: 0 26px; font-size: 16px; }
    .tl-btn--ink { background: var(--tl-ink); color: #fff; }
    .tl-btn--ink:hover { background: #000; }
    .tl-btn--line { background: var(--tl-paper); border-color: var(--tl-line-2); color: var(--tl-ink); }
    .tl-btn--line:hover { border-color: var(--tl-bronze); color: var(--tl-bronze-2); }
    .tl-link { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; color: var(--tl-bronze-2); font-weight: 600; text-decoration: none; }
    .tl-link svg { transition: transform .16s; }
    .tl-link:hover { text-decoration: underline; text-underline-offset: 4px; }
    .tl-link:hover svg { transform: translateX(3px); }

    /* Üst şerit */
    .tl-header { position: sticky; top: 0; z-index: 50; background: rgba(247,244,238,.94); backdrop-filter: blur(10px); border-bottom: 1px solid var(--tl-line-2); }
    .tl-header::after { content: ""; position: absolute; left: 0; right: 0; top: 100%; height: 2px; background: linear-gradient(90deg, transparent, var(--tl-bronze) 30%, var(--tl-bronze) 70%, transparent); opacity: .55; }
    .tl-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 72px; }
    .tl-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--tl-ink); text-decoration: none; }
    .tl-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .tl-brand__mark { display: block; flex: none; }
    .tl-brand__mark svg { display: block; }
    .tl-brand__name { min-width: 0; font: 700 23px/1.2 var(--font_heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .tl-nav { display: flex; flex: none; align-items: center; gap: 28px; }
    .tl-nav a { position: relative; padding: 10px 0; color: var(--tl-ink); font: 500 15px/1.2 var(--font_body); letter-spacing: .02em; text-decoration: none; white-space: nowrap; }
    .tl-nav a::after { content: ""; position: absolute; left: 0; right: 0; bottom: 2px; height: 1px; background: var(--tl-bronze); transform: scaleX(0); transform-origin: left; transition: transform .18s; }
    .tl-nav a:hover::after, .tl-nav a[aria-current="page"]::after { transform: scaleX(1); }
    .tl-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .tl-cta-short { display: none; }
    .tl-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1px solid var(--tl-line-2); border-radius: 3px; background: var(--tl-paper); color: var(--tl-ink); cursor: pointer; }

    /* Lobi */
    .tl-hero { position: relative; padding: 84px 0 76px; background: radial-gradient(90% 60% at 50% 0, #fffdf9, transparent 70%), var(--tl-stone); text-align: center; }
    .tl-hero::after { content: ""; position: absolute; left: 50%; bottom: 0; width: min(1180px, calc(100% - 64px)); height: 1px; transform: translateX(-50%); background: linear-gradient(90deg, transparent, var(--tl-line-2) 20%, var(--tl-line-2) 80%, transparent); }
    .tl-eyebrow { display: flex; align-items: center; justify-content: center; gap: 16px; margin: 0 0 20px; color: var(--tl-bronze-2); font: 600 13px/1.3 var(--font_body); letter-spacing: .2em; text-transform: uppercase; }
    .tl-h1 { max-width: 20ch; margin: 0 auto 16px; font: 600 clamp(38px, 5.4vw, 68px)/1.06 var(--font_heading); letter-spacing: -.01em; color: var(--tl-ink); text-wrap: balance; overflow-wrap: break-word; }
    .tl-lede { max-width: 52ch; margin: 0 auto; color: var(--tl-muted); font-size: 18px; line-height: 1.6; }

    /* Arama levhası: bronz çift çerçeve */
    .tl-search { position: relative; display: grid; grid-template-columns: minmax(0,1.35fr) minmax(0,1fr) auto; gap: 0; max-width: 760px; margin: 34px auto 0; padding: 6px; border: 1px solid var(--tl-bronze); background: var(--tl-paper); box-shadow: 0 24px 50px -36px rgba(36,39,43,.5); text-align: left; }
    .tl-search::before { content: ""; position: absolute; inset: -6px; border: 1px solid var(--tl-line-2); pointer-events: none; }
    .tl-field { display: flex; flex-direction: column; justify-content: center; min-width: 0; min-height: 62px; padding: 8px 16px; }
    .tl-field--city { border-left: 1px solid var(--tl-line); }
    .tl-field:focus-within { box-shadow: inset 0 -2px 0 var(--tl-bronze); }
    .tl-field label { color: var(--tl-bronze-2); font: 600 12px/1.2 var(--font_body); letter-spacing: .12em; text-transform: uppercase; }
    .tl-field :where(input,select) { width: 100%; min-width: 0; height: 32px; padding: 0; border: 0; background: transparent; color: var(--tl-ink); font: 500 17px/1.2 var(--font_body); }
    .tl-field :where(input,select):focus { outline: none; }
    .tl-field input::placeholder { color: #9a958b; }
    .tl-search__btn { min-width: 128px; padding: 0 26px; border: 0; background: var(--tl-ink); color: #fff; font: 600 15.5px/1 var(--font_body); letter-spacing: .04em; cursor: pointer; transition: background-color .16s; }
    .tl-search__btn:hover { background: #000; }
    .tl-often { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 6px 0; margin: 26px 0 0; color: var(--tl-muted); font-size: 14.5px; }
    .tl-often__k { margin-right: 14px; font: italic 500 17px/1.2 var(--font_heading); color: var(--tl-bronze-2); }
    .tl-often a { color: var(--tl-ink); font-weight: 600; text-decoration: underline; text-decoration-color: var(--tl-line-2); text-underline-offset: 4px; }
    .tl-often a:hover { text-decoration-color: var(--tl-bronze); }
    .tl-counter { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 28px 0 0; color: var(--tl-muted); font: 500 15px/1.3 var(--font_body); }
    .tl-counter strong { margin-right: 6px; color: var(--tl-ink); font: 600 30px/1 var(--font_heading); font-variant-numeric: tabular-nums; }

    /* Bölümler */
    .tl-section { padding: 76px 0; }
    .tl-section--stone { background: var(--tl-stone-2); border-top: 1px solid var(--tl-line); border-bottom: 1px solid var(--tl-line); }
    .tl-section--rule { border-bottom: 1px solid var(--tl-line); }
    .tl-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 32px; }
    .tl-head--center { justify-content: center; text-align: center; }
    .tl-kicker { margin: 0 0 8px; color: var(--tl-bronze-2); font: 600 12.5px/1.3 var(--font_body); letter-spacing: .2em; text-transform: uppercase; }
    .tl-h2 { margin: 0; font: 600 clamp(30px, 3.4vw, 42px)/1.12 var(--font_heading); letter-spacing: -.01em; }
    .tl-sub { margin: 10px 0 0; color: var(--tl-muted); font-size: 16.5px; }
    .tl-after { margin: 26px 0 0; text-align: center; }
    .tl-empty-line { margin: 0; color: var(--tl-muted); font-size: 17px; text-align: center; }

    /* Levha kartı */
    .tl-plates { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; }
    .tl-plate { position: relative; display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 22px 22px 20px; border: 1px solid var(--tl-line-2); background: var(--tl-paper); box-shadow: inset 0 0 0 4px var(--tl-paper), inset 0 0 0 5px var(--tl-line); transition: border-color .18s, box-shadow .18s, transform .18s; }
    .tl-plate:hover, .tl-plate:focus-within { border-color: var(--tl-bronze); transform: translateY(-3px); box-shadow: inset 0 0 0 4px var(--tl-paper), inset 0 0 0 5px var(--tl-bronze-soft), 0 24px 44px -32px rgba(36,39,43,.5); }
    .tl-plate--premium { border-color: var(--tl-bronze); box-shadow: inset 0 0 0 4px var(--tl-paper), inset 0 0 0 5px var(--tl-bronze); }
    .tl-plate__head { display: flex; align-items: center; justify-content: space-between; gap: 10px; }
    .tl-plate__cat { color: var(--tl-bronze-2); font: 600 12px/1.3 var(--font_body); letter-spacing: .14em; text-transform: uppercase; }
    .tl-plate__seal { padding: 3px 9px; border: 1px solid var(--tl-bronze); border-radius: 2px; color: var(--tl-bronze-2); font: 600 11px/1.2 var(--font_body); letter-spacing: .1em; text-transform: uppercase; }
    .tl-plate__logo { display: block; width: fit-content; }
    .tl-plate__logo img { display: block; max-width: 120px; max-height: 44px; width: auto; height: auto; object-fit: contain; }
    .tl-plate__name { margin: 0; font: 600 25px/1.2 var(--font_heading); overflow-wrap: break-word; }
    .tl-plate__name a { color: var(--tl-ink); text-decoration: none; }
    .tl-plate__name a:hover { color: var(--tl-bronze-2); text-decoration: underline; text-underline-offset: 4px; text-decoration-thickness: 1px; }
    .tl-plate__desc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0; color: var(--tl-muted); font-size: 15px; }
    .tl-plate__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 12px; margin: 0; color: var(--tl-muted); font-size: 13.5px; }
    .tl-chip { padding: 3px 9px; border: 1px solid var(--tl-line-2); border-radius: 2px; font-weight: 600; letter-spacing: .02em; }
    .tl-chip--ok { border-color: #b9d3c4; color: var(--tl-ok); }
    .tl-chip--open { border-color: #b9d3c4; background: #eaf3ee; color: var(--tl-ok); }
    .tl-plate__rating { color: var(--tl-bronze-2); font-weight: 600; }
    .tl-plate__place { display: inline-flex; align-items: center; gap: 4px; }
    .tl-plate__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 12px; border-top: 1px solid var(--tl-line); }
    .tl-call { display: inline-flex; flex: 1; align-items: center; justify-content: center; gap: 8px; min-width: 0; min-height: 48px; padding: 0 14px; border: 1px solid var(--tl-ink); border-radius: 3px; background: var(--tl-ink); color: #fff; font: 600 16.5px/1 var(--font_body); font-variant-numeric: tabular-nums; text-decoration: none; transition: background-color .16s; }
    .tl-call span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .tl-call:hover { background: #000; }
    .tl-call--wa { border-color: var(--tl-wa); background: var(--tl-wa); }
    .tl-call--wa:hover { background: #176f3c; }
    .tl-call--line { background: var(--tl-paper); color: var(--tl-ink); }
    .tl-call--line:hover { background: var(--tl-bronze-soft); }
    .tl-wa { display: grid; flex: none; place-items: center; width: 48px; height: 48px; border: 1px solid #b9d3c4; border-radius: 3px; background: var(--tl-paper); color: var(--tl-wa); }
    .tl-wa:hover { background: var(--tl-wa); border-color: var(--tl-wa); color: #fff; }
    .tl-more { display: grid; flex: none; place-items: center; width: 44px; height: 48px; border: 1px solid var(--tl-line-2); border-radius: 3px; background: var(--tl-paper); color: var(--tl-ink); }
    .tl-more:hover { border-color: var(--tl-bronze); color: var(--tl-bronze-2); }

    /* Boş levha */
    .tl-blank, .tl-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; height: 100%; padding: 30px 24px; border: 1px dashed var(--tl-bronze); background: var(--tl-bronze-soft); text-align: center; }
    .tl-blank__tag { padding: 3px 10px; border: 1px solid var(--tl-bronze); border-radius: 2px; color: var(--tl-bronze-2); font: 600 11.5px/1.2 var(--font_body); letter-spacing: .14em; text-transform: uppercase; }
    .tl-blank h3, .tl-empty h3 { margin: 4px 0 0; font: 600 26px/1.2 var(--font_heading); }
    .tl-blank p, .tl-empty p { max-width: 42ch; margin: 0; color: var(--tl-muted); }
    .tl-blank__acts { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 8px 18px; margin-top: 6px; }

    /* Kat planı: noktalı kılavuzlu dizin */
    .tl-plan { display: grid; grid-template-columns: minmax(0,1fr); gap: 0 56px; max-width: 980px; margin: 0 auto; }
    .tl-plan li { border-bottom: 1px solid var(--tl-line-2); }
    .tl-plan__row { display: flex; align-items: baseline; gap: 12px; min-height: 60px; padding: 14px 4px; color: var(--tl-ink); text-decoration: none; }
    .tl-plan__row:hover .tl-plan__name { color: var(--tl-bronze-2); }
    .tl-plan__no { flex: none; width: 30px; color: var(--tl-bronze-2); font: italic 500 17px/1 var(--font_heading); }
    .tl-plan__name { flex: none; max-width: 70%; font: 600 23px/1.2 var(--font_heading); overflow-wrap: break-word; transition: color .16s; }
    .tl-plan__dots { flex: 1; min-width: 20px; height: 1px; border-bottom: 1px dotted var(--tl-line-2); transform: translateY(-5px); }
    .tl-plan__n { flex: none; color: var(--tl-muted); font: 500 14px/1 var(--font_body); font-variant-numeric: tabular-nums; }

    /* Şehirler */
    .tl-cities { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 12px 0; }
    .tl-cities li { display: inline-flex; align-items: center; }
    .tl-city { display: inline-flex; align-items: baseline; gap: 8px; min-height: 44px; padding: 8px 4px; color: var(--tl-ink); font: 600 22px/1.2 var(--font_heading); text-decoration: none; }
    .tl-city:hover { color: var(--tl-bronze-2); text-decoration: underline; text-underline-offset: 5px; text-decoration-thickness: 1px; }
    .tl-city small { color: var(--tl-muted); font: 500 13px/1 var(--font_body); }
    .tl-city--big { font-size: 30px; }

    /* Neden */
    .tl-why { display: grid; grid-template-columns: minmax(0,1fr); gap: 28px; }
    .tl-why__item { padding: 28px 26px; border: 1px solid var(--tl-line-2); background: var(--tl-paper); text-align: center; }
    .tl-why__ico { display: grid; place-items: center; width: 52px; height: 52px; margin: 0 auto 16px; border: 1px solid var(--tl-bronze); border-radius: 50%; color: var(--tl-bronze-2); }
    .tl-why__item h3 { margin: 0 0 8px; font: 600 24px/1.2 var(--font_heading); }
    .tl-why__item p { margin: 0; color: var(--tl-muted); font-size: 15.5px; }

    /* Blog */
    .tl-posts { display: grid; grid-template-columns: minmax(0,1fr); gap: 20px; }
    .tl-post { display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 26px 24px 18px; border-top: 2px solid var(--tl-bronze); background: var(--tl-paper); border-inline: 1px solid var(--tl-line); border-bottom: 1px solid var(--tl-line); }
    .tl-post time { color: var(--tl-bronze-2); font: italic 500 16px/1.2 var(--font_heading); }
    .tl-post h3 { margin: 0; font: 600 24px/1.25 var(--font_heading); }
    .tl-post h3 a { color: var(--tl-ink); text-decoration: none; }
    .tl-post h3 a:hover { color: var(--tl-bronze-2); text-decoration: underline; text-underline-offset: 4px; text-decoration-thickness: 1px; }
    .tl-post p { margin: 0; color: var(--tl-muted); font-size: 15.5px; }
    .tl-post .tl-link { margin-top: auto; }

    /* İş ilanları */
    .tl-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; }
    .tl-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border: 1px solid var(--tl-line-2); border-left: 3px solid var(--tl-bronze); background: var(--tl-paper); color: var(--tl-ink); text-decoration: none; }
    .tl-job:hover .tl-job__title { text-decoration: underline; }
    .tl-job__type { color: var(--tl-bronze-2); font: 600 12px/1.3 var(--font_body); letter-spacing: .12em; text-transform: uppercase; }
    .tl-job__title { font: 600 22px/1.2 var(--font_heading); }
    .tl-job__meta { color: var(--tl-muted); font-size: 14.5px; }

    /* Levhanızı asın */
    .tl-cta-sec { padding-top: 24px; }
    .tl-cta { display: grid; grid-template-columns: minmax(0,1fr); gap: 28px; align-items: center; padding: 32px 24px; border: 1px solid var(--tl-line-2); background: var(--tl-paper); box-shadow: inset 0 0 0 6px var(--tl-paper), inset 0 0 0 7px var(--tl-line); }
    .tl-cta__frame { display: flex; justify-content: center; }
    .tl-cta__plate { display: flex; flex-direction: column; align-items: center; gap: 14px; width: 100%; max-width: 340px; padding: 36px 24px; border: 1px solid var(--tl-bronze); background: var(--tl-stone); box-shadow: inset 0 0 0 5px var(--tl-stone), inset 0 0 0 6px var(--tl-line-2); transition: box-shadow .3s; }
    .tl-cta__line { width: 60px; height: 1px; background: var(--tl-bronze); }
    .tl-cta__word { color: var(--tl-line-2); font: 600 clamp(34px, 4vw, 48px)/1.05 var(--font_heading); letter-spacing: .02em; transition: color .3s; }
    .tl-cta:hover .tl-cta__word, .tl-cta:focus-within .tl-cta__word { color: var(--tl-ink); }
    .tl-cta:hover .tl-cta__plate, .tl-cta:focus-within .tl-cta__plate { box-shadow: inset 0 0 0 5px var(--tl-stone), inset 0 0 0 6px var(--tl-bronze); }
    .tl-cta__txt h2 { margin: 0 0 10px; }
    .tl-cta__txt > p { max-width: 52ch; margin: 0 0 20px; color: var(--tl-muted); font-size: 17px; }
    .tl-cta__form label { display: block; margin-bottom: 6px; color: var(--tl-ink); font: 600 14px/1.2 var(--font_body); }
    .tl-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .tl-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 16px; border: 1px solid var(--tl-line-2); border-radius: 3px; background: #fff; color: var(--tl-ink); font: 500 17px/1.2 var(--font_body); }
    .tl-cta__row input::placeholder { color: #9a958b; }
    .tl-cta__row input:focus { outline: none; border-color: var(--tl-bronze); box-shadow: 0 0 0 3px var(--tl-bronze-soft); }
    .tl-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin: 12px 0 0; }

    /* Mobil sabit çubuk (arama ekrandan çıkınca görünür) */
    .tl-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-tas-lobi body > footer { position: relative; background: #1f2225 !important; color: #d6d1c7 !important; }
    .theme-tas-lobi body > footer::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 2px; background: linear-gradient(90deg, transparent, var(--tl-bronze) 30%, var(--tl-bronze) 70%, transparent); }
    .theme-tas-lobi body > footer :where(p,ul,button) { color: #bdb7ab !important; }
    .theme-tas-lobi body > footer :where(h3,span) { color: #f7f4ee !important; }
    .theme-tas-lobi body > footer h3 { font-family: var(--font_heading); font-size: 20px; text-transform: none !important; letter-spacing: 0 !important; }
    .theme-tas-lobi body > footer a:hover { color: #d9bf93 !important; }
    .theme-tas-lobi body > footer :focus-visible { outline-color: #d9bf93; }

    /* İç sayfalar: birincil renkli başlık bantları antrasit, altında bronz çizgi. */
    .theme-tas-lobi main section[style*="background:var(--primary)"] { background: #24272b !important; box-shadow: inset 0 -2px 0 var(--tl-bronze); }
    .theme-tas-lobi main section[style*="background:var(--primary)"] :focus-visible { outline-color: #d9bf93; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-tas-lobi #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-tas-lobi #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .tl-plates { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .tl-plates > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .tl-plan { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .tl-cta { padding: 36px; }
    }
    @media (min-width: 768px) {
        .tl-why, .tl-posts, .tl-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (min-width: 1024px) {
        .tl-plates { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 24px; }
        .tl-plates > li:last-child:nth-child(odd) { grid-column: auto; }
        .tl-plates > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .tl-plates > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .tl-cta { grid-template-columns: minmax(0,5fr) minmax(0,7fr); gap: 56px; padding: 48px 56px; }
    }
    @media (min-width: 1024px) and (max-width: 1179.98px) {
        .tl-nav { gap: 18px; }
    }
    @media (max-width: 1023.98px) {
        .tl-nav { display: none; }
        .tl-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-tas-lobi #mobile-menu { display: block; z-index: 60; }
        .theme-tas-lobi #mobile-backdrop { z-index: 55; }
        .theme-tas-lobi #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 760px) {
        html.theme-tas-lobi { scroll-padding-bottom: 84px; }
        .tl-home { padding-bottom: 76px; }
        .tl-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .tl-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(247,244,238,.97); border-top: 1px solid var(--tl-line-2); box-shadow: 0 -12px 28px -18px rgba(36,39,43,.45); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .tl-dock.is-on .tl-dock__bar { transform: none; visibility: visible; }
        .tl-dock__bar .tl-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 639.98px) {
        .tl-wrap { padding-inline: 16px; }
        .tl-header__row { height: 62px; gap: 10px; }
        .tl-brand { gap: 10px; }
        .tl-brand__name { max-width: 44vw; font-size: 20px; }
        .tl-brand__mark svg { width: 32px; height: 32px; }
        .tl-btn--sm { min-height: 44px; padding: 0 14px; }
        .tl-hero { padding: 48px 0 52px; }
        .tl-hero::after { width: calc(100% - 32px); }
        .tl-eyebrow { gap: 10px; font-size: 12px; letter-spacing: .16em; }
        .tl-rule { width: 24px; }
        .tl-h1 { font-size: clamp(32px, 9vw, 40px); }
        .tl-lede { font-size: 16.5px; }
        .tl-search { grid-template-columns: minmax(0,1fr); margin: 30px 6px 0; }
        .tl-search::before { inset: -5px; }
        .tl-field { min-height: 60px; }
        .tl-field--city { border-left: 0; border-top: 1px solid var(--tl-line); }
        .tl-search__btn { min-height: 52px; }
        .tl-often { gap: 6px 0; }
        .tl-often__k { width: 100%; margin: 0 0 2px; }
        .tl-dia { margin: 0 9px; }
        .tl-counter strong { font-size: 26px; }
        .tl-section { padding: 52px 0; }
        .tl-head { flex-direction: column; align-items: flex-start; gap: 6px; margin-bottom: 24px; }
        .tl-head--center { align-items: center; }
        .tl-head .tl-link { min-height: 36px; }
        .tl-plate { padding: 18px 16px 16px; }
        .tl-plate__name { font-size: 22px; }
        .tl-plan__row { gap: 10px; min-height: 54px; padding: 12px 2px; }
        .tl-plan__name { font-size: 20px; }
        .tl-city { font-size: 20px; }
        .tl-city--big { font-size: 25px; }
        .tl-why__item { padding: 24px 18px; }
        .tl-cta { padding: 26px 18px; }
        .tl-cta__plate { padding: 26px 18px; }
        .tl-cta__row .tl-btn { width: 100%; }
        .tl-cta-sec { padding-top: 8px; }
    }
    @media (max-width: 419.98px) {
        .tl-cta-long { display: none; }
        .tl-cta-short { display: inline; }
        .tl-call { font-size: 15.5px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-tas-lobi { scroll-behavior: auto; }
        .theme-tas-lobi #mobile-menu { transition: none; }
        .tl-plate, .tl-plan__name, .tl-nav a::after, .tl-btn, .tl-call, .tl-link svg, .tl-dock__bar, .tl-cta__plate, .tl-cta__word { transition: none !important; }
        .tl-plate:hover, .tl-plate:focus-within, .tl-link:hover svg { transform: none; }
    }
</style>
