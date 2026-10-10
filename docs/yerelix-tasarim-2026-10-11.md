# Yerelix tasarım yenilemesi

Sürüm: **1.14.0 — Yerelix Tasarım Yenilemesi**

Yerelix’in canlı sayfasında görülen Gökyüzü Adaları tasarımının yerine, yönetim panelinden de seçilebilen **Yerelix · Yerel Rehber** (`local-ledger`) teması hazırlandı.

## Yeni görünüm

- Sıcak beyaz zemin, koyu yeşil vurgu ve Manrope yazı karakteri.
- İlk ekranda firma/hizmet/şehir araması ve şehir sayfaları.
- Hizmet kategorileri, firma vitrini, şehir listesi ve blog yazıları.
- Görünür ücretsiz firma kayıt bağlantıları ve işletme sahipleri için tanıtım alanı.
- Ana sayfa ve alt sayfalarda ortak menü, renkler ve yazı karakterleri.
- Küçük ekranlarda sade kategori kutuları ve tek sütun firma kartları.

## Uygulama

```bash
cd /var/www/firmarehberi &&
git pull --ff-only origin main &&
composer dump-autoload -o &&
php artisan db:seed --class=YerelixThemeSeeder --force &&
php artisan optimize:clear
```

Otomatik dağıtıma da aynı yükleyici eklendi. Yükleyici yalnızca `yerelix.com.tr` kaydını günceller. Önceki temanın renk ve düzen özelleştirmeleri temizlenir; ana sayfa başlığı ve alt başlığı yeni tasarım metinleriyle değiştirilir. Firma kayıtları, blog yazıları ve SEO meta alanları korunur. Tema zaten güncelse tekrar çalıştırmak sonraki yönetici düzenlemelerini değiştirmez.

## Doğrulama

Görsel kontrol, ayrı bir yerel SQLite kopyasında örnek firmalarla yapıldı. Üretim veritabanına bağlanılmadı.

- PHP sözdizimi ve Blade derlemesi başarılı.
- Vite derlemesi başarılı.
- Tema uygulaması diğer rehber kaydını değiştirmedi.
- 1366 px masaüstü ve 390 px mobil görünüm kontrol edildi.
- Mobilde yatay taşma görülmedi; arama sonuçları, menü ve firma detay sayfası çalıştı.

Önizleme görselleri: `storage/app/yerelix-design/desktop.png` ve `storage/app/yerelix-design/mobile.png` (yerel dosyalar, örnek veriler).
