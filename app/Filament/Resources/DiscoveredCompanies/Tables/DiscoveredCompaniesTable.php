<?php

namespace App\Filament\Resources\DiscoveredCompanies\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DiscoveredCompaniesTable
{
    public static function configure(Table $table): Table
    {
        return $table
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
                    ->searchable()
                    ->limit(40)
                    ->toggleable(),
                TextColumn::make('website')
                    ->label('Web Sitesi')
                    ->url(fn($record) => $record->website, true)
                    ->toggleable(),
                TextColumn::make('source_url')
                    ->label('Kaynak Kaydı')
                    ->url(fn($record) => $record->source_url, true)
                    ->formatStateUsing(fn(?string $state): string => $state ? 'Aç' : '-')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('email')
                    ->label('E-posta')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('source')
                    ->label('Kaynak')
                    ->badge()
                    ->formatStateUsing(fn(string $state): string => match ($state) {
                        'google_maps' => 'Google Maps',
                        'openstreetmap' => 'OpenStreetMap',
                        'search' => 'Web Arama',
                        'custom_url' => 'Özel URL',
                        default => $state,
                    })
                    ->color(fn(string $state): string => match ($state) {
                        'google_maps' => 'success',
                        'openstreetmap' => 'info',
                        'search' => 'info',
                        'custom_url' => 'warning',
                        default => 'gray',
                    }),
                TextColumn::make('search_keyword')
                    ->label('Anahtar Kelime')
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('search_city')
                    ->label('Şehir')
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    ->label('Keşif Tarihi')
                    ->dateTime('d.m.Y H:i')
                    ->sortable()
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Durum')
                    ->options([
                        'pending' => 'Onay Bekliyor',
                        'approved' => 'Onaylandı',
                        'rejected' => 'Reddedildi',
                    ]),
                SelectFilter::make('source')
                    ->label('Kaynak')
                    ->options([
                        'google_maps' => 'Google Maps',
                        'openstreetmap' => 'OpenStreetMap',
                        'search' => 'Web Arama',
                        'custom_url' => 'Özel URL',
                    ]),
            ])
            ->defaultSort('created_at', 'desc');
    }
}
