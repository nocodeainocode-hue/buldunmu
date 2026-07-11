<?php

namespace App\Filament\Resources\Directories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use App\View\Helpers\ThemeHelper;

class DirectoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Rehber')
                    ->description(fn($record) => $record->slug)
                    ->searchable(),
                TextColumn::make('domain')
                    ->label('Domain')
                    ->url(fn($record) => 'https://' . $record->domain, true)
                    ->searchable(),
                TextColumn::make('template')
                    ->label('Tema')
                    ->badge()
                    ->formatStateUsing(fn($state) => ThemeHelper::TEMPLATES[$state]['name'] ?? $state),
                TextColumn::make('slug_pattern')
                    ->label('Slug Deseni')
                    ->fontFamily('mono')
                    ->copyable(),
                TextColumn::make('companies_count')
                    ->label('Firma')
                    ->counts('companies')
                    ->sortable(),
                TextColumn::make('plan')
                    ->label('Plan')
                    ->badge()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('status')
                    ->label('Durum')
                    ->badge()
                    ->formatStateUsing(fn($state) => $state === 'active' ? 'Aktif' : 'Pasif')
                    ->color(fn($state) => $state === 'active' ? 'success' : 'gray'),
                TextColumn::make('expires_at')
                    ->label('Bitiş')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_title')
                    ->label('Meta Başlık')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('meta_description')
                    ->label('Meta Açıklama')
                    ->limit(50)
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('created_at')
                    ->label('Oluşturulma')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Güncelleme')
                    ->dateTime('d.m.Y')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('name')
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
