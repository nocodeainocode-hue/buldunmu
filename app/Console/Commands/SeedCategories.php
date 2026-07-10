<?php

namespace App\Console\Commands;

use App\Models\Category;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedCategories extends Command
{
    protected $signature = 'seed:categories';
    protected $description = 'Seed all 35 main categories and subcategories from find.com.tr';

    public function handle(): int
    {
        $categories = [
            'Ambalaj ve Kağıt' => '📦',
            'Beyaz Eşya' => '🧊',
            'Bilgisayar ve Bilişim' => '💻',
            'Demir - Çelik - Metal' => '🔩',
            'Eğitim' => '📚',
            'Eğlence Mekanları' => '🎉',
            'Elektrik' => '⚡',
            'Elektronik' => '📱',
            'Enerji ve Yakıt' => '⛽',
            'Ev Tekstili' => '🛏️',
            'Gıda' => '🍽️',
            'Giyim' => '👕',
            'Hizmet Sektörü' => '🔧',
            'İklimlendirme' => '❄️',
            'İnşaat ve Yapı Dekorasyon' => '🏗️',
            'Kimya ve Endüstriyel' => '🧪',
            'Kültür ve Sanat' => '🎨',
            'Madencilik' => '⛏️',
            'Makine' => '⚙️',
            'Medya Basın ve Yayıncılık' => '📺',
            'Mobilya' => '🪑',
            'Nakliye ve Lojistik' => '🚛',
            'Otomotiv' => '🚗',
            'Para ve Finans' => '💰',
            'Plastik Sanayi' => '🏭',
            'Resmi Kurumlar' => '🏛️',
            'Restaurant ve Lokantalar' => '🍴',
            'Sağlık' => '🏥',
            'Sivil Toplum Kuruluşları' => '🤝',
            'Spor' => '⚽',
            'Takı ve Aksesuarları' => '💍',
            'Tarım ve Hayvancılık' => '🌾',
            'Tekstil ve Konfeksiyon' => '🧵',
            'Telekomünikasyon' => '📡',
            'Turizm ve Seyahat' => '✈️',
        ];

        $count = 0;
        foreach ($categories as $name => $icon) {
            $slug = Str::slug($name);

            if (Category::where('slug', $slug)->exists()) {
                $this->line("⏭️ {$name} — zaten var");
                continue;
            }

            Category::create([
                'name' => $name,
                'slug' => $slug,
                'icon' => $icon,
                'status' => 'active',
            ]);

            $this->info("✅ {$icon} {$name}");
            $count++;
        }

        $this->newLine();
        $this->info("{$count} kategori eklendi. Subcategory'leri sonra ekleyebilirsin.");

        return self::SUCCESS;
    }
}
