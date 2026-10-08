<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $plans = [
            ['name' => 'Ücretsiz', 'slug' => 'ucretsiz', 'price' => 0, 'billing_period' => 'onetime', 'sort_order' => 1,
                'features' => [
                    ['title' => 'Firma sayısı: 1'], ['title' => 'Fotoğraf: 1'], ['title' => 'Kategori: 1'],
                    ['title' => 'Web sitesi: ✓'], ['title' => 'Sosyal medya: —', 'included' => false],
                    ['title' => 'Öne çıkarma: —', 'included' => false], ['title' => 'Harita önceliği: —', 'included' => false],
                    ['title' => 'Video: —', 'included' => false],
                ]],
            ['name' => 'Silver', 'slug' => 'silver', 'price' => 249, 'billing_period' => 'yearly', 'sort_order' => 2,
                'features' => [
                    ['title' => 'Firma sayısı: 1'], ['title' => 'Fotoğraf: 5'], ['title' => 'Kategori: 2'],
                    ['title' => 'Web sitesi: ✓'], ['title' => 'Sosyal medya: ✓'],
                    ['title' => 'Öne çıkarma: —', 'included' => false], ['title' => 'Harita önceliği: —', 'included' => false],
                    ['title' => 'Video: —', 'included' => false], ['title' => 'Geçerlilik: 365 gün'],
                ]],
            ['name' => 'Gold', 'slug' => 'gold', 'price' => 549, 'billing_period' => 'yearly', 'sort_order' => 3,
                'features' => [
                    ['title' => 'Firma sayısı: 2'], ['title' => 'Fotoğraf: 10'], ['title' => 'Kategori: 3'],
                    ['title' => 'Web sitesi: ✓'], ['title' => 'Sosyal medya: ✓'],
                    ['title' => 'Öne çıkarma: ✓'], ['title' => 'Harita önceliği: ✓'],
                    ['title' => 'Video: ✓'], ['title' => 'Geçerlilik: 365 gün'],
                ]],
        ];

        foreach ($plans as $plan) {
            $features = $plan['features'];
            unset($plan['features']);
            DB::table('membership_plans')->updateOrInsert(
                ['directory_id' => null, 'slug' => $plan['slug']],
                array_merge($plan, [
                    'directory_id' => null,
                    'currency' => 'TRY',
                    'features' => json_encode($features, JSON_UNESCAPED_UNICODE),
                    'is_active' => true,
                    'updated_at' => now(),
                    'created_at' => now(),
                ])
            );
        }
    }

    public function down(): void
    {
        // Paket kayıtları önceki yönetici düzenlemelerini içerebilir; geri alma onları silmez.
    }
};
