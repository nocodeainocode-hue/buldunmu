<?php

namespace App\Console\Commands;

use App\Models\Directory;
use Illuminate\Console\Command;
use Illuminate\Support\Str;

class SeedDirectories extends Command
{
    protected $signature = 'seed:directories';
    protected $description = 'Create default directories if they do not exist';

    public function handle(): int
    {
        $directories = [
            'sektorbazaar.com.tr' => 'Sektör Bazaar',
            'sektorbul.com.tr' => 'Sektör Bul',
            'firmahatti.com.tr' => 'Firma Hattı',
            'guvenilirfirmalar.com.tr' => 'Güvenilir Firmalar',
            'kobiharita.com.tr' => 'Kobi Harita',
            'isletmelistesi.com.tr' => 'İşletme Listesi',
            'yerelrehber360.com.tr' => 'Yerel Rehber 360',
            'izmirisletmeleri.com.tr' => 'İzmir İşletmeleri',
            'istanbulfirmarehberi.com.tr' => 'İstanbul Firma Rehberi',
            'ankarakobi.com.tr' => 'Ankara Kobi',
            'esnafharita.com.tr' => 'Esnaf Harita',
            'yakindafirma.com.tr' => 'Yakında Firma',
            'hizmetyakinda.com.tr' => 'Hizmet Yakında',
            'isletmepusulasi.com.tr' => 'İşletme Pusulası',
        ];

        foreach ($directories as $domain => $name) {
            if (Directory::where('domain', $domain)->exists()) {
                $this->line("⏭️ {$domain} — zaten var");
                continue;
            }

            Directory::create([
                'name' => $name,
                'slug' => Str::slug($name),
                'domain' => $domain,
                'template' => 'default',
                'status' => 'active',
            ]);

            $this->info("✅ {$name} ({$domain})");
        }

        $this->newLine();
        $this->info('Bitti. php artisan optimize:clear yapmayı unutma.');

        return self::SUCCESS;
    }
}
