<?php

namespace App\Filament\Resources\DiscoveredCompanies\Pages;

use App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource;
use App\Jobs\DiscoverCompaniesJob;
use App\Models\DiscoveredCompany;
use Filament\Actions;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Forms\Form;
use Filament\Schemas\Schema;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Actions\BulkAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;

class DiscoverCompanies extends Page implements HasForms, HasTable
{
    use InteractsWithForms;
    use InteractsWithTable;

    protected static string $resource = DiscoveredCompanyResource::class;

    protected string $view = 'filament.resources.discovered-companies.discover';

    protected static ?string $title = 'Toplu Firma Keşfi';

    protected static ?string $navigationLabel = 'Yeni Keşif';

    /**
     * Cached discovery results for the table.
     */
    public array $discoveredResults = [];

    public ?string $keyword = '';
    public ?string $city = '';
    public ?string $source = 'google_maps';
    public ?string $customUrl = '';
    public bool $hasSearched = false;
    public bool $isSearching = false;

    public function mount(): void
    {
        $this->form->fill([
            'keyword' => '',
            'city' => '',
            'source' => 'google_maps',
            'customUrl' => '',
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                \Filament\Schemas\Components\Section::make('Keşif Ayarları')
                    ->description('Firma keşfi için kaynak ve anahtar kelime girin.')
                    ->schema([
                        Select::make('source')
                            ->label('Kaynak')
                            ->options([
                                'google_maps' => 'Google Maps',
                                'search' => 'Web Arama',
                                'custom_url' => 'Özel URL',
                            ])
                            ->default('google_maps')
                            ->live()
                            ->required(),
                        TextInput::make('keyword')
                            ->label('Anahtar Kelime')
                            ->placeholder('örn: restoran, berber, avukat')
                            ->requiredUnless('source', 'custom_url')
                            ->helperText('Hangi tür firmaları arıyorsunuz?'),
                        TextInput::make('city')
                            ->label('Şehir')
                            ->placeholder('örn: İstanbul, Ankara')
                            ->requiredUnless('source', 'custom_url')
                            ->helperText('Hangi şehirde arama yapılsın?'),
                        TextInput::make('customUrl')
                            ->label('Özel URL')
                            ->url()
                            ->placeholder('https://example.com/firmalar')
                            ->visible(fn($get) => $get('source') === 'custom_url')
                            ->requiredIf('source', 'custom_url')
                            ->helperText('Kazınacak sayfanın tam URL\'si'),
                    ])
                    ->columns(2),
            ]);
    }

    public function discover(): void
    {
        $data = $this->form->getState();

        $directoryId = app()->bound('currentDirectory')
            ? app('currentDirectory')->id
            : \App\Models\Directory::first()?->id ?? 1;

        $userId = auth()->id();

        // Dispatch the discovery job to the queue (non-blocking)
        DiscoverCompaniesJob::dispatch(
            data: [
                'keyword' => $data['keyword'] ?? '',
                'city' => $data['city'] ?? '',
                'source' => $data['source'],
                'customUrl' => $data['customUrl'] ?? null,
            ],
            userId: $userId,
            directoryId: $directoryId,
        );

        $this->keyword = $data['keyword'] ?? '';
        $this->city = $data['city'] ?? '';
        $this->source = $data['source'] ?? 'google_maps';
        $this->customUrl = $data['customUrl'] ?? '';
        $this->hasSearched = true;

        $label = $data['source'] === 'custom_url'
            ? $data['customUrl']
            : "{$data['keyword']} - {$data['city']}";

        Notification::make()
            ->title('Keşif kuyruğa alındı!')
            ->body("\"{$label}\" araması arka planda başlatıldı. Sonuçlar hazır olduğunda bildirim alacaksınız.")
            ->success()
            ->send();
    }

    /**
     * Approve all discovered results in bulk.
     */
    public function approveAll(): void
    {
        $pending = DiscoveredCompany::pending()
            ->where('search_keyword', $this->keyword)
            ->where('search_city', $this->city)
            ->get();

        $approved = 0;
        foreach ($pending as $record) {
            try {
                $record->approve();
                $approved++;
            } catch (\Exception $e) {
                // Skip failures
            }
        }

        Notification::make()
            ->title('Toplu onay tamamlandı!')
            ->body("{$approved} firma onaylanarak rehbere eklendi.")
            ->success()
            ->send();
    }

    /**
     * Bulk approve with category/city assignment wizard.
     */
    public ?int $bulkCategoryId = null;
    public ?int $bulkCityId = null;
    public ?int $bulkDistrictId = null;

