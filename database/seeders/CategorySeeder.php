<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Keep the shared catalog in one place for fresh installs and repeatable deployments.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Ambalaj ve Kağıt', 'icon' => '📦', 'description' => 'Ambalaj, kağıt ve paketleme firmaları'],
            ['name' => 'Beyaz Eşya', 'icon' => '🧊', 'description' => 'Beyaz eşya satış ve servisleri'],
            ['name' => 'Bilgisayar ve Bilişim', 'icon' => '💻', 'description' => 'Bilgisayar, yazılım ve bilişim hizmetleri'],
            ['name' => 'Demir - Çelik - Metal', 'icon' => '🔩', 'description' => 'Demir, çelik ve metal işletmeleri'],
            ['name' => 'Eğitim', 'icon' => '📚', 'description' => 'Eğitim kurumları ve kurslar'],
            ['name' => 'Eğlence Mekanları', 'icon' => '🎉', 'description' => 'Eğlence, etkinlik ve gece mekanları'],
            ['name' => 'Elektrik', 'icon' => '⚡', 'description' => 'Elektrik hizmetleri ve malzemeleri'],
            ['name' => 'Elektronik', 'icon' => '📱', 'description' => 'Elektronik ürün ve servisler'],
            ['name' => 'Emlak ve Gayrimenkul', 'icon' => '🏠', 'description' => 'Emlak ofisleri, gayrimenkul danışmanları ve yönetim firmaları'],
            ['name' => 'Enerji ve Yakıt', 'icon' => '⛽', 'description' => 'Enerji, akaryakıt ve yakıt hizmetleri'],
            ['name' => 'Ev Tekstili', 'icon' => '🛏️', 'description' => 'Ev tekstili ve dekorasyon ürünleri'],
            ['name' => 'Gıda', 'icon' => '🍽️', 'description' => 'Gıda üretim, satış ve hizmetleri'],
            ['name' => 'Giyim', 'icon' => '👕', 'description' => 'Giyim, moda ve tekstil mağazaları'],
            ['name' => 'Güvenlik Hizmetleri', 'icon' => '🛡️', 'description' => 'Özel güvenlik, alarm ve güvenlik sistemleri firmaları'],
            ['name' => 'Güzellik ve Kişisel Bakım', 'icon' => '✨', 'description' => 'Kuaför, güzellik salonu ve kişisel bakım işletmeleri'],
            ['name' => 'Hizmet Sektörü', 'icon' => '🔧', 'description' => 'Yerel hizmet sağlayıcıları'],
            ['name' => 'Hukuk ve Danışmanlık', 'icon' => '⚖️', 'description' => 'Avukatlık, hukuk ve profesyonel danışmanlık hizmetleri'],
            ['name' => 'İklimlendirme', 'icon' => '❄️', 'description' => 'Isıtma, soğutma ve iklimlendirme'],
            ['name' => 'İnşaat ve Yapı Dekorasyon', 'icon' => '🏗️', 'description' => 'İnşaat, yapı ve dekorasyon firmaları'],
            ['name' => 'Kimya ve Endüstriyel', 'icon' => '🧪', 'description' => 'Kimya ve endüstriyel ürünler'],
            ['name' => 'Kültür ve Sanat', 'icon' => '🎨', 'description' => 'Kültür, sanat ve yaratıcı işletmeler'],
            ['name' => 'Madencilik', 'icon' => '⛏️', 'description' => 'Madencilik ve doğal kaynaklar'],
            ['name' => 'Makine', 'icon' => '⚙️', 'description' => 'Makine üretim ve servisleri'],
            ['name' => 'Market ve Perakende', 'icon' => '🛒', 'description' => 'Marketler, mağazalar ve perakende satış işletmeleri'],
            ['name' => 'Medya Basın ve Yayıncılık', 'icon' => '📺', 'description' => 'Medya, basın ve yayıncılık'],
            ['name' => 'Mobilya', 'icon' => '🪑', 'description' => 'Mobilya ve ev yaşam ürünleri'],
            ['name' => 'Nakliye ve Lojistik', 'icon' => '🚛', 'description' => 'Nakliye, taşımacılık ve lojistik'],
            ['name' => 'Otomotiv', 'icon' => '🚗', 'description' => 'Otomotiv satış, servis ve bakım'],
            ['name' => 'Para ve Finans', 'icon' => '💰', 'description' => 'Finans, sigorta ve danışmanlık'],
            ['name' => 'Plastik Sanayi', 'icon' => '🏭', 'description' => 'Plastik üretim ve sanayi'],
            ['name' => 'Resmi Kurumlar', 'icon' => '🏛️', 'description' => 'Kamu kurumları ve resmi hizmetler'],
            ['name' => 'Restaurant ve Lokantalar', 'icon' => '🍴', 'description' => 'Restoran, lokanta ve yemek mekanları'],
            ['name' => 'Reklam ve Organizasyon', 'icon' => '📣', 'description' => 'Reklam, pazarlama, etkinlik ve organizasyon firmaları'],
            ['name' => 'Sağlık', 'icon' => '🏥', 'description' => 'Sağlık, klinik ve bakım hizmetleri'],
            ['name' => 'Sivil Toplum Kuruluşları', 'icon' => '🤝', 'description' => 'Dernek, vakıf ve sivil toplum kuruluşları'],
            ['name' => 'Spor', 'icon' => '⚽', 'description' => 'Spor, fitness ve aktivite merkezleri'],
            ['name' => 'Takı ve Aksesuarları', 'icon' => '💍', 'description' => 'Takı, mücevher ve aksesuarlar'],
            ['name' => 'Temizlik Hizmetleri', 'icon' => '🧹', 'description' => 'Ev, iş yeri ve endüstriyel temizlik firmaları'],
            ['name' => 'Tarım ve Hayvancılık', 'icon' => '🌾', 'description' => 'Tarım, hayvancılık ve çiftçilik'],
            ['name' => 'Tekstil ve Konfeksiyon', 'icon' => '🧵', 'description' => 'Tekstil, konfeksiyon ve üretim'],
            ['name' => 'Telekomünikasyon', 'icon' => '📡', 'description' => 'Telekomünikasyon ve iletişim hizmetleri'],
            ['name' => 'Turizm ve Seyahat', 'icon' => '✈️', 'description' => 'Turizm, seyahat ve konaklama'],
            ['name' => 'Veteriner ve Evcil Hayvan', 'icon' => '🐾', 'description' => 'Veteriner klinikleri ve evcil hayvan hizmetleri'],
        ];

        foreach ($categories as $category) {
            $slug = Str::slug($category['name']);

            Category::updateOrCreate(
                ['slug' => $slug],
                $category + ['slug' => $slug, 'status' => 'active'],
            );
        }
    }
}
