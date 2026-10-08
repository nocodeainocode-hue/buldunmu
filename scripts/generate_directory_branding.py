"""Generate the reviewed 83-directory identity set. Requires fonttools and brotli.

Uses the project's Inter font; wordmarks are outlines, not runtime fonts.
Production only copies the committed assets and does not require Python.
"""
from pathlib import Path
import json
import html
from fontTools.ttLib import TTFont
from fontTools.varLib.instancer import instantiateVariableFont
from fontTools.pens.svgPathPen import SVGPathPen
import cairosvg

ROOT = Path(__file__).resolve().parents[1]
OUT = ROOT / 'resources/branding/directories'
FONT_DIR = ROOT / 'public/fonts/filament/filament/inter'
FONTS = [instantiateVariableFont(TTFont(str(next(FONT_DIR.glob(f'inter-{subset}-wght-normal-*.woff2')))), {'wght': 700}, inplace=False) for subset in ('latin', 'latin-ext')]

# domain stem, wordmark, motif, accent. Explicit brand decisions, no random palette.
BRANDS = [
 ('81ilfirmalar','81 İl Firmalar','atlas','#1D4ED8'),
 ('alternatifbul','Alternatif Bul','fork','#0F766E'),
 ('ankarakobi','Ankara KOBİ','ankara','#B45309'),
 ('aradiginisletme','Aradığın İşletme','search','#4338CA'),
 ('bizbul','Biz Bul','dialog','#047857'),
 ('buldora','Buldora','door','#BE123C'),
 ('buldunmu','Buldun mu?','found','#2563EB'),
 ('bulmino','Bulmino','monogram:bm','#7C3AED'),
 ('bulnex','Bulnex','monogram:bn','#0369A1'),
 ('bulvera','Bulvera','monogram:bv','#0F766E'),
 ('cityesnaf','City Esnaf','skyline','#B45309'),
 ('esnafagi','Esnaf Ağı','network','#0F766E'),
 ('esnafharita','Esnaf Harita','map','#166534'),
 ('esnafmeydani','Esnaf Meydanı','square','#C2410C'),
 ('esnafpuani','Esnaf Puanı','star','#A16207'),
 ('esnafpusulasi','Esnaf Pusulası','compass','#B45309'),
 ('esnaftamyanimda','Esnaf Tam Yanımda','neighbor','#BE123C'),
 ('esnafy','Esnafy','monogram:e','#0F766E'),
 ('firmaendeksi','Firma Endeksi','index','#1D4ED8'),
 ('firmafeneri','Firma Feneri','beacon','#B45309'),
 ('firmahatti','Firma Hattı','phone','#0369A1'),
 ('firmahub','Firma Hub','hub','#4338CA'),
 ('firmakiyasla','Firma Kıyasla','compare','#0F766E'),
 ('firmakonum','Firma Konum','pin','#2563EB'),
 ('firmakonumbul','Firma Konum Bul','target','#047857'),
 ('firmalarimiz','Firmalarımız','buildings','#B45309'),
 ('firmasor','Firma Sor','question','#7C3AED'),
 ('firmatoplulugu','Firma Topluluğu','people','#4338CA'),
 ('firmavitrin','Firma Vitrin','window','#BE123C'),
 ('firmayeri','Firma Yeri','place','#0F766E'),
 ('firmayorum','Firma Yorum','review','#C2410C'),
 ('firmio','Firmio','monogram:f','#4338CA'),
 ('firmondo','Firmondo','globe','#0369A1'),
 ('guvenilirfirmalar','Güvenilir Firmalar','shield','#047857'),
 ('hizmetharitasi','Hizmet Haritası','map','#0369A1'),
 ('hizmetto','Hizmetto','monogram:ht','#C2410C'),
 ('hizmetyakinda','Hizmet Yakında','radar','#BE123C'),
 ('hizmetyanimda','Hizmet Yanımda','neighbor','#047857'),
 ('isletmebulutu','İşletme Bulutu','cloud','#2563EB'),
 ('isletmebulvar','İşletme Bulvarı','avenue','#4338CA'),
 ('isletmelistesi','İşletme Listesi','list','#0F766E'),
 ('isletmepusulasi','İşletme Pusulası','compass','#1D4ED8'),
 ('istanbulfirmarehberi','İstanbul Firma Rehberi','istanbul','#1D4ED8'),
 ('izmirisletmeleri','İzmir İşletmeleri','izmir','#0F766E'),
 ('kayitlikobi','Kayıtlı KOBİ','register','#B45309'),
 ('kentigo','Kentigo','monogram:k','#0369A1'),
 ('kentiva','Kentiva','monogram:kv','#7C3AED'),
 ('kentlist','Kent List','citylist','#4338CA'),
 ('kobibirligi','KOBİ Birliği','union','#1D4ED8'),
 ('kobiflow','KOBİ Flow','flow','#0F766E'),
 ('kobiharita','KOBİ Harita','atlas','#047857'),
 ('kobilocal','KOBİ Local','local','#B45309'),
 ('kobimercek','KOBİ Mercek','lens','#0369A1'),
 ('kobiva','Kobiva','monogram:kb','#2563EB'),
 ('kobivitrin','KOBİ Vitrin','window','#4338CA'),
 ('komsufirma','Komşu Firma','neighbor','#B45309'),
 ('kuaforrehberi','Kuaför Rehberi','scissors','#BE123C'),
 ('localbul','Local Bul','search','#0F766E'),
 ('localhizmet','Local Hizmet','service','#0369A1'),
 ('prestijli','Prestijli','diamond','#6D28D9'),
 ('secmedenonce','Seçmeden Önce','select','#C2410C'),
 ('sehirvitrini','Şehir Vitrini','skyline','#0F766E'),
 ('sektorbazaar','Sektör Bazaar','market','#B45309'),
 ('sektorbul','Sektör Bul','sectors','#1D4ED8'),
 ('servicebul','Service Bul','service','#047857'),
 ('tekirdagfirmarehberi','Tekirdağ Firma Rehberi','tekirdag','#0369A1'),
 ('tescilliesnaf','Tescilli Esnaf','seal','#166534'),
 ('trakyafirmalar','Trakya Firmalar','thrace','#B45309'),
 ('ustaharita','Usta Harita','toolpin','#C2410C'),
 ('ustarotasi','Usta Rotası','route','#B45309'),
 ('vipfirma','VIP Firma','diamond','#1D4ED8'),
 ('yakino','Yakino','monogram:y','#047857'),
 ('yakindafirma','Yakında Firma','radar','#2563EB'),
 ('yereldurak','Yerel Durak','stop','#B45309'),
 ('yerelgo','Yerel Go','go','#0F766E'),
 ('yerelhizmetim','Yerel Hizmetim','service','#4338CA'),
 ('yerelio','Yerelio','monogram:yl','#7C3AED'),
 ('yerelix','Yerelix','monogram:yx','#0F766E'),
 ('yerelkatalog','Yerel Katalog','book','#B45309'),
 ('yerelpuan','Yerel Puan','review','#047857'),
 ('yerelrehber360','Yerel Rehber 360','orbit','#1D4ED8'),
 ('yerelspot','Yerel Spot','spot','#BE123C'),
 ('yerelustalar','Yerel Ustalar','tools','#C2410C'),
]