    public function approveAllWithCategory(): void
    {
        $pending = DiscoveredCompany::pending()
            ->where('search_keyword', $this->keyword)
            ->where('search_city', $this->city)
            ->get();

        $categoryId = $this->bulkCategoryId;
        $cityId = $this->bulkCityId;
        $districtId = $this->bulkDistrictId;

        if (!$categoryId) {
            Notification::make()
                ->title('Lütfen bir kategori seçin.')
                ->danger()
                ->send();
            return;
        }

        $approved = 0;
        foreach ($pending as $record) {
            try {
                $overrides = ['category_id' => (int) $categoryId];
                if ($cityId) $overrides['city_id'] = (int) $cityId;
                if ($districtId) $overrides['district_id'] = (int) $districtId;
                $record->approve($overrides);
                $approved++;
            } catch (\Exception $e) {
                // Skip failures
            }
        }

        $this->bulkCategoryId = null;
        $this->bulkCityId = null;
        $this->bulkDistrictId = null;

        $catName = \App\Models\Category::find($categoryId)?->name ?? 'seçili kategori';

        Notification::make()
            ->title('Kategorili toplu onay tamamlandı!')
            ->body("{$approved} firma \"{$catName}\" kategorisiyle rehbere eklendi.")
            ->success()
            ->send();
    }

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DiscoveredCompany::query()
                    ->when($this->hasSearched, function ($query) {
                        $query->where('search_keyword', $this->keyword)
                              ->where('search_city', $this->city);
                    })
                    ->when(!$this->hasSearched, fn($q) => $q->whereRaw('1=0'))
                    ->latest()
            )
            ->columns([
                ImageColumn::make('logo_url')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name ?? '?') . '&size=64&background=6366f1&color=fff'),
                TextColumn::make('name')
                    ->label('Firma Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(),
                TextColumn::make('address')
                    ->label('Adres')
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('website')
                    ->label('Web Sitesi')
                    ->toggleable(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'pending' => 'Onay Bekliyor',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->filters([])
            ->actions([
                \Filament\Actions\Action::make('approve_single')
                    ->label('Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                    ->action(function (DiscoveredCompany $record) {
                        $record->approve();
                        Notification::make()->title('Firma onaylandı!')->success()->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Firmayı Onayla')
                    ->modalDescription(fn(DiscoveredCompany $record) => "{$record->name} firması onaylanıp rehbere eklenecek."),
                \Filament\Actions\Action::make('approve_with_details')
                    ->label('Detaylı Onayla')
                    ->icon('heroicon-o-pencil-square')
                    ->color('success')
                    ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                    ->form([
                        TextInput::make('name')->label('Firma Adı')->required()->default(fn($record) => $record->name),
                        TextInput::make('phone')->label('Telefon')->tel()->default(fn($record) => $record->phone),
                        TextInput::make('email')->label('E-posta')->email()->default(fn($record) => $record->email),
                        TextInput::make('website')->label('Web Sitesi')->url()->default(fn($record) => $record->website),
                        Textarea::make('address')->label('Adres')->default(fn($record) => $record->address),
                        Textarea::make('description')->label('Açıklama')->default(fn($record) => $record->description),
                        Select::make('category_id')
                            ->label('Kategori')
                            ->options(\App\Models\Category::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                        Select::make('city_id')
                            ->label('Şehir')
                            ->options(\App\Models\City::pluck('name', 'id'))
                            ->searchable()
                            ->preload(),
                    ])
                    ->action(function (DiscoveredCompany $record, array $data) {
                        $categoryId = $data['category_id'] ?? null;
                        $cityId = $data['city_id'] ?? null;
                        unset($data['category_id'], $data['city_id']);
                        $company = $record->approve($data);
                        if ($categoryId) $company->update(['category_id' => $categoryId]);
                        if ($cityId) $company->update(['city_id' => $cityId]);
                        Notification::make()->title('Firma detaylı onaylandı!')->success()->send();
                    }),
                \Filament\Actions\Action::make('reject_single')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                    ->action(fn(DiscoveredCompany $record) => $record->update(['status' => 'rejected']))
                    ->requiresConfirmation(),
                \Filament\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                BulkAction::make('bulk_approve')
                    ->label('Toplu Onayla')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->action(function (Collection $records) {
                        $count = 0;
                        foreach ($records as $record) {
                            if ($record->status === 'pending') {
                                try { $record->approve(); $count++; } catch (\Exception $e) {}
                            }
                        }
                        Notification::make()->title("{$count} firma onaylandı!")->success()->send();
                    })
                    ->requiresConfirmation()
                    ->modalHeading('Seçili Firmaları Onayla')
                    ->modalDescription('Seçili tüm firmalar onaylanıp rehbere eklenecek.'),
                BulkAction::make('bulk_reject')
                    ->label('Toplu Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->action(function (Collection $records) {
                        DiscoveredCompany::whereIn('id', $records->pluck('id'))
                            ->where('status', 'pending')
                            ->update(['status' => 'rejected']);
                        Notification::make()->title('Seçili firmalar reddedildi.')->success()->send();
                    })
                    ->requiresConfirmation(),
                \Filament\Actions\DeleteBulkAction::make(),
            ])
            ->emptyStateHeading('Keşif Sonucu Bekleniyor')
            ->emptyStateDescription('Yukarıdaki formu doldurup "Keşfet" butonuna tıklayarak firma araması yapabilirsiniz.')
            ->emptyStateIcon('heroicon-o-magnifying-glass')
            ->defaultSort('created_at', 'desc')
            ->paginated([10, 25, 50, 100]);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('go_to_list')
                ->label('Keşfedilenler Listesi')
                ->icon('heroicon-o-list-bullet')
                ->color('gray')
                ->url(fn() => DiscoveredCompanyResource::getUrl('index')),
        ];
    }
}
