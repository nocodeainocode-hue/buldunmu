<style>
    html.theme-classifieds-board body > header,
    html.theme-classifieds-board body > footer { display:none !important; }
    html.theme-classifieds-board body > main { background:#f5f4ef; color:#232338; }
    .board-shell { font:13px/1.5 Arial,sans-serif; }
    .board-shell a { color:#3834a7; text-decoration:underline; text-underline-offset:2px; }
    .board-shell a:hover { color:#bd4e2a; }
    .board-shell .bs-wrap { width:min(100% - 40px,1460px); margin:auto; }
    .board-shell .bs-topline { background:#3834a7; color:#fff; font-size:11px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
    .board-shell .bs-topline .bs-wrap { display:flex; justify-content:space-between; gap:14px; padding:8px 0; }
    .board-shell .bs-topline a { color:#fff; }
    .board-shell .bs-mainhead { background:#fffefa; border-bottom:3px double #3834a7; }
    .board-shell .bs-mainhead .bs-wrap { display:grid; grid-template-columns:minmax(0,1fr) auto; gap:15px 24px; align-items:end; padding:20px 0; }
    .board-shell .bs-brand { color:#232338; font:900 clamp(31px,4vw,56px)/1 Georgia,serif; letter-spacing:-.06em; text-decoration:none; }
    .board-shell .bs-brand em { color:#3834a7; font-style:normal; }
    .board-shell .bs-sub { margin-top:7px; color:#626277; font-size:12px; }
    .board-shell .bs-nav { display:flex; flex-wrap:wrap; justify-content:flex-end; gap:8px 17px; font-weight:800; font-size:12px; }
    .board-shell .bs-search { display:grid; grid-template-columns:minmax(0,1fr) auto; grid-column:1/-1; gap:7px; border:1px solid #aaa8bc; background:#eeecfb; padding:10px; }
    .board-shell .bs-search input { min-width:0; border:1px solid #aaa8bc; border-radius:0; background:#fff; padding:10px 12px; color:#232338; font-size:14px; }
    .board-shell .bs-search button { border:1px solid #3834a7; background:#3834a7; padding:9px 18px; color:#fff; font-size:12px; font-weight:900; cursor:pointer; }
    .board-shell.bs-footer { border-top:3px double #3834a7; background:#fffefa; }
    .board-shell.bs-footer .bs-wrap { display:flex; justify-content:space-between; flex-wrap:wrap; gap:10px 25px; padding:19px 0; }
    .board-shell-content { min-height:50vh; font-family:Arial,sans-serif; }
    .board-shell-content h1, .board-shell-content h2, .board-shell-content h3 { font-family:Georgia,serif; }
    .board-shell-content :is(.rounded-full,.rounded-3xl,.rounded-2xl,.rounded-xl,.rounded-lg,.rounded-md) { border-radius:2px !important; }
    .board-shell-content :is(.shadow,.shadow-sm,.shadow-md,.shadow-lg,.shadow-xl,.shadow-2xl) { box-shadow:none !important; }
    .board-shell-content :is(input,select,textarea,button) { border-radius:0 !important; }
    .board-shell-content .blog-prose { color:#232338; }
    .board-page { padding:22px 0 65px; background:#f5f4ef; color:#232338; font:14px/1.5 Arial,sans-serif; }
    .board-page .bp-wrap { width:min(100% - 40px,1460px); margin:auto; }
    .board-page a { color:#3834a7; text-decoration:underline; text-underline-offset:2px; }
    .board-page a:hover { color:#bd4e2a; }
    .board-page .bp-crumb { display:flex; flex-wrap:wrap; gap:7px; color:#626277; font-size:12px; }
    .board-page .bp-intro { display:flex; align-items:end; justify-content:space-between; flex-wrap:wrap; gap:20px; border-bottom:3px double #3834a7; padding:22px 0 20px; }
    .board-page .bp-eyebrow { color:#3834a7; font-size:10px; font-weight:900; letter-spacing:.12em; text-transform:uppercase; }
    .board-page h1 { margin:5px 0; font:900 clamp(31px,4vw,56px)/1.05 Georgia,serif; letter-spacing:-.05em; overflow-wrap:anywhere; }
    .board-page .bp-intro p:last-child { max-width:720px; color:#626277; }
    .board-page .bp-stamp { border:2px solid #3834a7; background:#eeecfb; padding:8px 15px; color:#3834a7; text-align:center; font-weight:900; }
    .board-page .bp-stamp strong { display:block; font:900 29px Georgia,serif; }
    .board-page .bp-filter { display:flex; flex-wrap:wrap; align-items:end; gap:9px; margin:18px 0; border:1px solid #cbc9d2; background:#fffefa; padding:14px; }
    .board-page .bp-field { display:grid; gap:4px; flex:1 1 170px; min-width:0; }
    .board-page .bp-field label { color:#626277; font-size:10px; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
    .board-page .bp-field :is(input,select) { width:100%; min-width:0; border:1px solid #aaa8bc; background:#fff; padding:10px; color:#232338; font-size:13px; }
    .board-page .bp-filter button { border:1px solid #3834a7; background:#3834a7; padding:10px 20px; color:#fff; font-size:12px; font-weight:900; cursor:pointer; }
    .board-page .bp-filter > a { align-self:center; font-size:12px; }
    .board-page .bp-columns { display:grid; grid-template-columns:minmax(0,1fr) 280px; gap:18px; align-items:start; }
    .board-page .bp-main, .board-page .bp-side { display:grid; gap:18px; min-width:0; }
    .board-page .bp-panel { border:1px solid #cbc9d2; background:#fffefa; }
    .board-page .bp-panel-head { display:flex; align-items:baseline; justify-content:space-between; flex-wrap:wrap; gap:8px; border-bottom:1px solid #cbc9d2; background:#eeecfb; padding:10px 13px; }
    .board-page .bp-panel-head h2 { color:#29247f; font:800 20px Georgia,serif; }
    .board-page .bp-panel-head small { color:#626277; font-size:11px; font-weight:800; }
    .board-page .bp-row { display:grid; grid-template-columns:42px minmax(0,1fr) auto; gap:13px; align-items:start; border-bottom:1px dotted #cbc9d2; padding:15px; }
    .board-page .bp-row:last-child { border-bottom:0; }
    .board-page .bp-initial { display:grid; place-items:center; width:42px; height:42px; border:1px solid #aaa8bc; background:#eeecfb; color:#3834a7; font:900 20px Georgia,serif; text-decoration:none; }
    .board-page .bp-row-title { font-size:16px; font-weight:900; line-height:1.2; }
    .board-page .bp-row p { margin-top:5px; color:#626277; font-size:12px; }
    .board-page .bp-tag { border:1px solid #d89f42; background:#fff4d9; padding:3px 6px; color:#7f4c00; font-size:10px; font-weight:900; text-transform:uppercase; }
    .board-page .bp-linklist { padding:5px 13px; }
    .board-page .bp-linklist a { display:flex; justify-content:space-between; gap:10px; border-bottom:1px dotted #cbc9d2; padding:9px 0; font-size:12px; font-weight:700; }
    .board-page .bp-linklist a:last-child { border:0; }
    .board-page .bp-linklist small { color:#85839c; }
    .board-page .bp-copy { padding:18px; color:#4f4b63; font-size:13px; line-height:1.7; }
    .board-page .bp-copy p + p { margin-top:13px; }
    .board-page .bp-empty { padding:30px 15px; color:#626277; font-size:13px; }
    .board-page .bp-pagination { padding:14px; border-top:1px solid #cbc9d2; }
    .board-page .bp-callout { border:2px solid #3834a7; background:#eeecfb; padding:17px; }
    .board-page .bp-callout h2 { color:#29247f; font:800 22px Georgia,serif; }
    .board-page .bp-callout p { margin-top:8px; color:#4f4b70; font-size:12px; }
    .board-page .bp-callout a { display:inline-block; margin-top:12px; font-weight:900; font-size:12px; }
    .board-page .bp-article { padding:24px; }
    .board-page .bp-article h1 { margin:8px 0 12px; }
    .board-page .bp-meta { display:flex; flex-wrap:wrap; gap:7px 17px; color:#626277; font-size:12px; }
    .board-page .bp-article-lead { margin-top:18px; color:#4f4b63; font-size:17px; line-height:1.6; }
    .board-page .bp-article-image { width:100%; max-height:500px; object-fit:cover; margin:21px 0; border:1px solid #cbc9d2; }
    .board-page .bp-prose { margin-top:22px; border-top:1px solid #cbc9d2; padding-top:22px; color:#36364a; font-size:15px; line-height:1.8; }
    .board-page .bp-prose h2, .board-page .bp-prose h3 { margin:24px 0 9px; color:#232338; font:800 24px Georgia,serif; }
    .board-page .bp-prose p + p { margin-top:15px; }
    .board-page .bp-prose :is(ul,ol) { padding-left:25px; margin:14px 0; list-style:revert; }
    .board-page .bp-prose a { text-decoration:underline; }
    .board-page .bp-action { display:inline-block; border:1px solid #3834a7; background:#3834a7; padding:10px 16px; color:#fff; font-size:12px; font-weight:900; text-decoration:none; }
    .board-page .bp-action:hover { color:#fff; background:#251f85; }
    .board-page .bp-divider { border-top:1px dotted #cbc9d2; margin-top:23px; padding-top:18px; }
    .board-page .bp-faq { border-bottom:1px dotted #cbc9d2; padding:11px 0; }
    .board-page .bp-faq summary { cursor:pointer; font-weight:800; }
    .board-page .bp-faq p { padding-top:8px; color:#626277; }
    @media(max-width:850px) { .board-page .bp-columns { grid-template-columns:1fr; } .board-page .bp-side { grid-template-columns:repeat(2,minmax(0,1fr)); } }
    @media(max-width:650px) { .board-shell .bs-wrap,.board-page .bp-wrap { width:min(100% - 24px,1460px); } .board-shell .bs-mainhead .bs-wrap { grid-template-columns:1fr; } .board-shell .bs-nav { justify-content:flex-start; } .board-shell .bs-search { grid-column:auto; } .board-page .bp-side { grid-template-columns:1fr; } .board-page .bp-row { grid-template-columns:36px minmax(0,1fr); } .board-page .bp-initial { width:36px; height:36px; } .board-page .bp-tag { grid-column:2; width:max-content; } .board-page .bp-filter { display:grid; grid-template-columns:1fr; } }
</style>
