<?php

namespace App\Filament\Resources\ListingRequests\Tables;

use Filament\Actions\Action;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

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
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'new' => 'Yeni',
                        'reviewed' => 'İncelendi',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
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
            ])
            ->recordActions([
                Action::make('approve')
                    ->label('Onayla → Firmaya Dönüştür')
                    ->icon('heroicon-o-check-circle')
                    ->color('success')
                    ->requiresConfirmation()
                    ->modalHeading('Firma Kaydına Dönüştür')
                    ->modalDescription('Bu talep onaylanacak ve yeni bir firma kaydı oluşturulacak. Onaylıyor musunuz?')
                    ->action(function ($record) {
                        $record->update(['status' => 'reviewed']);

                        $company = \App\Models\Company::create([
                            'name' => $record->company_name,
                            'directory_id' => $record->directory_id,
                            'category_id' => $record->category_id,
                            'city_id' => $record->city_id,
                            'district_id' => $record->district_id,
                            'phone' => $record->phone,
                            'whatsapp' => $record->whatsapp,
                            'email' => $record->email,
                            'website' => $record->website,
                            'status' => 'active',
                        ]);

                        $record->update(['status' => 'approved']);

                        Notification::make()
                            ->title('Firma oluşturuldu!')
                            ->success()
                            ->body("\"{$company->name}\" firması başarıyla eklendi.")
                            ->send();
                    })
                    ->visible(fn($record) => $record->status === 'new' || $record->status === 'reviewed'),

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
                    ->visible(fn($record) => $record->status === 'new' || $record->status === 'reviewed'),

                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
