# Firma Rehberi Layout Plani

Bu dokuman, coklu SEO firma rehberi siteleri icin 20 farkli gorsel yonu ve hangi Laravel/Blade parcasi uzerinden calisacaklarini tarif eder. Hedef: ayni altyapiyla 100 site uretirken sitelerin birbirinin kopyasi gibi gorunmemesi, ama bakimin tek sistemden yapilmasi.

## Kisa Tespit

Mevcut site temiz ama fazla soluk: hero kontrasti dusuk, kartlar birbirine cok benziyor, CTA alanlari arka planda kayboluyor. Ornek sitelerde daha guclu ticari his var: logo agirlikli firma kartlari, turuncu/karanlik vurgu bloklari, kategori yogunlugu, one cikan firma vitrinleri ve uzun SEO icerigi kullanilmis. Footer ornekte gereksiz uzun; biz footer'i kisa tutup SEO metnini ilgili sayfa icerigine yaymaliyiz.

Kodda 20 template anahtari var; bunlar 5 Blade layout ailesine dagilmali:

- `default`: klasik grid rehber
- `modern`: is odakli yatay liste
- `minimal`: arama odakli sade rehber
- `bold`: kampanya/vitrin agirlikli rehber
- `elegant`: premium/kurumsal rehber

## Ana Kurallar

- Her sitede hero ilk ekranda net arama barina sahip olsun.
- Ilk ekranda veya hemen altinda firma kartlari gorunsun; sadece kategori listesiyle baslama zayif kaliyor.
- Footer kisa kalsin: marka, 4-6 link, iletisim, yasal. Ornek sitedeki gibi link yigini kullanma.
- SEO metni footer'a doldurulmasin; anasayfada 2-3 kisa bolum, sehir/kategori sayfalarinda yerel niyetli metin, firma detayda dogal aciklama olarak dursun.
- Premium kartlar daha gorsel olmali: logo/cover, rozet, puan, konum, "Ara" ve "Detay" aksiyonlari.
- 100 site icin renkler tek basina yeterli degil; bolum sirasi, kart tipi, hero ritmi, grid yogunlugu ve CTA dili de degismeli.

## 20 Layout

| Template key | Yeni isim | Blade ailesi | En iyi kullanim | Gorsel karakter |
| --- | --- | --- | --- | --- |
| `default` | Klasik Yerel Rehber | `default` | Genel firma rehberi | Dengeli kategori, sehir ve firma gridleri |
| `premium-showcase` | Premium Vitrin | `default` | Reklam/paket satilacak rehberler | Premium firmalar ilk sirada, altin vurgu |
| `corporate` | Kurumsal Katalog | `default` | B2B, sanayi, avukat, klinik | Dar alan, ciddi lacivert, keskin kartlar |
| `landing` | Hizli Bul Landing | `default` | Tek sehir/tek kategori siteleri | Guclu hero, yesil guven hissi |
| `modern` | Modern Is Listesi | `modern` | Hizmet pazari, startup hissi | Yatay kartlar, dashboard ritmi |
| `dashboard` | Veri Paneli Rehber | `modern` | Cok kategori/cok sehir siteleri | Istatistik ve sirali liste hissi |
| `split-hero` | Split Reklam Rehberi | `modern` | Kampanyali dikeyler | Sol metin, sag istatistik/vitrin |
| `magazine` | Dergi Rehberi | `modern` | Restoran, turizm, yasam | Serif font, editorial sicaklik |
| `minimal` | Sade Arama | `minimal` | Mikro niche siteler | Bol beyaz alan, az kart |
| `search-first` | Arama Once | `minimal` | Kullanici niyeti net siteler | Arama, son firmalar, kategori etiketleri |
| `mobile-app` | Mobil Uygulama Hissi | `minimal` | Gencler, kuafor, spor, teknoloji | Yuvarlak kartlar, yumusak renkler |
| `step-by-step` | Adim Adim Bul | `minimal` | Hizmet talebi/referans odakli | Basit akistan firma secimi |
| `bold` | Cesur Vitrin | `bold` | Rekabetli ticari dikeyler | Buyuk renk bloklari, agresif CTA |
| `comparison` | Karsilastirma Rehberi | `bold` | Klinik, okul, servis, avukat | Filtre/karsilastirma algisi |
| `timeline` | Yeni Eklenen Akisi | `bold` | Surekli guncellenen rehberler | Tek kolon akis, yeni firma vurgusu |
| `mosaic` | Mozaik Kartlar | `bold` | Logo yogun portallar | 3-4 kolon, marka kartlari onde |
| `elegant` | Elegant Premium | `elegant` | Luks hizmetler, otel, restoran | Serif, altin/lacivert, premium hava |
| `city-focused` | Sehir Odakli | `elegant` | `istanbul...`, `gaziantep...` siteleri | Sehir bloklari ve yerel metin onde |
| `category-mega` | Kategori Mega | `elegant` | Genis kategori agaci olan siteler | Kategori merkezi navigasyon |
| `map-first` | Harita Odakli | `elegant` | Yerel arama, yakindaki isletmeler | Konum, il/ilce ve harita hissi |

## Uygulama Onceligi

1. `HomeController` template anahtarini dogrudan Blade dosyasi sanmamali; `ThemeHelper::layoutFile()` ile aile dosyasina gitmeli.
2. Her layout ailesinde ayni bolumleri farkli siralamak icin `ThemeHelper::sectionOrder()` gercekten kullanilmali.
3. `default`, `bold`, `elegant` icin logo/cover odakli yeni bir `visual` firma karti eklenmeli.
4. Firma detay sayfasi iki varyanta ayrilmali: `compact-local` ve `seo-story`.
5. Footer sade kalmali; uzun SEO metni icin anasayfa/kategori/sehir sayfalarina "Yerel rehber metni" parcasi eklenmeli.

## Firma Detay Sayfasi Icin Oneri

Mevcut detay sayfasi okunur ama ornek detay sayfasindaki kadar SEO tasimiyor. En iyi denge:

- Ustte logo/cover, telefon, WhatsApp, web sitesi ve adres karti.
- Hemen altinda 300-700 kelimelik dogal firma/sektor/sehir metni.
- Hizmetler, neden bu firma, sik sorular, adres/harita, benzer firmalar.
- Yorum bolumu varsa sayfanin sonunda; yoksa bos alan kocaman kalmasin.

## Footer Standarti

Footer maksimum 4 kolon:

- Marka + 1 cumle
- Linkler: Firmalar, Firma Ekle, Hakkimizda, Iletisim
- Iletisim: telefon, e-posta, adres
- Yasal: Gizlilik, Kullanim Sartlari

SEO icin footer'a yuzlerce link basma. Bunun yerine sitemap, kategori sayfalari ve sehir sayfalari uzerinden ic linkleme yap.
