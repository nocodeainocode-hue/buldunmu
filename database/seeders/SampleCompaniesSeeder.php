<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\District;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SampleCompaniesSeeder extends Seeder
{
    public function run(): void
    {
        $cats = Category::active()->pluck('id', 'name')->toArray();
        $cities = City::pluck('id', 'name')->toArray();
        $districts = District::pluck('id', 'name')->toArray();

        $catId = fn(string $name) => $cats[$name] ?? throw new \RuntimeException("Category not found: {$name}");
        $cityId = fn(string $name) => $cities[$name] ?? throw new \RuntimeException("City not found: {$name}");
        $distId = fn(?string $name) => $name ? ($districts[$name] ?? null) : null;

        $companies = [
            // ── Restoran ──
            ['name' => 'Meşhur Kebapçı Yusuf',          'cat' => 'Restaurant ve Lokantalar', 'city' => 'İstanbul', 'dist' => 'Kadıköy' , 'phone' => '+90 216 345 67 89', 'whatsapp' => '+905356781234', 'desc' => '1965\'ten beri Gaziantep usulü kebap ve lahmacun. Odun ateşinde pişen antep kebaplarıyla meşhur.', 'premium' => true, 'verified' => true],
            ['name' => 'Deniz Yıldızı Balık Restoran',  'cat' => 'Restaurant ve Lokantalar', 'city' => 'İzmir'   , 'dist' => 'Karşıyaka', 'phone' => '+90 232 456 78 90', 'whatsapp' => '+905357892345', 'desc' => 'Körfez manzaralı, günlük taze balık ve mezeler.', 'premium' => true],
            // ── Gıda ──
            ['name' => 'Pizza Romano İtalyan Mutfağı',   'cat' => 'Gıda'                    , 'city' => 'Ankara'  , 'dist' => 'Çankaya' , 'phone' => '+90 312 456 78 90', 'whatsapp' => '+905358903456', 'desc' => 'Taş fırında odun ateşinde İtalyan pizzaları. Glutensiz ve vegan seçenekler mevcut.', 'verified' => true],
            ['name' => 'Kahve Dünyası Butik Kavurma',    'cat' => 'Gıda'                    , 'city' => 'İstanbul', 'dist' => 'Beşiktaş', 'phone' => '+90 212 567 89 01', 'whatsapp' => '+905359014567', 'desc' => 'Özel kavrum kahve çekirdekleri ve butik kahve deneyimi.'],
            // ── Sağlık ──
            ['name' => 'Özel Sağlık Merkezi Tıp Kliniği','cat' => 'Sağlık'                  , 'city' => 'İstanbul', 'dist' => 'Şişli'   , 'phone' => '+90 212 345 67 89', 'whatsapp' => '+905360125678', 'desc' => 'Dahiliye, kardiyoloji, check-up. Son teknoloji MR ve tomografi.', 'premium' => true, 'verified' => true],
            ['name' => 'Gülüş Estetik Diş Kliniği',      'cat' => 'Sağlık'                  , 'city' => 'Ankara'  , 'dist' => 'Çankaya' , 'phone' => '+90 312 567 89 01', 'whatsapp' => '+905361236789', 'desc' => 'Zirkonyum kaplama, gülüş tasarımı, implant ve ortodonti.', 'premium' => true],
            ['name' => 'Fizyocare Rehabilitasyon',       'cat' => 'Sağlık'                  , 'city' => 'Antalya' , 'dist' => null       , 'phone' => '+90 242 567 89 01', 'whatsapp' => '+905362347890', 'desc' => 'Manuel terapi, spor rehabilitasyonu, bel-boyun fıtığı tedavisi.'],
            // ── Otomotiv ──
            ['name' => 'Hızlı Oto Servis & Lastik',      'cat' => 'Otomotiv'                , 'city' => 'İstanbul', 'dist' => 'Ümraniye', 'phone' => '+90 216 678 90 12', 'whatsapp' => '+905363458901', 'desc' => 'Oto elektrik, mekanik bakım, lastik değişimi. 7/24 yol yardım.'],
            ['name' => 'Premium Oto Galeri',             'cat' => 'Otomotiv'                , 'city' => 'İstanbul', 'dist' => 'Maslak'  , 'phone' => '+90 212 789 01 23', 'desc' => 'Sıfır ve ikinci el lüks araçlar. Expertiz raporu ve garanti ile satış.', 'premium' => true, 'verified' => true],
            // ── İnşaat ──
            ['name' => 'Modern Yapı Mimarlık Ofisi',     'cat' => 'İnşaat ve Yapı Dekorasyon', 'city' => 'Ankara'  , 'dist' => 'Ümitköy' , 'phone' => '+90 312 890 12 34', 'whatsapp' => '+905364569012', 'desc' => 'Mimari proje, iç mimarlık, tadilat, anahtar teslim inşaat. 20 yıllık tecrübe.', 'premium' => true],
            // ── Eğitim ──
            ['name' => 'Bilge Akademi Yabancı Dil Kursu', 'cat' => 'Eğitim'                  , 'city' => 'İzmir'   , 'dist' => 'Bornova' , 'phone' => '+90 232 890 12 34', 'whatsapp' => '+905365670123', 'desc' => 'İngilizce, Almanca, Fransızca, İspanyolca. Online ve yüz yüze.', 'verified' => true],
            // ── Bilgisayar ──
            ['name' => 'CodeFast Yazılım ve Web Tasarım', 'cat' => 'Bilgisayar ve Bilişim'   , 'city' => 'İstanbul', 'dist' => 'Levent'  , 'phone' => '+90 212 901 23 45', 'whatsapp' => '+905366781234', 'desc' => 'Web sitesi, mobil uygulama, e-ticaret ve SEO danışmanlığı.'],
            // ── Giyim ──
            ['name' => 'Trend Moda Butik',               'cat' => 'Giyim'                    , 'city' => 'İstanbul', 'dist' => 'Nişantaşı', 'phone' => '+90 212 234 56 78', 'whatsapp' => '+905367892345', 'desc' => 'Kadın ve erkek günlük-abiye giyim. Yerli ve ithal markalar.'],
            // ── Hizmet ──
            ['name' => 'Temizlik Zamanı Profesyonel',    'cat' => 'Hizmet Sektörü'           , 'city' => 'Antalya' , 'dist' => null       , 'phone' => '+90 242 789 01 23', 'whatsapp' => '+905368903456', 'desc' => 'Ev, ofis, inşaat sonrası temizlik. Halı yıkama ve dezenfeksiyon.'],
            // ── Nakliye ──
            ['name' => 'Anadolu Lojistik Nakliyat',      'cat' => 'Nakliye ve Lojistik'      , 'city' => 'Ankara'  , 'dist' => 'Sincan'  , 'phone' => '+90 312 345 67 89', 'whatsapp' => '+905369014567', 'desc' => 'Şehir içi ve şehirler arası evden eve nakliyat. Asansörlü araç filosu.', 'premium' => true],
            // ── Spor ──
            ['name' => 'IronBody Fitness & Crossfit',    'cat' => 'Spor'                     , 'city' => 'İstanbul', 'dist' => 'Ataşehir', 'phone' => '+90 216 567 89 01', 'whatsapp' => '+905370125678', 'desc' => '1500m² alan, crossfit, pilates, yoga, spinning. Sauna ve buhar odası.', 'premium' => true, 'verified' => true],
            // ── Eğlence ──
            ['name' => 'Mavi Sahne Canlı Müzik Bar',     'cat' => 'Eğlence Mekanları'        , 'city' => 'İzmir'   , 'dist' => 'Alsancak', 'phone' => '+90 232 345 67 89', 'whatsapp' => '+905371236789', 'desc' => 'Canlı müzik, DJ performansları, kokteyl menüsü.'],
            // ── Elektrik ──
            ['name' => 'Voltaj Elektrik Tesisat',        'cat' => 'Elektrik'                 , 'city' => 'İstanbul', 'dist' => 'Maltepe' , 'phone' => '+90 216 789 01 23', 'whatsapp' => '+905372347890', 'desc' => 'Elektrik tesisat, tadilat, arıza onarım, akıllı ev sistemleri. 7/24 acil servis.', 'verified' => true],
        ];

        $created = 0;
        foreach ($companies as $c) {
            $sid = $catId($c['cat']);
            $cid = $cityId($c['city']);
            $did = $distId($c['dist']);

            Company::create([
                'name'             => $c['name'],
                'slug'             => Str::slug($c['name']),
                'category_id'      => $sid,
                'city_id'          => $cid,
                'district_id'      => $did,
                'phone'            => $c['phone'],
                'whatsapp'         => $c['whatsapp'] ?? null,
                'address'          => ($c['dist'] ?? $c['city']) . ', ' . $c['city'],
                'short_description'=> $c['desc'],
                'description'      => $c['desc'] . ' Profesyonel ekibimizle hizmetinizdeyiz. Detaylı bilgi için arayın.',
                'is_premium'       => $c['premium'] ?? false,
                'is_verified'      => $c['verified'] ?? false,
                'premium_until'    => ($c['premium'] ?? false) ? now()->addMonths(2) : null,
                'status'           => 'active',
                'view_count'       => rand(50, 2500),
                'opening_hours'    => implode("\n", [
                    'Pazartesi 09:00-22:00', 'Salı 09:00-22:00', 'Çarşamba 09:00-22:00',
                    'Perşembe 09:00-22:00', 'Cuma 09:00-23:00',
                    'Cumartesi 10:00-23:00', 'Pazar 10:00-20:00',
                ]),
            ]);
            $created++;
        }

        $this->command->info("✅ {$created} sample companies seeded.");
    }
}
