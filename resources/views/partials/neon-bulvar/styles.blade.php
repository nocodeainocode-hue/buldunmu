<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Righteous&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Outfit:wght@400;600;700;800&family=Righteous&display=swap"></noscript>
<style>
    /* Neon Bulvar · Gece Vitrini: gece göğü, üç katmanlı şehir silueti, neon tüp yazılar ve ışıklı vitrinler. Görsel dosyası yok; her şey CSS ve satır içi SVG. */
    .theme-neon-bulvar {
        --nb-night: #070a1c; --nb-night-2: #0c1030; --nb-panel: #10142b; --nb-panel-2: #161b3a; --nb-line: #262c4d; --nb-line-2: #3a4170;
        --nb-ink: #eef0ff; --nb-muted: #a3a9d1;
        --nb-pink: #ff4fae; --nb-cyan: #22e3ff; --nb-yellow: #ffe14d; --nb-lime: #8dff5a; --nb-orange: #ff8a3d; --nb-violet: #b26bff; --nb-wa: #25d366;
        scroll-padding-top: 90px;
    }
    .theme-neon-bulvar body { background: var(--nb-night); color: var(--nb-ink); font-family: var(--font_body); font-size: 16px; line-height: 1.6; }
    .theme-neon-bulvar :where(h1,h2,h3) { font-family: var(--font_heading); font-weight: 400; }
    .theme-neon-bulvar :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--nb-yellow); outline-offset: 3px; }
    .theme-neon-bulvar #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .nb-wrap { width: 100%; max-width: var(--page_width, 1240px); margin-inline: auto; padding-inline: 32px; }
    .nb-home { overflow-x: clip; --tone: var(--nb-pink); }
    .nb-home ul { margin: 0; padding: 0; list-style: none; }

    /* Neon tüp yazı: beyaz çekirdek, renkli ışıma. --c tüpün rengi. */
    .nb-neon { --c: var(--nb-pink); color: #fff; text-shadow: 0 0 2px #fff, 0 0 8px var(--c), 0 0 18px var(--c), 0 0 38px var(--c); }
    .nb-neon--pink { --c: var(--nb-pink); }
    .nb-neon--cyan { --c: var(--nb-cyan); }
    .nb-neon--yellow { --c: var(--nb-yellow); }
    .nb-flicker { animation: nb-flicker 9s linear infinite; }
    @keyframes nb-flicker { 0%, 18%, 20%, 22.5%, 64%, 66%, 100% { opacity: 1; } 19%, 21% { opacity: .45; } 65% { opacity: .7; } }

    /* Ton: her meslek tüm sayfada aynı neon renkte yanar (crc32(slug) % 6). */
    .nb-c0 { --tone: var(--nb-pink); } .nb-c1 { --tone: var(--nb-cyan); } .nb-c2 { --tone: var(--nb-yellow); }
    .nb-c3 { --tone: var(--nb-lime); } .nb-c4 { --tone: var(--nb-orange); } .nb-c5 { --tone: var(--nb-violet); }

    /* Düğmeler ve bağlantılar */
    .nb-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 22px; border-radius: 10px; border: 2px solid transparent; font: 800 16px/1.2 var(--font_body); text-align: center; text-decoration: none; cursor: pointer; transition: background-color .16s, color .16s, border-color .16s, box-shadow .16s; }
    .nb-btn--sm { min-height: 42px; padding: 0 16px; white-space: nowrap; }
    .nb-btn--lg { min-height: 54px; padding: 0 26px; font-size: 17px; }
    .nb-btn--yellow { background: var(--nb-yellow); color: #140f00; box-shadow: 0 0 14px rgba(255,225,77,.45), 0 0 34px rgba(255,225,77,.18); }
    .nb-btn--yellow:hover { background: #fff07a; box-shadow: 0 0 18px rgba(255,225,77,.7), 0 0 44px rgba(255,225,77,.3); }
    .nb-btn--line { background: transparent; border-color: var(--nb-cyan); color: var(--nb-cyan); }
    .nb-btn--line:hover { background: var(--nb-cyan); color: var(--nb-night); }
    .nb-link { display: inline-flex; align-items: center; gap: 6px; min-height: 44px; color: var(--nb-cyan); font-weight: 700; text-decoration: underline; text-decoration-thickness: 1px; text-underline-offset: 4px; }
    .nb-link:hover { color: #fff; text-decoration-thickness: 2px; }

    /* Üst şerit */
    .nb-header { position: sticky; top: 0; z-index: 50; background: rgba(7,10,28,.9); backdrop-filter: blur(10px); border-bottom: 1px solid var(--nb-line); box-shadow: 0 1px 0 rgba(34,227,255,.3), 0 0 26px -6px rgba(34,227,255,.4); }
    .nb-header__row { display: flex; align-items: center; justify-content: space-between; gap: 18px; height: 70px; }
    .nb-brand { display: inline-flex; align-items: center; gap: 12px; min-width: 0; min-height: 44px; flex-shrink: 1; color: var(--nb-ink); text-decoration: none; }
    .nb-brand img { display: block; width: auto; max-width: 170px; height: 40px; object-fit: contain; }
    .nb-brand__mark { display: block; flex: none; }
    .nb-brand__mark svg { display: block; filter: drop-shadow(0 0 6px rgba(255,79,174,.75)); }
    .nb-brand__name { min-width: 0; font: 400 22px/1.2 var(--font_heading); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .nb-nav { display: flex; flex: none; align-items: center; gap: 26px; }
    .nb-nav a { position: relative; padding: 10px 0; color: var(--nb-ink); font: 600 16px/1.2 var(--font_body); text-decoration: none; white-space: nowrap; }
    .nb-nav a::after { content: ""; position: absolute; left: 0; right: 0; bottom: 2px; height: 2px; border-radius: 2px; background: var(--nb-cyan); box-shadow: 0 0 8px var(--nb-cyan); transform: scaleX(0); transition: transform .18s; }
    .nb-nav a:hover::after, .nb-nav a[aria-current="page"]::after { transform: scaleX(1); }
    .nb-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; }
    .nb-cta-short { display: none; }
    .nb-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1.5px solid var(--nb-cyan); border-radius: 8px; background: transparent; color: var(--nb-cyan); cursor: pointer; }

    /* Gece göğü ve siluet */
    .nb-hero { position: relative; isolation: isolate; padding: 84px 0 210px; overflow: hidden; background: radial-gradient(70% 40% at 50% 100%, rgba(255,79,174,.26), transparent 70%), linear-gradient(180deg, #04051a 0%, #0b0e2e 55%, #191549 100%); text-align: center; }
    .nb-sky { position: absolute; inset: 0; z-index: -1; pointer-events: none; }
    .nb-sky::before { content: ""; position: absolute; inset: 0 0 35% 0; background:
        radial-gradient(1.6px 1.6px at 12px 18px, rgba(255,255,255,.9), transparent 70%) 0 0 / 190px 130px,
        radial-gradient(1px 1px at 90px 70px, rgba(255,255,255,.75), transparent 70%) 0 0 / 230px 170px,
        radial-gradient(1.3px 1.3px at 150px 30px, rgba(255,255,255,.6), transparent 70%) 0 0 / 270px 210px,
        radial-gradient(1px 1px at 40px 110px, rgba(180,230,255,.7), transparent 70%) 0 0 / 310px 240px; }
    .nb-moon { position: absolute; top: 46px; right: 12%; width: 58px; height: 58px; border-radius: 50%; background: radial-gradient(circle at 38% 36%, #fffbe8, #f3e4a8 70%, #d9c88a); box-shadow: 0 0 24px rgba(255,246,213,.55), 0 0 70px rgba(255,246,213,.25); }
    .nb-sky__layer { position: absolute; left: 0; right: 0; bottom: 40px; width: 100%; overflow: visible; }
    .nb-sky__far { height: 52%; color: #1b2152; opacity: .85; }
    .nb-sky__mid { height: 42%; color: #0f1336; }
    .nb-sky__near { height: 30%; color: #07091f; }
    .nb-bld { fill: currentColor; }
    .nb-win-on { fill: #fff2b0; animation: nb-twinkle 3.4s ease-in-out infinite; }
    .nb-win-on--1 { animation-delay: 1.1s; } .nb-win-on--2 { animation-delay: 2.3s; }
    @keyframes nb-twinkle { 50% { opacity: .1; } }
    .nb-beacon { fill: #ff5a5a; animation: nb-beacon 1.8s steps(2, end) infinite; }
    @keyframes nb-beacon { 50% { opacity: .15; } }
    .nb-road { position: absolute; left: 0; right: 0; bottom: 0; height: 40px; background: linear-gradient(180deg, #0b0d20, #04050f); }
    .nb-road::before { content: ""; position: absolute; left: 0; right: 0; top: 0; height: 14px; background: linear-gradient(180deg, rgba(255,79,174,.3), transparent); }
    .nb-road__line { position: absolute; left: -56px; right: 0; top: 50%; height: 3px; background: repeating-linear-gradient(90deg, var(--nb-yellow) 0 26px, transparent 26px 56px); opacity: .75; animation: nb-drive 1.6s linear infinite; }
    @keyframes nb-drive { to { transform: translateX(56px); } }

    .nb-hero__in { position: relative; z-index: 1; }
    .nb-eyebrow { display: flex; align-items: center; justify-content: center; gap: 14px; margin: 0 0 16px; color: var(--nb-cyan); font: 600 15px/1.3 var(--font_body); letter-spacing: .18em; text-transform: uppercase; }
    .nb-tube { display: inline-block; flex: none; width: 46px; height: 3px; border-radius: 3px; background: var(--nb-cyan); box-shadow: 0 0 8px var(--nb-cyan), 0 0 18px var(--nb-cyan); }
    .nb-h1 { max-width: 18ch; margin: 0 auto 16px; font-family: var(--font_heading); font-weight: 400; font-size: clamp(36px, 5.2vw, 66px); line-height: 1.08; text-wrap: balance; overflow-wrap: break-word; }
    .nb-lede { max-width: 52ch; margin: 0 auto; color: var(--nb-muted); font-size: 18px; line-height: 1.55; }

    /* Vitrin camı: arama kutusu */
    .nb-search { display: grid; grid-template-columns: minmax(0,1.35fr) minmax(0,1fr) auto; gap: 6px; max-width: 760px; margin: 30px auto 0; padding: 6px; border-radius: 16px; border: 2px solid var(--nb-cyan); background: rgba(14,18,43,.88); box-shadow: inset 0 0 0 1px rgba(34,227,255,.25), 0 0 18px rgba(34,227,255,.45), 0 0 56px rgba(34,227,255,.22), 0 30px 60px -30px #000; backdrop-filter: blur(6px); text-align: left; }
    .nb-field { display: flex; flex-direction: column; justify-content: center; min-width: 0; min-height: 62px; padding: 6px 14px; border-radius: 10px; background: rgba(7,10,28,.7); }
    .nb-field:focus-within { box-shadow: inset 0 0 0 2px var(--nb-cyan); }
    .nb-field label { color: var(--nb-cyan); font: 700 12.5px/1.2 var(--font_body); letter-spacing: .04em; text-transform: uppercase; }
    .nb-field :where(input,select) { width: 100%; min-width: 0; height: 32px; padding: 0; border: 0; background: transparent; color: var(--nb-ink); font: 600 17px/1.2 var(--font_body); color-scheme: dark; }
    .nb-field :where(input,select):focus { outline: none; }
    .nb-field input::placeholder { color: #7f86b3; }
    .nb-field option { background: var(--nb-panel); color: var(--nb-ink); }
    .nb-search__btn { min-width: 112px; padding: 0 30px; border: 0; border-radius: 10px; background: var(--nb-yellow); color: #140f00; font: 800 19px/1 var(--font_body); cursor: pointer; box-shadow: 0 0 16px rgba(255,225,77,.5); transition: background-color .16s; }
    .nb-search__btn:hover { background: #fff07a; }
    .nb-often { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 8px 10px; margin: 22px 0 0; color: var(--nb-muted); font-size: 14.5px; }
    .nb-often__k { font-weight: 700; }
    .nb-often__a { display: inline-flex; align-items: center; min-height: 36px; padding: 0 14px; border: 1px solid var(--tone); border-radius: 999px; color: var(--tone); font-weight: 700; text-decoration: none; box-shadow: 0 0 10px -3px var(--tone); }
    .nb-often__a:hover { background: var(--tone); color: var(--nb-night); }
    .nb-counter { margin: 26px 0 0; color: var(--nb-muted); font: 600 17px/1.3 var(--font_body); }
    .nb-counter__n { margin-right: 6px; font: 400 30px/1 var(--font_heading); }

    /* Bölümler */
    .nb-section { padding: 72px 0; }
    .nb-section--wall { background: linear-gradient(180deg, #0a0e26, var(--nb-night)); border-top: 1px solid var(--nb-line); border-bottom: 1px solid var(--nb-line); }
    .nb-section--road { position: relative; background: #05060f; }
    .nb-section--road::before { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 1px; background: linear-gradient(90deg, transparent, var(--nb-yellow), transparent); opacity: .5; }
    .nb-head { display: flex; align-items: flex-end; justify-content: space-between; gap: 18px; margin-bottom: 30px; }
    .nb-head--center { justify-content: center; text-align: center; }
    .nb-head p { margin: 8px 0 0; color: var(--nb-muted); font-size: 16.5px; }
    .nb-h2 { margin: 0; font: 400 clamp(28px, 3.2vw, 38px)/1.15 var(--font_heading); overflow-wrap: break-word; }
    .nb-after { margin: 22px 0 0; text-align: center; }
    .nb-empty-line { margin: 0; color: var(--nb-muted); font-size: 17px; text-align: center; }

    /* Vitrin kartı */
    .nb-vitrinler { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .nb-vitrin { position: relative; display: flex; flex-direction: column; height: 100%; overflow: hidden; border-radius: 14px; border: 1px solid var(--nb-line); background: var(--nb-panel); box-shadow: inset 0 1px 0 rgba(255,255,255,.04), 0 22px 40px -30px #000; transition: transform .2s, box-shadow .2s, border-color .2s; }
    .nb-vitrin:hover, .nb-vitrin:focus-within { transform: translateY(-4px); border-color: var(--tone); box-shadow: 0 0 22px -4px var(--tone), 0 26px 46px -26px #000; }
    .nb-vitrin--premium { border-color: var(--nb-pink); }
    .nb-vitrin__cam { display: block; position: relative; height: 128px; overflow: hidden; background: linear-gradient(180deg, color-mix(in srgb, var(--tone) 30%, #0f1330), #0f1330 90%); border-bottom: 2px solid var(--tone); box-shadow: 0 2px 20px -6px var(--tone); }
    .nb-vitrin__glow { position: absolute; inset: 0; background: radial-gradient(60% 90% at 50% 100%, color-mix(in srgb, var(--tone) 55%, transparent), transparent 70%), repeating-linear-gradient(90deg, transparent 0 46px, rgba(255,255,255,.06) 46px 48px); }
    .nb-vitrin__cover { display: block; width: 100%; height: 100%; object-fit: cover; opacity: .85; }
    .nb-vitrin__logo { position: absolute; inset: 0; display: grid; place-items: center; padding: 16px; }
    .nb-vitrin__logo img { display: block; max-width: 140px; max-height: 60px; width: auto; height: auto; object-fit: contain; filter: drop-shadow(0 0 10px rgba(255,255,255,.25)); }
    .nb-vitrin__cat { position: absolute; left: 14px; bottom: 10px; max-width: calc(100% - 28px); padding: 4px 10px; border: 1px solid var(--tone); border-radius: 999px; background: rgba(7,10,28,.8); color: var(--tone); font: 700 12.5px/1.3 var(--font_body); white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
    .nb-vitrin__seal { position: absolute; top: 12px; right: 14px; font: 400 13px/1 var(--font_heading); letter-spacing: .08em; text-transform: uppercase; }
    .nb-vitrin__body { display: flex; flex: 1; flex-direction: column; gap: 8px; padding: 16px 18px 18px; }
    .nb-vitrin__name { margin: 0; font: 400 21px/1.25 var(--font_heading); overflow-wrap: break-word; }
    .nb-vitrin__name a { color: var(--nb-ink); text-decoration: none; }
    .nb-vitrin__name a:hover { color: var(--tone); }
    .nb-vitrin__desc { display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow: hidden; margin: 0; color: var(--nb-muted); font-size: 15px; }
    .nb-vitrin__meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 10px; margin: 0; color: var(--nb-muted); font-size: 13.5px; }
    .nb-chip { padding: 3px 9px; border: 1px solid var(--nb-line-2); border-radius: 999px; font-weight: 700; }
    .nb-chip--ok { border-color: var(--nb-cyan); color: var(--nb-cyan); }
    .nb-chip--open { border-color: var(--nb-lime); color: var(--nb-lime); }
    .nb-vitrin__rating { color: var(--nb-yellow); font-weight: 700; }
    .nb-vitrin__place { display: inline-flex; align-items: center; gap: 4px; }
    .nb-vitrin__acts { display: flex; gap: 8px; margin-top: auto; padding-top: 8px; }
    .nb-call { display: inline-flex; flex: 1; align-items: center; justify-content: center; gap: 8px; min-width: 0; min-height: 50px; padding: 0 14px; border-radius: 10px; background: var(--nb-yellow); color: #140f00; font: 800 18px/1 var(--font_body); text-decoration: none; box-shadow: 0 0 16px rgba(255,225,77,.35); transition: background-color .16s, box-shadow .16s; }
    .nb-call span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .nb-call:hover { background: #fff07a; box-shadow: 0 0 22px rgba(255,225,77,.6); }
    .nb-call--wa { background: var(--nb-wa); color: #06200f; box-shadow: 0 0 16px rgba(37,211,102,.35); }
    .nb-call--wa:hover { background: #4ae386; }
    .nb-call--line { background: transparent; border: 2px solid var(--nb-line-2); color: var(--nb-ink); box-shadow: none; }
    .nb-call--line:hover { border-color: var(--nb-cyan); background: transparent; box-shadow: none; }
    .nb-wa { display: grid; flex: none; place-items: center; width: 50px; height: 50px; border: 1px solid #1d6b3d; border-radius: 10px; background: #0f2a1c; color: var(--nb-wa); }
    .nb-wa:hover { background: var(--nb-wa); color: #06200f; }
    .nb-more { display: grid; flex: none; place-items: center; width: 44px; height: 50px; border: 1px solid var(--nb-line-2); border-radius: 10px; color: var(--nb-muted); font-size: 26px; line-height: 1; text-decoration: none; }
    .nb-more:hover { border-color: var(--nb-cyan); color: var(--nb-cyan); }

    /* Kiralık vitrin */
    .nb-bos, .nb-empty { display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 10px; height: 100%; padding: 32px 24px; border: 2px dashed var(--nb-line-2); border-radius: 14px; background: rgba(16,20,43,.5); text-align: center; }
    .nb-bos__sign { display: inline-block; padding: 6px 14px; border: 2px dashed var(--nb-pink); border-radius: 8px; color: var(--nb-pink); font: 400 14px/1.2 var(--font_heading); letter-spacing: .12em; text-transform: uppercase; animation: nb-flicker 6s linear infinite; }
    .nb-bos h3, .nb-empty h3 { margin: 4px 0 0; font: 400 24px/1.2 var(--font_heading); }
    .nb-bos p, .nb-empty p { max-width: 40ch; margin: 0; color: var(--nb-muted); }
    .nb-bos__acts { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 8px 18px; margin-top: 6px; }

    /* Neon tabelalar */
    .nb-tabelalar { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 24px 16px; padding-top: 12px; }
    .nb-sign { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 6px; height: 100%; min-height: 124px; padding: 24px 14px 18px; border: 2px solid var(--tone); border-radius: 12px; background: linear-gradient(180deg, #151a3a, #0d1128); box-shadow: inset 0 0 0 1px rgba(255,255,255,.04), inset 0 0 24px -8px var(--tone), 0 0 14px -2px var(--tone), 0 0 40px -14px var(--tone); color: var(--nb-ink); text-align: center; text-decoration: none; transition: transform .18s, box-shadow .18s; }
    .nb-sign:hover, .nb-sign:focus-visible { transform: translateY(-3px); box-shadow: inset 0 0 0 1px rgba(255,255,255,.06), inset 0 0 30px -6px var(--tone), 0 0 22px 0 var(--tone), 0 0 60px -10px var(--tone); }
    .nb-sign:hover .nb-sign__name { animation: nb-zzt .5s linear 1; }
    @keyframes nb-zzt { 0%, 100% { opacity: 1; } 30% { opacity: .4; } 45% { opacity: 1; } 60% { opacity: .6; } }
    .nb-sign__hook { position: absolute; top: -13px; left: 50%; width: 44px; height: 13px; margin-left: -22px; border: 2px solid var(--nb-line-2); border-bottom: 0; border-radius: 12px 12px 0 0; }
    .nb-sign__name { --c: var(--tone); font: 400 22px/1.15 var(--font_heading); overflow-wrap: break-word; }
    .nb-sign__n { color: var(--nb-muted); font: 700 13px/1.2 var(--font_body); }

    /* Şehir durakları */
    .nb-cities { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
    .nb-city { display: inline-flex; align-items: center; gap: 10px; min-height: 48px; padding: 0 18px; border: 1px solid var(--nb-line); border-radius: 999px; background: var(--nb-panel); color: var(--nb-ink); font: 700 17px/1.2 var(--font_body); text-decoration: none; transition: border-color .16s, transform .16s; }
    .nb-city:hover { border-color: var(--nb-cyan); transform: translateY(-2px); }
    .nb-city__dot { flex: none; width: 10px; height: 10px; border-radius: 50%; background: var(--nb-cyan); box-shadow: 0 0 8px var(--nb-cyan), 0 0 18px var(--nb-cyan); }
    .nb-city small { color: var(--nb-muted); font-size: 13px; font-weight: 600; }
    .nb-city--big { min-height: 56px; padding: 0 22px; font: 400 22px/1.2 var(--font_heading); }
    .nb-city--big .nb-city__dot { width: 13px; height: 13px; background: var(--nb-yellow); box-shadow: 0 0 8px var(--nb-yellow), 0 0 20px var(--nb-yellow); }

    /* Reklam panoları */
    .nb-panolar { display: grid; grid-template-columns: minmax(0,1fr); gap: 18px; }
    .nb-pano { --tone: var(--nb-cyan); height: 100%; padding: 26px 24px; border: 1px solid var(--nb-line); border-top: 3px solid var(--tone); border-radius: 14px; background: var(--nb-panel); box-shadow: 0 -8px 30px -22px var(--tone); }
    .nb-pano--2 { --tone: var(--nb-pink); } .nb-pano--3 { --tone: var(--nb-yellow); }
    .nb-pano__ico { display: grid; place-items: center; width: 52px; height: 52px; margin-bottom: 14px; border: 1px solid var(--tone); border-radius: 50%; color: var(--tone); box-shadow: 0 0 14px -4px var(--tone); }
    .nb-pano h3 { margin: 0 0 6px; font: 400 22px/1.2 var(--font_heading); }
    .nb-pano p { margin: 0; color: var(--nb-muted); font-size: 15.5px; }

    /* Gece okumaları */
    .nb-posts { display: grid; grid-template-columns: minmax(0,1fr); gap: 18px; }
    .nb-post { position: relative; display: flex; flex-direction: column; gap: 8px; height: 100%; padding: 26px 22px 18px; overflow: hidden; border: 1px solid var(--nb-line); border-radius: 14px; background: var(--nb-panel); }
    .nb-post__no { position: absolute; top: -8px; right: 14px; color: var(--tone); font: 400 72px/1 var(--font_heading); opacity: .16; }
    .nb-post time { color: var(--tone); font-size: 13.5px; font-weight: 700; }
    .nb-post h3 { margin: 0; font: 400 22px/1.25 var(--font_heading); }
    .nb-post h3 a { color: var(--nb-ink); text-decoration: none; }
    .nb-post h3 a:hover { color: var(--tone); }
    .nb-post p { margin: 0; color: var(--nb-muted); font-size: 15.5px; }
    .nb-post .nb-link { margin-top: auto; }

    /* İş ilanları */
    .nb-jobs { display: grid; grid-template-columns: minmax(0,1fr); gap: 12px; }
    .nb-job { display: flex; flex-direction: column; gap: 4px; height: 100%; padding: 16px 20px; border: 1px solid var(--nb-line); border-left: 4px solid var(--nb-cyan); border-radius: 10px; background: var(--nb-panel); color: var(--nb-ink); text-decoration: none; }
    .nb-job:hover .nb-job__title { text-decoration: underline; }
    .nb-job__type { color: var(--nb-cyan); font: 700 13.5px/1.3 var(--font_body); letter-spacing: .04em; text-transform: uppercase; }
    .nb-job__title { font: 400 20px/1.25 var(--font_heading); }
    .nb-job__meta { color: var(--nb-muted); font-size: 14.5px; }

    /* Sönük tabela: forma odaklanınca ya da üzerine gelince yanar. */
    .nb-cta-sec { padding-top: 24px; }
    .nb-cta { display: grid; grid-template-columns: minmax(0,1fr); gap: 28px; align-items: center; padding: 32px 24px; border: 1px solid var(--nb-line); border-radius: 18px; background: linear-gradient(180deg, #121736, #0b0f26); box-shadow: 0 30px 60px -40px #000; }
    .nb-cta__sign { display: flex; justify-content: center; }
    .nb-cta__frame { display: flex; flex-direction: column; align-items: center; gap: 2px; width: 100%; max-width: 360px; padding: 30px 24px; border: 3px solid #2d3461; border-radius: 16px; background: #0a0d22; transition: border-color .35s, box-shadow .35s; }
    .nb-cta__word { color: #3b4270; font: 400 clamp(30px, 4vw, 46px)/1.05 var(--font_heading); transition: color .35s, text-shadow .35s; }
    .nb-cta__open { margin-top: 12px; padding: 4px 14px; border: 2px solid #3b4270; border-radius: 8px; color: #3b4270; font: 800 15px/1.3 var(--font_body); letter-spacing: .22em; transition: color .35s, border-color .35s, box-shadow .35s, text-shadow .35s; }
    .nb-cta:hover .nb-cta__frame, .nb-cta:focus-within .nb-cta__frame { border-color: var(--nb-pink); box-shadow: 0 0 26px rgba(255,79,174,.5), inset 0 0 34px rgba(255,79,174,.14); }
    .nb-cta:hover .nb-cta__word--1, .nb-cta:focus-within .nb-cta__word--1 { color: #fff; text-shadow: 0 0 2px #fff, 0 0 10px var(--nb-pink), 0 0 24px var(--nb-pink); }
    .nb-cta:hover .nb-cta__word--2, .nb-cta:focus-within .nb-cta__word--2 { color: #fff; text-shadow: 0 0 2px #fff, 0 0 10px var(--nb-cyan), 0 0 24px var(--nb-cyan); animation: nb-zzt .7s linear 1; }
    .nb-cta:hover .nb-cta__open, .nb-cta:focus-within .nb-cta__open { color: #fff; border-color: var(--nb-lime); text-shadow: 0 0 8px var(--nb-lime); box-shadow: 0 0 14px rgba(141,255,90,.5); }
    .nb-cta__txt h2 { margin: 0 0 10px; }
    .nb-cta__txt > p { max-width: 52ch; margin: 0 0 20px; color: var(--nb-muted); font-size: 17px; }
    .nb-cta__form label { display: block; margin-bottom: 6px; color: var(--nb-ink); font: 700 14px/1.2 var(--font_body); }
    .nb-cta__row { display: flex; flex-wrap: wrap; gap: 10px; }
    .nb-cta__row input { flex: 1 1 240px; min-width: 0; height: 54px; padding: 0 16px; border: 1.5px solid var(--nb-line-2); border-radius: 10px; background: var(--nb-night); color: var(--nb-ink); font: 600 17px/1.2 var(--font_body); }
    .nb-cta__row input::placeholder { color: #7f86b3; }
    .nb-cta__row input:focus { outline: none; border-color: var(--nb-cyan); box-shadow: 0 0 0 3px rgba(34,227,255,.35); }
    .nb-cta__links { display: flex; flex-wrap: wrap; gap: 0 24px; margin: 12px 0 0; }

    /* Mobil sabit çubuk (arama kutusu ekrandan çıkınca görünür) */
    .nb-dock { display: none; }

    /* Ortak alt bilgi */
    .theme-neon-bulvar body > footer { background: #04050f !important; border-top: 1px solid var(--nb-line); color: #b9bfe3 !important; }
    .theme-neon-bulvar body > footer :where(p,ul,button) { color: #a3a9d1 !important; }
    .theme-neon-bulvar body > footer :where(h3,span) { color: var(--nb-ink) !important; }
    .theme-neon-bulvar body > footer h3 { font-family: var(--font_heading); font-weight: 400; font-size: 17px; text-transform: none !important; letter-spacing: 0 !important; }
    .theme-neon-bulvar body > footer a:hover { color: var(--nb-pink) !important; }

    /* İç sayfalar: birincil renkli başlık bantları gece göğüne, altında neon çizgi. */
    .theme-neon-bulvar main section[style*="background:var(--primary)"] { background: linear-gradient(180deg, #141937, #0b0f26) !important; box-shadow: inset 0 -3px 0 var(--nb-pink), 0 0 30px -6px rgba(255,79,174,.45); }
    .theme-neon-bulvar main section[style*="background:var(--primary)"] :focus-visible, .theme-neon-bulvar body > footer :focus-visible { outline-color: var(--nb-cyan); }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar. */
    .theme-neon-bulvar #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-neon-bulvar #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .nb-tabelalar { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .nb-vitrinler { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .nb-vitrinler > li:last-child:nth-child(odd) { grid-column: 1 / -1; }
        .nb-cta { padding: 36px; }
    }
    @media (min-width: 768px) {
        .nb-panolar, .nb-posts, .nb-jobs { grid-template-columns: repeat(3,minmax(0,1fr)); }
    }
    @media (min-width: 960px) {
        .nb-tabelalar { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .nb-sign__name { font-size: 24px; }
    }
    @media (min-width: 1024px) {
        .nb-vitrinler { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 26px; }
        .nb-vitrinler > li:last-child:nth-child(odd) { grid-column: auto; }
        .nb-vitrinler > li:last-child:nth-child(3n+1) { grid-column: 1 / -1; }
        .nb-vitrinler > li:last-child:nth-child(3n+2) { grid-column: span 2; }
        .nb-cta { grid-template-columns: minmax(0,5fr) minmax(0,7fr); gap: 56px; padding: 44px 48px; }
    }
    @media (min-width: 1024px) and (max-width: 1179.98px) {
        .nb-nav { gap: 18px; }
    }
    @media (max-width: 1023.98px) {
        .nb-nav { display: none; }
        .nb-menu { display: inline-grid; }
        /* Ortak #mobile-menu Tailwind md:hidden taşır; menü düğmesi göründüğü sürece yeniden açılır. */
        .theme-neon-bulvar #mobile-menu { display: block; z-index: 60; }
        .theme-neon-bulvar #mobile-backdrop { z-index: 55; }
        .theme-neon-bulvar #mobile-backdrop:not(.hidden) { display: block; }
    }
    @media (max-width: 760px) {
        html.theme-neon-bulvar { scroll-padding-bottom: 84px; }
        .nb-home { padding-bottom: 76px; }
        .nb-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .nb-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(7,10,28,.96); border-top: 1px solid var(--nb-line); box-shadow: 0 -1px 0 rgba(34,227,255,.3), 0 -12px 28px -16px #000; transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .nb-dock.is-on .nb-dock__bar { transform: none; visibility: visible; }
        .nb-dock__bar .nb-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 639.98px) {
        .nb-wrap { padding-inline: 16px; }
        .nb-header__row { height: 62px; gap: 10px; }
        .nb-brand { gap: 10px; }
        .nb-brand__name { max-width: 44vw; font-size: 19px; }
        .nb-brand__mark svg { width: 34px; height: 34px; }
        .nb-btn--sm { min-height: 44px; padding: 0 14px; }
        .nb-hero { padding: 52px 0 150px; }
        .nb-moon { top: 26px; right: 8%; width: 40px; height: 40px; }
        .nb-sky__far { height: 44%; } .nb-sky__mid { height: 34%; } .nb-sky__near { height: 24%; }
        .nb-eyebrow { gap: 10px; font-size: 13px; letter-spacing: .14em; }
        .nb-tube { width: 28px; }
        .nb-h1 { font-size: clamp(30px, 8.6vw, 38px); }
        .nb-lede { font-size: 16.5px; }
        .nb-search { grid-template-columns: minmax(0,1fr); margin-top: 24px; padding: 6px; border-radius: 14px; }
        .nb-field { min-height: 60px; }
        .nb-search__btn { min-height: 52px; }
        .nb-counter { font-size: 16px; }
        .nb-counter__n { font-size: 26px; }
        .nb-section { padding: 52px 0; }
        .nb-head { flex-direction: column; align-items: flex-start; gap: 6px; margin-bottom: 24px; }
        .nb-head--center { align-items: center; }
        .nb-head .nb-link { min-height: 36px; }
        .nb-tabelalar { gap: 22px 12px; }
        .nb-sign { min-height: 110px; padding: 22px 10px 16px; }
        .nb-sign__name { font-size: 18px; }
        .nb-sign__n { font-size: 12.5px; }
        .nb-vitrin__cam { height: 104px; }
        .nb-city { min-height: 44px; padding: 0 14px; font-size: 15.5px; }
        .nb-city--big { min-height: 50px; font-size: 19px; }
        .nb-cta__frame { padding: 22px 16px; }
        .nb-cta__row .nb-btn { width: 100%; }
        .nb-cta-sec { padding-top: 8px; }
    }
    @media (max-width: 419.98px) {
        .nb-cta-long { display: none; }
        .nb-cta-short { display: inline; }
        .nb-call { font-size: 17px; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-neon-bulvar { scroll-behavior: auto; }
        .theme-neon-bulvar #mobile-menu { transition: none; }
        .nb-flicker, .nb-bos__sign, .nb-win-on, .nb-beacon, .nb-road__line, .nb-sign:hover .nb-sign__name, .nb-cta:hover .nb-cta__word--2, .nb-cta:focus-within .nb-cta__word--2 { animation: none !important; }
        .nb-vitrin, .nb-sign, .nb-city, .nb-nav a::after, .nb-btn, .nb-call, .nb-dock__bar, .nb-cta__frame, .nb-cta__word, .nb-cta__open { transition: none !important; }
        .nb-vitrin:hover, .nb-vitrin:focus-within, .nb-sign:hover, .nb-city:hover { transform: none; }
    }
</style>
