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
            ['name' => 'Enerji ve Yakıt', 'icon' => '⛽', 'description' => 'Enerji, akaryakıt ve yakıt hizmetleri'],
            ['name' => 'Ev Tekstili', 'icon' => '🛏️', 'description' => 'Ev tekstili ve dekorasyon ürünleri'],
            ['name' => 'Gıda', 'icon' => '🍽️', 'description' => 'Gıda üretim, satış ve hizmetleri'],
            ['name' => 'Giyim', 'icon' => '👕', 'description' => 'Giyim, moda ve tekstil mağazaları'],
            ['name' => 'Hizmet Sektörü', 'icon' => '🔧', 'description' => 'Yerel hizmet sağlayıcıları'],
            ['name' => 'İklimlendirme', 'icon' => '❄️', 'description' => 'Isıtma, soğutma ve iklimlendirme'],
            ['name' => 'İnşaat ve Yapı Dekorasyon', 'icon' => '🏗️', 'description' => 'İnşaat, yapı ve dekorasyon firmaları'],
            ['name' => 'Kimya ve Endüstriyel', 'icon' => '🧪', 'description' => 'Kimya ve endüstriyel ürünler'],
            ['name' => 'Kültür ve Sanat', 'icon' => '🎨', 'description' => 'Kültür, sanat ve yaratıcı işletmeler'],
            ['name' => 'Madencilik', 'icon' => '⛏️', 'description' => 'Madencilik ve doğal kaynaklar'],
            ['name' => 'Makine', 'icon' => '⚙️', 'description' => 'Makine üretim ve servisleri'],
            ['name' => 'Medya Basın ve Yayıncılık', 'icon' => '📺', 'description' => 'Medya, basın ve yayıncılık'],
            ['name' => 'Mobilya', 'icon' => '🪑', 'description' => 'Mobilya ve ev yaşam ürünleri'],
            ['name' => 'Nakliye ve Lojistik', 'icon' => '🚛', 'description' => 'Nakliye, taşımacılık ve lojistik'],
            ['name' => 'Otomotiv', 'icon' => '🚗', 'description' => 'Otomotiv satış, servis ve bakım'],
            ['name' => 'Para ve Finans', 'icon' => '💰', 'description' => 'Finans, sigorta ve danışmanlık'],
            ['name' => 'Plastik Sanayi', 'icon' => '🏭', 'description' => 'Plastik üretim ve sanayi'],
            ['name' => 'Resmi Kurumlar', 'icon' => '🏛️', 'description' => 'Kamu kurumları ve resmi hizmetler'],
            ['name' => 'Restaurant ve Lokantalar', 'icon' => '🍴', 'description' => 'Restoran, lokanta ve yemek mekanları'],
            ['name' => 'Sağlık', 'icon' => '🏥', 'description' => 'Sağlık, klinik ve bakım hizmetleri'],
            ['name' => 'Sivil Toplum Kuruluşları', 'icon' => '🤝', 'description' => 'Dernek, vakıf ve sivil toplum kuruluşları'],
            ['name' => 'Spor', 'icon' => '⚽', 'description' => 'Spor, fitness ve aktivite merkezleri'],
            ['name' => 'Takı ve Aksesuarları', 'icon' => '💍', 'description' => 'Takı, mücevher ve aksesuarlar'],
            ['name' => 'Tarım ve Hayvancılık', 'icon' => '🌾', 'description' => 'Tarım, hayvancılık ve çiftçilik'],
            ['name' => 'Tekstil ve Konfeksiyon', 'icon' => '🧵', 'description' => 'Tekstil, konfeksiyon ve üretim'],
            ['name' => 'Telekomünikasyon', 'icon' => '📡', 'description' => 'Telekomünikasyon ve iletişim hizmetleri'],
            ['name' => 'Turizm ve Seyahat', 'icon' => '✈️', 'description' => 'Turizm, seyahat ve konaklama'],
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