def wordmark(text, size, x, y, fill, max_width=None):
    paths, cursor = [], 0
    for char in text:
        font = next((f for f in FONTS if ord(char) in f.getBestCmap()), None)
        if font is None:
            raise ValueError(f'Unsupported glyph: {char}')
        glyph = font.getBestCmap()[ord(char)]
        gs = font.getGlyphSet()
        pen = SVGPathPen(gs)
        gs[glyph].draw(pen)
        units = font['head'].unitsPerEm
        scale = size / units
        if pen.getCommands():
            paths.append(f'<path d="{pen.getCommands()}" transform="translate({cursor:.3f} 0) scale({scale:.6f} {-scale:.6f})"/>')
        cursor += font['hmtx'][glyph][0] * scale
    squeeze = min(1, max_width / cursor) if max_width else 1
    return f'<g fill="{fill}" transform="translate({x} {y}) scale({squeeze:.6f} 1)">{"".join(paths)}</g>'

def motif(kind):
    # Local 48px artboard; no external icons or images.
    shapes = {
      'pin':'<path d="M24 43S9 30 9 19a15 15 0 0 1 30 0c0 11-15 24-15 24Z"/><circle cx="24" cy="19" r="5"/>',
      'target':'<circle cx="24" cy="24" r="15"/><circle cx="24" cy="24" r="6"/><path d="M24 3v6m0 30v6M3 24h6m30 0h6"/>',
      'map':'<path d="m4 11 13-5 14 5 13-5v31l-13 5-14-5-13 5Zm13-5v31m14-26v31"/>',
      'atlas':'<path d="M5 14 17 9l14 5 12-5v29l-12 5-14-5-12 5Zm12-5v29m14-24v29"/><circle cx="29" cy="9" r="5"/>',
      'found':'<circle cx="20" cy="20" r="14"/><path d="m30 30 12 12M12 20l6 6 11-12"/>',
      'search':'<circle cx="20" cy="20" r="13"/><path d="m30 30 12 12M14 20h12m-6-6v12"/>',
      'door':'<path d="M8 42V7h32v35M15 42V15l19-5v32M3 42h42"/><circle cx="28" cy="27" r="1"/>',
      'fork':'<path d="M24 42V28c0-13-14-7-14-20m14 20c0-13 14-7 14-20M4 13l6-6 6 6m16 0 6-6 6 6"/>',
      'dialog':'<path d="M5 6h29v23H15L5 37ZM19 34h14l10 8V18h-4"/><path d="M12 15h15m-15 6h10"/>',
      'skyline':'<path d="M4 42h40M8 42V19h9v23m3 0V6h10v36m3 0V25h8v17M24 12h2m-2 7h2m-2 7h2M11 25h3m-3 6h3"/>',
      'network':'<circle cx="24" cy="24" r="6"/><circle cx="8" cy="9" r="4"/><circle cx="40" cy="9" r="4"/><circle cx="8" cy="39" r="4"/><circle cx="40" cy="39" r="4"/><path d="m11 12 9 8m8 0 9-8m-26 24 9-8m8 0 9 8"/>',
      'hub':'<circle cx="24" cy="24" r="8"/><path d="M24 4v12m0 16v12M4 24h12m16 0h12m-34-14 8 8m12 12 8 8m-28 0 8-8m12-12 8-8"/>',
      'square':'<path d="M5 5h13v13H5Zm25 0h13v13H30ZM5 30h13v13H5Zm25 0h13v13H30Z"/><circle cx="24" cy="24" r="3"/>',
      'star':'<path d="m24 5 6 12 14 2-10 10 3 14-13-7-13 7 3-14L4 19l14-2Z"/>',
      'compass':'<circle cx="24" cy="24" r="19"/><path d="m32 16-5 11-11 5 5-11ZM24 5v4m0 30v4M5 24h4m30 0h4"/>',
      'neighbor':'<path d="m3 23 11-10 10 10V41H5V23m19 0 10-10 11 10M27 41h16V23M11 41V30h7v11m16-23h3"/>',
      'index':'<path d="M9 6h29v36H9Zm-4 8h9m-9 10h9m-9 10h9M21 14h10m-10 9h10m-10 9h7"/>',
      'beacon':'<path d="m16 42 4-27h8l4 27ZM17 15h14V7H17ZM8 42h32M5 8l7 3M36 11l7-3M21 5h6"/>',
      'phone':'<path d="m10 5 8 9-5 6c4 7 8 11 15 14l6-5 9 8c-2 8-8 8-15 5C15 37 7 28 4 16 2 9 5 6 10 5Z"/>',
      'compare':'<path d="M24 5v38M5 10h14v12H5Zm24 0h14v12H29ZM5 28h14v10H5Zm24 0h14v10H29Z"/>',
      'buildings':'<path d="M5 42V15h16v27m0 0V5h21v37M11 22h4m-4 8h4m13-18h7m-7 8h7m-7 8h7M3 42h42"/>',
      'question':'<path d="M5 5h38v29H22L9 43v-9H5Z"/><path d="M19 15c0-8 13-8 13 0 0 5-7 4-7 9m0 5h.1"/>',
      'people':'<circle cx="24" cy="12" r="6"/><circle cx="8" cy="18" r="4"/><circle cx="40" cy="18" r="4"/><path d="M13 42V32a11 11 0 0 1 22 0v10M3 38v-8a5 5 0 0 1 8-4m26 0a5 5 0 0 1 8 4v8"/>',
      'union':'<path d="M5 16 15 6h18l10 10v17L33 43H15L5 33ZM15 16h18v17H15Z"/><path d="m5 16 10 0m18 0h10M15 33 5 33m28 0h10"/>',
      'window':'<path d="M5 7h38v35H5Zm0 9h38M24 16v26M11 7v9m8-9v9m8-9v9m8-9v9"/>',
      'place':'<path d="M7 41V20l17-13 17 13v21ZM18 41V27h12v14"/><circle cx="24" cy="17" r="2"/>',
      'review':'<path d="M4 6h40v27H22L9 43V33H4Z"/><path d="m24 12 3 6 7 1-5 5 1 7-6-3-6 3 1-7-5-5 7-1Z"/>',
      'globe':'<circle cx="24" cy="24" r="19"/><ellipse cx="24" cy="24" rx="8" ry="19"/><path d="M5 24h38M9 13h30M9 35h30"/>',
      'shield':'<path d="m24 4 17 6v14c0 11-17 20-17 20S7 35 7 24V10Z"/><path d="m15 23 6 6 13-14"/>',
      'radar':'<circle cx="24" cy="24" r="19"/><circle cx="24" cy="24" r="10"/><path d="m24 24 14-14"/><circle cx="24" cy="24" r="2"/>',
      'cloud':'<path d="M12 38a9 9 0 0 1-1-18 13 13 0 0 1 25-4 11 11 0 0 1 0 22Z"/><path d="m17 27 7-7 7 7m-7-7v16"/>',
      'avenue':'<path d="M6 5h10v38H6ZM32 5h10v38H32ZM24 5v7m0 8v8m0 8v7"/>',
      'list':'<path d="M15 11h28M15 24h28M15 37h28"/><circle cx="5" cy="11" r="2"/><circle cx="5" cy="24" r="2"/><circle cx="5" cy="37" r="2"/>',
      'istanbul':'<path d="M3 39h42M5 36V19m38 17V19M5 23h38M5 23c9 13 29 13 38 0M13 31v5m11-1v1m11-5v5M3 44c6-4 9 4 15 0s9 4 15 0 9 4 12 0"/>',
      'ankara':'<path d="M7 42V21h34v21M7 21l17-9 17 9M12 21v21m8-21v21m8-21v21m8-21v21M4 42h40M24 12V4h8"/>',
      'izmir':'<path d="M17 41V13h14v28M14 41h20M15 13h18l-9-9ZM20 29h8m-4 0v12M4 44h40"/><circle cx="24" cy="21" r="4"/>',
      'register':'<path d="M8 6h22l10 10v26H8ZM30 6v10h10M15 25l5 5 11-11M15 36h17"/>',
      'citylist':'<path d="M6 42V10h15v32m0 0V20h11v22M3 42h31M10 17h6m-6 8h6M37 10h8m-8 10h8m-8 10h8"/>',
      'flow':'<path d="M4 12h28l-7-7m7 7-7 7M44 36H16l7 7m-7-7 7-7M7 19v10m34-10v10"/>',
      'local':'<circle cx="24" cy="24" r="19"/><path d="m12 24 12-10 12 10v13H12ZM20 37V27h8v10"/>',
      'lens':'<circle cx="21" cy="21" r="16"/><circle cx="21" cy="21" r="8"/><path d="m33 33 10 10M21 5v8"/>',
      'scissors':'<circle cx="11" cy="35" r="7"/><circle cx="37" cy="35" r="7"/><path d="m16 30 24-25M32 30 8 5"/>',
      'service':'<path d="M32 5a12 12 0 0 0-15 16L4 34l10 10 13-13A12 12 0 0 0 43 16l-9 6-8-8Z"/>',
      'diamond':'<path d="m4 16 9-10h22l9 10-20 28ZM4 16h40M13 6l11 38L35 6M13 6l11 10L35 6"/>',
      'select':'<path d="M5 8h28v8M5 8v32h28V27"/><path d="m13 25 9 9L44 9"/>',
      'market':'<path d="M5 19h38l-5-12H10ZM8 19v23h32V19M17 42V28h14v14M5 19c0 7 8 7 10 0 2 7 8 7 10 0 2 7 8 7 10 0 2 7 8 7 8 0"/>',
      'sectors':'<path d="M5 5h15v15H5ZM28 5h15v15H28ZM5 28h15v15H5Z"/><circle cx="35.5" cy="35.5" r="7.5"/>',
      'tekirdag':'<path d="M5 33 15 17l9 10 8-18 12 24M5 38h39M4 44c7-4 9 4 16 0s9 4 16 0 7 2 9 0"/>',
      'seal':'<path d="m24 4 5 5 7-1 2 7 6 4-3 7 1 7-7 2-4 8-7-3-7 3-4-8-7-2 1-7-3-7 6-4 2-7 7 1Z"/><path d="m15 23 6 6 12-13"/>',
      'thrace':'<path d="M4 39h40M9 39V24m15 15V24m15 15V24M9 24c0-17 15-17 15 0 0-17 15-17 15 0M4 43h40"/>',
      'toolpin':'<path d="M24 44S7 29 7 18a17 17 0 0 1 34 0c0 11-17 26-17 26Z"/><path d="m16 26 13-13m-9-3 13 13"/>',
      'route':'<circle cx="8" cy="37" r="5"/><circle cx="40" cy="11" r="5"/><path d="M13 37h15a8 8 0 0 0 0-16H20a5 5 0 0 1 0-10h15"/>',
      'stop':'<path d="M7 42V9h34v33M7 17h34M13 42V28h22v14M13 9V5h22v4M3 42h42"/>',
      'go':'<circle cx="24" cy="24" r="19"/><path d="M12 24h24m-9-9 9 9-9 9"/>',
      'book':'<path d="M24 12C17 7 11 6 4 9v30c7-3 13-2 20 3 7-5 13-6 20-3V9c-7-3-13-2-20 3Zm0 0v30M10 16l8 2m-8 7 8 2m12-9 8-2m-8 11 8-2"/>',
      'orbit':'<circle cx="24" cy="24" r="10"/><path d="M40 15A19 19 0 1 0 42 29M40 15l-10 1m10-1 1-10"/>',
      'spot':'<circle cx="24" cy="24" r="18"/><circle cx="24" cy="24" r="6"/><path d="M24 6v6m12 12h6M24 36v6M6 24h6"/>',
      'tools':'<path d="m8 7 33 33m-8-33L8 32m0-25 9-3 7 7-9 9ZM4 36l8-8 8 8-8 8ZM33 7l8-3 3 8-8 8"/>',
    }
    if kind.startswith('monogram:'):
        letters = kind.split(':')[1]
        # Custom letter combinations form each invented brand's emblem.
        return wordmark(letters, 31 if len(letters) == 1 else 25, 8 if len(letters) == 1 else 4, 35, '#FFFFFF', 40)
    return '<g fill="none" stroke="#FFFFFF" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round">'+shapes[kind]+'</g>'

