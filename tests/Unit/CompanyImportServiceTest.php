<?php

namespace Tests\Unit;

use App\Services\CompanyImportService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PHPUnit\Framework\TestCase;

class CompanyImportServiceTest extends TestCase
{
    public function test_it_reads_xlsx_and_normalizes_turkish_headers(): void
    {
        $path = sys_get_temp_dir() . DIRECTORY_SEPARATOR . 'company-import-' . uniqid() . '.xlsx';
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray([
            ['Firma Adı', 'Rehber', 'Kategori', 'Şehir', 'Telefon'],
            ['Örnek Diş', 'hizmetyakinda.com.tr', 'Diş Kliniği', 'İstanbul', '0212 555 00 00'],
        ]);
        (new Xlsx($spreadsheet))->save($path);
        $spreadsheet->disconnectWorksheets();

        try {
            $rows = (new CompanyImportService())->rows($path);
            $this->assertCount(1, $rows);
            $this->assertSame('Örnek Diş', $rows[0]['name']);
            $this->assertSame('hizmetyakinda.com.tr', $rows[0]['directory']);
            $this->assertSame('Diş Kliniği', $rows[0]['category']);
            $this->assertSame('İstanbul', $rows[0]['city']);
            $this->assertSame('0212 555 00 00', $rows[0]['phone']);
        } finally {
            @unlink($path);
        }
    }
}
