# Durum notu (2026-10-10, sürüm 1.12.0)

Yeni bir oturum ya da hesapla devam ederken önce `CLAUDE.md`, sonra bu dosya okunur. Her çalışma gününün sonunda güncellenir.

## Yayında olanlar (özet)
- **Temalar:** Tasarım Kataloğu, Mahalle Panosu v2, Split Screen yeniden tasarımı, Elegant okunabilirliği.
- **İçe aktarma:** Toplu Firma Merkezi (çoklu rehber, tekrar stratejisi, geri alma), açıklama üretici (`companies:fill-descriptions`), varlık onarma (`companies:repair-entities`), "Tümünü seç" düğmeleri.
- **Reklam sistemi:** Banner üst/alt, kendi reklamlarımız (genel ve Tekirdağ örneği), şehir hedefleme, panelden düzenleme.
- **Kayıt hunisi:** UTM yakalama, 2 adımlı kayıtta adım 1'de yarım başvuru, kampanya sayfası ve popup, Reklam Kaynakları raporu (rehber sütunu, yarım kalanlar), Türkiye telefon doğrulaması, kampanya coğrafya kuralı.
- **1.9.0 Güvenlik ve hız:** firma açıklaması temizleme, admin-only rotalar, giriş/kayıt/yorum sınırları, bot tuzağı, güvenlik başlıkları, ana sayfa/ayar/rehber önbelleği, sayfa görüntüleme kaydı yanıt sonrası, geceleri eski kayıt temizleme.
- **1.10.0 Kayıt sayfası:** reklama özel başlık varyantları, sadeleşen adım 1, canlı profil önizlemesi.
- **1.11.x Sahiplenme davetleri:** kısa linkli firma davetleri ve WhatsApp'a tek tıkla gönderim; rehber çözümleme düzeltmesi (aynı adresli firmalar); kompakt Google Ads çerez tercihi.
- **1.12.0:** 10 yeni uzun makale (10 rehber).
- **Sunucu:** güvenlik yamaları uygulandı, otomatik güvenlik güncellemesi açık, çekirdek güncellendi (6.8.0-146). Yük, disk ve bellek rahat.

## Bekleyen işler (öncelik sırasıyla)
1. **Sahiplenme daveti ilk partisi:** Gelir > Sahiplenme Davetleri. Tek rehber, tek kategori, 50 firma; sonuca göre mesajı düzelt, büyüt. Günde en fazla 30-50 mesaj.
2. **Firma Konum dosyası:** `storage/app/private/firmakonum/parti-02-100-firma.xlsx` (56 il, 100 firma, 60'ı Otomotiv) yüklenmedi. Karar: olduğu gibi mi, kategori sınırıyla yeniden mi? Kullanım hakkı konusu açık (kullanıcı sitenin sahibi mi?).
3. **Meta (Facebook/Instagram) lead reklamı:** henüz kurulmadı. Küçük bütçeli deneme; kayıt sayfası `?v=` varyantlarıyla birlikte kullanılacak.
4. **Google Ads itirazı:** "Resmi Belgeler ve Hizmetler" politikası "Firma Kaydı" ifadesini işaretledi. Çözüm planı: reklam dilini "Firmanızı Rehberde Yayınlayın" gibi tarafsız yap; sayfada "resmi kurum değiliz" notu, tarafsız adres (`/rehberde-yayinla` takma adı), sayfa başlığı/rozet yeniden yazımı. Kullanıcı reklam tarafını yaptığını bildirdi; sayfa tarafı (1.10.1 önerisi) yapılmadı.
5. **Kayıt sayfası planı kalanları:** madde 3 (adım 2 sadeleşmesi: şifre tekrarını kaldır), madde 5 (yarım kalanlara WhatsApp geri dönüş düğmesi, formu önceden doldurma), madde 6 (varyant bazlı huni raporu).
6. **Coğrafi kapsam:** Şehir odaklı rehberlerde (Tekirdağ, Trakya, İstanbul, İzmir, Ankara) Coğrafi Kapsam hâlâ varsayılan "national"; kampanya şehir kuralı için panelden ayarlanmalı.
7. **GitHub Actions deploy hatası:** `.github/workflows/deploy.yml` neden başarısız oluyor? Hata günlüğüne bakılıp düzeltilebilir (SSH sırrı, bellek/zaman aşımı ya da komut hatası olabilir). Düzelene kadar elle deploy sürer.
8. **Sunucu izleme:** Uptime Kuma + Telegram uyarısı kurulumu (isteğe bağlı: "Sunucu Durumu" panel sayfası).
9. **Çerez/ölçüm:** Google Ads dönüşümü yalnızca izin verenlerde gönderilir; ciddi bütçeyle reklam başlayınca gözden geçir.
10. **Eski test hataları:** 6 bilinen hata (CLAUDE.md'de listeli); istenirse düzeltilir.
11. **Hız (isteğe bağlı):** Cloudflare önünde HTML önbelleği (`no-store` değişikliği), fontları kendi sunucudan sunma, görsellerin WebP'ye çevrilmesi, sitemap parçalama, `pg_trgm` arama indeksi. Statik dosyalarda tarayıcı önbelleği için Cloudflare "Browser Cache TTL: Respect Existing Headers" ve nginx `/build/` için 1 yıl `immutable`.

## Açık sorular
- Firma Konum verisini kullanma hakkı (kendi siteniz mi, izin var mı).
- Makalelerde yazar/denetleyen satırı boş; gerçek bir kişinin adıyla doldurulacak mı?
- Uptime Kuma'yı hangi sunucuya kuracağız (aynı sunucu, çökerse susar; dış bir kontrol de önerildi).

## Sunucu ve operasyon notları
- Deploy **elle**: GitHub Actions deploy akışı hata veriyor, kullanıcı sunucuda kendisi çekiyor: `cd /var/www/firmarehberi && git pull origin main && php artisan migrate --force && php artisan optimize:clear` (+ gerekirse `composer install --no-dev -o`, `npm ci && npm run build`, makale seeder'ı). Her push sonrası hangi adımların gerektiğini yazın.
- Kuyruk işçisi `--queue=imports,default` dinlemeli (supervisor). Kontrol: `supervisorctl status`.
- Zamanlayıcı: kampanya yayını her 5 dakikada, `pageviews:prune` her gece 03:30.
- Aylık bakım: `apt list --upgradable`, `reboot-required` kontrolü, `composer audit`.
- Yeni makale partisi eklemek için: `database/content/<klasör>/manifest.json` + HTML dosyaları, bir seeder sınıfı, test ve `deploy.yml`'ye seeder satırı (bkz. `LongFormMarketingArticlesVol2Seeder`).