def emblem(kind, accent, x=8, y=8, size=56):
    frame = '<rect width="56" height="56" rx="14"/>'
    if kind in ('diamond','seal','select','shield','register'):
        frame = '<path d="M10 0h36l10 10v36L46 56H10L0 46V10Z"/>'
    elif kind in ('radar','spot','compass','globe','orbit','lens','target'):
        frame = '<circle cx="28" cy="28" r="28"/>'
    return f'<g transform="translate({x} {y}) scale({size/56})"><g fill="{accent}">{frame}</g><g transform="translate(7 7) scale(.875)">{motif(kind)}</g></g>'

def logo_svg(name, kind, accent, dark=False):
    ink = '#F8FAFC' if dark else '#172033'
    paper = '#172033' if dark else '#FFFFFF'
    # A small solid plate keeps wordmarks readable across all existing theme headers.
    art = f'<rect width="320" height="72" rx="10" fill="{paper}"/>'+emblem(kind, accent)
    if len(name) > 19:
        words = name.split()
        first = words[0] if len(words[0]) >= 7 else ' '.join(words[:2])
        second = name[len(first):].strip()
        art += wordmark(first, 24, 78, 31, ink, 232)+wordmark(second, 20, 78, 57, ink, 232)
    else:
        art += wordmark(name, 27, 78, 46, ink, 232)
    return svg(name, art, 320, 72)

