<?php

return [
    // Yeni yayında sürümü ve aşağıdaki geçmişi birlikte güncelleyin.
    'number' => '1.7.1',
    'name' => 'Firma Adı Düzeltmesi',
    'released_at' => '2026-10-09',
    'commit' => env('APP_COMMIT'),
    'history' => [
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
