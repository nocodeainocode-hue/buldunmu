<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use League\Csv\Reader;

class ImportCompaniesFromCsv extends Command
{
    protected $signature = 'import:companies-csv 
                            {file : CSV dosyasının yolu} 
                            {--category= : Varsayılan kategori ID}
                            {--city= : Varsayılan şehir ID}
                            {--auto-approve : Firmaları otomatik onayla}
                            {--directory= : Directory ID}';

    protected $description = 'CSV dosyasından toplu firma içe aktarımı';

    public function handle(): int
    {
        $filePath = $this->argument('file');

        if (!file_exists($filePath)) {
            $this->error("Dosya bulunamadı: {$filePath}");
            return self::FAILURE;
        }

        $defaultCategoryId = $this->option('category');
        $defaultCityId = $this->option('city');
        $autoApprove = $this->option('auto-approve');
        $directoryId = $this->option('directory') ?? 1;

        $this->info("CSV dosyası okunuyor: {$filePath}");

        try {
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);

            $imported = 0;
            $skipped = 0;
            $total = iterator_count($csv);
            $csv->setHeaderOffset(0); // Reset after count

            $bar = $this->output->createProgressBar($total);
            $bar->start();

            foreach ($csv as $row) {
                $name = trim($row['name'] ?? '');

                if (empty($name)) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $slug = Str::slug($name);
                $baseSlug = $slug;
                $counter = 1;
                while (Company::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter++;
                }

                // Resolve category
                $categoryId = $defaultCategoryId;
                if (!empty($row['category'] ?? '')) {
                    $cat = Category::where('name', 'like', '%' . trim($row['category']) . '%')->first();
                    if ($cat) $categoryId = $cat->id;
                }

                // Resolve city
                $cityId = $defaultCityId;
                if (!empty($row['city'] ?? '')) {
                    $ct = City::where('name', 'like', '%' . trim($row['city']) . '%')->first();
                    if ($ct) $cityId = $ct->id;
                }

                if (!$categoryId) {
                    $this->warn("  Kategori bulunamadı: {$name} - varsayılan kategori atanmamış, atlanıyor.");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                if (!$cityId) {
                    $this->warn("  Şehir bulunamadı: {$name} - varsayılan şehir atanmamış, atlanıyor.");
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                Company::create([
                    'name' => $name,
                    'slug' => $slug,
                    'category_id' => $categoryId,
                    'city_id' => $cityId,
                    'phone' => trim($row['phone'] ?? '') ?: null,
                    'email' => trim($row['email'] ?? '') ?: null,
                    'website' => trim($row['website'] ?? '') ?: null,
                    'address' => trim($row['address'] ?? '') ?: null,
                    'description' => trim($row['description'] ?? '') ?: null,
                    'status' => $autoApprove ? 'active' : 'pending',
                    'directory_id' => $directoryId,
                ]);

                $imported++;
                $bar->advance();
            }

            $bar->finish();
            $this->newLine(2);

            $this->info("✅ İçe aktarma tamamlandı!");
            $this->table(
                ['Metrik', 'Değer'],
                [
                    ['Başarılı', $imported],
                    ['Atlandı', $skipped],
                    ['Toplam', $total],
                ]
            );

            return self::SUCCESS;

        } catch (\Exception $e) {
            $this->error("Hata: " . $e->getMessage());
            return self::FAILURE;
        }
    }
}
