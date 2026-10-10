# Firma Rehberi Projesi: Claude için çalışma notları

Bu dosya her oturumun başında okunur. Güncel durum ve bekleyen işler için `docs/DURUM.md` dosyasına bakın.

## Proje nedir
Tek yönetim panelinden yönetilen, birbirinden bağımsız görünen 100 firma rehberi sitesi (her biri kendi alan adı ve teması). Gelir modeli: firma sahiplerine rehberde yayın kampanyası (4.900 ₺), ödeme sitede değil **WhatsApp görüşmesi sonrası** alınır.

- Yığın: Laravel 13 / PHP 8.4 / Filament 5 / Livewire / Blade / Tailwind (Vite). Üretimde PostgreSQL, yerelde SQLite. Cache ve oturum veritabanında.
- Çoklu rehber: `Directory` modeli, `BelongsToDirectory` global kapsamı, `SetCurrentDirectory` ara katmanı alan adından rehberi belirler. Rota modelleri çözülmeden önce çalışması için öncelik listesinde `SubstituteBindings`'ten önce (bootstrap/app.php).
- Tema sistemi: `ThemeHelper::TEMPLATES`, `frontend/home/{layout}`, `frontend/{aile}/*` alt sayfaları, `config/directory_themes.php`.
- Deploy: `main`'e push edince GitHub Actions sunucuya bağlanır (`.github/workflows/deploy.yml`): git pull, composer, npm build, migrate, makale seeder'ları, optimize. Sunucuda elle bir şey yapmak normalde gerekmez. Sunucu: Ubuntu 24.04, nginx, PHP-FPM, supervisor (kuyruk), cron (schedule:run), Cloudflare arkasında.

## Değişmeyen kurallar (kullanıcının açık talepleri)
1. **Rehber sayısı her yerde "100" yazılır.** Eski, farklı bir sayı hiçbir yerde (arayüz, metin, test, makale) geçmez. Bu konu kapanmıştır, tartışmaya açmayın.
2. **Her rehber bağımsız görünür.** Halka açık sayfalarda "ağ", "diğer rehberler" ya da rehber sayısı geçmez. Ağ bilgisi yalnızca giriş yapmış firma sahibi panelinde olabilir.
3. **Ödeme WhatsApp'ta alınır.** Sitede ödeme alma akışı kurmayın.
4. **Kampanya coğrafya kuralı:** Firma, uymayan rehbere eklenmez (ör. Ankara firması Tekirdağ/Trakya rehberine eklenmez). `CampaignPlanService::directoryFitsCity` bunu uygular.
5. **Sürüm ve değişiklik kaydı:** Her yayından önce `config/version.php` içinde `number`, `name`, `released_at` ve `history` girişini birlikte güncelleyin. `ApplicationVersionTest` tutarlılığı denetler.
6. **Push:** Commit atın, ancak kullanıcı "pushla/push" demeden `git push` yapmayın. Her seferinde açık onay beklenir.
7. **Commit'lenmeyecek dosyalar:** `tests/Feature/NewDirectoryThemesTest.php` (başkasının değişiklikleri) ve Firma Konum çekme dosyaları (`ScrapeFirmaKonum.php`, `FirmaKonumScraper.php`, `FirmaKonumCategoryMap.php`, `tests/Unit/FirmaKonumScraperTest.php`). Bunlar bilerek yerelde tutulur.
8. **Üslup:** Kullanıcı Türkçe yazar, cevaplar Türkçe olur. Aşırı uyarı, tekrar eden hukuki not ve gereksiz soru istemez. İş net ise doğrudan yapın, kısa ve somut anlatın.

## Çalışma komutları ve tuzaklar
- Testler: `php -d memory_limit=3G vendor/bin/phpunit` (varsayılan bellek yetmez; `php artisan test` alt süreçte limiti yok sayar). Bilinen 6 eski hata var, yeni sayılmaz: `ThemeHelperTest` (pocket-stories genişliği), `AnalyticsDashboardTest` ×2 ve `DirectoryManagementServiceTest` ×2 (403, test kullanıcısı admin değil), `CompanySlugLifecycleTest` (site_settings tablosu yok).
- Windows ortamı: bash heredoc çoğu zaman bozulur. Dosya içeriği için Write aracını, toplu düzenleme için scratchpad'e yazılmış Python betiklerini kullanın. Python betiklerinde dosya CRLF/LF farkına dikkat edin (okuyup `\r\n` normalize edin).
- Laravel 13 önbellekten Eloquent nesnesi okumaz: `ModelCache` (kendi serialize'ı) kullanın ya da dizi saklayıp `hydrate` edin.
- Test ortamında `MODEL_CACHE=false`; önbelleği sınayan testler `config(['performance.model_cache' => true])` yapar.
- Blade: alt görünüm döngü değişkenleri layout'a sızar; `kelime@if` direktif sayılmaz; `@json` eğik çizgiyi kaçırır. PHP metot adları büyük/küçük harfe duyarsız (`setup` ile `setUp` çakışır).
- Testlerde JSON POST için `withCredentials()` gerekir; telefon alanlarında uydurma görünen numaralar (`0212 111 11 11`) `TurkishPhone` kuralına takılır, gerçekçi numara kullanın.
- Yerelde Firma Konum çekerken PHP'nin CA paketi yok: `php -d curl.cainfo="D:/Program Files/Git/mingw64/etc/ssl/certs/ca-bundle.crt" -d openssl.cafile="..."`.
- Kullanıcı için kopyalanacak sunucu komutları ayrı kod bloklarında, her blokta tek komut olarak verilir.

## Önemli yerler
- Reklam sistemi: `AdServer`, `AdCampaign`, `AdController`, `ads:seed-house`. Panel: Gelir.
- Kayıt ve reklam ölçümü: `Attribution`, `CaptureAttribution`, `OwnerPanelController` (ön başvuru/yarım başvuru, kampanya popup'ı), Reklam Kaynakları raporu (`AdAttributionReport`).
- Kayıt sayfası varyantları: `RegisterPageContent`, `RegisterVariant` (`?v=anahtar`, `{rehber}`, `{sehir}`, `{kategori}`). Panel: Gelir > Kayıt Sayfası Varyantları.
- Sahiplenme davetleri: `ClaimInvite`, `ClaimInviteGenerator`, `ClaimInviteController` (`/s/{kod}`). Panel: Gelir > Sahiplenme Davetleri. WhatsApp'a yarı otomatik gönderilir, API yok.
- Güvenlik/hız: `HtmlSanitizer` (firma açıklaması), `Honeypot`, `SecurityHeaders`, `EnsureAdmin`, `ModelCache`, `BotDetector`, `PrunePageViews`, `config/performance.php`.
- Makaleler: `database/content/*` + `database/seeders/*Articles*Seeder.php`; her makale tek rehberde yayınlanır, tekrar çalıştırılabilir, editör değişikliklerini korur.
- Google Ads etiketi yalnızca `config/tracking.php` içinde tanımlı rehberde çalışır; kompakt çerez tercihi `partials/google-ads-consent`.
