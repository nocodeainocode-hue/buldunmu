<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\CompanyImportBatch;
use App\Models\Directory;
use App\Models\District;
use App\Services\CompanyImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class SharedTaxonomyImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_import_reuses_shared_taxonomies_for_every_directory(): void
    {
        Storage::fake('local');
        $directory = Directory::create([
            'name' => 'Hedef Rehber', 'slug' => 'hedef-rehber', 'domain' => 'hedef.test', 'status' => 'active',
        ]);
        $category = Category::create(['name' => 'Diş Kliniği', 'slug' => 'dis-klinigi', 'status' => 'active']);
        $city = City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);
        $district = District::create(['city_id' => $city->id, 'name' => 'Kadıköy', 'slug' => 'kadikoy']);

        Storage::disk('local')->makeDirectory('company-imports');
        $path = Storage::disk('local')->path('company-imports/shared.xlsx');
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray([
            ['Firma Adı', 'Kategori', 'Şehir', 'İlçe'],
            ['Örnek Klinik', 'Dis Klinigi', 'Istanbul', 'Kadikoy'],
        ]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $batch = CompanyImportBatch::create([
            'filename' => 'shared.xlsx',
            'stored_path' => 'company-imports/shared.xlsx',
            'status' => 'pending',
            'duplicate_strategy' => 'skip',
            'default_status' => 'active',
            'options' => ['directory_ids' => [$directory->id], 'auto_create_taxonomies' => false],
        ]);

        app(CompanyImportService::class)->process($batch);

        $this->assertDatabaseHas('companies', [
            'name' => 'Örnek Klinik',
            'directory_id' => $directory->id,
            'category_id' => $category->id,
            'city_id' => $city->id,
            'district_id' => $district->id,
        ]);
        $this->assertDatabaseMissing('categories', ['directory_id' => $directory->id]);
        $this->assertDatabaseMissing('cities', ['directory_id' => $directory->id]);
        $this->assertDatabaseMissing('districts', ['directory_id' => $directory->id]);
    }
}
