<?php

namespace App\Console\Commands;

use App\Models\AdCampaign;
use App\Models\City;
use Illuminate\Console\Command;

class SeedHouseAds extends Command
{
    protected $signature = 'ads:seed-house';

    protected $description = 'Ücretli reklamveren gelene kadar gösterilecek kendi reklamlarımızı (omnipuremarketing.com genel, suaritma59.com Tekirdağ) oluşturur. Tekrar çalıştırmak güvenlidir.';

    public function handle(): int
    {
        // Genel: hedefi olmayan, her rehberde ve her sayfada son çare olarak görünür.
        AdCampaign::firstOrCreate(
            ['name' => 'OmniPure Marketing (genel)'],
            [
                'type' => 'house',
                'status' => 'active',
                'placements' => ['top', 'bottom'],
                'headline' => 'Firmanız için dijital pazarlama',
                'body' => 'Daha fazla müşteriye ulaşmak için OmniPure Marketing ile görüşün.',
                'cta_label' => 'omnipuremarketing.com',
                'link_url' => 'https://omnipuremarketing.com',
                'bg_color' => '#14213d',
                'text_color' => '#ffffff',
                'accent_color' => '#ffc233',
                'weight' => 1,
            ],
        );

        // Bölgesel: Tekirdağ sayfalarında ve Tekirdağ odaklı rehberlerde genel reklamın önüne geçer.
        $tekirdag = City::withoutGlobalScope('directory')->whereNull('directory_id')->where('slug', 'tekirdag')->first();

        if (! $tekirdag) {
            $this->warn('Ortak katalogda "Tekirdağ" şehri bulunamadı; suaritma59.com reklamı oluşturulmadı.');
        } else {
            AdCampaign::firstOrCreate(
                ['name' => 'suaritma59.com (Tekirdağ)'],
                [
                    'type' => 'house',
                    'status' => 'active',
                    'placements' => ['top', 'bottom'],
                    'headline' => "Tekirdağ'da su arıtma çözümleri",
                    'body' => 'Ev ve işyeriniz için su arıtma ürünleri ve servis bilgisi.',
                    'cta_label' => 'suaritma59.com',
                    'link_url' => 'https://suaritma59.com',
                    'bg_color' => '#0b4a6f',
                    'text_color' => '#ffffff',
                    'accent_color' => '#7dd3fc',
                    'city_ids' => [$tekirdag->id],
                    'weight' => 1,
                ],
            );
        }

        $this->info('Kendi reklamlarımız hazır. Metin ve görselleri Yönetim paneli → Gelir → Reklamlar bölümünden düzenleyebilirsiniz.');

        return self::SUCCESS;
    }
}
