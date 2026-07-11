<?php

namespace App\Filament\Resources\DiscoveredCompanies\Pages;

use App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource;
use App\Jobs\ProcessCompanyImport;
use App\Jobs\RollbackCompanyImport;
use App\Models\CompanyImportBatch;
use App\Models\Directory;
use App\Services\CompanyImportService;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ImportCompanies extends Page
{
    use InteractsWithForms;

    protected static string $resource = DiscoveredCompanyResource::class;
    protected string $view = 'filament.resources.discovered-companies.import';
    protected static ?string $title = 'Toplu Firma Merkezi';
    protected static ?string $navigationLabel = 'Excel ile Toplu Yükle';

    public ?array $data = [];
    public array $previewRows = [];

    public function mount(): void
    {
        $currentDirectoryId = app()->bound('currentDirectory') ? app('currentDirectory')->id : null;
        $this->form->fill([
            'directoryIds' => $currentDirectoryId ? [$currentDirectoryId] : [],
            'duplicateStrategy' => 'skip',
            'defaultStatus' => 'pending',
            'autoCreateTaxonomies' => true,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            \Filament\Schemas\Components\Section::make('Excel veya CSV yükle')
                ->description('Dosyadaki directory sütunu boşsa seçilen rehberlere; doluysa eşleşen domain veya slug değerlerine aktarılır.')
                ->schema([
                    FileUpload::make('importFile')
                        ->label('Firma dosyası')
                        ->acceptedFileTypes([
                            'text/csv', 'text/plain', 'application/csv',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                            'application/vnd.ms-excel',
                        ])
                        ->maxSize(25600)
                        ->disk('local')
                        ->directory('company-imports')
                        ->required()
                        ->helperText('CSV, XLSX veya XLS. En fazla 25 MB.'),
                    Select::make('directoryIds')
                        ->label('Varsayılan hedef rehberler')
                        ->options(fn() => Directory::orderBy('name')->get()->mapWithKeys(fn($directory) => [
                            $directory->id => $directory->name . ' (' . $directory->domain . ')',
                        ])->all())
                        ->multiple()
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('duplicateStrategy')
                        ->label('Tekrar bulunan firma')
                        ->options([
                            'skip' => 'Atla (önerilen)',
                            'update' => 'Mevcut kaydı güncelle',
                            'create' => 'Yeni kayıt oluştur',
                        ])->required(),
                    Select::make('defaultStatus')
                        ->label('Varsayılan yayın durumu')
                        ->options(['pending'=>'Onay beklesin','active'=>'Doğrudan yayınla','passive'=>'Pasif'])
                        ->required(),
                    Toggle::make('autoCreateTaxonomies')
                        ->label('Eksik kategori, şehir ve ilçeleri oluştur')
                        ->helperText('Kapalıysa eşleşmeyen satırlar hata raporuna yazılır.'),
                ])->columns(2),
        ])->statePath('data');
    }

    public function preview(CompanyImportService $service): void
    {
        $data = $this->form->getState();
        try {
            $path = Storage::disk('local')->path($data['importFile']);
            $this->previewRows = $service->preview($path, $data['directoryIds'] ?? []);
            Notification::make()->title('Ön izleme hazır')->body('İlk ' . count($this->previewRows) . ' veri satırı kontrol edildi.')->success()->send();
        } catch (\Throwable $exception) {
            $this->previewRows = [];
            Notification::make()->title('Dosya okunamadı')->body($exception->getMessage())->danger()->send();
        }
    }

    public function startImport(): void
    {
        $data = $this->form->getState();
        $storedPath = $data['importFile'];
        $batch = CompanyImportBatch::create([
            'user_id' => auth()->id(),
            'filename' => basename($storedPath),
            'stored_path' => $storedPath,
            'status' => 'pending',
            'duplicate_strategy' => $data['duplicateStrategy'],
            'default_status' => $data['defaultStatus'],
            'options' => [
                'directory_ids' => array_map('intval', $data['directoryIds'] ?? []),
                'auto_create_taxonomies' => (bool) ($data['autoCreateTaxonomies'] ?? false),
            ],
            'stats' => ['rows'=>0,'created'=>0,'updated'=>0,'skipped'=>0,'failed'=>0],
        ]);

        ProcessCompanyImport::dispatch($batch->id)->onQueue('imports');
        $this->previewRows = [];
        Notification::make()->title('İçe aktarma kuyruğa alındı')->body("Batch #{$batch->id} arka planda işlenecek.")->success()->send();
    }

    public function rollbackBatch(int $batchId): void
    {
        $batch = CompanyImportBatch::where('status', 'completed')->findOrFail($batchId);
        RollbackCompanyImport::dispatch($batch->id)->onQueue('imports');
        Notification::make()->title('Geri alma kuyruğa alındı')->warning()->send();
    }

    public function downloadTemplate(): StreamedResponse
    {
        return response()->streamDownload(function () {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setTitle('Firmalar');
            $sheet->fromArray(CompanyImportService::COLUMNS, null, 'A1');
            $sheet->fromArray([
                'FRM-001', 'hizmetyakinda.com.tr', 'Örnek Firma', 'Diş Kliniği', 'İstanbul', 'Kadıköy',
                '0212 555 00 00', '905325550000', 'info@ornek.com', 'https://ornek.com', 'Örnek Mah. No:1',
                '', "Pazartesi: 09:00 - 18:00", 'Kısa firma açıklaması', 'Detaylı firma açıklaması', '', 'pending',
            ], null, 'A2');
            $sheet->freezePane('A2');
            $sheet->getStyle('A1:Q1')->getFont()->setBold(true);
            foreach (range('A', 'Q') as $column) $sheet->getColumnDimension($column)->setAutoSize(true);
            (new Xlsx($spreadsheet))->save('php://output');
            $spreadsheet->disconnectWorksheets();
        }, 'toplu-firma-sablonu.xlsx', ['Content-Type'=>'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']);
    }

    public function downloadErrors(int $batchId): StreamedResponse
    {
        $batch = CompanyImportBatch::findOrFail($batchId);
        return response()->streamDownload(function () use ($batch) {
            $output = fopen('php://output', 'wb');
            fputcsv($output, ['batch_id', 'hata'], ';');
            foreach ($batch->errors ?? [] as $error) fputcsv($output, [$batch->id, $error], ';');
            fclose($output);
        }, 'firma-import-hatalari-' . $batch->id . '.csv', ['Content-Type'=>'text/csv; charset=UTF-8']);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('template')->label('Excel şablonunu indir')->icon('heroicon-o-arrow-down-tray')->action(fn() => $this->downloadTemplate()),
            Actions\Action::make('discover')->label('Firma keşfet')->icon('heroicon-o-magnifying-glass')->url(fn() => DiscoveredCompanyResource::getUrl('discover')),
        ];
    }

    protected function getViewData(): array
    {
        return ['batches' => CompanyImportBatch::latest()->take(20)->get()];
    }
}
