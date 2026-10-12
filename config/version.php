<?php

return [
    // Yeni yayında sürümü ve aşağıdaki geçmişi birlikte güncelleyin.
    'number' => '1.17.0',
    'name' => 'Canlı Vitrin Teması',
    'released_at' => '2026-10-12',
    'commit' => env('APP_COMMIT'),
    'history' => [
        [
            'number' => '1.17.0',
            'name' => 'Canlı Vitrin Teması',
            'released_at' => '2026-10-12',
            'changes' => [
                'Yeni tema: Canlı Vitrin · Sarı Liste (sun-listing). Sarı vurgulu iri başlık, yuvarlak arama çubuğu (hizmet/işletme + şehir), gerçek sayılarla özet, zengin öne çıkan firma kartları (açık/kapalı, öne çıkan, puan, kısa açıklama, ara/incele), dört adım, hızlı kategori, avantajlar, rehber yazıları, şehir bağlantıları ve firma ekleme bandı; mobilde yüzen "Firma Bul" düğmesi.',
                'Tema, rehber ayarlarından seçilir; iç sayfalar mevcut düzende açılır ve yeni üst menüyü kullanır. Özet sayıları (aktif firma, kategori, şehir) yalnızca gerçek ve yeterince büyükse gösterilir.',
            ],
        ],
        [
            'number' => '1.16.0',
            'name' => 'Reklamsız Premium Profil',
            'released_at' => '2026-10-12',
            'changes' => [
                'Premium (süresi dolmamış) firmaların profilinde artık başka firma tanıtımı (Benzer Firmalar, Aynı Kategori, Yakın Çevre) ve reklam gösterilmez; süresi biten Premium normal profil gibi davranır.',
                'Premium üyelik paketlerinin özelliklerine "Reklamsız profil" maddesi eklendi (adında premium geçen paketlere bir kez eklenir; Üyelik Paketleri ekranından düzenlenebilir).',
            ],
        ],
        [
            'number' => '1.15.0',
            'name' => 'Ana Sayfa SEO Başlıkları',
            'released_at' => '2026-10-12',
            'changes' => [
                'Tüm rehberlerde ana sayfa başlığı (title), H1 ve meta açıklaması artık yalnızca marka adı ya da slogan değil: rehberin odağından (usta, esnaf, KOBİ, kurumsal, hizmet, işletme, yerel, kuaför, genel firma) ve şehir kapsamından üretilen, aranabilir ve rehbere özgü metin gösterilir. Başlık en fazla 60, açıklama 120-155 karakterdir.',
                'Editörün bilinçli yazdığı başlık (en az 25 karakter) ve açıklama (en az 110 karakter) olduğu gibi korunur; yalnızca marka adı, oluşturma sırasında yazılan varsayılan başlık ya da kısa slogan olanlar yenilenir.',
                'Rehberin logosu ya da kapak görseli varsa sosyal medya paylaşımında görsel (og:image, twitter:image) çıkar ve paylaşım kartı büyük görselli olur.',
            ],
        ],
        [
            'number' => '1.14.3',
            'name' => 'Rehbere Özgü Footer Açıklaması',
            'released_at' => '2026-10-12',
            'changes' => [
                'Footer ve uygulama (PWA) açıklaması artık tüm rehberlerde aynı sabit cümle değil: rehberin adından, odağından (usta, esnaf, KOBİ, kurumsal, hizmet, işletme, yerel, kuaför) ve şehir kapsamından üretilen, rehbere özgü bir cümle gösterilir.',
                'Rehbere ya da site ayarlarına yazılan açıklama her zaman öncelikli kalır; yazılmamışsa otomatik cümle kullanılır.',
            ],
        ],
        [
            'number' => '1.14.2',
            'name' => 'İlçe Adı Düzeltmesi',
            'released_at' => '2026-10-11',
            'changes' => [
                'Düzeltme: açık ilçe veri kaynağındaki yazım hataları giderildi; noktasız ı yerine i yazılan ilçe adları (Sarioğlan, Hacilar, Akkişla gibi 460 kadar ad) ve bozuk birleşik nokta işaretleri doğru yazıma çevrildi. Merkez ilçeler şehrin doğru yazımlı adını alır.',
                'Yeni catalog:fix-district-names komutu mevcut ilçe adlarını düzeltir (--dry-run ile önce listeler, adresleri değiştirmez); catalog:sync-districts artık yeni eklediği adları da düzelterek yazar.',
            ],
        ],
        [
            'number' => '1.14.1',
            'name' => 'Yerelix Okunabilirlik Güncellemesi',
            'released_at' => '2026-10-11',
            'changes' => [
                'Yerelix temasında Arial yazı karakteri; sıkışık harf aralıkları ve aşırı kalın başlıklar düzeltildi.',
                'Açıklamalar 17–18 px, menü ve düğmeler 16 px, küçük etiketler en az 14 px olacak şekilde büyütüldü; mobilde metinleri küçülten kurallar kaldırıldı.',
                'Firma ve blog başlıkları 22 px; daha rahat satır aralıkları, tablet menüsü ve mobil kayıt düğmesi düzeni.',
            ],
        ],
        [
            'number' => '1.14.0',
            'name' => 'Yerelix Tasarım Yenilemesi',
            'released_at' => '2026-10-11',
            'changes' => [
                'Yerelix için sıcak beyaz ve koyu yeşil renklerle yeni Yerel Rehber teması: belirgin arama, şehir rehberleri, hizmet kategorileri, firma vitrini ve blog alanı.',
                'Masaüstü ve mobilde sade gezinme, görünür ücretsiz firma kayıt çağrıları; alt sayfalarda ortak renk ve yazı karakterleri.',
                'Tema yalnızca yerelix.com.tr rehberine uygulanır; tekrarlanan yüklemelerde mevcut tema düzenlemeleri korunur.',
            ],
        ],
        [
            'number' => '1.13.1',
            'name' => 'Mobil Taşma Düzeltmesi',
            'released_at' => '2026-10-10',
            'changes' => [
                'Düzeltme: blog yazılarında ve liste sayfalarında, yan paneldeki uzun bir yazı başlığı telefonda sütunu ekrandan geniş yapıp sayfayı yana taşırabiliyordu. Tek sütuna düşen ızgaralar tüm temalarda taşmayacak şekilde düzeltildi.',
            ],
        ],
        [
            'number' => '1.13.0',
            'name' => 'Kayıtlı Firma Sahipleri',
            'released_at' => '2026-10-10',
            'changes' => [
                'Firma Yönetimi > Kayıtlı Firma Sahipleri: kayıt formunun 2. adımını tamamlayıp hesap açan firma sahipleri; firma, rehber, yetkili, e-posta, telefon, WhatsApp, kategori, şehir, kaynak ve kayıt zamanıyla listelenir.',
                'Sekmeler (Tümü, Onay bekleyen, Bugün), arama (firma, kişi, e-posta, telefon), rehber filtresi, ayrıntı penceresi, WhatsApp ve firmayı açma düğmeleri; menüde onay bekleyen sayısı rozeti.',
            ],
        ],
        [
            'number' => '1.12.0',
            'name' => 'Yeni Rehber Makaleleri',
            'released_at' => '2026-10-10',
            'changes' => [
                'İşletme sahiplerinin aradığı konularda 10 yeni uzun makale yayına hazırlandı (bilgi tutarlılığı, hizmet açıklaması, iş fotoğrafı, ilçe bazlı arama, çalışma saatleri, hizmet bölgesi, sezonluk takvim, sık sorulan sorular, ilk 14 gün planı, güven sinyalleri).',
                'Her makale tek bir rehberde yayınlanır, yaklaşık 600-700 kelimedir, kaynak bağlantıları içerir ve kesin sonuç vaat etmez; deploy sırasında tekrarlanabilir şekilde eklenir, editör değişiklikleri korunur.',
            ],
        ],
        [
            'number' => '1.11.2',
            'name' => 'Kompakt Çerez Tercihi',
            'released_at' => '2026-10-10',
            'changes' => [
                'Google Ads çerez tercihi, sayfanın altında köşede küçük tek satırlık bir kutu olarak geri geldi; seçimden sonra yalnızca küçük bir "Çerez tercihleri" bağlantısı kalır.',
            ],
        ],
        [
            'number' => '1.11.1',
            'name' => 'Rehber Çözümleme Düzeltmesi',
            'released_at' => '2026-10-10',
            'changes' => [
                'Düzeltme: aynı adrese (slug) sahip firma birden fazla rehberde varsa sahiplenme, yorum ve firma paneli sayfaları başka rehberin kaydını bulup 404 verebiliyordu. Rehber artık rota modelleri çözülmeden önce belirleniyor.',
                'Davet bağlantısı açılamazsa nedeni sistem kaydına yazılıyor.',
            ],
        ],
        [
            'number' => '1.11.0',
            'name' => 'Sahiplenme Davetleri',
            'released_at' => '2026-10-10',
            'changes' => [
                'Gelir > Sahiplenme Davetleri: rehber, şehir ve kategoriye göre firmalar için kısa bağlantılı davet partisi oluşturulur (yalnızca cep numaraları, daha önce davet edilmemiş ve sahibi olmayan firmalar).',
                'Her satırda tek tıkla hazır mesajla WhatsApp açma, gönderildi işaretleme, mesajı görme ve "bir daha yazma" listesi.',
                'Davet bağlantısı (/s/kod) tıklamayı kaydeder, botları saymaz ve firmanın sahiplenme sayfasına utm_source=davet ile yönlendirir; sahiplenme talebi verilince davet "Sahiplendi" olur.',
            ],
        ],
        [
            'number' => '1.10.0',
            'name' => 'Kayıt Sayfası Dönüşüm Güncellemesi',
            'released_at' => '2026-10-10',
            'changes' => [
                'Firma kayıt sayfası başlığı, alt başlığı, fayda maddeleri ve düğme metni reklam adresindeki ?v=anahtar ile değiştirilebiliyor; {rehber}, {sehir} ve {kategori} yer tutucuları destekleniyor. Varyantlar yönetim panelinde Gelir > Kayıt Sayfası Varyantları altında düzenlenir.',
                'Kayıt formu adım 1 sadeleştirildi: "Bu numara WhatsApp\'ta da var" tek tıkla, ilçe isteğe bağlı olarak işaretlendi, uzun kategori listesine arama kutusu eklendi.',
                'Kayıt sayfasına yazdıkça güncellenen canlı profil önizlemesi ve (yeterince firma varsa) rehberdeki yayın sayısı eklendi.',
                'Google Ads çerez tercihi banner\'ı kaldırıldı.',
            ],
        ],
        [
            'number' => '1.9.1',
            'name' => 'Google Ads Firma Kaydı Ölçümü',
            'released_at' => '2026-10-09',
            'changes' => [
                '81 İl Firmalar için Google etiketi ve reklam çerezi tercihleri eklendi; kişiselleştirilmiş reklamlar kapalıdır.',
                'Firma kaydı dönüşümü yalnızca başarılı başvurunun ardından bir kez gönderilir; panel ziyaretleri kayıt sayılmaz.',
                'Etiket ve dönüşüm yapılandırması rehber alan adına göre ayrıldı.',
            ],
        ],
        [
            'number' => '1.9.0',
            'name' => 'Güvenlik ve Hız Güncellemesi',
            'released_at' => '2026-10-09',
            'changes' => [
                'Güvenlik: firma açıklamasına yazılan HTML artık izinli etiket listesiyle temizleniyor (kaydederken ve gösterirken); script, iframe, olay öznitelikleri ve javascript: bağlantıları engellendi.',
                'Güvenlik: kampanya CSV indirme ve rehber değiştirme rotaları yalnızca yöneticiye açıldı.',
                'Güvenlik: giriş (5 hatalı denemede kilit), kayıt, yorum, firma ekleme ve iletişim formlarına deneme sınırı; kayıt, yorum ve firma ekleme formlarına görünmez bot tuzağı eklendi.',
                'Güvenlik: tüm sayfalara X-Content-Type-Options, X-Frame-Options, Referrer-Policy, Permissions-Policy ve (https üzerinde) HSTS başlıkları eklendi.',
                'Hız: ana sayfa verisi, site ayarları ve rehber çözümleme kısa süreli önbelleğe alındı; firma/rehber/ayar kaydedilince otomatik tazelenir.',
                'Hız: sayfa görüntüleme ve firma görüntülenme sayaçları yanıt gönderildikten sonra yazılıyor; botlar ve hatalı sayfalar sayılmıyor.',
                'Hız: Google Fonts artık sayfayı bloklamadan yükleniyor; kullanılmayan yazı tipi paketi kaldırıldı.',
                'Bakım: eski sayfa görüntüleme kayıtlarını (180 gün) her gece temizleyen pageviews:prune komutu eklendi.',
            ],
        ],
        [
            'number' => '1.8.1',
            'name' => 'Telefon Doğrulama ve Rapor İyileştirmeleri',
            'released_at' => '2026-10-09',
            'changes' => [
                'Telefon ve WhatsApp alanları artık Türkiye numarası biçimini doğrular (kayıt, ön başvuru, firma ekleme, firma düzenleme ve iletişim formları); "abcd" gibi değerler tarayıcıda ve sunucuda reddedilir.',
                'Reklam Kaynakları raporuna Rehber sütunu ve "Yarım kalan başvurular" listesi (firma, telefon, rehber, kaynak) eklendi.',
                'Ön başvuru doğrulama hataları artık sistem kaydına yazılır.',
            ],
        ],
        [
            'number' => '1.8.0',
            'name' => 'Reklam Ölçümü ve Kampanya Hunisi',
            'released_at' => '2026-10-09',
            'changes' => [
                'Firma kaydında reklam kaynağı (UTM, Google/Meta tıklama kimliği, yönlendiren site) 30 gün saklanır ve kayıt sırasında kullanıcıya yazılır.',
                'Gelir > Reklam Kaynakları sayfası eklendi: hangi reklamdan kaç kayıt geldiği, kaçının popup\'ı gördüğü, kampanya sayfasına girdiği ve WhatsApp\'a tıkladığı görülür.',
                'Kampanya popup\'ı yenilendi: firma adıyla kişiselleştirme, rehber başına fiyat, kısa fayda listesi, Esc/dışarı tıklama ile kapatma. Etkileşim olmayan kullanıcılara 2 gün sonra bir kez daha gösterilir.',
                'Firma panelinde küçük düğmenin yerine kampanya bannerı eklendi.',
                'Kampanya sayfası genişletildi: nasıl işler (3 adım), sık sorulan sorular, mobilde sabit WhatsApp çubuğu. WhatsApp tıklamaları sayılır.',
                'Kampanya planı artık firmanın şehrine uygun rehberleri seçer: şehir odaklı rehberlere yalnızca kendi şehrinin firmaları eklenir.',
                'Kayıt formunda 1. adım geçildiğinde firma ve telefon bilgisi yarım başvuru olarak kaydedilir; 2. adım tamamlanmasa bile geri dönülebilir. Yönetici Telegram bildirimi alır, tamamlanınca aynı kayıt güncellenir.',
                'Başvurular listesine "Yarım kalanlar" filtresi ve etiketi eklendi; yarım başvuru yanlışlıkla firmaya çevrilemez. Reklam raporunda kaynak bazında yarım kalan sayısı görünür.',
            ],
        ],
        [
            'number' => '1.7.1',
            'name' => 'Firma Adı Düzeltmesi',
            'released_at' => '2026-10-09',
            'changes' => [
                'İçe aktarılan firma adı ve adreslerinde görünen HTML kaçışları (&#8211; gibi) düzeltildi; slug içindeki "8211" kalıntısı temizlenir.',
                'companies:repair-entities komutu eklendi: mevcut firmaları onarır, bu adla üretilmiş açıklamaları yeniler, elle yazılanlara dokunmaz.',
            ],
        ],
        [
            'number' => '1.7.0',
            'name' => 'Reklam Sistemi ve Yeni Temalar',
            'released_at' => '2026-10-09',
            'changes' => [
                'Banner reklam sistemi eklendi: Gelir > Reklamlar bölümünden sayfa üstü ve sayfa altı konumlarına reklam, görsel, metin ve bağlantı girilir.',
                'Reklam önceliği: ücretli reklamveren, hedefi en dar kendi reklamımız, genel kendi reklamımız. Şehir, kategori ve rehber bazında hedefleme yapılabilir.',
                'Gösterim ve tıklama sayıları günlük olarak izlenir; tıklamalar UTM parametreleriyle reklamverene yönlendirilir. Bağlantılar "sponsored nofollow" ve "Reklam" etiketlidir.',
                'Reklam önbelleği Laravel 13 ile uyumlu hale getirildi (aktif reklamlı sayfalarda görülen 500 hatası giderildi).',
                'Yönetim panelindeki tarih alanları Türkiye saatiyle girilir ve gösterilir; veritabanı UTC olarak kalır.',
                'Yeni temalar: Tasarım Kataloğu (dergi tarzı tam alt sayfa ailesiyle) ve Mahalle Panosu v2 (liste/galeri görünümlü ilan panosu).',
                'Split Screen teması bölünmüş ekran ana sayfası ve yeni teal/mercan paletiyle yeniden tasarlandı.',
                'Elegant Premium okunabilirliği artırıldı: daha yüksek kontrast, daha büyük yazılar, Playfair Display başlıklar ve düğme renk hatası düzeltildi.',
                'Toplu Firma Merkezi\'ne rehber seçiminde "Tümünü seç" ve "Temizle" düğmeleri eklendi.',
                'Firmalara rehbere özgü açıklama üreten companies:fill-descriptions komutu eklendi.',
            ],
        ],
        [
            'number' => '1.6.8',
            'name' => 'Rehber Marka Kimlikleri',
            'released_at' => '2026-10-08',
            'changes' => [
                '83 rehber için markaya uygun SVG logolar, koyu zemin sürümleri ve faviconlar hazırlandı.',
                'Logo setlerini domainlerle eşleştiren, mevcut logo yollarını yedekleyen toplu yükleme komutu eklendi.',
                'Telefon ana ekranı ve PWA için kare PNG simgeleri tanımlandı.',
            ],
        ],
        [
            'number' => '1.6.7',
            'name' => 'Genel Paketler ve Cepte Hikâyeler',
            'released_at' => '2026-10-08',
            'changes' => [
                'Ücretsiz, Silver ve Gold paketleri tüm rehberler için genel paket olarak tanımlandı.',
                'Gold paketi popüler paket olarak işaretlendi.',
                'Cepte Hikâyeler teması sadeleştirildi ve ortak firmalar sayaçlara dahil edildi.',
            ],
        ],
        [
            'number' => '1.6.6',
            'name' => 'Güncel İçerik ve Ütopik Temalar',
            'released_at' => '2026-09-25',
            'changes' => [],
        ],
    ],
];
