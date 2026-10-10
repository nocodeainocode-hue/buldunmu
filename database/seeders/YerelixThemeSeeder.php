<?php

namespace Database\Seeders;

use App\Models\Directory;
use App\Models\SiteSetting;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class YerelixThemeSeeder extends Seeder
{
    public function run(): void
    {
        $directory = Directory::where('domain', 'yerelix.com.tr')->first();

        if (! $directory) {
            $this->command?->warn('Yerelix rehberi bulunamadı; tema uygulanmadı.');

            return;
        }

        if ($directory->template === 'local-ledger') {
            $this->command?->info('Yerelix teması zaten güncel; mevcut düzenlemeler korundu.');

            return;
        }

        DB::transaction(function () use ($directory): void {
            // The previous theme's layout and palette overrides must not leak into this design.
            $directory->update(['template' => 'local-ledger', 'theme' => []]);
            $settings = SiteSetting::withoutGlobalScope('directory')->firstOrCreate(
                ['directory_id' => $directory->id], ['site_name' => $directory->name],
            );
            $settings->update([
                'homepage_title' => 'İyi bir iş, doğru firmayla başlar.',
                'homepage_subtitle' => 'İhtiyacınız olan hizmeti bulun, şehrinizdeki işletmeleri inceleyin ve doğrudan iletişime geçin.',
            ]);
        });

        $this->command?->info('Yerelix için Yerel Rehber teması uygulandı.');
    }
}
