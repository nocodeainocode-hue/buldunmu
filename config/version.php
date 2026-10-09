<?php

return [
    // Yeni yayında sürümü ve aşağıdaki geçmişi birlikte güncelleyin.
    'number' => '1.9.1',
    'name' => 'Google Ads Firma Kaydı Ölçümü',
    'released_at' => '2026-10-09',
    'commit' => env('APP_COMMIT'),
    'history' => [
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
