# Rehber marka koleksiyonu — Ekim 2026

83 domainin açıkça eşleştirildiği `manifest.json` ve her marka için:

- `logo.svg`: açık renk taşıyıcı üzerinde vektör yazı ve simge;
- `logo-dark.svg`: koyu zemin sürümü;
- `favicon.svg`, `favicon.png`: sekme simgeleri;
- `icon-180.png`, `icon-192.png`, `icon-512.png`: telefon ve PWA simgeleri.

`index.html` tüm markaların açık ve koyu görünümlerini gösterir. Yazılar Inter
fontundan vektöre dönüştürülmüştür; font yüklenmesine ihtiyaç duymaz. Simgeler
proje için SVG geometrisiyle çizildi. Gradyan ve harici görsel kullanılmadı.
Simgeler tanıtım içindir; herhangi bir doğrulama veya resmi kurum onayı belirtmez.

Sunucuda Python gerekmiyor. Commit edilmiş dosyaları yüklemek için:

```bash
php artisan directories:install-branding --apply --replace-existing
php artisan optimize:clear
```

`--apply` olmadan yalnızca önizleme yapılır. `--replace-existing` olmadan elle
atanmış logolar korunur. Önceki logo/favicon yolları local diskindeki
`branding-backups` klasörüne kaydedilir; eski görsel dosyaları silinmez.
Eksik domain/dosya varsa bütün işlem DB güncellemesinden önce durur.
Tekrar çalıştırıldığında aynı logo yolları kullanılır.

Yerel üretim için Python: `fonttools`, `brotli`, `cairosvg` paketleri ve Cairo
gereklidir. `python scripts/generate_directory_branding.py` ile yeniden üretin.
Inter proje içinde kullanılan fonttur (SIL Open Font License).
