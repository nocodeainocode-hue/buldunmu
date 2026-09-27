<style>
    html.theme-cinematic-atlas body > header,
    html.theme-cinematic-atlas body > footer { display: none !important; }
    html.theme-cinematic-atlas body > main { background: #f1eadb; color: #211d19; }
    .cinema { --ink:#17282d; --paper:#f1eadb; --card:#fffaf0; --rust:#bd542e; --gold:#e8aa62; --line:#d8c9b7; --muted:#6c625a; color:#211d19; font:15px/1.55 Arial,sans-serif; }
    .cinema * { box-sizing:border-box; }
    .cinema a { color:inherit; }
    .cinema a:hover { color:var(--rust); }
    .cinema-wrap { width:min(100% - 48px,1340px); margin-inline:auto; }
    .cinema-kicker { color:var(--rust); font-size:11px; font-weight:900; letter-spacing:.24em; text-transform:uppercase; }
    .cinema-title { font:700 clamp(42px,7vw,104px)/.92 Georgia,serif; letter-spacing:-.075em; }
    .cinema-small-title { font:700 clamp(32px,4vw,60px)/1 Georgia,serif; letter-spacing:-.06em; }
    .cinema-btn { display:inline-flex; align-items:center; justify-content:center; gap:12px; min-height:48px; padding:11px 19px; background:var(--rust); color:#fff !important; font-size:12px; font-weight:900; letter-spacing:.08em; text-decoration:none; text-transform:uppercase; transition:transform .2s ease,background .2s ease; cursor:pointer; border:1px solid var(--rust); }
    .cinema-btn:hover { background:#983e20; transform:translateY(-2px); }
    .cinema-btn--ghost { background:transparent; color:var(--ink) !important; border-color:var(--ink); }
    .cinema-btn--ghost:hover { background:var(--ink); color:#fff !important; }
    .cinema-header { background:var(--ink); color:#f9ecd7; }
    .cinema-header__top { border-bottom:1px solid rgba(255,255,255,.18); font-size:10px; font-weight:800; letter-spacing:.2em; text-transform:uppercase; }
    .cinema-header__top .cinema-wrap { display:flex; justify-content:space-between; gap:15px; padding-block:10px; }
    .cinema-header__top a { color:#f9ecd7; }
    .cinema-header__main { display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:20px; padding-block:23px; }
    .cinema-brand { color:#fff8eb !important; font:700 clamp(25px,3vw,40px)/1 Georgia,serif; letter-spacing:-.055em; text-decoration:none; }
    .cinema-brand span { color:var(--gold); }
    .cinema-brand small { display:block; margin-top:8px; color:#c7beb0; font:700 9px Arial,sans-serif; letter-spacing:.24em; text-transform:uppercase; }
    .cinema-nav { display:flex; flex-wrap:wrap; align-items:center; gap:7px 21px; font-size:12px; font-weight:800; text-transform:uppercase; letter-spacing:.08em; }
    .cinema-nav a { color:#fff8eb; text-decoration:none; }
    .cinema-nav a:last-child { border:1px solid var(--gold); padding:9px 12px; color:var(--gold); }
    .cinema-nav a:hover { color:var(--gold); }
    .cinema-film { position:relative; height:36px; overflow:hidden; background:#bd542e; color:#fff8eb; border-block:2px solid #331f1c; }
    .cinema-film::before,.cinema-film::after { content:""; position:absolute; left:0; right:0; height:5px; background:repeating-linear-gradient(90deg,#f1eadb 0 14px,transparent 14px 37px); }
    .cinema-film::before { top:2px; } .cinema-film::after { bottom:2px; }
    .cinema-film__track { display:flex; width:max-content; gap:42px; padding:9px 0 0 14px; font-size:10px; font-weight:900; letter-spacing:.3em; white-space:nowrap; text-transform:uppercase; animation:cinema-roll 35s linear infinite; }
    @keyframes cinema-roll { to { transform:translateX(-50%); } }
    .cinema-footer { border-top:8px solid var(--rust); background:var(--ink); color:#f9ecd7; }
    .cinema-footer__main { display:grid; grid-template-columns:minmax(0,1.5fr) repeat(2,minmax(0,1fr)); gap:35px; padding-block:45px; }
    .cinema-footer h2 { font:700 28px Georgia,serif; }
    .cinema-footer h3 { margin-bottom:11px; color:var(--gold); font-size:10px; font-weight:900; letter-spacing:.2em; text-transform:uppercase; }
    .cinema-footer p { max-width:360px; margin-top:11px; color:#b7b5ad; font-size:12px; }
    .cinema-footer a { display:block; width:fit-content; margin:7px 0; color:#f9ecd7; font-size:12px; text-decoration:none; }
    .cinema-footer__bottom { display:flex; justify-content:space-between; gap:12px; border-top:1px solid rgba(255,255,255,.16); padding-block:16px; color:#b7b5ad; font-size:11px; }
    .cinema-shell-content { min-height:50vh; background:var(--paper); }
    .cinema-shell-content :is(.rounded-full,.rounded-3xl,.rounded-2xl,.rounded-xl,.rounded-lg,.rounded-md) { border-radius:2px !important; }
    .cinema-shell-content :is(.shadow,.shadow-sm,.shadow-md,.shadow-lg,.shadow-xl,.shadow-2xl) { box-shadow:none !important; }
    .cinema-shell-content :is(input,select,textarea,button) { border-radius:1px !important; }
    .cinema-shell-content h1,.cinema-shell-content h2,.cinema-shell-content h3 { font-family:Georgia,serif; }
    .cinema-hero { position:relative; isolation:isolate; overflow:hidden; background:#17282d; color:#fff8eb; }
    .cinema-hero::before { content:""; position:absolute; z-index:-1; inset:-35% -10% -30% 35%; background:radial-gradient(circle at 62% 45%,rgba(255,230,183,.88) 0 8%,rgba(232,170,98,.44) 16%,rgba(189,84,46,.25) 29%,transparent 56%); transform:rotate(-17deg); }
    .cinema-hero::after { content:""; position:absolute; z-index:-1; inset:0; opacity:.21; background:repeating-linear-gradient(135deg,transparent 0 120px,rgba(255,255,255,.12) 121px 122px); }
    .cinema-hero .cinema-wrap { position:relative; padding-block:65px 82px; }
    .cinema-hero .cinema-kicker { color:var(--gold); }
    .cinema-hero h1 { max-width:850px; margin:16px 0 22px; color:#fff8eb; text-wrap:balance; }
    .cinema-hero p { max-width:560px; color:#e3d8c9; font-size:16px; }
    .cinema-hero__actions { display:flex; flex-wrap:wrap; gap:12px; margin-top:31px; }
    .cinema-hero__actions .cinema-btn--ghost { border-color:#f9ecd7; color:#f9ecd7 !important; }
    .cinema-hero__actions .cinema-btn--ghost:hover { background:#f9ecd7; color:var(--ink) !important; }
    .cinema-hero__frame { position:absolute; right:2%; top:10%; width:clamp(180px,27vw,365px); aspect-ratio:1; border:2px solid rgba(255,248,235,.36); border-radius:50%; box-shadow:0 0 0 34px rgba(255,255,255,.045),0 0 0 67px rgba(255,255,255,.035),0 0 0 100px rgba(255,255,255,.025); pointer-events:none; }
    .cinema-hero__frame::before,.cinema-hero__frame::after { content:""; position:absolute; inset:15%; border:1px solid rgba(255,248,235,.46); border-radius:50%; }
    .cinema-hero__frame::after { inset:40%; background:rgba(255,248,235,.55); }
    .cinema-search { display:flex; max-width:750px; margin-top:32px; border:5px solid rgba(255,248,235,.2); background:#fff8eb; box-shadow:0 18px 35px rgba(0,0,0,.18); }
    .cinema-search input { flex:1; min-width:0; padding:16px 20px; color:#211d19; border:0; outline:0; background:#fff8eb; font-size:15px; }
    .cinema-search button { border:0; padding:13px 22px; background:var(--rust); color:#fff; font-weight:900; cursor:pointer; }
    .cinema-stats { display:flex; flex-wrap:wrap; gap:15px 38px; border-bottom:1px solid var(--line); padding-block:23px; color:var(--muted); font-size:12px; font-weight:800; letter-spacing:.07em; text-transform:uppercase; }
    .cinema-stats strong { color:var(--rust); font:700 25px Georgia,serif; letter-spacing:-.05em; }
    .cinema-section { padding-block:63px; }
    .cinema-section--cream { background:#fff7e9; }
    .cinema-section__head { display:flex; justify-content:space-between; align-items:end; flex-wrap:wrap; gap:20px; margin-bottom:26px; }
    .cinema-section__head p { max-width:540px; margin-top:11px; color:var(--muted); }
    .cinema-section__head a { color:var(--rust); font-size:12px; font-weight:900; text-transform:uppercase; letter-spacing:.1em; }
    .cinema-grid { display:grid; grid-template-columns:repeat(3,minmax(0,1fr)); gap:18px; }
    .cinema-card { position:relative; min-width:0; overflow:hidden; background:var(--card); border:1px solid var(--line); box-shadow:0 12px 25px rgba(33,29,25,.06); transition:transform .25s ease,box-shadow .25s ease; }
    .cinema-card:hover { transform:translateY(-5px); box-shadow:0 20px 35px rgba(33,29,25,.13); }
    .cinema-card__visual { position:relative; display:flex; align-items:end; height:190px; overflow:hidden; background:radial-gradient(circle at 73% 27%,#e8aa62 0 7%,#9d593a 26%,#273a3a 73%); color:#fff8eb; }
    .cinema-card__visual::after { content:""; position:absolute; inset:0; background:linear-gradient(0deg,rgba(8,18,20,.7),transparent 60%); }
    .cinema-card__visual img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; }
    .cinema-card__initial { position:relative; z-index:1; padding:12px 20px; font:700 92px/.75 Georgia,serif; opacity:.72; }
    .cinema-card__number { position:absolute; z-index:2; top:12px; left:12px; padding:5px 7px; background:#fff8eb; color:var(--ink); font-size:10px; font-weight:900; letter-spacing:.12em; }
    .cinema-card__body { padding:20px; }
    .cinema-card__meta { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.15em; text-transform:uppercase; }
    .cinema-card h3 { margin:9px 0; font:700 25px/1.12 Georgia,serif; letter-spacing:-.04em; }
    .cinema-card h3 a { text-decoration:none; }
    .cinema-card p { color:var(--muted); font-size:12px; line-height:1.6; }
    .cinema-card__foot { display:flex; align-items:center; justify-content:space-between; gap:10px; margin-top:19px; border-top:1px solid var(--line); padding-top:13px; color:var(--ink); font-size:11px; font-weight:900; text-transform:uppercase; }
    .cinema-category-grid { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:1px; border:1px solid var(--line); background:var(--line); }
    .cinema-category { display:flex; flex-direction:column; justify-content:space-between; min-height:136px; padding:18px; background:#fffaf0; text-decoration:none; transition:background .2s; }
    .cinema-category:hover { background:#f5dfc7; }
    .cinema-category small { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.14em; }
    .cinema-category strong { font:700 22px/1.1 Georgia,serif; }
    .cinema-category span { color:var(--muted); font-size:11px; }
    .cinema-city-list { display:flex; flex-wrap:wrap; gap:10px; }
    .cinema-city-list a { padding:10px 15px; border:1px solid var(--line); background:#fffaf0; color:var(--ink); font-size:12px; font-weight:800; text-decoration:none; }
    .cinema-city-list a:hover { border-color:var(--rust); color:var(--rust); }
    .cinema-feature { display:grid; grid-template-columns:1fr 1fr; min-height:300px; background:var(--ink); color:#fff8eb; }
    .cinema-feature__art { display:grid; place-items:center; overflow:hidden; background:radial-gradient(circle at center,#e8aa62 0 9%,#ad5637 30%,#17282d 70%); font:700 clamp(80px,16vw,210px)/1 Georgia,serif; color:rgba(255,248,235,.55); }
    .cinema-feature__copy { display:flex; flex-direction:column; justify-content:center; align-items:start; padding:clamp(28px,5vw,65px); }
    .cinema-feature__copy h2 { margin:10px 0 18px; font:700 clamp(32px,4vw,55px)/1 Georgia,serif; letter-spacing:-.05em; }
    .cinema-feature__copy p { max-width:440px; color:#d4d0c5; }
    .cinema-feature__copy .cinema-btn { margin-top:25px; }
    .cinema-page { min-height:60vh; background:var(--paper); }
    .cinema-page__hero { padding-block:49px 56px; background:var(--ink); color:#fff8eb; }
    .cinema-page__hero .cinema-kicker { color:var(--gold); }
    .cinema-page__hero h1 { max-width:900px; margin:12px 0; color:#fff8eb; }
    .cinema-page__hero p { max-width:650px; color:#d4d0c5; }
    .cinema-crumb { display:flex; flex-wrap:wrap; gap:9px; margin-bottom:23px; color:#d4d0c5; font-size:11px; font-weight:800; text-transform:uppercase; letter-spacing:.07em; }
    .cinema-crumb a { color:#e8aa62; }
    .cinema-page__body { padding-block:35px 76px; }
    .cinema-filter { display:flex; align-items:end; flex-wrap:wrap; gap:12px; padding:17px; border:1px solid var(--line); background:var(--card); }
    .cinema-filter label { display:grid; flex:1 1 180px; gap:5px; color:var(--muted); font-size:10px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
    .cinema-filter :is(input,select) { min-width:0; width:100%; border:1px solid var(--line); padding:12px; background:#fff; color:var(--ink); font-size:13px; }
    .cinema-filter button { min-height:44px; border:0; padding:11px 18px; background:var(--rust); color:#fff; font-size:12px; font-weight:900; cursor:pointer; }
    .cinema-filter > a { align-self:center; color:var(--rust); font-size:12px; font-weight:800; }
    .cinema-columns { display:grid; grid-template-columns:minmax(0,1fr) 280px; gap:25px; align-items:start; margin-top:26px; }
    .cinema-main,.cinema-side { display:grid; gap:24px; min-width:0; }
    .cinema-panel { border:1px solid var(--line); background:var(--card); }
    .cinema-panel__head { display:flex; align-items:baseline; justify-content:space-between; flex-wrap:wrap; gap:10px; padding:16px 19px; border-bottom:1px solid var(--line); }
    .cinema-panel__head h2 { font:700 24px/1.1 Georgia,serif; }
    .cinema-panel__head small { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.1em; text-transform:uppercase; }
    .cinema-list-row { display:grid; grid-template-columns:94px minmax(0,1fr) auto; gap:19px; align-items:center; padding:18px; border-bottom:1px solid var(--line); }
    .cinema-list-row:last-child { border-bottom:0; }
    .cinema-list-row__art { display:grid; place-items:center; overflow:hidden; width:94px; height:85px; background:radial-gradient(circle at 72% 25%,#e8aa62,#a05131 42%,#243739 80%); color:#fff; font:700 50px Georgia,serif; text-decoration:none; }
    .cinema-list-row__art img { width:100%; height:100%; object-fit:cover; }
    .cinema-list-row small { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
    .cinema-list-row h3 { margin:4px 0; font:700 23px/1.1 Georgia,serif; }
    .cinema-list-row h3 a { text-decoration:none; }
    .cinema-list-row p { color:var(--muted); font-size:12px; }
    .cinema-list-row__go { color:var(--rust); font-size:11px; font-weight:900; text-decoration:none; text-transform:uppercase; }
    .cinema-link-list a { display:flex; justify-content:space-between; gap:8px; padding:12px 17px; border-bottom:1px solid var(--line); color:var(--ink); font-size:12px; font-weight:800; text-decoration:none; }
    .cinema-link-list a:last-child { border-bottom:0; }
    .cinema-link-list a span:last-child { color:var(--rust); }
    .cinema-copy { padding:21px; color:#524841; font-size:14px; line-height:1.8; }
    .cinema-copy p + p { margin-top:15px; }
    .cinema-empty { padding:32px 20px; color:var(--muted); }
    .cinema-pagination { padding:14px; border-top:1px solid var(--line); }
    .cinema-promo { padding:24px; background:var(--ink); color:#fff8eb; }
    .cinema-promo h2 { font:700 26px/1.1 Georgia,serif; }
    .cinema-promo p { margin-block:12px 18px; color:#d4d0c5; font-size:12px; }
    .cinema-article { padding:clamp(22px,4vw,52px); }
    .cinema-article h1 { font:700 clamp(35px,5vw,68px)/1 Georgia,serif; letter-spacing:-.06em; }
    .cinema-article__lead { margin-top:18px; color:#524841; font-size:18px; }
    .cinema-article__image { width:100%; max-height:500px; margin-top:25px; object-fit:cover; }
    .cinema-prose { margin-top:27px; border-top:1px solid var(--line); padding-top:25px; color:#3d3630; line-height:1.8; }
    .cinema-prose :is(h2,h3) { margin:25px 0 10px; font:700 27px Georgia,serif; }
    .cinema-prose p + p { margin-top:16px; }
    .cinema-prose :is(ol,ul) { margin:15px 0; padding-left:25px; list-style:revert; }
    .cinema-prose a { color:var(--rust); text-decoration:underline; }
    .cinema-detail { background:var(--paper); }
    .cinema-detail__cover { position:relative; min-height:340px; overflow:hidden; background:radial-gradient(circle at 78% 20%,#e8aa62 0 5%,#a95c3e 25%,#17282d 67%); color:#fff8eb; }
    .cinema-detail__cover > img { position:absolute; inset:0; width:100%; height:100%; object-fit:cover; opacity:.55; }
    .cinema-detail__cover::after { content:""; position:absolute; inset:0; background:linear-gradient(90deg,rgba(18,30,34,.95),rgba(18,30,34,.2)); }
    .cinema-detail__cover .cinema-wrap { position:relative; z-index:1; padding-block:42px 70px; }
    .cinema-detail__cover .cinema-crumb { margin-bottom:40px; }
    .cinema-detail__cover h1 { max-width:900px; margin:14px 0; color:#fff8eb; }
    .cinema-detail__cover p { max-width:650px; color:#e3d8c9; }
    .cinema-detail__body { display:grid; grid-template-columns:minmax(0,1fr) 310px; gap:25px; padding-block:35px 70px; }
    .cinema-detail__main,.cinema-detail__side { display:grid; gap:24px; min-width:0; align-content:start; }
    .cinema-detail .td-section,.cinema-detail .td-side-card { border:1px solid var(--line); background:var(--card); padding:clamp(19px,3vw,31px); }
    .cinema-detail .td-section-head { display:flex; align-items:baseline; flex-wrap:wrap; gap:10px; margin-bottom:17px; border-bottom:1px solid var(--line); padding-bottom:15px; }
    .cinema-detail .td-index { color:var(--rust); font:700 20px Georgia,serif; }
    .cinema-detail .td-section-head h2,.cinema-detail .td-side-card h2 { font:700 clamp(22px,3vw,31px)/1.1 Georgia,serif; }
    .cinema-detail .td-section-head > a,.cinema-detail .td-count { margin-left:auto; color:var(--rust); font-size:11px; font-weight:900; }
    .cinema-detail .td-prose { color:#524841; line-height:1.75; }
    .cinema-detail .td-prose p + p { margin-top:12px; }
    .cinema-detail .td-prose :is(h2,h3) { margin:18px 0 8px; font:700 23px Georgia,serif; }
    .cinema-detail .td-prose ul { padding-left:24px; list-style:disc; }
    .cinema-detail .td-share { margin-top:20px; border-top:1px solid var(--line); padding-top:15px; }
    .cinema-detail .td-card-grid { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:12px; }
    .cinema-detail .td-card { display:block; min-width:0; overflow:hidden; border:1px solid var(--line); background:#f9f2e6; text-decoration:none; }
    .cinema-detail .td-card-image { width:100%; height:170px; object-fit:cover; }
    .cinema-detail .td-card-body,.cinema-detail .td-job { padding:17px; }
    .cinema-detail .td-card small,.cinema-detail .td-side-label { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.13em; text-transform:uppercase; }
    .cinema-detail .td-card h3 { margin:6px 0; font:700 21px Georgia,serif; }
    .cinema-detail .td-card p,.cinema-detail .td-side-card p { color:var(--muted); font-size:12px; }
    .cinema-detail .td-price { display:block; margin-top:12px; color:var(--rust); }
    .cinema-detail .td-gallery { display:grid; grid-template-columns:repeat(4,minmax(0,1fr)); gap:8px; }
    .cinema-detail .td-gallery img { width:100%; aspect-ratio:1; object-fit:cover; }
    .cinema-detail .td-map { aspect-ratio:16/9; }
    .cinema-detail .td-map iframe { width:100%; height:100%; border:0; }
    .cinema-detail .td-review { padding:13px 0; border-bottom:1px solid var(--line); }
    .cinema-detail .td-review > div { display:flex; justify-content:space-between; gap:10px; }
    .cinema-detail .td-review span,.cinema-detail .td-empty { color:var(--muted); font-size:12px; }
    .cinema-detail .td-stars { color:var(--rust); }
    .cinema-detail .td-form-title { margin:20px 0 10px; font:700 23px Georgia,serif; }
    .cinema-detail .td-review-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:9px; }
    .cinema-detail .td-review-form :is(input,select,textarea) { min-width:0; border:1px solid var(--line); background:#fff; padding:11px; }
    .cinema-detail .td-review-form textarea { grid-column:1/-1; }
    .cinema-detail .td-review-form button { width:max-content; padding:10px 15px; border:0; background:var(--rust); color:#fff; font-weight:900; cursor:pointer; }
    .cinema-detail .td-message { margin-bottom:13px; padding:11px; background:#f5dfc7; }
    .cinema-detail .td-faq { padding:12px 0; border-bottom:1px solid var(--line); }
    .cinema-detail .td-faq summary { cursor:pointer; font-weight:800; }
    .cinema-detail .td-contact dl { margin:13px 0; }
    .cinema-detail .td-contact dl div { padding:9px 0; border-bottom:1px solid var(--line); overflow-wrap:anywhere; }
    .cinema-detail .td-contact dt { color:var(--muted); font-size:10px; font-weight:800; text-transform:uppercase; }
    .cinema-detail .td-contact dd { font-weight:800; }
    .cinema-detail .td-actions { display:grid; gap:8px; margin-top:18px; }
    .cinema-detail .td-actions a,.cinema-detail .td-claim > a { display:block; padding:10px 12px; background:var(--ink); color:#fff; font-size:12px; font-weight:800; text-align:center; }
    .cinema-detail .td-claim > a { margin:15px 0; background:var(--rust); }
    .cinema-detail .td-related a { display:block; padding:10px 0; border-bottom:1px solid var(--line); }
    .cinema-detail .td-related span { display:block; color:var(--muted); font-size:11px; }
    .cinema-detail .td-linebreak { white-space:pre-line; }
    .cinema-info { max-width:960px; margin-inline:auto; padding-block:35px 76px; }
    .cinema-info .cinema-panel { padding:clamp(23px,5vw,55px); }
    .cinema-info .cinema-prose { margin-top:0; border-top:0; padding-top:0; }
    .cinema-contact-form { display:grid; grid-template-columns:repeat(2,minmax(0,1fr)); gap:17px; margin-top:24px; }
    .cinema-contact-form label { display:grid; gap:7px; color:var(--muted); font-size:11px; font-weight:900; letter-spacing:.07em; text-transform:uppercase; }
    .cinema-contact-form :is(input,select,textarea) { min-width:0; width:100%; border:1px solid var(--line); padding:12px; background:#fff; color:var(--ink); font-size:14px; font-weight:400; letter-spacing:0; text-transform:none; }
    .cinema-contact-form .wide { grid-column:1/-1; }
    .cinema-contact-form button { width:max-content; }
    .cinema-plan { display:flex; flex-direction:column; min-height:360px; padding:30px; border:1px solid var(--line); background:var(--card); }
    .cinema-plan__number { color:var(--rust); font-size:10px; font-weight:900; letter-spacing:.16em; }
    .cinema-plan h2 { margin-top:16px; font:700 32px/1 Georgia,serif; }
    .cinema-plan__price { margin-block:19px; font:700 42px Georgia,serif; letter-spacing:-.05em; }
    .cinema-plan ul { flex:1; border-top:1px solid var(--line); padding-top:14px; }
    .cinema-plan li { margin-bottom:12px; color:var(--muted); font-size:12px; }
    .cinema-plan .cinema-btn { margin-top:24px; }
    .cinema-shell-content:has(#owner-registration-form) > div > div:first-child { max-width:850px; padding:26px 25px; background:var(--ink); color:#fff8eb; }
    .cinema-shell-content:has(#owner-registration-form) > div > div:first-child h1 { color:#fff8eb !important; font-size:clamp(36px,5vw,57px); letter-spacing:-.055em; }
    .cinema-shell-content:has(#owner-registration-form) > div > div:first-child p { color:#d4d0c5 !important; }
    .cinema-shell-content #owner-registration-form { border-top:6px solid var(--rust) !important; }
    .cinema-shell-content #owner-registration-form :is(button[data-next-step],button[type=submit]) { background:var(--rust) !important; }
    .cinema-shell-content > .max-w-3xl { max-width:960px; padding-block:35px 75px; }
    .cinema-shell-content > .max-w-3xl > .bg-white { border-top:6px solid var(--rust); background:var(--card); }
    .cinema-shell-content > .max-w-3xl > .bg-white h1 { font-size:clamp(30px,4vw,52px); letter-spacing:-.05em; }
    .cinema-shell-content > .max-w-3xl > .bg-white button[type=submit] { background:var(--rust) !important; }
    @media(max-width:900px) { .cinema-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .cinema-category-grid { grid-template-columns:repeat(2,minmax(0,1fr)); } .cinema-hero__frame { opacity:.3; } }
    @media(max-width:760px) { .cinema-columns,.cinema-detail__body,.cinema-feature { grid-template-columns:1fr; } .cinema-side { grid-template-columns:repeat(2,minmax(0,1fr)); } .cinema-feature__art { min-height:200px; } .cinema-footer__main { grid-template-columns:1fr 1fr; } .cinema-footer__main > div:first-child { grid-column:1/-1; } }
    @media(max-width:560px) { .cinema-wrap { width:min(100% - 28px,1340px); } .cinema-header__top .cinema-wrap { font-size:9px; } .cinema-header__main { padding-block:16px; } .cinema-nav { gap:8px 13px; font-size:10px; } .cinema-hero .cinema-wrap { padding-block:53px 66px; } .cinema-hero::before { opacity:.24; } .cinema-hero__frame { right:-100px; top:15%; opacity:.15; } .cinema-search { flex-direction:column; } .cinema-search button { min-height:46px; } .cinema-filter { display:grid; grid-template-columns:1fr; } .cinema-filter label { min-width:0; } .cinema-filter button { width:100%; } .cinema-grid,.cinema-side,.cinema-category-grid { grid-template-columns:1fr; } .cinema-list-row { grid-template-columns:64px minmax(0,1fr); gap:12px; } .cinema-list-row__art { width:64px; height:66px; font-size:38px; } .cinema-list-row__go { grid-column:2; } .cinema-detail .td-card-grid { grid-template-columns:1fr; } .cinema-detail .td-gallery { grid-template-columns:repeat(2,minmax(0,1fr)); } .cinema-contact-form { grid-template-columns:1fr; } .cinema-footer__main { grid-template-columns:1fr; } .cinema-footer__main > div:first-child { grid-column:auto; } }
    @media(prefers-reduced-motion:reduce) { .cinema-film__track { animation:none; } .cinema-card,.cinema-btn { transition:none; } }
</style>
