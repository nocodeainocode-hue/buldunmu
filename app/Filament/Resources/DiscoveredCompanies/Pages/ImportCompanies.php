<?php

namespace App\Filament\Resources\DiscoveredCompanies\Pages;

use App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource;
use App\Models\Category;
use App\Models\City;
use App\Models\Company;
use Filament\Schemas\Schema;
use Filament\Actions;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use League\Csv\Reader;

class ImportCompanies extends Page
{
    use InteractsWithForms;

    protected static string $resource = DiscoveredCompanyResource::class;

    protected string $view = 'filament.resources.discovered-companies.import';

    protected static ?string $title = 'Toplu Firma İçe Aktar';

    protected static ?string $navigationLabel = 'CSV ile İçe Aktar';

    public ?string $importFile = null;
    public ?int $defaultCategoryId = null;
    public ?int $defaultCityId = null;
    public bool $autoApprove = false;
    public bool $hasImported = false;
    public int $importedCount = 0;
    public int $skippedCount = 0;
    public array $errors = [];

    public function mount(): void
    {
        $this->form->fill([
            'defaultCategoryId' => null,
            'defaultCityId' => null,
            'autoApprove' => false,
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('CSV Dosyası Yükle')
                    ->description('Firma listesini içeren bir CSV dosyası yükleyin. CSV başlıkları: name, phone, email, website, address, description, category, city')
                    ->schema([
                        FileUpload::make('importFile')
                            ->label('CSV Dosyası')
                            ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv'])
                            ->maxSize(10240)
                            ->disk('local')
                            ->directory('imports')
                            ->required()
                            ->helperText('Max 10MB. İlk satır başlık olmalıdır.'),
                        Select::make('defaultCategoryId')
                            ->label('Varsayılan Kategori')
                            ->options(fn() => Category::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->helperText('CSV\'de belirtilmeyen firmalar için varsayılan kategori'),
                        Select::make('defaultCityId')
                            ->label('Varsayılan Şehir')
                            ->options(fn() => City::pluck('name', 'id')->toArray())
                            ->searchable()
                            ->preload()
                            ->helperText('CSV\'de belirtilmeyen firmalar için varsayılan şehir'),
                        Toggle::make('autoApprove')
                            ->label('Firmaları otomatik onayla')
                            ->helperText('Aktif edildiğinde firmalar doğrudan yayına alınır'),
                    ])
                    ->columns(2),
            ])
            ->statePath('data');
    }

    public function import(): void
    {
        $data = $this->form->getState();

        if (empty($data['importFile'])) {
            Notification::make()->title('Lütfen bir CSV dosyası seçin.')->danger()->send();
            return;
        }

        $this->importedCount = 0;
        $this->skippedCount = 0;
        $this->errors = [];

        try {
            $filePath = Storage::disk('local')->path($data['importFile']);
            $csv = Reader::createFromPath($filePath, 'r');
            $csv->setHeaderOffset(0);
            $csv->setDelimiter(',');

            $directoryId = app()->bound('currentDirectory')
                ? app('currentDirectory')->id
                : \App\Models\Directory::first()?->id ?? 1;

            foreach ($csv as $index => $row) {
                try {
                    $name = trim($row['name'] ?? '');
                    if (empty($name)) {
                        $this->skippedCount++;
                        $this->errors[] = "Satır {$index}: Firma adı boş, atlandı.";
                        continue;
                    }

                    $slug = Str::slug($name);
                    $baseSlug = $slug;
                    $counter = 1;
                    while (Company::where('slug', $slug)->exists()) {
                        $slug = $baseSlug . '-' . $counter++;
                    }

                    // Resolve category
                    $categoryId = $data['defaultCategoryId'] ?? null;
                    if (!empty($row['category'] ?? '')) {
                        $cat = Category::where('name', 'like', '%' . trim($row['category']) . '%')->first();
                        if ($cat) $categoryId = $cat->id;
                    }

                    // Resolve city
                    $cityId = $data['defaultCityId'] ?? null;
                    if (!empty($row['city'] ?? '')) {
                        $ct = City::where('name', 'like', '%' . trim($row['city']) . '%')->first();
                        if ($ct) $cityId = $ct->id;
                    }

                    Company::create([
                        'name' => $name,
                        'slug' => $slug,
                        'category_id' => $categoryId ?? 1,
                        'city_id' => $cityId ?? 1,
                        'phone' => trim($row['phone'] ?? '') ?: null,
                        'email' => trim($row['email'] ?? '') ?: null,
                        'website' => trim($row['website'] ?? '') ?: null,
                        'address' => trim($row['address'] ?? '') ?: null,
                        'description' => trim($row['description'] ?? '') ?: null,
                        'status' => $data['autoApprove'] ? 'active' : 'pending',
                        'directory_id' => $directoryId,
                    ]);

                    $this->importedCount++;

                } catch (\Exception $e) {
                    $this->skippedCount++;
                    $this->errors[] = "Satır {$index}: " . $e->getMessage();
                }
            }

            $this->hasImported = true;

            // Clean up temp file
            Storage::disk('local')->delete($data['importFile']);

            Notification::make()
                ->title('İçe aktarma tamamlandı!')
                ->body("{$this->importedCount} firma başarıyla eklendi. {$this->skippedCount} atlandı.")
                ->success()
                ->send();

        } catch (\Exception $e) {
            Notification::make()
                ->title('İçe aktarma hatası')
                ->body($e->getMessage())
                ->danger()
                ->send();
        }
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('go_to_discover')
                ->label('Firma Keşfet')
                ->icon('heroicon-o-magnifying-glass')
                ->color('gray')
                ->url(fn() => DiscoveredCompanyResource::getUrl('discover')),
        ];
    }

    protected function getViewData(): array
    {
        return [
            'importedCount' => $this->importedCount,
            'skippedCount' => $this->skippedCount,
            'errors' => $this->errors,
            'hasImported' => $this->hasImported,
        ];
    }
}
