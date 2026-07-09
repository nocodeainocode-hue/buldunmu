<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'Restoran', 'slug' => 'restoran', 'icon' => '🍽️', 'description' => 'Restoran ve yemek mekanları'],
            ['name' => 'Otel', 'slug' => 'otel', 'icon' => '🏨', 'description' => 'Otel ve konaklama tesisleri'],
            ['name' => 'Kuaför & Güzellik', 'slug' => 'kuafor-guzellik', 'icon' => '💇', 'description' => 'Kuaför ve güzellik salonları'],
            ['name' => 'Oto Tamir', 'slug' => 'oto-tamir', 'icon' => '🔧', 'description' => 'Oto tamir ve bakım servisleri'],
            ['name' => 'Diş Hekimi', 'slug' => 'dis-hekimi', 'icon' => '🦷', 'description' => 'Diş hekimi ve ağız sağlığı klinikleri'],
            ['name' => 'Avukat', 'slug' => 'avukat', 'icon' => '⚖️', 'description' => 'Hukuk büroları ve avukatlar'],
            ['name' => 'Emlakçı', 'slug' => 'emlakci', 'icon' => '🏠', 'description' => 'Emlak danışmanlık ofisleri'],
            ['name' => 'Market', 'slug' => 'market', 'icon' => '🛒', 'description' => 'Market ve süpermarketler'],
            ['name' => 'Eczane', 'slug' => 'eczane', 'icon' => '💊', 'description' => 'Eczaneler'],
            ['name' => 'Spor Salonu', 'slug' => 'spor-salonu', 'icon' => '🏋️', 'description' => 'Spor ve fitness merkezleri'],
            ['name' => 'Giyim Mağazası', 'slug' => 'giyim-magazasi', 'icon' => '👕', 'description' => 'Giyim ve tekstil mağazaları'],
            ['name' => 'Elektronik', 'slug' => 'elektronik', 'icon' => '📱', 'description' => 'Elektronik ve teknoloji mağazaları'],
        ];

        foreach ($categories as $cat) {
            \App\Models\Category::create($cat + ['status' => 'active']);
        }
    }
}