def svg(name, art, width, height):
    return f'<svg xmlns="http://www.w3.org/2000/svg" width="{width}" height="{height}" viewBox="0 0 {width} {height}" role="img" aria-labelledby="title"><title id="title">{html.escape(name)}</title>{art}</svg>\n'

def main():
    assert len(BRANDS) == 83 and len({b[0] for b in BRANDS}) == 83
    article_domains = {r[0] for r in json.loads((ROOT/'database/content/directory_articles_2026_10.json').read_text(encoding='utf-8'))}
    assert article_domains == {stem+'.com.tr' for stem, *_ in BRANDS}
    manifest, cards = [], []
    for stem, name, kind, accent in BRANDS:
        dest = OUT/stem
        dest.mkdir(parents=True, exist_ok=True)
        (dest/'logo.svg').write_text(logo_svg(name, kind, accent), encoding='utf-8')
        (dest/'logo-dark.svg').write_text(logo_svg(name, kind, accent, dark=True), encoding='utf-8')
        (dest/'favicon.svg').write_text(svg(name, emblem(kind, accent, 4, 4, 56), 64, 64), encoding='utf-8')
        cairosvg.svg2png(url=str(dest/'favicon.svg'), write_to=str(dest/'favicon.png'), output_width=32, output_height=32)
        for size in (180, 192, 512):
            # Safe inset for rounded app icons and home-screen masks.
            app_icon = svg(name, '<rect width="64" height="64" fill="'+accent+'"/>'+emblem(kind, accent, 10, 10, 44), 64, 64)
            cairosvg.svg2png(bytestring=app_icon.encode(), write_to=str(dest/f'icon-{size}.png'), output_width=size, output_height=size)
        manifest.append({'domain':stem+'.com.tr','name':name,'directory':stem,'motif':kind,'accent':accent})
        cards.append(f'<article><header><span>{len(cards)+1:02}</span><code>{stem}.com.tr</code></header><img src="{stem}/logo.svg" alt="{html.escape(name)}"><div class="night"><img src="{stem}/logo-dark.svg" alt="{html.escape(name)} koyu"></div><footer><img src="{stem}/favicon.svg" alt="Simge"><span>{html.escape(kind.split(":")[0])}</span><i style="background:{accent}"></i></footer></article>')
    (OUT/'manifest.json').write_text(json.dumps(manifest, ensure_ascii=False, indent=2)+'\n', encoding='utf-8')
    (OUT/'index.html').write_text('''<!doctype html><html lang="tr"><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1"><title>83 Rehber · Marka Koleksiyonu</title><style>*{box-sizing:border-box}body{margin:0;background:#f1f3f5;color:#172033;font-family:system-ui,sans-serif}main{max-width:1400px;margin:auto;padding:48px 24px}h1{font-size:40px;letter-spacing:-2px;margin:8px 0}p{color:#64748b}.grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(310px,1fr));gap:20px;margin-top:36px}article{border:1px solid #e2e8f0;background:white;border-radius:16px;overflow:hidden}header,footer{display:flex;align-items:center;gap:12px;padding:14px 18px}header{font-size:12px;color:#64748b}header span{font-weight:700}article>img{display:block;width:100%;padding:24px 14px}.night{background:#172033;padding:24px 14px}.night img{width:100%;display:block}footer{font-size:12px;color:#64748b}footer img{width:32px;height:32px}footer i{width:16px;height:16px;border-radius:50%;margin-left:auto}</style><main><small>YEREL İŞLETMELER / MARKA SİSTEMİ</small><h1>83 ayrı isim. Net bir kimlik.</h1><p>Özgün semboller · Vektör yazılar · Açık ve koyu zemin · Küçük ekran simgeleri</p><section class="grid">'''+''.join(cards)+'</section></main></html>',encoding='utf-8')
    print(f'Generated {len(BRANDS)} brands / {len(BRANDS)*3} SVG files.')

if __name__ == '__main__':
    main()
