<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use App\Models\CompanyImportBatch;
use App\Models\Directory;
use App\Services\CompanyImportService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

/** Toplu yüklemede enlem/boylam sütunları ve Google Haritalar linki firma profilinde haritayı çizer. */
class CompanyImportCoordinatesTest extends TestCase
{
    use RefreshDatabase;

    public function test_latitude_longitude_columns_and_maps_links_put_a_map_on_the_profile(): void
    {
        Storage::fake('local');
        $directory = Directory::create(['name' => 'Harita Rehberi', 'slug' => 'harita', 'domain' => 'harita.test', 'status' => 'active']);
        Category::create(['name' => 'Kuaför', 'slug' => 'kuafor', 'status' => 'active']);
        City::create(['name' => 'İstanbul', 'slug' => 'istanbul']);

        Storage::disk('local')->makeDirectory('company-imports');
        $path = Storage::disk('local')->path('company-imports/koordinat.xlsx');
        $spreadsheet = new Spreadsheet;
        $spreadsheet->getActiveSheet()->fromArray([
            ['Firma Adı', 'Kategori', 'Şehir', 'Telefon', 'Google Maps', 'Enlem', 'Boylam'],
            // Kısa paylaşım linkinde konum yok: elle girilen enlem/boylam (virgüllü ondalık) kullanılır.
            ['Kısa Linkli Kuaför', 'Kuaför', 'İstanbul', '0532 412 55 66', 'https://maps.app.goo.gl/abc123', '40,9903', '29,0291'],
            // Linkte konum var: enlem/boylam sütunu boş olsa da koordinat linkten çıkarılır.
            ['Uzun Linkli Kuaför', 'Kuaför', 'İstanbul', '0532 412 55 67', 'https://www.google.com/maps/place/X/@41.0,29.0,17z/data=!3d41.086243!4d29.003287', '', ''],
            // Hatalı değer yok sayılır.
            ['Hatalı Koordinat', 'Kuaför', 'İstanbul', '0532 412 55 68', '', 'abc', '999'],
        ]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        $batch = CompanyImportBatch::create([
            'filename' => 'koordinat.xlsx', 'stored_path' => 'company-imports/koordinat.xlsx', 'status' => 'pending',
            'duplicate_strategy' => 'skip', 'default_status' => 'active',
            'options' => ['directory_ids' => [$directory->id], 'auto_create_taxonomies' => false],
        ]);
        app(CompanyImportService::class)->process($batch);

        $short = Company::withoutGlobalScopes()->where('name', 'Kısa Linkli Kuaför')->firstOrFail();
        $this->assertEqualsWithDelta(40.9903, (float) $short->latitude, 0.00001);
        $this->assertEqualsWithDelta(29.0291, (float) $short->longitude, 0.00001);
        $this->assertSame('https://www.google.com/maps?q=40.9903,29.0291&z=16&output=embed', $short->googleMapsEmbedSrc());

        $long = Company::withoutGlobalScopes()->where('name', 'Uzun Linkli Kuaför')->firstOrFail();
        $this->assertEqualsWithDelta(41.086243, (float) $long->latitude, 0.00001);
        $this->assertEqualsWithDelta(29.003287, (float) $long->longitude, 0.00001);

        $bad = Company::withoutGlobalScopes()->where('name', 'Hatalı Koordinat')->firstOrFail();
        $this->assertNull($bad->latitude);
        $this->assertNull($bad->longitude);

        $this->withServerVariables(['HTTP_HOST' => 'harita.test'])->get('http://harita.test/firma/'.$short->slug)
            ->assertOk()
            ->assertSee('output=embed', false);
    }

    public function test_template_has_latitude_and_longitude_columns(): void
    {
        $this->assertContains('latitude', CompanyImportService::COLUMNS);
        $this->assertContains('longitude', CompanyImportService::COLUMNS);
        $this->assertSame(array_search('google_maps', CompanyImportService::COLUMNS) + 1, array_search('latitude', CompanyImportService::COLUMNS));
    }
}
