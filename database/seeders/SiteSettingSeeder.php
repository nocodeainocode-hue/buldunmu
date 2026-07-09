<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SiteSettingSeeder extends Seeder
{
    public function run(): void
    {
        \App\Models\SiteSetting::create([
            'site_name' => 'Firma Rehberi',
            'phone' => '+90 212 555 55 55',
            'whatsapp' => '+905555555555',
            'email' => 'info@firmarehberi.local',
            'address' => 'İstanbul, Türkiye',
            'homepage_title' => 'Aradığınız Firmayı Hemen Bulun',
            'homepage_subtitle' => 'Türkiye\'nin en kapsamlı firma rehberinde binlerce işletme arasından arama yapın.',
            'meta_title' => 'Firma Rehberi - Türkiye\'nin En Kapsamlı Firma Rehberi',
            'meta_description' => 'Firma Rehberi ile Türkiye genelinde binlerce firmayı keşfedin. Restoran, otel, kuaför ve daha fazlası.',
        ]);
    }
}