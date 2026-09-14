<?php

namespace App\Filament\Resources\ListingRequests\Tables;

use App\Models\Directory;
use App\Models\ListingRequest;
use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ListingRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('company_name')
                    ->label('Firma Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('claimCompany.name')
                    ->label('Talep Türü')
                    ->formatStateUsing(fn (?string $state): string => $state ? 'Profil sahiplenme' : 'Yeni firma')
                    ->badge()
                    ->color(fn (?string $state): string => $state ? 'warning' : 'gray'),
                TextColumn::make('directory.name')
                    ->label('Geldiği Rehber')
                    ->badge()
                    ->placeholder('Eski kayıt - rehber seçilmemiş')
                    ->color(fn ($state): string => filled($state) ? 'info' : 'danger')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('contact_name')
                    ->label('Yetkili')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('category.name')
                    ->label('Kategori'),
                TextColumn::make('city.name')
                    ->label('Şehir'),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'new' => 'Yeni',
                        'reviewed' => 'İncelendi',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => $state,
                    })
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'reviewed' => 'info',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Tarih')
                    ->dateTime('d.m.Y H:i')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'new' => 'Yeni',
                        'reviewed' => 'İncelendi',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                    ]),
                SelectFilter::make('directory_id')
                    ->label('Rehber')
                    ->options(fn (): array => Directory::query()
                        ->orderBy('name')
                        ->pluck('name', 'id')
                        ->all()),
            ])
            ->recordActions([
                Action::make('approve')
                    ->label(fn (ListingRequest $record): string => $record->claim_company_id ? 'Onayla → Profili Güncelle' : 'Onayla → Firmaya Dönüştür')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading(fn (ListingRequest $record): string => $record->claim_company_id ? 'Profil Sahiplenme Talebini Onayla' : 'Firma Kaydına Dönüştür')
                    ->modalDescription(fn (ListingRequest $record): string => $record->directory
                        ? ($record->claimCompany
                            ? ($record->claimCompany->directory_id === null
                                ? "{$record->claimCompany->name} için {$record->directory->name} rehberine özel firma profili oluşturulacak. Onaylıyor musunuz?"
                                : "{$record->claimCompany->name} profili, talepteki bilgilerle güncellenecek. Onaylıyor musunuz?")
                            : "Firma yalnızca {$record->directory->name} rehberine eklenecek. Onaylıyor musunuz?")
                        : 'Bu eski talepte kaynak rehber kayıtlı değil. Firmanın ekleneceği rehberi seçin.')
                    ->form([
                        Select::make('directory_id')
                            ->label('Hedef Rehber')
                            ->options(fn (): array => Directory::query()
                                ->where('status', 'active')
                                ->orderBy('name')
                                ->pluck('name', 'id')
                                ->all())
                            ->searchable()
                            ->preload()
                            ->required()
                            ->default(fn (ListingRequest $record): ?int => $record->directory_id)
                            ->disabled(fn (ListingRequest $record): bool => filled($record->directory_id))
                            ->dehydrated(),
                    ])
                    ->action(function (ListingRequest $record, array $data) {
                        $directoryId = $record->directory_id ?: (int) $data['directory_id'];
                        $company = $record->approveToCompany($directoryId);

                        Notification::make()
                            ->title($record->claim_company_id ? 'Firma profili güncellendi!' : 'Firma oluşturuldu!')
                            ->success()
                            ->body($record->claim_company_id
                                ? "\"{$company->name}\" profilindeki iletişim bilgileri güncellendi veya rehbere özel profili oluşturuldu."
                                : "\"{$company->name}\" firması başarıyla eklendi.")
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'new' || $record->status === 'reviewed'),

                Action::make('reject')
                    ->label('Reddet')
                    ->icon('heroicon-o-x-circle')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Talebi Reddet')
                    ->modalDescription('Bu talebi reddetmek istediğinize emin misiniz?')
                    ->action(function ($record) {
                        $record->update(['status' => 'rejected']);
                        Notification::make()
                            ->title('Talep reddedildi.')
                            ->warning()
                            ->send();
                    })
                    ->visible(fn ($record) => $record->status === 'new' || $record->status === 'reviewed'),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
