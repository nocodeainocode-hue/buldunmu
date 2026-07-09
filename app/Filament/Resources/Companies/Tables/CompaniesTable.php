<?php

namespace App\Filament\Resources\Companies\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->circular()
                    ->defaultImageUrl(fn($record) => 'https://ui-avatars.com/api/?name=' . urlencode($record->name) . '&size=64&background=6366f1&color=fff'),
                TextColumn::make('name')
                    ->label('Firma Adı')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->sortable(),
                TextColumn::make('city.name')
                    ->label('Şehir')
                    ->sortable(),
                TextColumn::make('phone')
                    ->label('Telefon')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),
                IconColumn::make('is_premium')
                    ->label('Premium')
                    ->boolean(),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'active' => 'Aktif',
                        'passive' => 'Pasif',
                        'pending' => 'Onay Bekliyor',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'active' => 'success',
                        'passive' => 'danger',
                        'pending' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('created_at')
                    ->label('Kayıt Tarihi')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Onay Bekliyor',
                        'active' => 'Aktif',
                        'passive' => 'Pasif',
                    ]),
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name'),
                SelectFilter::make('city_id')
                    ->label('Şehir')
                    ->relationship('city', 'name'),
                SelectFilter::make('is_premium')
                    ->label('Premium')
                    ->options([
                        '1' => 'Premium',
                        '0' => 'Standart',
                    ]),
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    \Filament\Actions\Action::make('activate')
                        ->label('Aktif Et')
                        ->icon('heroicon-o-check-circle')
                        ->action(fn($records) => $records->each->update(['status' => 'active'])),
                    \Filament\Actions\Action::make('deactivate')
                        ->label('Pasif Et')
                        ->icon('heroicon-o-x-circle')
                        ->color('danger')
                        ->action(fn($records) => $records->each->update(['status' => 'passive'])),
                ]),
            ]);
    }
}
