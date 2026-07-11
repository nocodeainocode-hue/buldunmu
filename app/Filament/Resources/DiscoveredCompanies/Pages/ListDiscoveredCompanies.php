<?php

namespace App\Filament\Resources\DiscoveredCompanies\Pages;

use App\Filament\Resources\DiscoveredCompanies\DiscoveredCompanyResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Filament\Tables\Table;
use App\Models\DiscoveredCompany;
use Illuminate\Database\Eloquent\Builder;

class ListDiscoveredCompanies extends ListRecords
{
    protected static string $resource = DiscoveredCompanyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('import')
                ->label('Toplu Firma Merkezi')
                ->icon('heroicon-o-arrow-up-tray')
                ->color('success')
                ->url(fn() => static::getResource()::getUrl('import')),
            Actions\Action::make('discover')
                ->label('Yeni Keşif')
                ->icon('heroicon-o-magnifying-glass')
                ->color('primary')
                ->url(fn() => static::getResource()::getUrl('discover')),
        ];
    }

    public function table(Table $table): Table
    {
        return parent::table($table)
            ->actions([
                Actions\ActionGroup::make([
                    Actions\Action::make('approve')
                        ->label('Onayla')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                        ->action(function (DiscoveredCompany $record) {
                            $record->approve();
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Firmayı Onayla')
                        ->modalDescription(fn(DiscoveredCompany $record) => "{$record->name} firması onaylanıp firma listesine eklenecek. Onaylıyor musunuz?"),
                    Actions\Action::make('reject')
                        ->label('Reddet')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                        ->action(function (DiscoveredCompany $record) {
                            $record->update(['status' => 'rejected']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Firmayı Reddet')
                        ->modalDescription(fn(DiscoveredCompany $record) => "{$record->name} firması reddedilecek. Onaylıyor musunuz?"),
                    Actions\EditAction::make('edit')
                        ->label('Düzenle')
                        ->icon('heroicon-o-pencil')
                        ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->label('Firma Adı')
                                ->required(),
                            \Filament\Forms\Components\TextInput::make('phone')
                                ->label('Telefon')
                                ->tel(),
                            \Filament\Forms\Components\TextInput::make('email')
                                ->label('E-posta')
                                ->email(),
                            \Filament\Forms\Components\TextInput::make('website')
                                ->label('Web Sitesi')
                                ->url(),
                            \Filament\Forms\Components\Textarea::make('address')
                                ->label('Adres'),
                            \Filament\Forms\Components\Textarea::make('description')
                                ->label('Açıklama'),
                        ])
                        ->action(function (DiscoveredCompany $record, array $data) {
                            $record->update($data);
                        }),
                    Actions\Action::make('approve_with_edit')
                        ->label('Düzenle ve Onayla')
                        ->icon('heroicon-o-pencil-square')
                        ->color('success')
                        ->visible(fn(DiscoveredCompany $record) => $record->status === 'pending')
                        ->form([
                            \Filament\Forms\Components\TextInput::make('name')
                                ->label('Firma Adı')
                                ->required()
                                ->default(fn(DiscoveredCompany $record) => $record->name),
                            \Filament\Forms\Components\TextInput::make('phone')
                                ->label('Telefon')
                                ->tel()
                                ->default(fn(DiscoveredCompany $record) => $record->phone),
                            \Filament\Forms\Components\TextInput::make('email')
                                ->label('E-posta')
                                ->email()
                                ->default(fn(DiscoveredCompany $record) => $record->email),
                            \Filament\Forms\Components\TextInput::make('website')
                                ->label('Web Sitesi')
                                ->url()
                                ->default(fn(DiscoveredCompany $record) => $record->website),
                            \Filament\Forms\Components\Textarea::make('address')
                                ->label('Adres')
                                ->default(fn(DiscoveredCompany $record) => $record->address),
                            \Filament\Forms\Components\Textarea::make('description')
                                ->label('Açıklama')
                                ->default(fn(DiscoveredCompany $record) => $record->description),
                            \Filament\Forms\Components\Select::make('category_id')
                                ->label('Kategori')
                                ->options(\App\Models\Category::pluck('name', 'id'))
                                ->searchable()
                                ->preload(),
                            \Filament\Forms\Components\Select::make('city_id')
                                ->label('Şehir')
                                ->options(\App\Models\City::pluck('name', 'id'))
                                ->searchable()
                                ->preload(),
                        ])
                        ->action(function (DiscoveredCompany $record, array $data) {
                            $record->approve($data);
                        }),
                    Actions\DeleteAction::make()
                        ->label('Sil'),
                ]),
            ])
            ->bulkActions([
                Actions\BulkActionGroup::make([
                    Actions\BulkAction::make('bulk_approve')
                        ->label('Toplu Onayla')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            foreach ($records as $record) {
                                if ($record->status === 'pending') {
                                    try {
                                        $record->approve();
                                    } catch (\Exception $e) {
                                        // Skip failures in bulk
                                    }
                                }
                            }
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Firmaları Onayla')
                        ->modalDescription('Seçili tüm firmalar onaylanıp firma listesine eklenecek. Devam edilsin mi?'),
                    Actions\BulkAction::make('bulk_reject')
                        ->label('Toplu Reddet')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(function (\Illuminate\Support\Collection $records) {
                            DiscoveredCompany::whereIn('id', $records->pluck('id'))
                                ->where('status', 'pending')
                                ->update(['status' => 'rejected']);
                        })
                        ->requiresConfirmation()
                        ->modalHeading('Seçili Firmaları Reddet')
                        ->modalDescription('Seçili tüm firmalar reddedilecek. Devam edilsin mi?'),
                    Actions\DeleteBulkAction::make()
                        ->label('Toplu Sil'),
                ]),
            ]);
    }
}
