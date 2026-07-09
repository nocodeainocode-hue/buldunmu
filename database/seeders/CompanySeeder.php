<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CompanySeeder extends Seeder
{
    public function run(): void
    {
        $companies = [
            ['name' => 'Lezzet Durağı Restoran', 'category_id' => 1, 'city_id' => 1, 'district_id' => 1, 'phone' => '+90 216 333 44 55', 'whatsapp' => '+905333333333', 'email' => 'info@lezzetduragi.com', 'website' => 'https://lezzetduragi.com', 'short_description' => 'Kadıköy\'ün en iyi ev yemekleri restoranı.', 'is_premium' => true],
            ['name' => 'Deniz Manzaralı Otel', 'category_id' => 2, 'city_id' => 5, 'district_id' => null, 'phone' => '+90 242 111 22 33', 'whatsapp' => '+905421111111', 'email' => 'info@denizmanzara.com', 'website' => 'https://denizmanzara.com', 'short_description' => 'Antalya\'da deniz manzaralı lüks otel.', 'is_premium' => true],
            ['name' => 'Style Kuaför', 'category_id' => 3, 'city_id' => 1, 'district_id' => 3, 'phone' => '+90 212 222 33 44', 'whatsapp' => '+905332222222', 'email' => 'info@stylekuafor.com', 'short_description' => 'Modern kuaför ve güzellik salonu.', 'is_premium' => false],
            ['name' => 'Usta Oto Tamir', 'category_id' => 4, 'city_id' => 1, 'district_id' => 6, 'phone' => '+90 212 444 55 66', 'whatsapp' => '+905334443333', 'short_description' => 'Profesyonel oto tamir ve bakım hizmeti.', 'is_premium' => false],
            ['name' => 'DentPlus Ağız ve Diş Sağlığı', 'category_id' => 5, 'city_id' => 2, 'district_id' => 7, 'phone' => '+90 312 555 66 77', 'email' => 'info@dentplus.com', 'website' => 'https://dentplus.com', 'short_description' => 'Modern diş kliniği, implant ve estetik diş hekimliği.', 'is_premium' => true],
            ['name' => 'Hukuk Bürosu Av. Yılmaz', 'category_id' => 6, 'city_id' => 2, 'district_id' => 8, 'phone' => '+90 312 777 88 99', 'email' => 'info@avukatyilmaz.com', 'short_description' => 'Ceza ve aile hukuku alanında uzman avukat.', 'is_premium' => false],
            ['name' => 'İzmir Emlak', 'category_id' => 7, 'city_id' => 3, 'district_id' => 11, 'phone' => '+90 232 666 77 88', 'whatsapp' => '+905356666666', 'email' => 'info@izmiremlak.com', 'short_description' => 'İzmir\'de satılık ve kiralık daireler.', 'is_premium' => false],
            ['name' => 'Şok Market', 'category_id' => 8, 'city_id' => 1, 'district_id' => 4, 'phone' => '+90 216 888 99 00', 'short_description' => 'Mahallenizin güvenilir marketi.', 'is_premium' => false],
            ['name' => 'Sağlık Eczanesi', 'category_id' => 9, 'city_id' => 1, 'district_id' => 2, 'phone' => '+90 212 999 00 11', 'short_description' => '7/24 açık nöbetçi eczane.', 'is_premium' => true],
            ['name' => 'FitLife Spor Salonu', 'category_id' => 10, 'city_id' => 4, 'district_id' => 15, 'phone' => '+90 224 111 22 33', 'whatsapp' => '+905371111111', 'email' => 'info@fitlife.com', 'website' => 'https://fitlife.com', 'short_description' => 'Modern ekipmanlar ve profesyonel eğitmenler.', 'is_premium' => false],
            ['name' => 'Moda Tekstil', 'category_id' => 11, 'city_id' => 1, 'district_id' => 5, 'phone' => '+90 212 333 44 55', 'whatsapp' => '+905383333333', 'short_description' => 'Toptan ve perakende tekstil ürünleri.', 'is_premium' => false],
            ['name' => 'TechStore Elektronik', 'category_id' => 12, 'city_id' => 2, 'district_id' => 9, 'phone' => '+90 312 444 55 66', 'email' => 'info@techstore.com', 'website' => 'https://techstore.com', 'short_description' => 'Bilgisayar, telefon ve elektronik ürünler.', 'is_premium' => true],
        ];

        foreach ($companies as $i => $c) {
            \App\Models\Company::create($c + [
                'slug' => Str::slug($c['name']),
                'status' => 'active',
                'is_premium' => $c['is_premium'] ?? false,
                'premium_until' => ($c['is_premium'] ?? false) ? now()->addMonths(3) : null,
            ]);
        }
    }
}