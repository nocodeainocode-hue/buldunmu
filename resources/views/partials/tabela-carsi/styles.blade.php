<link rel="preconnect" href="https://fonts.googleapis.com"><link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preload" as="style" href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Nunito:wght@400;600;700;800&family=Zilla+Slab:wght@500;600;700&display=swap" onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Alfa+Slab+One&family=Nunito:wght@400;600;700;800&family=Zilla+Slab:wght@500;600;700&display=swap"></noscript>
<style>
    /* Tabela Çarşısı · Esnaf Sırası */
    .theme-tabela-carsi {
        --tc-ink: #1d1a17; --tc-cream: #f8ecd0; --tc-plum: #5c2346; --tc-plum-2: #45192f; --tc-plum-deep: #2a1220;
        --tc-mustard: #d9a12b; --tc-mustard-2: #c58f1f; --tc-turq: #17877f; --tc-turq-ink: #0d5f59;
        --tc-navy: #1f3566; --tc-terra: #b4532a; --tc-glass: #1f2422; --tc-gold: #e2b75a;
        --tc-zinc-a: #b7bcbe; --tc-zinc-b: #959b9e;
        --tc-sign: 'Alfa Slab One', Georgia, serif;
        --tc-head: 'Zilla Slab', Georgia, serif;
        --tc-body: Nunito, system-ui, sans-serif;
        --tc-bolt: #f1e4c4; --tc-bolt-ring: rgba(0,0,0,.38);
        --tc-gut: 24px;
        scroll-padding-top: 100px;
    }
    .theme-tabela-carsi body { background: var(--bg); color: var(--text); font-family: var(--tc-body) !important; font-size: 16px; line-height: 1.55; }
    .theme-tabela-carsi :where(h1,h2,h3) { font-family: var(--tc-head); }
    .theme-tabela-carsi :where(a,button,input,select,summary):focus-visible { outline: 3px solid var(--tc-plum); outline-offset: 3px; }
    .theme-tabela-carsi :where(.tc-header, body > footer, .tc-window, .tc-post, main section[style*="background:var(--primary);"]) :focus-visible { outline-color: var(--tc-cream); }
    .theme-tabela-carsi #mobile-menu .truncate { white-space: normal; overflow: visible; text-overflow: clip; }

    .tc-wrap { width: 100%; max-width: var(--page_width, 1240px); margin-inline: auto; padding-inline: var(--tc-gut); }
    .tc-home { overflow-x: clip; }
    .tc-home ul { margin: 0; padding: 0; list-style: none; }
    .tc-sr { position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); white-space: nowrap; border: 0; }

    /* Ortak parçalar: cıvata, boyalı harf, tente, emaye levha */
    .tc-bolts {
        background-image:
            radial-gradient(circle at 10px 10px, var(--tc-bolt) 0 2px, var(--tc-bolt-ring) 2.5px 3.1px, transparent 3.6px),
            radial-gradient(circle at calc(100% - 10px) 10px, var(--tc-bolt) 0 2px, var(--tc-bolt-ring) 2.5px 3.1px, transparent 3.6px),
            radial-gradient(circle at 10px calc(100% - 10px), var(--tc-bolt) 0 2px, var(--tc-bolt-ring) 2.5px 3.1px, transparent 3.6px),
            radial-gradient(circle at calc(100% - 10px) calc(100% - 10px), var(--tc-bolt) 0 2px, var(--tc-bolt-ring) 2.5px 3.1px, transparent 3.6px);
    }
    .tc-paint { font-family: var(--tc-sign); font-weight: 400; color: var(--tc-cream); text-shadow: 1px 1px 0 var(--tc-mustard), 2px 2px 0 var(--tc-mustard), 3px 3px 0 var(--tc-mustard), 4px 4px 4px rgba(0,0,0,.25); }
    .tc-tente {
        --s: 16px; --ta: var(--tc-plum); --tb: var(--tc-cream);
        display: block; height: var(--s);
        background: linear-gradient(180deg, rgba(255,255,255,.14), rgba(0,0,0,.10)), repeating-linear-gradient(90deg, var(--ta) 0 var(--s), var(--tb) var(--s) calc(var(--s) * 2));
        -webkit-mask: linear-gradient(#000 0 0) top/100% calc(100% - var(--s) / 2) no-repeat, radial-gradient(circle calc(var(--s) / 2) at 50% 0, #000 96%, transparent 100%) 0 100%/var(--s) calc(var(--s) / 2) repeat-x;
        mask: linear-gradient(#000 0 0) top/100% calc(100% - var(--s) / 2) no-repeat, radial-gradient(circle calc(var(--s) / 2) at 50% 0, #000 96%, transparent 100%) 0 100%/var(--s) calc(var(--s) / 2) repeat-x;
    }
    .tc-awn { display: block; filter: drop-shadow(0 4px 3px rgba(29,26,23,.22)); }
    .tc-plate { display: inline-flex; align-items: center; justify-content: center; gap: 6px; max-width: 100%; padding: 5px 24px; border: 2px solid var(--tc-cream); border-radius: 8px; color: var(--tc-cream); font: 800 .85rem/1.2 var(--tc-body);
        background: radial-gradient(circle at 10px 50%, #fbf3df 0 1.8px, #24101b 2.3px 2.8px, transparent 3.2px), radial-gradient(circle at calc(100% - 10px) 50%, #fbf3df 0 1.8px, #24101b 2.3px 2.8px, transparent 3.2px), linear-gradient(180deg, rgba(255,255,255,.14), transparent 45%, rgba(0,0,0,.16)), var(--tc-plum);
        box-shadow: 0 0 0 2px var(--tc-plum), 0 6px 12px -8px rgba(0,0,0,.55); }
    .tc-plate--big { padding: 8px 30px; font-size: .95rem; font-weight: 700; }
    .tc-plate b { font: 400 1.15rem/1 var(--tc-sign); }

    /* Ton: her meslek tüm sayfada aynı renkte (crc32(slug) % 6) */
    .tc-c0 { --tone: #5c2346; --tone-ink: #f8ecd0; --tone-shade: #d9a12b; --wash: #ecdfe8; }
    .tc-c1 { --tone: #17877f; --tone-ink: #f8ecd0; --tone-shade: #0d5f59; --wash: #d9ebe7; }
    .tc-c2 { --tone: #d9a12b; --tone-ink: #1d1a17; --tone-shade: #f8ecd0; --wash: #f5e7c0; }
    .tc-c3 { --tone: #b4532a; --tone-ink: #f8ecd0; --tone-shade: #6e2c12; --wash: #f3ddd0; }
    .tc-c4 { --tone: #5d6a2b; --tone-ink: #f8ecd0; --tone-shade: #d9a12b; --wash: #e3e9d3; }
    .tc-c5 { --tone: #6b4a32; --tone-ink: #f8ecd0; --tone-shade: #2e1d10; --wash: #ece0d2; }

    /* Düğmeler ve bağlantılar */
    .tc-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 0 18px; border: 2px solid transparent; border-radius: 6px; font: 800 .98rem/1.1 var(--tc-body); text-align: center; text-decoration: none; cursor: pointer; transition: background-color .18s, color .18s, border-color .18s; }
    .tc-btn--sm { min-height: 42px; padding: 0 16px; white-space: nowrap; }
    .tc-btn--lg { min-height: 52px; padding: 0 28px; font-size: 1.04rem; }
    .tc-btn--plum { background: var(--tc-plum); color: #fff; }
    .tc-btn--plum:hover { background: var(--tc-plum-2); }
    .tc-btn--mustard { background: var(--tc-mustard); color: var(--tc-ink); }
    .tc-btn--mustard:hover { background: #e6b347; }
    .tc-btn--wa { background: #fff; border-color: var(--tc-turq); color: var(--tc-turq-ink); }
    .tc-btn--wa:hover { background: var(--tc-turq); color: #fff; }
    .tc-btn--line { background: transparent; border-color: #c9bfae; color: var(--tc-ink); }
    .tc-btn--line:hover { border-color: var(--tc-plum); color: var(--tc-plum); }
    .tc-more { display: inline-flex; align-items: center; min-height: 44px; color: var(--tc-ink); font-weight: 800; text-decoration: underline; text-decoration-color: var(--tc-mustard); text-decoration-thickness: 2px; text-underline-offset: 5px; }
    .tc-more:hover { color: var(--tc-plum); }

    /* Üst tabela */
    .tc-header { position: sticky; top: 0; z-index: 50; }
    .tc-header__board { --tc-bolt: #e9c46a; --tc-bolt-ring: rgba(0,0,0,.5); position: relative; z-index: 1; height: 72px; background-color: var(--tc-plum); }
    .tc-header__row { display: flex; align-items: center; gap: 20px; height: 100%; }
    .tc-brand { display: inline-flex; align-items: center; gap: 10px; min-width: 0; min-height: 44px; flex-shrink: 1; text-decoration: none; }
    .tc-brand__logo { display: inline-grid; place-items: center; flex: none; height: 40px; padding: 4px 8px; border-radius: 999px; background: var(--tc-cream); box-shadow: 0 0 0 2px var(--tc-mustard); }
    .tc-brand__logo img { display: block; width: auto; max-width: 110px; height: 30px; object-fit: contain; }
    .tc-brand__name { min-width: 0; font-size: 1.5rem; line-height: 1.1; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; padding: 2px 4px 4px 0; }
    .tc-nav { display: flex; flex: none; align-items: center; gap: 26px; margin-inline: auto; }
    .tc-nav a { padding: 6px 0; border-bottom: 2px solid transparent; color: rgba(248,236,208,.9); font: 700 1rem/1.2 var(--tc-body); text-decoration: none; white-space: nowrap; }
    .tc-nav a:hover, .tc-nav a:focus-visible, .tc-nav a[aria-current="page"] { color: var(--tc-cream); border-bottom-color: var(--tc-mustard); }
    .tc-header__actions { display: flex; align-items: center; gap: 10px; flex-shrink: 0; margin-left: auto; }
    .tc-nav + .tc-header__actions { margin-left: 0; }
    .tc-ghost { display: inline-flex; align-items: center; min-height: 42px; padding: 0 14px; border: 1.5px solid rgba(248,236,208,.45); border-radius: 6px; color: var(--tc-cream); font-weight: 700; text-decoration: none; white-space: nowrap; }
    .tc-ghost:hover { border-color: var(--tc-mustard); }
    .tc-menu { display: none; width: 44px; height: 44px; place-items: center; border: 1.5px solid rgba(248,236,208,.45); border-radius: 6px; background: transparent; color: var(--tc-cream); cursor: pointer; flex: none; }
    .tc-header .tc-awn { position: relative; z-index: 0; }

    /* Çarşı başı */
    .tc-hero { position: relative; padding: 26px 0 0; text-align: center; background: radial-gradient(55% 60% at 18% 8%, rgba(255,255,255,.55), transparent 70%), radial-gradient(45% 50% at 86% 24%, rgba(217,161,43,.08), transparent 70%), var(--bg); }
    .tc-h1 { max-width: 22ch; margin: 14px auto 10px; color: var(--tc-ink); font: 700 clamp(2.1rem, 4.6vw, 3.9rem)/1.05 var(--tc-head); letter-spacing: -.01em; text-shadow: 3px 3px 0 #ecd9a8; text-wrap: balance; overflow-wrap: break-word; }
    .tc-sub { max-width: 40rem; margin: 0 auto; color: var(--text_muted); font-size: 1.125rem; text-wrap: balance; }

    /* Asma tabela (arama ve kayıt) */
    .tc-hang { position: relative; max-width: 780px; margin: 20px auto 0; padding-top: 36px; text-align: left; }
    .tc-hang__rail { position: absolute; top: 0; left: 14px; right: 14px; height: 4px; border-radius: 3px; background: #3b332c; box-shadow: 0 2px 0 rgba(255,255,255,.6); }
    .tc-hang__rail::before, .tc-hang__rail::after { content: ""; position: absolute; top: -3px; width: 10px; height: 10px; border-radius: 50%; background: #3b332c; }
    .tc-hang__rail::before { left: -6px; } .tc-hang__rail::after { right: -6px; }
    .tc-board { --tc-bolt: #b3a791; --tc-bolt-ring: rgba(29,26,23,.45); position: relative; background-color: var(--bg_card); border: 2px solid var(--tc-ink); border-radius: 6px; transform-origin: 50% -36px; box-shadow: 0 22px 34px -24px rgba(29,26,23,.6); }
    .tc-board--sway { animation: tc-sway 1.4s ease-out .25s both; }
    .tc-board::before, .tc-board::after { content: ""; position: absolute; top: -36px; z-index: 3; width: 7px; height: 44px;
        background: radial-gradient(3.5px 4px at 50% 4px, transparent 48%, var(--tc-ink) 56% 92%, transparent 100%) 0 0/7px 12px repeat-y, linear-gradient(180deg, transparent 0 7px, var(--tc-ink) 7px 12px) 50% 0/2px 12px repeat-y; }
    .tc-board::before { left: 37px; } .tc-board::after { right: 37px; }
    .tc-board__strip { display: flex; align-items: center; justify-content: center; height: 42px; border-bottom: 2px solid var(--tc-ink); border-radius: 4px 4px 0 0; color: var(--tc-ink); font: 400 1.15rem/1 var(--tc-sign); text-shadow: 1px 1px 0 var(--tc-cream), 2px 2px 0 rgba(248,236,208,.6);
        background: radial-gradient(circle at 40px 9px, transparent 0 2px, #3b332c 2.4px 4px, transparent 4.4px), radial-gradient(circle at calc(100% - 40px) 9px, transparent 0 2px, #3b332c 2.4px 4px, transparent 4.4px), linear-gradient(180deg, rgba(255,255,255,.18), rgba(0,0,0,.06)), var(--tc-mustard); }
    .tc-board__fields { display: grid; grid-template-columns: minmax(0,1fr) minmax(0,220px) auto; gap: 10px; padding: 16px 18px 18px; }
    .tc-field { position: relative; display: block; }
    .tc-field svg { position: absolute; left: 14px; top: 50%; width: 20px; height: 20px; margin-top: -10px; color: var(--text_muted); pointer-events: none; }
    .tc-input, .tc-select { width: 100%; height: 50px; padding: 0 14px; border: 1.5px solid #cfc6b6; border-radius: 6px; background: #fff; color: var(--tc-ink); font: 600 1rem/1.2 var(--tc-body); }
    .tc-input { padding-left: 44px; }
    .tc-input::placeholder { color: #7d7569; font-weight: 600; opacity: 1; }
    .tc-select { appearance: none; -webkit-appearance: none; padding-right: 38px; cursor: pointer; background: #fff url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%235c2346' stroke-width='2.6' stroke-linecap='round'%3E%3Cpath d='m6 9 6 6 6-6'/%3E%3C/svg%3E") no-repeat right 12px center/18px; }
    .tc-input:focus, .tc-select:focus { outline: none; border-color: var(--tc-plum); box-shadow: 0 0 0 3px rgba(92,35,70,.2); }
    .tc-bul { height: 50px; padding: 0 32px; border: 0; border-radius: 6px; background: var(--tc-plum); color: #fff; font: 800 1.08rem/1 var(--tc-body); cursor: pointer; }
    .tc-bul:hover { background: var(--tc-plum-2); }
    .tc-asked { margin: 16px 0 0; color: var(--text_muted); font-size: 1rem; }
    .tc-asked a { color: var(--tc-ink); font-weight: 700; white-space: nowrap; text-decoration: underline; text-decoration-color: var(--tc-mustard); text-decoration-thickness: 2px; text-underline-offset: 4px; }
    .tc-asked a:hover { color: var(--tc-plum); }
    .tc-asked i { margin: 0 6px; color: #a99f90; font-style: normal; }
    @keyframes tc-sway { 0% { transform: rotate(0); } 30% { transform: rotate(1.2deg); } 65% { transform: rotate(-.7deg); } 100% { transform: rotate(0); } }

    /* Çarşı cephesi */
    .tc-street-wrap { position: relative; margin-top: 30px; }
    .tc-street { display: flex; align-items: flex-end; overflow-x: auto; scrollbar-width: none; }
    .tc-street::-webkit-scrollbar { display: none; }
    .tc-front { position: relative; display: flex; flex-direction: column; justify-content: flex-end; flex: 1 1 0; min-width: 140px; color: var(--tc-ink);
        background-color: var(--wall); background-image: radial-gradient(120% 70% at 22% 12%, rgba(255,255,255,.38), transparent 60%), radial-gradient(90% 60% at 80% 88%, rgba(90,60,30,.06), transparent 65%);
        box-shadow: inset 2px 0 0 rgba(255,255,255,.45), inset -2px 0 0 rgba(29,26,23,.08); }
    .tc-front__link { display: flex; flex-direction: column; justify-content: flex-end; flex: 1; color: inherit; text-decoration: none; }
    .tc-front__link:focus-visible { outline-offset: -4px; }
    .tc-front__upper { display: none; position: relative; height: 74px; padding-top: 14px; }
    .tc-front:nth-child(3n+1) .tc-front__upper, .tc-front.is-house .tc-front__upper { display: flex; justify-content: center; gap: 26px; }
    .tc-front__upper::before { content: ""; position: absolute; left: -2px; right: -2px; top: 0; height: 9px; background: rgba(29,26,23,.14); box-shadow: 0 2px 0 rgba(255,255,255,.5), 0 5px 6px -3px rgba(29,26,23,.25); }
    .tc-win { position: relative; display: block; width: 30px; height: 46px; border: 3px solid #fbf6ea; border-radius: 14px 14px 2px 2px;
        background: linear-gradient(135deg, rgba(255,255,255,.28) 0 18%, transparent 18% 32%, rgba(255,255,255,.12) 32% 38%, transparent 38%), linear-gradient(180deg, #56656d, #2f3b41); box-shadow: 0 5px 0 -1px rgba(29,26,23,.16); }
    .tc-win::after { content: ""; position: absolute; left: 50%; top: 0; bottom: 0; width: 2px; margin-left: -1px; background: #fbf6ea; }
    .tc-front__parapet { position: relative; display: block; height: 20px; }
    .tc-front__parapet::before { content: ""; position: absolute; left: -2px; right: -2px; top: 0; height: 7px; background: rgba(29,26,23,.13); box-shadow: 0 2px 0 rgba(255,255,255,.5); }
    .tc-front:nth-child(3n) .tc-front__parapet { height: 34px; }
    .tc-front:nth-child(3n) .tc-front__parapet::after { content: ""; position: absolute; left: 24%; right: 24%; top: -14px; height: 16px; border-radius: 4px 4px 0 0; background: var(--wall); box-shadow: inset 0 5px 0 rgba(29,26,23,.13); }
    .tc-fsign { --tc-bolt: #f3e6c6; display: grid; place-items: center; height: 48px; margin: 0 10px; padding: 0 16px; border-radius: 4px; background-color: var(--tone); box-shadow: 0 8px 10px -8px rgba(29,26,23,.55); overflow: hidden; }
    .tc-fsign > span { display: block; max-width: 100%; overflow: hidden; padding: 2px 3px 3px 0; color: var(--tone-ink); font: 400 1.08rem/1.2 var(--tc-sign); white-space: nowrap; text-overflow: ellipsis; text-shadow: 2px 2px 0 var(--tone-shade); }
    .tc-front .tc-awn { margin: 4px 4px 0; }
    .tc-front .tc-tente { --ta: var(--tone); }
    .tc-front__ground { display: flex; align-items: flex-end; justify-content: center; gap: 10px; padding: 16px 10px 0; }
    .tc-front:nth-child(even) .tc-front__ground { flex-direction: row-reverse; }
    .tc-door { position: relative; display: block; width: 52%; max-width: 98px; height: 122px; overflow: hidden; border: 4px solid #3a302a; border-top-width: 9px; border-bottom: 0; border-radius: 3px 3px 0 0;
        background: radial-gradient(70% 60% at 50% 92%, rgba(255,170,64,.62), rgba(255,140,40,.12) 60%, transparent 75%), #2b2622; transition: box-shadow .25s; }
    .tc-front__link:hover .tc-door, .tc-front__link:focus-visible .tc-door { box-shadow: inset 0 -30px 40px -10px rgba(255,180,80,.55); }
    /* Kepenk her zaman yukarıda sarılı durur (saate bağlı değil); vitrin ışığı ve firma sayısı görünür. */
    .tc-roll { position: absolute; left: 0; right: 0; top: 0; height: 16px; background: linear-gradient(180deg, rgba(255,255,255,.25), transparent), repeating-linear-gradient(180deg, var(--tc-zinc-a) 0 4px, var(--tc-zinc-b) 4px 6px); border-bottom: 3px solid #6f7679; box-shadow: 0 5px 8px -5px rgba(0,0,0,.6); }
    .tc-door__count { position: absolute; left: 0; right: 0; bottom: 12px; color: var(--tc-cream); font: 800 .86rem/1.15 var(--tc-body); text-align: center; text-shadow: 0 1px 2px rgba(0,0,0,.6); }
    .tc-door__count b { display: block; margin-bottom: 2px; color: #ffe2a6; font: 400 1.25rem/1 var(--tc-sign); }
    .tc-side { display: flex; flex-direction: column; align-items: center; width: 34%; max-width: 64px; padding-bottom: 24px; }
    .tc-glass { position: relative; display: grid; place-items: center; width: 100%; height: 64px; border: 3px solid #3a302a; border-radius: 2px;
        background: linear-gradient(125deg, transparent 0 30%, rgba(255,255,255,.2) 30% 40%, transparent 40%), radial-gradient(80% 60% at 50% 100%, rgba(255,170,64,.38), transparent 70%), linear-gradient(180deg, #3d4a4f, #262c2e); }
    .tc-glass__decal { display: grid; place-items: center; width: 34px; height: 34px; border-radius: 50%; background: rgba(255,253,249,.92); font-size: 18px; line-height: 1; }
    .tc-front__base { display: block; height: 12px; background: rgba(29,26,23,.12); box-shadow: inset 0 2px 0 rgba(255,255,255,.3); }
    /* Boş dükkân: kepenk yarıya kadar inik, tabela kesik çizgili */
    .tc-front.is-blank .tc-fsign { background: rgba(255,253,249,.6); border: 2px dashed #a99f90; box-shadow: none; }
    .tc-front.is-blank .tc-fsign > span { color: #5e574d; font: 800 .98rem/1.2 var(--tc-body); text-shadow: none; }
    .tc-front.is-blank .tc-tente { --ta: #a7adaf; --tb: #efe9dd; }
    .tc-kepenk { position: absolute; left: 0; right: 0; top: 0; height: 56%; background: linear-gradient(90deg, rgba(255,255,255,.22), transparent 28%, transparent 72%, rgba(0,0,0,.10)), repeating-linear-gradient(180deg, var(--tc-zinc-a) 0 5px, var(--tc-zinc-b) 5px 7px); border-bottom: 3px solid #6f7679; box-shadow: 0 6px 10px -6px rgba(0,0,0,.45); transition: height .4s ease; }
    .tc-kepenk::after { content: ""; position: absolute; left: 50%; bottom: 3px; width: 16px; height: 4px; margin-left: -8px; border-radius: 2px; background: #4d5356; }
    .tc-front__link:hover .tc-kepenk, .tc-front__link:focus-visible .tc-kepenk { height: 30%; }
    .tc-door__count--blank { color: var(--tc-mustard); font-size: .82rem; }
    .tc-note { padding: 4px 6px; background: #fffdf6; color: var(--tc-plum); font: 800 .66rem/1.1 var(--tc-body); text-align: center; transform: rotate(-3deg); box-shadow: 0 3px 5px -2px rgba(0,0,0,.5); }
    /* Dükkânsız ev: caddeyi tamamlar, bağlantı değildir */
    .tc-house-door { display: block; width: 38%; max-width: 64px; height: 104px; border: 4px solid #6b4a32; border-bottom: 0; border-radius: 30px 30px 0 0; background: repeating-linear-gradient(90deg, #7a5638 0 10px, #6b4a32 10px 12px); }
    .tc-glass--home { background: linear-gradient(125deg, transparent 0 30%, rgba(255,255,255,.22) 30% 40%, transparent 40%), linear-gradient(180deg, #56656d, #2f3b41); }
    /* Kaldırım */
    .tc-walk { position: relative; min-height: 46px; padding: 14px 16px 24px; background: linear-gradient(180deg, rgba(29,26,23,.14), transparent 6px), repeating-linear-gradient(90deg, transparent 0 62px, rgba(29,26,23,.11) 62px 64px), #d9d6cf; }
    .tc-walk::after { content: ""; position: absolute; left: 0; right: 0; bottom: 0; height: 10px; background: #bdb7ab; box-shadow: inset 0 2px 0 rgba(255,255,255,.35), inset 0 -2px 0 rgba(29,26,23,.12); }
    .tc-stat { margin: 0; text-align: center; }
    .tc-swipe { display: none; }

    /* Bölümler */
    .tc-sec { padding: 76px 0 0; }
    .tc-head { display: flex; flex-wrap: wrap; align-items: flex-end; justify-content: space-between; gap: 12px 24px; margin-bottom: 26px; }
    .tc-h2 { margin: 0; color: var(--tc-ink); font: 700 clamp(1.7rem, 3vw, 2.35rem)/1.1 var(--tc-head); text-shadow: 2px 2px 0 #ecd9a8; }
    .tc-lead { margin: 6px 0 0; color: var(--text_muted); font-size: 1.04rem; }
    .tc-empty-line { margin: 0; color: var(--text_muted); }

    /* Çarşının vitrini */
    .tc-grid { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .tc-grid > li { display: flex; min-width: 0; }
    .tc-shop { display: flex; flex-direction: column; flex: 1; min-width: 0; overflow: hidden; background: var(--bg_card); border: 1px solid var(--border); border-radius: 6px; box-shadow: 0 1px 0 #ddd5c7, 0 14px 26px -18px rgba(29,26,23,.45); }
    .tc-shop__sign { --tc-bolt: #f3e6c6; position: relative; display: flex; align-items: center; gap: 12px; min-height: 66px; padding: 10px 26px; background-color: var(--tone); }
    .tc-shop__name { display: -webkit-box; margin: 0; overflow: hidden; color: var(--tone-ink); font: 700 1.2rem/1.18 var(--tc-head); -webkit-line-clamp: 2; -webkit-box-orient: vertical; overflow-wrap: anywhere; }
    .tc-shop__name a { color: inherit; text-decoration: none; }
    .tc-shop__name a:hover { text-decoration: underline; text-decoration-thickness: 2px; text-underline-offset: 4px; }
    .tc-shop.is-premium .tc-shop__sign { --tc-bolt: #e9c46a; background-color: var(--tc-plum); box-shadow: inset 0 0 0 3px var(--tc-plum), inset 0 0 0 5px var(--tc-mustard); }
    .tc-shop.is-premium .tc-shop__name { color: var(--tc-cream); }
    .tc-vitrinde { flex: none; margin-left: auto; padding: 5px 9px; border: 1px solid #8a6414; border-radius: 4px; background: linear-gradient(180deg, #f6d886, #d9a12b 55%, #b98519); box-shadow: inset 0 1px 0 rgba(255,255,255,.5); color: var(--tc-ink); font: 800 .72rem/1 var(--tc-body); }
    .tc-shop .tc-tente { --s: 14px; --ta: var(--tone); }
    .tc-shop.is-premium .tc-tente { --ta: var(--tc-plum); --tb: var(--tc-mustard); }
    .tc-shop .tc-awn { position: relative; z-index: 1; margin-top: -1px; }
    .tc-shop__body { display: flex; flex-direction: column; flex: 1; gap: 10px; padding: 12px 18px 16px; }
    .tc-shop__top { display: grid; grid-template-columns: 56px minmax(0,1fr); align-items: center; gap: 14px; }
    .tc-shop__top--plain { grid-template-columns: minmax(0,1fr); }
    .tc-decal { display: grid; place-items: center; width: 56px; height: 56px; overflow: hidden; border-radius: 50%; background: var(--wash); box-shadow: inset 0 0 0 3px #fff, 0 0 0 1px var(--border); font-size: 26px; line-height: 1; }
    .tc-decal img { width: 100%; height: 100%; object-fit: contain; padding: 6px; background: #fff; }
    .tc-shop__who { display: flex; flex-direction: column; gap: 2px; min-width: 0; }
    .tc-cat { display: inline-flex; align-items: center; gap: 8px; align-self: flex-start; color: var(--tc-ink); font-weight: 800; text-decoration: none; }
    .tc-cat::before { content: ""; flex: none; width: 10px; height: 10px; border-radius: 50%; background: var(--tone); box-shadow: 0 0 0 2px var(--bg_card), 0 0 0 3px color-mix(in srgb, var(--tone) 45%, transparent); }
    a.tc-cat:hover { text-decoration: underline; text-decoration-color: var(--tc-mustard); text-decoration-thickness: 2px; }
    .tc-place { display: inline-flex; align-items: center; gap: 5px; color: var(--text_muted); font-size: .92rem; font-weight: 600; }
    .tc-place svg { flex: none; color: var(--tc-plum); }
    .tc-badges { display: flex; flex-wrap: wrap; align-items: center; gap: 8px 14px; margin: 0; font-size: .9rem; }
    .tc-ok { display: inline-flex; align-items: center; gap: 4px; padding: 4px 10px; border: 1.5px solid #fff; border-radius: 6px; background: linear-gradient(180deg, rgba(255,255,255,.16), transparent 50%, rgba(0,0,0,.1)), var(--tc-turq-ink); box-shadow: 0 0 0 1.5px var(--tc-turq-ink); color: #fff; font: 800 .76rem/1 var(--tc-body); }
    .tc-star { color: var(--text_muted); font-weight: 700; }
    .tc-star b { color: #a87612; }
    .tc-desc { display: -webkit-box; margin: 0; overflow: hidden; color: var(--text_muted); font-size: .95rem; line-height: 1.5; -webkit-line-clamp: 2; -webkit-box-orient: vertical; }
    .tc-esik { display: flex; flex-direction: column; gap: 8px; padding: 12px 14px 14px; background: #ebe7df; border-top: 2px solid #d3ccbe; box-shadow: inset 0 1px 0 rgba(255,255,255,.6); }
    .tc-call { display: flex; align-items: center; justify-content: center; gap: 10px; min-height: 54px; padding: 0 16px; border-radius: 6px; background: var(--tc-plum); color: #fff; text-decoration: none; transition: background-color .18s; }
    .tc-call:hover { background: var(--tc-plum-2); }
    .tc-call svg { flex: none; }
    .tc-call__k { font: 800 1rem/1 var(--tc-body); opacity: .85; }
    .tc-call__n { font: 800 1.18rem/1 var(--tc-body); font-variant-numeric: tabular-nums; letter-spacing: .01em; white-space: nowrap; }
    .tc-esik__row { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 8px; }
    .tc-esik__row > :only-child { grid-column: 1 / -1; }
    .tc-esik__row .tc-btn { padding: 0 8px; background-color: #fff; white-space: nowrap; font-size: .94rem; }
    .tc-esik__row .tc-btn--wa:hover { background-color: var(--tc-turq); }
    /* Yer boş: vitrinin son sırasını tamamlar */
    .tc-grid > .tc-slot { display: none; }
    .tc-shop--empty { position: relative; border-style: dashed; border-color: #c6bcab; background: rgba(255,253,249,.7); color: var(--tc-ink); text-decoration: none; box-shadow: none; }
    .tc-shop--empty .tc-shop__sign { --tc-bolt: #b3a791; --tc-bolt-ring: rgba(29,26,23,.35); justify-content: center; min-height: 54px; margin: 10px 10px 0; border: 2px dashed #a99f90; border-radius: 4px; background-color: transparent; }
    .tc-shop--empty .tc-shop__name { color: #6f675c; font: 400 1.2rem/1 var(--tc-sign); }
    .tc-shop--empty .tc-tente { --ta: #a7adaf; --tb: #efe9dd; }
    .tc-shop--empty .tc-awn { margin: 0 10px; }
    .tc-shop--empty .tc-shop__body { position: relative; align-items: center; justify-content: flex-end; gap: 8px; margin: 0 10px 10px; padding: 0 18px 20px; text-align: center; background: linear-gradient(180deg, #e9e4da, #f1ece3); }
    .tc-shop--empty .tc-shop__body::before { content: ""; display: block; align-self: stretch; height: 92px; margin: 0 -18px 10px; background: linear-gradient(90deg, rgba(255,255,255,.22), transparent 28%, transparent 72%, rgba(0,0,0,.10)), repeating-linear-gradient(180deg, var(--tc-zinc-a) 0 5px, var(--tc-zinc-b) 5px 7px); border-bottom: 4px solid #6f7679; box-shadow: 0 6px 10px -6px rgba(0,0,0,.45); transition: height .4s ease; }
    .tc-shop--empty:hover .tc-shop__body::before, .tc-shop--empty:focus-visible .tc-shop__body::before { height: 40px; }
    .tc-empty__title { display: block; margin: 0; font: 700 1.35rem/1.2 var(--tc-head); }
    .tc-empty__text { display: block; max-width: 34ch; margin: 0 0 6px; color: var(--text_muted); font-size: .95rem; }
    .tc-shop--empty:hover .tc-btn--plum { background: var(--tc-plum-2); }
    /* Boş çarşı */
    .tc-empty { overflow: hidden; border: 2px dashed #c6bcab; border-radius: 6px; background: rgba(255,253,249,.7); }
    .tc-empty__sign { --tc-bolt: #b3a791; --tc-bolt-ring: rgba(29,26,23,.35); justify-content: center; margin: 12px 12px 0; border: 2px dashed #a99f90; border-radius: 4px; background-color: transparent; }
    .tc-empty__sign .tc-shop__name { color: #6f675c; font: 400 1.35rem/1.1 var(--tc-sign); text-align: center; }
    .tc-empty .tc-tente { --ta: #a7adaf; --tb: #efe9dd; }
    .tc-empty .tc-awn { margin: 0 12px; }
    .tc-empty__body { display: flex; flex-direction: column; align-items: center; gap: 6px; padding: 28px 20px 34px; text-align: center; }
    .tc-empty__body .tc-btn { margin-top: 8px; }

    /* Tabela duvarı */
    .tc-wall { margin-top: 76px; padding: 64px 0 70px; box-shadow: inset 0 10px 18px -14px rgba(29,26,23,.25);
        background: #efe8dc url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='64' height='48'%3E%3Cg fill='%23e5ddcf'%3E%3Crect y='22' width='64' height='2'/%3E%3Crect y='46' width='64' height='2'/%3E%3Crect x='62' width='2' height='22'/%3E%3Crect x='30' y='24' width='2' height='22'/%3E%3C/g%3E%3C/svg%3E"); }
    .tc-wall .tc-h2 { text-shadow: 2px 2px 0 #e2cf9c; }
    .tc-signs { display: flex; flex-wrap: wrap; align-items: center; gap: 14px; }
    .tc-signs > li { max-width: 100%; }
    .tc-wsign { --tc-bolt: #f3e6c6; display: inline-flex; align-items: center; gap: 14px; max-width: 100%; min-height: 52px; padding: 10px 14px 10px 26px; border-radius: 5px; background-color: var(--tone); text-decoration: none; box-shadow: 0 10px 14px -10px rgba(29,26,23,.5); transition: transform .2s, box-shadow .2s; }
    .tc-wsign:hover, .tc-wsign:focus-visible { transform: translateY(-2px); box-shadow: 0 18px 22px -12px rgba(29,26,23,.55); }
    .tc-wsign__name { min-width: 0; padding: 0 3px 3px 0; color: var(--tone-ink); font: 400 1.14rem/1.15 var(--tc-sign); text-shadow: 2px 2px 0 var(--tone-shade); overflow-wrap: anywhere; }
    .tc-wsign b { flex: none; margin-right: 4px; padding: 5px 8px; border-radius: 4px; background: var(--tc-cream); box-shadow: inset 0 -1px 0 rgba(0,0,0,.12); color: var(--tc-ink); font: 800 .82rem/1 var(--tc-body); }

    /* Şehir levhaları (lacivert emaye yalnızca burada) */
    .tc-cities { display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 18px; }
    .tc-cplate { position: relative; display: flex; flex-direction: column; align-items: center; justify-content: center; gap: 2px; min-height: 66px; padding: 12px 30px; overflow: hidden; border: 2px solid #fff; border-radius: 8px; color: #fff; text-align: center; text-decoration: none;
        background: radial-gradient(circle at 10px 50%, #e9eef6 0 1.8px, #0b1733 2.3px 2.8px, transparent 3.2px), radial-gradient(circle at calc(100% - 10px) 50%, #e9eef6 0 1.8px, #0b1733 2.3px 2.8px, transparent 3.2px), linear-gradient(180deg, rgba(255,255,255,.14), transparent 45%, rgba(0,0,0,.14)), var(--tc-navy);
        box-shadow: 0 0 0 2px var(--tc-navy), 0 6px 12px -8px rgba(0,0,0,.55); }
    .tc-wall__cities { margin-top: 44px; }
    .tc-h3 { margin: 0 0 16px; color: var(--tc-ink); font: 700 1.4rem/1.2 var(--tc-head); }
    .tc-cities--inline { display: flex; flex-wrap: wrap; gap: 18px; }
    .tc-cities--inline > li { flex: 0 1 240px; min-width: 0; }
    .tc-cplate strong { font: 800 1.15rem/1.15 var(--tc-body); overflow-wrap: anywhere; }
    .tc-cplate small { color: #dfe6f3; font: 700 .84rem/1.2 var(--tc-body); }
    .tc-cplate::after { content: ""; position: absolute; inset: 0; background: linear-gradient(115deg, transparent 35%, rgba(255,255,255,.3) 50%, transparent 65%); transform: translateX(-120%); transition: transform .6s ease; pointer-events: none; }
    .tc-cplate:hover::after, .tc-cplate:focus-visible::after { transform: translateX(120%); }
    .tc-cplate.is-zinc { background: radial-gradient(circle at 10px 50%, #f4f5f5 0 1.8px, #3b4043 2.3px 2.8px, transparent 3.2px), radial-gradient(circle at calc(100% - 10px) 50%, #f4f5f5 0 1.8px, #3b4043 2.3px 2.8px, transparent 3.2px), linear-gradient(180deg, rgba(255,255,255,.18), transparent 45%, rgba(0,0,0,.14)), #6e7578; box-shadow: 0 0 0 2px #6e7578, 0 6px 12px -8px rgba(0,0,0,.55); }

    /* Eleman aranıyor: dükkân camı */
    .tc-window { position: relative; overflow: hidden; padding: 34px 34px 40px; border: 10px solid #3a2d26; border-radius: 6px; background: radial-gradient(70% 90% at 50% 0%, rgba(255,200,120,.10), transparent 60%), var(--tc-glass); box-shadow: inset 0 0 0 2px #5a463b, 0 18px 30px -20px rgba(29,26,23,.6); }
    .tc-window::after { content: ""; position: absolute; inset: 0; z-index: 2; pointer-events: none; background: linear-gradient(135deg, transparent 0 24%, rgba(255,255,255,.07) 24% 33%, transparent 33% 37%, rgba(255,255,255,.045) 37% 40%, transparent 40%); }
    .tc-notes { position: relative; z-index: 3; display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .tc-jnote { display: flex; flex-direction: column; align-items: center; gap: 6px; height: 100%; padding: 20px 20px 18px; border-radius: 2px; background: #fffdf6 linear-gradient(180deg, rgba(0,0,0,.035), transparent 18%); color: var(--tc-ink); text-align: center; text-decoration: none; box-shadow: 0 18px 22px -14px rgba(0,0,0,.7); }
    .tc-jnote__head { width: 100%; margin-bottom: 6px; padding-bottom: 10px; border-bottom: 2px dashed #e0d6c4; color: var(--tc-terra); font: 400 1.25rem/1.08 var(--tc-sign); }
    .tc-jnote__title { font: 700 1.2rem/1.2 var(--tc-head); }
    .tc-jnote__meta { color: var(--text_muted); font-size: .92rem; font-weight: 600; }
    .tc-jnote__type { margin-top: auto; padding-top: 6px; color: var(--tc-plum); font-size: .88rem; font-weight: 800; }
    .tc-jnote:hover .tc-jnote__title { text-decoration: underline; text-decoration-color: var(--tc-mustard); }

    /* Çarşıdan notlar: isli cam, yaldız harf */
    .tc-posts { display: grid; grid-template-columns: minmax(0,1fr); gap: 22px; }
    .tc-posts > li { display: flex; min-width: 0; }
    .tc-post { position: relative; display: flex; flex-direction: column; flex: 1; gap: 12px; padding: 26px 24px 22px; overflow: hidden; border-radius: 6px; background: radial-gradient(90% 70% at 30% 0%, rgba(255,220,150,.07), transparent 60%), var(--tc-glass); box-shadow: inset 0 0 0 1px rgba(226,183,90,.22), 0 18px 28px -20px rgba(29,26,23,.7); }
    .tc-post::after { content: ""; position: absolute; inset: 0; pointer-events: none; background: linear-gradient(135deg, transparent 0 46%, rgba(255,255,255,.06) 46% 56%, transparent 56% 60%, rgba(255,255,255,.035) 60% 63%, transparent 63%); }
    .tc-post h3 { margin: 0; font: 600 1.38rem/1.22 var(--tc-head); }
    .tc-post h3 a { color: var(--tc-gold); text-decoration: none; text-shadow: 0 1px 0 #7a5a1c; }
    .tc-post h3 a::before { content: ""; position: absolute; inset: 0; z-index: 1; }
    .tc-post:hover h3 a { color: #f0cb76; }
    .tc-post p { margin: 0; color: #e9e3d6; font-size: .96rem; line-height: 1.55; }
    .tc-post time { margin-top: auto; padding-top: 12px; border-top: 1px solid rgba(226,183,90,.2); color: rgba(233,227,214,.72); font-size: .86rem; font-weight: 700; }

    /* Tabelanı as */
    .tc-cta { padding: 84px 0 96px; }
    .tc-hang--cta { max-width: 720px; margin-top: 0; }
    .tc-board__inner { display: flex; flex-direction: column; align-items: center; padding: 22px 28px 20px; text-align: center; }
    .tc-blank { display: grid; place-items: center; align-self: stretch; height: 96px; margin-bottom: 20px; border: 2px dashed #a99f90; border-radius: 5px; background: repeating-linear-gradient(135deg, rgba(29,26,23,.025) 0 10px, transparent 10px 20px); cursor: text; }
    .tc-blank:focus-within { border-color: var(--tc-plum); border-style: solid; }
    .tc-blank input { width: 100%; height: 100%; padding: 0 16px; border: 0; background: transparent; color: var(--tc-plum); font: 400 clamp(1.15rem, 2.6vw, 1.6rem)/1.15 var(--tc-sign); text-align: center; }
    .tc-blank input::placeholder { color: #8a8174; opacity: 1; }
    .tc-blank input:focus { outline: none; }
    .tc-cta__title { margin: 0; color: var(--tc-ink); font: 700 clamp(1.6rem, 3.2vw, 2.2rem)/1.15 var(--tc-head); }
    .tc-cta__text { max-width: 46ch; margin: 8px 0 20px; color: var(--text_muted); font-size: 1.05rem; }
    .tc-cta__btns { display: flex; flex-wrap: wrap; justify-content: center; gap: 12px; }
    .tc-cta__panel { margin-top: 10px; font-size: .95rem; }

    /* Mobil alt çubuk */
    .tc-dock { display: none; }

    /* Ortak alt bilgi: kaldırım ve bordür, koyu mürdüm */
    .theme-tabela-carsi body > footer { padding-top: 34px; color: #efe3cc !important;
        background: repeating-linear-gradient(90deg, transparent 0 62px, rgba(29,26,23,.11) 62px 64px) top/100% 24px no-repeat, linear-gradient(180deg, #d9d6cf 0 24px, #bdb7ab 24px 34px, var(--tc-plum-deep) 34px) !important; }
    .theme-tabela-carsi body > footer :where(p,ul,button) { color: #d8c9b4 !important; }
    .theme-tabela-carsi body > footer :where(h3,span) { color: var(--tc-cream) !important; }
    .theme-tabela-carsi body > footer h3 { font-family: var(--tc-head); font-size: 17px; text-transform: none !important; letter-spacing: 0 !important; }
    .theme-tabela-carsi body > footer a:hover { color: var(--tc-mustard) !important; }

    /* İç sayfalar: üst bandın altına tente */
    .theme-tabela-carsi main section[style*="background:var(--primary);"] { position: relative; margin-bottom: 16px; }
    .theme-tabela-carsi main section[style*="background:var(--primary);"]::after { content: ""; position: absolute; left: 0; right: 0; top: 100%; height: 16px;
        background: repeating-linear-gradient(90deg, var(--tc-plum) 0 16px, var(--tc-cream) 16px 32px);
        -webkit-mask: linear-gradient(#000 0 0) top/100% 8px no-repeat, radial-gradient(circle 8px at 50% 0, #000 96%, transparent 100%) 0 100%/16px 8px repeat-x;
        mask: linear-gradient(#000 0 0) top/100% 8px no-repeat, radial-gradient(circle 8px at 50% 0, #000 96%, transparent 100%) 0 100%/16px 8px repeat-x; }

    /* Ortak mobil menü: kapalıyken Tab sırasından da çıkar */
    .theme-tabela-carsi #mobile-menu { transition: transform .3s ease-in-out, visibility .3s; }
    .theme-tabela-carsi #mobile-menu.translate-x-\[-105\%\] { visibility: hidden; }

    @media (min-width: 640px) {
        .tc-grid { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .tc-cities { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .tc-notes.tc-n2, .tc-notes.tc-n3, .tc-posts.tc-n2, .tc-posts.tc-n3 { grid-template-columns: repeat(2,minmax(0,1fr)); }
        .tc-posts.tc-n3 > li:last-child { grid-column: 1 / -1; }
    }
    @media (min-width: 640px) and (max-width: 1023.98px) {
        .tc-grid > .tc-slot--t1 { display: flex; }
    }
    @media (min-width: 1024px) {
        .tc-grid { grid-template-columns: repeat(3,minmax(0,1fr)); gap: 24px; }
        .tc-grid > .tc-slot--d1 { display: flex; grid-column: span 1; }
        .tc-grid > .tc-slot--d2 { display: flex; grid-column: span 2; }
        .tc-slot--d2 .tc-shop__body::before { height: 70px; }
        .tc-cities { grid-template-columns: repeat(4,minmax(0,1fr)); }
        .tc-notes.tc-n3, .tc-posts.tc-n3 { grid-template-columns: repeat(3,minmax(0,1fr)); }
        .tc-posts.tc-n3 > li:last-child { grid-column: auto; }
        .tc-notes.tc-n1, .tc-posts.tc-n1 { max-width: 560px; }
        .tc-notes > li:nth-child(1) .tc-jnote { transform: rotate(-1.5deg); }
        .tc-notes > li:nth-child(2) .tc-jnote { transform: rotate(1deg); }
        .tc-notes > li:nth-child(3) .tc-jnote { transform: rotate(-.5deg); }
    }
    @media (min-width: 1024px) and (max-width: 1199.98px) {
        .tc-nav { gap: 18px; }
        .tc-brand__name { font-size: 1.3rem; }
        .tc-ghost { display: none; }
        .tc-fsign { margin: 0 6px; padding: 0 8px; }
        .tc-fsign > span { font-size: .9rem; }
        .tc-front.is-blank .tc-fsign > span { font-size: .84rem; }
        .tc-front__ground { gap: 8px; padding-inline: 8px; }
    }
    @media (max-width: 1023.98px) {
        .tc-nav, .tc-ghost { display: none; }
        .tc-menu { display: inline-grid; }
        .theme-tabela-carsi #mobile-menu { display: block; z-index: 60; }
        .theme-tabela-carsi #mobile-backdrop { z-index: 55; }
        .theme-tabela-carsi #mobile-backdrop:not(.hidden) { display: block; }
        .tc-street { scroll-snap-type: x mandatory; scroll-padding: 0 16px; -webkit-mask-image: linear-gradient(90deg, #000 0, #000 80%, transparent 100%); mask-image: linear-gradient(90deg, #000 0, #000 80%, transparent 100%); }
        .tc-front { flex: 0 0 clamp(150px, 30vw, 200px); scroll-snap-align: start; }
        .tc-swipe { display: block; position: absolute; right: 16px; bottom: 16px; color: #5e574d; font: 400 .8rem/1 var(--tc-sign); letter-spacing: .04em; }
        html.theme-tabela-carsi { scroll-padding-bottom: 84px; }
        .tc-home { padding-bottom: 76px; }
        .tc-dock { display: block; position: sticky; bottom: 0; z-index: 40; height: 0; }
        .tc-dock__bar { position: absolute; left: 0; right: 0; bottom: 0; display: grid; grid-template-columns: repeat(2,minmax(0,1fr)); gap: 10px; padding: 10px 16px max(10px, env(safe-area-inset-bottom)); background: rgba(255,253,249,.97); border-top: 1px solid var(--border); box-shadow: 0 -10px 24px -16px rgba(29,26,23,.5); transform: translateY(110%); visibility: hidden; transition: transform .2s, visibility .2s; }
        .tc-dock.is-on .tc-dock__bar { transform: none; visibility: visible; }
        .tc-dock__bar .tc-btn { min-height: 48px; padding: 0 10px; }
    }
    @media (max-width: 760px) {
        .theme-tabela-carsi { --tc-gut: 16px; }
        .tc-header__board { height: 60px; }
        .tc-header__row { gap: 10px; }
        .tc-brand__name { font-size: 1.06rem; white-space: normal; display: -webkit-box; -webkit-line-clamp: 2; -webkit-box-orient: vertical; text-shadow: 1px 1px 0 var(--tc-mustard), 2px 2px 0 var(--tc-mustard), 3px 3px 3px rgba(0,0,0,.25); }
        .tc-brand__logo { height: 34px; }
        .tc-brand__logo img { max-width: 80px; height: 24px; }
        .tc-header .tc-btn--sm { min-height: 42px; padding: 0 12px; font-size: .88rem; }
        .tc-header .tc-tente { --s: 10px; }
        .tc-hero { padding-top: 22px; }
        .tc-h1 { font-size: 2.05rem; line-height: 1.1; }
        .tc-sub { font-size: 1.02rem; }
        .tc-hang { margin-top: 18px; padding-top: 20px; }
        .tc-hang--cta { margin-top: 0; }
        .tc-board { transform-origin: 50% -20px; }
        .tc-board::before, .tc-board::after { top: -20px; height: 28px; }
        .tc-board::before { left: 29px; } .tc-board::after { right: 29px; }
        .tc-board__strip { background: radial-gradient(circle at 32px 9px, transparent 0 2px, #3b332c 2.4px 4px, transparent 4.4px), radial-gradient(circle at calc(100% - 32px) 9px, transparent 0 2px, #3b332c 2.4px 4px, transparent 4.4px), linear-gradient(180deg, rgba(255,255,255,.18), rgba(0,0,0,.06)), var(--tc-mustard); }
        .tc-hang__rail { left: 8px; right: 8px; }
        .tc-board__fields { grid-template-columns: minmax(0,1fr); padding: 14px; }
        .tc-bul { width: 100%; }
        .tc-asked { line-height: 2; }
        .tc-front { flex-basis: clamp(150px, 46vw, 200px); }
        .tc-sec { padding-top: 56px; }
        .tc-grid { gap: 18px; }
        .tc-shop__sign { min-height: 60px; padding: 10px 22px; }
        .tc-wall { margin-top: 56px; padding: 48px 0 52px; }
        .tc-signs { gap: 10px; }
        .tc-wsign { min-height: 46px; padding: 8px 10px 8px 20px; gap: 10px; }
        .tc-wsign__name { font-size: .98rem; }
        .tc-cities { gap: 14px; }
        .tc-cplate { padding: 12px 22px; }
        .tc-cplate strong { font-size: 1.02rem; }
        .tc-window { padding: 22px 16px 26px; border-width: 8px; }
        .tc-post { padding: 22px 18px 18px; }
        .tc-cta { padding: 56px 0 8px; }
        .tc-board__inner { padding: 18px 16px 18px; }
        .tc-blank { height: 80px; }
        .tc-blank input { padding: 0 10px; font-size: 1.05rem; }
        .tc-cta__btns { align-self: stretch; }
        .tc-cta__btns .tc-btn { width: 100%; }
    }
    @media (max-width: 380px) {
        .tc-brand__name { font-size: .98rem; }
        .tc-call__n { font-size: 1.08rem; }
    }
    @media (prefers-reduced-motion: reduce) {
        html.theme-tabela-carsi { scroll-behavior: auto; }
        .theme-tabela-carsi #mobile-menu { transition: none; }
        .tc-board--sway { animation: none; }
        .tc-kepenk, .tc-door, .tc-wsign, .tc-cplate::after, .tc-shop--empty .tc-shop__body::before, .tc-dock__bar, .tc-btn, .tc-call { transition: none !important; }
        .tc-wsign:hover, .tc-wsign:focus-visible { transform: none; }
    }
</style>
