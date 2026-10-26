<?php

return [
    // Yeni yayında sürümü ve aşağıdaki geçmişi birlikte güncelleyin.
    'number' => '1.29.1',
    'name' => 'Kampanya Popup Gecikmesi',
    'released_at' => '2026-10-26',
    'commit' => env('APP_COMMIT'),
    'history' => [
        [
            'number' => '1.29.1',
            'name' => 'Kampanya Popup Gecikmesi',
            'released_at' => '2026-10-26',
            'changes' => [
                'Firma panelindeki kampanya popup\'ı artık sayfa açılır açılmaz değil, 6 saniye sonra yumuşakça açılır; 100 sayacı popup göründüğü anda başlar.',
                'Popup fiyatı değil "100 firma rehberinde aynı anda yayın" vaadini öne çıkarır; fiyat ve rehber başına tutar popup\'tan kaldırıldı (kampanya sayfasında duruyor).',
            ],
        ],
        [
            'number' => '1.29.0',
            'name' => 'Başvurulara Otomatik WhatsApp Mesajı',
            'released_at' => '2026-10-26',
            'changes' => [
                'Firma başvurusu gelince (kayıt, yarım kayıt, profil sahiplenme, firma ekle) başvuru sahibinin cep numarasına, başvurunun geldiği rehberin adı ve alan adıyla WhatsApp mesajı gider (sunucudaki WAHA üzerinden).',
                'Mesaj gecikmeli ve gönderim anındaki duruma göre seçilir: 1. adımda kalan başvuruya 15 dakika sonra "kaydı tamamlayalım" mesajı; bu arada kayıt tamamlandıysa 2 dakika sonra "başvurunuz alındı" mesajı. Aynı başvuruya bir kez, aynı numaraya 24 saatte bir kez mesaj gider; sabit hatlar ve reddedilen başvurular atlanır; gönderim hatasında yeniden denenir.',
                'Açık WAHA anahtarı içeren eski mesaj-gonder.php ve setup-waha.php depodan kaldırıldı (anahtar sunucuda yenilendi, WAHA yalnızca 127.0.0.1\'de dinliyor).',
            ],
        ],
        [
            'number' => '1.28.0',
            'name' => 'Posta Pulu Teması',
            'released_at' => '2026-10-25',
            'changes' => [
                'Yeni tema: Posta Pulu · Mektup Kutusu (posta-pulu). Uçak postası şeritli zarf hero: adres bloğu biçiminde arama ("Kime: ne arıyorsunuz?" / "Nereye: şehir"), köşede her gün değişen "Günün pulu" firması ve şehir/tarih damgası (İstanbul saatine göre), yanda "Masadaki pullar".',
                '"Yeni gelen mektuplar" tırtıklı pul firma kartları (büyük Ara, WhatsApp, gerçekse taahhütlü/onaylı damgası), kategoriler için "Pul tabakası", şehirler için posta damgaları, blog için kartpostallar ve "Burada sizin pulunuz olabilir" kayıt zarfı; mobilde sabit "Firma bul / Pulunu yapıştır" çubuğu. Gerçek bir posta markası kullanılmaz.',
            ],
        ],
        [
            'number' => '1.27.0',
            'name' => 'Yaprak Takvim Teması',
            'released_at' => '2026-10-25',
            'changes' => [
                'Yeni tema: Yaprak Takvim · Günün Firması (yaprak-takvim). Duvar takvimi metaforu: hero bugünün takvim yaprağı (büyük kırmızı gün rakamı, Türkçe ay ve gün adı, İstanbul saatine göre); "günün sözü" yerine her gün değişen "Günün firması" (büyük Ara ve WhatsApp düğmesi) ve yaprağın üzerinde arama formu.',
                'Sağda "Koparılan yapraklar": dünün ve önceki günlerin firmaları yırtık yapraklar olarak; "Bu haftanın yaprakları" firma kartları, "Hizmet takvimi" kategorileri, "Şehir almanağı", "Takvim arkası yazıları" blog bölümü ve "Takvimde yerinizi alın" kayıt çağrısı. Sayılar yalnızca gerçek ve yeterince büyükse gösterilir; mobilde sabit Ara / Ekle çubuğu.',
            ],
        ],
        [
            'number' => '1.26.0',
            'name' => '83 Yeni Rehber Yazısı: Soru ve Adım Rehberleri',
            'released_at' => '2026-10-23',
            'changes' => [
                'Her rehbere bir yeni rehber yazısı (83 yazı, 700 kelime civarı): soru biçiminde başlıklar ve "3 adımda", "5 adımda" gibi adım rehberleri, 2026 güncel bilgisi. Yazılar yalnızca kendi rehberinde yayınlanır ve tekrar çalıştırılabilir.',
            ],
        ],
        [
            'number' => '1.25.0',
            'name' => 'Beyaz Ofis ve Taş Lobi Temaları',
            'released_at' => '2026-10-20',
            'changes' => [
                'Yeni tema: Beyaz Ofis · Kurumsal Vitrin (beyaz-ofis). Bembeyaz zemin, lacivert ve ince çizgiler; üstte başlık, arama ve numaralı hizmet dizini kartı; firma kartları baş harfli, büyük Ara ve WhatsApp düğmeli; hizmet alanları numaralı satır listesi; lacivert kayıt bandı. Manrope yazı tipi.',
                'Yeni tema: Taş Lobi · Mermer Kurumsal (tas-lobi). Taş rengi zemin, antrasit metin, bronz çizgiler ve serif başlıklar (Cormorant Garamond); çift çerçeveli arama levhası; çift çerçeveli firma levhaları; noktalı kılavuzlu "Kat planı" hizmet dizini; baklava ayraçlı şehir satırı; forma odaklanınca belirginleşen "Levhanızı asın" kayıt çağrısı.',
                'Her iki temada sayılar yalnızca gerçek ve yeterince büyükse gösterilir; boş rehberde boş durum metinleri; mobilde sabit Firma ara / Firma ekle çubuğu; 360 px genişlikte yatay taşma yok; animasyonlar prefers-reduced-motion ile kapanır.',
            ],
        ],
        [
            'number' => '1.24.0',
            'name' => 'Neon Bulvar Teması',
            'released_at' => '2026-10-20',
            'changes' => [
                'Yeni tema: Neon Bulvar · Gece Vitrini (neon-bulvar). Gece göğü, ay ve yıldızlar; CSS ve satır içi SVG ile çizilmiş üç katmanlı şehir silueti (yanıp sönen pencereler, anten ışıkları, akan yol çizgisi); neon tüp başlık; ışıklı vitrin camı içinde arama. Her kategori kendi neon renginde yanan bir tabela, firmalar büyük Ara ve WhatsApp düğmeli ışıklı vitrin kartları, şehir durakları, "Sönük tabela" kayıt çağrısı (forma odaklanınca yanar). Mobilde sabit Ara / Tabelanı yak çubuğu.',
                'Animasyonlar hafif ve prefers-reduced-motion ile kapanır; sayılar yalnızca gerçek ve yeterince büyükse gösterilir; boş rehberde boş durum metinleri; 360 px genişlikte yatay taşma yok.',
            ],
        ],
        [
            'number' => '1.23.0',
            'name' => '83 Yeni Rehber Makalesi',
            'released_at' => '2026-10-20',
            'changes' => [
                'Her rehbere birer yeni uzun makale (83 yazı, 700 kelime civarı): ev, işletme, hizmet ve tüketici rehberleri; arama niyetine uygun başlık, açıklama ve iç bağlantılar. Yazılar yalnızca kendi rehberinde yayınlanır ve tekrar çalıştırılabilir.',
            ],
        ],
        [
            'number' => '1.22.0',
            'name' => 'Tabela Çarşısı ve Çini Pano Temaları',
            'released_at' => '2026-10-19',
            'changes' => [
                'Yeni tema: Tabela Çarşısı · Esnaf Sırası (tabela-carsi). CSS ile çizilmiş çarşı sokağı: her kategori tabelalı, tenteli bir dükkân (vitrinde gerçek firma sayısı), zincirle asılı "Esnafa sor" araması, "Çarşının vitrini" firma kartları (büyük Ara ve WhatsApp), meslek sokakları, şehir levhaları ve "Tabelanı as" kayıt çağrısı. Kepenkler saate bağlı değil; mobilde sabit "Esnafa sor / Tabelanı as" çubuğu.',
                'Yeni tema: Çini Pano · İznik Karosu (cini-pano). Krem sırlı karo zemin, SVG lale, karanfil, saz yaprağı ve hatayi motifleri; sır bandıyla çevrili madalyon içinde arama; köşe motifleri birleşerek rozet oluşturan kategori karoları; büyük Ara düğmeli firma kartları; mercan renk yalnızca kayıt çağrısında.',
                'Sayılar yalnızca gerçek ve yeterince büyükse gösterilir; boş rehberde boş durum metinleri; 360 px genişlikte yatay taşma yok.',
            ],
        ],
        [
            'number' => '1.21.1',
            'name' => 'Toplu Yüklemede Enlem ve Boylam',
            'released_at' => '2026-10-18',
            'changes' => [
                'Toplu firma şablonuna google_maps sütununun yanına latitude ve longitude sütunları eklendi (Enlem/Boylam, lat/lng başlıkları da tanınır; virgüllü ondalık kabul edilir). Konum içermeyen kısa Google linklerinde haritayı bu değerler çizer; hatalı ya da aralık dışı değer yok sayılır.',
            ],
        ],
        [
            'number' => '1.21.0',
            'name' => 'İndeksleme Temelleri ve 20 Yeni Makale',
            'released_at' => '2026-10-18',
            'changes' => [
                'robots.txt artık her rehberde kendi site haritasını gösteriyor: statik public/robots.txt dosyası rehbere özel rotayı gölgeliyordu. Panel, arama, form ve yönlendirme adresleri taramadan çıkarıldı.',
                'www adresi tek adrese (www\'siz) 301 ile yönlenir; aynı sitenin iki kopyası indekslenmez.',
                'Site haritası: bölgesel rehberlerde 404 veren şehir adresleri çıkarıldı, "Diğer İller" eklendi; kategori, şehir ve ana sayfaya gerçek lastmod; boş blog ve iş ilanı listeleri çıkarıldı; doğru içerik türü ve 15 dakikalık önbellek.',
                'Coğrafya modu boş olan rehberde şehir sayfaları 404 veriyordu; boş mod artık ulusal sayılır.',
                'İç sayfa başlıklarına rehber adı eklenir ("Hakkımızda | Rehber", "Tekirdağ Firmaları | Rehber"); firma başlığı "Ad – Kategori, Şehir". Blog yazı başlıkları değişmez.',
                'Çalışma saati şeması: Cumartesi artık Cuma, Pazartesi Pazar sayılmıyor; "Pazartesi-Cuma" aralıkları her güne açılıyor.',
                'Makalesi olmayan 13 rehber ve rastgele 7 rehber için arama niyetli 20 yeni rehber yazısı (Vol7: kombi bakımı, çilingir, oto tamircisi, halı yıkama, çatı aktarma...).',
            ],
        ],
        [
            'number' => '1.20.1',
            'name' => 'Kartvizitlik Yelpazesi',
            'released_at' => '2026-10-16',
            'changes' => [
                'Kartvizitlik hero: tek kart + iki boş kâğıt yerine üç gerçek firma kartından yelpaze. Yelpaze sağ sütunu doldurur ve başlık/arama hizasında ortalanır; önde siyah öne çıkan kart, arkada kâğıt renkli kartlar. Fareyle ya da klavyeyle odaklanınca açılır ve üç numara da okunur; dokunmatik geniş ekranda açık durur.',
                'Kart rafı artık firma düşürmüyor (6-9 firmalı rehberlerde bazı firmalar ana sayfadan kayboluyordu); yelpazedeki firmalar rafta tekrar edilmez.',
                'Açıklaması olmayan firmada "Ayrıntılar kartın tamamında." dolgu yazısı kaldırıldı.',
            ],
        ],
        [
            'number' => '1.20.0',
            'name' => 'Kartvizitlik Teması',
            'released_at' => '2026-10-16',
            'changes' => [
                'Yeni tema: Kartvizitlik · Kart Dosyası (kartvizitlik). Her firma sabit düzenli bir kartvizit: kim, ne, nerede ve en iri yazıyla sol altta telefon (şeridin tamamı arama bağlantısı) + WhatsApp. Kraft masa hero, çizgili "Arama fişi" arama formu, masaüstünde yelpaze kartlar, "Masadaki kartlar" rafı, şu an açık firmalar için hızlı arama listesi, kategori çekmeceleri, şehir kutuları ve "Kartını bırak" boş kartı.',
                'Sayılar yalnızca gerçek ve yeterince büyükse gösterilir; boş rehberde boş kart durumları; mobilde alt sabit "Kart ara / Kartını bırak" şeridi; 360 px genişlikte yatay kaydırma yok; erişilebilirlik (kontrast, odak, dokunma alanları, azaltılmış hareket).',
                'Kayıt sayfası, boş karttan gelen firma adını formda önceden doldurur. Paylaşılan mobil menü düğmesi aria-expanded durumunu günceller.',
            ],
        ],
        [
            'number' => '1.19.2',
            'name' => 'Panelde Rehbere Göre Kategori Durumu',
            'released_at' => '2026-10-15',
            'changes' => [
                'Kategoriler listesi üstten seçili rehbere göre durum gösterir: o rehberde gizlenmiş kategori "Aktif" yerine "Bu rehberde gizli" yazar.',
                'Seçili rehber için "Görünenler / Gizlenenler" filtresi ve satırda "Bu rehberde gizle / göster" işlemi eklendi.',
            ],
        ],
        [
            'number' => '1.19.1',
            'name' => 'Rehbere Özel Kategori Listesi',
            'released_at' => '2026-10-15',
            'changes' => [
                'Yeni komut: directory:set-categories. Bir rehberde yalnızca verilen kategorileri verilen sırayla gösterir, diğerlerini o rehberde gizler (silmez); eksik kategorileri oluşturur ve yeni açılanları diğer rehberlerde gizler.',
                'Rehbere ait firmalar ada (berber, nail, epilasyon, spa…) ve eski kategoriye göre yeni kategorilere taşınır; eşleşmeyenlere dokunulmaz ve listelenir. Varsayılan kuru çalıştırmadır, --apply ile uygulanır.',
                'kuaforrehberi.com.tr için: Unisex Kuaför, Kadın Kuaför, Erkek Kuaför, Spa Merkezi, Epilasyon, Tırnak Salonu, Güzellik Merkezi.',
            ],
        ],
        [
            'number' => '1.19.0',
            'name' => 'İletişim Formu Spam Koruması',
            'released_at' => '2026-10-14',
            'changes' => [
                'İletişim formundaki güvenlik sorusu artık oturuma bağlı değil: toplama, çıkarma ve çarpma karışık, imzalı ve tek kullanımlık; bot cevabı hesaplasa da belirteci yeniden kullanamaz.',
                'İletişim formuna gizli alan (honeypot) ve zaman tuzağı eklendi; çok hızlı gönderimler, dolu gizli alan, aynı mesajın tekrarı ve saatlik sınırı aşan gönderimler sessizce elenir.',
                'Çok bağlantılı ya da bilinen spam kalıplarını içeren mesajlar "Spam" durumuyla kaydedilir (silinmez, menü rozetine eklenmez); İletişim Mesajları listesinde Durum filtresiyle ayrıştırılır.',
            ],
        ],
        [
            'number' => '1.18.5',
            'name' => 'Yarım Başvuru İşlemleri',
            'released_at' => '2026-10-13',
            'changes' => [
                'Firma Talepleri menüsündeki rozet yalnızca yeni (kayıt formunun 2. adımını tamamlamış) başvuruları sayar; yarım kalanlar rozete eklenmez.',
                'Yarım kalan başvuruların satırında yeni işlemler: "Firma Olarak Ekle" (kategori seçilir, firma yayında eklenir ve başvuru kapanır) ve "Kapat" (ilgilenilmeyecek olarak kapatır). Toplu işlem: "Seçili yarım başvuruları kapat" (tamamlanmış başvurulara dokunmaz).',
            ],
        ],
        [
            'number' => '1.18.4',
            'name' => 'Boş Firma Formu',
            'released_at' => '2026-10-13',
            'changes' => [
                'Yönetim panelinde yeni firma eklerken Hizmet Maddeleri ve Öne Çıkan Özellikler alanları artık boş satırlarla açılmaz; gerekirse Hizmet Ekle ve Özellik Ekle düğmeleriyle eklenir.',
            ],
        ],
        [
            'number' => '1.18.3',
            'name' => 'Firma Talepleri Rozeti Düzeltmesi',
            'released_at' => '2026-10-13',
            'changes' => [
                'Menüde Firma Talepleri üzerindeki bekleyen sayısı, kayıt formunun 1. adımında kalan (yarım) başvurular dahil incelenmemiş tüm başvuruları yeniden sayar; önceki sürümdeki kısıtlama geri alındı.',
            ],
        ],
        [
            'number' => '1.18.2',
            'name' => 'Firma Talepleri Rozeti',
            'released_at' => '2026-10-13',
            'changes' => [
                'Menüde Firma Talepleri üzerindeki bekleyen sayısı artık yalnızca kayıt formunun 2. adımını tamamlamış yeni başvuruları gösterir; yarım kalan başvurular listede görünmeye devam eder ama rozete eklenmez.',
            ],
        ],
        [
            'number' => '1.18.1',
            'name' => 'Firma Haritası Düzeltmesi',
            'released_at' => '2026-10-13',
            'changes' => [
                'Düzeltme: firma detayındaki Konum bölümünde Google arama/yer bağlantıları çerçeveye konulunca "google.com bağlanmayı reddetti" görünüyordu. Artık her bağlantıdan koordinat bulunup gömülebilir harita adresi üretilir; koordinat yoksa adresle arama yapılır.',
                'Konum bilgisi hiç yoksa harita bölümü çizilmez, kırık çerçeve görünmez. Yer bağlantılarındaki !3d/!4d koordinat biçimi de artık okunur.',
            ],
        ],
        [
            'number' => '1.18.0',
            'name' => 'Yeni Rehber Makaleleri ve Başlık Düzeltmesi',
            'released_at' => '2026-10-13',
            'changes' => [
                'Rehber önem listesindeki ilk 20 rehber için (Kobiva, Buldun mu?, Yerelio, Buldora, Firmondo, Seçmeden Önce, Yerelix, Esnaf Pusulası…) 20 yeni yararlı makale hazırlandı; her biri en az 500 kelime, yalnızca kendi rehberinde yayınlanır ve tekrar çalıştırılabilir (LongFormMarketingArticlesVol4Seeder).',
                'Makale başlıkları en fazla 60 karakter (arama sonuçlarında kesilmez), meta açıklamaları 120-155 karakterdir; testlerle denetlenir.',
                'Düzeltme: sayfa başlığı (title) ve meta açıklamasında tırnak, kesme işareti ve & karakterleri iki kez kaçırılıyordu (&amp;quot; gibi görünüyordu); tek kez kaçırılacak şekilde düzeltildi (og:title ve og:description dahil).',
            ],
        ],
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
