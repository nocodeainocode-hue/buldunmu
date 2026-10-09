<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Sora:wght@400;600;700;800&family=Manrope:wght@400;500;600;700;800&family=Space+Grotesk:wght@400;500;600;700&family=Outfit:wght@400;600;700;800;900&family=IBM+Plex+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
<style>
    /* ══════════════════════════════════════════════════════════════════
       CEP TEMALARI · ortak telefon kabuğu (story-reels / pocket-stories / swipe-cards
       / pull-drawer / radar-scope / index-rally)
       Masaüstünde 430px cihaz çerçevesi, gerçek cihazda tam ekran. Alt sayfa
       şablonlarının tamamı bu kabuğun içinde render edilir; asla masaüstü
       düzenine geçmez.
       ══════════════════════════════════════════════════════════════════ */

    /* ── Tema etiketleri (token blokları) ────────────────────────────── */
    html.theme-story-reels {
        --ph-wall: #07040c; --ph-wall2: #14071f; --ph-bezel: #221634;
        --ph-bg: #0b0713; --ph-screen: #100a19; --ph-card: #191026; --ph-card2: #231534;
        --ph-ink: #f5effd; --ph-muted: #a390bd; --ph-line: #2d1c42;
        --ph-primary: #8b5cf6; --ph-primary2: #ec4899; --ph-accent: #22d3ee; --ph-ok: #3ddc97; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(16,10,25,.86); --ph-tab: rgba(11,7,19,.94);
        --ph-radius: 26px; --ph-radius-sm: 18px; --ph-pill: 999px;
        --ph-shadow: 0 20px 44px rgba(0,0,0,.48);
        --ph-font: Sora, "Segoe UI", sans-serif; --ph-num: Sora, sans-serif;
        --ph-title-weight: 700; --ph-title-case: none;
    }
    html.theme-pocket-stories {
        --ph-wall: #d9edef; --ph-wall2: #f2f7f7; --ph-bezel: #dfe7e8;
        --ph-bg: #eff5f6; --ph-screen: #f6fafb; --ph-card: #ffffff; --ph-card2: #eaf3f4;
        --ph-ink: #0e2a2e; --ph-muted: #5c7f85; --ph-line: #d6e5e7;
        --ph-primary: #0f8b8d; --ph-primary2: #ff7a45; --ph-accent: #ffd166; --ph-ok: #12a36b; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(246,250,251,.92); --ph-tab: rgba(255,255,255,.95);
        --ph-radius: 22px; --ph-radius-sm: 14px; --ph-pill: 999px;
        --ph-shadow: 0 12px 28px rgba(14,42,46,.12);
        --ph-font: Manrope, "Segoe UI", sans-serif; --ph-num: Manrope, sans-serif;
        --ph-title-weight: 800; --ph-title-case: none;
    }
    html.theme-swipe-cards {
        --ph-wall: #0d0a09; --ph-wall2: #1b1210; --ph-bezel: #2a1d18;
        --ph-bg: #14100e; --ph-screen: #191311; --ph-card: #221a16; --ph-card2: #2c211c;
        --ph-ink: #fff4ec; --ph-muted: #b39a8c; --ph-line: #3a2b23;
        --ph-primary: #ff6a3d; --ph-primary2: #c9f24d; --ph-accent: #f0a12b; --ph-ok: #c9f24d; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(25,19,17,.9); --ph-tab: rgba(20,16,14,.95);
        --ph-radius: 30px; --ph-radius-sm: 20px; --ph-pill: 8px;
        --ph-shadow: 0 24px 54px rgba(0,0,0,.55);
        --ph-font: "Space Grotesk", "Segoe UI", sans-serif; --ph-num: "Space Grotesk", sans-serif;
        --ph-title-weight: 700; --ph-title-case: uppercase;
    }
    html.theme-pull-drawer {
        --ph-wall: #e6d8c1; --ph-wall2: #f6efe4; --ph-bezel: #efe3d0;
        --ph-bg: #f6efe4; --ph-screen: #fbf6ec; --ph-card: #fffdf8; --ph-card2: #f1e6d4;
        --ph-ink: #26170c; --ph-muted: #7c6a58; --ph-line: #e3d5c1;
        --ph-primary: #7a3811; --ph-primary2: #d9822b; --ph-accent: #4e8c74; --ph-ok: #3d8a5f; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(251,246,236,.94); --ph-tab: rgba(255,253,248,.96);
        --ph-radius: 18px; --ph-radius-sm: 12px; --ph-pill: 999px;
        --ph-shadow: 0 14px 30px rgba(38,23,12,.14);
        --ph-font: Outfit, "Segoe UI", sans-serif; --ph-num: Outfit, sans-serif;
        --ph-title-weight: 800; --ph-title-case: none;
    }
    html.theme-radar-scope {
        --ph-wall: #01080e; --ph-wall2: #071f28; --ph-bezel: #123240;
        --ph-bg: #04121a; --ph-screen: #071a24; --ph-card: #0a1f2b; --ph-card2: #102b3a;
        --ph-ink: #e3f6ef; --ph-muted: #7ba3ac; --ph-line: #163949;
        --ph-primary: #33d6a2; --ph-primary2: #5ad3ff; --ph-accent: #ffb020; --ph-ok: #33d6a2; --ph-btn-fg: #04121a;
        --ph-nav: rgba(7,26,36,.92); --ph-tab: rgba(4,18,26,.96);
        --ph-radius: 10px; --ph-radius-sm: 8px; --ph-pill: 6px;
        --ph-shadow: 0 16px 36px rgba(0,0,0,.5);
        --ph-font: "IBM Plex Mono", "Consolas", monospace; --ph-num: "IBM Plex Mono", monospace;
        --ph-title-weight: 600; --ph-title-case: uppercase;
    }
    html.theme-index-rally {
        --ph-wall: #e2e6f5; --ph-wall2: #f5f6fb; --ph-bezel: #dde1f0;
        --ph-bg: #f5f6fb; --ph-screen: #fafbff; --ph-card: #ffffff; --ph-card2: #e9edfa;
        --ph-ink: #121633; --ph-muted: #5d6480; --ph-line: #d8ddef;
        --ph-primary: #2b47d8; --ph-primary2: #ff4d6d; --ph-accent: #ffc247; --ph-ok: #1d9e6f; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(250,251,255,.94); --ph-tab: rgba(255,255,255,.96);
        --ph-radius: 14px; --ph-radius-sm: 10px; --ph-pill: 999px;
        --ph-shadow: 0 12px 26px rgba(18,22,51,.12);
        --ph-font: Outfit, "Segoe UI", sans-serif; --ph-num: Outfit, sans-serif;
        --ph-title-weight: 900; --ph-title-case: uppercase;
    }

    html.theme-social-feed {
        --ph-wall: #ebe5f7; --ph-wall2: #f6f4fb; --ph-bezel: #e3dcf1;
        --ph-bg: #f6f4fb; --ph-screen: #faf8ff; --ph-card: #ffffff; --ph-card2: #f3e8ff;
        --ph-ink: #151022; --ph-muted: #6f6a80; --ph-line: #e7e2f2;
        --ph-primary: #7c3aed; --ph-primary2: #db2777; --ph-accent: #f59e0b; --ph-ok: #16a34a; --ph-btn-fg: #ffffff;
        --ph-nav: rgba(250,248,255,.94); --ph-tab: rgba(255,255,255,.96);
        --ph-radius: 20px; --ph-radius-sm: 14px; --ph-pill: 999px;
        --ph-shadow: 0 8px 24px rgba(124,58,237,.10);
        --ph-font: "Plus Jakarta Sans", "Segoe UI", sans-serif; --ph-num: "Plus Jakarta Sans", sans-serif;
        --ph-title-weight: 800; --ph-title-case: none;
    }

    /* ── Kabuk: global chrome gizle + gövde zemini ───────────────────── */
    html.theme-story-reels body > header, html.theme-story-reels body > footer,
    html.theme-pocket-stories body > header, html.theme-pocket-stories body > footer,
    html.theme-swipe-cards body > header, html.theme-swipe-cards body > footer,
    html.theme-pull-drawer body > header, html.theme-pull-drawer body > footer,
    html.theme-radar-scope body > header, html.theme-radar-scope body > footer,
    html.theme-index-rally body > header, html.theme-index-rally body > footer,
    html.theme-social-feed body > header, html.theme-social-feed body > footer { display: none !important; }
    html[class*="theme-story-reels"] body, html.theme-pocket-stories body, html.theme-swipe-cards body,
    html.theme-pull-drawer body, html.theme-radar-scope body, html.theme-index-rally body, html.theme-social-feed body { background: var(--ph-bg); }
    html.theme-story-reels body > main, html.theme-pocket-stories body > main, html.theme-swipe-cards body > main,
    html.theme-pull-drawer body > main, html.theme-radar-scope body > main, html.theme-index-rally body > main, html.theme-social-feed body > main { padding: 0; background: transparent; }

    /* ── Cihaz çerçevesi ─────────────────────────────────────────────── */
    .ph { color: var(--ph-ink); font-family: var(--ph-font); font-size: 15px; line-height: 1.55; -webkit-font-smoothing: antialiased; }
    .ph :is(h1,h2,h3,h4) { margin: 0; font-weight: var(--ph-title-weight); text-transform: var(--ph-title-case); letter-spacing: -.015em; }
    .ph p { margin: 0; }
    .ph a { color: inherit; }
    .ph-wrap { min-height: 100dvh; }
    .ph-device { display: flex; flex-direction: column; background: var(--ph-bg); color: var(--ph-ink); position: relative; }
    .ph-notch { display: none; }
    .ph-screen { flex: 1 1 auto; display: flex; flex-direction: column; }
    .ph-scroll { flex: 1 1 auto; }

    @media(min-width:720px) {
        .ph-wrap { min-height: 100dvh; display: flex; align-items: center; justify-content: center; padding: 26px 0 34px; background: radial-gradient(720px 460px at 18% 8%, color-mix(in srgb, var(--ph-primary) 26%, transparent), transparent 62%), radial-gradient(660px 420px at 84% 92%, color-mix(in srgb, var(--ph-primary2) 22%, transparent), transparent 60%), linear-gradient(158deg, var(--ph-wall), var(--ph-wall2)); }
        .ph-device { width: 430px; height: min(912px, calc(100vh - 60px)); border: 11px solid var(--ph-bezel); border-radius: 48px; box-shadow: var(--ph-shadow), 0 0 0 2px rgba(255,255,255,.05) inset; overflow: hidden; }
        .ph-device::after { content: ""; position: absolute; inset: 0; pointer-events: none; z-index: 60; background: linear-gradient(122deg, rgba(255,255,255,.08) 0 12%, transparent 34%), radial-gradient(120% 60% at 50% 120%, rgba(0,0,0,.35), transparent 60%); }
        .ph-notch { display: flex; align-items: center; justify-content: space-between; gap: 12px; padding: 9px 22px 4px; font-size: 12px; font-weight: 600; letter-spacing: .02em; color: var(--ph-muted); }
        .ph-notch__sig { display: inline-flex; align-items: center; gap: 6px; }
        .ph-notch__bat { width: 22px; height: 11px; border: 1.4px solid currentColor; border-radius: 3px; position: relative; }
        .ph-notch__bat::before { content: ""; position: absolute; inset: 2px 30% 2px 2px; background: var(--ph-ok); }
        .ph-notch__bat::after { content: ""; position: absolute; right: -3px; top: 3px; width: 2px; height: 5px; background: currentColor; }
        .ph-screen { overflow-y: auto; overscroll-behavior: contain; scrollbar-width: thin; border-radius: 2px; }
    }

    /* ── Uygulama başlığı ────────────────────────────────────────────── */
    .ph-appbar { position: sticky; top: 0; z-index: 40; display: flex; align-items: center; gap: 11px; padding: 12px 16px; background: var(--ph-nav); backdrop-filter: blur(14px) saturate(140%); border-bottom: 1px solid var(--ph-line); }
    .ph-appbar__logo { display: inline-flex; align-items: center; gap: 9px; font-size: 16px; font-weight: 800; letter-spacing: -.02em; text-decoration: none; }
    .ph-appbar__mark { width: 30px; height: 30px; display: grid; place-items: center; border-radius: 11px; background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font-size: 14px; font-weight: 800; }
    .ph-appbar__spacer { flex: 1 1 auto; }
    .ph-appbar__btn { display: inline-flex; align-items: center; gap: 7px; padding: 8px 13px; border-radius: var(--ph-pill); background: var(--ph-card); border: 1px solid var(--ph-line); font-size: 12px; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .ph-appbar__btn--go { background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); border-color: transparent; color: var(--ph-btn-fg, #fff); }
    .ph-appbar__back { width: 32px; height: 32px; display: grid; place-items: center; border-radius: 50%; background: var(--ph-card); border: 1px solid var(--ph-line); text-decoration: none; font-size: 15px; }

    /* ── Alt sekme çubuğu ────────────────────────────────────────────── */
    .ph-tabbar { position: sticky; bottom: 0; z-index: 45; display: grid; grid-template-columns: repeat(5,1fr); padding: 7px 8px calc(7px + env(safe-area-inset-bottom)); background: var(--ph-tab); backdrop-filter: blur(16px) saturate(150%); border-top: 1px solid var(--ph-line); }
    @media(min-width:720px) { .ph-tabbar { position: static; padding-bottom: 12px; } }
    .ph-tab { display: grid; justify-items: center; gap: 3px; padding: 7px 2px; border-radius: var(--ph-radius-sm); font-size: 10px; font-weight: 600; letter-spacing: .01em; color: var(--ph-muted); text-decoration: none; transition: color .18s ease, background .18s ease, transform .18s ease; }
    .ph-tab__ico { font-size: 18px; line-height: 1; transition: transform .16s ease; }
    .ph-tab:hover { color: var(--ph-ink); }
    .ph-tab.is-active { color: var(--ph-primary); background: color-mix(in srgb, var(--ph-primary) 14%, transparent); transform: translateY(-1px); }
    .ph-tab.is-active .ph-tab__ico { transform: scale(1.14); }
    .ph-tab--cta .ph-tab__ico { width: 34px; height: 34px; display: grid; place-items: center; border-radius: 50%; background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font-size: 18px; box-shadow: 0 8px 18px color-mix(in srgb, var(--ph-primary) 45%, transparent); }

    /* ── Tipografi yardımcıları ──────────────────────────────────────── */
    .ph-pad { padding: 16px; }
    .ph-eyebrow { display: inline-flex; align-items: center; gap: 7px; font-size: 10.5px; font-weight: 700; letter-spacing: .16em; text-transform: uppercase; color: var(--ph-primary); }
    .ph-eyebrow::before { content: ""; width: 6px; height: 6px; border-radius: 50%; background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); }
    .ph-h1 { font-size: clamp(26px,7.6vw,32px); line-height: 1.08; }
    .ph-h2 { font-size: 20px; line-height: 1.16; }
    .ph-h3 { font-size: 16px; line-height: 1.25; font-weight: 700; }
    .ph-lead { margin-top: 8px; color: var(--ph-muted); font-size: 14px; }
    .ph-muted { color: var(--ph-muted); }
    .ph-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 6px 10px; font-size: 11.5px; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; color: var(--ph-muted); }
    .ph-btn { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; padding: 12px 18px; border: 1px solid transparent; border-radius: var(--ph-pill); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font: inherit; font-size: 14px; font-weight: 700; text-decoration: none; cursor: pointer; transition: transform .16s ease, filter .16s ease, box-shadow .16s ease; box-shadow: 0 10px 22px color-mix(in srgb, var(--ph-primary) 34%, transparent); }
    .ph-btn:hover { transform: translateY(-2px); filter: brightness(1.06); }
    .ph-btn:active { transform: translateY(0) scale(.985); }
    .ph-btn--ghost { background: var(--ph-card); border-color: var(--ph-line); color: var(--ph-ink); box-shadow: none; }
    .ph-btn--wide { width: 100%; }
    .ph-chips { display: flex; gap: 8px; overflow-x: auto; padding: 2px 16px 6px; scrollbar-width: none; }
    .ph-chips::-webkit-scrollbar { display: none; }
    .ph-chip { flex: 0 0 auto; padding: 8px 14px; border-radius: var(--ph-pill); background: var(--ph-card); border: 1px solid var(--ph-line); font-size: 12.5px; font-weight: 700; text-decoration: none; white-space: nowrap; transition: border-color .18s ease, color .18s ease; }
    .ph-chip:hover { border-color: var(--ph-primary); color: var(--ph-primary); }
    .ph-chip.is-on { background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); border-color: transparent; color: var(--ph-btn-fg, #fff); }

    /* ── Bölüm başlıkları ────────────────────────────────────────────── */
    .ph-section { padding: 20px 0 6px; }
    .ph-section__head { display: flex; align-items: flex-end; justify-content: space-between; gap: 12px; padding: 0 16px 12px; }
    .ph-section__more { font-size: 12px; font-weight: 700; color: var(--ph-primary); text-decoration: none; white-space: nowrap; }

    /* ── Hikâye halkaları ────────────────────────────────────────────── */
    .ph-rings { display: flex; gap: 14px; overflow-x: auto; padding: 4px 16px 10px; scrollbar-width: none; }
    .ph-rings::-webkit-scrollbar { display: none; }
    .ph-ring { flex: 0 0 auto; width: 68px; display: grid; justify-items: center; gap: 6px; text-decoration: none; }
    .ph-ring__av { position: relative; width: 62px; height: 62px; border-radius: 50%; padding: 3px; background: conic-gradient(from 210deg, var(--ph-primary), var(--ph-primary2), var(--ph-accent), var(--ph-primary)); transition: transform .2s ease; }
    .ph-ring__av::after { content: ""; position: absolute; inset: 3px; border-radius: 50%; background: var(--ph-bg); }
    .ph-ring__in { position: relative; z-index: 1; width: 100%; height: 100%; border-radius: 50%; display: grid; place-items: center; background: var(--ph-card2); font-size: 21px; font-weight: 800; color: var(--ph-ink); overflow: hidden; }
    .ph-ring__in img { width: 100%; height: 100%; object-fit: cover; }
    .ph-ring:hover .ph-ring__av { transform: translateY(-3px) scale(1.03); }
    .ph-ring__label { max-width: 68px; font-size: 11px; font-weight: 600; text-align: center; color: var(--ph-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-ring--add .ph-ring__av { background: var(--ph-line); }
    .ph-ring--add .ph-ring__in { color: var(--ph-primary); font-size: 24px; }

    /* ── Reel (tam ekran) kart ───────────────────────────────────────── */
    .ph-reels { display: grid; gap: 14px; padding: 4px 16px 12px; scroll-snap-type: y proximity; }
    .ph-reel { position: relative; display: flex; flex-direction: column; justify-content: flex-end; min-height: 396px; border-radius: var(--ph-radius); overflow: hidden; background: var(--ph-card); border: 1px solid var(--ph-line); text-decoration: none; scroll-snap-align: center; }
    .ph-reel__media { position: absolute; inset: 0; }
    .ph-reel__media img { width: 100%; height: 100%; object-fit: cover; }
    .ph-reel__media--ph { background: linear-gradient(150deg, var(--ph-primary), var(--ph-card2) 62%, var(--ph-primary2)); opacity: .9; }
    .ph-reel::before { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 32%, rgba(0,0,0,.72) 78%); }
    .ph-reel__body { position: relative; z-index: 1; padding: 16px; color: #fff; }
    .ph-reel__tag { display: inline-block; padding: 4px 10px; border-radius: var(--ph-pill); background: rgba(255,255,255,.18); backdrop-filter: blur(6px); font-size: 10.5px; font-weight: 700; letter-spacing: .1em; text-transform: uppercase; }
    .ph-reel__body h3 { margin: 10px 0 6px; font-size: 19px; color: #fff; }
    .ph-reel__body p { color: rgba(255,255,255,.82); font-size: 13px; }
    .ph-reel__side { position: absolute; z-index: 2; right: 12px; bottom: 96px; display: grid; gap: 10px; }
    .ph-reel__act { width: 42px; height: 42px; display: grid; place-items: center; border-radius: 50%; background: rgba(255,255,255,.16); backdrop-filter: blur(8px); color: #fff; font-size: 16px; border: 1px solid rgba(255,255,255,.22); }

    /* ── Izgara / yığın kart ─────────────────────────────────────────── */
    .ph-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; padding: 0 16px 8px; }
    .ph-card { display: flex; flex-direction: column; min-width: 0; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); overflow: hidden; text-decoration: none; box-shadow: var(--ph-shadow); transition: transform .2s ease, border-color .2s ease; }
    .ph-card:hover { transform: translateY(-3px); border-color: var(--ph-primary); }
    .ph-card__media { position: relative; aspect-ratio: 4/3; background: linear-gradient(150deg, var(--ph-primary), var(--ph-card2) 70%, var(--ph-primary2)); }
    .ph-card__media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .ph-card__initial { position: absolute; inset: 0; display: grid; place-items: center; font-size: 34px; font-weight: 800; color: rgba(255,255,255,.72); }
    .ph-card__badge { position: absolute; top: 8px; left: 8px; padding: 4px 9px; border-radius: var(--ph-pill); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font-size: 9.5px; font-weight: 800; letter-spacing: .08em; text-transform: uppercase; }
    .ph-card__rate { position: absolute; bottom: 8px; right: 8px; padding: 4px 9px; border-radius: var(--ph-pill); background: rgba(0,0,0,.55); backdrop-filter: blur(4px); color: #fff; font-size: 11px; font-weight: 700; }
    .ph-card__body { padding: 11px 12px 13px; display: grid; gap: 5px; }
    .ph-card__body h3 { font-size: 14.5px; line-height: 1.25; }
    .ph-card__body p { color: var(--ph-muted); font-size: 12px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-card__foot { display: flex; align-items: center; justify-content: space-between; gap: 8px; margin-top: 3px; padding-top: 9px; border-top: 1px dashed var(--ph-line); font-size: 11px; font-weight: 600; color: var(--ph-muted); }

    /* ── Swipe destesi ───────────────────────────────────────────────── */
    .ph-deck { position: relative; display: grid; gap: 0; padding: 8px 20px 18px; min-height: 470px; }
    .ph-deck__card { position: relative; border-radius: var(--ph-radius); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); overflow: hidden; }
    .ph-deck__card + .ph-deck__card { margin-top: -86px; transform: scale(.965) translateY(6px); opacity: .82; }
    .ph-deck__card + .ph-deck__card + .ph-deck__card { margin-top: -86px; transform: scale(.93) translateY(12px); opacity: .6; }
    .ph-deck__media { position: relative; height: 232px; background: linear-gradient(140deg, var(--ph-primary), var(--ph-card2) 58%, var(--ph-primary2)); }
    .ph-deck__media img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .ph-deck__body { padding: 15px 16px 16px; }
    .ph-deck__body h3 { font-size: 19px; }
    .ph-deck__body p { margin-top: 7px; color: var(--ph-muted); font-size: 13.5px; }
    .ph-deck__facts { display: flex; flex-wrap: wrap; gap: 7px; margin-top: 12px; }
    .ph-fact { padding: 5px 11px; border-radius: var(--ph-pill); background: var(--ph-card2); border: 1px solid var(--ph-line); font-size: 11px; font-weight: 700; }
    .ph-deck__acts { display: flex; justify-content: center; gap: 14px; padding: 6px 0 2px; }
    .ph-deck__btn { width: 54px; height: 54px; display: grid; place-items: center; border-radius: 50%; border: 1px solid var(--ph-line); background: var(--ph-card); color: var(--ph-ink); font-size: 20px; text-decoration: none; transition: transform .16s ease, background .16s ease; }
    .ph-deck__btn:hover { transform: translateY(-3px) scale(1.04); }
    .ph-deck__btn--no { color: #ff5d5d; }
    .ph-deck__btn--yes { background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); border-color: transparent; color: var(--ph-btn-fg, #fff); box-shadow: 0 12px 26px color-mix(in srgb, var(--ph-primary) 42%, transparent); }
    .ph-deck__hint { text-align: center; font-size: 11px; font-weight: 700; letter-spacing: .14em; text-transform: uppercase; color: var(--ph-muted); }

    /* ── Çekmece (pull-drawer): açık arama paneli satırları ───────────── */
    .ph-pull { display: grid; gap: 2px; padding: 6px; border-radius: var(--ph-radius); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); }
    .ph-pull a { display: flex; align-items: center; gap: 11px; padding: 11px 12px; border-radius: var(--ph-radius-sm); background: transparent; text-decoration: none; transition: background .16s ease, transform .16s ease; }
    .ph-pull a:hover { background: var(--ph-card2); transform: translateX(3px); }
    .ph-pull__ico { flex: 0 0 auto; width: 34px; height: 34px; display: grid; place-items: center; border-radius: 11px; background: var(--ph-card2); color: var(--ph-primary); font-size: 15px; font-weight: 800; }
    .ph-pull__txt { flex: 1 1 auto; min-width: 0; }
    .ph-pull__txt strong { display: block; font-size: 14px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-pull__txt span { display: block; font-size: 11.5px; color: var(--ph-muted); }
    .ph-pull kbd { flex: 0 0 auto; padding: 3px 7px; border-radius: 6px; border: 1px solid var(--ph-line); background: var(--ph-screen); font-family: var(--ph-num); font-size: 10px; color: var(--ph-muted); }

    /* ── Radar kadranı + sinyal listesi (radar-scope) ────────────────── */
    .ph-dial { position: relative; width: min(300px, 78vw); aspect-ratio: 1; margin: 10px auto 16px; border-radius: 50%; border: 1px solid var(--ph-line); background: repeating-radial-gradient(circle at 50% 50%, transparent 0 22px, color-mix(in srgb, var(--ph-primary) 14%, transparent) 22px 23px), var(--ph-screen); }
    .ph-dial::before { content: ""; position: absolute; inset: 0; border-radius: 50%; background: conic-gradient(from 0deg, color-mix(in srgb, var(--ph-primary) 38%, transparent), transparent 26%); animation: ph-sweep 4.6s linear infinite; }
    .ph-dial__hub { position: absolute; top: 50%; left: 50%; transform: translate(-50%,-50%); width: 64px; height: 64px; border-radius: 50%; display: grid; place-items: center; text-align: center; gap: 0; background: var(--ph-card); border: 1px solid var(--ph-primary); color: var(--ph-primary); font-size: 9.5px; font-weight: 700; letter-spacing: .12em; text-decoration: none; z-index: 2; }
    .ph-pip { position: absolute; top: 50%; left: 50%; z-index: 3; width: 72px; margin: -24px 0 0 -36px; display: grid; justify-items: center; gap: 3px; text-decoration: none; color: var(--ph-ink); font-size: 9.5px; font-weight: 600; text-align: center; transition: transform .18s ease; }
    .ph-pip:hover { transform: scale(1.12); color: var(--ph-primary); }
    .ph-pip__dot { width: 11px; height: 11px; border-radius: 50%; background: var(--ph-primary); box-shadow: 0 0 12px color-mix(in srgb, var(--ph-primary) 70%, transparent); }
    .ph-pip span { overflow: hidden; text-overflow: ellipsis; white-space: nowrap; max-width: 72px; color: var(--ph-muted); }
    .ph-blips { display: grid; gap: 9px; }
    .ph-blip { display: grid; grid-template-columns: auto 1fr auto; align-items: center; gap: 11px; padding: 11px 12px; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); text-decoration: none; transition: border-color .18s ease, box-shadow .18s ease; }
    .ph-blip:hover { border-color: var(--ph-primary); box-shadow: 0 0 18px color-mix(in srgb, var(--ph-primary) 26%, transparent); }
    .ph-blip__dot { position: relative; width: 12px; height: 12px; border-radius: 50%; background: var(--ph-primary); }
    .ph-blip__dot::after { content: ""; position: absolute; inset: -5px; border-radius: 50%; border: 1px solid var(--ph-primary); animation: ph-ping 1.9s ease-out infinite; }
    .ph-blip__txt { min-width: 0; }
    .ph-blip__txt strong { display: block; font-size: 13.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-blip__txt span { display: block; font-size: 11px; color: var(--ph-muted); }
    .ph-blip__sig { display: flex; align-items: flex-end; gap: 2px; height: 16px; }
    .ph-blip__sig i { width: 3px; background: var(--ph-primary); opacity: .55; }
    .ph-blip__sig i:nth-child(1) { height: 5px; } .ph-blip__sig i:nth-child(2) { height: 8px; } .ph-blip__sig i:nth-child(3) { height: 11px; } .ph-blip__sig i:nth-child(4) { height: 15px; opacity: 1; }

    /* ── Rampa listesi (index-rally): numaralı sıralama + harf başlıkları */
    .ph-rally { display: grid; gap: 10px; counter-reset: rally; }
    .ph-rally a { position: relative; display: flex; align-items: center; gap: 12px; padding: 12px 14px 12px 52px; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); text-decoration: none; transition: transform .16s ease, border-color .16s ease; }
    .ph-rally a::before { counter-increment: rally; content: counter(rally, decimal-leading-zero); position: absolute; left: 13px; top: 50%; transform: translateY(-50%); font-family: var(--ph-num); font-size: 17px; font-weight: 900; color: var(--ph-primary); }
    .ph-rally a:hover { transform: translateX(3px); border-color: var(--ph-primary); }
    .ph-rally__txt { flex: 1 1 auto; min-width: 0; }
    .ph-rally__txt strong { display: block; font-size: 14.5px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-rally__txt span { display: block; font-size: 11.5px; color: var(--ph-muted); }
    .ph-rally__go { flex: 0 0 auto; width: 30px; height: 30px; display: grid; place-items: center; border-radius: 50%; background: var(--ph-card2); color: var(--ph-ink); font-size: 14px; font-weight: 800; }
    .ph-letter { position: sticky; top: 0; z-index: 5; display: inline-grid; place-items: center; width: 34px; height: 34px; margin: 8px 0 6px; border-radius: 10px; background: var(--ph-ink); color: var(--ph-bg); font-family: var(--ph-num); font-size: 16px; font-weight: 900; }
    @keyframes ph-sweep { to { transform: rotate(360deg); } }
    @keyframes ph-ping { 0% { transform: scale(.6); opacity: .9; } 100% { transform: scale(1.7); opacity: 0; } }

    /* ── Liste satırı, panel, boş durum ──────────────────────────────── */
    .ph-list { display: grid; gap: 10px; padding: 0 16px 10px; }
    .ph-list--card { padding: 0 16px 10px; }
    .ph-row { display: flex; align-items: center; gap: 12px; padding: 11px 12px; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); text-decoration: none; transition: border-color .18s ease, transform .18s ease; }
    .ph-row:hover { border-color: var(--ph-primary); transform: translateX(2px); }
    .ph-row__av { flex: 0 0 auto; width: 46px; height: 46px; display: grid; place-items: center; border-radius: 15px; background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font-size: 17px; font-weight: 800; overflow: hidden; }
    .ph-row__av img { width: 100%; height: 100%; object-fit: cover; }
    .ph-row__txt { flex: 1 1 auto; min-width: 0; }
    .ph-row__txt strong { display: block; font-size: 14.5px; line-height: 1.3; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-row__txt span { display: block; margin-top: 2px; font-size: 12px; color: var(--ph-muted); overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .ph-row__go { flex: 0 0 auto; color: var(--ph-muted); font-size: 16px; }
    .ph-empty { display: grid; gap: 8px; justify-items: center; padding: 26px 16px; border-radius: var(--ph-radius-sm); border: 1px dashed var(--ph-line); color: var(--ph-muted); font-size: 13.5px; text-align: center; }
    .ph-empty::before { content: "◎"; font-size: 24px; color: var(--ph-primary); }
    .ph-sheet { margin: 0 16px 14px; border-radius: var(--ph-radius); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); overflow: hidden; }
    .ph-sheet__head { display: flex; align-items: center; justify-content: space-between; gap: 10px; padding: 13px 14px; border-bottom: 1px solid var(--ph-line); }
    .ph-sheet__head h2 { font-size: 15.5px; }
    .ph-sheet__body { padding: 14px; display: grid; gap: 10px; }

    /* ── İstatistik / arama / CTA ────────────────────────────────────── */
    .ph-stats { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; padding: 6px 16px 14px; }
    .ph-stats > div { padding: 12px 14px; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); }
    .ph-stats b { display: block; font-family: var(--ph-num); font-size: 24px; font-weight: 800; background: linear-gradient(120deg, var(--ph-primary), var(--ph-primary2)); -webkit-background-clip: text; background-clip: text; color: transparent; }
    .ph-stats span { display: block; margin-top: 2px; font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase; color: var(--ph-muted); }
    .ph-search { display: flex; align-items: center; gap: 10px; margin: 6px 16px 14px; padding: 6px 6px 6px 16px; border-radius: var(--ph-pill); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); }
    .ph-search input { flex: 1 1 auto; min-width: 0; border: 0; background: transparent; color: var(--ph-ink); font: inherit; font-size: 14.5px; padding: 10px 0; }
    .ph-search input::placeholder { color: var(--ph-muted); }
    .ph-search input:focus { outline: none; }
    .ph-search button { flex: 0 0 auto; min-width: 44px; height: 40px; padding: 0 16px; border: 0; border-radius: var(--ph-pill); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font: inherit; font-size: 13.5px; font-weight: 800; cursor: pointer; }
    .ph-hero { position: relative; padding: 22px 16px 8px; overflow: hidden; }
    .ph-hero::before { content: ""; position: absolute; inset: -40% -30% auto; height: 340px; background: radial-gradient(60% 60% at 30% 40%, color-mix(in srgb, var(--ph-primary) 42%, transparent), transparent 70%), radial-gradient(60% 60% at 74% 30%, color-mix(in srgb, var(--ph-primary2) 34%, transparent), transparent 70%); filter: blur(6px); }
    .ph-hero > * { position: relative; }
    .ph-cta { display: grid; gap: 10px; margin: 6px 16px 16px; padding: 18px; border-radius: var(--ph-radius); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); box-shadow: var(--ph-shadow); }
    .ph-cta h2 { font-size: 19px; color: var(--ph-btn-fg, #fff); }
    .ph-cta p { color: color-mix(in srgb, var(--ph-btn-fg, #fff) 88%, transparent); font-size: 13.5px; }
    .ph-cta .ph-btn { justify-self: start; margin-top: 4px; background: #fff; color: var(--ph-ink); box-shadow: none; }
    .ph-promo { margin: 0 16px 16px; padding: 16px; border-radius: var(--ph-radius); border: 1px dashed var(--ph-primary); background: color-mix(in srgb, var(--ph-primary) 12%, var(--ph-card)); }
    .ph-promo h2 { font-size: 17px; }
    .ph-promo p { margin: 7px 0 12px; color: var(--ph-muted); font-size: 13px; }

    /* ── Alt sayfa: başlık, filtre, form, sayfalama ──────────────────── */
    .ph-pagehero { padding: 16px 16px 12px; background: var(--ph-screen); border-bottom: 1px solid var(--ph-line); }
    .ph-crumb { display: flex; flex-wrap: wrap; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ph-muted); }
    .ph-crumb a { color: var(--ph-primary); text-decoration: none; }
    .ph-pagehero h1 { margin: 10px 0 6px; font-size: 24px; line-height: 1.12; }
    .ph-pagehero p { color: var(--ph-muted); font-size: 13.5px; }
    .ph-filter { display: grid; gap: 10px; padding: 14px 16px; background: var(--ph-card); border-bottom: 1px solid var(--ph-line); }
    .ph-field { display: grid; gap: 6px; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ph-muted); }
    .ph-field :is(input,select,textarea) { width: 100%; min-width: 0; padding: 12px 13px; border: 1px solid var(--ph-line); border-radius: var(--ph-radius-sm); background: var(--ph-screen); color: var(--ph-ink); font: inherit; font-size: 14.5px; letter-spacing: 0; text-transform: none; }
    .ph-field :is(input,select,textarea):focus { outline: 2px solid var(--ph-primary); outline-offset: -1px; }
    .ph-note { margin: 12px 16px; padding: 12px 14px; border-radius: var(--ph-radius-sm); border-left: 4px solid var(--ph-primary); background: var(--ph-card); color: var(--ph-ink); font-size: 13.5px; }
    .ph-pagination { display: flex; flex-wrap: wrap; align-items: center; justify-content: center; gap: 6px; padding: 12px 16px 22px; }
    .ph-pagination :is(a,span) { min-width: 38px; padding: 9px 12px; border-radius: var(--ph-pill); border: 1px solid var(--ph-line); background: var(--ph-card); font-size: 13px; font-weight: 700; text-decoration: none; }
    .ph-pagination [aria-current="page"], .ph-pagination .current { background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); border-color: transparent; color: var(--ph-btn-fg, #fff); }

    /* ── Metin / içerik ──────────────────────────────────────────────── */
    .ph-prose { color: var(--ph-ink); font-size: 15px; line-height: 1.75; }
    .ph-prose :is(h2,h3) { margin: 18px 0 8px; font-size: 18px; }
    .ph-prose p + p { margin-top: 12px; }
    .ph-prose :is(ol,ul) { margin: 12px 0; padding-left: 22px; list-style: revert; }
    .ph-prose a { color: var(--ph-primary); text-decoration: underline; }
    .ph-prose img { max-width: 100%; height: auto; border-radius: var(--ph-radius-sm); }
    .ph-prose table { width: 100%; border-collapse: collapse; font-size: 13.5px; }
    .ph-prose :is(th,td) { border: 1px solid var(--ph-line); padding: 9px 10px; text-align: left; }
    .ph-copy { color: var(--ph-muted); font-size: 14px; line-height: 1.7; }
    .ph-copy p + p { margin-top: 10px; }
    .ph-article { padding: 16px; }
    .ph-article__img { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--ph-radius); }
    .ph-faq { border-radius: var(--ph-radius-sm); border: 1px solid var(--ph-line); background: var(--ph-card); margin-bottom: 8px; overflow: hidden; }
    .ph-faq summary { padding: 13px 14px; font-size: 14px; font-weight: 700; cursor: pointer; list-style: none; }
    .ph-faq summary::-webkit-details-marker { display: none; }
    .ph-faq summary::after { content: "＋"; float: right; color: var(--ph-primary); font-weight: 800; }
    .ph-faq[open] summary::after { content: "－"; }
    .ph-faq p { padding: 0 14px 13px; color: var(--ph-muted); font-size: 13.5px; }
    .ph-plan { border-radius: var(--ph-radius); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); padding: 16px; display: grid; gap: 10px; }
    .ph-plan--featured { border-color: var(--ph-primary); background: linear-gradient(180deg, color-mix(in srgb, var(--ph-primary) 14%, var(--ph-card)), var(--ph-card)); }
    .ph-plan h2 { font-size: 18px; }
    .ph-plan__price { font-family: var(--ph-num); font-size: 27px; font-weight: 800; color: var(--ph-primary); }
    .ph-plan ul { display: grid; gap: 7px; padding: 0; margin: 0; list-style: none; font-size: 13.5px; }
    .ph-plan li { position: relative; padding-left: 20px; color: var(--ph-muted); }
    .ph-plan li::before { content: "✓"; position: absolute; left: 0; color: var(--ph-ok); font-weight: 800; }
    .ph-plans { display: grid; gap: 12px; padding: 6px 16px 20px; }

    /* ── Firma detay ─────────────────────────────────────────────────── */
    .ph-detail__cover { position: relative; height: 210px; overflow: hidden; background: linear-gradient(140deg, var(--ph-primary), var(--ph-card2) 60%, var(--ph-primary2)); }
    .ph-detail__cover img { position: absolute; inset: 0; width: 100%; height: 100%; object-fit: cover; }
    .ph-detail__cover::after { content: ""; position: absolute; inset: 0; background: linear-gradient(180deg, transparent 30%, color-mix(in srgb, var(--ph-bg) 88%, transparent)); }
    .ph-detail__id { position: relative; z-index: 1; margin-top: -58px; padding: 0 16px; display: grid; gap: 10px; }
    .ph-detail__id h1 { font-size: 24px; line-height: 1.12; }
    .ph-avatar { width: 68px; height: 68px; border-radius: 22px; display: grid; place-items: center; background: var(--ph-card); border: 3px solid var(--ph-bg); font-size: 26px; font-weight: 800; color: var(--ph-primary); overflow: hidden; box-shadow: var(--ph-shadow); }
    .ph-avatar img { width: 100%; height: 100%; object-fit: cover; }
    .ph-quick { display: grid; grid-template-columns: repeat(2,1fr); gap: 10px; padding: 12px 16px 4px; }
    .ph-quick a { display: inline-flex; align-items: center; justify-content: center; gap: 8px; min-height: 46px; border-radius: var(--ph-radius-sm); background: var(--ph-card); border: 1px solid var(--ph-line); font-size: 13.5px; font-weight: 700; text-decoration: none; }
    .ph-quick a:first-child { background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); border-color: transparent; color: var(--ph-btn-fg, #fff); }
    .ph-dl { display: grid; gap: 0; margin: 0; padding: 4px 16px 12px; }
    .ph-dl > div { display: flex; justify-content: space-between; gap: 12px; padding: 10px 0; border-bottom: 1px dashed var(--ph-line); font-size: 13.5px; }
    .ph-dl dt { color: var(--ph-muted); font-weight: 600; }
    .ph-dl dd { margin: 0; font-weight: 700; text-align: right; overflow-wrap: anywhere; }
    .ph-detail .td-section, .ph-detail .td-side-card { margin: 0 16px 14px; padding: 15px; border-radius: var(--ph-radius); background: var(--ph-card); border: 1px solid var(--ph-line); box-shadow: var(--ph-shadow); }
    .ph-detail .td-section-head { display: flex; align-items: baseline; flex-wrap: wrap; gap: 8px; margin-bottom: 12px; padding-bottom: 10px; border-bottom: 1px solid var(--ph-line); }
    .ph-detail .td-index { font-family: var(--ph-num); font-size: 12px; font-weight: 800; color: var(--ph-primary); }
    .ph-detail .td-section-head h2, .ph-detail .td-side-card h2 { font-size: 16.5px; }
    .ph-detail .td-side-label { font-size: 11px; font-weight: 800; letter-spacing: .1em; text-transform: uppercase; color: var(--ph-primary); }
    .ph-detail .td-section-head > a, .ph-detail .td-count { margin-left: auto; font-size: 11.5px; font-weight: 700; color: var(--ph-primary); text-decoration: none; }
    .ph-detail .td-prose { font-size: 14.5px; line-height: 1.7; color: var(--ph-ink); }
    .ph-detail .td-prose p + p { margin-top: 10px; }
    .ph-detail .td-card-grid { display: grid; gap: 10px; }
    .ph-detail .td-card { display: block; padding: 12px; border-radius: var(--ph-radius-sm); background: var(--ph-card2); border: 1px solid var(--ph-line); text-decoration: none; }
    .ph-detail .td-card-image { width: 100%; aspect-ratio: 16/9; object-fit: cover; border-radius: var(--ph-radius-sm); margin-bottom: 10px; }
    .ph-detail .td-card h3 { font-size: 14.5px; margin-top: 4px; }
    .ph-detail .td-card p, .ph-detail .td-side-card p { color: var(--ph-muted); font-size: 12.5px; }
    .ph-detail .td-card small, .ph-detail .td-price { display: block; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: var(--ph-muted); }
    .ph-detail .td-price { margin-top: 6px; font-size: 14px; color: var(--ph-primary); }
    .ph-detail .td-gallery { display: grid; grid-template-columns: repeat(2,1fr); gap: 8px; }
    .ph-detail .td-gallery img { width: 100%; aspect-ratio: 4/3; object-fit: cover; border-radius: var(--ph-radius-sm); }
    .ph-detail .td-map { aspect-ratio: 4/3; border-radius: var(--ph-radius-sm); overflow: hidden; border: 1px solid var(--ph-line); }
    .ph-detail .td-map iframe { width: 100%; height: 100%; border: 0; }
    .ph-detail .td-review { padding: 11px 0; border-bottom: 1px dashed var(--ph-line); font-size: 13.5px; }
    .ph-detail .td-review > div { display: flex; justify-content: space-between; gap: 10px; }
    .ph-detail .td-review span, .ph-detail .td-empty { color: var(--ph-muted); font-size: 11.5px; }
    .ph-detail .td-stars { color: var(--ph-accent); font-size: 13px; margin: 3px 0; }
    .ph-detail .td-review-form { display: grid; gap: 9px; }
    .ph-detail .td-review-form :is(input,select,textarea) { width: 100%; padding: 11px 12px; border: 1px solid var(--ph-line); border-radius: var(--ph-radius-sm); background: var(--ph-screen); color: var(--ph-ink); font: inherit; font-size: 14px; }
    .ph-detail .td-review-form button { min-height: 46px; border: 0; border-radius: var(--ph-pill); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font: inherit; font-weight: 800; cursor: pointer; }
    .ph-detail .td-message { padding: 11px 12px; border-radius: var(--ph-radius-sm); background: var(--ph-card2); font-size: 13px; }
    .ph-detail .td-faq { border-bottom: 1px dashed var(--ph-line); }
    .ph-detail .td-faq summary { padding: 10px 0; font-size: 14px; font-weight: 700; cursor: pointer; }
    .ph-detail .td-faq p { padding: 0 0 10px; color: var(--ph-muted); font-size: 13px; }
    .ph-detail .td-actions { display: grid; gap: 8px; }
    .ph-detail .td-actions a { display: flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; border-radius: var(--ph-radius-sm); background: var(--ph-card2); border: 1px solid var(--ph-line); font-size: 13.5px; font-weight: 700; text-decoration: none; }
    .ph-detail .td-claim > a { display: flex; align-items: center; justify-content: center; gap: 8px; min-height: 44px; border-radius: var(--ph-pill); background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: var(--ph-btn-fg, #fff); font-size: 13.5px; font-weight: 800; text-decoration: none; }
    .ph-detail .td-claim small { display: block; margin-top: 8px; color: var(--ph-muted); font-size: 11.5px; }
    .ph-detail .td-related a { display: flex; flex-direction: column; gap: 3px; padding: 10px 0; border-bottom: 1px dashed var(--ph-line); text-decoration: none; }
    .ph-detail .td-related span { color: var(--ph-muted); font-size: 12px; }
    .ph-detail .td-share { margin-top: 12px; }
    .ph-detail .td-linebreak { white-space: pre-line; }

    /* ── Tema bazlı karakter farkları ────────────────────────────────── */
    html.theme-story-reels .ph-card, html.theme-story-reels .ph-row { border-color: var(--ph-line); }
    html.theme-story-reels .ph-section__head h2, html.theme-story-reels .ph-h1 { background: linear-gradient(112deg, #fff, var(--ph-accent)); -webkit-background-clip: text; background-clip: text; color: transparent; }
    html.theme-story-reels .ph-reels { scroll-snap-type: y mandatory; }
    html.theme-story-reels .ph-tab__ico { filter: drop-shadow(0 0 10px color-mix(in srgb, var(--ph-primary) 55%, transparent)); }

    html.theme-pocket-stories .ph-device { border-color: #cfdfe1; }
    html.theme-pocket-stories .ph-card, html.theme-pocket-stories .ph-row, html.theme-pocket-stories .ph-sheet { box-shadow: 0 6px 16px rgba(14,42,46,.08); }
    html.theme-pocket-stories .ph-reel::before { background: linear-gradient(180deg, transparent 26%, rgba(6,26,29,.78) 80%); }
    html.theme-pocket-stories .ph-ring__av { background: conic-gradient(from 200deg, var(--ph-primary), var(--ph-accent), var(--ph-primary2), var(--ph-primary)); }
    html.theme-pocket-stories .ph-tab.is-active { background: color-mix(in srgb, var(--ph-primary) 12%, transparent); }

    html.theme-swipe-cards .ph-h1, html.theme-swipe-cards .ph-section__head h2 { letter-spacing: -.02em; }
    html.theme-swipe-cards .ph-card, html.theme-swipe-cards .ph-deck__card, html.theme-swipe-cards .ph-sheet { border-width: 1px; border-color: var(--ph-line); }
    html.theme-swipe-cards .ph-chip { border-radius: 8px; }
    html.theme-swipe-cards .ph-tab.is-active { background: color-mix(in srgb, var(--ph-primary) 16%, transparent); }
    html.theme-swipe-cards .ph-reel__tag { background: color-mix(in srgb, var(--ph-primary) 78%, black); }

    html.theme-pull-drawer .ph-device { border-color: #dcc9a8; }
    html.theme-pull-drawer .ph-h1 { font-weight: 900; letter-spacing: -.03em; }
    html.theme-pull-drawer .ph-pull { border-radius: 22px; }
    html.theme-pull-drawer .ph-btn { box-shadow: 0 8px 18px rgba(38,23,12,.22); }
    html.theme-pull-drawer .ph-card, html.theme-pull-drawer .ph-row { border-color: var(--ph-line); }

    html.theme-radar-scope .ph-h1, html.theme-radar-scope .ph-section__head h2 { font-weight: 600; letter-spacing: .02em; }
    html.theme-radar-scope .ph-btn { text-transform: uppercase; letter-spacing: .08em; font-size: 12.5px; }
    html.theme-radar-scope .ph-search, html.theme-radar-scope .ph-card, html.theme-radar-scope .ph-row { border-radius: 8px; }
    html.theme-radar-scope .ph-tab.is-active { color: var(--ph-primary); background: color-mix(in srgb, var(--ph-primary) 10%, transparent); box-shadow: inset 0 0 0 1px color-mix(in srgb, var(--ph-primary) 40%, transparent); }
    html.theme-radar-scope .ph-eyebrow { color: var(--ph-accent); }

    html.theme-index-rally .ph-h1 { font-weight: 900; letter-spacing: -.02em; }
    html.theme-index-rally .ph-letter { border-radius: 12px; background: linear-gradient(135deg, var(--ph-primary), var(--ph-primary2)); color: #fff; }
    html.theme-index-rally .ph-rally a { border-width: 1.5px; }
    html.theme-index-rally .ph-tab.is-active { font-weight: 800; }
    html.theme-index-rally .ph-card, html.theme-index-rally .ph-row { border-width: 1.5px; }

    /* ── Ortak kabuk içeriği (Tailwind sayfalarının çerçeveye alınması) ─ */
    .ph-shell-content { display: flex; flex-direction: column; gap: 0; }
    .ph-shell-content > *:first-child { border-top: 0; }
    html.theme-story-reels .ph-shell-content :is(.rounded-xl,.rounded-2xl,.rounded-lg,.rounded-full,.rounded-3xl) { border-radius: var(--ph-radius-sm) !important; }
    .ph-shell-content :is(input,select,textarea) { font-family: var(--ph-font); border-radius: var(--ph-radius-sm) !important; background: var(--ph-screen); color: var(--ph-ink); }
    .ph-shell-content :is(input,select,textarea):focus { outline: 2px solid var(--ph-primary); outline-offset: -1px; }
    .ph-shell-content h1, .ph-shell-content h2, .ph-shell-content h3 { font-family: var(--ph-font); color: var(--ph-ink); text-transform: var(--ph-title-case); }
    .ph-shell-content p, .ph-shell-content li, .ph-shell-content span, .ph-shell-content label, .ph-shell-content small { color: var(--ph-ink); }
    .ph-shell-content .bg-white { background: var(--ph-card) !important; border: 1px solid var(--ph-line); }
    .ph-shell-content :is(a,button).rounded-xl, .ph-shell-content button[type=submit] { font-family: var(--ph-font); }
    .ph-shell-content .text-slate-500, .ph-shell-content .text-gray-500, .ph-shell-content .text-slate-600 { color: var(--ph-muted) !important; }
    .ph-shell-content :is(.shadow,.shadow-sm,.shadow-md,.shadow-lg) { box-shadow: var(--ph-shadow) !important; }
    .ph-shell-content { padding-bottom: 8px; }

    /* ── Hareket / baskı ─────────────────────────────────────────────── */
    @media(prefers-reduced-motion:reduce) {
        .ph :is(.ph-card,.ph-row,.ph-btn,.ph-ring,.ph-deck__btn,.ph-tab,.ph-pull a,.ph-blip,.ph-rally a,.ph-pip) { transition: none; }
        .ph-reels { scroll-snap-type: none; }
        .ph-dial::before, .ph-blip__dot::after { animation: none; }
    }
    @media print {
        html[class] body > main { padding: 0; }
        .ph-appbar, .ph-tabbar, .ph-notch { display: none !important; }
        .ph-device { border: 0; height: auto; width: 100%; box-shadow: none; }
    }
</style>
