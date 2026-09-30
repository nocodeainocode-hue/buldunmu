<?php

// Her yazı tek bir rehberin arama niyetine göre hazırlanır. Mevcut yazılar
// yeniden yayımlamada değiştirilmez; editörün sonraki düzenlemeleri korunur.
return [
    'kayitlikobi.com.tr' => [
        'title' => 'Küçük işletmeniz internette nasıl bulunur? Müşterinin aradığı 7 bilgi',
        'slug' => 'kucuk-isletme-internette-nasil-bulunur',
        'content_type' => 'guide',
        'primary_query' => 'küçük işletme internette nasıl bulunur',
        'search_intent' => 'informational',
        'excerpt' => 'Bir müşteri adınızı ilk kez duyduğunda hangi bilgilere bakar? İşletme profilinizi yedi pratik adımda kontrol edin.',
        'content' => <<<'HTML'
<p>Yeni bir müşteri sizi çoğu zaman tabelanızdan önce telefon ekranında tanır. Aradığı hizmeti, bulunduğu şehri ve “yakınımda” gibi bir ifadeyi yazar. Karşısına çıkan işletmeler arasında karar verebilmesi için yalnızca isim yetmez: Ne yaptığınızı, nerede olduğunuzu ve size nasıl ulaşacağını hemen anlamalıdır.</p>
<h2>1. Adınız her yerde aynı yazılsın</h2>
<p>Tabelanızda, sosyal hesaplarınızda ve firma profilinizde farklı adlar kullanmak müşterinin doğru işletmeyi bulmasını zorlaştırır. Resmî veya yaygın kullandığınız işletme adını seçin; şube varsa şubeyi ayrıca belirtin.</p>
<h2>2. Hizmetinizi tek cümlede anlatın</h2>
<p>“Kaliteli hizmet” birçok işletmenin söyleyebileceği bir ifade. Bunun yerine “ofisler için klima bakım ve arıza servisi” ya da “özel ölçü mutfak dolabı üretimi” gibi somut bir açıklama yazın. Ürün veya hizmet kapsamınızı olduğundan geniş göstermeyin.</p>
<h2>3. Şehir ve adres bilgisini kontrol edin</h2>
<p>Müşteri hizmet alabileceği yeri bilmek ister. Fiziksel mağazanız varsa açık adresinizi ve konumunuzu doğrulayın. Yerinde hizmet veriyorsanız çalıştığınız il ve ilçeleri anlaşılır biçimde yazın. Taşındığınızda eski adresi güncelleyin.</p>
<h2>4. Doğru telefon ve çalışma saatlerini ekleyin</h2>
<p>Ulaşılmayan bir telefon numarası iyi bir ilk izlenimi bozar. Telefonunuzu, varsa web sitenizi ve saatlerinizi güncel tutun. Hafta sonu veya resmî tatil çalışma düzeniniz farklıysa bunu da belirtin.</p>
<h2>5. Gerçek fotoğraflar kullanın</h2>
<p>İş yerinin dış görünümü, ekip, ürün veya tamamlanmış bir işten fotoğraf, müşterinin ne bekleyeceğini anlamasına yardım eder. Fotoğrafı kullanmaya hakkınız olduğundan ve görüntünün bugünkü işletmenizi yansıttığından emin olun.</p>
<h2>6. Müşterinin ilk sorularını yanıtlayın</h2>
<p>Randevu gerekiyor mu? Hangi bölgelere hizmet veriyorsunuz? Teklif almak için ne paylaşmalı? Bu soruları kısa ve açık biçimde yanıtlamak, ilk telefon görüşmesini de kolaylaştırır. Fiyat işin kapsamına göre değişiyorsa sabit bir rakam uydurmak yerine teklif sürecini anlatın.</p>
<h2>7. Profilinizi düzenli gözden geçirin</h2>
<p>Yeni bir hizmet, taşınma veya numara değişikliği olduğunda profilinizi de yenileyin. Google İşletme Profili gibi kullandığınız diğer kanallardaki bilgilerle tutarlı olması müşterinin güvenini artırır. Hiçbir rehber kaydı Google’da belirli bir sıra garantisi vermez; amaç, işletmenizle ilgilenen kişinin doğru bilgiye ulaşmasıdır.</p>
<h2>İlk adımı bugün atın</h2>
<p>Kayıtlı KOBİ’de işletmenizin bilgilerini tek bir profilde sunarak sizi araştıran kişilere daha açık bir başlangıç noktası verebilirsiniz. <a href="/firma-kayit">Firmanızı kaydetmek için hesap oluşturun</a>; ad, hizmet ve iletişim bilgilerinizi yukarıdaki listeyle kontrol ederek profilinizi tamamlayın.</p>
HTML,
        'faq_items' => [
            ['question' => 'Firma rehberi kaydı Google’da üst sırayı garanti eder mi?', 'answer' => 'Hayır. Rehber profili müşteriye ek bilgi sunar; arama sırası için garanti vermez.'],
            ['question' => 'İşletme bilgilerini ne zaman güncellemeliyim?', 'answer' => 'Telefon, adres, çalışma saati veya hizmet kapsamı değiştiğinde güncelleyin.'],
        ],
        'sources' => ['https://developers.google.com/search/docs/fundamentals/creating-helpful-content'],
    ],
    'esnaftamyanimda.com.tr' => [
        'title' => 'Mahallede esnaf ararken haritada hangi bilgilere bakmalı?',
        'slug' => 'mahallede-esnaf-ararken-haritada-neye-bakmali',
        'content_type' => 'local',
        'primary_query' => 'mahallede esnaf ararken nelere bakılır',
        'search_intent' => 'local',
        'excerpt' => 'Yakındaki işletmeleri haritada karşılaştırırken konum, hizmet alanı, saat ve iletişim bilgisini nasıl değerlendireceğinizi öğrenin.',
        'content' => <<<'HTML'
<p>Acil bir tamir, hızlı bir alışveriş veya düzenli çalışabileceğiniz bir esnaf arıyorsunuz. Haritada size yakın görünen ilk pin cazip gelebilir; yine de kısa bir kontrol, boşa giden yolculuğu ve yanlış beklentiyi önler.</p>
<h2>Pin gerçekten hizmet noktasını mı gösteriyor?</h2>
<p>İşletmenin profiline girip açık adresi kontrol edin. Bazı esnaf dükkânda hizmet verir, bazıları ise müşterinin adresine gelir. Haritadaki konum ile hizmet verdiği ilçe aynı şey olmayabilir. Özellikle yerinde hizmette çalışma bölgesini teyit etmek gerekir.</p>
<h2>Aradığınız işi açıkça yapıyor mu?</h2>
<p>“Tadilat” geniş bir kategori. Siz kombi bakımı, anahtar kopyalama veya perde dikimi arıyorsanız profil açıklamasında bunun açıkça geçmesine bakın. Emin değilseniz telefonla işin kapsamını sorun. Böylece hem siz hem esnaf doğru beklentiyle görüşmeye başlar.</p>
<h2>Gitmeden önce saat ve iletişimi doğrulayın</h2>
<p>Çalışma saatleri değişebilir; telefon numarası güncel olmayabilir. Uzun yol gitmeden önce aramak, özellikle randevulu işlerde faydalıdır. Talebinizi kısa anlatın: bulunduğunuz ilçe, istediğiniz iş ve uygun olduğunuz zaman.</p>
<h2>Teklif isterken aynı bilgileri paylaşın</h2>
<p>Birden fazla işletmeyle görüşecekseniz hepsine benzer bilgiler verin. Malzeme, ölçü, adres ve süre beklentisi fiyatı değiştirebilir. Sadece en düşük rakama bakmayın; nelerin fiyata dahil olduğunu ve işin ne zaman yapılabileceğini de sorun.</p>
<h2>Esnaf için: Müşteri sizi seçmeden önce ne görüyor?</h2>
<p>Bir müşteri haritada pinden profilinize geçtiğinde doğru adres, anlaşılır hizmet açıklaması ve çalışan bir telefon görmek ister. Fotoğraf ekleyebiliyorsanız dükkânınızı veya işinizden gerçek örnekleri kullanın. Hizmet bölgeniz değiştiğinde bilgilerinizi yenileyin. Yakınınızda arayan kişiye en çok yardımcı olan şey, kolayca doğrulanabilen bir profildir.</p>
<p>Esnaf Tam Yanımda’da işletmeniz için bir profil açmak istiyorsanız <a href="/firma-kayit">firma kaydına başlayın</a>. Müşterinin arayacağı kategori, konum ve iletişim bilgilerini dikkatle doldurun; böylece haritadaki pinin arkasında gerçek, anlaşılır bir işletme görsün.</p>
HTML,
        'faq_items' => [
            ['question' => 'Haritadaki en yakın işletme mutlaka bana hizmet verir mi?', 'answer' => 'Hayır. İşletmenin hizmet alanını ve aradığınız işi yapıp yapmadığını profilinden veya telefonla doğrulayın.'],
        ],
    ],
    'firmaendeksi.com.tr' => [
        'title' => 'Tedarikçi araştırırken firma rehberinden nasıl yararlanılır?',
        'slug' => 'tedarikci-arastirirken-firma-rehberi-nasil-kullanilir',
        'content_type' => 'guide',
        'primary_query' => 'tedarikçi araştırırken firma rehberi nasıl kullanılır',
        'search_intent' => 'commercial',
        'excerpt' => 'Sektör ve şehir filtrelerinden kısa liste oluşturmaya, teklifleri karşılaştırmaya kadar pratik bir tedarikçi araştırma yolu.',
        'content' => <<<'HTML'
<p>Yeni bir ambalaj üreticisi, muhasebe desteği ya da teknik servis ararken ilk bulunan firmayı seçmek kolaydır. Kurumunuz için daha iyi yöntem, seçenekleri aynı ölçütlerle karşılaştıran kısa bir liste hazırlamaktır. Firma rehberi bu ilk tarama için kullanışlı bir başlangıç noktasıdır.</p>
<h2>İhtiyacınızı ölçülebilir biçimde yazın</h2>
<p>“Ambalaj firması arıyoruz” yerine ürün türü, yaklaşık adet, teslim şehri ve hedef tarihi belirleyin. Hizmet arıyorsanız işin sıklığını, yerinde çalışma gerekip gerekmediğini ve teslim beklentinizi yazın. Net bir talep, gelen tekliflerin karşılaştırılmasını kolaylaştırır.</p>
<h2>Sektör ve konuma göre aday bulun</h2>
<p>Firma Endeksi’nde sektörler ve firma yoğunluğu üzerinden seçenekleri inceleyebilirsiniz. Aynı kategorideki işletmelerin açıklamalarını okuyun; yalnızca firma adına bakarak karar vermeyin. Teslimat veya yerinde hizmet gerekiyorsa bulunduğunuz şehri ve işletmenin hizmet alanını da hesaba katın.</p>
<h2>Profilde kontrol edilecek dört nokta</h2>
<ul><li><strong>Hizmet kapsamı:</strong> İhtiyacınız olan işi açıkça anlatıyor mu?</li><li><strong>Konum:</strong> Ürün teslimi veya hizmet ziyareti için uygun yerde mi?</li><li><strong>İletişim:</strong> Güncel bir telefon veya başvuru yolu var mı?</li><li><strong>Kanıt:</strong> Proje örnekleri, ürün fotoğrafları veya açıklayıcı bir web sayfası sunuyor mu?</li></ul>
<p>Profil, araştırmanın ilk adımıdır. Kapasite, sertifika, teslim süresi ve sözleşme koşulları gibi kritik bilgileri doğrudan firmayla doğrulayın.</p>
<h2>Teklifleri aynı tabloda karşılaştırın</h2>
<p>Her adaydan aynı kapsam için teklif isteyin. Toplam fiyatın yanında teslim zamanı, ödeme koşulları, revizyon hakkı ve satış sonrası desteği de not edin. Sorularınıza açık yanıt veren firma, süreç boyunca iletişimin nasıl ilerleyeceğine dair de fikir verir.</p>
<h2>Tedarikçiyseniz araştırma listesine girmeyi kolaylaştırın</h2>
<p>Alıcılar “ne iş yapıyor?” sorusunun cevabını saniyeler içinde arar. Ürün gruplarınızı, çalıştığınız bölgeleri ve iletişim yolunuzu anlaşılır yazın. Gerçek örnekler ve güncel bilgiler, doğru taleplerin size gelmesine yardımcı olur. Firma Endeksi’ndeki görünümünüzü oluşturmak için <a href="/firma-kayit">işletmenizi kaydedin</a> ve profilinizi bir alıcının gözünden kontrol edin.</p>
HTML,
        'faq_items' => [
            ['question' => 'Rehberdeki firma profili tek başına seçim için yeterli mi?', 'answer' => 'Hayır. Teknik yeterlilik, ticari koşullar ve gerekli belgeleri firmadan ayrıca doğrulayın.'],
        ],
    ],
    'firmafeneri.com.tr' => [
        'title' => 'Yeni açılan işletme ilk müşterilerine nasıl ulaşır? Yerel görünürlük planı',
        'slug' => 'yeni-acilan-isletme-ilk-musterilere-nasil-ulasir',
        'content_type' => 'guide',
        'primary_query' => 'yeni açılan işletme ilk müşterilerine nasıl ulaşır',
        'search_intent' => 'informational',
        'excerpt' => 'Yeni bir işletme için ilk haftalarda uygulanabilecek tanıtım adımları: net açıklama, doğru konum, gerçek fotoğraf ve ölçülebilir iletişim.',
        'content' => <<<'HTML'
<p>Yeni bir işletme açtığınızda en zor soru genellikle şudur: “Bizi henüz tanımayan kişi nasıl bulacak?” Büyük bir reklam bütçesi olmadan da işe yarar bir başlangıç yapabilirsiniz. Önce sizi bulan kişinin ne sunduğunuzu anlayacağı bir dijital vitrin kurun; sonra hangi kanaldan talep geldiğini izleyin.</p>
<h2>İlk hafta: Tek bir net mesaj belirleyin</h2>
<p>Her şeyi yaptığınızı söylemek yerine en iyi çözdüğünüz ihtiyacı seçin. “Ev ve ofis için aynı gün perde dikimi” gibi bir ifade, müşteriye hızlı bir cevap verir. Aynı açıklamayı firma profilinizde, sosyal hesaplarınızda ve varsa web sitenizde tutarlı kullanın.</p>
<h2>İkinci adım: Bulunabilecek bir profil hazırlayın</h2>
<p>İşletme adınız, kategori, adres veya hizmet bölgesi, telefon ve çalışma saatleri eksiksiz olsun. Açılış duyurusu yapmadan önce bir arkadaşınızdan telefonuyla sizi aramasını isteyin: Doğru şehre ve doğru hizmete ulaşabiliyor mu? İletişim düğmesi çalışıyor mu?</p>
<h2>Üçüncü adım: İlk görselleriniz gerçek olsun</h2>
<p>Dükkânın dış cephesi, çalışma alanı, ürün ya da tamamlanan işten birkaç net fotoğraf yeterli olabilir. Stok görsellerle farklı bir beklenti yaratmayın. Müşteri geldiğinde gördüğü yer ile ekranda gördüğü yerin uyuşması güven kurar.</p>
<h2>Dördüncü adım: Çevrenizden gelen soruları içeriğe dönüştürün</h2>
<p>“Randevu gerekiyor mu?”, “Hangi ilçelere geliyorsunuz?” veya “Teslim süresi ne kadar?” gibi soruları not edin. Profil açıklamanızda bunları yanıtlayın. Müşterinin karar vermesine yarayan bilgiler, yalnızca tanıtım cümlelerinden daha değerlidir.</p>
<h2>İlk ay sonunda neye bakmalı?</h2>
<p>Kaç telefon veya mesaj aldığınızı, insanların hangi hizmeti sorduğunu ve sizi nereden bulduğunu basit bir listede tutun. Bir kanaldan hiç talep gelmemesi hemen başarısızlık anlamına gelmez; önce profilin doğru ve güncel olduğundan emin olun. Hiçbir platform tek başına müşteri ya da arama sırası garantisi vermez.</p>
<p>Firma Feneri şehirdeki firmaları, hizmetleri ve yazıları bir arada keşfetmeye açıyor. Siz de firmanızı bu panoya taşımak istiyorsanız <a href="/firma-kayit">firma kaydı oluşturun</a>. Profilinizi yayımlamadan önce bir yabancının ilk bakışta ne yaptığınızı anlayıp anlamadığını kontrol edin.</p>
HTML,
        'faq_items' => [
            ['question' => 'Yeni işletme için ilk dijital adım nedir?', 'answer' => 'Ad, hizmet, konum, telefon ve saat bilgilerinin doğru olduğu anlaşılır bir firma profili hazırlamak iyi bir başlangıçtır.'],
        ],
    ],
    'hizmetyakinda.com.tr' => [
        'title' => 'Yakınımdaki hizmeti seçerken hangi 6 soruyu sormalıyım?',
        'slug' => 'yakindaki-hizmeti-secerken-sorulacak-sorular',
        'content_type' => 'local',
        'primary_query' => 'yakındaki hizmeti seçerken sorulacak sorular',
        'search_intent' => 'local',
        'excerpt' => 'Yakındaki bir servis veya uzmanla görüşmeden önce hizmet alanı, kapsam, süre ve ücret hakkında sorulacak altı net soru.',
        'content' => <<<'HTML'
<p>“Yakınımda” diye aramak seçenekleri daraltır, ama doğru hizmeti bulduğunuz anlamına gelmez. Bir tesisatçı, temizlik ekibi veya teknik servisle anlaşmadan önce birkaç kısa soru sormak hem zaman hem de yanlış anlaşılma riskini azaltır.</p>
<h2>1. Bulunduğum adrese hizmet veriyor musunuz?</h2>
<p>Haritadaki işletme size yakın olsa da yerinde hizmet alanı farklı olabilir. İlçe ve mahalleyi söyleyerek ulaşım durumunu öğrenin. Dükkâna gitmeniz gerekiyorsa adresi ve çalışma saatlerini ayrıca doğrulayın.</p>
<h2>2. Tam olarak hangi işleri yapıyorsunuz?</h2>
<p>“Tamir” veya “bakım” tek başına yeterince açık değildir. Cihazın türünü, sorunun belirtilerini ya da istediğiniz işin ölçüsünü anlatın. Uzmanlık alanı dışında kalan işlerde başka bir firmaya yönlendirilmek, yanlış randevudan iyidir.</p>
<h2>3. İlk görüşme için hangi bilgileri göndermeliyim?</h2>
<p>Model numarası, fotoğraf, ölçü veya kısa bir video ön değerlendirmeyi kolaylaştırabilir. Kişisel bilgilerinizi yalnızca iş için gerekli ölçüde paylaşın. Fotoğrafın sorunu gösterdiğinden ve kullanma hakkınız olduğundan emin olun.</p>
<h2>4. Ne zaman gelebilirsiniz, iş ne kadar sürer?</h2>
<p>Müsaitlik ile işin bitiş süresi aynı değildir. Randevu saatini, tahmini çalışma süresini ve parça temini gerekirse ne olacağını sorun. Acil işlerde kesin olmayan bir süreyi kesin söz gibi değerlendirmeyin.</p>
<h2>5. Ücrete neler dahil?</h2>
<p>Servis, keşif, malzeme ve işçilik ücretlerinin ayrı olup olmadığını öğrenin. Yerinde inceleme olmadan kesin fiyat verilemiyorsa hangi durumda fiyatın değişebileceğini sorun. Anlaştığınız kapsamı mesajla özetlemek faydalıdır.</p>
<h2>6. İş sonrası destek nasıl işliyor?</h2>
<p>İş tamamlandığında fatura, garanti veya tekrar ziyaret koşullarını sorun. Gereken belgeler hizmet türüne göre değişir. Önemli bir karar veriyorsanız firmanın kimlik ve yetki bilgilerini doğrudan doğrulayın.</p>
<h2>Hizmet sağlayıcıysanız cevapları profilinize taşıyın</h2>
<p>Müşterilerin tekrar tekrar sorduğu sorular, firmanızın açıklamasında bulunması gereken bilgilerdir. Hizmet verdiğiniz ilçeleri, iş kapsamınızı ve ulaşılabilir telefonunuzu açık yazın. Hizmet Yakında haritasında sizi gören kişi ne zaman ve nasıl iletişime geçeceğini anlasın. <a href="/firma-kayit">Firmanızı kaydederek profilinizi oluşturun</a> ve ilk müşterinin sorularını daha aramadan yanıtlayın.</p>
HTML,
        'faq_items' => [
            ['question' => 'Yakındaki hizmet için fiyatı telefonda kesinleştirebilir miyim?', 'answer' => 'İşin kapsamına bağlıdır. Keşif gerekiyorsa ücret kalemlerini ve fiyatın hangi koşullarda değişebileceğini sorun.'],
        ],
    ],
];
